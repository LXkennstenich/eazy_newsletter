<?php

/**
 * Eazy Newsletter
 *
 * @package     eazy_newsletter
 * @author      Alexander Weese
 * @copyright   2018 Alexander Weese Webdesign
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 * Plugin Name: Eazy Newsletter
 * Plugin URI:  https://alexweese.de/
 * Description: Newsletter Plugin. Send Newsletter by Publishing or WP-Cron Job
 * Version:     1.0.0
 * Author:      Alexander Weese
 * Author URI:  https://alexweese.de/
 * Text Domain: eazy_newsletter
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */
/**
 * Nicht in WordPress ? die
 */
if (!defined('ABSPATH')) {
    die();
}

/**
 * Plugin Root-Directory definieren
 */
if (!defined('EAZYROOTDIR')) {
    define('EAZYROOTDIR', plugin_dir_path(__FILE__));
}

/**
 * Plugin Root-Url definieren
 */
if (!defined('EAZYROOTURL')) {
    define('EAZYROOTURL', plugin_dir_url(__FILE__));
}

/**
 * Alle Klassen einbinden.
 */
require_once EAZYROOTDIR . 'Classes/Settings.php';
require_once EAZYROOTDIR . 'Classes/System.php';
require_once EAZYROOTDIR . 'Classes/AjaxRequest.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterPostType.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterScripts.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterStyles.php';
require_once EAZYROOTDIR . 'Classes/EazyNewsletterTemplates.php';
require_once EAZYROOTDIR . 'Classes/EmailAddress.php';
require_once EAZYROOTDIR . 'Classes/SettingsPage.php';
require_once EAZYROOTDIR . 'Classes/Shortcode.php';
require_once EAZYROOTDIR . 'Classes/EazyCronJob.php';

/**
 * Create every Object we need
 */
$settings = Settings::getInstance();
$shortcode = new Shortcode();
$ajaxRequest = new AjaxRequest();
$eazyNewsletterScripts = new EazyNewsletterScripts();
$eazyNewsletterStyles = new EazyNewsletterStyles();
$eazyNewsletterPostType = new EazyNewsletterPostType();
$eazyNewsletterTemplates = new EazyNewsletterTemplates();
$settingsPage = new SettingsPage();
$eazyCronJob = new EazyCronJob();

$eazyCronJob->createCronInterval();
$eazyCronJob->createCronJob();

/**
 * Soll ein Fehler-Log erstellt werden ? 
 */
$eazyLogData = $settings->getEazyNewsletterLogData();

define('EAZYLOGDATA', $eazyLogData);

/**
 * Activation Hook Callback
 */
function eazy_newsletter_activate() {
    try {
        $system = new System();

        if (!$system->tableExists()) {
            $system->createNewsletterTable();
        }
    } catch (Exception $ex) {
        if (EAZYLOGDATA) {
            System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
        }
    }
}

/**
 * Activation Hook 
 */
register_activation_hook(__FILE__, 'eazy_newsletter_activate');

/**
 * Deactivation Hook
 */
function eazy_newsletter_deactivate() {
    try {
        $shortcode = new Shortcode();
        $ajaxRequest = new AjaxRequest();
        $eazyNewsletterScripts = new EazyNewsletterScripts();
        $eazyNewsletterStyles = new EazyNewsletterStyles();
        $eazyNewsletterPostType = new EazyNewsletterPostType();
        $eazyNewsletterTemplates = new EazyNewsletterTemplates();
        $eazyCronJob = new EazyCronJob();

        $shortcode->removeShortcodes();
        $ajaxRequest->removeRequests();
        $eazyNewsletterScripts->removeScripts();
        $eazyNewsletterStyles->removeStyles();
        $eazyNewsletterPostType->removePostType();
        $eazyNewsletterTemplates->removeTemplates();
        $eazyCronJob->removeCronInterval();
        $eazyCronJob->removeCronJob();
    } catch (Exception $ex) {
        if (EAZYLOGDATA) {
            System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
        }
    }
}

register_deactivation_hook(__FILE__, 'eazy_newsletter_deactivate');

/**
 * Register Ajax Calls
 */
$ajaxRequest->createRequests();

/**
 * Enqueue all Scripts
 */
$eazyNewsletterScripts->enqueueScripts();

/**
 * Enqueue all Stylesheets
 */
$eazyNewsletterStyles->enqueueStyles();

/**
 * Create the custom PostType
 */
$eazyNewsletterPostType->createPostType();

/**
 * Register Templates
 */
$eazyNewsletterTemplates->createTemplates();

/**
 * Create the Shortcode for the Frontend
 */
$shortcode->createShortCodes();

/**
 * Render the Settingspage
 */
$settingsPage->render();

/**
 * Translation Callback
 */
function my_plugin_load_plugin_textdomain() {
    try {
        load_plugin_textdomain('eazy_newsletter', FALSE, basename(dirname(__FILE__)) . '/lang/');
    } catch (Exception $ex) {
        if (EAZYLOGDATA) {
            System::Log(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
        }
    }
}

/**
 * New Action for Translation
 */
add_action('plugins_loaded', 'my_plugin_load_plugin_textdomain');












