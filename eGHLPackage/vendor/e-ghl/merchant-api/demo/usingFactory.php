<?php
    require_once "../vendor/autoload.php";

    use eGHL\MerchantAPI\core\APIFactory;

    $ServiceID = 'SIT';
    $merchantPass = 'sit12345';

    $params = array(
        'PymtMethod' => 'DD',
        'ServiceID' => $ServiceID ,
        'PaymentID' => '8308110000',
        'Amount' => '123.10',
        'CurrencyCode' => 'MYR'
    );

    $API = APIFactory::create('Query', $params, $merchantPass, true);
    $response = $API->sendRequest()->getResponse();
    echo "<pre>".print_r($response,1)."</pre>";

    $params = array(
        'PymtMethod' => 'CC',
        'ServiceID' => $ServiceID ,
        'PaymentID' => '130#1547432197655ef9',
        'Amount' => '1005.00',
        'CurrencyCode' => 'MYR'
    );

    $API = APIFactory::create('Refund', $params, $merchantPass, true);
    $response = $API->sendRequest()->getResponse();
    echo "<pre>".print_r($response,1)."</pre>";

    $API = APIFactory::create('Reversal', $params, $merchantPass, true);
    $response = $API->sendRequest()->getResponse();
    echo "<pre>".print_r($response,1)."</pre>";

    $params = array(
        'PymtMethod' => 'CC',
        'ServiceID' => $ServiceID ,
        'PaymentID' => 'IPGJAW20190111600001',
        'Amount' => '1.00',
        'CurrencyCode' => 'MYR'
    );

    $API = APIFactory::create('Capture', $params, $merchantPass, true);
    $response = $API->sendRequest(false)->getResponse();
    echo "<pre>".print_r($response,1)."</pre>";
?>