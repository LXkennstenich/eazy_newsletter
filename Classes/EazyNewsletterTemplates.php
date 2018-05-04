<?php

if (!defined('ABSPATH')) {
    die();
}

class EazyNewsletterTemplates {

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
    public function createTemplates() {
        if (!has_filter('template_include')) {
            add_filter('template_include', array($this, 'activation_page_template'), 99);
        }
    }

    /**
     * 
     */
    public static function removeTemplates() {
        if (has_filter('template_include')) {
            remove_filter('template_include', 'activation_page_template');
        }
    }

    /**
     * 
     * @param type $template
     * @return type
     */
    public function activation_page_template($template) {

        $pageId = $this->getSettings()->getEazyNewsletterActivationPageID();
        $post = get_post($pageId);
        $title = $post->post_title;

        if (is_page($title)) {
            $new_template = include System::getViewPath('eazy_newsletter_activation_page');
            if ('' != $new_template) {
                return $new_template;
            }
        }

        return $template;
    }

}
