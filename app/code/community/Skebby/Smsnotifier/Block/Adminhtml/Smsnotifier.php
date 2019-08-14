<?php

class Skebby_Smsnotifier_Block_Adminhtml_Smsnotifier extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_smsnotifier';
        $this->_blockGroup = 'smsnotifier';
        $this->_headerText = Mage::helper('smsnotifier')->__('SMSNotifier');
        parent::__construct();
        $this->removeButton('add');

    }

}
