<?php


if (!class_exists('Inbound_Google_Calendars_Leads')) {

    class Inbound_Google_Calendars_Leads {

        static $calendars;
        static $calendar;
        static $pre_select;
        static $tokens;

        /**
         *  Initialize class
         */
        public function __construct() {


            self::load_hooks();
        }

        /**
         *  Loads hooks and filters
         */
        private function load_hooks() {

            /* enqueue scripts and css */
            add_filter('admin_enqueue_scripts', array( __CLASS__ , 'enqueue_scripts' ) , 10 );

            /* add nav tabs */
            add_filter('wpl_lead_tabs', array( __CLASS__ , 'create_nav_tabs' ) , 10, 1);

            /* add nav tab content */
            add_action( 'wpl_print_lead_tab_sections' , array( __CLASS__ , 'add_content_container' ) );

            /* save user selection */
            add_action('wp_ajax_inbound_get_calendar' , array( __CLASS__ , 'ajax_get_calendar' ) );

            /* save user selection */
            add_action('wp_ajax_inbound_save_calendar_selection' , array( __CLASS__ , 'ajax_save_calendar_selection' ) );

            /* save user selection */
            add_action('wp_ajax_inbound_create_calendar_event' , array( __CLASS__ , 'ajax_create_calendar_event' ) );

        }

        /**
         * Enqueue scripts
         */
        public static function enqueue_scripts() {
            global $post;

            if (!isset($post) || $post->post_type !='wp-lead' ) {
                return;
            }

            /* load sweetalert */
            wp_enqueue_script('sweetalert', INBOUND_GOOGLE_CALENDARS_URLPATH . 'assets/libraries/sweetalert-master/dist/sweetalert.min.js');
            wp_enqueue_style('sweetalert', INBOUND_GOOGLE_CALENDARS_URLPATH . 'assets/libraries/sweetalert-master/dist/sweetalert.css');

            /* load custom js */
            wp_enqueue_script('inbound-google-calendar', INBOUND_GOOGLE_CALENDARS_URLPATH . 'assets/js/lead-profile.js' );

            /* load custom css */
            wp_enqueue_style('inbound-google-calendar', INBOUND_GOOGLE_CALENDARS_URLPATH . 'assets/css/lead-profile.css' );

            /* load fontawesome */
            wp_enqueue_style('fontawesome', INBOUND_GOOGLE_CALENDARS_URLPATH . 'assets/libraries/FontAwesome/css/font-awesome.min.css');


        }

        /**
         * Create New Nav Tabs in WordPress Leads - Lead UI
         */
        public static function create_nav_tabs( $nav_items ) {
            global $post;


            /* Add attachments tab */
            $nav_items[] = array(
                'id'=> 'wpleads_lead_tab_calendar',
                'label'=> __( 'Schedule' , 'inbound-pro' )
            );


            return $nav_items;
        }

        /**
         *  Prints container content
         */
        public static function add_content_container() {

            global $post;

            self::$tokens = Inbound_Google_Calendar_Connect::get_access_tokens();

            if (empty(self::$tokens['access']) ) {
                self::prompt_authorization();
            } else {
                self::display_ui();
            }

        }

        /**
         *
         */
        public static function prompt_authorization() {
            ?>
            <div class="lead-profile-section" id="wpleads_lead_tab_calendar" >
                <div class='authorize-prompt'>
                    <center>
                        <img src='<?php echo INBOUND_GOOGLE_CALENDARS_URLPATH. 'assets/img/google-calendar-logo.gif'; ?>'>

                        <h4><?php _e('No calendar is authorized for logged in user' , 'inbound-pro' ); ?></h4>
                        <p class='authorize-message'>
                            <?php echo sprintf( __('Please head into your %s WordPress User profile edit screen %s to authorize a Google Calendar account' , 'inbound-pro')  , '<br>', '<a href="'.admin_url('profile.php#google-calendar').'">' , '</a>' ); ?>
                        </p>
                </div>
            </div>
        <?php
        }

        /**
         *
         */
        public static function display_ui() {

            ?>
            <div class="lead-profile-section" id="wpleads_lead_tab_calendar" >
                <?php
                self::display_calendar_select();
                self::display_add_event();
                self::display_calendar();
                ?>
            </div>
        <?php
        }

        /**
         *
         */
        public static function  display_calendar_select() {
            self::$calendars = Inbound_Google_Calendar_Connect::get_calendar_lists();

            $user = wp_get_current_user();
            self::$pre_select = self::get_remembered_calendar_selection();

            ?>
            <div class="calendar-selector">
                <label>Select Calendar</label>
                <select id='google-calendars'>
                    <?php
                    foreach (self::$calendars as $calendar) {
                        echo '<option value="'. $calendar['id'].'" '.( $calendar['id'] == self::$pre_select ? 'selected="selected"' : '' ) .'>'. $calendar['summary'].'</option>';
                    }
                    ?>
                </select>
            </div>
        <?php
        }


        /**
         *
         */
        public static function display_calendar() {
            ?>
            <iframe id="calendar-display" src="" style="border: 0" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
        <?php
        }

        /**
         * Display add event link
         */
        public static function  display_add_event() {
            ?>

            <div class='add-calendar-event'>
                <input type='text' class='quick-add-event' id='event-query' value='<?php echo self::display_default_event_text(); ?>'>
            </div>
            <div class='notify-me-of-event'>
                <span class="notify-me">
                    <label class='notify-label' title="<?php _e( 'Notification settings are controlled from your Google Calendar Settings area.' , 'inbound-pro' ); ?>"><?php _e('Enable notifications for this event?' , 'inbound-pro' ); ?>
                        <input type='checkbox' id='notify_me' value='on' checked='checked'>
                    </label>
                </span>
            </div>
            <div class='add_new_event'>
                <span class="button button-primary" id='add_new_event'><?php _e('Set Event','inbound-pro'); ?></span>

                <span class="help">
                    <?php add_thickbox(); ?>
                    <a href="https://support.google.com/calendar/answer/36604" class="" target='_blank'>
                        <i class='fa fa-question-circle' title='<?php _e( 'Click here to find out more information about how to add events' , 'inbound-pro' ); ?>'></i>
                    </a>
                </span>
            </div>
        <?php

        }

        /**
         * template
         */
        public static function display_default_event_text() {
            global $post;
            $future =  date("F jS Y", strtotime( date('F jS Y') . " +15 day" ));
            return apply_filters( 'inbound_google_calendar_quickEvent_default_text' , sprintf( __( 'Followup with %s %s' , 'inbound-pro' ) , $post->post_title , $future ) );
        }

        /**
         * get user selected calendar
         */
        public static function get_remembered_calendar_selection() {
            $user = wp_get_current_user();
            $memory = get_user_meta( $user->ID  , 'google_calendar_selection' , true );

            return ($memory) ? $memory : null;
        }

        /**
         * ajax listeners that saves a calendar preselect
         */
        public static function ajax_save_calendar_selection() {
            if (!isset($_REQUEST['id'])) {
                echo 'nothing selected';
                exit;
            }
            $user = wp_get_current_user();
            update_user_meta( $user->ID  , 'google_calendar_selection', $_REQUEST['id']  );

        }

        /**
         * ajax listeners that saves a calendar preselect
         */
        public static function ajax_get_calendar() {
            if (!isset($_REQUEST['id'])) {
                echo 'no calendar selected';
                exit;
            }

            self::$calendar = Inbound_Google_Calendar_Connect::get_calendar( $_REQUEST['id'] );

            echo json_encode( self::$calendar );
            exit;

        }


        /**
         * ajax listeners that creates a calendar event
         */
        public static function ajax_create_calendar_event() {
            if (empty($_REQUEST['query'])) {
                echo 0;
                exit;
            }

            $response = Inbound_Google_Calendar_Connect::quick_add_event( $_REQUEST );

            echo json_encode( $response );
            exit;

        }


    }


    new Inbound_Google_Calendars_Leads;


}