<?php

if (!defined('ABSPATH')) {
    die();
}

class EmailAddress {

    /**
     *
     * @var Settings
     */
    var $settings;

    /**
     *
     * @var type 
     */
    public $active;

    /**
     *
     * @var type 
     */
    public $address;

    /**
     *
     * @var type 
     */
    public $token;

    /**
     *
     * @var type 
     */
    public $timestamp;

    /**
     * 
     * @param type $active
     * @param type $address
     * @param type $token
     * @param type $timestamp
     */
    function __construct($active = false, $address = '', $token = '', $timestamp = null) {
        $this->setActive($active);
        $this->setAddress($address);
        $this->setToken($token);
        $this->setTimestamp($timestamp);
        $this->setSettings(Settings::getInstance());
    }

    /**
     * 
     * @param Settings $settings
     */
    private function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * 
     * @return Settings
     */
    private function getSettings() {
        return $this->settings;
    }

    /**
     * 
     * @return type
     */
    public function isActive() {
        return $this->getActive() == 1 ? true : false;
    }

    /**
     * 
     * @param type $active
     */
    function setActive($active) {
        $this->active = $active;
    }

    /**
     * 
     * @param type $address
     */
    function setAddress($address) {
        $this->address = $address;
    }

    /**
     * 
     * @param type $token
     */
    function setToken($token) {
        $this->token = $token;
    }

    /**
     * 
     * @param type $timestamp
     */
    function setTimestamp($timestamp) {
        $this->timestamp = $timestamp;
    }

    /**
     * 
     * @return type
     */
    function getActive() {
        return $this->active;
    }

    /**
     * 
     * @return type
     */
    function getAddress() {
        return $this->address;
    }

    /**
     * 
     * @return type
     */
    function getToken() {
        return $this->token;
    }

    /**
     * 
     * @return type
     */
    function getTimestamp() {
        return $this->timestamp;
    }

    /**
     * 
     * @return type
     */
    private function getHost() {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * 
     * @return boolean|int
     */
    public function getError() {

        $mail = $this->getAddress();
        $arrayAddress = explode('@', $mail);
        $serverAddress = array_pop($arrayAddress);


        if (!checkdnsrr($serverAddress, 'MX')) {
            return 2;
        }

        if ($this->getToken() != null && $this->getActive() == 1 || $this->getToken() == null && $this->getActive() == 0) {
            return 3;
        }

        return false;
    }

    /**
     * 
     * @return string
     */
    private function getServerAddress() {

        $host = $this->getHost();

        if (strpos('http://', $this->getHost()) === false || strpos('https://', $this->getHost()) === false) {
            $host = 'http://' . $this->getHost();
        }

        return $host;
    }

    /**
     * 
     * @return type
     */
    private function getActivationPageTitle() {
        $post = get_post($this->getActivationPageId());
        return $pageTitle = $post->post_title;
    }

    /**
     * 
     * @return type
     */
    private function getActivationPageId() {
        return $pageId = $this->getSettings()->getEazyNewsletterActivationPageID();
    }

    /**
     * 
     * @return type
     */
    private function getSenderMail() {
        return $this->getSettings()->getEazyNewsletterMail();
    }

    /**
     * 
     * @return string
     */
    private function getActivationUrl() {
        $pageTitle = $this->getActivationPageTitle();

        $activationUrl = $this->getServerAddress() . '/' . $pageTitle . '?a=activate&tk=' . $this->getToken() . '&u=' . $this->getAddress();

        return $activationUrl;
    }

    /**
     * 
     * @return type
     */
    private function getMessageBody() {
        $message = '';
        $message .= __('Klicken Sie auf folgenden Link um Ihre Eintragung im Newsletter auf ', 'eazy_newsletter');
        $message .= $this->getServerAddress();
        $message .= __(' zu bestätigen: ', 'eazy_newsletter');

        if ($this->getSettings()->getEazyNewsletterHtml() === true) {
            $message .= '<a style="width:100%;height:auto;overflow:hidden;display:block;background:blue;color:#fff;font-size:16px;border-radius:8px;padding:8px;text-align:center;" href=' . '"' . $this->getActivationUrl() . '"' . '>' . __('Bestätigen', 'eazy_newsletter') . '</a>';
        } else {
            $message .= $this->getActivationUrl();
        }



        return $message;
    }

    /**
     * 
     * @return boolean
     */
    public function sendValidationMail() {

        $headers = array();

        $headers[] = 'From: "' . $this->getSettings()->getEazyNewsletterName() . '"' . '<' . $this->getSettings()->getEazyNewsletterMail() . '>';

        if ($this->getSettings()->getEazyNewsletterHtml() === true) {
            $headers[] = 'Content-Type: text/html';
        } else {
            $headers[] = 'Content-Type: text/plain';
        }

        if (wp_mail($this->getAddress(), __('Bitte bestätigen Sie die Eintragung im Newsletter', 'eazy_newsletter'), $this->getMessageBody(), $headers)) {
            return true;
        }

        return false;
    }

}
