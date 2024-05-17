<?php

namespace eGHL\ParamValidator\tests;
use eGHL\ParamValidator\PaymentRequest;

class PaymentRequestTest extends \PHPUnit_Framework_TestCase
{
    private $params = array();
    
    private function initParams(){
        $this->params = array(
            'TransactionType' => $this->generateRandomString(7,'A'),
            'PymtMethod' => $this->generateRandomString(3,'A'),
            'ServiceID' => $this->generateRandomString(3,'AN'),
            'PaymentID' => $this->generateRandomString(20,'AN'),
            'OrderNumber' => $this->generateRandomString(20,'AN'),
            'PaymentDesc' => $this->generateRandomString(100,'AN'),
            'MerchantReturnURL' => 'http://example.com/return?p1=1&p2=add',
            'Amount' => '100.00',
            'CurrencyCode' => $this->generateRandomString(3,'A'),
            'HashValue' => $this->generateRandomString(64,'AN'),
            'CustIP' => '127.0.0.1',
            'CustName' => $this->generateRandomString(50,'AN'),
            'CustEmail' => 'abc@123.com',
            'CustPhone' => $this->generateRandomString(25,'AN'),
            'B4TaxAmt' => '90.00',
            'TaxAmt' => '10.00',
            'MerchantName' => $this->generateRandomString(25,'AN'),
            'CustMAC' => $this->generateRandomString(50,'AN'),
            'MerchantApprovalURL' => 'http://example.com/approval?p1=1&p2=add',
            'MerchantUnApprovalURL' => 'http://example.com/unapproval?p1=1&p2=add',
            'MerchantCallBackURL' => 'http://example.com/callback?p1=1&p2=add',
            'LanguageCode' => $this->generateRandomString(2,'A'),
            'PageTimeout' => $this->generateRandomString(4,'N'),
            'CardHolder' => $this->generateRandomString(30,'AN'),
            'CardNo' => $this->generateRandomString(19,'N'),
            'CardExp' => $this->generateRandomString(6,'N'),
            'CardCVV2' => $this->generateRandomString(4,'N'),
            'IssuingBank' => $this->generateRandomString(30,'AN'),
            'BillAddr' => $this->generateRandomString(150,'AN'),
            'BillPostal' => $this->generateRandomString(15,'AN'),
            'BillCity' => $this->generateRandomString(100,'ANS'),
            'BillRegion' => $this->generateRandomString(30,'ANS'),
            'BillCountry' => $this->generateRandomString(2,'A'),
            'ShipAddr' => $this->generateRandomString(150,'ANS'),
            'ShipPostal' => $this->generateRandomString(15,'AN'),
            'ShipCity' => $this->generateRandomString(100,'ANS'),
            'ShipRegion' => $this->generateRandomString(30,'ANS'),
            'ShipCountry' => $this->generateRandomString(2,'A'),
            'SessionID' => $this->generateRandomString(100,'AN'),
            'TokenType' => $this->generateRandomString(3,'A'),
            'Token' => $this->generateRandomString(50,'ANS'),
            'Param6' => $this->generateRandomString(50,'ANS'),
            'Param7' => $this->generateRandomString(50,'ANS'),
            'EPPMonth' => $this->generateRandomString(2,'N'),
            'PromoCode' => $this->generateRandomString(100,'AN')
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
            case "ANS":
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ-,#_(); ';
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
        $parent = get_parent_class('eGHL\ParamValidator\PaymentRequest');
        $this->assertEquals('eGHL\ParamValidator\core\validatorBase', $parent);
    }

    public function testAllCorrectParams(){
        $exception = false;
        $this->initParams();
        try{
            $Request = new PaymentRequest($this->params);
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
            $Request = new PaymentRequest($this->params);
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
        $this->params[$p_nane] = $this->generateRandomString(7,'A')."ABC";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(4,'A')."123";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
    }

    public function testPymtMethodError(){
        $this->initParams();
        $p_nane = 'PymtMethod';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(3,'A')."ABC";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'A')."12";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
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

    public function testPaymentIDError(){
        $this->initParams();
        $p_nane = 'PaymentID';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(20,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testOrderNumberError(){
        $this->initParams();
        $p_nane = 'OrderNumber';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(40,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testPaymentDescError(){
        $this->initParams();
        $p_nane = 'PaymentDesc';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(100,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testMerchantReturnURLError(){
        $this->initParams();
        $p_nane = 'MerchantReturnURL';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(255,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        // Invalid URL Exception
        $this->params[$p_nane] = 'www.example.com/index.php?p1=abc&p2=def';
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
    }

    public function testAmountError(){
        $this->initParams();
        $p_nane = 'Amount';
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

    public function testCurrencyCodeError(){
        $this->initParams();
        $p_nane = 'CurrencyCode';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(3,'A')."ABC";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'A')."12";
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

    public function testCustIPError(){
        $this->initParams();
        $p_nane = 'CustIP';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(45,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        // invalid IP Exception
        $this->params[$p_nane] = "invalidIP";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
    }

    public function testCustNameError(){
        $this->initParams();
        $p_nane = 'CustName';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(50,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testCustEmailError(){
        $this->initParams();
        $p_nane = 'CustEmail';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(60,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        // Invalid email Exception
        $this->params[$p_nane] = "Invalid Email";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
    }

    public function testCustPhoneError(){
        $this->initParams();
        $p_nane = 'CustPhone';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(25,'AN')."ABC";
        $exceptionCount += $this->RunValidator();

        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testB4TaxAmtError(){
        $this->initParams();
        $p_nane = 'B4TaxAmt';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(15,'N')."12";
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

        $this->assertEquals(5, $exceptionCount);
    }

    public function testTaxAmtError(){
        $this->initParams();
        $p_nane = 'TaxAmt';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(15,'N')."12";
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

        $this->assertEquals(5, $exceptionCount);
    }

    public function testMerchantNameError(){
        $this->initParams();
        $p_nane = 'MerchantName';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(25,'AN')."AB";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testCustMACError(){
        $this->initParams();
        $p_nane = 'CustMAC';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(50,'AN')."AB";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testMerchantApprovalURLError(){
        $this->initParams();
        $p_nane = 'MerchantApprovalURL';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(255,'AN')."AB";
        $exceptionCount += $this->RunValidator();

        // Invalid URL Exception
        $this->params[$p_nane] = 'www.example.com/index.php?p1=abc&p2=def';
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testMerchantUnApprovalURLError(){
        $this->initParams();
        $p_nane = 'MerchantUnApprovalURL';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(255,'AN')."AB";
        $exceptionCount += $this->RunValidator();

        // Invalid URL Exception
        $this->params[$p_nane] = 'www.example.com/index.php?p1=abc&p2=def';
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testMerchantCallBackURLError(){
        $this->initParams();
        $p_nane = 'MerchantCallBackURL';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(255,'AN')."AB";
        $exceptionCount += $this->RunValidator();

        // Invalid URL Exception
        $this->params[$p_nane] = 'www.example.com/index.php?p1=abc&p2=def';
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testLanguageCodeError(){
        $this->initParams();
        $p_nane = 'LanguageCode';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(2,'A')."AB";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'A')."1";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testPageTimeoutError(){
        $this->initParams();
        $p_nane = 'PageTimeout';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(4,'N')."12";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(2,'N')."AB";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testCardHolderError(){
        $this->initParams();
        $p_nane = 'CardHolder';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(30,'AN')."A2";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testCardNoError(){
        $this->initParams();
        $p_nane = 'CardNo';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(19,'N')."12";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(17,'N')."AB";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testCardExpError(){
        $this->initParams();
        $p_nane = 'CardExp';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(6,'N')."12";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(4,'N')."AB";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testCardCVV2Error(){
        $this->initParams();
        $p_nane = 'CardCVV2';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(4,'N')."12";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(4,'N')."AB";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testIssuingBankError(){
        $this->initParams();
        $p_nane = 'IssuingBank';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(30,'AN')."A2";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testBillAddrError(){
        $this->initParams();
        $p_nane = 'BillAddr';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(250,'ANS')."A,";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testBillPostalError(){
        $this->initParams();
        $p_nane = 'BillPostal';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(15,'AN')."A2";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testBillCityError(){
        $this->initParams();
        $p_nane = 'BillCity';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(100,'ANS')."A B";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testBillRegionError(){
        $this->initParams();
        $p_nane = 'BillRegion';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(30,'ANS')."A B";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testBillCountryError(){
        $this->initParams();
        $p_nane = 'BillCountry';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(2,'A')."AB";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'A')."1";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testShipAddrError(){
        $this->initParams();
        $p_nane = 'ShipAddr';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(250,'ANS')."A,";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testShipPostalError(){
        $this->initParams();
        $p_nane = 'ShipPostal';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(15,'AN')."A2";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testShipCityError(){
        $this->initParams();
        $p_nane = 'ShipCity';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(100,'A')."A B";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testShipRegionError(){
        $this->initParams();
        $p_nane = 'ShipRegion';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(30,'ANS')."A B";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testShipCountryError(){
        $this->initParams();
        $p_nane = 'ShipCountry';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(2,'A')."AB";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'A')."1";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testSessionIDError(){
        $this->initParams();
        $p_nane = 'SessionID';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(100,'AN')."A2";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testTokenTypeError(){
        $this->initParams();
        $p_nane = 'TokenType';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(3,'A')."AB";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'A')."12";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(2, $exceptionCount);
    }

    public function testTokenError(){
        $this->initParams();
        $p_nane = 'Token';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(50,'ANS')."A1";
        $exceptionCount += $this->RunValidator();

        // Conditional Required Exception 1
        $this->params[$p_nane] = '';
        $exceptionCount += $this->RunValidator();

        // Conditional Required Exception 2
        unset($this->params[$p_nane]);
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(3, $exceptionCount);
    }

    public function testParam6Error(){
        $this->initParams();
        $p_nane = 'Param6';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(50,'ANS')."A1";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testParam7Error(){
        $this->initParams();
        $p_nane = 'Param7';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(50,'ANS')."A1";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testEPPMonthError(){
        $this->initParams();
        $p_nane = 'EPPMonth';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(2,'N')."12";
        $exceptionCount += $this->RunValidator();

        // DataType Exception
        $this->params[$p_nane] = $this->generateRandomString(1,'N')."1";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

    public function testPromoCodeError(){
        $this->initParams();
        $p_nane = 'PromoCode';
        $exceptionCount = 0;

        // MaxLength Exception
        $this->params[$p_nane] = $this->generateRandomString(100,'AN')."A1";
        $exceptionCount += $this->RunValidator();

        $this->assertEquals(1, $exceptionCount);
    }

}