<?php
/**
 * @package   	      WP E-Signature Add Custom Message to Signature invite
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 *
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Add Custom Message
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       Add Custom Message to Signature invitation email .
 * Version:           1.1.1
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-acm
 * Domain Path:       /languages
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if(class_exists( 'WP_E_Addon' ))
{
	$esign_addons= new WP_E_Addon();
	$esign_addons->esign_update_check('7878','1.1.1');
}


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-add-custom-message.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_CUSTOM_MESSAGE', 'activate' ) );
// setting reminder schedule event . 

register_deactivation_hook( __FILE__, array( 'ESIG_CUSTOM_MESSAGE', 'deactivate' ) );



//if (is_admin()) {
     
require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-add-custom-message-admin.php' );
add_action( 'plugins_loaded', array( 'ESIG_CUSTOM_MESSAGE_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_custom_message_load_textdomain() {
    
  load_plugin_textdomain('esig-acm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_custom_message_load_textdomain');
