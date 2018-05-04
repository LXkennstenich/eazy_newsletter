<?php

if (!defined('ABSPATH')) {
    die();
}

class EazyNewsletterScripts {

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
    public function enqueueScripts() {
        add_action('wp_enqueue_scripts', array($this, 'eazy_newsletter_scripts'), 90);
        add_action('admin_enqueue_scripts', array($this, 'eazy_newsletter_backend_scripts'));
    }

    /**
     * 
     */
    public static function removeScripts() {
        if (wp_script_is('eazy-newsletter-jquery-js', 'enqueued')) {
            wp_dequeue_script('eazy-newsletter-jquery-js');
        }

        if (wp_script_is('eazy-newsletter-jquery-js', 'enqueued')) {
            wp_dequeue_script('eazy-newsletter-custom-js');
        }
    }

    /**
     * 
     */
    public function eazy_newsletter_scripts() {
        if (!wp_script_is('eazy-newsletter-jquery-js', 'enqueued')) {
            wp_enqueue_script('eazy-newsletter-jquery-js', System::eazyNewsletterScriptUrl('jquery.min'));
        }

        if (!wp_script_is('eazy-newsletter-custom-js', 'enqueued')) {
            wp_enqueue_script('eazy-newsletter-custom-js', System::eazyNewsletterScriptUrl('eazy-newsletter-custom-js'));
        }

        wp_localize_script('eazy-newsletter-custom-js', 'getAjaxUrl', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

    /**
     * 
     */
    public function eazy_newsletter_backend_scripts() {
        if (!wp_script_is('eazy-newsletter-backend', 'enqueued')) {
            wp_enqueue_script('eazy-newsletter-backend', System::eazyNewsletterScriptUrl('eazy-newsletter-backend'));
        }

        wp_localize_script('eazy-newsletter-backend', 'getAjaxUrl', array('ajaxurl' => admin_url('admin-ajax.php')));
    }

}
