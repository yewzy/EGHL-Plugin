<?php
    require_once "../vendor/autoload.php";

    use eGHL\MerchantAPI\Query;

    $ServiceID = 'GHL';
    $merchantPass = 'ghl12345';

    $params = array(
        'PymtMethod' => 'OTC',
        'ServiceID' => $ServiceID ,
        'PaymentID' => 'IPGJAW20190111600013',
        'Amount' => '1.00',
        'CurrencyCode' => 'MYR'
    );

    $API = new Query($params, $merchantPass , true);
    if($API->doTxnExist() && $API->isPendingByBank()){
        echo "<pre>".print_r($API->getResponse(),1)."</pre>";
    }
?>