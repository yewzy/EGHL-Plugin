<?php

namespace eGHL\MerchantAPI\core;

use eGHL\Exception;

class APIFactory{
    public static function create($api_name, $params, $merchantPass, $testMode)
    {
        $class = "\\eGHL\\MerchantAPI\\$api_name";
        if(class_exists($class)){
            return new $class($params, $merchantPass, $testMode);
        }
        else{
            $msg = 'class "'.$class.'" Does not exist';
            throw new Exception($msg);
        }
    }
}