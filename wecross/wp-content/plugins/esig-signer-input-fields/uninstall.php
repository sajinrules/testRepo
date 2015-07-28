<?php
/**
 * Fired when the plugin is uninstalled.
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$table_prefix = $wpdb->prefix . "esign_";
$sql = "DROP TABLE IF EXISTS `" . $table_prefix . "documents_signer_field_data`";
$wpdb->query($sql);
