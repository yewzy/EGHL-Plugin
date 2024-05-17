# eGHL OmniPay Framework Integration
Package for omnipay to Integrate with eGHL.

## Installation
1. This package can be installed/ required via [Composer](https://getcomposer.org/ "Composer") with the following command
> composer require e-ghl/omnipay

2. Clone the eGHL_hashing package from private repo using the following command. (Gain access to the private repo by contacting at support.eghl@ghl.com)

> git clone https://bitbucket.org/eghl/eghl_hashing.git vendor/e-ghl/merchant-api/src/eghl_hashing


## How OmniPay framework fits in any e-commerce payment plugin system?
To understand this, first we need to identify steps required to build a payment plugin for an e-commerce system. 

The table below shows all the salient steps involved in devloping the e-commerce payment plugin. Some of these steps are completely e-commerce framework dependent and rest of the steps are handled by omnipay framework. The table below also elaborates that which steps are e-commerce framework dependent and which are handled by omnipay framework.

|   #   | Description                                                                                                                  | is e-commerce Framework Dependent | is OmniPay Framework Dependent |
| :---: | :--------------------------------------------------------------------------------------------------------------------------- | :-------------------------------: | :----------------------------: |
|   1   | Build Payment plugin configuration storage mechanism i.e. MerchantID and MerchantPass etc                                    |              **Yes**              |               -               |
|   2   | Get and prepare checkout order information required by eGHL for a  transaction                                               |              **Yes**              |               -               |
|   3   | Determine Callback (server to server) and Return (Server to browser) URL                                                     |              **Yes**              |               -               |
|   4   | Validate order information required by eGHL to process a payment transaction                                                 |                -                 |            **Yes**             |
|   5   | Prepare auto submit HTML form containing payment request info along with valid HashValue to be submitted to eGHL payment URL |                -                 |            **Yes**             |
|   6   | Validate eGHL payment response using Hash Value matching in callback / return URL                                            |                -                 |            **Yes**             |
|   7   | Determine Transaction status i.e. success, failed or pending                                                                 |                -                 |            **Yes**             |
|   8   | Update the order status, create invoice, notify customer depending on transaction status                                     |              **Yes**              |               -               |



## Usage
The Developer only require three easy steps of code to integrate with eGHL payment method if the [omnipay](http://omnipay.thephpleague.com/ "omnipay") is already installed.

| #    | Steps                                                                         | Remarks                                                                                                                                                                                                                                                                                                                                                                                                                         |
| :--- | :---------------------------------------------------------------------------- | :------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 1    | [Send payment request to eGHL](#markdown-header-send-payment-request-to-eghl) | The autosubmit html form will be generated that will send payment request to eGHL                                                                                                                                                                                                                                                                                                                                               |
| 2    | [Handle callback response](#markdown-header-handle-callback-response)         | This is server to server response from eGHL to merchant website. This is URL on merchant website which is specified in **MerchantCallBackURL** parameter of the payment request. This parameter is not mandatory however it is recommende to be included. This piece of code is responsible to update the order status.                                                                                                         |
| 3    | [Handle return response](#markdown-header-handle-return-response)             | This is server to client response from eGHL to merchant website. This URL is on merchant website which is specified in **MerchantReturnURL** parameter of the payment request. This parameter is mandatory and it is mainly reponsibe to update the order status if it is not already updated by the callback URL and also redirect the shopper to appropriate landing page i.e. successful payment page or failed payment page |

## Send payment request to eGHL ##
```php
define('BASE_URL','http://localhost/omni-eghl/demo/');
use Omnipay\Omnipay;

$gateway = Omnipay::create('eGHL');

$gateway->setMerchantId('SIT');
$gateway->setMerchantPassword('sit12345');
$gateway->setTestMode(); // Add tis line only to enable test mode payment

$data = array(
			//'TransactionType' => 'AUTH', // Set TransactionType = AUTH in order to authorize the CC transaction
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
	$PurchaseResponse->redirect(); // redirect to offsite payment gateway
}
```

## Handle callback response ##
```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('eGHL');

$gateway->setMerchantId('SIT');
$gateway->setMerchantPassword('sit12345');
$Response = $gateway->completePurchase($_REQUEST)->send();

if($Response->isSuccessful()){
	// Payment successful logic
}
elseif($Response->isCancelled()){
	// Payment canceled by buyer
}
elseif($Response->isPending()){
	// Payment pending logic
}
else{
	// Payment failed logic
}
die('OK'); //acknowlegment sent back to eGHL
```
## Handle return response ##

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('eGHL');

$gateway->setMerchantId('SIT');
$gateway->setMerchantPassword('sit12345');
$Response = $gateway->completePurchase($_REQUEST)->send();

if($Response->isSuccessful()){
// Payment successful logic
// Redirect to Success page
}
elseif($Response->isCancelled()){
// Payment canceled by buyer
// Redirect to failed page
}
elseif($Response->isPending()){
// Payment pending logic
// Redirect to pending page
}
else{
// Payment failed logic
// Redirect to failed page
}
```
## Other Features
eGHL Omnipay integration also supports following features which are very useful and common in any payment processing.

1. Refund (To Refund a Credit Card Transaction)
2. Void / Reversal (To reverse / void a Credit Card Transaction)
3. Capture (To capture the authorised Credit Card Transaction)

The example code of each of the above mentioned features is explained below.

### Refund
The *refund* method of *Omnipay\eGHL\Gateway* object sends online refund request for a credit card transaction to eGHL gateway.

Returns [eGHL\MerchantAPI\Refund](https://bitbucket.org/eghl/merchantapi/src/master/) object if the refund request is successful or pending. Throws exception if transaction is already refunded or failed due to any reason.

> Only credit card transactions are refundable online, therefore Direct debit or online banking transactions will not be refunded.

```php
// Require Autoloader
require_once "../vendor/autoload.php";

// Invoke Omnipay namespace
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
```
### Void / Reverse
The *void* method of *Omnipay\eGHL\Gateway* object sends online reversal request for a credit card transaction to eGHL gateway.

Returns [eGHL\MerchantAPI\Reversal](https://bitbucket.org/eghl/merchantapi/src/master/) object if the void request is successful or pending. Throws exception if transaction is already reversed or failed due to any reason.

> Only credit card transactions are reversed online, therefore Direct debit or online banking transactions will not be reversed.

```php
// Require Autoloader
require_once "../vendor/autoload.php";

// Invoke Omnipay namespace
use Omnipay\Omnipay;

$gateway = Omnipay::create('eGHL');

$gateway->setMerchantId('SIT');
$gateway->setMerchantPassword('sit12345');
$gateway->setTestMode(); // Add tis line only to enable test mode payment

$data = array(
            'PymtMethod' => 'CC',
            'PaymentID' => 'IPGJAW20190111600016',
            'Amount' => '1.00',
            'CurrencyCode' => 'MYR'
        );

try{
    $RespData = $gateway->void($data)->getResponse();
    echo "<pre>".print_r($RespData, 1)."</pre>";
}
catch(\Exception $e){
    echo "Caught Exception: ".$e->getMessage();
}
```

### Capture
The *capture* method of *Omnipay\eGHL\Gateway* object sends online capture request for a credit card transaction to eGHL gateway.

Returns [eGHL\MerchantAPI\Capture](https://bitbucket.org/eghl/merchantapi/src/master/) object if the void request is successful or pending. Throws exception if transaction is already capture, not authorized or failed due to any reason.

> Only credit card transactions are capture online, therefore Direct debit or online banking transactions will not be captured.

```php
// Require Autoloader
require_once "../vendor/autoload.php";

// Invoke Omnipay namespace
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
```