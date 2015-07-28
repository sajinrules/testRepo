<?php


if (!class_exists('Inbound_Google_Calendar_Connect')) {

    class Inbound_Google_Calendar_Connect {

        static $client;

        /**
         * Get data related to user
         * @return mixed
         */
        public static function get_access_tokens() {
            $user = wp_get_current_user();
            $tokens['access_json'] = trim(get_the_author_meta( 'google_calendar_access_token_json', $user->ID ));
            $tokens['access'] = trim(get_the_author_meta( 'google_calendar_access_token', $user->ID ));
            $tokens['refresh'] = trim( get_the_author_meta( 'google_calendar_refresh_token', $user->ID ));
            $tokens['client_id'] = trim(get_the_author_meta( 'google_calendar_client_id', $user->ID ));
            $tokens['client_secret'] = trim(get_the_author_meta( 'google_calendar_client_secret', $user->ID ));
            $tokens['redirect_uri'] = trim(admin_url('profile.php?inbound-action=save_gc_keys'));
            $tokens['user_id'] = $user->ID;
            return $tokens;
        }

        /**
         * Setup the Google Client
         * @return Google_Client
         */
        public static function get_client() {
            include_once INBOUND_GOOGLE_CALENDARS_PATH .  'assets/libraries/google-api-php-client-master/src/Google/autoload.php';

            $tokens = Inbound_Google_Calendar_Connect::get_access_tokens();

            self::$client = new Google_Client();
            self::$client->setApplicationName("Google Calendar PHP Starter Application");
            self::$client->setScopes(array(
                'https://www.googleapis.com/auth/calendar',
                'https://www.googleapis.com/auth/calendar.readonly',
            ));
            self::$client->setClientId($tokens['client_id']);
            self::$client->setClientSecret($tokens['client_secret']);
            self::$client->setRedirectUri($tokens['redirect_uri']);
            self::$client->setAccessType('offline');
            self::$client->setApprovalPrompt('force');

            /* check if access token exists and set it or reset it if expired */
           Inbound_Google_Calendar_Connect::check_token();

        }


        /**
         * Get oauth URL
         */
        public static function get_oauth_url() {
            Inbound_Google_Calendar_Connect::get_client();
            return self::$client->createAuthUrl();
        }


        /**
         * Get access token from oauth code
         */
         public static function get_access_token( $code ) {
             Inbound_Google_Calendar_Connect::get_client();

             /* get access tokens from auth code */
             return self::$client->authenticate( $code );

         }

        /**
         * Checks if access token is expired and renews if neccecary
         * @param self::$client
         * @return mixed
         */
        public static function check_token() {
            $tokens = Inbound_Google_Calendar_Connect::get_access_tokens();

            if (empty($tokens['access_json'])) {
                return;
            }

            self::$client->setAccessToken( $tokens['access_json'] );

            if(self::$client->isAccessTokenExpired()) {
                self::$client->refreshToken( $tokens['refresh'] );
                $new_token = self::$client->getAccessToken();
                self::$client->setAccessToken( $new_token );
                update_user_meta( $tokens['user_id']  , 'google_calendar_access_token', $new_token );
            }

        }

        /**
         * Gets list of calendars
         * @return array
         */
        public static function get_calendar_lists() {

            $calendars = array();

            Inbound_Google_Calendar_Connect::get_client();

            $service = new Google_Service_Calendar(self::$client);
            $calendarList = $service->calendarList->listCalendarList();

            while(true) {

                foreach ($calendarList->getItems() as $calendarListEntry) {
                    $calendars[ $calendarListEntry->getId()]['id'] = $calendarListEntry->getId();
                    $calendars[ $calendarListEntry->getId()]['summary'] = $calendarListEntry->getSummary();
                }

                $pageToken = $calendarList->getNextPageToken();

                if ($pageToken) {
                    $optParams = array('pageToken' => $pageToken);
                    $calendarList = $service->calendarList->listCalendarList($optParams);
                } else {
                    break;
                }
            }

            return $calendars;

        }

        /**
         * Get information given calendar id
         */
        public static function get_calendar( $calendar_id ) {

            Inbound_Google_Calendar_Connect::get_client();

            $service = new Google_Service_Calendar( self::$client );
            $calendar = $service->calendars->get( $calendar_id );

            return (array) $calendar;
        }

        /**
         * Create event using quick add method
         */
        public static function quick_add_event( $args ) {
            Inbound_Google_Calendar_Connect::get_client();

            $service = new Google_Service_Calendar( self::$client );

            $createdEvent = $service->events->quickAdd(
                $args['id'],
                $args['query'],
                array(
                    'sendNotifications' => $args['notify']
                )
            );

            echo $createdEvent->getId();exit;
        }
    }

    new Inbound_Google_Calendar_Connect;

}
