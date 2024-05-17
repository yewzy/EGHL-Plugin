# eGHL Exception Handler
This exception hanler class is extend from core PHP Exception and can be used in almost simmilar manner. This exception handler is created specifically for handling the exceptions in PHP libraries built for eGHL.

## Example Usage
```php
    // Require Composer autoloader
    require_once '../vendor/autoload.php';

    // Invoke namespace
    use eGHL\Exception;

    class Foo
    {
        private $var;

        function __construct($avalue = NULL) {
            $this->var = $avalue;
            if(is_null($this->var)){
                throw new Exception('Variable must have some value');
            }
        }

        public function get(){
            return $this->var;
        }
    }

    // Example 1
    try {
        $A = new Foo();
    } 
    catch (Exception $e) {
        echo "Caught Exception: $e<br/>";
        echo "<pre>Trace: ".print_r($e->getTrace(),1)."</pre>";
    }

    // Example 2
    try {
        $B = new Foo('B');
    } 
    catch (Exception $e) {
        echo "Caught Exception: $e<br/>";
        echo "<pre>Trace: ".print_r($e->getTrace(),1)."</pre>";
    }
    echo "The value of B is: {$B->get()}<br/>";
```