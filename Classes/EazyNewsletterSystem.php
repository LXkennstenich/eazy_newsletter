<?php

if (!defined('ABSPATH')) {
    die();
}

/**
 * Eazy Newsletter
 *
 * @package     eazy_newsletter
 * @author      Alexander Weese
 * @copyright   2018 Alexander Weese Webdesign
 * @license     GPL-3.0+
 */
class EazyNewsletterSystem {

    /**
     * Enthält die Daten aus der Datenbank
     * @var EazyNewsletterSettings
     */
    protected $settings;

    /**
     * Unser Table-Name in der Datenbank
     * @var string
     */
    private static $tableName = 'eazy_newsletter_settings';

    /**
     * Konstruktor
     */
    function __construct() {
        $this->setSettings(EazyNewsletterSettings::getUpdatetInstance());
    }

    /**
     * Getter für Settings
     * @return EazyNewsletterSettings
     */
    public function getSettings() {
        return $this->settings;
    }

    /**
     * Setter für Settings
     * @param EazyNewsletterSettings $settings
     */
    private function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * Fügt dem Debug-Log Daten hinzu. Gibt True zurück wenn die Daten erfolgreich in die Datei geschrieben wurden.
     * Andernfalls false.
     * @param string $data
     * @return boolean
     */
    public static function Log($data) {
        try {
            $file = EAZYROOTDIR . 'log.txt';

            $logData = '';

            $logData .= '********************************************************************************************************' . "\n";
            $logData .= '********************************************************************************************************' . "\n";
            $logData .= '********************************************************************************************************';
            $logData .= "\n";
            $logData .= "\n";

            $logData .= '[***UHRZEIT:*** ' . date('d-m-Y H:i:s', current_time('timestamp')) . ' *** ' . $data;

            $logData .= "\n";
            $logData .= "\n";
            $logData .= '********************************************************************************************************' . "\n";
            $logData .= '********************************************************************************************************' . "\n";
            $logData .= '********************************************************************************************************';
            $logData .= "\n";
            $logData .= "\n";
            $logData .= "\n";

            $writeLog = file_put_contents($file, $logData, FILE_APPEND);

            if ($writeLog === false) {
                return false;
            }

            return true;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * Ermittelt den Pfad für eine angefragte "View" Datei des Plugins. Ist die datei vorhanden wird der absolute pfad zurückgegeben andernfalls NULL.
     * @param string $file
     * @return string
     */
    public static function getViewPath($file) {
        try {
            $fullFilePath = EAZYROOTDIR . 'View/' . $file . '.php';

            if (file_exists($fullFilePath)) {
                return $fullFilePath;
            }

            return null;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * Ermittelt die Url für eine JavaScript-Datei. Existiert die Datei wird die entsprechende Url zurückgegeben andernfalls NULL.
     * @param string $file
     * @return string
     */
    public static function eazyNewsletterScriptURL($file) {
        try {
            $scriptPath = EAZYROOTDIR . 'Js/' . $file . '.js';
            $scriptURL = EAZYROOTURL . 'Js/' . $file . '.js';

            if (file_exists($scriptPath)) {
                return $scriptURL;
            }

            return null;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * Ermittelt die Url für eine CSS-Datei. Existiert die Datei wird die entsprechende Url zurückgegeben andernfalls NULL.
     * @param string $file
     * @return string
     */
    public static function eazyNewsletterStyleURL($file) {
        try {
            $scriptPath = EAZYROOTDIR . 'Css/' . $file . '.css';
            $scriptURL = EAZYROOTURL . 'Css/' . $file . '.css';

            if (file_exists($scriptPath)) {
                return $scriptURL;
            }

            return null;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * Ermittelt den Controller-Pfad für einen angefragten Ajax-Controller. 
     * Existiert die Datei wird der entsprechende Pfad zurück gegeben andernfalls NULL.
     * @param string $action
     * @return string
     */
    public static function getAjaxControllerPath($action) {
        try {
            $requestedControllerPath = EAZYROOTDIR . 'Controller/' . $action . 'Controller.php';

            if (file_exists($requestedControllerPath)) {
                return $requestedControllerPath;
            }

            return null;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * Überprüft ob ein Ajax-Controller existiert. Wenn ja wird TRUE zurückgegeben andernfalls FALSE.
     * @param string $action
     * @return boolean
     */
    public static function ajaxControllerExists($action) {
        try {
            $requestedControllerPath = EAZYROOTDIR . 'Controller/' . $action . 'Controller.php';

            if (file_exists($requestedControllerPath)) {
                return true;
            }

            return false;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public static function getAjaxRequestValue($action) {
        try {
            return base64_encode($action);
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public static function getImageURL($imageName) {
        try {
            $imagePath = EAZYROOTDIR . 'Images/' . $imageName;
            $imageURL = EAZYROOTURL . 'Images/' . $imageName;

            if (file_exists($imagePath)) {
                return $imageURL;
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public function createNewsletterTable() {
        try {
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
            `eazy_newsletter_delete_mail_page_id` int(11) NOT NULL,
            `eazy_newsletter_send_time` text NOT NULL,
            `eazy_newsletter_last_cron_action` text  NULL,
            `eazy_newsletter_log_data` tinyint(1) NOT NULL
            ) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);

            $this->getSettings()->createSettings();
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public function tableExists() {
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

    public function mailExists($mailAddress) {
        try {
            $mail = filter_var($mailAddress, FILTER_VALIDATE_EMAIL) !== false ? $mailAddress : '';
            $arrayAddress = explode('@', $mail);
            $serverAddress = array_pop($arrayAddress);

            if (checkdnsrr($serverAddress, 'MX')) {
                return true;
            }

            return false;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public function isNewsletterSend($postID) {
        return $isSend = get_post_meta($postID, 'eazy_newsletter_is_send', true) == 1 ? true : 0;
    }

    public function timeToSendNewsletter($postID) {
        $publishDate = strtotime(date('Y-m-d', intval(get_post_meta($postID, 'eazy_newsletter_publish_date', true))));

        $today = strtotime(date('Y-m-d', current_time('timestamp')));

        $now = new DateTime(date('H:i', current_time('timestamp')));

        $sendTime = intval($this->getSettings()->getEazyNewsletterSendTime());

        $publishTime = new DateTime(date('H:i', $sendTime));

        $interval = $publishTime->diff($now);

        $timedifference = intval($interval->format("%i"));

        if ($publishDate === $today && $timedifference <= 5) {
            return true;
        }

        return $timedifference;
    }

    public function sendNewsletter($args) {
        try {
            $query = new WP_Query($args);

            if ($query->have_posts()) {
                $posts = $query->get_posts();
                /* @var $post WP_Post  */
                foreach ($posts as $post) {

                    $postID = $post->ID;

                    /*
                     * Wenn das Sende-Datum mit dem heutigen Datum übereinstimmt und die momentane 
                     * Zeit +/- 5 minuten von der eingestellten Sendezeit beträgt kann der Newsletter versendet werden 
                     */
                    if ($this->isNewsletterSend($postID) === true || $this->timeToSendNewsletter($postID) !== true || $this->getSettings()->hasAddresses() === false) {
                        return;
                    }

                    $addresses = $this->getSettings()->getEazyNewsletterAddresses();

                    /* @var $singleAddress EazyNewsletterEmailAddress */
                    foreach ($addresses as $singleAddress) {

                        $address = $singleAddress->getAddress();

                        if ($singleAddress->isActive() && $this->mailExists($address)) {
                            continue;
                        } else {

                            $timestampRegistered = $singleAddress->getTimestamp();
                            $maxTimeStamp = $timestampRegistered + 86400;
                            $currentTime = current_time('timestamp');

                            if ($maxTimeStamp <= $currentTime || !$this->mailExists($address)) {
                                $this->deleteMail($address);
                            }
                        }
                    }

                    $title = $post->post_title;
                    $content = $post->post_content;

                    $messageContent = $singleAddress->buildHtmlWrapper($content);

                    $headers = $singleAddress->buildHeader();

                    if (wp_mail($address, $title, $messageContent, $headers)) {
                        update_post_meta($postID, 'eazy_newsletter_is_send', 1);
                    }
                }
            }

            wp_reset_postdata();
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }

            wp_reset_postdata();
        }
    }

    public function deleteMail($mailAddress) {
        $newAddressArray = array();
        $mailAddressToDelete = filter_var($mailAddress, FILTER_VALIDATE_EMAIL);
        $currentAddressArray = $this->getSettings()->getEazyNewsletterAddresses();

        foreach ($currentAddressArray as $singleAddress) {
            if ($singleAddress == $mailAddressToDelete) {
                continue;
            }

            $newAddressArray[] = $singleAddress;
        }

        $this->getSettings()->setEazyNewsletterAddresses($newAddressArray);

        if ($this->getSettings()->updateSettings() === true) {
            return true;
        }

        return false;
    }

    public function sendSingleNewsletter($postID) {
        try {

            $post = get_post($postID);

            /*
             * Wenn das Sende-Datum mit dem heutigen Datum übereinstimmt und die momentane 
             * Zeit +/- 5 minuten von der eingestellten Sendezeit beträgt kann der Newsletter versendet werden 
             */
            if ($this->isNewsletterSend($postID) === true || $this->timeToSendNewsletter($postID) !== true || $this->getSettings()->hasAddresses() === false) {
                return;
            }

            $addresses = $this->getSettings()->getEazyNewsletterAddresses();

            $title = $post->post_title;
            $content = $post->post_content;

            $postMetaUpdated = false;

            /* @var $singleAddress EazyNewsletterEmailAddress */
            foreach ($addresses as $singleAddress) {

                $address = $singleAddress->getAddress();

                if ($singleAddress->isActive() && $this->mailExists($address)) {

                    $messageContent = $singleAddress->buildHtmlWrapper($content);

                    $headers = $singleAddress->buildHeader();

                    if (wp_mail($address, $title, $messageContent, $headers)) {

                        if ($postMetaUpdated !== false) {
                            continue;
                        }

                        if (update_post_meta($postID, 'eazy_newsletter_is_send', 1)) {
                            $postMetaUpdated = true;
                        }
                    }
                }
            }

            wp_reset_postdata();
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }

            wp_reset_postdata();
        }
    }

}
