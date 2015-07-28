<?php

	$this->dir = um_social_login_path . 'providers/facebook/api/';

	require_once( $this->dir . '/FacebookSession.php' );
	require_once( $this->dir . '/FacebookRedirectLoginHelper.php' );
	require_once( $this->dir . '/FacebookRequest.php' );
	require_once( $this->dir . '/FacebookResponse.php' );
	require_once( $this->dir . '/FacebookSDKException.php' );
	require_once( $this->dir . '/FacebookRequestException.php' );
	require_once( $this->dir . '/FacebookAuthorizationException.php' );
	require_once( $this->dir . '/GraphObject.php' );
	require_once( $this->dir . '/GraphUser.php' );
	require_once( $this->dir . '/GraphSessionInfo.php' );
	require_once( $this->dir . '/Entities/AccessToken.php' );
	require_once( $this->dir . '/HttpClients/FacebookCurl.php' );
	require_once( $this->dir . '/HttpClients/FacebookHttpable.php' );
	require_once( $this->dir . '/HttpClients/FacebookCurlHttpClient.php' );

	use Facebook\FacebookSession;
	use Facebook\FacebookRedirectLoginHelper;
	use Facebook\FacebookRequest;
	use Facebook\FacebookResponse;
	use Facebook\FacebookSDKException;
	use Facebook\FacebookRequestException;
	use Facebook\FacebookAuthorizationException;
	use Facebook\GraphObject;
	use Facebook\GraphUser;
	use Facebook\GraphSessionInfo;
	use Facebook\Entities\AccessToken;
	use Facebook\HttpClients\FacebookCurl;
	use Facebook\HttpClients\FacebookHttpable;
	use Facebook\HttpClients\FacebookCurlHttpClient;

class UM_Social_Login_Facebook {

	function __construct() {
		
		add_action('init', array(&$this, 'load'));
		
		add_action('init', array(&$this, 'get_auth'));

	}

	/***
	***	@load
	***/
	function load() {

		$app_id = ( um_get_option('facebook_app_id') ) ? um_get_option('facebook_app_id') : 'APP_ID';
		$app_secret = ( um_get_option('facebook_app_secret') ) ? um_get_option('facebook_app_secret') : 'APP_SECRET';
		
		$this->app_id             	= $app_id;
		$this->app_secret         	= $app_secret;
		$this->required_scope     	= 'public_profile, email';
		$this->redirect_url 		= trailingslashit( get_bloginfo('url') );
		$this->redirect_url 		= add_query_arg('facebook_auth', 'true', $this->redirect_url);

		$this->login_url		  	= '';

	}

	/***
	***	@Get auth
	***/
	function get_auth() {
		global $um_social_login;
		
		if ( isset( $_REQUEST['facebook_auth'] ) && $_REQUEST['facebook_auth'] == 'true' ) {
			
			FacebookSession::setDefaultApplication( $this->app_id, $this->app_secret );
			
			$helper = new FacebookRedirectLoginHelper( $this->redirect_url );

			// check if $_SESSION is stored
			if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {
				$session = new FacebookSession( $_SESSION['fb_token'] );
				try {
					if ( !$session->validate() ) {
					$session = null;
					}
				} catch ( Exception $e ) {
					$session = null;
				}
			}     

			// Get unique session key
			if ( !isset( $session ) || $session === null ) {
				try {
				$session = $helper->getSessionFromRedirect();
					} catch( FacebookRequestException $ex ) {
					} catch( Exception $ex ) {
				}
			}

			// store new session and get profile data
			if ( isset( $session ) ) {
				
				$_SESSION['fb_token'] = $session->getToken();
				$appsecret_proof = hash_hmac('sha256', $_SESSION['fb_token'], $this->app_secret);
				$request = new FacebookRequest($session, 'GET', '/me', array("appsecret_proof" =>  $appsecret_proof));
				$response = $request->execute();
				$profile = $response->getGraphObject()->asArray();

				// prepare the array that will be sent
				$profile['user_email'] = $profile['email'];

				// username/email exists
				$profile['email_exists'] = $profile['email'];
				$profile['username_exists'] = $profile['email'];
				
				// provider identifier
				$profile['_uid_facebook'] = $profile['id'];
				
				$profile['_save_synced_profile_photo'] = 'http://graph.facebook.com/'.$profile['id'].'/picture?width=200&height=200';
				$profile['_save_facebook_handle'] = $profile['name'];
				$profile['_save_facebook_link'] = $profile['link'];

				// have everything we need?
				$um_social_login->resume_registration( $profile, 'facebook' );

			}

		}
		
	}
		
	/***
	***	@get login uri
	***/
	function login_url() {
		global $ultimatemember;

		FacebookSession::setDefaultApplication( $this->app_id, $this->app_secret );
		
		$helper = new FacebookRedirectLoginHelper( $this->redirect_url );
		
		$session = $helper->getSessionFromRedirect();
		
		if ( !isset( $session ) ){
			$this->login_url = $helper->getLoginUrl( array( 'scope' => $this->required_scope ) );
		}
		
		return $this->login_url;
		
	}
		
}