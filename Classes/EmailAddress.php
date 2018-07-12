<?php

if (!defined('ABSPATH')) {
    die();
}

class EmailAddress {

    /**
     * Enthält die Daten aus der Datenbank
     * @var Settings
     */
    protected $settings;

    /**
     * System-Objekt
     * @var System
     */
    protected $system;

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
        $this->setSystem(new System());
        $this->setSettings($this->getSystem()->getSettings());
    }

    /**
     * 
     * @param System $system
     */
    private function setSystem($system) {
        $this->system = $system;
    }

    /**
     * 
     * @return System
     */
    private function getSystem() {
        return $this->system;
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
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $this->getSettings()->getEazyNewsletterMail();
    }

    /**
     * 
     * @return type
     */
    private function getBlogUrl() {
        return get_bloginfo('wp_url');
    }

    /**
     * 
     * @return boolean|int
     */
    public function getError() {
        try {
            $mail = $this->getAddress();
            $arrayAddress = explode('@', $mail);
            $serverAddress = array_pop($arrayAddress);


            if (!checkdnsrr($serverAddress, 'MX')) {
                return 2;
            }

            if ($this->getToken() == null && $this->getActive() == 1 || $this->getToken() == null && $this->getActive() == 0) {
                return 3;
            }

            return false;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @return string
     */
    private function getServerAddress() {
        try {
            $host = $this->getHost();

            if (strpos('http://', $this->getHost()) === false || strpos('https://', $this->getHost()) === false) {
                $host = 'http://' . $this->getHost();
            }

            return $host;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @return type
     */
    private function getActivationPageTitle() {
        try {
            $post = get_post($this->getActivationPageId());
            return $pageTitle = $post->post_title;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
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
    private function getDeleteMailPageTitle() {
        try {
            $post = get_post($this->getDeleteMailPageId());
            return $pageTitle = $post->post_title;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @return type
     */
    private function getDeleteMailPageId() {
        return $pageId = $this->getSettings()->getEazyNewsletterDeleteMailPageID();
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
    private function getUnsubscribeLink() {
        try {
            $pageTitle = $this->getDeleteMailPageTitle();

            $deleteMailUrl = $this->getServerAddress() . '/' . $pageTitle . '?a=delete&tk=' . $this->getToken() . '&u=' . $this->getAddress();

            return $deleteMailUrl;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @return string
     */
    private function getActivationUrl() {
        try {
            $pageTitle = $this->getActivationPageTitle();

            $activationUrl = $this->getServerAddress() . '/' . $pageTitle . '?a=activate&tk=' . $this->getToken() . '&u=' . $this->getAddress();

            return $activationUrl;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @return type
     */
    private function getMessageBody() {
        try {
            //$css = $this->getSettings()->getEazyNewsletterCustomHtmlValidationLink();

            $css = '';

            if ($this->getSettings()->getEazyNewsletterHtml() === true) {
                $message = '';
                $message .= __('Sie haben sich für unseren Newsletter auf ' . $this->getServerAddress() . ' angemeldet.', 'eazy_newsletter') . '<br/>';
                $message .= __('Bitte bestätigen Sie die Anmeldung durch Klicken des folgenden Links:', 'eazy_newsletter') . '<br/>' . '<br/>';
                $message .= '<a style="' . $css . '" href=' . '"' . $this->getActivationUrl() . '"' . '>' . __('Bestätigen', 'eazy_newsletter') . '</a>' . '<br/>' . '<br/>';
                $message .= __('Wenn Sie den Link nicht aktivieren, erhalten Sie keine weiteren Mitteilungen von uns.', 'eazy_newsletter') . '<br/>';
                $message .= __('Ihre E-Mail Adresse wird automatisch aus unserem Verteiler gelöscht.', 'eazy_newsletter') . '<br/>';
                //todo impressum mit einbinden
            } else {
                $message = '';
                $message .= __('Sie haben sich für unseren Newsletter auf' . $this->getServerAddress() . 'angemeldet.', 'eazy_newsletter') . '\n';
                $message .= __('Bitte bestätigen Sie die Anmeldung durch Klicken des folgenden Links:', 'eazy_newsletter') . '<br/>' . '<br/>';
                $message .= '<a style="' . $css . '" href=' . '"' . $this->getActivationUrl() . '"' . '>' . __('Bestätigen', 'eazy_newsletter') . '</a>' . '<br/>' . '<br/>';
                $message .= __('Wenn Sie den Link nicht aktivieren, erhalten Sie keine weiteren Mitteilungen von uns.', 'eazy_newsletter') . '<br/>';
                $message .= __('Ihre E-Mail Adresse wird automatisch aus unserem Verteiler gelöscht.', 'eazy_newsletter') . '<br/>';
            }

            return $message;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @return boolean
     */
    public function sendValidationMail() {
        try {
            $headers = $this->buildHeader();

            $messageBody = $this->buildHtmlWrapper($this->getMessageBody(), true);

            if (wp_mail($this->getAddress(), __('Bitte bestätigen Sie die Eintragung im Newsletter', 'eazy_newsletter'), $messageBody, $headers)) {
                return true;
            }

            return false;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    public function buildHtmlWrapper($messageBody, $activation = false) {
        $html = '';

        if ($this->getSettings()->getEazyNewsletterCustomHtmlHeader() != '') {
            $html .= $this->getSettings()->getEazyNewsletterCustomHtmlHeader();
        }

        if ($this->getSettings()->getEazyNewsletterCustomHtmlBody() != '') {
            $html .= $this->getSettings()->getEazyNewsletterCustomHtmlBody();
        }

        $html .= $messageBody . "\n";

        if ($this->getSettings()->getEazyNewsletterCustomHtmlFooter() != '') {
            $html .= $this->getSettings()->getEazyNewsletterCustomHtmlFooter();
        }

        if ($activation === false) {
            if ($this->getSettings()->getEazyNewsletterHtml() === true) {
                $html .= "<br/>";
                $html .= '<a href="' . $this->getUnsubscribeLink() . '" class="eazy-newsletter-unsubscribe-link">' . __('Vom Newsletter abmelden', 'eazy_newsletter') . '</a>';
            } else {
                $html .= $this->getUnsubscribeLink();
            }
        }

        return $html;
    }

    public function buildHeader() {
        $headers = array();
        $headers[] = 'From: "' . $this->getSettings()->getEazyNewsletterName() . '"' . '<' . $this->getSettings()->getEazyNewsletterMail() . '>';
        $headers[] = 'X-Sender: ' . $this->getHost();

        if ($this->getSettings()->getEazyNewsletterHtml() === true) {
            $headers[] = 'Content-Type: text/html';
        } else {
            $headers[] = 'Content-Type: text/plain';
        }

        return $headers;
    }

}
