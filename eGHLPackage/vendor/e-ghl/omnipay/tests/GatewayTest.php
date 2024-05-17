<?php

namespace Omnipay\eGHL\tests;
use Omnipay\Omnipay;

define('BASE_URL','http://localhost:82//');
class GatewayTest extends \PHPUnit_Framework_TestCase
{
    private $gateway;
    private $RequestData;
    private $ResponseData;

    private function SetUPGateway(){
        $this->gateway = Omnipay::create('eGHL');
	
        $this->gateway->setMerchantId('SIT');
        $this->gateway->setMerchantPassword('sit12345');

        $this->RequestData = array(
            'PymtMethod' => 'ANY',
            'OrderNumber' => 'OMNI001',
            //'PaymentID' => 'OMNI001', // if not defined, its generated automatically
            'PaymentDesc' => 'ominpay test',
            'MerchantReturnURL' => BASE_URL.'return.php',
            'MerchantCallBackURL' => BASE_URL.'callback.php',
            'Amount' => '10.00',
            'CurrencyCode' => 'MYR',
            'CustName' => 'Jawad Humayun',
            'CustEmail' => 'jawad.humayun@ghl.com',
            'CustPhone' => '01156301987',
            'CustIP' => '127.0.0.1',
            'PageTimeout' => 700
        );

        $this->ResponseData = array(
            'TransactionType' => 'SALE',
            'PymtMethod' => 'DD',
            'ServiceID' => 'SIT',
            'PaymentID' => '15434756362e18a914ef',
            'OrderNumber' => 'OMNI001',
            'Amount' => '10.00',
            'CurrencyCode' => 'MYR',
            'HashValue' => 'ca50fa8361ba6e09937e632af4a4ce5b0a74fa687dbcd9c6c98f8ad61c62bb36',
            'HashValue2' => 'b09dd0905ab5d25be8d0d00d5bc5f556befb9d9a5a88c0eca87b2e504fc9667d',
            'TxnID' => 'SIT15434756362e18a914ef',
            'IssuingBank' => 'HostSim',
            'TxnStatus' => '0',
            'AuthCode' => 'SIT154',
            'TxnMessage' => 'Transaction Successful'
        );
    }

    public function testGatwayMerchantId(){
        $this->SetUPGateway();
        $actual = $this->gateway->getMerchantId();
        $expected = 'SIT';

        $this->assertEquals($expected, $actual);
    }

    public function testGatwayMerchantPassword(){
        $this->SetUPGateway();
        $actual = $this->gateway->getMerchantPassword();
        $expected = 'sit12345';

        $this->assertEquals($expected, $actual);
    }

    public function testGatwayName(){
        $this->SetUPGateway();
        $actual = $this->gateway->getName();
        $expected = 'eGHL';

        $this->assertEquals($expected, $actual);
    }

    public function testGatwayPurchase(){
        $this->SetUPGateway();
        $PurchaseResponse = $this->gateway->purchase($this->RequestData)->send();
        $actual = $PurchaseResponse->isRedirect();
        $expected = true;

        $this->assertEquals($expected, $actual);
    }

    public function testTestModeURL(){
        $this->SetUPGateway();
        $this->gateway->setTestMode();
        $PurchaseResponse = $this->gateway->purchase($this->RequestData)->send();
        $actual = $PurchaseResponse->getRedirectUrl();
        $expected = 'https://test2pay.ghl.com/IPGSG/Payment.aspx';

        $this->assertEquals($expected, $actual);
    }

    public function testLiveModeURL(){
        $this->SetUPGateway();
        $PurchaseResponse = $this->gateway->purchase($this->RequestData)->send();
        $actual = $PurchaseResponse->getRedirectUrl();
        $expected = 'https://securepay.e-ghl.com/IPG/Payment.aspx';

        $this->assertEquals($expected, $actual);
    }

    public function testGatewayResponse(){
        $this->SetUPGateway();

        $Response = $this->gateway->completePurchase($this->ResponseData)->send();
        $actual = $Response->isSuccessful();
        $expected = true;

        $this->assertEquals($expected, $actual);
    }

}

?>