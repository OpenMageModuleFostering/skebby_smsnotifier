<?php
 /*
 * Opzione per scegliere il tipo di messaggio
 */
class Skebby_Smsnotifier_Adminhtml_Model_System_Config_Source_Messagetype
{
    const MESSAGE_TYPE_BASIC = 'basic';
    const MESSAGE_TYPE_CLASSIC = 'classic';
    const MESSAGE_TYPE_CLASSIC_PLUS = 'classic_report';
     /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => self::MESSAGE_TYPE_BASIC, 'label'=>Mage::helper('adminhtml')->__('Basic')),
            array('value' => self::MESSAGE_TYPE_CLASSIC, 'label'=>Mage::helper('adminhtml')->__('Classic')),
            array('value' => self::MESSAGE_TYPE_CLASSIC_PLUS, 'label'=>Mage::helper('adminhtml')->__('Classic+')),
        );
    }
}
