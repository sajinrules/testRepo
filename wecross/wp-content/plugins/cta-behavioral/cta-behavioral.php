<?php
/*
Plugin Name: Calls to Action - Behavioral Targeting
Plugin URI: http://www.inboundnow.com/landing-pages/downloads/template-customizer/
Description: Personalize CTAs based on the list visitors belong to and more. 
Version: 2.0.2
Author: InboundNow
Author URI: http://www.inboudnow.com/
*/

//checks to make sure landing page plugin is active


if ( !class_exists('CTA_Behavioral_Plugin') ) {

	class CTA_Behavioral_Plugin {

		public function __construct() {
		
			/* adds licensing */
			add_action('admin_init', array( __CLASS__ , 'add_licensing' ) );
			
			/* adds inbound pro settings */
			add_action( 'inbound_settings/extend' , array( __CLASS__ , 'add_pro_settings' ) , 10 , 1);

			/* define constants */
			define( 'CTA_BT_URLPATH' , plugins_url( ' ', __FILE__ ) );
			define( 'CTA_BT_API', 'https://www.inboundnow.com' );
			define( 'CTA_BT_LABEL', 'Calls to Action - Behavioral Targeting' );
			define( 'CTA_BT_REMOTE_ITEM_NAME' , 'behavioral-calls-to-action' );
			define( 'CTA_BT_SLUG', basename(dirname(__FILE__) )); 
			define( 'CTA_BT_PATH', plugin_dir_path( __FILE__ ) );
			define( 'CTA_BT_FILE', __FILE__ );
			define( 'CTA_BT_VERSION_NUMBER', '2.0.2' );


			/* load core files */
			switch (is_admin()) :
				case true :
					/* loads admin files */
					include_once('classes/class.metaboxes.php');
					include_once('classes/class.behavioral-ctas.php');

					BREAK;

				case false :
					/* load front-end files */					
					include_once('classes/class.behavioral-ctas.php');
					
					BREAK;
			endswitch;
		}
		
		/**
		*  Add stand alone licensing
		*/
		public static function add_licensing() {
			
			/* ignore these hooks if inbound pro is active */
			if (defined('INBOUND_PRO_CURRENT_VERSION')) {
				return $global_settings;
			}
	
			if ( class_exists( 'Inbound_License' ) ) { 
				$license = new Inbound_License( CTA_BT_FILE , CTA_BT_LABEL , CTA_BT_SLUG , CTA_BT_VERSION_NUMBER  , CTA_BT_REMOTE_ITEM_NAME ) ;
			}
	
		}
		
		/**
		*  Add inbound pro settings references
		*/
		public static function add_pro_settings($settings) {

			$settings['inbound-pro-settings'][] = array(
				'group_name' => CTA_BT_SLUG ,					
				'keywords' => __('cta,calls to action,behavioral,lists' , 'inbound-pro'),
				'fields' => array (						
					array(
						'id'  => 'header_cta_behavioral',			
						'type'  => 'header', 
						'default'  => __('Behavioral Calls to Action', 'inbound-pro' ),
						'options' => null
					),					
					array(
						'id'  => 'cta_behavioral_documentations',
						'type'  => 'ol', 
						'label' => __( 'Documentation:' , 'inbound-pro' ),
						'options' => array(
							'<a href="http://docs.inboundnow.com/guide/how-to-place-calls-to-action/#toc-8" target="_blank">'.__( 'Call to action placement guide' , 'inbound-pro' ).'</a>'
						)					
					)
				)
				
			);
			
			return $settings;

		}
	}
	
	$GLOBALS['CTA_Behavioral_Plugin'] = new CTA_Behavioral_Plugin;
	
}
?>