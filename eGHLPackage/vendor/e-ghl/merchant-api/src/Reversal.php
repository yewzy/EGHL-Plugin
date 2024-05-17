<?php
    namespace eGHL\MerchantAPI;
    use eGHL\MerchantAPI\core\APIBase;
    use eGHL\MerchantAPI\Query;

    class Reversal extends APIBase{

        public function isReversed(){
            $Query = new Query($this->params, $this->merchantPass, $this->testMode);
            return $Query->isReversed();
        }

        public function isReversalPending(){
            $Query = new Query($this->params, $this->merchantPass, $this->testMode);
            return $Query->isReversalPending();
        }

        public function doTxnNotExist(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='-1');
        }

        public function gotSystemError(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='-2');
        }

    }
?>