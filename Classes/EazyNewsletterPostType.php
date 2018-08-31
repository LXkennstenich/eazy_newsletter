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
class EazyNewsletterPostType {

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
    public function createPostType() {
        try {
            add_action('init', array($this, 'eazy_newsletter_post_type'));
            add_action('add_meta_boxes', array($this, 'addMetaBox'));
            add_action('save_post', array($this, 'save'));
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @return string
     */
    public function set_content_type_html() {
        return "text/html";
    }

    /**
     * 
     * @param int $postID
     * @return 
     */
    public function save($postID) {
        try {
            /* PostType aus der Datenbank holen */
            $postType = get_post_type($postID);

            /* PostType nicht Eazy Newsletter ? Ende! */
            if ($postType != 'eazy_newsletter') {
                return;
            }

            /* Ohne Publish Date geht nix */
            if (array_key_exists('eazy_newsletter_publish_date', $_POST)) {

                $publishTimeStamp = strtotime($_POST['eazy_newsletter_publish_date']);
                /* Publish-Date in Post-Meta speichern */
                update_post_meta($postID, 'eazy_newsletter_publish_date', current_time($publishTimeStamp));

                $hasBeenSend = get_post_meta($postID, 'eazy_newsletter_is_send', true) == 1 ? true : false;

                if (!$hasBeenSend) {
                    /* Publish-Date in Post-Meta speichern */
                    update_post_meta($postID, 'eazy_newsletter_is_send', 0);
                } else {
                    return;
                }

                /*
                 * Wenn das Sende-Datum mit dem heutigen Datum übereinstimmt und die momentane 
                 * Zeit +/- 5 minuten von der eingestellten Sendezeit beträgt kann der Newsletter versendet werden 
                 */

                if ($this->getSystem()->isNewsletterSend($postID) === true || $this->getSystem()->timeToSendNewsletter($postID) !== true || $this->getSettings()->hasAddresses() === false) {
                    return;
                }

                $this->getSystem()->sendSingleNewsletter($postID);
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     * @param type $post
     */
    public function metaboxContent($post) {
        $publishDate = strtotime(get_post_meta($post->ID, 'eazy_newsletter_publish_date', true));

        if ($publishDate == '') {
            $publishDate = current_time('timestamp');
        }

        $value = date('Y-m-d', intval($publishDate));
        ?>
        <label for="eazy-newsletter-publish-date"><?php var_dump($publishDate); ?></label>
        <input type="date" name="eazy_newsletter_publish_date" id="eazy-newsletter-publish-date" class="eazy-newsletter-publish-date" value="<?php echo current_time($value); ?>">
        <?php
    }

    /**
     * 
     */
    public function addMetaBox() {
        try {
            add_meta_box('eazy_newsletter_date_metabox', 'Veröffentlichungsdatum', array($this, 'metaboxContent'), 'eazy_newsletter');
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

    /**
     * 
     */
    public function removePostType() {
        try {
            if (post_type_exists('eazy_newsletter')) {
                unregister_post_type('eazy_newsletter');
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
    public function eazy_newsletter_post_type() {
        try {
            if (!post_type_exists('eazy_newsletter')) {
                register_post_type('eazy_newsletter', array(
                    'labels' => array(
                        'name' => __('Newsletter'),
                        'singular_name' => __('Newsletter')
                    ),
                    'public' => false,
                    'has_archive' => false,
                    'show_ui' => true
                        )
                );
            }
        } catch (Exception $ex) {
            if (EAZYLOGDATA) {
                EazyNewsletterSystem::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
            }
        }
    }

}
