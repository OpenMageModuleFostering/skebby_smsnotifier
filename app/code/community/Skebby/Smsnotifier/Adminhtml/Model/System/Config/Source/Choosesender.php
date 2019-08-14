<?php
 /*
 * Opzione per scegliere il mittente
 */
class Skebby_Smsnotifier_Adminhtml_Model_System_Config_Source_Choosesender
{
     /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'storename', 'label'=>Mage::helper('adminhtml')->__('Store Name')),
            array('value' => 'sendernumber', 'label'=>Mage::helper('adminhtml')->__('Phone Number'))
        );
    }
}
