<?php
/*
 * SMS API client class
 *
 * @category   Skebby
 * @package    SmsnotifierApi
 * @copyright  Copyright (c) 2014 Skebby (http://skebby.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @author     Skebby <supporto@skebby.com>
 * 
 */
 
class Skebby_Smsnotifier_Model_ApiClient
{
	private function getConfig()
	{
		return Mage::getModel('smsnotifier/config');

	}

	private function curlSend($url, $params)
	{
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_TIMEOUT,60);
        curl_setopt($ch, CURLOPT_USERAGENT,'Generic Client');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        // response of the POST request
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;

	}

    public function sendByCurl(array $smsData)
    {
        if (empty($smsData['recipients']))
            throw new Exception(Mage::helper('smsnotifier')->__('No recipients found'));

        $config = $this->getConfig();
        $postUrl = $config->getApiUrl();

        $recipients = (!is_array($smsData['recipients'])) ? array($smsData['recipients']) : $smsData['recipients'];
        $method = 'send_sms_'.$smsData['message_type'];

       	$params = 'method='         .$method.    '&'
       			 .'username='     .urlencode($config->getLogin()).    '&'
       			 .'password='     .urlencode($config->getPassword()).'&'
       			 .'text='         .urlencode($smsData['message']).    '&'
       			 .'recipients[]='.implode('&recipients[]=',$recipients).'&'
				 .'charset='     .urlencode($config->getCharset()).    '&'
				 .'encoding_scheme='.urlencode($config->getEncodingscheme());
        if (isset($smsData['reference'])) {
            $params .= '&' . 'user_reference='   .urlencode($smsData['reference']);
        }

       	$sender = $config->getChoosesender();
       	if ($sender == 'storename') {
       		$params .= '&sender_string=' .urlencode($config->getStoreName());
       	} else {
       		$params .= '&sender_number=' .urlencode($config->getSenderNumber());
       	}

        Skebby_Smsnotifier_Model_Smsnotifier::log('Send request: '.var_export($params,true));

        // response of the POST request
        $response = $this->curlSend($postUrl, $params);
        //$response = 'status=success&remaining_sms=414'; // debug
        //$response = 'status=failed&code=21&message=Username+or+password+not+valid%2C+you+cannot+use+your+email+or+phone+number%2C+only+username+is+allowed+on+gateway'; // debug

        parse_str($response, $ret);

        return $ret;

    }

    public function getCredits()
    {
        $config = $this->getConfig();
        $postUrl = $config->getApiUrl();

        $params = 'method=get_credit&'
       			 .'username='     .urlencode($config->getLogin()).    '&'
       			 .'password='     .urlencode($config->getPassword());

        $response = $this->curlSend($postUrl, $params);

        return $response;

    }

    public function checkCreditLimit()
    {
        if (!Mage::getSingleton('admin/session')->isLoggedIn()) //checks credit limit only for logged admin
            return;

        $config =   Mage::getModel('smsnotifier/config');
        if ($config->isApiEnabled()==0) return;

        $limit = $config->creditAllertLimit();
        if ($limit==0) return; //If limit allert is turned off


        try {

            $credits = $this->getCredits();

            if ($credits=='UNKNOWN_COMMAND') {
                Mage::getSingleton('core/session')->addError(Mage::helper('smsnotifier')->__($config::WRONG_AUTH_DATA));
            } elseif ($credits < $limit) {
                Mage::getSingleton('core/session')->addError(Mage::helper('smsnotifier')->__($config::LOW_CREDITS_WARNING_MESSAGE));
            }

        } catch (Exception $e) {
             Mage::getSingleton('core/session')->addError($e->getMessage());
        }

    }

}
