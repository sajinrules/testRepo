<?php

require um_social_login_path . 'providers/twitter/api/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

class UM_Social_Login_Twitter {

	function __construct() {
		
		add_action('init', array(&$this, 'load'));
		
		add_action('init', array(&$this, 'get_auth'));

	}

	/***
	***	@load
	***/
	function load() {

		$this->consumer_key = um_get_option('twitter_consumer_key');
		$this->consumer_secret = um_get_option('twitter_consumer_secret');
		$this->oauth_callback = get_bloginfo('url') . '/?provider=twitter';
		
	}

	/***
	***	@Get auth
	***/
	function get_auth() {
		global $um_social_login;
		
		if ( isset($_REQUEST['provider']) && $_REQUEST['provider'] == 'twitter' && isset($_REQUEST['oauth_token']) && isset($_REQUEST['oauth_verifier']) ) {
			
			$request_token['oauth_token'] = $_SESSION['tw_oauth_token'];
			$request_token['oauth_token_secret'] = $_SESSION['tw_oauth_token_secret'];

			// invalid token: abort
			if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
				
			} else {
				
				// if session already stored
				if ( isset($_SESSION['tw_access_token']) ) {
					
					// get access token
					$access_token = $_SESSION['tw_access_token'];
					$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

				} else {
					
					$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);
					$access_token = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));
					$_SESSION['tw_access_token'] = $access_token;
					
					// get access token
					$access_token = $_SESSION['tw_access_token'];
					$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret, $access_token['oauth_token'], $access_token['oauth_token_secret']);

				}
				
				$profile = $connection->get("account/verify_credentials");

				$profile = json_decode(json_encode($profile), true);
				
				$name = $profile['name'];
				$name = explode(' ', $name);
				
				// prepare the array that will be sent
				$profile['username'] = $profile['screen_name'];
				$profile['user_login'] = $profile['screen_name'];
				$profile['first_name'] = $name[0];
				$profile['last_name'] = $name[1];

				// username/email exists
				$profile['email_exists'] = '';
				$profile['username_exists'] = '';
				
				// provider identifier
				$profile['_uid_twitter'] = $profile['id'];
				
				if ( isset( $profile['profile_image_url'] ) && strstr( $profile['profile_image_url'], '_normal' ) ) {
					$profile['_save_synced_profile_photo'] = str_replace('_normal','',$profile['profile_image_url']);
				}
				
				$profile['_save_twitter_handle'] = '@' . $profile['screen_name'];
				$profile['_save_twitter_link'] = 'https://twitter.com/' . $profile['screen_name'];
				$profile['_save_twitter_photo_url_dyn'] = $profile['profile_image_url'];

				// have everything we need?
				$um_social_login->resume_registration( $profile, 'twitter' );
				
			}
			
		}
		
	}
		
	/***
	***	@get login uri
	***/
	function login_url() {
		global $ultimatemember;
		
		$connection = new TwitterOAuth( $this->consumer_key, $this->consumer_secret );
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->oauth_callback ));
		
		$_SESSION['tw_oauth_token'] = $request_token['oauth_token'];
		$_SESSION['tw_oauth_token_secret'] = $request_token['oauth_token_secret'];

		$this->login_url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));
		
		return $this->login_url;
		
	}
		
}