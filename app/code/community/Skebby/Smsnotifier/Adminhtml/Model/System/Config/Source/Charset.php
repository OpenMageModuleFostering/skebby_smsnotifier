<?php
 /*
 * Opzione per scegliere il charset
 */
class Skebby_Smsnotifier_Adminhtml_Model_System_Config_Source_Charset
{
     /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'ISO-8859-1', 'label'=>Mage::helper('adminhtml')->__('ISO-8859-1')),
            array('value' => 'UTF-8', 'label'=>Mage::helper('adminhtml')->__('UTF-8'))
        );
    }
}
