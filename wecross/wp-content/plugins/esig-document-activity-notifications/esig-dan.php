<?php
/**
 * @package   	      WP E-Signature - Document Activity Notifications
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Document Activity Notifications
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       This add-on sends document activity email notifications every time your signer has viewed a document sent for signature (even if they haven't signed it).
 * Version:           1.1.5
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-dan
 * Domain Path:       /languages
 * License/Terms & Conditions: http://www.approveme.me/terms-conditions/
 * Privacy Policy: http://www.approveme.me/privacy-policy/
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if(class_exists( 'WP_E_Addon' ))
{
	$esign_addons= new WP_E_Addon();
	$esign_addons->esign_update_check('3544','1.1.5');
}   

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-dan.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_DAN', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_DAN', 'deactivate' ) );


//if (is_admin()) {
     
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-dan-admin.php' );
	add_action( 'plugins_loaded', array( 'ESIG_DAN_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_dan_load_textdomain() {
    
  load_plugin_textdomain('esig-dan', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_dan_load_textdomain');

