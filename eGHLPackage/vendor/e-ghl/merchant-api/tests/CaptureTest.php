<?php

namespace eGHL\MerchantAPI\tests;
use eGHL\MerchantAPI\Capture;

class CaptureTest extends \PHPUnit_Framework_TestCase
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
            'PaymentID' => 'IPGJAW20190111600001',
            'Amount' => '1.00',
            'CurrencyCode' => 'MYR'
        );
    }

    public function testRequestSend(){
        $this->initParams();
        $flg_success = false;
        $API = new Capture($this->params, $this->merchantPass, true);
        $response = $API->sendRequest(false)->getResponse();
        if(is_array($response) && isset($response['TxnStatus'])){
            $flg_success = true;
        }
        $this->assertEquals(true, $flg_success);
    }
}

?>