<?php

class Skebby_Smsnotifier_Block_Buttons_NewAccount extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('button')
                    ->setLabel(Mage::helper('smsnotifier')->__('Open Sign Up form'))
                    ->setOnClick("window.open('http://www.skebby.com/sms-bulk-solutions/business-sms-sign-up/','window1','width=990, height=705, scrollbars=1, resizable=1'); return false;")
                    ->toHtml();

        return $html;

    }
}
