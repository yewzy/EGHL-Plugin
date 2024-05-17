# eGHL_Hash
A class that helps the integration plug-in to calculate hash for communicating with eGHL payment Gateway

## Installation
This package can be installed/ required via [Composer](https://getcomposer.org/ "Composer") with the following command
> composer require e-ghl/hashing

## Usage
### eGHL_Hash class Constructuctor
The class constructor accepts the array of necessary parameters to calculate hash. Details of the necessary parametrs is given below in later section.

### function generateHashValueForPaymentInfo Description

The function generateHashValueForPaymentInfo accepts 3 parameters
1. Hash Value type (mandatory to provide)
2. Merchant Password (mandatory to provide)
3. Is Response Hash (Boolean flag with default value false)

#### Request Hash Value types
> Please note the Expected Params mentioned in bold are mandatory to pass 

| Type  | Expected Prams  |
| :------------ | :------------ |
| SALE  |  **MerchantReturnURL**, MerchantApprovalURL, MerchantUnApprovalURL, MerchantCallBackURL, **ServiceID**, **PaymentID**,  **Amount**, **CurrencyCode**, **CustIP**, PageTimeout, CardNo, Token |
| QUERY, REVERSAL, CAPTURE, REFUND |  **CurrencyCode**, **ServiceID**, **Amount**, **PaymentID**  |
| SOP  | **PaymentID**, **ServiceID**,  **CustIP**  |
| SOP2  | **ServiceID**, CustPhone, **Token**, **CustIP**, **PaymentID**, **CustEmail**  |
| SOPBL  | **DateTime**, **ServiceID**, **CurrencyCode**  |
| SOPCARD  | CustEmail, CustPhone, **ServiceID**, **Token**, **DateTime**  |

#### Response Hash Value types
> Please note the Expected Params mentioned in bold are mandatory to pass 

| Type  | Expected Prams  |
| :------------ | :------------ |
| HASHVALUE  |  **Amount**, **CurrencyCode**,  **PaymentID**, **TxnStatus**, **TxnID**, **ServiceID**, AuthCode |
| HASHVALUE2  | AuthCode, **OrderNumber**, **TxnID**, **ServiceID**, **CurrencyCode**,  **PaymentID**, **TxnStatus**, **Amount**, Param6, Param7  |


### Payment Request HashValue example
```php
use eGHL\Hashing\eGHL_Hash;
define('BASE_URL','http://localhost/eGHL_Hash/demo/');

$paymentInfo = array(
    'ServiceID' => 'SIT',
    'PymtMethod' => 'ANY',
    'OrderNumber' => 'Test001',
    'PaymentID' => 'Test001',
    'PaymentDesc' => 'hash Testing',
    'MerchantReturnURL' => BASE_URL.'return.php',
    'MerchantCallBackURL' => BASE_URL.'callback.php',
    'Amount' => '10.00',
    'CurrencyCode' => 'MYR',
    'CustName' => 'Jawad Humayun',
    'CustEmail' => 'jawad.humayun@ghl.com',
    'CustPhone' => '01156301987',
    'PageTimeout' => 700,
    'CustIP' => '127.0.0.1'
);

$merchantPass = 'sit12345';

$eGHL_Hash = new eGHL_Hash($paymentInfo);
$hashValue = $eGHL_Hash->generateHashValueForPaymentInfo('SALE', $merchantPass);
```
### Payment Response HashValue2 example
```php
define('BASE_URL','http://localhost/eGHL_Hash/demo/');
$paymentResponse = array
        (
            'TransactionType' => 'SALE',
            'PymtMethod' => 'ANY',
            'ServiceID' => 'GHL',
            'PaymentID' => '1543399909012156ffe3',
            'OrderNumber' => 'OMNI001',
            'Amount' => '10.00',
            'CurrencyCode' => 'MYR',
            'HashValue' => '9f1335ea1b0f075f504b20f3c823a43e81f956e8dbf4617ebebf34bb6db37efe',
            'HashValue2' => '8b5e3eed92592ddf7a53741115af4ba98aba9002ac2e2d2cee8ea607cad833d9',
            'TxnID' => '',
            'TxnStatus' => '1',
            'TxnMessage' => 'Buyer cancelled',
            'Param6' => '',
            'Param7' => ''
        );
        
$merchantPass = 'ghl12345';
        
$eGHL_Hash = new eGHL_Hash($paymentResponse);
$hashValue = $eGHL_Hash->generateHashValueForPaymentInfo('HashValue2', $merchantPass, true);
```