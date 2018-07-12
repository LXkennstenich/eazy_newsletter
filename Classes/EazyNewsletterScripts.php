<?php

if (!defined('ABSPATH')) {
    die();
}

class EazyNewsletterScripts {

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
     * Konstruktor
     */
    function __construct() {
        $this->setSystem(new System());
        $this->setSettings($this->getSystem()->getSettings());
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
     */
    public function enqueueScripts() {
        try {
            add_action('wp_enqueue_scripts', array($this, 'eazy_newsletter_scripts'), 90);
            add_action('admin_enqueue_scripts', array($this, 'eazy_newsletter_backend_scripts'));
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function removeScripts() {
        try {
            if (wp_script_is('eazy-newsletter-jquery-js', 'enqueued')) {
                wp_dequeue_script('eazy-newsletter-jquery-js');
            }

            if (wp_script_is('eazy-newsletter-jquery-js', 'enqueued')) {
                wp_dequeue_script('eazy-newsletter-custom-js');
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function eazy_newsletter_scripts() {
        try {
            if (!wp_script_is('eazy-newsletter-jquery-js', 'enqueued')) {
                wp_enqueue_script('eazy-newsletter-jquery-js', System::eazyNewsletterScriptUrl('jquery.min'));
            }

            if (!wp_script_is('eazy-newsletter-custom-js', 'enqueued')) {
                wp_enqueue_script('eazy-newsletter-custom-js', System::eazyNewsletterScriptUrl('eazy-newsletter-custom-js.min'));
            }

            wp_localize_script('eazy-newsletter-custom-js', 'getAjaxUrl', array('ajaxurl' => admin_url('admin-ajax.php')));
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function eazy_newsletter_backend_scripts() {
        try {
            if (!wp_script_is('eazy-newsletter-backend', 'enqueued')) {
                wp_enqueue_script('eazy-newsletter-backend', System::eazyNewsletterScriptUrl('eazy-newsletter-backend.min'));
            }

            wp_localize_script('eazy-newsletter-backend', 'getAjaxUrl', array('ajaxurl' => admin_url('admin-ajax.php')));
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

}
