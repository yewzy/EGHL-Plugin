<?php
    require_once "../vendor/autoload.php";

    use eGHL\MerchantAPI\Refund;

    $ServiceID = 'SIT';
    $merchantPass = 'sit12345';

    $params = array(
        'PymtMethod' => 'CC',
        'ServiceID' => $ServiceID ,
        'PaymentID' => '130#1547432197655ef9',
        'Amount' => '1005.00',
        'CurrencyCode' => 'MYR'
    );

    $API = new Refund($params, $merchantPass, true);
    if(!$API->isRefunded()){
        $response = $API->sendRequest()->getResponse();
        if($API->isSuccess()){
            echo "<pre>".$response['TxnID']." is Refunded</pre>";
        }
        else{
            echo "<pre>".print_r($response,1)."</pre>";
        }
    }
    else{
        echo "<pre>Already Refunded</pre>";
    }
    
?>