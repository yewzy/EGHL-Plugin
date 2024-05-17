<?php

namespace Omnipay\eGHL;

use Omnipay\Common\AbstractGateway;
use eGHL\Hashing\eGHL_Hash;
use eGHL\ParamValidator\PaymentRequest;
use eGHL\MerchantAPI\core\APIFactory;
use eGHL\Exception;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'eGHL';
    }

    public function getDefaultParameters()
    {
        return [
            'merchantId' => '',
            'merchantPassword' => '',
            'testMode' => false
        ];
    }

    public function getMode()
    {
        return $this->getParameter('testMode');
    }

    public function setTestMode($isTest = true)
    {
        return $this->setParameter('testMode', $isTest);
    }
	
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getMerchantPassword()
    {
        return $this->getParameter('merchantPassword');
    }

    public function setMerchantPassword($merchantPassword)
    {
        return $this->setParameter('merchantPassword', $merchantPassword);
    }

    public function purchase(array $parameters = array())
    {
        try{
            // Formatted Params
            $parameters['Amount'] = number_format($parameters['Amount'],2, '.' , ''); // format the amount to 2 decimal places
            
            // Additional computed params
            $parameters['TransactionType'] = isset($parameters['TransactionType'])?$parameters['TransactionType']:'SALE';
            $parameters['ServiceID'] = $this->getMerchantId();
            $parameters['CustIP'] = isset($parameters['CustIP'])?$parameters['CustIP']:$this->get_client_ip();

            if(!isset($parameters['PaymentID'])){
                $parameters['PaymentID'] = $this->genPaymentID();
            }
            
            // Compute Request hash to be sent
            $parameters['HashValue'] = $this->computeRequestHash($parameters);

            $Request = new PaymentRequest($parameters);
            $parameters = $Request->validate();

            $PurchaseRequest = $this->createRequest('\Omnipay\eGHL\Message\PurchaseRequest', $parameters);
            $PurchaseRequest->setAmount($parameters['Amount']);
            $PurchaseRequest->setCurrency($parameters['CurrencyCode']);
            $PurchaseRequest->setDescription($parameters['PaymentDesc']);
            $PurchaseRequest->setTransactionId($parameters['PaymentID']);
            $PurchaseRequest->setClientIp($parameters['CustIP']);
            $PurchaseRequest->setReturnUrl($parameters['MerchantReturnURL']);
            $PurchaseRequest->setNotifyUrl($parameters['MerchantCallBackURL']);
            $PurchaseRequest->setPaymentMethod($parameters['PymtMethod']);
            $PurchaseRequest->setTestMode($this->getMode());

            $PurchaseRequest->addParams($parameters);
            return $PurchaseRequest;
        }
        catch(\Exception $e){
            die("$e");
        }
    }

    public function completePurchase($parameters = array())
    {
        $completePurchaseRequest = $this->createRequest('\Omnipay\eGHL\Message\CompletePurchaseRequest', $parameters);
        $completePurchaseRequest->setParams($parameters);
        return $completePurchaseRequest;
    }

    /**
     * Returns eGHL\MerchantAPI\Capture object if the refund request is successful or pending
     * Throws exception if txn is already captured, not authorised or failed due to any reason
     */
    public function capture($parameters = array()){
        $parameters['ServiceID'] = $this->getMerchantId();
        $Capture_API = APIFactory::create('Capture', $parameters, $this->getMerchantPassword(), $this->getMode());
        if(!$Capture_API->isCaptured()){
            if($Capture_API->isAuthorised()){
                if($Capture_API->isSuccess() || $Capture_API->isPending()){
                    return $Capture_API;
                }
                else{
                    throw new Exception($Capture_API->getResponse()['TxnMessage']);
                }
            }
            else{
                throw new Exception('Transaction not Authorised');
            }
        }
        else{
            throw new Exception('Already Captured');
        }
    }

    /**
     * Returns eGHL\MerchantAPI\Refund object if the refund request is successful or pending
     * Throws exception if txn is already refunded or failed due to any reason
     */
    public function refund($parameters = array()){
        $parameters['ServiceID'] = $this->getMerchantId();
        $Refund_API = APIFactory::create('Refund', $parameters, $this->getMerchantPassword(), $this->getMode());
        if(!$Refund_API->isRefunded()){
            if($Refund_API->isSuccess() || $Refund_API->isPending()){
                return $Refund_API;
            }
            else{
                throw new Exception($Refund_API->getResponse()['TxnMessage']);
            }
        }
        else{
            throw new Exception('Already Refunded');
        }
    }

    /**
     * Returns eGHL\MerchantAPI\Reversal object if the reversal request is successful or pending
     * Throws exception if txn is already reversed or failed due to any reason
     */
    public function void($parameters = array()){
        $parameters['ServiceID'] = $this->getMerchantId();
        $Reversal_API = APIFactory::create('Reversal', $parameters, $this->getMerchantPassword(), $this->getMode());
        if(!$Reversal_API->isReversed()){
            if($Reversal_API->isSuccess() || $Reversal_API->isPending() || $Reversal_API->isReversalPending()){
                return $Reversal_API;
            }
            else{
                throw new Exception($Reversal_API->getResponse()['TxnMessage']);
            }
        }
        else{
            throw new Exception('Already Reversed');
        }
    }

    protected function computeRequestHash($parameters){
        $eGHL_Hash = new eGHL_Hash($parameters);
        $hashValue = $eGHL_Hash->generateHashValueForPaymentInfo('SALE', $this->getMerchantPassword());

        return $hashValue;
    }

    protected function safeUrl($url)
    {
        return str_replace('&', ';', $url);
    }

    // Function to get the client IP address
    protected function get_client_ip() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    protected function GUID($length)
	{
		$output = '';	
		$pseudoBytesLen = 64;
		
		$bytes = openssl_random_pseudo_bytes($pseudoBytesLen);
		$hex_array = str_split(bin2hex($bytes));
		
		for($i=0;$i<$length;$i++){
			$output .= $hex_array[rand ( 0 , ($pseudoBytesLen-1) )];
		}
		return $output;
	}

	protected function genPaymentID($maxLength = 20){
		$timeStamp = time();
		$guid_len = $maxLength - strlen((string)$timeStamp);
		return $timeStamp.$this->GUID($guid_len);
	}    

}