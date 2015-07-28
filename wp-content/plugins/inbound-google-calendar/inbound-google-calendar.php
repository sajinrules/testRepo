<?php
/*
Plugin Name: Inbound Extension - Google Calendar Integration
Plugin URI: http://www.inboundnow.com/market/support-will-complete
Description: Provides administrator ways to quickly schedule reminders to contact leads.
Version: 2.0.2
Author: Inbound Now
Author URI: http://www.inboundnow.com/
Text Domain: inboundnow-zapier
Domain Path: lang
*/


if (!class_exists('Inbound_Google_Calendars')) {

	class Inbound_Google_Calendars {

		/**
		*  Initialize class
		*/
		public function __construct() {
			self::define_constants();
			self::load_files();
		}

		/**
		*  Define constants
		*/
		public static function define_constants() {
			/* Define constants */
			define('INBOUND_GOOGLE_CALENDARS_CURRENT_VERSION', '1.0.1' );
			define('INBOUND_GOOGLE_CALENDARS_LABEL' , 'Google Calanader Integration' );
			define('INBOUND_GOOGLE_CALENDARS_FILE' , __FILE__ );
			define('INBOUND_GOOGLE_CALENDARS_SLUG' , plugin_basename( basename(__DIR__) ));
			define('INBOUND_GOOGLE_CALENDARS_TEXT_DOMAIN' , plugin_basename( dirname(__FILE__) ) );
			define('INBOUND_GOOGLE_CALENDARS_REMOTE_ITEM_NAME' , 'goole-calendar' );
			define('INBOUND_GOOGLE_CALENDARS_URLPATH', WP_PLUGIN_URL.'/'.plugin_basename( dirname(__FILE__) ).'/' );
			define('INBOUND_GOOGLE_CALENDARS_PATH', WP_PLUGIN_DIR.'/'.plugin_basename( dirname(__FILE__) ).'/' );
		}


        /**
         * load files
         *
         */
         public static function load_files() {
            if ( is_admin() ) {
                include INBOUND_GOOGLE_CALENDARS_PATH .'classes/class.calendar.php';
                include INBOUND_GOOGLE_CALENDARS_PATH .'classes/class.settings.php';
                include INBOUND_GOOGLE_CALENDARS_PATH .'classes/class.user-profile.php';
                include INBOUND_GOOGLE_CALENDARS_PATH .'classes/class.leads-profile.php';
            } else {

            }
         }




	}


	new Inbound_Google_Calendars;


}