<?php

namespace eGHL\ParamValidator\tests;
use eGHL\ParamValidator\SettlementRequest;

class SettlementRequestTest extends \PHPUnit_Framework_TestCase
{
    private $params = array();
    
    private function initParams(){
        $this->params = array(
            'TransactionType' => 'SETTLE',
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'SettleTAID' => $this->generateRandomString(10,'N'),
            'SettleAmount' => '100.00',
            'SettleTxnCount' => $this->generateRandomString(3,'N'),
            'HashValue' => $this->generateRandomString(64,'AN')
        );
    }
    
    private function generateRandomString($length = 10, $type ="AN") {
        switch($type){
            case "A":
                $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
            case "AN":
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
            case "N":
                $characters = '0123456789';
            break;
            default:
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        }

        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function testExtendsBase(){
        $parent = get_parent_class('eGHL\ParamValidator\SettlementRequest');
        $this->assertEquals('eGHL\ParamValidator\core\validatorBase', $parent);
    }

    public function testAllCorrectParams(){
        $exception = false;
        $this->initParams();
        try{
            $Request = new SettlementRequest($this->params);
            $Request->validate();
        }
        catch(\Exception $e){
            echo "Caught Exception: $e";
            $exception = true;
        }
        $this->assertEquals(false, $exception);
    }

    private function RunValidator(){
        $exceptionCount = 0;
        try{
            $Request = new SettlementRequest($this->params);
            $Request->validate();
        }
        catch(\Exception $e){
            echo "Caught Exception: $e";
            $exceptionCount++;
        }
        return $exceptionCount;
    }

    public function testTransactionTypeError(){
        $this->initParams();
        $p_nane = 'TransactionType';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(6,'A')."ABC";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(3,'A')."123";
        $exceptionCount += $this->RunValidator();

        // Ivalid value Exception
        $this->params[$p_nane] = "SALE";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(4, $exceptionCount);
    }

    public function testServiceIDError(){
        $this->initParams();
        $p_nane = 'ServiceID';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(3,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testSettleTAIDError(){
        $this->initParams();
        $p_nane = 'SettleTAID';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(10,'N')."123";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(7,'N')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
    }

    public function testSettleAmountError(){
        $this->initParams();
        $p_nane = 'SettleAmount';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(15,'N')."11";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(12,'N')."ABC";
        $exceptionCount += $this->RunValidator();

        // Invalid Amount Exception 1
        $this->params[$p_nane] = '1000.0';
        $exceptionCount += $this->RunValidator();

        // Invalid Amount Exception 2
        $this->params[$p_nane] = '1000.012';
        $exceptionCount += $this->RunValidator();

        // Invalid Amount Exception 3
        $this->params[$p_nane] = '1,000.00';
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(6, $exceptionCount);
    }

    public function testSettleTxnCountError(){
        $this->initParams();
        $p_nane = 'SettleTxnCount';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(3,'N')."123";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'N')."AB";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
    }

    public function testHashValueError(){
        $this->initParams();
        $p_nane = 'HashValue';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(64,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

}