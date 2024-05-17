<?php

namespace Omnipay\eGHL\Message;


use Omnipay\Common\Exception\InvalidRequestException;

class PurchaseRequest extends AbstractRequest
{
    public function send()
    {
        $data = $this->getData();

        return $this->sendData($data);
    }

    public function sendData($data){
        return new PurchaseResponse($this, $data);
    }

    public function getData(){
        $data = $this->getParameters();
        return $data;
    }

    public function addParams($data){
        foreach($data as $key=>$value){
            $this->setParameter($key, $value);
        }
    }
   
}