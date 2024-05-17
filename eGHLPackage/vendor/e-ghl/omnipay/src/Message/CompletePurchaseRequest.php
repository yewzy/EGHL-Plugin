<?php

namespace Omnipay\eGHL\Message;

use eGHL\Hashing\eGHL_Hash;
use eGHL\Exception;

class CompletePurchaseRequest extends AbstractRequest
{
    protected $eghl_response = array();

    public function setParams($parameters){
        foreach($parameters as $key=>$value){
            $this->setParameter($key, $value);
        }
    }

    public function sendData($data)
    {
        try{
            $this->ValidateGatewayResponse();
            return new CompletePurchaseResponse($this, $data);
        }
        catch(Exception $e){
            die("$e");
        }
    }

    protected function ValidateGatewayResponse(){
        
        $parameters = $this->eghl_response;

        $eGHL_Hash = new eGHL_Hash($parameters);
        $computed = $eGHL_Hash->generateHashValueForPaymentInfo('HashValue2', $this->getMerchantPassword(), true);

        if(!isset($this->eghl_response['HashValue2']) || $computed != $this->eghl_response['HashValue2']){
            throw new Exception(
                sprintf('Computed HashValue2 [%s] does not match HashValue2 recieved [%s].', $computed, isset($this->eghl_response['HashValue2'])?$this->eghl_response['HashValue2']:'')
            );
        }
        else{
            return true;
        }
    }

    public function getData(){

        $params = $this->getParameters();
        //echo 'getData called:'. print_r($params,1 ); exit;
        $expected = array(
            'TransactionType',
            'PymtMethod',
            'ServiceID',
            'PaymentID',
            'OrderNumber',
            'Amount',
            'CurrencyCode',
            'HashValue',
            'HashValue2',
            'TxnID',
            'IssuingBank',
            'TxnStatus',
            'AuthCode',
            'TxnMessage',
            'TokenType',
            'Token',
            'Param6',
            'Param7',
            'CardHolder',
            'CardNoMask',
            'CardExp',
            'CardType',
            'SettleTAID',
            'TID',
            'EPPMonth',
            'EPP_YN',
            'PromoCode',
            'PromoOriAmt'
        );

        foreach($expected as $param){
            if(isset($params[$param])){
                $this->eghl_response[$param] = $params[$param];
            }
        }

        return $this->eghl_response;
    }

}