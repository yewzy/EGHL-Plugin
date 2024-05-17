<?php

Namespace eGHL\ParamValidator;

use eGHL\ParamValidator\core\validatorBase;
use eGHL\Exception;

class RefundRequest extends validatorBase{

    protected $type = 'Refund';

    protected $meta = array(
        'TransactionType' => array(
            'type' => 'A',
            'maxLength' => '6',
            'isReq' => true
        ),
        'PymtMethod' => array(
            'type' => 'A',
            'maxLength' => '3',
            'isReq' => true
        ),
        'ServiceID' => array(
            'type' => 'AN',
            'maxLength' => '3',
            'isReq' => true
        ),
        'PaymentID' => array(
            'type' => 'AN',
            'maxLength' => '20',
            'isReq' => true
        ),
        'Amount' => array(
            'type' => 'N',
            'maxLength' => '15',
            'isReq' => true
        ),
        'CurrencyCode' => array(
            'type' => 'A',
            'maxLength' => '3',
            'isReq' => true
        ),
        'HashValue' => array(
            'type' => 'AN',
            'maxLength' => '64',
            'isReq' => true
        )
    );

    public function __construct($Params = array()){
        parent::__construct($Params);
    }
}

?>