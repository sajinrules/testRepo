<?php
/**
 * @package   	      WP E-Signature - Unlimited Sender Roles
 * @contributors      Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me) <abushoaib73@gmail.com>
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Unlimited Sender Roles
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       Most document signing companies charge $10, $15, even $30 per month… PER USER that can send documents! You get unlimited users (and no monthly fees) with this powerful add-on.
 * Version:           1.1.6
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-usr
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
	$esign_addons->esign_update_check('4330','1.1.6');
}   


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-usr.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */

register_activation_hook( __FILE__, array( 'ESIG_USR', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_USR', 'deactivate' ) );


//if (is_admin()) {

require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-usr-admin.php' );
add_action( 'plugins_loaded', array( 'ESIG_USR_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_usr_load_textdomain() {
    
  load_plugin_textdomain('esig-usr', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_usr_load_textdomain');
