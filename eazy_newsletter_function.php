<?php

/**
 * Eazy Newsletter
 *
 * @package     PluginPackage
 * @author      Alexander Weese
 * @copyright   2018 Alexander Weese Webdesign
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Eazy Newsletter
 * Plugin URI:  https://alexweese.de/
 * Description: Newsletter Plugin. Send Newsletter by Publishing or WP-Cron Job
 * Version:     1.0.0
 * Author:      Alexander Weese
 * Author URI:  https://alexweese.de/
 * Text Domain: eazy_newsletter
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */
/* @var $settings Settings */

if (!defined('ABSPATH')) {
    die();
}

if (!defined('EAZYROOTDIR')) {
    define('EAZYROOTDIR', plugin_dir_path(__FILE__));
}

if (!defined('EAZYROOTURL')) {
    define('EAZYROOTURL', plugin_dir_url(__FILE__));
}

define('EAZYDEBUGLOG', true);

require_once EAZYROOTDIR . 'Classes/AjaxRequest.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterPostType.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterScripts.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterStyles.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterTemplates.php';
require_once EAZYROOTDIR . 'Classes/EmailAddress.php';
require_once EAZYROOTDIR . 'Classes/Settings.php';
require_once EAZYROOTDIR . 'Classes/SettingsPage.php';
require_once EAZYROOTDIR . 'Classes/Shortcode.php';
require_once EAZYROOTDIR . 'Classes/System.php';

register_activation_hook(__FILE__, 'eazy_newsletter_activate');
register_deactivation_hook(__FILE__, 'eazy_newsletter_deactivate');
add_action('send_newsletter_to_user', 'send_newsletter');

function eazy_newsletter_activate() {

    $system = new System();

    if (!$system->tableExists()) {
        $system->createNewsletterTable();
    }

    if (!wp_next_scheduled('send_newsletter_to_user')) {
        wp_schedule_event(current_time('timestamp'), 'hourly', 'send_newsletter_to_user');
    }
}

function send_newsletter() {
    $settings = Settings::getInstance();

    $args = array(
        'post_type' => array('eazy_newsletter'),
        'post_status' => array('published'),
        'posts_per_page' => '-1',
        'cache_results' => false,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    );


    $query = new WP_Query($args);


    if ($query->have_posts()) {
        $posts = $query->get_posts();
        /* @var $post WP_Post  */
        foreach ($posts as $post) {

            $postID = $post->ID;

            $isSend = get_post_meta($postID, 'eazy_newsletter_is_send', true);

            $publishDate = date('Y-m-d', get_post_meta($postID, 'eazy_newsletter_publish_date', true));

            $today = date('Y-m-d', current_time('timestamp'));

            $now = new DateTime(date('H:i', current_time('timestamp')));

            $sendTime = $settings->getEazyNewsletterSendTime();

            $publishTime = DateTime::createFromFormat('H:i', $sendTime);

            $interval = $publishTime->diff($now);

            $timedifference = intval($interval->format("%i"));

            if ($isSend == 0) {
                System::debugLog('zeitunterschied: ' . $timedifference);
                if ($publishDate === $today && ($timedifference < 30 || $timedifference < -30)) {
                    if (sizeof($settings->getEazyNewsletterAddresses()) > 0) {
                        $addresses = $settings->getEazyNewsletterAddresses();

                        $title = $post->post_title;
                        $content = $post->post_content;

                        $headers = array();
                        $headers[] = 'From: "' . $settings->getEazyNewsletterName() . '"' . '<' . $settings->getEazyNewsletterMail() . '>';

                        if ($settings->getEazyNewsletterHtml() === true) {
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
                            update_post_meta($postID, 'eazy_newsletter_is_send', 1);
                        }
                    }
                }
            }
        }
    }

    wp_reset_postdata();
}

function eazy_newsletter_deactivate() {
    wp_clear_scheduled_hook('send_newsletter_to_user');
    Shortcode::removeShortcodes();
    AjaxRequest::removeRequests();
    EazyNewsletterScripts::removeScripts();
    EazyNewsletterStyles::removeStyles();
    EazyNewsletterTemplates::removeTemplates();
    EazyNewsletterPostType::removePostType();
}

$shortcode = new Shortcode();
$ajaxRequest = new AjaxRequest();
$eazyNewsletterScripts = new EazyNewsletterScripts();
$eazyNewsletterStyles = new EazyNewsletterStyles();
$eazyNewsletterPostType = new EazyNewsletterPostType();
$eazyNewsletterTemplates = new EazyNewsletterTemplates();
$settingsPage = new SettingsPage();

$ajaxRequest->createRequests();
$eazyNewsletterScripts->enqueueScripts();
$eazyNewsletterStyles->enqueueStyles();
$eazyNewsletterPostType->createPostType();
$eazyNewsletterTemplates->createTemplates();
$shortcode->createShortCodes();
$settingsPage->render();

function my_plugin_load_plugin_textdomain() {
    load_plugin_textdomain('eazy_newsletter', FALSE, basename(dirname(__FILE__)) . '/lang/');
}

add_action('plugins_loaded', 'my_plugin_load_plugin_textdomain');












