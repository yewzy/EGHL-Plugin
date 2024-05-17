<?php

namespace Omnipay\eGHL\Message;

use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @var  AbstractRequest
     */
    protected $request;

    private $testUrl = 'https://test2pay.ghl.com/IPGSG/Payment.aspx';

    private $liveUrl = 'https://securepay.e-ghl.com/IPG/Payment.aspx';

    public function __construct(AbstractRequest $request, $data)
    {
        parent::__construct($request, $data);
    }

    public function getRedirectUrl()
    {
        return $this->request->getTestMode() ? $this->testUrl : $this->liveUrl;
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return true;
    }

    public function isTransparentRedirect()
    {
        return true;
    }

    public function getTransactionId()
    {
        return $this->data['PaymentID'];
    }

    public function getRedirectMethod()
    {
        return 'POST';
    }

    public function getRedirectData()
    {
        $allowedParams = array(
            'TransactionType',
            'PymtMethod',
            'ServiceID',
            'PaymentID',
            'OrderNumber',
            'PaymentDesc',
            'MerchantReturnURL',
            'Amount',
            'CurrencyCode',
            'HashValue',
            'CustIP',
            'CustName',
            'CustEmail',
            'CustPhone',
            'B4TaxAmt',
            'TaxAmt',
            'MerchantName',
            'CustMAC',
            'MerchantApprovalURL',
            'MerchantUnApprovalURL',
            'MerchantCallBackURL',
            'LanguageCode',
            'PageTimeout',
            'CardHolder',
            'CardNo',
            'CardExp',
            'CardCVV2',
            'IssuingBank',
            'BillAddr',
            'BillPostal',
            'BillCity',
            'BillRegion',
            'BillCountry',
            'ShipAddr',
            'ShipPostal',
            'ShipPostal',
            'ShipRegion',
            'ShipCountry',
            'SessionID',
            'TokenType',
            'Token',
            'Param6',
            'Param7',
            'EPPMonth',
            'PromoCode'
        );
        $toSend = array();
        foreach($this->data as $key=>$value){
            if(in_array($key, $allowedParams)){
                $toSend[$key] = $value;
            }
        }
        return $toSend;
    }

}