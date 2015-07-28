<?php

require_once um_social_login_path . 'providers/instagram/api/Instagram.php';

use MetzWeb\Instagram\Instagram;

class UM_Social_Login_Instagram {

	function __construct() {
		
		add_action('init', array(&$this, 'load'));
		
		add_action('init', array(&$this, 'get_auth'));

	}

	/***
	***	@load
	***/
	function load() {

		$this->client_id = um_get_option('instagram_client_id');
		$this->client_secret = um_get_option('instagram_client_secret');
		$this->callback_url = get_bloginfo('url') . '/?provider=instagram';
		
	}

	/***
	***	@Get auth
	***/
	function get_auth() {
		global $um_social_login;

		if ( isset($_REQUEST['provider']) && $_REQUEST['provider'] == 'instagram' && isset($_REQUEST['code']) ) {
	
			$instagram = new Instagram(array(
				'apiKey'      => $this->client_id,
				'apiSecret'   => $this->client_secret,
				'apiCallback' => $this->callback_url
			));
			
			$token = false;
			
			if (isset($_SESSION['insta_access_token'])) {
				
				$token = $_SESSION['insta_access_token'];
				$user = $_SESSION['insta_user'];
				  
			} else {

				$code = $_GET['code'];
				$data = $instagram->getOAuthToken($code);
				$token = $data->access_token;
				$_SESSION['insta_access_token'] = $token;
				$_SESSION['insta_user'] = $data->user;
				
				$user = $_SESSION['insta_user'];
			
			}
			
			$instagram->setAccessToken($token);

			$profile = (array) $user;
			
			// prepare the array that will be sent
			$profile['username'] = $profile['username'];
			$profile['user_login'] = $profile['username'];

			// username/email exists
			$profile['email_exists'] = '';
			$profile['username_exists'] = '';
				
			// provider identifier
			$profile['_uid_instagram'] = $profile['id'];
				
			$profile['_save_synced_profile_photo'] = $profile['profile_picture'];
				
			$profile['_save_instagram_handle'] = '@' . $profile['username'];
			$profile['_save_instagram_link'] = 'https://instagram.com/' . $profile['username'];
			$profile['_save_instagram_photo_url_dyn'] = $profile['profile_picture'];

			// have everything we need?
			$um_social_login->resume_registration( $profile, 'instagram' );
			
		}
		
	}
		
	/***
	***	@get login uri
	***/
	function login_url() {
		global $ultimatemember;
		
		$instagram = new Instagram(array(
			'apiKey'      => $this->client_id,
			'apiSecret'   => $this->client_secret,
			'apiCallback' => $this->callback_url
		));
		
		$this->login_url = $instagram->getLoginUrl();
		
		return $this->login_url;
		
	}
		
}