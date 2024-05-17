# eghl/param-validator

Validate input parameters for eGHL API requests for *Capture*, *Payment*, *Query*, *Refund*, *Reversal* & *Settlement*.

## Composer Require command

```pseudocode
composer require eghl/param-validator
```

## Introduction

This library/package can be used prior to sending request to eGHL. This library validate the request parameters and throws exception with explicit error message. However, if all the parameters are valid no exception will be thrown and the code will continue.

### Supported Request Types

This library supports validation of parameters for following request types.

1. Capture
2. Payment
3. Query
4. Refund
5. Reversal
6. Settlement

### Scope of Validation

Following are the scopes of validation performed by this package for the above mentioned request types.

1. Look for Required parameters. Also validates if some parameter is conditionally required.
2. validate data type i.e. *Numeric*, *Alphabetic* and *Alphanumeric* for each parameter.
3. validate max character length for each parameter.
4. validate amount format, IP address, email address, and URL for some parameters at ad hoc basis.

## Usage

The eghl/param-validator package can be used in two ways.

1. Explicitly define request type class.
2. Using Factory class.

### Explicitly define request type class

The following code example explains the validation of parameters of *Query* request type.

```php
// Require composer generated autoloader
require_once('../vendor/autoload.php');

// Namespace to be used
use eGHL\ParamValidator\QueryRequest;

// Request Parameters to be validated
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
    $Request = new QueryRequest($params);
    $params = $Request->validate(); // This line actually performs validation and will throw exception if any parameter is invalid. If all parameters are defined correctly, the function will retrun array of parameters to be sent to respective eGHL API.
}
catch(\Exception $e){
    echo "<pre>".$e->getMessage()."</pre>";
}
```

### Using Factory Class

The following code example explains the validation of parameters of *Query* request type.

```php
// Require composer generated autoloader
require_once('../vendor/autoload.php');

// use Namespace of validatorFactory
use eGHL\ParamValidator\core\validatorFactory;

// Request Parameters to be validated
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
    // The first parameter is type of request (Case-Sensitive) i.e. 'Query' in the current example
    $Request = validatorFactory::create('Query', $params);
    $params = $Request->validate();// This line actually performs validation and will throw exception if any parameter is invalid. If all parameters are defined correctly, the function will retrun array of parameters to be sent to respective eGHL API.
}
catch(\Exception $e){
    echo "<pre>".$e->getMessage()."</pre>";
}
```

