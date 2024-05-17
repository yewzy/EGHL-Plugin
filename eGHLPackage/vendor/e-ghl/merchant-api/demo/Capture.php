<?php
    require_once "../vendor/autoload.php";

    use eGHL\MerchantAPI\Capture;

    $ServiceID = 'SIT';
    $merchantPass = 'sit12345';

    $params = array(
        'PymtMethod' => 'CC',
        //'ServiceID' => $ServiceID ,
        'PaymentID' => 'IPGJAW20190111600003',
        'Amount' => '1.00',
        'CurrencyCode' => 'MYR'
    );

    $API = new Capture($params, $merchantPass, true);
    
    if(!$API->isCaptured()){
        if($API->isAuthorised()){
            $response = $API->sendRequest(false)->getResponse();
            if($API->isSuccess()){
                echo "<pre>".$response['TxnID']." is Captured</pre>";
            }
            else{
                echo "<pre>".print_r($response,1)."</pre>";
            }
        }
        else{
            echo "<pre>Txn must be authorised prior to being captured</pre>";
        }
    }
    else{
        echo "<pre>Already Captured</pre>";
    }
?>