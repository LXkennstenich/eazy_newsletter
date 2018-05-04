<?php

if (!defined('ABSPATH')) {
    die();
}

/**
 * Description of Settings
 * 
 * @author alexw
 */
class Settings {

    private static $instance = null;
    private static $tableName = 'eazy_newsletter_settings';
    private static $settingsID = 1;

    /**
     * Der Name der im "From" Header der E-Mail erscheinen soll
     * @var string 
     */
    protected $eazyNewsletterName;

    /**
     * Die E-Mail Addresse von der Standardmäßig E-Mails versendet werden
     * @var string 
     */
    protected $eazyNewsletterMail;

    /**
     * Gibt an ob Newsletter im HTML-Format versendet werden
     * @var bool 
     */
    protected $eazyNewsletterHtml;

    /**
     * Custom Wrapper für den Head-Bereich der E-Mail
     * @var string
     */
    protected $eazyNewsletterCustomHtmlHeader;

    /**
     * Custom Wrapper für den Body-Bereich der E-Mail
     * @var string 
     */
    protected $eazyNewsletterCustomHtmlBody;

    /**
     * Custom Wrapper für den Footer-Bereich der E-Mail
     * @var string 
     */
    protected $eazyNewsletterCustomHtmlFooter;

    /**
     * Gibt an ob Newsletter automatisch zu einem bestimmten Datum versendet werden sollen
     * @var bool
     */
    protected $eazyNewsletterAutomatic;

    /**
     * Adressen die für den Newsletter registriert wurden
     * @var Array
     */
    protected $eazyNewsletterAddresses;

    /**
     * Seiten-ID der Validierungsseite
     * @var int
     */
    protected $eazyNewsletterActivationPageID;

    /**
     * Zeit an der Newsletter versendet werden sollen
     * @var string 
     */
    protected $eazyNewsletterSendTime;

    function __construct() {

        if ($this->tableExists()) {
            $this->setEazyNewsletterName($this->getOption('eazy_newsletter_name'));
            $this->setEazyNewsletterMail($this->getOption('eazy_newsletter_mail'));
            $this->setEazyNewsletterHtml(boolval($this->getOption('eazy_newsletter_html')));
            $this->setEazyNewsletterCustomHtmlHeader($this->getOption('eazy_newsletter_custom_html_header'));
            $this->setEazyNewsletterCustomHtmlBody($this->getOption('eazy_newsletter_custom_html_body'));
            $this->setEazyNewsletterCustomHtmlFooter($this->getOption('eazy_newsletter_custom_html_footer'));
            $this->setEazyNewsletterAutomatic(boolval($this->getOption('eazy_newsletter_automatic')));
            $this->setEazyNewsletterAddresses($this->getOption('eazy_newsletter_addresses'));
            $this->setEazyNewsletterActivationPageID($this->getOption('eazy_newsletter_activation_page_id'));
            $this->setEazyNewsletterSendTime($this->getOption('eazy_newsletter_send_time'));
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Settings();
        }

        return self::$instance;
    }

    public static function getUpdatetInstance() {
        self::$instance = new Settings();

        return self::$instance;
    }

    public function setEazyNewsletterSendTime($sendTime) {
        $this->eazyNewsletterSendTime = $sendTime;
    }

    public function setEazyNewsletterName($name) {
        $this->eazyNewsletterName = $name;
    }

    public function setEazyNewsletterMail($mail) {
        $this->eazyNewsletterMail = $mail;
    }

    public function setEazyNewsletterHtml($html) {
        $this->eazyNewsletterHtml = boolval($html);
    }

    public function setEazyNewsletterCustomHtmlHeader($header) {
        $this->eazyNewsletterCustomHtmlHeader = $header;
    }

    public function setEazyNewsletterCustomHtmlBody($body) {
        $this->eazyNewsletterCustomHtmlBody = $body;
    }

    public function setEazyNewsletterCustomHtmlFooter($footer) {
        $this->eazyNewsletterCustomHtmlFooter = $footer;
    }

    public function setEazyNewsletterAutomatic($automatic) {
        $this->eazyNewsletterAutomatic = boolval($automatic);
    }

    public function setEazyNewsletterAddresses($addresses) {
        $this->eazyNewsletterAddresses = $addresses;
    }

    public function setEazyNewsletterSend($send) {
        $this->eazyNewsletterSend = $send;
    }

    public function setEazyNewsletterActivationPageID($pageID) {
        $this->eazyNewsletterActivationPageID = $pageID;
    }

    public function getEazyNewsletterSendTime() {
        return $this->eazyNewsletterSendTime;
    }

    public function getEazyNewsletterName() {
        return $this->eazyNewsletterName;
    }

    public function getEazyNewsletterMail() {
        return $this->eazyNewsletterMail;
    }

    public function getEazyNewsletterHtml() {
        return boolval($this->eazyNewsletterHtml);
    }

    public function getEazyNewsletterCustomHtmlHeader() {
        return $this->eazyNewsletterCustomHtmlHeader;
    }

    public function getEazyNewsletterCustomHtmlBody() {
        return $this->eazyNewsletterCustomHtmlBody;
    }

    public function getEazyNewsletterCustomHtmlFooter() {
        return $this->eazyNewsletterCustomHtmlFooter;
    }

    public function getEazyNewsletterAutomatic() {
        return boolval($this->eazyNewsletterAutomatic);
    }

    public function getEazyNewsletterAutomaticDate() {
        return $this->eazyNewsletterAutomaticDate;
    }

