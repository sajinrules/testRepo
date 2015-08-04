<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SimpleSocialInbox {
	public $dir;
	private $file;
	private $assets_dir;
	private $assets_url;

	private $db_version = "1.48";
	// 1.48 - added facebook group tables.
	// 1.47 - added linkedin tables.
	// 1.46 - added linkedin tables.

	/* @var $message_managers ucm_facebook[] */
	public $message_managers = array();

	private static $instance = null;
	public static function getInstance ( $file = false ) {
        if (is_null(self::$instance)) { self::$instance = new self( $file ); }
        return self::$instance;
    }

	public function __construct( $file ) {
		$this->file = $file;
		$this->dir = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		// Handle localisation
		$this->load_plugin_textdomain();
		add_action( 'init', array( $this, 'load_localisation' ), 0 );
		add_action( 'admin_init', array( $this, 'register_session' ));
		add_action( 'admin_init', array( $this, 'init' ) );


		add_action( 'plugins_loaded', array( $this, 'db_upgrade_check') );

		register_activation_hook( $file, array( $this, 'activation') );
		register_deactivation_hook( $file, array( $this, 'deactivation') );

		global $wpdb;
		define('_SIMPLE_SOCIAL_DB_PREFIX',$wpdb->prefix);

		// todo: hook these into loop
		// todo: option to disable a social method completely (tickboxes on settings page)
		$this->message_managers = array(
			'facebook' => new ucm_facebook(),
			'twitter' => new ucm_twitter(),
			'google' => new ucm_google(),
			'linkedin' => new ucm_linkedin(),
		);

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );
		add_action( 'wp_ajax_simple_social_send-message-reply' , array( $this, 'admin_ajax' ) );
		add_action( 'wp_ajax_simple_social_set-answered' , array( $this, 'admin_ajax' ) );
		add_action( 'wp_ajax_simple_social_set-unanswered' , array( $this, 'admin_ajax' ) );
		add_action( 'wp_ajax_simple_social_modal' , array( $this, 'admin_ajax' ) );
		add_action( 'wp_ajax_simple_social_fb_url_info' , array( $this, 'admin_ajax' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

		//ini_set('display_errors',true);
		//ini_set('error_reporting',E_ALL);
		add_filter('cron_schedules', array( $this, 'cron_new_interval') );
		add_action( 'simple_social_cron_job', array( $this, 'cron_run') );

		add_action( 'init', array( $this, 'social_init' ) );


	}


	public function social_init(){

		if ( ! wp_next_scheduled( 'simple_social_cron_job' ) ) {
			wp_schedule_event( time(), 'minutes_10', 'simple_social_cron_job' );
		}
		foreach($this->message_managers as $name => $message_manager){
			$message_manager->init();
		}
	}

	public function register_session(){
	    if( !session_id() )
	        session_start();
	}

	public function admin_ajax(){
		check_ajax_referer( 'simple-social-nonce', 'wp_nonce' );

		$action = isset($_REQUEST['action']) ? str_replace('simple_social_','',$_REQUEST['action']) : false;
		// pass off the ajax handling to our media managers:
		foreach($this->message_managers as $name => $message_manager){
			if($message_manager->handle_ajax($action, $this)){
				// success!
			}
		}

		exit;
	}

	function add_meta_box() {

		$screens = array( 'post', 'page' );

		foreach ( $screens as $screen ) {
			add_meta_box(
				'simple_social_inbox_meta',
				__( 'Simple Social', 'simple_social_inbox' ),
				array($this,'meta_box_callback'),
				$screen,
				'side'
			);
		}
	}

	/**
	 * Prints the box content.
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	function meta_box_callback( $post ) {
		include( trailingslashit( $this->dir ) . 'pages/metabox.php');

	}


	public function inbox_assets() {

		add_thickbox();

		wp_register_style( 'simple-social-css', $this->assets_url . 'css/social.css', array(), '1.0.0' );
		wp_register_style( 'jquery-timepicker', $this->assets_url . 'css/jquery.timepicker.css', array(), '1.0.0' );
		wp_enqueue_style( 'simple-social-css' );
		wp_enqueue_style( 'jquery-timepicker' );
		wp_enqueue_style('jquery-style', '//ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

    	wp_register_script( 'simple-social', $this->assets_url . 'js/social.js', array( 'jquery' ), '1.0.0' );
    	//wp_register_script( 'jquery-charcount', $this->assets_url . 'js/jquery.charactercounter.js', array( 'jquery' ), '1.0.0' );

    	wp_register_script( 'jquery-timepicker', $this->assets_url . 'js/jquery.timepicker.min.js', array( 'jquery','jquery-ui-datepicker' ), '1.0.0' );

		wp_localize_script( 'simple-social', 'simple_social', array(
			'wp_nonce' => wp_create_nonce('simple-social-nonce'),
		) );

    	wp_enqueue_script( 'simple-social' );
    	wp_enqueue_script( 'jquery-timepicker' );

		foreach($this->message_managers as $name => $message_manager) {
			$message_manager->page_assets(true);
		}

	}


	public function init() {
		if(isset($_REQUEST['_process'])){

			foreach($_POST as $key=>$val){
				if(!is_array($val)){
					$_POST[$key] = stripslashes($val);
				}
			}
			$process_action = $_REQUEST['_process'];
			$process_options = array();
			$social_message_id = false;
			if($process_action == 'send_social_message'){
				check_admin_referer( 'social_send-message' );
				// we are sending a social message! yay!

			    $send_time = time(); // default: now
                if(isset($_POST['schedule_date']) && isset($_POST['schedule_time']) && !empty($_POST['schedule_date']) && !empty($_POST['schedule_time'])){
                    $date = $_POST['schedule_date'];
                    $time_hack = $_POST['schedule_time'];
                    $time_hack = str_ireplace('am','',$time_hack);
                    $time_hack = str_ireplace('pm','',$time_hack);
                    $bits = explode(':',$time_hack);
                    if(strpos($_POST['schedule_time'],'pm')){
                        $bits[0] += 12;
                    }
                    // add the time if it exists
                    $date .= ' '.implode(':',$bits).':00';
	                $send_time = strtotime($date);
	                //echo $date."<br>".$send_time."<br>".ucm_print_date($send_time,true);exit;
                }else if(isset($_POST['schedule_date']) && !empty($_POST['schedule_date'])){
                    $send_time = strtotime($_POST['schedule_date']);
                }
				// wack a new entry into the social_message database table and pass that onto our message_managers below
				$social_message_id = ucm_update_insert('social_message_id',false,'social_message',array(
					'post_id' => isset($_POST['post_id']) ? $_POST['post_id'] : 0, //todo
					'sent_time' => $send_time,
				));
				if($social_message_id){
					$process_options['social_message_id'] = $social_message_id;
					$process_options['send_time'] = $send_time;
				}else{
					die('Failed to create social message');
				}
				/* @var $message_manager ucm_facebook */
				$message_count = 0;
				foreach($this->message_managers as $name => $message_manager){
					$message_count += $message_manager->handle_process($process_action, $process_options);
				}
				if($social_message_id && !$message_count){
					// remove the gobal social message as nothing worked.
					ucm_delete_from_db('social_message','social_message_id',$social_message_id);
				}else if($social_message_id){
					ucm_update_insert('social_message_id',$social_message_id,'social_message',array(
						'message_count' => $message_count,
					));
				}
				if(isset($_POST['debug']) && $_POST['debug']){
					echo "<br><hr> Successfully sent $message_count messages <hr><br><pre>";
					print_r($_POST);
					print_r($process_options);
					echo "</pre><hr><br>Completed";
					exit;
				}
				header("Location: admin.php?page=simple_social_inbox_sent");
				exit;
			}else{
				// just process each request normally:

				/* @var $message_manager ucm_facebook */
				foreach($this->message_managers as $name => $message_manager){
					$message_manager->handle_process($process_action, $process_options);
				}
			}

		}
	}

	public function add_menu_item() {

		$message_count = 0;
		foreach($this->message_managers as $name => $message_manager) {
			$message_count += $message_manager->get_unread_count();
		}
		$menu_label = sprintf( __( 'Simple Social %s', 'simple_social_inbox' ), $message_count > 0 ? "<span class='update-plugins count-$message_count' title='$message_count'><span class='update-count'>" . number_format_i18n( $message_count ) . "</span></span>" : '');

        add_menu_page( __( 'Simple Social Inbox', 'simple_social_inbox' ), $menu_label, 'edit_pages', 'simple_social_inbox_main', array($this, 'show_inbox'), 'dashicons-format-chat', "21.1" );

		// hack to rmeove default submenu
		$menu_label = sprintf( __( 'Inbox %s', 'simple_social_inbox' ), $message_count > 0 ? "<span class='update-plugins count-$message_count' title='$message_count'><span class='update-count'>" . number_format_i18n($message_count) . "</span></span>" : '' );
		$page = add_submenu_page('simple_social_inbox_main', __( 'Simple Social Inbox', 'simple_social_inbox' ), $menu_label, 'edit_pages',  'simple_social_inbox_main' , array($this, 'show_inbox'));
		add_action( 'admin_print_styles-'.$page, array( $this, 'inbox_assets' ) );

		//$page = add_submenu_page('simple_social_inbox_main', __( 'Interactions', 'simple_social_inbox' ), __('Interactions' ,'simple_social_inbox'), 'edit_pages',  'simple_social_inbox_interactions' , array($this, 'show_interactions'));
		//add_action( 'admin_print_styles-'.$page, array( $this, 'inbox_assets' ) );

		$page = add_submenu_page('simple_social_inbox_main', __( 'Compose', 'simple_social_inbox' ), __('Compose' ,'simple_social_inbox'), 'edit_pages',  'simple_social_inbox_compose' , array($this, 'show_compose'));
		add_action( 'admin_print_styles-'.$page, array( $this, 'inbox_assets' ) );

		$page = add_submenu_page('simple_social_inbox_main', __( 'Sent', 'simple_social_inbox' ), __('Sent' ,'simple_social_inbox'), 'edit_pages',  'simple_social_inbox_sent' , array($this, 'show_sent'));
		add_action( 'admin_print_styles-'.$page, array( $this, 'inbox_assets' ) );

		foreach($this->message_managers as $name => $message_manager) {
			$message_manager->init_menu();
		}

		$page = add_submenu_page( 'simple_social_inbox_main', __( 'Plugin Updates', 'simple_social_inbox' ) , __( 'Plugin Updates', 'simple_social_inbox' ) , 'manage_options' , 'simple_social_inbox_plugin_updates' ,  array( $this, 'plugin_updates_page' ) );

	}

	private function is_setup(){
		foreach($this->message_managers as $name => $message_manager){
			$accounts = $message_manager->get_accounts();
			if(count($accounts)){
				return true;
			}
		}
		return false;
	}

	public function show_interactions(){
		if($this->is_setup()){
			include( trailingslashit( $this->dir ) . 'pages/interactions.php');
		}else{
			include( trailingslashit( $this->dir ) . 'pages/setup.php');
		}
	}
	public function show_inbox(){
		if($this->is_setup()){
			include( trailingslashit( $this->dir ) . 'pages/inbox.php');
		}else{
			include( trailingslashit( $this->dir ) . 'pages/setup.php');
		}
	}
	public function show_compose(){
		if($this->is_setup()){
			include( trailingslashit( $this->dir ) . 'pages/compose.php');
		}else{
			include( trailingslashit( $this->dir ) . 'pages/setup.php');
		}
	}
	public function show_sent(){
		if($this->is_setup()){
			include( trailingslashit( $this->dir ) . 'pages/sent.php');
		}else{
			include( trailingslashit( $this->dir ) . 'pages/setup.php');
		}
	}

	public function plugin_updates_page(){
		include( trailingslashit( $this->dir ) . 'pages/plugin_updates.php');
	}
	public function cron_new_interval($interval){
		$interval['minutes_10'] = array('interval' => 10 * 60, 'display' => 'Once 10 minutes');
	    return $interval;
	}
	function cron_run( $debug = false ){
		// running the cron job every 10 minutes.
		// we get a list of accounts and refresh them all.
		//@set_time_limit(0);
		//mail('dtbaker@gmail.com','cron job running','test');
		//ob_start();
		//echo 'Checking cron job...';

		// todo: $debug as config variable in settings

		foreach($this->message_managers as $name => $message_manager) {
			$message_manager->run_cron( $debug );
		}

		//echo 'completed cron job';
		//mail('dtbaker@gmail.com','simple social cron',ob_get_clean());
	}

	public function db_upgrade_check(){
        if(get_option("simple_social_db_version") != $this->db_version){
	        $this->activation();
        }
	}
	public function deactivation(){
		wp_clear_scheduled_hook( 'simple_social_cron_job' );
	}
	public function activation(){


		global $wpdb;

$sql = <<< EOT


CREATE TABLE {$wpdb->prefix}social_message (
  social_message_id int(11) NOT NULL AUTO_INCREMENT,
  post_id int(11) NOT NULL,
  sent_time int(11) NOT NULL DEFAULT '0',
  message_data text NOT NULL,
  message_count int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_message_id (social_message_id),
  KEY post_id (post_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_facebook (
  social_facebook_id int(11) NOT NULL AUTO_INCREMENT,
  facebook_name varchar(50) NOT NULL,
  last_checked int(11) NOT NULL DEFAULT '0',
  last_message int(11) NOT NULL DEFAULT '0',
  facebook_data text NOT NULL,
  facebook_token varchar(255) NOT NULL,
  facebook_app_id varchar(255) NOT NULL,
  facebook_app_secret varchar(255) NOT NULL,
  import_personal int(1) NOT NULL DEFAULT '0',
  machine_id varchar(255) NOT NULL,
  PRIMARY KEY  social_facebook_id (social_facebook_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_facebook_message (
  social_facebook_message_id int(11) NOT NULL AUTO_INCREMENT,
  social_facebook_id int(11) NOT NULL,
  social_message_id int(11) NOT NULL DEFAULT '0',
  social_facebook_page_id int(11) NOT NULL,
  social_facebook_group_id int(11) NOT NULL,
  facebook_id varchar(255) NOT NULL,
  summary text NOT NULL,
  last_active int(11) NOT NULL DEFAULT '0',
  comments text NOT NULL,
  type varchar(20) NOT NULL,
  link varchar(255) NOT NULL,
  data text NOT NULL,
  status tinyint(1) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_facebook_message_id (social_facebook_message_id),
  KEY social_facebook_id (social_facebook_id),
  KEY social_message_id (social_message_id),
  KEY last_active (last_active),
  KEY social_facebook_page_id (social_facebook_page_id),
  KEY facebook_id (facebook_id),
  KEY status (status)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_facebook_message_read (
  social_facebook_message_id int(11) NOT NULL,
  read_time int(11) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_facebook_message_id (social_facebook_message_id,user_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_facebook_message_comment (
  social_facebook_message_comment_id int(11) NOT NULL AUTO_INCREMENT,
  social_facebook_message_id int(11) NOT NULL,
  facebook_id varchar(255) NOT NULL,
  time int(11) NOT NULL,
  message_from text NOT NULL,
  message_to text NOT NULL,
  data text NOT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_facebook_message_comment_id (social_facebook_message_comment_id),
  KEY social_facebook_message_id (social_facebook_message_id),
  KEY facebook_id (facebook_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_facebook_message_link (
  social_facebook_message_link_id int(11) NOT NULL AUTO_INCREMENT,
  social_facebook_message_id int(11) NOT NULL DEFAULT '0',
  link varchar(255) NOT NULL,
  PRIMARY KEY  social_facebook_message_link_id (social_facebook_message_link_id),
  KEY social_facebook_message_id (social_facebook_message_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_facebook_message_link_click (
  social_facebook_message_link_click_id int(11) NOT NULL AUTO_INCREMENT,
  social_facebook_message_link_id int(11) NOT NULL DEFAULT '0',
  click_time int(11) NOT NULL,
  ip_address varchar(20) NOT NULL,
  user_agent varchar(100) NOT NULL,
  url_referrer varchar(255) NOT NULL,
  PRIMARY KEY  social_facebook_message_link_click_id (social_facebook_message_link_click_id),
  KEY social_facebook_message_link_id (social_facebook_message_link_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;



CREATE TABLE {$wpdb->prefix}social_facebook_page (
  social_facebook_page_id int(11) NOT NULL AUTO_INCREMENT,
  social_facebook_id int(11) NOT NULL,
  page_name varchar(50) NOT NULL,
  last_message int(11) NOT NULL DEFAULT '0',
  last_checked int(11) NOT NULL,
  page_id varchar(255) NOT NULL,
  facebook_token varchar(255) NOT NULL,
  PRIMARY KEY  social_facebook_page_id (social_facebook_page_id),
  KEY social_facebook_id (social_facebook_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_facebook_group (
  social_facebook_group_id int(11) NOT NULL AUTO_INCREMENT,
  social_facebook_id int(11) NOT NULL,
  group_name varchar(50) NOT NULL,
  last_message int(11) NOT NULL DEFAULT '0',
  last_checked int(11) NOT NULL,
  group_id varchar(255) NOT NULL,
  administrator int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_facebook_group_id (social_facebook_group_id),
  KEY social_facebook_id (social_facebook_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_twitter (
  social_twitter_id int(11) NOT NULL AUTO_INCREMENT,
  twitter_id varchar(255) NOT NULL,
  twitter_name varchar(50) NOT NULL,
  twitter_data text NOT NULL,
  last_checked int(11) NOT NULL DEFAULT '0',
  user_key varchar(255) NOT NULL,
  user_secret varchar(255) NOT NULL,
  import_dm tinyint(1) NOT NULL DEFAULT '0',
  import_mentions tinyint(1) NOT NULL DEFAULT '0',
  import_tweets tinyint(1) NOT NULL DEFAULT '0',
  user_data text NOT NULL,
  searches text NOT NULL,
  account_name varchar(80) NOT NULL,
  PRIMARY KEY  social_twitter_id (social_twitter_id),
  KEY twitter_id (twitter_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_twitter_message (
  social_twitter_message_id int(11) NOT NULL AUTO_INCREMENT,
  social_twitter_id int(11) NOT NULL,
  social_message_id int(11) NOT NULL DEFAULT '0',
  reply_to_id int(11) NOT NULL DEFAULT '0',
  twitter_message_id varchar(255) NOT NULL,
  twitter_from_id varchar(80) NOT NULL,
  twitter_to_id varchar(80) NOT NULL,
  twitter_from_name varchar(80) NOT NULL,
  twitter_to_name varchar(80) NOT NULL,
  type tinyint(1) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '0',
  summary text NOT NULL,
  message_time int(11) NOT NULL DEFAULT '0',
  data text NOT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_twitter_message_id (social_twitter_message_id),
  KEY social_twitter_id (social_twitter_id),
  KEY social_message_id (social_message_id),
  KEY message_time (message_time),
  KEY status (status),
  KEY type (type),
  KEY twitter_message_id (twitter_message_id),
  KEY twitter_from_id (twitter_from_id,twitter_to_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_twitter_message_read (
  social_twitter_message_id int(11) NOT NULL,
  read_time int(11) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_twitter_message_id (social_twitter_message_id,user_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_twitter_message_link (
  social_twitter_message_link_id int(11) NOT NULL AUTO_INCREMENT,
  social_twitter_message_id int(11) NOT NULL DEFAULT '0',
  link varchar(255) NOT NULL,
  PRIMARY KEY  social_twitter_message_link_id (social_twitter_message_link_id),
  KEY social_twitter_message_id (social_twitter_message_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_twitter_message_link_click (
  social_twitter_message_link_click_id int(11) NOT NULL AUTO_INCREMENT,
  social_twitter_message_link_id int(11) NOT NULL DEFAULT '0',
  click_time int(11) NOT NULL,
  ip_address varchar(20) NOT NULL,
  user_agent varchar(100) NOT NULL,
  url_referrer varchar(255) NOT NULL,
  PRIMARY KEY  social_twitter_message_link_click_id (social_twitter_message_link_click_id),
  KEY social_twitter_message_link_id (social_twitter_message_link_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_google (
  social_google_id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  google_id varchar(255) NOT NULL,
  google_name varchar(50) NOT NULL,
  google_data text NOT NULL,
  last_checked int(11) NOT NULL DEFAULT '0',
  import_comments tinyint(1) NOT NULL DEFAULT '0',
  import_plusones tinyint(1) NOT NULL DEFAULT '0',
  import_mentions tinyint(1) NOT NULL DEFAULT '0',
  user_data text NOT NULL,
  searches text NOT NULL,
  api_cookies text NOT NULL,
  account_name varchar(80) NOT NULL,
  PRIMARY KEY  social_google_id (social_google_id),
  KEY google_id (google_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_google_message (
  social_google_message_id int(11) NOT NULL AUTO_INCREMENT,
  social_google_id int(11) NOT NULL,
  social_message_id int(11) NOT NULL DEFAULT '0',
  google_message_id varchar(255) NOT NULL,
  comment_count int(11) NOT NULL,
  comments text NOT NULL,
  share_count int(11) NOT NULL,
  plusone_count int(11) NOT NULL,
  google_actor text NOT NULL,
  google_type varchar(80) NOT NULL,
  type tinyint(1) NOT NULL DEFAULT '0',
  status tinyint(1) NOT NULL DEFAULT '0',
  summary text NOT NULL,
  summary_latest text NOT NULL,
  message_time int(11) NOT NULL DEFAULT '0',
  data text NOT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_google_message_id (social_google_message_id),
  KEY social_google_id (social_google_id),
  KEY social_message_id (social_message_id),
  KEY message_time (message_time),
  KEY status (status),
  KEY type (type),
  KEY google_message_id (google_message_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_google_message_read (
  social_google_message_id int(11) NOT NULL,
  read_time int(11) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_google_message_id (social_google_message_id,user_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_google_message_link (
  social_google_message_link_id int(11) NOT NULL AUTO_INCREMENT,
  social_google_message_id int(11) NOT NULL DEFAULT '0',
  link varchar(255) NOT NULL,
  PRIMARY KEY  social_google_message_link_id (social_google_message_link_id),
  KEY social_google_message_id (social_google_message_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_google_message_link_click (
  social_google_message_link_click_id int(11) NOT NULL AUTO_INCREMENT,
  social_google_message_link_id int(11) NOT NULL DEFAULT '0',
  click_time int(11) NOT NULL,
  ip_address varchar(20) NOT NULL,
  user_agent varchar(100) NOT NULL,
  url_referrer varchar(255) NOT NULL,
  PRIMARY KEY  social_google_message_link_click_id (social_google_message_link_click_id),
  KEY social_google_message_link_id (social_google_message_link_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_linkedin (
  social_linkedin_id int(11) NOT NULL AUTO_INCREMENT,
  linkedin_name varchar(50) NOT NULL,
  last_checked int(11) NOT NULL DEFAULT '0',
  import_stream int(11) NOT NULL DEFAULT '0',
  post_stream int(11) NOT NULL DEFAULT '0',
  linkedin_data text NOT NULL,
  linkedin_token varchar(255) NOT NULL,
  linkedin_app_id varchar(255) NOT NULL,
  linkedin_app_secret varchar(255) NOT NULL,
  machine_id varchar(255) NOT NULL,
  PRIMARY KEY  social_linkedin_id (social_linkedin_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_linkedin_message (
  social_linkedin_message_id int(11) NOT NULL AUTO_INCREMENT,
  social_linkedin_id int(11) NOT NULL,
  social_message_id int(11) NOT NULL DEFAULT '0',
  social_linkedin_group_id int(11) NOT NULL,
  linkedin_id varchar(255) NOT NULL,
  summary text NOT NULL,
  title text NOT NULL,
  last_active int(11) NOT NULL DEFAULT '0',
  comments text NOT NULL,
  type varchar(20) NOT NULL,
  link varchar(255) NOT NULL,
  data text NOT NULL,
  status tinyint(1) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_linkedin_message_id (social_linkedin_message_id),
  KEY social_linkedin_id (social_linkedin_id),
  KEY social_message_id (social_message_id),
  KEY last_active (last_active),
  KEY social_linkedin_group_id (social_linkedin_group_id),
  KEY linkedin_id (linkedin_id),
  KEY status (status)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_linkedin_message_read (
  social_linkedin_message_id int(11) NOT NULL,
  read_time int(11) NOT NULL DEFAULT '0',
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_linkedin_message_id (social_linkedin_message_id,user_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_linkedin_message_comment (
  social_linkedin_message_comment_id int(11) NOT NULL AUTO_INCREMENT,
  social_linkedin_message_id int(11) NOT NULL,
  linkedin_id varchar(255) NOT NULL,
  time int(11) NOT NULL,
  message_from text NOT NULL,
  message_to text NOT NULL,
  comment_text text NOT NULL,
  data text NOT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY  social_linkedin_message_comment_id (social_linkedin_message_comment_id),
  KEY social_linkedin_message_id (social_linkedin_message_id),
  KEY linkedin_id (linkedin_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE {$wpdb->prefix}social_linkedin_message_link (
  social_linkedin_message_link_id int(11) NOT NULL AUTO_INCREMENT,
  social_linkedin_message_id int(11) NOT NULL DEFAULT '0',
  link varchar(255) NOT NULL,
  PRIMARY KEY  social_linkedin_message_link_id (social_linkedin_message_link_id),
  KEY social_linkedin_message_id (social_linkedin_message_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_linkedin_message_link_click (
  social_linkedin_message_link_click_id int(11) NOT NULL AUTO_INCREMENT,
  social_linkedin_message_link_id int(11) NOT NULL DEFAULT '0',
  click_time int(11) NOT NULL,
  ip_address varchar(20) NOT NULL,
  user_agent varchar(100) NOT NULL,
  url_referrer varchar(255) NOT NULL,
  PRIMARY KEY  social_linkedin_message_link_click_id (social_linkedin_message_link_click_id),
  KEY social_linkedin_message_link_id (social_linkedin_message_link_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE {$wpdb->prefix}social_linkedin_group (
  social_linkedin_group_id int(11) NOT NULL AUTO_INCREMENT,
  social_linkedin_id int(11) NOT NULL,
  group_name varchar(50) NOT NULL,
  last_message int(11) NOT NULL DEFAULT '0',
  last_checked int(11) NOT NULL,
  group_id varchar(255) NOT NULL,
  linkedin_token varchar(255) NOT NULL,
  PRIMARY KEY  social_linkedin_group_id (social_linkedin_group_id),
  KEY social_linkedin_id (social_linkedin_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

EOT;

		$bits = explode(';',$sql);

	   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		foreach($bits as $sql){
			if(trim($sql)){
				dbDelta( trim($sql).';' );
			}
		}

		// now we update any old latin collations to utf8 (opps!)
		// from: http://stackoverflow.com/a/106272/457850
		$tables_to_check = array(
			'social_facebook',
			'social_facebook_message',
			'social_facebook_message_comment',
			'social_facebook_page',
			'social_google',
			'social_google_mesage',
			'social_message',
			'social_twitter',
			'social_twitter_message',
		);
		$convert_fields_collate_from = 'latin1_swedish_ci';
		$convert_fields_collate_to = 'utf8_general_ci';
		$convert_tables_character_set_to = 'utf8';
		$show_debug_messages = false;
		global $wpdb;
		$wpdb->show_errors();
		foreach($tables_to_check as $table) {
			$table = $wpdb->prefix . $table;
			$indicies = $wpdb->get_results(  "SHOW INDEX FROM `$table`", ARRAY_A );
			$results = $wpdb->get_results( "SHOW FULL COLUMNS FROM `$table`" , ARRAY_A );
			foreach($results as $result){
				if($show_debug_messages)echo "Checking field ".$result['Field'] ." with collat: ".$result['Collation']."\n";
				if(isset($result['Field']) && $result['Field'] && isset($result['Collation']) && $result['Collation'] == $convert_fields_collate_from){
					if($show_debug_messages)echo "Table: $table - Converting field " .$result['Field'] ." - " .$result['Type']." - from $convert_fields_collate_from to $convert_fields_collate_to \n";
					// found a field to convert. check if there's an index on this field.
					// we have to remove index before converting field to binary.
					$is_there_an_index = false;
					foreach($indicies as $index){
						if ( isset($index['Column_name']) && $index['Column_name'] == $result['Field']){
							// there's an index on this column! store it for adding later on.
							$is_there_an_index = $index;
							$wpdb->query( $wpdb->prepare( "ALTER TABLE `%s` DROP INDEX %s", $table, $index['Key_name']) );
							if($show_debug_messages)echo "Dropped index ".$index['Key_name']." before converting field.. \n";
							break;
						}
					}
					$set = false;

					if ( preg_match( "/^varchar\((\d+)\)$/i", $result['Type'], $mat ) ) {
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` VARBINARY({$mat[1]})" );
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` VARCHAR({$mat[1]}) CHARACTER SET {$convert_tables_character_set_to} COLLATE {$convert_fields_collate_to}" );
						$set = true;
					} else if ( !strcasecmp( $result['Type'], "CHAR" ) ) {
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` BINARY(1)" );
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` VARCHAR(1) CHARACTER SET {$convert_tables_character_set_to} COLLATE {$convert_fields_collate_to}" );
						$set = true;
					} else if ( !strcasecmp( $result['Type'], "TINYTEXT" ) ) {
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` TINYBLOB" );
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` TINYTEXT CHARACTER SET {$convert_tables_character_set_to} COLLATE {$convert_fields_collate_to}" );
						$set = true;
					} else if ( !strcasecmp( $result['Type'], "MEDIUMTEXT" ) ) {
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` MEDIUMBLOB" );
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` MEDIUMTEXT CHARACTER SET {$convert_tables_character_set_to} COLLATE {$convert_fields_collate_to}" );
						$set = true;
					} else if ( !strcasecmp( $result['Type'], "LONGTEXT" ) ) {
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` LONGBLOB" );
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` LONGTEXT CHARACTER SET {$convert_tables_character_set_to} COLLATE {$convert_fields_collate_to}" );
						$set = true;
					} else if ( !strcasecmp( $result['Type'], "TEXT" ) ) {
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` BLOB" );
						$wpdb->query( "ALTER TABLE `{$table}` MODIFY `{$result['Field']}` TEXT CHARACTER SET {$convert_tables_character_set_to} COLLATE {$convert_fields_collate_to}" );
						$set = true;
					}else{
						if($show_debug_messages)echo "Failed to change field - unsupported type: ".$result['Type']."\n";
					}
					if($set){
						if($show_debug_messages)echo "Altered field success! \n";
						$wpdb->query( "ALTER TABLE `$table` MODIFY {$result['Field']} COLLATE $convert_fields_collate_to" );
					}
					if($is_there_an_index !== false){
						// add the index back.
						if ( !$is_there_an_index["Non_unique"] ) {
							$wpdb->query( "CREATE UNIQUE INDEX `{$is_there_an_index['Key_name']}` ON `{$table}` ({$is_there_an_index['Column_name']})", $is_there_an_index['Key_name'], $table, $is_there_an_index['Column_name'] );
						} else {
							$wpdb->query( "CREATE UNIQUE INDEX `{$is_there_an_index['Key_name']}` ON `{$table}` ({$is_there_an_index['Column_name']})", $is_there_an_index['Key_name'], $table, $is_there_an_index['Column_name'] );
						}
					}
				}
			}
			// set default collate
			$wpdb->query( "ALTER TABLE `{$table}` DEFAULT CHARACTER SET {$convert_tables_character_set_to} COLLATE {$convert_fields_collate_to}" );
			if($show_debug_messages)echo "Finished with table $table \n";
		}
		$wpdb->hide_errors();


	   update_option( "simple_social_db_version", $this->db_version );
	}

	/**
	 * Load plugin localisation
	 * @return void
	 */
	public function load_localisation () {
		load_plugin_textdomain( 'simple_social_inbox' , false , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

	/**
	 * Load plugin textdomain
	 * @return void
	 */
	public function load_plugin_textdomain () {
	    $domain = 'simple_social_inbox';

	    $locale = apply_filters( 'plugin_locale' , get_locale() , $domain );

	    load_textdomain( $domain , WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
	    load_plugin_textdomain( $domain , FALSE , dirname( plugin_basename( $this->file ) ) . '/lang/' );
	}

}
