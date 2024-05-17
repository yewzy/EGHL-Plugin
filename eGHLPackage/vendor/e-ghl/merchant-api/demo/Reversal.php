<?php
    require_once "../vendor/autoload.php";

    use eGHL\MerchantAPI\Reversal;

    $ServiceID = 'SIT';
    $merchantPass = 'sit12345';

    $params = array(
        'PymtMethod' => 'DD',
        'ServiceID' => $ServiceID ,
        'PaymentID' => '8308110000',
        'Amount' => '123.10',
        'CurrencyCode' => 'MYR'
    );

    $API = new Reversal($params, $merchantPass, true);
    
    if(!$API->isReversed()){
        $response = $API->sendRequest()->getResponse();
        if($API->isSuccess()){
            echo "<pre>".$response['TxnID']." is Reversed</pre>";
        }
        else{
            echo "<pre>".print_r($response,1)."</pre>";
        }
    }
    else{
        echo "<pre>already Reversed</pre>";
    }

    
?>