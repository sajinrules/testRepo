<?php
/**
 * @package   	      WP E-Signature - Assign Signer Order
 * @contributors	  Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Assign Signer Order
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       Allows you to add Signer order . 
 * Version:           1.1.4
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-order
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
	$esign_addons->esign_update_check('7881','1.1.4');
}   



/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-assign-signer-order.php' );


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_ASSIGN_ORDER', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_ASSIGN_ORDER', 'deactivate' ) );


//if (is_admin()) {
     
	require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-assign-signer-order-admin.php' );
	add_action( 'plugins_loaded', array( 'ESIG_ASSIGN_ORDER_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_order_load_textdomain() {
    
  load_plugin_textdomain('esig-order', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_order_load_textdomain' );
