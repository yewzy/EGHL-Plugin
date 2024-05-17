<?php

namespace eGHL\MerchantAPI\tests;
use eGHL\MerchantAPI\core\APIFactory;

class APIFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $ServiceID;
    private $merchantPass;
    private $params = array();
    private $APIs = array(
        'Capture',
        'Query',
        'Refund',
        'Reversal'
    );

    private function initParams(){
        $this->ServiceID = 'SIT';
        $this->merchantPass = 'sit12345';
        $this->params_capture = array(
            'PymtMethod' => 'CC',
            'ServiceID' => $this->ServiceID ,
            'PaymentID' => 'IPGJAW20190111600001',
            'Amount' => '1.00',
            'CurrencyCode' => 'MYR'
        );
        $this->params_query = array(
            'PymtMethod' => 'DD',
            'ServiceID' => $this->ServiceID ,
            'PaymentID' => '8308110000',
            'Amount' => '123.10',
            'CurrencyCode' => 'MYR'
        );
        $this->params_refund = array(
            'PymtMethod' => 'CC',
            'ServiceID' => $this->ServiceID ,
            'PaymentID' => '130#1547432197655ef9',
            'Amount' => '1005.00',
            'CurrencyCode' => 'MYR'
        );
        $this->params_reversal = array(
            'PymtMethod' => 'CC',
            'ServiceID' => $this->ServiceID ,
            'PaymentID' => '130#1547432197655ef9',
            'Amount' => '1005.00',
            'CurrencyCode' => 'MYR'
        );
    }

    public function testRequestSend(){
        $this->initParams();
        $success_count = 0;

        foreach($this->APIs as $api_name){
            switch($api_name){
                case 'Capture':
                    $flg_validateResp = false;
                break;
                default:
                     $flg_validateResp = true;
                break;
            }

            $params_name = 'params_'.strtolower($api_name);
            $API = APIFactory::create($api_name, $this->$params_name, $this->merchantPass, true);
            $response = $API->sendRequest($flg_validateResp)->getResponse();
            if(is_array($response) && isset($response['TxnStatus'])){
                $success_count ++;
            }
        }

        $this->assertEquals(count($this->APIs), $success_count);
    }
}

?>