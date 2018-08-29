<?php

/**
 * Kein Deinstallationsvorgang ? die
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/**
 * Eazy Newsletter Settings Table löschen
 */
try {
    global $wpdb;
    $tableName = $wpdb->prefix . 'eazy_newsletter_settings';
    $wpdb->query("DROP TABLE IF EXISTS $tableName");
} catch (Exception $ex) {
    if (EAZYDEBUGLOG) {
        EazyNewsletterSystem::debugLog(__('Ausnahme: ' . $ex->getMessage() . ' Datei: ' . __FILE__ . ' Zeile: ' . __LINE__ . ' Funktion: ' . __FUNCTION__, 'eazy_newsletter'));
    }
}


