<?php
if (!defined('ABSPATH')) {
    die();
}

class EazyNewsletterShortcode {

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
    public function createShortCodes() {
        try {
            if (!shortcode_exists('eazy_newsletter')) {
                add_shortcode('eazy_newsletter', array($this, 'custom_shortcode'));
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function removeShortcodes() {
        try {
            if (shortcode_exists('eazy_newsletter')) {
                remove_shortcode('eazy_newsletter');
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function custom_shortcode() {
        ?>
        <div id="eazy-newsletter-register-form" class="eazy-newsletter-register-form">
            <input type="hidden" id="eazy-newsletter-time" value="<?php echo current_time('timestamp'); ?>">
            <input type="hidden" id="eazy-newsletter-action"  value="<?php echo EazyNewsletterSystem::getAjaxRequestValue('RegisterNewEmail'); ?>">
            <input id="eazy-newsletter-mail-three" class="eazy-newsletter-mail-two" type="text" autocomplete="off">
            <input id="eazy-newsletter-mail-two" class="eazy-newsletter-mail-two" type="email" autocomplete="off">
            <input id="eazy-newsletter-mail" class="eazy-newsletter-mail" type="email" autocomplete="off" required="true" placeholder="<?php echo __('Ihre E-Mail Adresse...', 'eazy_newsletter'); ?>">
            <button id="eazy-newsletter-submit-button" class="eazy-newsletter-submit-button" value="Eintragen"><?php echo __('Eintragen', 'eazy_newsletter'); ?></button>
        </div>
        <div class = "ajax-message" id = "ajax-message">
            <p class = "text"></p>
        </div>
        <div class = "loading-div" id = "loading-div">
            <img src = "<?php echo EazyNewsletterSystem::getImageURL('ajax-loader.gif'); ?>" />
        </div>
        <?php
    }

}
