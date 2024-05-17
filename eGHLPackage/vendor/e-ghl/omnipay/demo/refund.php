<?php
require_once "../vendor/autoload.php";
use Omnipay\Omnipay;

$gateway = Omnipay::create('eGHL');

$gateway->setMerchantId('SIT');
$gateway->setMerchantPassword('sit12345');
$gateway->setTestMode(); // Add tis line only to enable test mode payment

$data = array(
            'PymtMethod' => 'CC',
            'PaymentID' => '130#1547432197655ef9',
            'Amount' => '1005.00',
            'CurrencyCode' => 'MYR'
        );

try{
    $RespData = $gateway->refund($data)->getResponse();
    echo "<pre>".print_r($RespData, 1)."</pre>";
}
catch(\Exception $e){
    echo "Caught Exception: ".$e->getMessage();
}

?>