<?php

define('BASE_URL','http://test2pay.ghl.com:82/eGHL_Hash/demo/');
require_once '../vendor/autoload.php';
use eGHL\Hashing\eGHL_Hash;

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

echo 'Hash value generated: ' . $hashValue;
?>