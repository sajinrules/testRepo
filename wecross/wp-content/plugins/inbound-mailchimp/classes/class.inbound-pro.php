<?php

/**
*  Define class
*/
class MailChimp_InboundPro {

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
			'group_name' => INBOUNDNOW_MAILCHIMP_SLUG ,
			'keywords' => __('mailchimp' , 'inbound-pro'),
			'fields' => array (
				array(
					'id'  => 'header_mailchimp',
					'type'  => 'header',
					'default'  => __('MailChimp', 'inbound-pro' ),
					'options' => null
				),
				array(
					'id'  => 'mailchimp_api_key',
					'type'  => 'text',
					'label'  => __( 'MailChimp API Key', 'inbound-pro' ),
					'description'  => __( 'Input your MailChimp API Key Here' , 'inbound-pro' ),
					'options' => null
				),
				array(
					'id'  => 'mailchimp_double_optin',
					'type'  => 'radio',
					'label'  => __( 'Enable Double Optin?', 'inbound-pro' ),
					'description'  => __( 'Disabling double optin will prevent registrants from having to confirm their subscription status via email.' , 'inbound-pro' ),
					'default' => 'false',
					'options' => array(
						'false' => __( 'off' , 'inbound-pro' ), 
						'true' => __( 'on' , 'inbound-pro' )
					)
				),
				array(
					'id'  => 'mailchimp_instructions',
					'type'  => 'ol',
					'label' => __( 'Instructions:' , 'inbound-pro' ),
					'options' => array(
						sprintf( __( 'Discover your API key over at %s. ' , 'inbound-pro' ), '<a href="https://login.mailchimp.com/?referrer=%2Faccount%2Fapi-key-popup%2F" target="_blank">https://login.mailchimp.com/?referrer=%2Faccount%2Fapi-key-popup%2F</a>'),
						sprintf( __( 'Additional extension documentation can be found at %s. ' , 'inbound-pro' ), '<a href="http://docs.inboundnow.com/section/mailchimp-integration/" target="_blank">'. __( 'Inbound MailChimp Docs' , 'inbound-pro' ) .'</a>'),
						__( 'Once an API Key is inputted then MailChimp integration can be enabled at the Inboud Form setup.' , 'inbound-pro' )
					)
				)
			)

		);


		return $settings;

	}

	/**
	*  Get keys
	*/
	public static function get_keys() {
		if (!defined('INBOUND_PRO_CURRENT_VERSION')) {
			$keys['api_key'] = get_option( 'inboundnow_mailchimp_api_key' , 0 );	
			$keys['double_optin'] =  get_option('inboundnow_mailchimp_double_optin' , 'true' );
		} else {
		
			$settings = Inbound_Options_API::get_option( 'inbound-pro' , 'settings' , array() );
			$keys['api_key'] =  $settings[ INBOUNDNOW_MAILCHIMP_SLUG ][ 'mailchimp_api_key' ];
			$keys['double_optin'] =  $settings[ INBOUNDNOW_MAILCHIMP_SLUG ][ 'mailchimp_double_optin' ];
			
		}

		return $keys;
	}

}


new MailChimp_InboundPro;