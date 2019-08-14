<?php

class Skebby_Smsnotifier_Model_Cron
{
    public function getDelieveryReport()
    {
        $apiClient = Mage::getModel('smsnotifier/apiClient');
        $apiClient->saveDeliveryReport();

    }

}
