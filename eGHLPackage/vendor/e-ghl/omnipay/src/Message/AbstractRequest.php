<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 23/3/18
 * Time: 11:56 AM
 */

namespace Omnipay\eGHL\Message;


abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    public function getMerchantId()
    {
        return $this->getParameter('merchantId');
    }

    public function setMerchantId($merchantId)
    {
        return $this->setParameter('merchantId', $merchantId);
    }

    public function getMerchantPassword()
    {
        return $this->getParameter('merchantPassword');
    }

    public function setMerchantPassword($merchantPassword)
    {
        return $this->setParameter('merchantPassword', $merchantPassword);
    }

    public function getInvoiceNo()
    {
        return $this->getParameter('OrderNumber');
    }

    public function setInvoiceNo($invoiceNo)
    {
        return $this->setParameter('OrderNumber', $invoiceNo);
    }
}