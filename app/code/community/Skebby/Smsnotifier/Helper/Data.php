<?php

class Skebby_Smsnotifier_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getPhoneNumber($phoneNumber)
    {
        /*$config = Mage::getModel('smsnotifier/config');
        $prefix = $config->getCountryPrefix();
        $toStrip = '+,0';

        return $prefix . ltrim($phoneNumber,$toStrip);*/
        return ltrim($phoneNumber, '+,0');
    }

    public function isSendSuccess($response)
    {
        return $response['status'] == 'success';
    }

    public function getSendError($response)
    {
        $map = array(
            10 => Mage::helper('smsnotifier')->__('Generic error'),
            11 => Mage::helper('smsnotifier')->__('Unknown charset, use ISO-8859-1 or UTF-8'),
            12 => Mage::helper('smsnotifier')->__('Set all the mandatory parameters'),
            20 => Mage::helper('smsnotifier')->__('Input not valid'),
            21 => Mage::helper('smsnotifier')->__('Username or password not valid, you cannot use your email or phone number, only username is allowed on gateway'),
            22 => Mage::helper('smsnotifier')->__('Sender number not valid'),
            23 => Mage::helper('smsnotifier')->__('Sender string too long, max 11 chars'),
            24 => Mage::helper('smsnotifier')->__('Text too long'),
            25 => Mage::helper('smsnotifier')->__('Recipient not valid (must be in international format like 393401234567)'),
            26 => Mage::helper('smsnotifier')->__('Sender not allowed, please verify your number on http://www.skebby.it/user/verified-numbers/sender-id/'),
            27 => Mage::helper('smsnotifier')->__('Too many recipients, max 50.000'),
            28 => Mage::helper('smsnotifier')->__('You cannot repeate the same phone number twice or more'),
            29 => Mage::helper('smsnotifier')->__('No credit left, please buy a recharge on http://www.skebby.it/'),
            30 => Mage::helper('smsnotifier')->__('You cannot use the gateway interface with your acount, please send an email to supporto@skebby.com to enable it'),
            31 => Mage::helper('smsnotifier')->__('For this function only POST method is allowed, please send data in an HTTP POST request'),
            32 => Mage::helper('smsnotifier')->__('The delivery start date is not valid, use RFC 2822 format es: Mon, 15 Aug 2005 15:52:01 +0000'),
            33 => Mage::helper('smsnotifier')->__('The encoding scheme is not valid, allowed values: normal, ucs2 visit http://en.wikipedia.org/wiki/GSM_03.38 fo)r more information'),
            34 => Mage::helper('smsnotifier')->__('The validity period is not valid, MUST be an integer value (minutes) greater then zero and below 10080 (7 days)'),
            35 => Mage::helper('smsnotifier')->__('The user reference is not valid, MUST be max 20 chars of range [a-zA-Z0-9-_+:;]'),
            36 => Mage::helper('smsnotifier')->__('If you use a delivery start and you want the report you must also specify the user referece value'),
            37 => Mage::helper('smsnotifier')->__('Some characters in the text are not valid for the charset specified'),
            38 => Mage::helper('smsnotifier')->__('Too many alias with same vat'),
            39 => Mage::helper('smsnotifier')->__('Vat number not valid'),
            40 => Mage::helper('smsnotifier')->__('Not a business user'),
            41 => Mage::helper('smsnotifier')->__('Alias already approved'),
        );

        return $map[$response['code']];
    }

}
