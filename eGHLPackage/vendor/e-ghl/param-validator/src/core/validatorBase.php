<?php

Namespace eGHL\ParamValidator\core;

use eGHL\Exception;

class validatorBase{
    protected $params = array();

    private $data_types = array(
        "A" => "Alphabetic",
        "AN" => "AlphaNumeric",
        "N" => "Numeric"
    );

    public function __construct($Params = array()){
        $this->params = $Params;
        $this->validateMeta();
    }

    /**
     * if valid; Returns void
     * if invalid; Throws Exception
     **/
    private function validateMeta(){
        foreach($this->meta as $param=>$data){
            if(!isset($data['type'])){
                $msg = "Meta structure definition is invalid for '".$this->type."' (type not defined for param $param)";
                throw new Exception($msg);
            }
            if(!isset($data['maxLength'])){
                $msg = "Meta structure definition is invalid for '".$this->type."' (maxLength not defined for param $param)";
                throw new Exception($msg);
            }
            if(!isset($data['isReq'])){
                $msg = "Meta structure definition is invalid for '".$this->type."' (isReq not defined for param $param)";
                throw new Exception($msg);
            }
        }
    }

    /**
     * if valid; Returns void
     * if invalid; Throws Exception
     **/
    public function validate(){
        foreach($this->meta as $name=>$ctr){
            $val = '';
            if(isset($this->params[$name])){
                $val = $this->params[$name];
            }            
            if($ctr['isReq']===true && (!isset($this->params[$name]) || $this->params[$name]=='' || is_null($this->params[$name])) ){
                $msg = "Param '$name' is required";
                throw new Exception($msg);
            }
            elseif(is_string($ctr['isReq'])){
                $class_name = 'eGHL\ParamValidator\\'.$this->type.'Request';
                $class_methods = get_class_methods($class_name);
                if(!in_array($ctr['isReq'], $class_methods)){
                    $msg = "Conditional method for isReq not defied in class ".$class_name;
                    throw new Exception($msg);
                }
                try{
                    if(is_string($this->$ctr['isReq'])){
                        $this->$ctr['isReq']();
                    }
                }
                catch(\Exception $e){
                    $msg = "Conditional Method '".$ctr['isReq']."' for isReq throwed Esception >> ".$e->getMessage();
                    throw new Exception($msg);
                }
            }
            
            if(!$this->validateMaxLen($val,$ctr['maxLength'])){
                $msg = "Param '$name' exceeds maxlength i.e. ".$ctr['maxLength'];
                throw new Exception($msg);
            }
            
            if(isset($this->params[$name]) && !$this->validateParamType($val,$ctr['type'])){
                $msg = "Param '$name' expects data type to be ".(isset($this->data_types[$ctr['type']])?$this->data_types[$ctr['type']]:$ctr['type']);
                throw new Exception($msg);
            }

            if(isset($this->params[$name])){
                $this->validateParamValue($val,$name);
            }
        }

        return $this->params;
    }

    /**
     * if valid; Returns true
     * if invalid; Returns false
     **/
    private function validateMaxLen($val,$maxLength){
        if(mb_strlen($val)>$maxLength){
            return false;
        }
        return true;
    }

    /**
     * if valid; Returns true
     * if invalid; Returns false
     **/
    private function validateParamType($val,$paramType){
        if(strtolower($paramType)=='a'){
            if(!ctype_alpha($val)){
                return false;
            }
        }
        elseif(strtolower($paramType)=='n'){
            if(!is_numeric($val)){
                return false;
            }
        }
        return true;
    }

    private function validateParamValue($val,$paramName){
        switch($paramName){
            case "TransactionType":
                $isValid = $this->isValidTransactionType($val, $paramName);
            break;
            case "Amount":
            case "B4TaxAmt":
            case "TaxAmt":
            case "SettleAmount":
                $isValid = $this->isValidAmount($val, $paramName);
            break;
            case "CustIP":
                $isValid = true; //$this->isValidIP($val, $paramName);
            break;
            case "CustEmail":
                $isValid = $this->isValidEMail($val, $paramName);
            break;
            case "MerchantReturnURL":
            case "MerchantApprovalURL":
            case "MerchantUnApprovalURL":
            case "MerchantCallBackURL":
                $isValid = $this->isValidURL($val, $paramName);
            break;
            default:
                $isValid = true;
            break;
        }

        if($isValid!==true){
            $msg = "Parmeter '$paramName' value is invalid. Reason: $isValid";
            throw new Exception($msg);
        }
    }

    /**
     * if valid; Returns true
     * if invalid; Returns string message
     **/
    private function isValidAmount($val, $paramName){
        if(is_numeric($val)){
            $str_amt = strval($val);
            if(false === strpos($str_amt,',')){
                if( strpos($str_amt,'.') - (strlen($str_amt)-(2+1)) != 0){
                    return "Amount [$val] must be 2 decimal places";
                }
                else{
                    return true;
                }
            }
            else{
                return "',' Sign must be excluded in amount [$val]";
            }
        }
        else{
            return "The Amount [$val] must be numeric";
        }
    }

    /**
     * if valid; Returns true
     * if invalid; Returns string message
     **/
    private function isValidIP($val, $paramName){
        $filtered = filter_var($val, FILTER_VALIDATE_IP);
        if($filtered === false){
            return "IP [$val] is invalid";
        }
        else{
            $this->params[$paramName] = $filtered;
            return true;
        }
    }

    /**
     * if valid; Returns true
     * if invalid; Returns string message
     **/
    private function isValidURL($val, $paramName){
        if(strpos($val,';') === false){
            $val = filter_var($val, FILTER_SANITIZE_URL);
        }
        else{
            $val = filter_var(str_replace(';','&',$val), FILTER_SANITIZE_URL);
        }

        $filtered = filter_var($val, FILTER_VALIDATE_URL);
        if($filtered === false){
            return "URL [$val] is invalid";
        }
        else{
            $this->params[$paramName] = str_replace('&',';',$filtered);
            return true;
        }
    }

    /**
     * if valid; Returns true
     * if invalid; Returns string message
     **/
    private function isValidEMail($val, $paramName){
        // Remove illegal characters
        $val = filter_var($val, FILTER_SANITIZE_EMAIL);
        $filtered = filter_var($val, FILTER_VALIDATE_EMAIL);
        if($filtered === false){
            return "Email [$val] is invalid";
        }
        else{
            $this->params[$paramName] = $filtered;
            return true;
        }
    }

    /**
     * if valid; Returns true
     * if invalid; Returns string message
     **/
    private function isValidTransactionType($val, $paramName){
        switch(strtolower($this->type)){
            case 'query':
                if(strtolower($val)=="query"){
                    return true;
                }
                else{
                    return "Value must be QUERY";
                }
            break;
            case 'capture':
                if(strtolower($val)=="capture"){
                    return true;
                }
                else{
                    return "Value must be CAPTURE";
                }
            break;
            case 'reversal':
                if(strtolower($val)=="rsale"){
                    return true;
                }
                else{
                    return "Value must be RSALE";
                }
            break;
            case 'refund':
                if(strtolower($val)=="refund"){
                    return true;
                }
                else{
                    return "Value must be REFUND";
                }
            break;
            case 'settlement':
                if(strtolower($val)=="settle"){
                    return true;
                }
                else{
                    return "Value must be SETTLE";
                }
            break;
            default:
                return true;
            break;
        }
    }

}

?>