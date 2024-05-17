<?php
namespace eGHL\Hashing\tests;
use eGHL\Hashing\eGHL_Hash;

class eGHL_HashTest extends \PHPUnit_Framework_TestCase
{
    public function testReqHashValue(){
        define('BASE_URL','http://test2pay.ghl.com:82/eGHL_Hash/demo/');
        $paymentInfo = array(
            'ServiceID' => 'GHL',
            'PymtMethod' => 'ANY',
            'OrderNumber' => 'OMNI001',
            'PaymentID' => 'OMNI001',
            'PaymentDesc' => 'ominpay test',
            'MerchantReturnURL' => BASE_URL.'return.php',
            'MerchantCallBackURL' => BASE_URL.'callback.php',
            'Amount' => '10.00',
            'CurrencyCode' => 'MYR',
            'CustName' => 'Jawad Humayun',
            'CustEmail' => 'jawad.humayun@ghl.com',
            'CustPhone' => '01156301987',
            'PageTimeout' => 700,
            'CustIP' => '127.0.0.1'
        );
        
        $merchantPass = 'ghl12345';
        
        $eGHL_Hash = new eGHL_Hash($paymentInfo);
        $hashValue = $eGHL_Hash->generateHashValueForPaymentInfo('SALE', $merchantPass);

        $expected = '00151bb78f2590e4ab568a204e920d547ae90f05d69ab6cac11b0c83c8784dd5';
        $this->assertEquals($expected, $hashValue);
    }
    
    public function testRespHashValue(){
        define('BASE_URL','http://test2pay.ghl.com:82/eGHL_Hash/demo/');
        $paymentResponse = array
        (
            'TransactionType' => 'SALE',
            'PymtMethod' => 'ANY',
            'ServiceID' => 'GHL',
            'PaymentID' => '1543399909012156ffe3',
            'OrderNumber' => 'OMNI001',
            'Amount' => '10.00',
            'CurrencyCode' => 'MYR',
            'HashValue' => '9f1335ea1b0f075f504b20f3c823a43e81f956e8dbf4617ebebf34bb6db37efe',
            'HashValue2' => '8b5e3eed92592ddf7a53741115af4ba98aba9002ac2e2d2cee8ea607cad833d9',
            'TxnID' => '',
            'TxnStatus' => '1',
            'TxnMessage' => 'Buyer cancelled',
            'Param6' => '',
            'Param7' => ''
        );
        
        $merchantPass = 'ghl12345';
        
        $eGHL_Hash = new eGHL_Hash($paymentResponse);
        $hashValue = $eGHL_Hash->generateHashValueForPaymentInfo('HashValue2', $merchantPass, true);

        $expected = '8b5e3eed92592ddf7a53741115af4ba98aba9002ac2e2d2cee8ea607cad833d9';
        $this->assertEquals($expected, $hashValue);
    }
}
?>