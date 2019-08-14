<?php

class Skebby_Smsnotifier_Model_Smsnotifier extends Mage_Core_Model_Abstract
{
    const DEBUG_LOG_NAME = 'Skebby_SmsNotifier';

    public static function log($message)
    {
        Mage::log(self::DEBUG_LOG_NAME.' - '.$message);
    }

    public function _construct()
    {
        $this->_init("smsnotifier/smsnotifier");
    }

    /** Gets list of Partners or Distributors
     *
     * @param  type $ofWhat
     * @return type
     */
    public function getFailedSmses()
    {
        $collection = $this->getCollection();
        $collection->addFieldToFilter("group_id"   ,array("eq"=>$ofWhat));
        $items = $collection->getItems();

        foreach ($items as $item) {
            $listToReturn[] = $item->getData();
        }

        return $listToReturn;

    }

    public function saveMessages(array $smsData)
    {
        $config = Mage::getModel('smsnotifier/config');
        $sender = $config->getChoosesender();
        $senderName = null;
        if ($smsData['message_type']  == Skebby_Smsnotifier_Adminhtml_Model_System_Config_Source_Messagetype::MESSAGE_TYPE_CLASSIC_PLUS) {
            if ($sender == 'storename') {
                $senderName = $config->getStoreName();
            } else {
                $senderName = $config->getSenderNumber();
            }
        }

        foreach ($smsData['recipients'] as $gsmNumber) { //save sms to database and retrieve message id's
            $reference = isset($smsData['reference']) ? $smsData['reference'] : null;
            $this->newSms($gsmNumber,$smsData['message'],'SENDING',$smsData['message_type'],$reference,$senderName);
            $smsId = $this->getData('id');
            $smsIds[$gsmNumber] = $smsId;
            $this->unsetData();

        }

        return $smsIds;
    }

    public function newSms($telephone,$message,$deliveryStatus,$messageType,$reference,$sender = false)
    {
        $this->setSender($sender);
        $this->setTelephone($telephone);
        $this->setDeliveryStatus($deliveryStatus);
        $this->setMessage($message);
        $this->setCreated(new Zend_Db_Expr('CURDATE()'));
        $this->setMessageType($messageType);
        if ($reference) {
            // Add reference
            $this->setDeliveryReference($reference);
        }
        $this->save();

    }

    public function setNewDeliveryStatus($smsId,$deliveryStatus)
    {
        $this->load($smsId);
        $this->setDeliveryStatus($deliveryStatus)->save();

    }

    public function isValidNumber($number)
    {
        return preg_match('/^\+(9[976]\d|8[987530]\d|6[987]\d|5[90]\d|42\d|3[875]\d|2[98654321]\d|9[8543210]|8[6421]|6[6543210]|5[87654321]|4[987654310]|3[9643210]|2[70]|7|1)\d{4,14}$/', $number);
    }

    public function getUserReference()
    {
        return substr(md5(rand(1,999999).'-'.microtime(true)),0,20);
    }

    /**
     * Get all phone numbers from customer address collection
     *
     *
     * @return type
     */

    public function getPhoneNumbers()
    {
        $col = Mage::getModel('customer/address')->getCollection()->addAttributeToSelect('telephone')->getItems();
        foreach ($col as $address) {
            $number = $address->getTelephone();
            if ($this->isValidNumber($number)) {
                $phones[] = Mage::helper('smsnotifier')->getPhoneNumber($number);
            }
        }
        $phones = array_unique($phones);

        return $phones;

    }

    public function sendBulkSMS($messageContent)
    {
        $config = Mage::getModel('smsnotifier/config');
        $numbers = $this->getPhoneNumbers();

        $message = array(
            'recipients'    => $numbers,
            'message'       => $messageContent,
            'message_type'  => $config->getMethod(),
        );
        if ($message['message_type']  == Skebby_Smsnotifier_Adminhtml_Model_System_Config_Source_Messagetype::MESSAGE_TYPE_CLASSIC_PLUS) {
            $message['reference']         = Mage::getModel('smsnotifier/smsnotifier')->getUserReference();
        }

        try {
                $apiClient = Mage::getModel('smsnotifier/apiClient');
                $savedIds = Mage::getModel('smsnotifier/smsnotifier')->saveMessages($message);
                $message['ids'] = $savedIds;

                $response = $apiClient->sendByCurl($message);

                $sendSuccessFully = Mage::helper('smsnotifier')->isSendSuccess($response);
                if (!$sendSuccessFully) {
                    Skebby_Smsnotifier_Model_Smsnotifier::log('Send error: '.var_export($response,true));
                    foreach ($savedIds as $id) {
                        $this->setNewDeliveryStatus($id,'ERROR');
                    }
                    Mage::throwException(Mage::helper('smsnotifier')->__('Error sending Message:').' '.Mage::helper('smsnotifier')->getSendError($response));
                }
                foreach ($savedIds as $id) {
                    $this->setNewDeliveryStatus($id,'SENT');
                }

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('smsnotifier')->__('Message sent successfully'));

            } catch (Exception $e) {

                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

            }

    }

}
