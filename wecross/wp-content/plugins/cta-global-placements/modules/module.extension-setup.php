<?php

add_action('admin_init', 'inboundnow_cta_placements_extension_setup');

function inboundnow_cta_placements_extension_setup()
{
/*PREPARE THIS EXTENSION FOR LICESNING*/
	if ( class_exists( 'INBOUNDNOW_EXTEND' ) ) { 
		$license = new INBOUNDNOW_EXTEND( CTA_PLACEMENTS_FILE , CTA_PLACEMENTS_LABEL , CTA_PLACEMENTS_SLUG , CTA_PLACEMENTS_CURRENT_VERSION  , CTA_PLACEMENTS_REMOTE_ITEM_NAME ) ;
	}
	
}