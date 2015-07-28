<?php

add_action('admin_init', 'inboundnow_dynamickeywords_extension_setup');

function inboundnow_dynamickeywords_extension_setup()
{
	/*PREPARE THIS EXTENSION FOR LICESNING*/
	if ( class_exists( 'INBOUNDNOW_EXTENSION_LICENSE' ) )   
		$license = new INBOUNDNOW_EXTENSION_LICENSE(  INBOUNDNOW_DYNAMICKEYWORDS_LABEL , INBOUNDNOW_DYNAMICKEYWORDS_SLUG ) ;
	 
	/*PREPARE THIS EXTENSION FOR AUTOMATIC UPDATES*/
	if ( class_exists( 'INBOUNDNOW_EXTENSION_UPDATER' ) )
	{   
		$edd_updater = new INBOUNDNOW_EXTENSION_UPDATER( INBOUNDNOW_STORE_URL, __FILE__, array( 
			'version'   => INBOUNDNOW_DYNAMICKEYWORDS_CURRENT_VERSION, /* current version number of extension */
			'license'   => trim(get_option( 'inboundnow-license-keys-'.INBOUNDNOW_DYNAMICKEYWORDS_SLUG )), /* to retrieve license keys we use the following as the option id: 'lp-license-key-' + 'id' as defined above in lp_add_option() above. You can leave this alone.*/
			'item_name' => INBOUNDNOW_DYNAMICKEYWORDS_SLUG, /* permalink name of this extension on inboundnow.com/landing-pages/ store. Leave this line alone */
			'nature'    => 'extension'  /* nature of update request. leave this line alone */
		));
	}
}