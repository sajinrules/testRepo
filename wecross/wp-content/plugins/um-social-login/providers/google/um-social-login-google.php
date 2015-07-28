<?php

class UM_Social_Login_Google {

	function __construct() {
		
		add_action('init', array(&$this, 'load'));
		
		add_action('init', array(&$this, 'get_auth'));

	}

	/***
	***	@load
	***/
	function load() {
		
		require_once um_social_login_path . 'providers/google/autoload.php';
		
		$this->client_id = um_get_option('google_client_id');
		$this->client_secret = um_get_option('google_client_secret');
		$this->redirect_uri = get_bloginfo('url') . '/';
		
	}

	/***
	***	@Get auth
	***/
	function get_auth() {
		global $um_social_login;

		if ( isset($_GET['code']) && !isset( $_GET['facebook_auth'] ) ) {
			
			$client = new Google_Client();
			$client->setAccessType('offline');
			$client->setClientId($this->client_id);
			$client->setClientSecret($this->client_secret);
			$client->setRedirectUri($this->redirect_uri);
			$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
			$client->addScope("https://www.googleapis.com/auth/userinfo.email");
			$service = new Google_Service_Oauth2($client);

			if (isset($_SESSION['gplus_token']) && $_SESSION['gplus_token']) {
				
				$client->setAccessToken($_SESSION['gplus_token']);
				$_SESSION['gplus_token'] = $client->getAccessToken();
			
			} else {
				
				$client->authenticate($_GET['code']); 
				$_SESSION['gplus_token'] = $client->getAccessToken();
				
			}
		
			if ($client->getAccessToken())  {
				$profile = $service->userinfo->get();
			}
			
			if ( isset( $profile ) ) {

				$profile = json_decode(json_encode($profile), true);

				// prepare the array that will be sent
				$profile['first_name'] = $profile['givenName'];
				$profile['last_name'] = $profile['familyName'];
				$profile['user_email'] = $profile['email'];

				// username/email exists
				$profile['email_exists'] = $profile['email'];
				$profile['username_exists'] = $profile['email'];
					
				// provider identifier
				$profile['_uid_google'] = $profile['id'];
				
				if ( isset( $profile['picture'] ) ) {
					$profile['_save_synced_profile_photo'] = $profile['picture'] . '?sz=200';
				}
				
				$profile['_save_google_handle'] = $profile['name'];
				$profile['_save_google_link'] = $profile['link'];
				$profile['_save_google_photo_url_dyn'] = $profile['picture'] . '?sz=40';
				
				// have everything we need?
				$um_social_login->resume_registration( $profile, 'google' );
			
			}
			
		}

	}
		
	/***
	***	@get login uri
	***/
	function login_url() {
		global $ultimatemember;
		
		$client = new Google_Client();
		$client->setClientId($this->client_id);
		$client->setClientSecret($this->client_secret);
		$client->setRedirectUri($this->redirect_uri);
		$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
		$client->addScope("https://www.googleapis.com/auth/userinfo.email");
		$this->login_url = $client->createAuthUrl();
		
		return $this->login_url;
		
	}
		
}