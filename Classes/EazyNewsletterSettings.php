<?php

if (!defined('ABSPATH')) {
    die();
}

/**
 * Description of Settings
 * 
 * @author alexw
 */
class EazyNewsletterSettings {

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
     * Seiten-ID der Austragungsseite
     * @var int
     */
    protected $eazyNewsletterDeleteMailPageID;

    /**
     * Zeit an der Newsletter versendet werden sollen
     * @var string 
     */
    protected $eazyNewsletterSendTime;

    /**
     *
     * @var type 
     */
    protected $lastCronAction;

    /**
     *
     * @var type 
     */
    protected $eazyNewsletterLogData;

    /**
     * 
     */
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
            $this->setEazyNewsletterLastCronAction($this->getOption('eazy_newsletter_last_cron_action'));
            $this->setEazyNewsletterDeleteMailPageID($this->getOption('eazy_newsletter_delete_mail_page_id'));
            $this->setEazyNewsletterLogData(boolval($this->getOption('eazy_newsletter_log_data')));
        }
    }

    /**
     * 
     * @return type
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new EazyNewsletterSettings();
        }

        return self::$instance;
    }

    /**
     * 
     * @return type
     */
    public static function getUpdatetInstance() {
        self::$instance = new EazyNewsletterSettings();

        return self::$instance;
    }

    /**
     * 
     * @param type $lastCronAction
     */
    public function setEazyNewsletterLastCronAction($lastCronAction) {
        $this->lastCronAction = $lastCronAction;
    }

    /**
     * 
     * @param type $sendTime
     */
    public function setEazyNewsletterSendTime($sendTime) {
        $this->eazyNewsletterSendTime = $sendTime;
    }

    /**
     * 
     * @param type $name
     */
    public function setEazyNewsletterName($name) {
        $this->eazyNewsletterName = $name;
    }

    /**
     * 
     * @param type $mail
     */
    public function setEazyNewsletterMail($mail) {
        $this->eazyNewsletterMail = $mail;
    }

    /**
     * 
     * @param type $html
     */
    public function setEazyNewsletterHtml($html) {
        $this->eazyNewsletterHtml = boolval($html);
    }

    /**
     * 
     * @param type $header
     */
    public function setEazyNewsletterCustomHtmlHeader($header) {
        $this->eazyNewsletterCustomHtmlHeader = $header;
    }

    /**
     * 
     * @param type $body
     */
    public function setEazyNewsletterCustomHtmlBody($body) {
        $this->eazyNewsletterCustomHtmlBody = $body;
    }

    /**
     * 
     * @param type $footer
     */
    public function setEazyNewsletterCustomHtmlFooter($footer) {
        $this->eazyNewsletterCustomHtmlFooter = $footer;
    }

    /**
     * 
     * @param type $automatic
     */
    public function setEazyNewsletterAutomatic($automatic) {
        $this->eazyNewsletterAutomatic = boolval($automatic);
    }

    /**
     * 
     * @param type $addresses
     */
    public function setEazyNewsletterAddresses($addresses) {
        $this->eazyNewsletterAddresses = $addresses;
    }

    /**
     * 
     * @param type $send
     */
    public function setEazyNewsletterSend($send) {
        $this->eazyNewsletterSend = $send;
    }

    /**
     * 
     * @param type $pageID
     */
    public function setEazyNewsletterDeleteMailPageID($pageID) {
        $this->eazyNewsletterDeleteMailPageID = $pageID;
    }

    /**
     * 
     * @param type $pageID
     */
    public function setEazyNewsletterActivationPageID($pageID) {
        $this->eazyNewsletterActivationPageID = $pageID;
    }

    public function setEazyNewsletterLogData($logData) {
        $this->eazyNewsletterLogData = boolval($logData);
    }

    public function getEazyNewsletterLogData() {
        return $this->eazyNewsletterLogData;
    }

    /**
     * 
     * @return type
     */
    public function getEazyNewsletterLastCronAction() {
        return $this->lastCronAction;
    }

    /**
     * 
     * @return type
     */
    public function getEazyNewsletterSendTime() {
        return $this->eazyNewsletterSendTime;
    }

    /**
     * 
     * @return type
     */
    public function getEazyNewsletterName() {
        return $this->eazyNewsletterName;
    }

    /**
     * 
     * @return type
     */
    public function getEazyNewsletterMail() {
        return $this->eazyNewsletterMail;
    }

    /**
     * 
     * @return type
     */
    public function getEazyNewsletterHtml() {
        return boolval($this->eazyNewsletterHtml);
    }

    /**
     * 
     * @return type
     */
    public function getEazyNewsletterCustomHtmlHeader() {
        return $this->eazyNewsletterCustomHtmlHeader;
    }

    /**
     * 
     * @return type
     */
    public function getEazyNewsletterCustomHtmlBody() {
        return $this->eazyNewsletterCustomHtmlBody;
    }

    /**
     * 
     * @return type
     */
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

    public function getEazyNewsletterDeleteMailPageID() {
        return $this->eazyNewsletterDeleteMailPageID;
    }

    public function hasAddresses() {
        return $hasAddresses = sizeof($this->getEazyNewsletterAddresses()) > 0 ? true : false;
    }

    public function createSettings() {
        try {
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
            $this->setEazyNewsletterLastCronAction(NULL);
            $this->setEazyNewsletterDeleteMailPageID(0);
            $this->setEazyNewsletterLogData(true);
            $this->insertSettings();
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    private function insertSettings() {
        try {
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
                'eazy_newsletter_send_time' => $this->getEazyNewsletterSendTime(),
                'eazy_newsletter_last_cron_action' => $this->getEazyNewsletterLastCronAction(),
                'eazy_newsletter_delete_mail_page_id' => $this->getEazyNewsletterDeleteMailPageID(),
                'eazy_newsletter_log_data' => $this->getEazyNewsletterLogData()
            );

            $wpdb->insert($tableName, $settings);
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public function updateSettings() {
        try {
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

            if ($this->updateOption('eazy_newsletter_last_cron_action', $this->getEazyNewsletterLastCronAction())) {
                $i++;
            }

            if ($this->updateOption('eazy_newsletter_delete_mail_page_id', $this->getEazyNewsletterDeleteMailPageID())) {
                $i++;
            }

            if ($this->updateOption('eazy_newsletter_log_data', $this->getEazyNewsletterLogData())) {
                $i++;
            }

            return $i !== 13 ? false : true;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public function updateOption($optionName, $value) {
        try {
            /* @var $wpdb wpdb */
            global $wpdb;
            $tableName = $wpdb->prefix . static::$tableName;
            $optionValue = '';

            switch ($optionName) {
                case 'eazy_newsletter_name':
                    $optionValue = filter_var($value, FILTER_SANITIZE_STRING) ? htmlspecialchars(strip_tags($value)) : ' ';
                    break;
                case 'eazy_newsletter_mail':
                    $optionValue = filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : '';
                    break;
                case 'eazy_newsletter_html':
                    $optionValue = filter_var($value, FILTER_VALIDATE_INT) ? $value : 0;
                    break;
                case 'eazy_newsletter_custom_html_header':
                    $optionValue = stripslashes(wp_filter_post_kses(addslashes($value)));
                    break;
                case 'eazy_newsletter_custom_html_body':
                    $optionValue = stripslashes(wp_filter_post_kses(addslashes($value)));
                    break;
                case 'eazy_newsletter_custom_html_footer':
                    $optionValue = stripslashes(wp_filter_post_kses(addslashes($value)));
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
                case 'eazy_newsletter_last_cron_action':
                    $optionValue = filter_var($value, FILTER_SANITIZE_STRING) ? $value : null;
                    break;
                case 'eazy_newsletter_delete_mail_page_id':
                    $optionValue = filter_var($value, FILTER_VALIDATE_INT) ? $value : 0;
                    break;
                case 'eazy_newsletter_log_data':
                    $optionValue = filter_var($value, FILTER_VALIDATE_INT) ? $value : 0;
                    break;
            }

            $settingsID = static::$settingsID;

            if ($wpdb->update($tableName, array($optionName => $optionValue), array('Id' => $settingsID)) !== false) {
                return true;
            }

            return false;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public function getOption($optionName) {
        try {
            /* @var $wpdb wpdb */
            global $wpdb;
            $tableName = $wpdb->prefix . static::$tableName;
            $option = esc_sql($optionName);


            $sql = 'SELECT ' . $option . ' FROM ' . $tableName;

            if ($optionName !== 'eazy_newsletter_addresses') {
                $value = $wpdb->get_var($sql);
            } else {
                $value = unserialize($wpdb->get_var($sql));
            }

            return $value;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    private function tableExists() {
        try {
            global $wpdb;

            $tableName = $wpdb->prefix . static::$tableName;

            if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
                return false;
            } else {
                return true;
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

}
