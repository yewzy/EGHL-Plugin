<?php
    /** 
	 * Handles server to client return response from eGHL (defined in MerchantReturnURL)
    */
    
    require_once '../vendor/autoload.php';
    use Omnipay\Omnipay;

	$gateway = Omnipay::create('eGHL');
	
	$gateway->setMerchantId('SIT');
    $gateway->setMerchantPassword('sit12345');
    $Response = $gateway->completePurchase($_REQUEST)->send();

    echo "<pre>".print_r($Response->getData(),1)."</pre>";

    if($Response->isSuccessful()){
        // Payment successful logic
        // Redirect to Success page
        echo "success";
    }
    elseif($Response->isCancelled()){
        // Payment canceled by buyer
        // Redirect to failed page
        echo "cancelled";
    }
    elseif($Response->isPending()){
        // Payment pending logic
        // Redirect to pending page
        echo "pending";
    }
    else{
        // Payment failed logic
        // Redirect to failed page
        echo "failed";
    }
?>