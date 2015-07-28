<?php
/**
 * @package   	      WP E-Signature Signer Input Fields
 * @author	      Kevin Michael Gray (Approve Me), Michael Medaglia (Approve Me), Abu Shoaib(Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Signer Input Fields
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       This add-on makes it easy to add "initial here" text fields, address information, radio boxes, checkboxes, calendar dates or just about anything else on your documents.
 * Version:           1.1.6
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-sif
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
	$esign_addons->esign_update_check('64','1.1.6');
}   


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/esig-sif.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( __FILE__, array( 'ESIG_SIF', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_SIF', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'ESIG_SIF', 'get_instance' ) );

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
if ( is_admin() ) {

		require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-sif-admin.php' );
		add_action( 'plugins_loaded', array( 'ESIG_SIF_Admin', 'get_instance' ) );

}
/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_sif_load_textdomain() {
    
  load_plugin_textdomain('esig-sif', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_sif_load_textdomain');


