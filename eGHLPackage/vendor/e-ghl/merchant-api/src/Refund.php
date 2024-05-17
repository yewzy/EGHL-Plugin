<?php
    namespace eGHL\MerchantAPI;
    use eGHL\MerchantAPI\core\APIBase;
    use eGHL\MerchantAPI\Query;

    class Refund extends APIBase{

        public function isRefunded(){
            $Query = new Query($this->params, $this->merchantPass, $this->testMode);
            return $Query->isRefunded();
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