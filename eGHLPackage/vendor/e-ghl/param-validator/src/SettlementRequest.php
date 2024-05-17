<?php

Namespace eGHL\ParamValidator;

use eGHL\ParamValidator\core\validatorBase;
use eGHL\Exception;

class SettlementRequest extends validatorBase{

    protected $type = 'Settlement';

    protected $meta = array(
        'TransactionType' => array(
            'type' => 'A',
            'maxLength' => '6',
            'isReq' => true
        ),
        'ServiceID' => array(
            'type' => 'AN',
            'maxLength' => '3',
            'isReq' => true
        ),
        'SettleTAID' => array(
            'type' => 'N',
            'maxLength' => '10',
            'isReq' => true
        ),
        'SettleAmount' => array(
            'type' => 'N',
            'maxLength' => '15',
            'isReq' => true
        ),
        'SettleTxnCount' => array(
            'type' => 'N',
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