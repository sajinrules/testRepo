<?php


if (!class_exists('Inbound_Google_Calendars_Settings')) {

    class Inbound_Google_Calendars_Settings {

        /**
         *  Initialize class
         */
        public function __construct() {
            self::load_hooks();
        }


        /**
         *  Load hooks and filters
         */
        public static function load_hooks() {

            /* Setup Automatic Updating & Licensing */
            add_action('admin_init', array( __CLASS__ , 'license_setup') );

            /* Add non-pro settings page */
            add_filter('lp_define_global_settings', array(__CLASS__, 'add_non_pro_settings'));
            add_filter('wpleads_define_global_settings', array(__CLASS__, 'add_non_pro_settings'));
            add_filter('wp_cta_define_global_settings',array(__CLASS__, 'add_non_pro_settings'));

            /* Add settings to inbound pro */
            add_filter('inbound_settings/extend', array( __CLASS__  , 'add_pro_settings' ) );

            /* Add google oauth to user profile page */
        }



        /*
        * Setups Software Update API
        */
        public static function license_setup() {

            /* ignore these hooks if inbound pro is active */
            if (defined('INBOUND_PRO_CURRENT_VERSION')) {
                return;
            }

            /*PREPARE THIS EXTENSION FOR LICESNING*/
            if ( class_exists( 'Inbound_License' ) ) {
                $license = new Inbound_License( INBOUND_GOOGLE_CALENDARS_FILE , INBOUND_GOOGLE_CALENDARS_LABEL , INBOUND_GOOGLE_CALENDARS_SLUG , INBOUND_GOOGLE_CALENDARS_CURRENT_VERSION  , INBOUND_GOOGLE_CALENDARS_REMOTE_ITEM_NAME ) ;
            }
        }


        /**
         *  Legacy settings model
         */
        public static function add_non_pro_settings($global_settings) {

            /* ignore these hooks if inbound pro is active */
            if (defined('INBOUND_PRO_CURRENT_VERSION')) {
                return $global_settings;
            }

            switch (current_filter()) {
                case "lp_define_global_settings":
                    $tab_slug = 'lp-extensions';
                    break;
                case "wpleads_define_global_settings":
                    $tab_slug = 'wpleads-extensions';
                    break;
                case "wp_cta_define_global_settings":
                    $tab_slug = 'wp-cta-extensions';
                    break;
            }

            return $global_settings;
        }

        /**
         *  Add inbound pro settings references
         */
        public static function add_pro_settings($settings) {
            /**/
            $settings['inbound-pro-settings'][] = array(
                'group_name' => INBOUND_GOOGLE_CALENDARS_SLUG ,
                'keywords' => __('google calendars, notifications, settings' , 'inbound-pro'),
                'fields' => array (
                    array(
                        'id'  => 'header_google_calendar',
                        'type'  => 'header',
                        'default'  => __('Google Calendars', 'inbound-pro' ),
                        'options' => null
                    ),
                    array(
                        'id'  => 'google_calendar_documentations',
                        'type'  => 'ul',
                        'label' => __( 'Setup Guide' , 'inbound-pro' ),
                        'options' => array(
                            sprintf( __('Setup your connected Google Account here: %s also see for help.', 'inbound-pro'),	'<a href="" target="_blank"></a>' ),

                        )
                    )
                )

            );

            return $settings;

        }



    }


    new Inbound_Google_Calendars_Settings;


}