<?php

class Skebby_Smsnotifier_Block_Adminhtml_Smsnotifier_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'smsnotifier';
        $this->_controller = 'adminhtml_smsnotifier';

        $this->_updateButton('save', 'label', Mage::helper('smsnotifier')->__('Send'));
    }

    public function getHeaderText()
    {
        return Mage::helper('smsnotifier')->__('Send Bulk SMS');
    }
}
