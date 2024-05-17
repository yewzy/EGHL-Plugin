# eGHL Merchant API Package
This package provides an easy interface to the merchants to interact with eGHL APIs. The set of APIs that are supported by this package are as under.

1. Capture
2. Query
3. Refund
4. Reversal

# Install
1. Run the following composer command to install this package
> composer require e-ghl/merchant-api
2. Clone the eGHL_hashing package from private repo using the following command. (Gain access to the private repo by contacting at support.eghl@ghl.com)
> git clone https://bitbucket.org/eghl/eghl_hashing.git vendor/e-ghl/merchant-api/src/eghl_hashing

# Usage
Using these API objects consist of following intuitive steps.
1. Require autoloader
2. Invoke respective API object's namespace
3. Define key-value (associative) array of parameters to be sent
4. Instanciate the respective API object to be used.
5. Start using the object

The usage by example for each of the above mentioned APIs is given as following. 

### Query
Query API is used to get information about any existing transaction. Given that the existing transaction matches all parameters sent in Query request i.e. *PymtMethod*, *ServiceID*, *PaymentID*, *Amount* and *CurrencyCode*

```php
// Requiring autoloader
require_once "../vendor/autoload.php";

// Invoke Namespace
use eGHL\MerchantAPI\Query;

// Defining Parameters to be sent
$ServiceID = 'SIT';
$merchantPass = 'sit12345';

$params = array(
    'PymtMethod' => 'DD',
    'ServiceID' => $ServiceID ,
    'PaymentID' => '8308110000',
    'Amount' => '123.10',
    'CurrencyCode' => 'MYR'
);

// Instantiate Query API Object
/**
 * First parameter: Key value pair of Data to be sent
 * Secont Parameter: Merchant Password
 * Third Paramete: if true then test mode and production mode if value is false
 * */
$API = new Query($params, $merchantPass , true);
if($API->doTxnExist()){
    echo "<pre>".print_r($API->getResponse(),1)."</pre>";
}
```

### Refund
The Refund API initiated the process of online refund via eGHL Payment Gateway for an existing transaction. Given that the existing transaction matches all parameters sent in Refund request i.e. *PymtMethod*, *ServiceID*, *PaymentID*, *Amount* and *CurrencyCode*.
> Not all transactions are refundable online. 
> Only the transactions with **PymtMethod = CC** are refundable online.
> The amount must not exceeed the original transaction amount. The transaction can be refunded partially.

```php
// Requiring autoloader
require_once "../vendor/autoload.php";

// Invoke Namespace
use eGHL\MerchantAPI\Refund;

// Defining Parameters to be sent
$ServiceID = 'SIT';
$merchantPass = 'sit12345';

$params = array(
    'PymtMethod' => 'CC',
    'ServiceID' => $ServiceID ,
    'PaymentID' => '130#1547432197655ef9',
    'Amount' => '1005.00',
    'CurrencyCode' => 'MYR'
);

// Instantiate Refund API Object
/**
 * First parameter: Key value pair of Data to be sent
 * Secont Parameter: Merchant Password
 * Third Paramete: if true then test mode and production mode if value is false
 * */
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
```

### Reversal
Reversal API is used to void/reverse the existing transaction. The reversal is only doable before the bank's settlement. Therfore, it is highly recommended to Query about the payment status of the transaction prior to sending Reversal request.
> It is advisable to reverse transaction within one hour of its original sale time.
>  Reversal of a transaction already settled by bank will be rejected with *fail* status.
> Partial reversal is not possible and will be rejected with *not found* status.

```php
// Requiring autoloader
require_once "../vendor/autoload.php";

// Invoke Namespace
use eGHL\MerchantAPI\Reversal;

// Defining Parameters to be sent
$ServiceID = 'SIT';
$merchantPass = 'sit12345';

$params = array(
    'PymtMethod' => 'DD',
    'ServiceID' => $ServiceID ,
    'PaymentID' => '8308110000',
    'Amount' => '123.10',
    'CurrencyCode' => 'MYR'
);

// Instantiate Reversal API Object
/**
 * First parameter: Key value pair of Data to be sent
 * Secont Parameter: Merchant Password
 * Third Paramete: if true then test mode and production mode if value is false
 * */
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
```

### Capture
Capture API is used to capture an authorised transaction. 
> The authorised transactions are those transactions which were submitted to eGHL with **TransactionType = AUTH** parameter.

