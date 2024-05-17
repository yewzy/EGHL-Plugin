<?php
    namespace eGHL\MerchantAPI;
    use eGHL\MerchantAPI\core\APIBase;
    use eGHL\MerchantAPI\Query;

    class Capture extends APIBase{

        public function isCaptured(){
            $Query = new Query($this->params, $this->merchantPass, $this->testMode);
            return $Query->isCaptured();
        }

        public function isAuthorised(){
            $Query = new Query($this->params, $this->merchantPass, $this->testMode);
            return $Query->isAuthorised();
        }

    }
?>