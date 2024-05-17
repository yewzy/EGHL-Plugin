<?php
    require_once('../vendor/autoload.php');

    use eGHL\ParamValidator\core\validatorFactory;

    $params = array(
        'TransactionType' => 'QUERY',
        'PymtMethod' => 'CC',
        'ServiceID' => 'SIT',
        'PaymentID' => '123',
        'Amount' => '100.00',
        'CurrencyCode' => 'MYR',
        'HashValue' => 'adasdasdasd'
    );

    try{
        $Request = validatorFactory::create('Query', $params);
        $params = $Request->validate();
        echo "<pre>".print_r($params, 1)."</pre>";
    }
    catch(Exception $e){
        echo "<pre>Caught Exception: $e</pre>";
    }
    
?>