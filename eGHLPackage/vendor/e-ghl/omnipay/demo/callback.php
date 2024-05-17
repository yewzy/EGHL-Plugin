<?php
    /** 
	 * Handles server to server callback response from eGHL (defined in MerchantCallBackURL)
    */
    
    require_once '../vendor/autoload.php';
    use Omnipay\Omnipay;
    use Omnipay\Common\Message\NotificationInterface;

	$gateway = Omnipay::create('eGHL');
	
	$gateway->setMerchantId('SIT');
    $gateway->setMerchantPassword('sit12345');
    $Response = $gateway->completePurchase($_REQUEST)->send();

    echo "<pre>".print_r($Response->getData(),1)."</pre>";

    if($Response->isSuccessful()){
        // Payment successful logic
        echo "success";
    }
    elseif($Response->isCancelled()){
        // Payment canceled by buyer
        echo "cancelled";
    }
    elseif($Response->isPending()){
        // Payment pending logic
        echo "pending";
    }
    else{
        // Payment failed logic
        echo "failed";
    }

    die('OK');
?>