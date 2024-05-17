<?php

namespace eGHL\MerchantAPI\tests;
use eGHL\MerchantAPI\Refund;

class RefundTest extends \PHPUnit_Framework_TestCase
{
    private $ServiceID;
    private $merchantPass;
    private $params = array();

    private function initParams(){
        $this->ServiceID = 'SIT';
        $this->merchantPass = 'sit12345';
        $this->params = array(
            'PymtMethod' => 'CC',
            'ServiceID' => $this->ServiceID ,
            'PaymentID' => '130#1547432197655ef9',
            'Amount' => '1005.00',
            'CurrencyCode' => 'MYR'
        );
    }

    public function testRequestSend(){
        $this->initParams();
        $flg_success = false;
        $API = new Refund($this->params, $this->merchantPass, true);
        $response = $API->sendRequest()->getResponse();
        if(is_array($response) && isset($response['TxnStatus'])){
            $flg_success = true;
        }
        $this->assertEquals(true, $flg_success);
    }
}

?>