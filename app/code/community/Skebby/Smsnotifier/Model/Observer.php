<?php
//File:  app/code/community/Skebby/Smsnotifier/Model/Observer.php

/**
 * @category   Skebby
 * @package    SmsnotifierApi
 * @copyright  Copyright (c) 2014 Skebby (http://skebby.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Skebby <supporto@skebby.com>
* ...
*/
class Skebby_Smsnotifier_Model_Observer
{
    public static $lastExecutionTime; //to avoid multiple SMS if status was changed more than one time per 2 second


    public function _construct()
    {
        if (!self::$lastExecutionTime)
            self::$lastExecutionTime = time();
    }

        /**
     * Generating alert notification if smsnotifierAPI account balance is low
     *
     * @return none
     */

    public function checkCreditLimit()
    {
        Mage::getModel('smsnotifier/apiClient')->checkCreditLimit();

    }

    /**
     * Check if authorization data is ok
     *
     * @return none
     */
    public function checkAuthorizationData()
    {
        $config =   Mage::getModel('smsnotifier/config');

        if ($config->isApiEnabled()==0) return;

        try {
            $credits = Mage::getModel('smsnotifier/apiClient')->getCredits();

            if ($credits=='UNKNOWN_COMMAND') {
                throw new Exception(Mage::helper('smsnotifier')->__($config::WRONG_AUTH_DATA));
            } else {
                Mage::getSingleton('core/session')->addSuccess(Mage::helper('smsnotifier')->__('Success. Logged into Smsnotifier API.'));
            }

        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
        }

    }

    public function orderStatusHistorySave($observer)
    {
        $config =   Mage::getModel('smsnotifier/config');
        if ($config->isApiEnabled()==0) return; //do nothing if api is disabled

        $history = $observer->getEvent()->getStatusHistory();

        //only for new status
        if (!$history->getId()) {

            $order = $history->getOrder();
            $newStatus =  $order->getData('status');
            $origStatus =  $order->getOrigData('status');

            if (time()-self::$lastExecutionTime<=2)
                return;

            self::$lastExecutionTime = time();

            //if status has changed run action
            if ($newStatus!=$origStatus) {

                $message = $config->getMessageTemplate($newStatus); //get template for new status (if active and exists)
                if (!$message)  //return if no active message template
                return;

                //getting last tracking number
                $tracking = Mage::getResourceModel('sales/order_shipment_track_collection')->setOrderFilter($order)->getData();

                if (!empty($tracking)) {
                    $last = count($tracking)-1;
                    $last_tracking_number = $tracking[$last]['track_number'];
                } else
                    $last_tracking_number = 'no_tracking'; //if no tracking number set "no_tracking" message for {TRACKINGNUMBER} template


                //getting order data to generate message template
                $messageOrderData['{NAME}'] = $order->getShippingAddress()->getData('firstname');
                $messageOrderData['{ORDERNUMBER}'] = $order->getIncrement_id();
                $messageOrderData['{ORDERSTATUS}']  = $newStatus;
                $messageOrderData['{TRACKINGNUMBER}'] = $last_tracking_number;
                $messageOrderData['{STORENAME}'] = $config->getStoreName();

                $message = strtr($message,$messageOrderData);

                $model = Mage::getModel('smsnotifier/smsnotifier');

                //prepare sms content
                $number = $order->getShippingAddress()->getData('telephone');
                $isValidNumber = $model->isValidNumber($number);
                $msg['recipients'][]    = Mage::helper('smsnotifier')->getPhoneNumber($number); //or getBillingAddress
                $msg['message']         = $message;
                $msg['message_type']         = $config->getMethod();
                if ($msg['message_type']  == Skebby_Smsnotifier_Adminhtml_Model_System_Config_Source_Messagetype::MESSAGE_TYPE_CLASSIC_PLUS) {
                    $msg['reference']         = $model->getUserReference();
                }

                //sending sms and getting API response

                try {

                    $apiClient = Mage::getModel('smsnotifier/apiClient');
                    $savedIds = $model->saveMessages($msg);
                    $msg['ids'] = $savedIds;
                    if ($isValidNumber) {
                        $response = $apiClient->sendByCurl($msg);
                    } else {
                        $response = false;
                    }
                    if ($response===false) {

                        //save delievery status for each message
                        foreach ($msg['ids'] as $messageId) {
                            $model->setNewDeliveryStatus($messageId,'ERROR');
                            $model->unsetData();
                            Mage::throwException('API CONNECTION ERROR.');
                        }

                    } else {

                        $sendSuccessFully = Mage::helper('smsnotifier')->isSendSuccess($response);
                        if (!$sendSuccessFully) {
                            foreach ($msg['ids'] as $messageId) {
                                $model->setNewDeliveryStatus($messageId,'ERROR');
                            }
                            Skebby_Smsnotifier_Model_Smsnotifier::log('Send error: '.var_export($response,true));
                            Mage::throwException(Mage::helper('smsnotifier')->__('Error sending Message:').' '.Mage::helper('smsnotifier')->getSendError($response));
                        }
                        foreach ($msg['ids'] as $messageId) {
                            $model->setNewDeliveryStatus($messageId,'SENT');
                        }
                        //@successs add comment to order
                        $newComment = Mage::helper('smsnotifier')->__('SMS notification sent (SMS id:').$msg['ids'][$msg['recipients'][0]].') ' ;
                        $history->setComment($newComment);
                        //Mage::getSingleton('core/session')->addSuccess($newComment);
                        $this->checkCreditLimit();
                    }

                } catch (Exception $e) {
                    $newComment = Mage::helper('smsnotifier')->__('SMS notification sending error:').' "'.$e->getMessage().'"';
                    $history->setComment($newComment);
                    //Mage::getSingleton('core/session')->addError($newComment);
                }

            }
        }

    }

}
