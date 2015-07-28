<?php
/**
 * @package   	      WP E-Signature Stand Alone Documents
 * @contributors	  Kevin Michael Gray (Approve Me), Michael Medaglia (Approve Me), Abu Shoaib(Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Stand Alone Documents
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       Allows you to create stand alone documents which anyone can sign.
 * Version:           1.1.7
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-sad
 * Domain Path:       /languages
 * License/Terms & Conditions: http://www.approveme.me/terms-conditions/
 * Privacy Policy: http://www.approveme.me/privacy-policy/
 */
 
// Copyright 2013 Approve Me (http://www.approveme.me)

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if(class_exists( 'WP_E_Addon' ))
{
	$esign_addons= new WP_E_Addon();
	$esign_addons->esign_update_check('63','1.1.7');
}


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/esig-sad.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'ESIG_SAD', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_SAD', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'ESIG_SAD', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if (is_admin()) {
     
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-sad-admin.php' );
	add_action( 'plugins_loaded', array( 'ESIG_SAD_Admin', 'get_instance' ) );

}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_sad_load_textdomain() {
    
  load_plugin_textdomain('esig-sad', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_sad_load_textdomain');
