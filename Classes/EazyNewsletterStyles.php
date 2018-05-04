<?php

if (!defined('ABSPATH')) {
    die();
}

class EazyNewsletterStyles {

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
    public function enqueueStyles() {
        add_action('wp_enqueue_scripts', array($this, 'eazy_newsletter_styles'), 90);
        add_action('admin_enqueue_scripts', array($this, 'eazy_newsletter_backend_styles'));
    }

    /**
     * 
     */
    public static function removeStyles() {
        if (wp_style_is('eazy-newsletter-frontend-style', 'enqueued')) {
            wp_dequeue_style('eazy-newsletter-frontend-style');
        }

        if (wp_style_is('eazy-newsletter-backend-style', 'enqueued')) {
            wp_dequeue_style('eazy-newsletter-backend-style');
        }
    }

    /**
     * 
     */
    public function eazy_newsletter_styles() {
        if (!wp_style_is('eazy-newsletter-frontend-style', 'enqueued')) {
            wp_enqueue_style('eazy-newsletter-frontend-style', System::eazyNewsletterStyleUrl('eazy-newsletter-frontend-style'));
        }
    }

    /**
     * 
     */
    public function eazy_newsletter_backend_styles() {
        if (!wp_style_is('eazy-newsletter-backend-style', 'enqueued')) {
            wp_enqueue_style('eazy-newsletter-backend-style', System::eazyNewsletterStyleUrl('eazy-newsletter-backend-style'));
        }
    }

}
