<?php

class Skebby_Smsnotifier_Model_Resource_Smsnotifier extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init("smsnotifier/skebby_smsnotifier","id");

    }

}
