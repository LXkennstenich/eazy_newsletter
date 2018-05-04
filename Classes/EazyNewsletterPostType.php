<?php
if (!defined('ABSPATH')) {
    die();
}

class EazyNewsletterPostType {

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
     */
    public function createPostType() {
        add_action('init', array($this, 'eazy_newsletter_post_type'));
        add_action('add_meta_boxes', array($this, 'addMetaBox'));
        add_action('save_post', array($this, 'save'));
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
     * @param type $post_id
     * @return type
     */
    public function save($post_id) {

        $postType = get_post_type($post_id);

        if ($postType != 'eazy_newsletter') {
            return;
        }

        $post = get_post($post_id);

        if (array_key_exists('eazy_newsletter_publish_date', $_POST)) {

            $date = strtotime($_POST['eazy_newsletter_publish_date']);
            update_post_meta($post_id, 'eazy_newsletter_publish_date', $date);
            update_post_meta($post_id, 'eazy_newsletter_is_send', 0);
            $publishDate = date('Y-m-d', get_post_meta($post_id, 'eazy_newsletter_publish_date', true));
            $today = date('Y-m-d', current_time('timestamp'));
            $now = new DateTime(date('H:i', current_time('timestamp')));
            $sendTime = $this->getSettings()->getEazyNewsletterSendTime();
            $publishTime = DateTime::createFromFormat('H:i', $sendTime);
            $interval = $publishTime->diff($now);
            $timedifference = intval($interval->format("%i"));

            if ($publishDate === $today && ($timedifference < 30 || $timedifference < -30)) {
                System::debugLog('true');

                if (sizeof($this->getSettings()->getEazyNewsletterAddresses()) > 0) {
                    $addresses = $this->getSettings()->getEazyNewsletterAddresses();

                    $title = $post->post_title;
                    $content = $post->post_content;

                    $headers = array();
                    $headers[] = 'From: "' . $this->getSettings()->getEazyNewsletterName() . '"' . '<' . $this->getSettings()->getEazyNewsletterMail() . '>';

                    if ($this->getSettings()->getEazyNewsletterHtml() === true) {
                        $headers[] = 'Content-Type: text/html';
                    } else {
                        $headers[] = 'Content-Type: text/plain';
                    }


                    $i = 0;


                    /* @var $singleAddress EmailAddress */
                    foreach ($addresses as $singleAddress) {
                        if ($singleAddress->isActive()) {
                            if (wp_mail($singleAddress->getAddress(), $title, $content, $headers)) {
                                $i++;
                            }
                        }
                    }

                    if ($i > 0) {
                        update_post_meta($post_id, 'eazy_newsletter_is_send', 1);
                    }
                }
            }
        }
    }

    /**
     * 
     * @param type $post
     */
    public function metaboxContent($post) {
        $value = get_post_meta($post->ID, 'eazy_newsletter_publish_date', true);
        ?>
        <label for="eazy-newsletter-publish-date"><?php echo __('Veröffentlichen am:', 'eazy_newsletter'); ?></label>
        <input type="date" name="eazy_newsletter_publish_date" id="eazy-newsletter-publish-date" class="eazy-newsletter-publish-date" value="<?php echo date('Y-m-d', intval($value)); ?>">
        <?php
    }

    /**
     * 
     */
    public function addMetaBox() {
        add_meta_box('eazy_newsletter_date_metabox', 'Veröffentlichungsdatum', array($this, 'metaboxContent'), 'eazy_newsletter');
    }

    /**
     * 
     */
    public static function removePostType() {
        if (post_type_exists('eazy_newsletter')) {
            unregister_post_type('eazy_newsletter');
        }
    }

    /**
     * 
     */
    public function eazy_newsletter_post_type() {
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
    }

}
