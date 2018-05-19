<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EazyCronJob
 *
 * @author alexw
 */
class EazyCronJob {

    /**
     * Enthält die Daten aus der Datenbank
     * @var Settings
     */
    var $settings;

    /**
     * System-Objekt
     * @var System
     */
    var $system;

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

    public function createCronJob() {
        if (!wp_next_scheduled('send_newsletter_to_user')) {
            wp_schedule_event(time(), 'five_minutes', 'send_newsletter_to_user');
        }
        if (!has_action('send_newsletter_to_user')) {
            add_action('send_newsletter_to_user', array($this, 'send_newsletter'));
        }
    }

    public function removeCronJob() {
        wp_clear_scheduled_hook('send_newsletter_to_user');
    }

    /**
     * WP-Cron Callback
     */
    function send_newsletter() {

        /* Wenn automatisches Versenden nicht gewollt ist abbrechen */
        if ($this->getSettings()->getEazyNewsletterAutomatic() !== true) {
            return;
        }

        $args = array(
            'post_type' => array('eazy_newsletter'),
            'post_status' => array('published'),
            'posts_per_page' => '-1',
            'cache_results' => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );

        $this->getSettings()->setEazyNewsletterLastCronAction(current_time('timestamp'));
        $this->getSettings()->updateSettings();

        /* Newsletter versenden */
        $this->getSystem()->sendNewsletter($args);
    }

    public function removeCronInterval() {
        remove_filter('cron_schedules', array($this, 'custom_cron_interval'));
    }

    /**
     * Fügt unseren Custom Interval zu den vorhandenen WP-Cron Intervallen hinzu
     */
    public function createCronInterval() {
        if (!has_filter('cron_schedules', array($this, 'custom_cron_interval'))) {
            add_filter('cron_schedules', array($this, 'custom_cron_interval'));
        }
    }

    public function custom_cron_interval($schedules) {
        try {
            if (!in_array('five_minutes', $schedules)) {
                $schedules['five_minutes'] = array(
                    'interval' => 300,
                    'display' => __('Every 5 Minutes', 'eazy_newsletter')
                );
            }

            return $schedules;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }

            return $schedules;
        }
    }

}
