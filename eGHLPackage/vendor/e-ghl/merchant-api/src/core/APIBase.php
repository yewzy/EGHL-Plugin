<?php
    namespace eGHL\MerchantAPI\core;
    
    use eGHL\Hashing\eGHL_Hash;
    use eGHL\ParamValidator\core\validatorFactory;
    use GuzzleHttp\Client;
    use eGHL\Exception;

    abstract class APIBase{
        protected $name;
        protected $testMode;
        protected $merchantPass;
        protected $params = array();
        protected $ResponseData = NULL;

        private static function whoAmI() {
            return get_called_class();
        }

        public function __construct(array $Params, $merchantPass, $testMode = true){
            try{
                $this->params = $Params;
                $this->testMode = $testMode;
                $this->merchantPass = $merchantPass;
                $this->name = str_replace('eGHL\\MerchantAPI\\','',$this->whoAmI());
                
                switch($this->name){
                    case "Reversal":
                        $this->params['TransactionType'] = 'RSALE';
                    break;
                    default:
                        $this->params['TransactionType'] = strtoupper($this->name);
                    break;
                }
                
                $eGHL_Hash = new eGHL_Hash($this->params);
                $HashValue = $eGHL_Hash->generateHashValueForPaymentInfo(strtoupper($this->name), $this->merchantPass);
                $this->params['HashValue'] = $HashValue;

                $Validator = validatorFactory::create($this->name, $this->params);
                $this->params = $Validator->validate();
            }
            catch(Exception $e){
                die("$e");
            }
        }

        /** 
         * Boolean $validateResponse: if set true it does hashValue checking on response
         * returns self object
        */
        public function sendRequest($validateResponse = true){
            try{
                $client = new Client();
                $response = $client->request('POST', $this->paymentURL(), [
                    'form_params' => $this->params
                ]);
                $code = $response->getStatusCode();
                $reason = $response->getReasonPhrase();
                switch($code){
                    case 200:
                        $this->ResponseData = $this->formatOutput($response->getBody()->getContents());
                        if($validateResponse===true){
                            $this->validateResponse();
                        }
                        return $this;
                    break;
                    default:
                        die('Response Failed: ('.$code.') '.$reason);
                    break;
                }
            }
            catch(Exception $e){
                die("$e");
            }
        }

        private function paymentURL(){
            switch($this->testMode){
                case true:
                    return "https://pay.e-ghl.com/ipgsg/payment.aspx";
                break;
                case false:
                    return "https://securepay.e-ghl.com/IPG/payment.aspx";
                break;
                case 'PH':
                    return "http://test2ph.ghl.com:86/IPGSG/Payment.aspx";
                break;
            }
        }

        protected function formatOutput($ResponseBody){
            $array = array();
            parse_str($ResponseBody, $array);
            return $array;
        }

        /** 
         * Validates Response from eGHL by matching HashValue
         * Throws exception if invalid
        */
        public function validateResponse(){
            $eGHL_Hash = new eGHL_Hash($this->ResponseData);
            $hashValue = $eGHL_Hash->generateHashValueForPaymentInfo('HashValue', $this->merchantPass, true);
            if($this->ResponseData['HashValue'] !== $hashValue){
                $message = 'HashValue missmatched >> Expected['.$hashValue.'] Recieved['.$this->ResponseData['HashValue'].']';
                throw new Exception($message);
            }
        }

        /** 
         * Returns response data recieved from eGHL in the form of associative array
        */
        public function getResponse(){
            return $this->ResponseData;
        }

        public function isSuccess(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='0');
        }

        public function isFail(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='1');
        }

        public function isPending(){
            if(is_null($this->ResponseData)){
                $this->sendRequest();
            }
            return (isset($this->ResponseData['TxnStatus']) && $this->ResponseData['TxnStatus']==='2');
        }
    }
?>