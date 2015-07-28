<?php


if (!class_exists('Inbound_Google_Calendars_Profiles')) {

    class Inbound_Google_Calendars_Profiles {

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

            /* Add oauth fields */
            add_action( 'show_user_profile', array( __CLASS__ , 'extra_user_profile_fields' ) , 10 );
            add_action( 'edit_user_profile', array( __CLASS__ , 'extra_user_profile_fields' ) , 10 );

            /* process oauth fields */
            add_action( 'personal_options_update', array( __CLASS__ , 'save_extra_user_profile_fields' ) );
            add_action( 'edit_user_profile_update', array( __CLASS__ , 'save_extra_user_profile_fields' ) );

            /* store authentication */
            add_action( 'admin_init', array( __CLASS__ , 'store_access_tokens' ) );
        }

        public static function extra_user_profile_fields( $user ) {
            $tokens = Inbound_Google_Calendar_Connect::get_access_tokens();

            if (empty($tokens['access'])) {
            ?>
                <a name='google-calendar'></a>
                <h3><?php _e("Google Calandar Authentication", 'inbound-pro'); ?></h3>
                <div id='installation-docs'>
                    <ol>
                        <li><?php echo sprintf(  __( 'Go to the %sDeveloper\'s Console%s.' , 'inbound-pro') , '<a href="https://console.developers.google.com/project" target="_blank">' , '</a>'); ?></li>
                        <li><?php _e( 'Select a project, or create a new one.' , 'inbound-pro' ); ?></li>
                        <li><?php _e( 'In the sidebar on the left, expand APIs & auth. Next, click APIs. Set the status to ON for any APIs you are using that appear in the list. Note that Google APIs Client Library for PHP is not listed because it does not need to be activated.' , 'inbound-pro' ); ?></li>
                        <li><?php _e( 'In the sidebar on the left, select Credentials.' , 'inbound-pro' ); ?></li>
                        <li><?php echo sprintf( __(  'Click create new client id and make sure to set your redirect URI to %s. ' , 'inbound-pro' )  , $tokens['redirect_uri'] ); ?></li>
                    </ol>


                </div>
                <table class="form-table">


                <tr>
                    <th><label for="google_calendar_client_id"><?php _e( 'Client ID' , 'inbound-pro'); ?></label></th>
                    <td>
                        <input type="text" name="google_calendar_client_id" id="google_calendar_client_id" value="<?php echo $tokens['client_id']; ?>" class="regular-text" /><br />
                        <span class="description"><?php _e("Please enter your google_calendar_client_id."); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><label for="google_calendar_client_secret"><?php _e('Client secret' , 'inbound-pro'); ?></label></th>
                    <td>
                        <input type="text" name="google_calendar_client_secret" id="google_calendar_client_secret" value="<?php echo $tokens['client_secret']; ?>" class="regular-text" /><br />
                        <span class="description"><?php _e("Please enter your google_calendar_client_secret.", 'inbound-pro'); ?></span>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Authorize' , 'inbound-pro' ); ?></th>
                    <td>
                        <input type="submit" name="google_calendar_authorize" id="google_calendar_authorize" value="<?php _e( 'Authorize' , 'inbound-pro' ); ?>" class="button button-primary primary" /><br />

                    </td>
                </tr>
                </table>
            <?php
            } else {
            ?>
                <a name='google-calendar'></a>
                <h3><?php _e("Google Calandar Authentication", 'inbound-pro'); ?></h3>
                <table class="form-table">
                <tr>
                    <th><?php _e( 'De-Authorize' , 'inbound-pro' ); ?></th>
                    <td>
                        <input type="submit" name="google_calendar_deauthorize" id="google_calendar_deauthorize" value="<?php _e( 'Deauthorize' , 'inbound-pro' ); ?>" class="button button-primary primary" /><br />

                    </td>
                </tr>
                </table>
            <?php
            }
            ?>

            <?php
        }

        public static function save_extra_user_profile_fields( $user_id ) {
            if (isset($_POST['google_calendar_client_id'])) {
                update_user_meta( $user_id, 'google_calendar_client_id', $_POST['google_calendar_client_id'] );
            }
            if (isset($_POST['google_calendar_client_secret'])) {
                update_user_meta( $user_id, 'google_calendar_client_secret', $_POST['google_calendar_client_secret'] );
            }
            /* return if fields are empty */
            if ( $_POST['google_calendar_authorize'] ) {
                self::start_authorization();
            }
             /* return if fields are empty */
            if ( $_POST['google_calendar_deauthorize'] ) {
                self::start_deauthorization();
            }



        }

        /**
         * redirect to google calendar oauth
         */
        public static function start_authorization() {
            include INBOUND_GOOGLE_CALENDARS_PATH .  'assets/libraries/google-api-php-client-master/src/Google/autoload.php';
            $authUrl = Inbound_Google_Calendar_Connect::get_oauth_url();
            header('Location:'.$authUrl);
            exit;

        }

        /**
         * redirect to google calendar oauth
         */
        public static function start_deauthorization() {

            $tokens = Inbound_Google_Calendar_Connect::get_access_tokens();

            /* set access token into user meta table */
            delete_user_meta( $tokens['user_id'] , 'google_calendar_access_token_json' );
            delete_user_meta( $tokens['user_id'] , 'google_calendar_access_token' );
            delete_user_meta( $tokens['user_id'] , 'google_calendar_refresh_token' );

        }

        /**
         * Coverts google oauth code to access token
         */
        public static function store_access_tokens() {
            if (!isset($_GET['inbound-action']) || $_GET['inbound-action'] != 'save_gc_keys' ) {
                return;
            }


            $response = Inbound_Google_Calendar_Connect::get_access_token( $_GET['code'] );
            $token = json_decode( $response , true );

            if (isset($token['error'])) {
                print_r($token);exit;
            }

            /* set access token into user meta table */
            $user = wp_get_current_user();
            update_user_meta( $user->ID  , 'google_calendar_access_token_json', $response  );
            update_user_meta( $user->ID  , 'google_calendar_access_token', $token['access_token']  );
            update_user_meta( $user->ID , 'google_calendar_refresh_token', $token['refresh_token'] );

        }


    }


    new Inbound_Google_Calendars_Profiles;


}