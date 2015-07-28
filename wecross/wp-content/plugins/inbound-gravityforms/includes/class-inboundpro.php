<?php

/**
*  Define class
*/
class GravityForms_InboundPro {

	/**
	*  init class
	*/
	public function __construct() {
	
		/*  Add settings to inbound pro  */
		add_filter('inbound_settings/extend', array( __CLASS__  , 'define_pro_settings' ) );
		
	}
	
	/**
	*  Adds pro admin settings 
	*/
	public static function define_pro_settings( $settings ) {
		$settings['inbound-pro-settings'][] = array(
			'group_name' => INBOUNDNOW_GRAVITYFORMS_SLUG ,					
			'keywords' => __('gravityforms,gravity' , 'inbound-pro'),
			'fields' => array (						
				array(
					'id'  => 'header_gravityforms',			
					'type'  => 'header', 
					'default'  => __('Gravity Forms', 'inbound-pro' ),
					'options' => null
				),
				array(
					'id'  => 'gravityforms_instructions',
					'type'  => 'ol', 
					'label' => __( 'Instructions:' , 'inbound-pro' ),
					'options' => array(
						sprintf( __( 'Please see %s for use documentation' , 'inbound-pro' ), '<a href="http://docs.inboundnow.com/section/gravity-forms/" target="_blank">'. __('Gravity Forms Documentation' , 'inbound-pro' ) .'</a>' ),
					)					
				)
			)
			
		);

	
		return $settings;
	
	}

	


}


new GravityForms_InboundPro;