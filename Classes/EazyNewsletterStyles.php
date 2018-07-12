<?php

if (!defined('ABSPATH')) {
    die();
}

class EazyNewsletterStyles {

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
    public function enqueueStyles() {
        try {
            add_action('wp_enqueue_scripts', array($this, 'eazy_newsletter_styles'), 90);
            add_action('admin_enqueue_scripts', array($this, 'eazy_newsletter_backend_styles'));
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function removeStyles() {
        try {
            if (wp_style_is('eazy-newsletter-frontend-style', 'enqueued')) {
                wp_dequeue_style('eazy-newsletter-frontend-style');
            }

            if (wp_style_is('eazy-newsletter-backend-style', 'enqueued')) {
                wp_dequeue_style('eazy-newsletter-backend-style');
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
    public function eazy_newsletter_styles() {
        try {
            if (!wp_style_is('eazy-newsletter-frontend-style', 'enqueued')) {
                wp_enqueue_style('eazy-newsletter-frontend-style', System::eazyNewsletterStyleUrl('eazy-newsletter-frontend-style.min'));
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
    public function eazy_newsletter_backend_styles() {
        try {
            if (!wp_style_is('eazy-newsletter-backend-style', 'enqueued')) {
                wp_enqueue_style('eazy-newsletter-backend-style', System::eazyNewsletterStyleUrl('eazy-newsletter-backend-style.min'));
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

}
