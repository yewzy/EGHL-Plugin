<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 23/3/18
 * Time: 11:11 AM
 */

namespace Omnipay\eGHL\Message;

use Omnipay\Common\Message\NotificationInterface;

class CompletePurchaseResponse extends AbstractResponse implements NotificationInterface
{
    public function isSuccessful()
    {
        return NotificationInterface::STATUS_COMPLETED == $this->getTransactionStatus();
    }

    public function isPending()
    {
        return NotificationInterface::STATUS_PENDING == $this->getTransactionStatus();
    }

    public function isCancelled()
    {
        return NotificationInterface::STATUS_FAILED == $this->getTransactionStatus() && 'Buyer cancelled' == $this->getMessage();
    }

    public function getTransactionId()
    {
        return $this->data['PaymentID'];
    }

    /**
     * MessageInterface NotificationInterface method
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData(){
        return $this->data;
    }

    /**
     * NotificationInterface method
     * Was the transaction successful?
     *
     * @return string Transaction status, one of {@see STATUS_COMPLETED}, {@see #STATUS_PENDING},
     * or {@see #STATUS_FAILED}.
     */
    public function getTransactionStatus(){
        if(isset($this->data['TxnStatus'])){
            switch($this->data['TxnStatus']){
                case '0':
                    return NotificationInterface::STATUS_COMPLETED;
                break;
                case '1':
                    return NotificationInterface::STATUS_FAILED;
                break;
                case '2':
                    return NotificationInterface::STATUS_PENDING;
                break;
            }
        }
        else{
            return false;
        }
    }

    /**
     * NotificationInterface method
     * Response Message
     *
     * @return string A response message from the payment gateway
     */
    public function getMessage(){
        if(isset($this->data['TxnMessage'])){
            return $this->data['TxnMessage'];
        }
        else{
            return false;
        }
    }

    /**
     * NotificationInterface method
     * Gateway Reference
     *
     * @return string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference(){
        if(isset($data['TxnID'])){
            return $data['TxnID'];
        }
        else{
            return false;
        }
    }

}