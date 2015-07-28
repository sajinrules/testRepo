<?php
/**
 * @package   	      WP E-Signature Signing Reminders
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 *
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Signing Reminders
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       This automation add-on sends signing reminder emails to your signers if they have not signed your agreement in the timeframe you define. You can set it to expire after a specific number of days. 
 * Version:           1.1.5
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-reminders
 * Domain Path:       /languages
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


if(class_exists( 'WP_E_Addon' ))
{
	$esign_addons= new WP_E_Addon();
	$esign_addons->esign_update_check('4326','1.1.5');
}   

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-reminders.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_REMINDERS', 'activate' ) );
// setting reminder schedule event . 
register_activation_hook( __FILE__,array('ESIG_REMINDERS_Admin','esig_reminders_schedule_activation') );
register_deactivation_hook( __FILE__, array( 'ESIG_REMINDERS', 'deactivate' ) );
// removing reinder schedule event . 
register_deactivation_hook( __FILE__, array('ESIG_REMINDERS_Admin','esig_reminders_schedule_deactivation') );


//if (is_admin()) {
     
require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-reminders-admin.php' );
add_action( 'plugins_loaded', array( 'ESIG_REMINDERS_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_reminders_load_textdomain() {
    
  load_plugin_textdomain('esig-reminders', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_reminders_load_textdomain');
