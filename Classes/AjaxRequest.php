<?php

if (!defined('ABSPATH')) {
    die();
}

class AjaxRequest {

    /**
     *
     * @var Settings
     */
    var $settings;

    /**
     * 
     */
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

    /**
     * 
     */
    public function createRequests() {

        if (!has_action('wp_ajax_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'))) {
            add_action('wp_ajax_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'));
        }

        if (!has_action('wp_ajax_nopriv_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'))) {
            add_action('wp_ajax_nopriv_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'));
        }
    }

    /**
     * 
     */
    public static function removeRequests() {
        if (has_action('wp_ajax_eazyNewsletterRequests', 'eazyNewsletterRequests')) {
            remove_action('wp_ajax_eazyNewsletterRequests', 'eazyNewsletterRequests');
        }

        if (has_action('wp_ajax_nopriv_eazyNewsletterRequests', 'eazyNewsletterRequests')) {
            remove_action('wp_ajax_nopriv_eazyNewsletterRequests', 'eazyNewsletterRequests');
        }
    }

    /**
     * 
     */
    public function eazyNewsletterRequests() {
        if (isset($_POST['eazy_newsletter_action']) && !empty($_POST['eazy_newsletter_action'])) {
            $actionDecoded = base64_decode($_POST['eazy_newsletter_action']);
            $action = filter_var($actionDecoded, FILTER_SANITIZE_STRING) ? $actionDecoded : null;

            if (System::ajaxControllerExists($action)) {
                $isAjax = true;
                $system = new System();
                $settings = Settings::getUpdatetInstance();

                include System::getAjaxControllerPath($action);
            }
        }

        wp_die();
    }

}
