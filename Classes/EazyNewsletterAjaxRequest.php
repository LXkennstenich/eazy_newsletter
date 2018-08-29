<?php

if (!defined('ABSPATH')) {
    die();
}

/**
 * Registriert den Ajax Callback
 */
class EazyNewsletterAjaxRequest {

    /**
     * Enthält die Daten aus der Datenbank
     * @var EazyNewsletterSettings
     */
    protected $settings;

    /**
     * System-Objekt
     * @var EazyNewsletterSystem
     */
    protected $system;

    /**
     * Konstruktor
     */
    function __construct() {
        $this->setSystem(new EazyNewsletterSystem());
        $this->setSettings($this->getSystem()->getSettings());
    }

    /**
     * 
     * @param EazyNewsletterSettings $settings
     */
    private function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * 
     * @return EazyNewsletterSettings
     */
    private function getSettings() {
        return $this->settings;
    }

    /**
     * 
     * @param EazyNewsletterSystem $system
     */
    private function setSystem($system) {
        $this->system = $system;
    }

    /**
     * 
     * @return EazyNewsletterSystem
     */
    private function getSystem() {
        return $this->system;
    }

    /**
     * Registriert unseren Callback für Frontent und Backend Ajax 
     */
    public function createRequests() {
        try {
            if (!has_action('wp_ajax_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'))) {
                add_action('wp_ajax_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'));
            }

            if (!has_action('wp_ajax_nopriv_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'))) {
                add_action('wp_ajax_nopriv_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'));
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * Bei Deaktivierung müssen unsere Callbacks wieder entfernt werden
     */
    public function removeRequests() {
        try {
            if (has_action('wp_ajax_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'))) {
                remove_action('wp_ajax_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'));
            }

            if (has_action('wp_ajax_nopriv_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'))) {
                remove_action('wp_ajax_nopriv_eazyNewsletterRequests', array($this, 'eazyNewsletterRequests'));
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * Unser Ajax-Callback. Hier werden die Controller eingebunden -> je nach angefragter Action
     */
    public function eazyNewsletterRequests() {
        try {
            if (isset($_POST['eazy_newsletter_action']) && !empty($_POST['eazy_newsletter_action'])) {
                $actionDecoded = base64_decode($_POST['eazy_newsletter_action']);
                $action = filter_var($actionDecoded, FILTER_SANITIZE_STRING) ? $actionDecoded : null;

                if (EazyNewsletterSystem::ajaxControllerExists($action)) {
                    $isAjax = true;
                    $system = new EazyNewsletterSystem();
                    include EazyNewsletterSystem::getAjaxControllerPath($action);
                }
            }

            wp_die();
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }

            wp_die();
        }
    }

}
