<?php

Namespace eGHL\ParamValidator\core;

use eGHL\Exception;

class validatorFactory{
    public static function create($RequestType, $params = array())
    {
        $class = "\\eGHL\\ParamValidator\\".$RequestType."Request";
        if(class_exists($class)){
            return new $class($params);
        }
        else{
            $msg = 'class "'.$class.'" Does not exist';
            throw new Exception($msg);
        }
    }
}