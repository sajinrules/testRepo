<?php
/*
Plugin Name: Inbound Extension - MailChimp Integration
Plugin URI: http://www.inboundnow.com/market/
Description: Provides MailChimp support for Landing Pages, Leads, and Calls to Action plugin.
Version: 2.0.1
Author: Inbound Now
Author URI: http://www.inboundnow.com/
Text Domain: inboundnow-mailchimp
Domain Path: lang
*/


if (!class_exists('Inbound_MailChimp')) {

	class Inbound_MailChimp {

		/**
		*  Initialize Class
		*/
		public function __construct() {
			self::define_constants();
			self::load_files();
		}

		/**
		*  Define constants
		*/
		public static function define_constants() {
			define('INBOUNDNOW_MAILCHIMP_CURRENT_VERSION' , '2.0.1' );
			define('INBOUNDNOW_MAILCHIMP_LABEL' , __( 'MailChimp Integration' , 'inbound-pro' ) );
			define('INBOUNDNOW_MAILCHIMP_FILE' , __FILE__ );
			define('INBOUNDNOW_MAILCHIMP_SLUG' , plugin_basename( dirname(__FILE__) ) );
			define('INBOUNDNOW_MAILCHIMP_TEXT_DOMAIN' , plugin_basename( dirname(__FILE__) ) );
			define('INBOUNDNOW_MAILCHIMP_REMOTE_ITEM_NAME' , 'mailchimp-integration' );
			define('INBOUNDNOW_MAILCHIMP_URLPATH' , WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__) ).'/' );
			define('INBOUNDNOW_MAILCHIMP_PATH' , realpath(dirname(__FILE__) ).'/');

		}

		/**
		*  Load Files
		*/
		public static function load_files() {

			/* load core files */
			switch (is_admin()) :
				case true :
					/* loads admin files */
					include_once('classes/class.inbound-pro.php');
					include_once('modules/module.extension-setup.php');
					include_once('modules/module.global-settings.php');
					include_once('modules/module.form-settings.php');
					include_once('modules/module.metaboxes.php');
					include_once('modules/module.bulk-export.php');
					include_once('modules/module.subscribe.php');

					if (!class_exists('MailChimp'))
						include_once('includes/mailchimp-api-master/MailChimp.class.php');

					BREAK;
				case false :
					/* loads frontend files */
					include_once('classes/class.inbound-pro.php');
					include_once('modules/module.subscribe.php');

					if (!class_exists('MailChimp')) {
						include_once('includes/mailchimp-api-master/MailChimp.class.php');
					}

					BREAK;
			endswitch;
		}

	}

	new Inbound_MailChimp;
}

