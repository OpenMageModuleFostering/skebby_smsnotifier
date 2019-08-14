<?php
 /*
 * Opzione per scegliere l'Encoding Scheme
 */
class Skebby_Smsnotifier_Adminhtml_Model_System_Config_Source_Encodingscheme
{
     /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'normal', 'label'=>Mage::helper('adminhtml')->__('Normal')),
            array('value' => 'UCS2', 'label'=>Mage::helper('adminhtml')->__('UCS2'))
        );
    }
}
