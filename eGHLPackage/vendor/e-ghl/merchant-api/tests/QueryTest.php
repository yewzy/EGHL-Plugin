<?php

namespace eGHL\MerchantAPI\tests;
use eGHL\MerchantAPI\Query;

class QueryTest extends \PHPUnit_Framework_TestCase
{
    private $ServiceID;
    private $merchantPass;
    private $params = array();

    private function initParams(){
        $this->ServiceID = 'SIT';
        $this->merchantPass = 'sit12345';
        $this->params = array(
            'PymtMethod' => 'DD',
            'ServiceID' => $this->ServiceID ,
            'PaymentID' => '8308110000',
            'Amount' => '123.10',
            'CurrencyCode' => 'MYR'
        );
    }

    public function testRequestSend(){
        $this->initParams();
        $flg_success = false;
        $API = new Query($this->params, $this->merchantPass, true);
        $response = $API->sendRequest()->getResponse();
        if(is_array($response) && isset($response['TxnExists'])){
            $flg_success = true;
        }
        $this->assertEquals(true, $flg_success);
    }
}

?>