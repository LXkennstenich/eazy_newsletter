<?php

if (!defined('ABSPATH')) {
    die();
}

/**
 * Description of System
 *
 * @author alexw
 */
class System {

    /**
     *
     * @var Settings
     */
    var $settings;
    private static $tableName = 'eazy_newsletter_settings';

    function __construct() {
        $this->setSettings(Settings::getInstance());
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
     * @param Settings $settings
     */
    private function setSettings($settings) {
        $this->settings = $settings;
    }

    public static function debugLog($data) {
        $file = EAZYROOTDIR . 'debuglog.txt';

        $data .= "\n";
        $writeLog = file_put_contents($file, $data, FILE_APPEND);

        if ($writeLog === false) {
            return false;
        }

        return true;
    }

    public static function getViewPath($file) {
        $fullFilePath = EAZYROOTDIR . 'View/' . $file . '.php';

        if (file_exists($fullFilePath)) {
            return $fullFilePath;
        }
    }

    public static function eazyNewsletterScriptURL($file) {
        $scriptPath = EAZYROOTDIR . 'Js/' . $file . '.js';
        $scriptURL = EAZYROOTURL . 'Js/' . $file . '.js';

        if (file_exists($scriptPath)) {
            return $scriptURL;
        }
    }

    public static function eazyNewsletterStyleURL($file) {
        $scriptPath = EAZYROOTDIR . 'Css/' . $file . '.css';
        $scriptURL = EAZYROOTURL . 'Css/' . $file . '.css';

        if (file_exists($scriptPath)) {
            return $scriptURL;
        }
    }

    public static function getAjaxControllerPath($action) {
        $requestedControllerPath = EAZYROOTDIR . 'Controller/' . $action . 'Controller.php';

        if (file_exists($requestedControllerPath)) {
            return $requestedControllerPath;
        }
    }

    public static function ajaxControllerExists($action) {
        $requestedControllerPath = EAZYROOTDIR . 'Controller/' . $action . 'Controller.php';

        if (file_exists($requestedControllerPath)) {
            return true;
        }

        return false;
    }

    public static function getAjaxRequestValue($action) {
        return base64_encode($action);
    }

    public static function getImageURL($imageName) {
        $imagePath = EAZYROOTDIR . 'Images/' . $imageName;
        $imageURL = EAZYROOTURL . 'Images/' . $imageName;

        if (file_exists($imagePath)) {
            return $imageURL;
        }
    }

    public function createNewsletterTable() {

        global $wpdb;
        $table_name = $wpdb->prefix . static::$tableName;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE  $table_name (
            `Id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `eazy_newsletter_name` text NOT NULL,
            `eazy_newsletter_mail` varchar(256) NOT NULL,
            `eazy_newsletter_html` tinyint(1) NOT NULL,
            `eazy_newsletter_custom_html_header` text NOT NULL,
            `eazy_newsletter_custom_html_body` text NOT NULL,
            `eazy_newsletter_custom_html_footer` text NOT NULL,
            `eazy_newsletter_automatic` tinyint(1) NOT NULL,
            `eazy_newsletter_addresses` text NOT NULL,
            `eazy_newsletter_activation_page_id` int(11) NOT NULL,
            `eazy_newsletter_send_time` text NOT NULL
            ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        $this->getSettings()->createSettings();
    }

    public function tableExists() {
        global $wpdb;

        $tableName = $wpdb->prefix . static::$tableName;

        if ($wpdb->get_var("SHOW TABLES LIKE '$tableName'") != $tableName) {
            return false;
        } else {
            return true;
        }
    }

}
