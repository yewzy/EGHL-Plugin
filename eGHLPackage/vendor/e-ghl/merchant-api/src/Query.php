<?php
    namespace eGHL\MerchantAPI;
    use eGHL\MerchantAPI\core\APIBase;

    class Query extends APIBase{

        public function isPendingByBank(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (
                isset($this->ResponseData['TxnStatus']) 
                && $this->ResponseData['TxnStatus']==='2' 
                && strlen($this->ResponseData['TxnID'])>0 
                && (
                    strlen($this->ResponseData['BankRefNo'])>0
                    || strlen($this->ResponseData['AuthCode'])>0
                )
            );
        }

        public function isRefunded(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='10');
        }

        public function isReversed(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='9');
        }

        public function isAuthorised(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='15');
        }

        public function isCaptured(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='16');
        }

        public function isReversalPending(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='31');
        }

        public function doTxnExist(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnExists']) && $this->ResponseData['TxnExists']==='0');
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