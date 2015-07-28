<?php
/**
 * @package   	      WP E-Signature - Attach PDF to Email
 * @contributors      Kevin Michael Gray (Approve Me), Abu Shoaib (Approve Me)
 * @wordpress-plugin
 * Plugin Name:       WP E-Signature - Attach PDF to Email
 * Plugin URI:        http://approveme.me/wp-digital-e-signature
 * Description:       Automatically attach a PDF of the completed document to email that gets sent to all signing parties.  
 * Version:           1.1.6
 * Author:            Approve Me
 * Author URI:        http://approveme.me/
 * Text Domain:       esig-pdfemail
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
	$esign_addons->esign_update_check('6170','1.1.6');
}   


/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'includes/esig-pdf-to-email.php');


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
 
register_activation_hook( __FILE__, array( 'ESIG_PDF_TO_EMAIL', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ESIG_PDF_TO_EMAIL', 'deactivate' ) );


//if (is_admin()) {
     
require_once( plugin_dir_path( __FILE__ ) . 'admin/esig-pdf-to-email-admin.php' );
add_action( 'plugins_loaded', array( 'ESIG_PDF_TO_EMAIL_Admin', 'get_instance' ) );

//}

/**
 * Load plugin textdomain.
 *
 * @since 1.1.3
 */
function esig_pdfemail_load_textdomain() {
    
  load_plugin_textdomain('esig-pdfemail', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
}
add_action( 'plugins_loaded', 'esig_pdfemail_load_textdomain' );

