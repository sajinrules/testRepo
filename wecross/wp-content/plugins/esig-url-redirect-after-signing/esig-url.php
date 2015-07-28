<?php
/**
 * @package   	      WP E-Signature URL Redirect After Signing
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - URL Redirect After Signing
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       This add-on let's you redirect signers to a url of your choice after they successfully sign your agreement.
 * Version:           1.1.7
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-url
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
	$esign_addons->esign_update_check('65','1.1.7');
}   

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-url.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_URL', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_URL', 'deactivate' ) );


//if (is_admin()) {
     
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-url-admin.php' );
	add_action( 'plugins_loaded', array( 'ESIG_URL_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_url_load_textdomain() {
    
  load_plugin_textdomain('esig-url', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_url_load_textdomain' );
