<?php

namespace eGHL\ParamValidator\tests;
use eGHL\ParamValidator\core\validatorFactory;

class validatorFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $RequestTypes = array(
        'Capture',
        'Payment',
        'Query',
        'Refund',
        'Reversal',
        'Settlement'
    );
    
    private $Capture_params = array();
    private $Payment_params = array();
    private $Query_params = array();
    private $Refund_params = array();
    private $Reversal_params = array();
    private $Settlement_params = array();

    private function initParams(){
        $this->Capture_params = array(
            'TransactionType' => 'CAPTURE',
            'PymtMethod' => $this->generateRandomString(3,'A'),
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'PaymentID' => $this->generateRandomString(20,'AN'),
            'Amount' => '100.00',
            'CurrencyCode' => $this->generateRandomString(3,'A'),
            'HashValue' => $this->generateRandomString(64,'AN')
        );

        $this->Payment_params = array(
            'TransactionType' => $this->generateRandomString(7,'A'),
            'PymtMethod' => $this->generateRandomString(3,'A'),
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'PaymentID' => $this->generateRandomString(20,'AN'),
            'OrderNumber' => $this->generateRandomString(20,'AN'),
            'PaymentDesc' => $this->generateRandomString(100,'AN'),
            'MerchantReturnURL' => 'http://example.com/return?p1=1&p2=add',
            'Amount' => '100.00',
            'CurrencyCode' => $this->generateRandomString(3,'A'),
            'HashValue' => $this->generateRandomString(64,'AN'),
            'CustIP' => '127.0.0.1',
            'CustName' => $this->generateRandomString(50,'AN'),
            'CustEmail' => 'abc@123.com',
            'CustPhone' => $this->generateRandomString(25,'AN'),
            'B4TaxAmt' => '90.00',
            'TaxAmt' => '10.00',
            'MerchantName' => $this->generateRandomString(25,'AN'),
            'CustMAC' => $this->generateRandomString(50,'AN'),
            'MerchantApprovalURL' => 'http://example.com/approval?p1=1&p2=add',
            'MerchantUnApprovalURL' => 'http://example.com/unapproval?p1=1&p2=add',
            'MerchantCallBackURL' => 'http://example.com/callback?p1=1&p2=add',
            'LanguageCode' => $this->generateRandomString(2,'A'),
            'PageTimeout' => $this->generateRandomString(4,'N'),
            'CardHolder' => $this->generateRandomString(30,'AN'),
            'CardNo' => $this->generateRandomString(19,'N'),
            'CardExp' => $this->generateRandomString(6,'N'),
            'CardCVV2' => $this->generateRandomString(4,'N'),
            'IssuingBank' => $this->generateRandomString(30,'AN'),
            'BillAddr' => $this->generateRandomString(100,'AN'),
            'BillPostal' => $this->generateRandomString(15,'AN'),
            'BillCity' => $this->generateRandomString(30,'A'),
            'BillRegion' => $this->generateRandomString(30,'A'),
            'BillCountry' => $this->generateRandomString(2,'A'),
            'ShipAddr' => $this->generateRandomString(100,'AN'),
            'ShipPostal' => $this->generateRandomString(15,'AN'),
            'ShipCity' => $this->generateRandomString(30,'A'),
            'ShipRegion' => $this->generateRandomString(30,'A'),
            'ShipCountry' => $this->generateRandomString(2,'A'),
            'SessionID' => $this->generateRandomString(100,'AN'),
            'TokenType' => $this->generateRandomString(3,'A'),
            'Token' => $this->generateRandomString(50,'ANS'),
            'Param6' => $this->generateRandomString(50,'ANS'),
            'Param7' => $this->generateRandomString(50,'ANS'),
            'EPPMonth' => $this->generateRandomString(2,'N'),
            'PromoCode' => $this->generateRandomString(10,'AN')
        );

        $this->Query_params = array(
            'TransactionType' => 'QUERY',
            'PymtMethod' => $this->generateRandomString(3,'A'),
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'PaymentID' => $this->generateRandomString(20,'AN'),
            'Amount' => '100.00',
            'CurrencyCode' => $this->generateRandomString(3,'A'),
            'HashValue' => $this->generateRandomString(64,'AN')
        );

        $this->Refund_params = array(
            'TransactionType' => 'REFUND',
            'PymtMethod' => $this->generateRandomString(3,'A'),
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'PaymentID' => $this->generateRandomString(20,'AN'),
            'Amount' => '100.00',
            'CurrencyCode' => $this->generateRandomString(3,'A'),
            'HashValue' => $this->generateRandomString(64,'AN')
        );

        $this->Reversal_params = array(
            'TransactionType' => 'RSALE',
            'PymtMethod' => $this->generateRandomString(3,'A'),
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'PaymentID' => $this->generateRandomString(20,'AN'),
            'Amount' => '100.00',
            'CurrencyCode' => $this->generateRandomString(3,'A'),
            'HashValue' => $this->generateRandomString(64,'AN')
        );

        $this->Settlement_params = array(
            'TransactionType' => 'SETTLE',
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'SettleTAID' => $this->generateRandomString(10,'N'),
            'SettleAmount' => '100.00',
            'SettleTxnCount' => $this->generateRandomString(3,'N'),
            'HashValue' => $this->generateRandomString(64,'AN')
        );
    }

    private function generateRandomString($length = 10, $type ="AN") {
        switch($type){
            case "A":
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
            case "AN":
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
            case "N":
                $characters = '0123456789';
            break;
            default:
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function testValidatorFactoryForAllRequestTypes(){
        $exceptionCaught = 0;
        $this->initParams();
        foreach($this->RequestTypes as $type){
            try{
                $params = $type.'_params';
                $Request = validatorFactory::create($type, $this->$params);
                $this->$params = $Request->validate();
            }
            catch(\Exception $e){
                echo "Caught Exception: $e";
                $exceptionCaught++;
            }
        }
        $this->assertEquals(0, $exceptionCaught);
    }
}

?>