<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

global $wpdb;
$tableName = $wpdb->prefix . 'eazy_newsletter_settings';
$wpdb->query("DROP TABLE IF EXISTS $tableName");