```php
// Requiring autoloader
require_once "../vendor/autoload.php";

// Invoke Namespace
use eGHL\MerchantAPI\Capture;

// Defining Parameters to be sent
$ServiceID = 'SIT';
$merchantPass = 'sit12345';

$params = array(
    'PymtMethod' => 'CC',
    'ServiceID' => $ServiceID ,
    'PaymentID' => 'IPGJAW20190111600003',
    'Amount' => '1.00',
    'CurrencyCode' => 'MYR'
);

// Instantiate Capture API Object
/**
 * First parameter: Key value pair of Data to be sent
 * Secont Parameter: Merchant Password
 * Third Paramete: if true then test mode and production mode if value is false
 * */
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
```

# Factory Usage
The factory object can be used to instantiate any of the above mentioned API request objects. Following example shows instantiating Query object using factory.

> Factory object is built to apply factory design pattern on API classes. One advantage of factory object is that we don't need to invoke namespace for each of the classes as Factory object itself will take the responsibility.

```php
// Requiring autoloader
require_once "../vendor/autoload.php";

// Invoke Namespace for Fctory object only
use eGHL\MerchantAPI\core\APIFactory;

$ServiceID = 'SIT';
$merchantPass = 'sit12345';

$params = array(
    'PymtMethod' => 'DD',
    'ServiceID' => $ServiceID ,
    'PaymentID' => '8308110000',
    'Amount' => '123.10',
    'CurrencyCode' => 'MYR'
);

$API = APIFactory::create('Query', $params, $merchantPass, true);
$response = $API->sendRequest()->getResponse();
echo "<pre>".print_r($response,1)."</pre>";
```

# Public Methods Description
| Name              |       Args        |      Return       |                                                                                                                              Description                                                                                                                               |                       Operations |
| :---------------- | :---------------: | :---------------: | :--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------: | -------------------------------: |
| sendRequest       | $validateResponse |    Self Object    | Throws Exception if something goes wrong Otherwise returns the object itself. If input argument $validateResponse is set to true then it will validate the response from eGHL by matching HashValue. If validateResponse is not provided, the value is true by default | Capture, Query, Refund, Reversal |
| validateResponse  |         -         |       void        |                                                                               Validates Response from eGHL by matching HashValue. Throws exception if invalid or something happens wrong                                                                               | Capture, Query, Refund, Reversal |
| getResponse       |         -         | associative array |                                                                                                        Returns the associative array of response data from eGHL                                                                                                        | Capture, Query, Refund, Reversal |
| isSuccess         |         -         |      boolean      |                                                                                                                 Cheks if the operation was successful                                                                                                                  | Capture, Query, Refund, Reversal |
| isFail            |         -         |      boolean      |                                                                                                                     Checks if operation was failed                                                                                                                     | Capture, Query, Refund, Reversal |
| isPending         |         -         |      boolean      |                                                                                                                    Checks if operation was pending                                                                                                                     | Capture, Query, Refund, Reversal |
| isPendingByBank   |         -         |      boolean      |                                                                                                                Check if transaction is pending by bank                                                                                                                 |                            Query |
| isCaptured        |         -         |      boolean      |                                                                                                                   Checks if transaction is captured                                                                                                                    |                   Query, Capture |
| isAuthorised      |         -         |      boolean      |                                                                                                                  Checks if transaction is authorised                                                                                                                   |                   Query, Capture |
| isRefunded        |         -         |      boolean      |                                                                                                                   Checks if transaction is Refunded                                                                                                                    |                    Query, Refund |
| isReversed        |         -         |      boolean      |                                                                                                                   Checks if transaction is Reversed                                                                                                                    |                  Query, Reversal |
| isReversalPending |         -         |      boolean      |                                                                                                               Checks if transaction Reversal is pending                                                                                                                |                  Query, Reversal |
| doTxnNotExist     |         -         |      boolean      |                                                                                                                  Checks if transaction does not exist                                                                                                                  |          Query, Refund, Reversal |
| gotSystemError    |         -         |      boolean      |                                                                                                                  Checks if operation got system error                                                                                                                  |          Query, Refund, Reversal |
| doTxnExist        |         -         |      boolean      |                                                                                                                      Checks if transaction exist                                                                                                                       |                            Query |

# Depends on
This package depends on following packages. Thus, upon installing this package, the following packages will automatically be installed by composer package manager.

1. [eghl/param-validator](https://packagist.org/packages/eghl/param-validator) (Validate input parameters for eGHL API requests)
2. [e-ghl/hashing](https://packagist.org/packages/e-ghl/hashing) (A class that helps the integration plug-in to calculate hash for communicating with eGHL payment Gateway)
3. [guzzlehttp/guzzle](https://packagist.org/packages/guzzlehttp/guzzle) (Guzzle is a PHP HTTP client that makes it easy to send HTTP requests and trivial to integrate with web services)