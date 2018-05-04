<?php
if (!defined('ABSPATH')) {
    die();
}

class Shortcode {

    /**
     *
     * @var Settings
     */
    var $settings;

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

    public function createShortCodes() {
        if (!shortcode_exists('eazy_newsletter')) {
            add_shortcode('eazy_newsletter', array($this, 'custom_shortcode'));
        }
    }

    public static function removeShortcodes() {
        if (shortcode_exists('eazy_newsletter')) {
            remove_shortcode('eazy_newsletter');
        }
    }

    public function custom_shortcode($atts) {

        $atts = shortcode_atts(
                array(
            'style' => '',
            'verifyemail' => 'true',
                ), $atts, 'eazy_newsletter'
        );
        ?>
        <div id="eazy-newsletter-register-form" class="eazy-newsletter-register-form">
            <input type="hidden" id="eazy-newsletter-time" value="<?php echo current_time('timestamp'); ?>">
            <input type="hidden" id="eazy-newsletter-action"  value="<?php echo System::getAjaxRequestValue('RegisterNewEmail'); ?>">
            <input id="eazy-newsletter-mail-three" class="eazy-newsletter-mail-two" type="text" autocomplete="off">
            <input id="eazy-newsletter-mail-two" class="eazy-newsletter-mail-two" type="email" autocomplete="off">
            <input id="eazy-newsletter-mail" class="eazy-newsletter-mail" type="email" autocomplete="off" required="true" placeholder="<?php echo __('Ihre E-Mail Adresse...', 'eazy_newsletter'); ?>">
            <button id="eazy-newsletter-submit-button" class="eazy-newsletter-submit-button" value="Eintragen"><?php echo __('Eintragen', 'eazy_newsletter'); ?></button>
        </div>
        <div class = "ajax-message" id = "ajax-message">
            <p class = "text"></p>
        </div>
        <div class = "loading-div" id = "loading-div">
            <img src = "<?php echo System::getImageURL('ajax-loader.gif'); ?>" />
        </div>
        <?php
    }

}
