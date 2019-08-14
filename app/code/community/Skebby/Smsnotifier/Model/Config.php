<?php
/*
 * Smsnotifier API config class
 *
 * @category   Skebby
 * @package    SmsnotifierApi
 * @copyright  Copyright (c) 2014 Skebby (http://skebby.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Skebby <supporto@skebby.com>
 * 
 */

class Skebby_Smsnotifier_Model_Config
{
    const LOW_CREDITS_WARNING_MESSAGE = 'Warning: Low credit level at your Skebby account. Buy credits.';
    const WRONG_AUTH_DATA = 'Skebby API: Wrong Password and/or Username' ;

    public $contacts = array (
        'en_US'=>'http://www.skebby.com/sms-solution-provider/contact-us', //default international
        'it_IT'=>'http://www.skebby.it/sms-business/contatti'
        );

	public function getApiUrl()
	{
		return "https://gateway.skebby.it/api/send/smseasy/advanced/http.php";
	}

    public function getContactUrl($localeCode)
    {
        return (array_key_exists($localeCode, $this->contacts)) ? $this->contacts[$localeCode] : $this->contacts['en_US'];

    }

    /**
     * getting API login from main configuration
     * @return string
     */
    public function getLogin()
    {
        return Mage::getStoreConfig('smsnotifier/main_conf/apilogin');

    }

    /**
     * getting API password from main configuration
     * @return string
     */
    public function getPassword()
    {
        $encrypted_pass = Mage::getStoreConfig('smsnotifier/main_conf/apipassword');

        return Mage::helper('core')->decrypt($encrypted_pass);

    }

    /**
     * getting API method from main configuration
     * @return string
     */
    public function getMethod()
    {
        return Mage::getStoreConfig('smsnotifier/main_conf/message_type');
        //return Mage::helper('core')->decrypt($encrypted_pass);
    }

    public function getCharset()
    {
        return Mage::getStoreConfig('smsnotifier/main_conf/charset');

    }

    public function getEncodingscheme()
    {
        return Mage::getStoreConfig('smsnotifier/main_conf/encodingscheme');

    }

    /*public function getCountryPrefix() {
        return str_replace( array('00','+'), '', Mage::getStoreConfig('smsnotifier/main_conf/country_prefix') );

    }*/

    public function getStoreName()
    {
        return Mage::getStoreConfig('smsnotifier/main_conf/storename');

    }

    public function getSenderNumber()
    {
        return Mage::getStoreConfig('smsnotifier/main_conf/sendernumber');

    }

    public function getChoosesender()
    {
    	return Mage::getStoreConfig('smsnotifier/main_conf/choosesender');
    }
     /**
     * checks if Smsnotifier API module is enabled
     * @return boolean
     */
    public function isApiEnabled()
    {
        return (Mage::getStoreConfig('smsnotifier/main_conf/active')==0) ? 0 : 1;

    }

    public function creditAllertLimit()
    {
        return floatval(str_replace(',','.',Mage::getStoreConfig('smsnotifier/main_conf/credit_alert_limit')));
    }

     /**
     * getting SMS templates from config
     * @return string
     */
    public function getMessageTemplate($template)
    {
        $templateContent = Mage::getStoreConfig('smsnotifier/templates/status_'.$template);

        if (Mage::getStoreConfig('smsnotifier/templates/status_'. $template .'_active') && !empty($templateContent))
            return $templateContent;

    }

   public function getMessageStatuses()
   {
       $statuses = array(   'DELIVERED'        => Mage::helper('smsnotifier')->__('Message delivered'),
                            'EXPIRED'        => Mage::helper('smsnotifier')->__('Message expired (device off/not reachable)'),
                        	'DELETED'    => Mage::helper('smsnotifier')->__('Operator network error'),
                            'UNDELIVERABLE'    => Mage::helper('smsnotifier')->__('Message undelivered'),
                            'UNKNOWN'        => Mage::helper('smsnotifier')->__('Generic error'),
                            'REJECTD'        => Mage::helper('smsnotifier')->__('Message rejected from operator'),
                            'SENT'          => Mage::helper('smsnotifier')->__('Message sent to Skebby'),
                            'SENDING'       => Mage::helper('smsnotifier')->__('Delivering to Skebby'),
                            'ERROR'         => Mage::helper('smsnotifier')->__('Unable to send to Skebby'),
                        );

       return $statuses;
   }

}
