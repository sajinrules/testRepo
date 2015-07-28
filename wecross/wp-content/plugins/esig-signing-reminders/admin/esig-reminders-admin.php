<?php
/**
 *
 * @package ESIG_AAMS_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_REMINDERS_Admin')) :
class ESIG_REMINDERS_Admin {

	/**
	 * Instance of this class.
	 * @since    1.0.1
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 * @since    1.0.1
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 * @since     0.1
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = ESIG_REMINDERS::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		// Load admin style sheet and JavaScript.
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		// adding filter 
		add_filter('esig_admin_more_document_contents', array($this, 'document_add_data'), 10,1);
		add_filter('esig_admin_more_document_actions', array($this, 'show_more_actions'), 10, 2);
		add_filter('esig-document-index-footer', array($this, 'document_index_footer'), 10, 2);
		
		// adding action 
		add_action('esig_document_after_save', array($this, 'document_after_save'), 10, 1);
		//ajax 
		add_action('wp_ajax_esig_reminders_settings', array($this,'esig_reminders_settings'));
		add_action('wp_ajax_esig_reminders_start_pause', array($this,'esig_reminders_start_pause'));
		add_action('wp_ajax_esig_reminders_update', array($this,'esig_reminders_update'));
		add_action('wp_ajax_esig_reminders_instant_email', array($this,'esig_reminders_instant_email'));
		//add_action('wp_ajax_nopriv_esig_reminders_settings', array($this, 'esig_reminders_settings'));
		add_action('esig_send_daily_reminders',array($this,'esig_send_reminder_email')); //
        
        //add_action('admin_init',array($this,'esig_send_reminder_email')); //
	}
	
	/**
	 * This is method esig_reminder_dateDiff
	 *
	 * @param mixed $d1 This is a description
	 * @param mixed $d2 This is a description
	 * @return mixed This is the return value description
	 *
	 */	
	private function esig_reminder_dateDiff ($d1, $d2) {
		// Return the number of days between the two dates:
		return round(abs(strtotime($d1)-strtotime($d2))/86400);
	}
	
	/**
	 * This is method esig_send_reminder_email
	 *
	 * @return mixed This is the return value description
	 *
	 */	
	public function esig_send_reminder_email(){
		
		if(!function_exists('WP_E_Sig'))
		        return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		// get document list by status awaiting 
		$docs=$api->document->fetchAllOnStatus('awaiting');
		// loops starts 
		foreach($docs as $doc)
		{
			$document_id = $doc->document_id ; 
			if($api->setting->get_generic('esig_reminder_send_'.$document_id)=="1")
			{
				
				// get all invitation list 
				$allinvitation = $api->invite->getInvitations($document_id);
                
				foreach($allinvitation as $invite)
				{
					$send_filter=apply_filters('esig_email_sending_invitation','',array('user_id' =>$invite->user_id,'document_id'=>$document_id));
					if($send_filter == "0")
                    {
                        $send=0;
                    }else 
                    {
                        $send=1;
                    }
                    
                    if($send){
                    // check if already this user has been signed 
                   
					        if(!$api->signature->userHasSignedDocument($invite->user_id, $document_id)){
						        // getting reminder settings 
						        $reminder_settings =json_decode($api->setting->get_generic('esig_reminder_settings_'.$document_id));
						        $first_reminder=$reminder_settings->esig_reminder_for;
						        $second_reminder=$reminder_settings->esig_reminder_repeat;
						        $expire_reminder=$reminder_settings->esig_reminder_expire;
						        // get document create date 
						        $document_create_date = $doc->date_created ; 
						        $current_date = date('Y-m-d H:i:s');
						        // calculate create and current date 
						        $date_difference =$this->esig_reminder_dateDiff($document_create_date,$current_date);
						        // checking if match with first reminder 
                                
						        if($date_difference == $first_reminder){
							
							        $this->send_reminder($document_id,$invite->user_id);
							
						        }
						        // check with second reminder 
						        elseif($date_difference%$second_reminder == 0){
							        $this->send_reminder($document_id,$invite->user_id);
						        }
						        // check if reminder has been expired 
						        elseif($date_difference >= $expire_reminder){
							        $this->expire_reminder($document_id);
						        }
						
					        }
                    
                    }
				}
			}
		}
		
	}
	
	private function send_reminder($document_id,$signer_id)
	{
		if(!function_exists('WP_E_Sig'))
		return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		// setting invite templates 
		$invite_template=dirname(__FILE__)."/view/invite.php";
		$pageID = $api->setting->get('default_display_page');
		$invite_hash = $api->invite->getInviteHash_By_documentID($documentID);
		$document_checksum = $api->document->document_checksum_by_id($document_id);
		$invitationURL = add_query_arg(array('invite'=>$invite_hash, 'csum'=>$document_checksum, get_permalink($pageID)));
		
		$users= $api->user->getUserBy('user_id',$signer_id);
		
		$document=$api->document->getDocument($document_id);	 
		$user_id = $users->user_id ; 
		$user_details=$api->user->getUserdetails($user_id, $document_id);
		$admin_user = $api->user->getUserByWPID(get_current_user_id());
		$sender_name = $admin_user->first_name . " " . $admin_user->last_name;
		$template_data = array(
			'user_email' => $users->user_email,
			'user_full_name' => $sender_name,
			'recipient_name' =>$user_details->first_name,
			'document_title' => $document->document_title,
			'document_checksum' => $document->document_checksum,
			'invite_url' => $invitationURL,
			'assets_dir' => ESIGN_ASSETS_DIR_URI,
			);
		
		$invite_message =$api->view->renderPartial('', $template_data, false, '', $invite_template);
        
       // $api->view->whiskers->whisk($invite_template, $template_data, false);
		
		$subject ="Reminder: ". $document->document_title . "is awaiting your signature";  

		$headers = array(
			"From: {$admin_user->first_name} {$admin_user->last_name} <{$admin_user->user_email}>",
			"Reply-To: {$admin_user->first_name} {$admin_user->last_name} <{$admin_user->user_email}>"
			);
		
		// send Email
		add_filter( 'wp_mail_content_type', array($this, 'mailType') );
		$mailsent = wp_mail($users->user_email, $subject, $invite_message, $headers);
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		if(!$mailsent){
			$api->view->setAlert(array('type'=>'e-sign-red-alert alert e-sign-alert esig-updated', 'title'=>'', 'message'=>__("Oh snap! Your reminder not sending properly. check your mail server settings",'esig-reminders')));
			echo  $api->view->renderAlerts();
		}
	}
	
	/**
	 * This is method expire_reminder
	 *
	 * @param mixed $document_id This is a description
	 * @return mixed This is the return value description
	 *
	 */	
	private function expire_reminder($document_id)
	{
		if(!function_exists('WP_E_Sig'))
		return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		if(!empty($document_id)){
			$api->setting->set('esig_reminder_send_','0');
		} 
		
	}
	
	
	public static function esig_reminders_schedule_activation( $network_wide ) {
		//Use wp_next_scheduled to check if the event is already scheduled
		$timestamp = wp_next_scheduled( 'esig_send_daily_reminders' );
		if( $timestamp == false ){
			//Schedule the event for right now, then to repeat daily using the hook 'esig_send_daily_reminders'
			wp_schedule_event( time(),'daily', 'esig_send_daily_reminders');
		}
	}
	
	public static function esig_reminders_schedule_deactivation( $network_wide ) {
		// removing event hook when deactivate 
		wp_clear_scheduled_hook('esig_send_daily_reminders');
	}
	/**
	 * This is method esig_reminders_instant_email
	 *
	 * @return mixed This is the return value description
	 *
	 */	
	public function esig_reminders_instant_email(){
		
		$document_id = $_POST['document_id'];
		
		if(!function_exists('WP_E_Sig'))
		return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		//$esig_whisker=new WP_E_Whiskers();
		// recieving and send email reminder 
		for($i=0; $i < count($_POST['esig_reminder_email']); $i++){
			// getting signer email address 
			$signer_email = $_POST['esig_reminder_email'][$i];
			// getting invite templates 
			$invite_template=file_get_contents(plugins_url("/view/invite.wtp",__FILE__) );
			// getting page id 
			$pageID = $api->setting->get('default_display_page');

			
			$invite_hash = $api->invite->getInviteHash_By_documentID($documentID);
			$document_checksum = $api->document->document_checksum_by_id($document_id);
			$invitationURL = add_query_arg(array('invite'=>$invite_hash, 'csum'=>$document_checksum, get_permalink($pageID)));
			
			$users= $api->user->getUserBy('user_email',$signer_email);
			
			$document=$api->document->getDocument($document_id);	 
			$user_id = $users->user_id ; 
			$user_details=$api->user->getUserdetails($user_id, $document_id);
			$admin_user = $api->user->getUserByWPID(get_current_user_id());
			$sender_name = $admin_user->first_name . " " . $admin_user->last_name;
			$template_data = array(
				'user_email' => $signer_email,
				'user_full_name' => $sender_name,
				'recipient_name' =>$user_details->first_name,
				'document_title' => $document->document_title,
				'document_checksum' => $document->document_checksum,
				'invite_url' => $invitationURL,
				'assets_dir' => ESIGN_ASSETS_DIR_URI,
				);
			
			$invite_message = $api->view->whiskers->whisk($invite_template, $template_data, false);
			
			$subject ="Reminder: ". $document->document_title . __('is awaiting your signature', 'esig-reminders');

			$headers = array(
				"From: {$admin_user->first_name} {$admin_user->last_name} <{$admin_user->user_email}>",
				"Reply-To: {$admin_user->first_name} {$admin_user->last_name} <{$admin_user->user_email}>"
				);
			
			// send Email
			add_filter( 'wp_mail_content_type', array($this, 'mailType') );
			$mailsent = wp_mail($signer_email, $subject, $invite_message, $headers);
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
			
			
			
			// getting invite content . 
		
		}
		
		die();
	}
	
	public function mailType($content_type){
		return 'text/html';
	}
	
	public function esig_reminders_update(){
		
		$document_id = $_POST['document_id'];
		$reminder_for = $_POST['reminder_for'];
		$reminder_repeat = $_POST['reminder_repeat'];
		$reminder_expire= $_POST['reminder_expire'];
		
		if(!function_exists('WP_E_Sig'))
					return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		// updating settings 
		$esig_reminders_settings = array(
			"esig_reminder_for" =>$reminder_for ,
			"esig_reminder_repeat"=> $reminder_repeat ,
			"esig_reminder_expire" => $reminder_expire ,
			);
		// saving into database 
		$api->setting->set('esig_reminder_settings_'.$document_id,json_encode($esig_reminders_settings));
		
		die();
	}
	
	public function esig_reminders_start_pause(){
		
		$document_id = $_POST['document_id'];
		
		if(!function_exists('WP_E_Sig'))
				return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		// get current settings 
		$reminder = $api->setting->get_generic('esig_reminder_send_'.$document_id);
		//checking reminder on /off
		if($reminder == '1'){
			$api->setting->set('esig_reminder_send_'.$document_id,'0');
			_e('start reminders','esig-reminders');
		}else{
			$api->setting->set('esig_reminder_send_'.$document_id,'1');
			_e('pause reminders', 'esig-reminders');
		}
		
		die();
	}
	
	/**
	 * This is method esig_reminders_settings
	 *
	 * @return mixed This is the return value description
	 *
	 */	
	public function esig_reminders_settings(){
		
		 $document_id = $_POST['document_id'];
		
			if(! function_exists('WP_E_Sig'))
						return ;
		
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
			
		$all_invitation = $api->invite->getInvitations($document_id);
		foreach($all_invitation as $invite){
			echo '<div class="invite_box_left"> '. $invite->user_email .' </div>';
			if($api->signature->userHasSignedDocument($invite->user_id, $document_id)){
				echo '<div class="invite_box_right">' .__('signed','esig-reminders'). '</div>';	
			}else {
				echo '<div class="invite_box_right"> <input type="checkbox" name="reminder_email[]" id="reminder_checkbox" value="'. $invite->user_email .'"> </div>';	
			}
			
			$remind_settings=json_decode($api->setting->get_generic('esig_reminder_settings_'.$document_id));
			echo '<div id="esig_signer_reminder_settings">
					<div class="settings_box_left">'. __('Signing Reminder Settings','esig-reminders').'</div>
					<div class="settings_box_right"><a href="#" id="esig_update_reminders">'. __('update settings','esig-reminders') .'</a></div>
					<div class="setting_box_all">
					<p>'.__('Send a reminder email to the signer in','esig-reminders').' <input type="number" name="esig_reminder_for" value="'. $remind_settings->esig_reminder_for .'"> '.__('Days','esig-reminders').'</p>
				<p>'.__('After the first reminder send reminder every','esig-reminders').'  <input type="number" name="esig_reminder_repeat" value="'. $remind_settings->esig_reminder_repeat .'"> '.__('Days','esig-reminders').'</p>
				<p>'.__('Expire reminders in','esig-reminders').' <input type="number" name="esig_reminder_expire" value="'. $remind_settings->esig_reminder_expire .'"> '.__('Days','esig-reminders').'</p>
					
					<input type="hidden" name="document_id_no" value="'. $document_id .'">
					</div>
					
					</div>';
					
		
		}
		
		die();
		
	}
	
	/**
	 * Action:
	 * Fires after document save. Updates page/document_id data and shortcode on page.
	 */		
	public function document_after_save($args) {
		
		global $wpdb;
		$doc_id = $args['document']->document_id;
		
		if(! function_exists('WP_E_Sig'))
					return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		// recieving variable from document post  . 
		$esig_reminder_for=isset($_POST['esig_reminder_for'])?$_POST['esig_reminder_for'] : null ; 
		$esig_reminder_repeat=isset($_POST['esig_reminder_repeat'])?$_POST['esig_reminder_repeat'] : null ; 
		$esig_reminder_expire=isset($_POST['esig_reminder_expire'])?$_POST['esig_reminder_expire'] : null ; 
		// settings an array reminder settings 
		$esig_reminders_settings = array(
			"esig_reminder_for" => $esig_reminder_for ,
			"esig_reminder_repeat"=> $esig_reminder_repeat ,
			"esig_reminder_expire" => $esig_reminder_expire ,
		);
		// saving into database 
		$api->setting->set('esig_reminder_settings_'.$doc_id,json_encode($esig_reminders_settings));
		// setting reminder start
		$api->setting->set('esig_reminder_send_'.$doc_id,"1");
	}
	
	/**
	 * Filter:
	 * For loop footer on document index page
	 */	
	public function document_index_footer($loop_tail, $args){

		add_thickbox();
        
		$assets_dir = plugins_url( 'assets', __FILE__ );
		
        $core_assets =ESIGN_ASSETS_DIR_URI;
        
		$loop_tail .= '
			<div id="esig_reminder_popup_hidden" style="display:none;">
			<form name="esig_reminder_form" action="" method="post">
				<div class="esig_sad_popup wp-core-ui">
					<p align="center" class="popup-logo"><img src="'.  $core_assets .'/images/logo.svg"></p>
					
					<p class="document_title_caption" style="display:none;">
						'.__('Send signing reminders for :','esig-reminders').' <br>
					</p>
					<p class="instructions">
						
					</p>
					
					<div class="esig_reminder_invite_box">
					<span class="invite_signers">'.__('Invited Signers','esig-reminders').'</span>
					</div>
					<div id="esig_reminder_invite_row">
					
					
					</div>
					
					<div class="settings_box_left"><a href="#" id="esig_pause_reminders">'.__('pause reminders','esig-reminders').'</a></div>
					<div class="settings_box_right"><a href="#" id="send_instant_reminder_email" class="button-primary esig-button-large">'.__('Send Reminder Now','esig-reminders').'</a></div>
				</div>
				</form>
			</div>
		';
		return $loop_tail;
	}
	/**
	 * Filter: 
	 * Show more document actions in the document list
	 */		
	public function show_more_actions($more_actions, $args){

		global $wpdb;
		$doc = $args['document'];
		
		if(! function_exists('WP_E_Sig'))
					return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		// getting e-signature defalut page . 
		$page_id = $api->setting->get('default_display_page');
		$url = get_permalink($page_id);
		
		$reminder = $api->setting->get_generic('esig_reminder_send_'.$doc->document_id);
		// checking reminder settings 
		$remind_text="";
		if($reminder == '1'){
			$remind_text="<i class='fa fa-pause'></i>".__('pause reminders','esig-reminders');
			
		}else{
			$remind_text="<i class='fa fa-play'></i>".__('start reminders','esig-reminders');
		}
		
			if($doc->document_status == "awaiting"){
			$more_actions .=  '|<span class="esig_reminders_setting"> <a data-url="' . $url . '" href="javascript:void(0)" data-document="' . $doc->document_id . '" data-reminder="'. $remind_text .'" data-title="' . $doc->document_title . '" title="Signig reminders settings " id="reminders_document">'.__('Signing Reminders','esig-reminders').'</a></span> ';
				
			}
		
		return $more_actions;	
	}
	
	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();
		$admin_screens = array(
			'admin_page_esign-add-document',
			'admin_page_esign-edit-document',
			'e-signature_page_esign-view-document',
			'toplevel_page_esign-docs'
			);

		// Add/Edit Document scripts
		
		if(in_array($screen->id, $admin_screens)){
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/esig_reminders.css', __FILE__ ), array(), ESIG_REMINDERS::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/esig-reminders.js', __FILE__ ), array('jquery', 'jquery-ui-dialog'), ESIG_REMINDERS::VERSION ,true);	
			wp_enqueue_style (  'wp-jquery-ui-dialog');
			wp_localize_script($this->plugin_slug . '-admin-script','reminderAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
		}
		
		
	}
	/**
	* Filter:
	* Adds options to the document-add and document-edit screens
	*/		
	public function document_add_data($more_contents) {
		
		if(!function_exists('WP_E_Sig'))
		return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		global $wpdb;
		
		$selected = '';
		$checked=apply_filters('esig-signer-reminder-checked-filter','');
		$display_select='display:block;';
		
		if(isset($_GET['esig_type'])&& $_GET['esig_type']=='sad')
        {
                return $more_contents;
        }
        
		//$doc_type = $api->document->getDocumenttype($document_id) ; 
		if(isset($_GET['temp_id']))
        {
                $remind_settings=json_decode($api->setting->get_generic('esig_reminder_settings_'.$_GET['temp_id']));
                
        }
        $esig_reminder_for= isset($remind_settings)? $remind_settings->esig_reminder_for : '';
        $esig_reminder_repeat= isset($remind_settings)? $remind_settings->esig_reminder_repeat : '';
        $esig_reminder_expire= isset($remind_settings)? $remind_settings->esig_reminder_expire : '';
		$assets_url=ESIGN_ASSETS_DIR_URI ; 
		$more_contents .= '
			<p id="esig_signing_reminders">
			<a href="#" class="tooltip">
					<img src="'. $assets_url .'/images/help.png" height="20px" width="20px" align="left" />
					<span>
					'.__('Automatically send email reminder(s) to all signers that have not yet signed your document.','esig-reminders').'
					</span>
					</a>
				<input type="checkbox" '. $checked . ' id="esig_reminders" name="esig_reminders"> '.__('Enable Signing Reminders','esig-reminders').'
				<div id="esig_reminders_input" style="display:none;padding-left:50px;">
				
				<p>'.__('Send a reminder email to the signer in','esig-reminders').' <input type="text" name="esig_reminder_for" id="esig_reminder_for" value="'.  $esig_reminder_for  .'"> '.__('Days','esig-reminders').'</p>
				<p>'.__('After the first reminder send reminder every','esig-reminders').'  <input type="text" name="esig_reminder_repeat" id="esig_reminder_repeat" value="'.  $esig_reminder_repeat  .'"> '.__('Days','esig-reminders').'</p>
				<p>'.__('Expire reminders in','esig-reminders').'   <input type="text" name="esig_reminder_expire" id="esig_reminder_expire" value="'.  $esig_reminder_expire  .'"> '.__('Days','esig-reminders').'</p>
				
				</div>
			</p>		
		';
		
		//if(isset($_GET['esig_type']) !='template')
		return $more_contents;
		//else 
			//return ; 
	}
/**
	 * Return an instance of this class.
	 * @since     0.1
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

endif;

