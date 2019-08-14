<?php

class Skebby_Smsnotifier_Adminhtml_SmsnotifierController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout()
             ->_setActiveMenu('smsnotifier/items');
        $this->renderLayout();

    }

    public function editAction()
    {
        $this->loadLayout()
             ->_setActiveMenu('smsnotifier/items');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->_addContent($this->getLayout()->createBlock('smsnotifier/adminhtml_smsnotifier_edit'))
                ->_addLeft($this->getLayout()->createBlock('smsnotifier/adminhtml_smsnotifier_edit_tabs'));

        $this->renderLayout();

    }

    public function newAction()
    {
            $this->_forward('edit');

    }

    public function saveAction()
    {
        Mage::getModel('smsnotifier/smsnotifier')->sendBulkSMS($this->getRequest()->getParam('sms_message'));
        $this->_redirect('*/*/new');

    }

}
