<?php
 
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   	      WP E-Signature Stand Alone Documents
 * @contributors	  Kevin Michael Gray (Approve Me), Michael Medaglia (Approve Me)
 *
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-stand-alone-documents
 */ 


// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$setting_table=$table_prefix . "settings";
$esign_remove_all_data = $wpdb->get_var("SELECT setting_value FROM $setting_table where setting_name='esign_remove_all_data'" );

if($esign_remove_all_data==1)
		 {

$table_prefix = $wpdb->prefix . "esign_";
$sql = "DROP TABLE IF EXISTS `" . $table_prefix . "documents_stand_alone_docs`";
$wpdb->query($sql);
}