    public function getEazyNewsletterAddresses() {
        return $this->eazyNewsletterAddresses;
    }

    public function getEazyNewsletterSend() {
        return $this->eazyNewsletterSend;
    }

    public function getEazyNewsletterActivationPageID() {
        return $this->eazyNewsletterActivationPageID;
    }

    public function createSettings() {
        $this->setEazyNewsletterName('eazy newsletter plugin');
        $this->setEazyNewsletterMail('noreply@not-existing.com');
        $this->setEazyNewsletterHtml(false);
        $this->setEazyNewsletterCustomHtmlHeader('');
        $this->setEazyNewsletterCustomHtmlBody('');
        $this->setEazyNewsletterCustomHtmlFooter('');
        $this->setEazyNewsletterAutomatic(false);
        $this->setEazyNewsletterAddresses(array());
        $this->setEazyNewsletterActivationPageID(0);
        $this->setEazyNewsletterSendTime('12:00');
        $this->insertSettings();
    }

    private function insertSettings() {
        /* @var $wpdb wpdb */
        global $wpdb;
        $tableName = $wpdb->prefix . static::$tableName;


        $settings = array(
            'eazy_newsletter_name' => $this->getEazyNewsletterName(),
            'eazy_newsletter_mail' => $this->getEazyNewsletterMail(),
            'eazy_newsletter_html' => $this->getEazyNewsletterHtml(),
            'eazy_newsletter_custom_html_header' => $this->getEazyNewsletterCustomHtmlHeader(),
            'eazy_newsletter_custom_html_body' => $this->getEazyNewsletterCustomHtmlBody(),
            'eazy_newsletter_custom_html_footer' => $this->getEazyNewsletterCustomHtmlFooter(),
            'eazy_newsletter_automatic' => $this->getEazyNewsletterAutomatic(),
            'eazy_newsletter_addresses' => serialize($this->getEazyNewsletterAddresses()),
            'eazy_newsletter_activation_page_id' => $this->getEazyNewsletterActivationPageID(),
            'eazy_newsletter_send_time' => $this->getEazyNewsletterSendTime()
        );

        $wpdb->insert($tableName, $settings);
    }

    public function updateSettings() {

        $i = 0;

        if ($this->updateOption('eazy_newsletter_name', $this->getEazyNewsletterName())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_mail', $this->getEazyNewsletterMail())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_html', $this->getEazyNewsletterHtml())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_custom_html_header', $this->getEazyNewsletterCustomHtmlHeader())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_custom_html_body', $this->getEazyNewsletterCustomHtmlBody())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_custom_html_footer', $this->getEazyNewsletterCustomHtmlFooter())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_automatic', $this->getEazyNewsletterAutomatic())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_addresses', $this->getEazyNewsletterAddresses())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_activation_page_id', $this->getEazyNewsletterActivationPageID())) {
            $i++;
        }

        if ($this->updateOption('eazy_newsletter_send_time', $this->getEazyNewsletterSendTime())) {
            $i++;
        }

        return $i !== 10 ? false : true;
    }

    public function updateOption($optionName, $value) {
        /* @var $wpdb wpdb */
        global $wpdb;
        $tableName = $wpdb->prefix . static::$tableName;
        $optionValue = '';

        $logValue = is_array($value) ? serialize($value) : $value;

        System::debugLog('Update Option: Name: ' . $optionName . ' Value: ' . $logValue);

        switch ($optionName) {
            case 'eazy_newsletter_name':
                $optionValue = filter_var($value, FILTER_SANITIZE_STRING) ? htmlspecialchars(strip_tags($value)) : '';
                break;
            case 'eazy_newsletter_mail':
                $optionValue = filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : '';
                break;
            case 'eazy_newsletter_html':
                $optionValue = filter_var($value, FILTER_VALIDATE_INT) ? $value : 0;
                break;
            case 'eazy_newsletter_custom_html_header':
                $optionValue = filter_var($value, FILTER_DEFAULT) ? $value : '';
                break;
            case 'eazy_newsletter_custom_html_body':
                $optionValue = filter_var($value, FILTER_DEFAULT) ? $value : '';
                break;
            case 'eazy_newsletter_custom_html_footer':
                $optionValue = filter_var($value, FILTER_DEFAULT) ? $value : '';
                break;
            case 'eazy_newsletter_automatic':
                $optionValue = filter_var($value, FILTER_VALIDATE_INT) ? $value : 0;
                break;
            case 'eazy_newsletter_addresses':
                $optionValue = serialize($value);
                break;
            case 'eazy_newsletter_activation_page_id':
                $optionValue = filter_var($value, FILTER_VALIDATE_INT) ? $value : 0;
                break;
            case 'eazy_newsletter_send_time':
                $optionValue = $value;
                break;
        }

        $settingsID = static::$settingsID;

        if ($wpdb->update($tableName, array($optionName => $optionValue), array('Id' => $settingsID)) !== false) {
            return true;
        }

        return false;
    }

    public function getOption($optionName) {
        /* @var $wpdb wpdb */
        global $wpdb;
        $tableName = $wpdb->prefix . static::$tableName;

        $sql = 'SELECT ' . $optionName . ' FROM ' . $tableName;

        if ($optionName !== 'eazy_newsletter_addresses') {
            $value = $wpdb->get_var($sql);
        } else {
            $value = unserialize($wpdb->get_var($sql));
        }

        return $value;
    }

    private function tableExists() {
        global $wpdb;

        $tableName = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            return false;
        } else {
            return true;
        }
    }

}
