<?php
    namespace eGHL;
    
    class Exception extends \Exception{

        public function __construct($message, $code = 0, \Exception $previous = null) {        
            parent::__construct($message, $code, $previous);
        }

        // overriding
        public function __toString() {
            if($this->code !== 0){
                return __CLASS__ . ": [{$this->code}]: {$this->message}\n";    
            }
            else{
                return __CLASS__ . ": {$this->message}\n";
            }
        }
    }
?>