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
class EazyNewsletterTemplates {

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
     * 
     */
    public function createTemplates() {
        try {
            if (has_filter('template_include', array($this, 'activation_page_template')) === false) {
                add_filter('template_include', array($this, 'activation_page_template'), 99);
            }

            if (has_filter('template_include', array($this, 'delete_mail_page_template')) === false) {
                add_filter('template_include', array($this, 'delete_mail_page_template'), 99);
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::debugLog(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function removeTemplates() {
        try {
            if (has_filter('template_include', array($this, 'activation_page_template')) !== false) {
                remove_filter('template_include', array($this, 'activation_page_template'));
            }

            if (has_filter('template_include', array($this, 'delete_mail_page_template')) !== false) {
                remove_filter('template_include', array($this, 'delete_mail_page_template'));
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::debugLog(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @param type $template
     * @return type
     */
    public function activation_page_template($template) {
        try {
            $pageId = $this->getSettings()->getEazyNewsletterActivationPageID();
            $post = get_post($pageId);
            $title = $post->post_title;

            if (is_page($title)) {
                $new_template = include EazyNewsletterSystem::getViewPath('eazy_newsletter_activation_page');
                if ('' != $new_template) {
                    return $new_template;
                }
            }

            return $template;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::debugLog(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }

            return $template;
        }
    }

    /**
     * 
     * @param type $template
     * @return type
     */
    public function delete_mail_page_template($template) {
        try {
            $pageId = $this->getSettings()->getEazyNewsletterDeleteMailPageID();
            $post = get_post($pageId);
            $title = $post->post_title;

            if (is_page($title)) {
                $new_template = include EazyNewsletterSystem::getViewPath('eazy_newsletter_delete_mail_page');
                if ('' != $new_template) {
                    return $new_template;
                }
            }

            return $template;
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::debugLog(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }

            return $template;
        }
    }

}
