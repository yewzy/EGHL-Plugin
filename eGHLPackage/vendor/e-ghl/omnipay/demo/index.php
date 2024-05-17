<?php
	/** 
	 * Redirects to eGHL Gateway
	*/
	error_reporting( E_ALL );
	define('BASE_URL','http://localhost:82/packages/eghl-omnipay/demo/');
	require_once '../vendor/autoload.php';
	use Omnipay\Omnipay;

	$gateway = Omnipay::create('eGHL');
	
	$gateway->setMerchantId('SIT');
	$gateway->setMerchantPassword('sit12345');
	$gateway->setTestMode(); // Add tis line only to enable test mode payment
	
	$data = array(
				'PymtMethod' => 'ANY',
				'OrderNumber' => 'OMNI001',
				//'PaymentID' => 'OMNI001', // if not defined, its generated automatically
				'PaymentDesc' => 'ominpay test',
				'MerchantReturnURL' => BASE_URL.'return.php',
				'MerchantCallBackURL' => BASE_URL.'callback.php',
				'Amount' => '10.00',
				'CurrencyCode' => 'MYR',
				'CustName' => 'Jawad Humayun',
				'CustEmail' => 'jawad.humayun@ghl.com',
				'CustPhone' => '01156301987',
				'PageTimeout' => 700
			);

	$PurchaseResponse = $gateway->purchase($data)->send();
	
	if ($PurchaseResponse->isRedirect()) {
		// redirect to offsite payment gateway
		echo "<pre>".print_r($PurchaseResponse->getRedirectData(),1)."</pre>";
		echo "<pre>".print_r($PurchaseResponse->getRedirectUrl(),1)."</pre>";
		$PurchaseResponse->redirect();
	}
	
	
?>