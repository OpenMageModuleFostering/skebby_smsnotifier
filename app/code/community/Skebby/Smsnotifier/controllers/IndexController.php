<?php
class Skebby_Smsnotifier_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        Skebby_Smsnotifier_Model_Smsnotifier::log('Got notify on wrong controller: '.var_export($_REQUEST,true));
    }

    public function notifyAction()
    {
        $recipient = Mage::app()->getRequest()->getParam('recipient');
        $userReference = Mage::app()->getRequest()->getParam('user_reference');
        $status = Mage::app()->getRequest()->getParam('status');
        $errorCode = Mage::app()->getRequest()->getParam('error_code');

        Skebby_Smsnotifier_Model_Smsnotifier::log('Got notify: '.var_export($_REQUEST,true));

        if (!$recipient || !$userReference || !$status || !$errorCode) {
            Skebby_Smsnotifier_Model_Smsnotifier::log('Notify missing needed parameters');

            return;
        }

        // check if present
        $collection = Mage::getModel('smsnotifier/smsnotifier')->getCollection()
            ->addFieldToSelect('*')
            ->addFieldToFilter('telephone', $recipient)
            ->addFieldToFilter('delivery_reference',$userReference)
            ->getItems();
        if (count($collection) > 0) {
            Skebby_Smsnotifier_Model_Smsnotifier::log('Found notify');
            $item = reset($collection);
            $id = $item->getId();
            $item = Mage::getModel('smsnotifier/smsnotifier')->load($id);
            $item->setDeliveryStatus($status)->save();
        } else {
            Skebby_Smsnotifier_Model_Smsnotifier::log('Not found notify');
        }
    }

}
