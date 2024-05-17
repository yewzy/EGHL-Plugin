<?php

Namespace eGHL\ParamValidator;

use eGHL\ParamValidator\core\validatorBase;
use eGHL\Exception;

class PaymentRequest extends validatorBase{

    protected $type = 'Payment';

    protected $meta = array(
        'TransactionType' => array(
            'type' => 'A',
            'maxLength' => '7',
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
        'OrderNumber' => array(
            'type' => 'AN',
            'maxLength' => '40',
            'isReq' => true
        ),
        'PaymentDesc' => array(
            'type' => 'AN',
            'maxLength' => '100',
            'isReq' => true
        ),
        'MerchantReturnURL' => array(
            'type' => 'AN',
            'maxLength' => '255',
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
        ),
        'CustIP' => array(
            'type' => 'AN',
            'maxLength' => '45',
            'isReq' => true
        ),
        'CustName' => array(
            'type' => 'AN',
            'maxLength' => '50',
            'isReq' => true
        ),
        'CustEmail' => array(
            'type' => 'AN',
            'maxLength' => '60',
            'isReq' => true
        ),
        'CustPhone' => array(
            'type' => 'AN',
            'maxLength' => '25',
            'isReq' => true
        ),
        'B4TaxAmt' => array(
            'type' => 'N',
            'maxLength' => '15',
            'isReq' => false
        ),
        'TaxAmt' => array(
            'type' => 'N',
            'maxLength' => '15',
            'isReq' => false
        ),
        'MerchantName' => array(
            'type' => 'AN',
            'maxLength' => '25',
            'isReq' => false
        ),
        'CustMAC' => array(
            'type' => 'AN',
            'maxLength' => '50',
            'isReq' => false
        ),
        'MerchantApprovalURL' => array(
            'type' => 'AN',
            'maxLength' => '255',
            'isReq' => false
        ),
        'MerchantUnApprovalURL' => array(
            'type' => 'AN',
            'maxLength' => '255',
            'isReq' => false
        ),
        'MerchantCallBackURL' => array(
            'type' => 'AN',
            'maxLength' => '255',
            'isReq' => false
        ),
        'LanguageCode' => array(
            'type' => 'A',
            'maxLength' => '2',
            'isReq' => false
        ),
        'PageTimeout' => array(
            'type' => 'N',
            'maxLength' => '4',
            'isReq' => false
        ),
        'CardHolder' => array(
            'type' => 'AN',
            'maxLength' => '30',
            'isReq' => false
        ),
        'CardNo' => array(
            'type' => 'N',
            'maxLength' => '19',
            'isReq' => false
        ),
        'CardExp' => array(
            'type' => 'N',
            'maxLength' => '6',
            'isReq' => false
        ),
        'CardCVV2' => array(
            'type' => 'N',
            'maxLength' => '4',
            'isReq' => false
        ),
        'IssuingBank' => array(
            'type' => 'AN',
            'maxLength' => '30',
            'isReq' => false
        ),
        'BillAddr' => array(
            'type' => 'ANS',
            'maxLength' => '250',
            'isReq' => false
        ),
        'BillPostal' => array(
            'type' => 'AN',
            'maxLength' => '15',
            'isReq' => false
        ),
        'BillCity' => array(
            'type' => 'ANS',
            'maxLength' => '100',
            'isReq' => false
        ),
        'BillRegion' => array(
            'type' => 'ANS',
            'maxLength' => '30',
            'isReq' => false
        ),
        'BillCountry' => array(
            'type' => 'A',
            'maxLength' => '2',
            'isReq' => false
        ),
        'ShipAddr' => array(
            'type' => 'ANS',
            'maxLength' => '250',
            'isReq' => false
        ),
        'ShipPostal' => array(
            'type' => 'AN',
            'maxLength' => '15',
            'isReq' => false
        ),
        'ShipCity' => array(
            'type' => 'ANS',
            'maxLength' => '100',
            'isReq' => false
        ),
        'ShipRegion' => array(
            'type' => 'ANS',
            'maxLength' => '30',
            'isReq' => false
        ),
        'ShipCountry' => array(
            'type' => 'A',
            'maxLength' => '2',
            'isReq' => false
        ),
        'SessionID' => array(
            'type' => 'AN',
            'maxLength' => '100',
            'isReq' => false
        ),
        'TokenType' => array(
            'type' => 'A',
            'maxLength' => '3',
            'isReq' => false
        ),
        'Token' => array(
            'type' => 'ANS',
            'maxLength' => '50',
            'isReq' => 'TokenRequiredCondition'
        ),
        'Param6' => array(
            'type' => 'ANS',
            'maxLength' => '50',
            'isReq' => false
        ),
        'Param7' => array(
            'type' => 'ANS',
            'maxLength' => '50',
            'isReq' => false
        ),
        'EPPMonth' => array(
            'type' => 'N',
            'maxLength' => '2',
            'isReq' => false
        ),
        'PromoCode' => array(
            'type' => 'AN',
            'maxLength' => '100',
            'isReq' => false
        )
    );

    public function __construct($Params = array()){
        parent::__construct($Params);
    }

    public function TokenRequiredCondition(){
        if(isset($this->params['TokenType']) && ( !isset($this->params['Token']) || $this->params['Token']=='' ) ){
            $ctr = $this->meta['Token'];
            $msg = "Parameter Token is required if parameter TokenType is defined";
            throw new Exception($msg);
        }
    }

}

?>