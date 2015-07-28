<?php
/**
 *
 * @package ESIG_DVN_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_DAN_Admin')) :
class ESIG_DAN_Admin {

	/**
	 * Instance of this class.
	 * @since    0.1
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 * @since    0.1
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
		$plugin = ESIG_DAN::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		
		add_filter('esig_audit_trail_view', array($this, 'show_view_notification'), 10, 2);
		add_action('esig_record_view_save', array($this, 'record_view_save'), 10, 1);
	}
	
	/**
	* Show document view in audit trail 
	* Since 1.0.4 
	*
	**/
   public function  show_view_notification($timeline,$args){
     
	  $events = $args['event'];
	  
	  if(! function_exists('WP_E_Sig'))
					return ;
				
				$esig = WP_E_Sig();
				$api = $esig->shortcode;
	  
	  foreach($events as $event){
			
			$data = json_decode($event->event_data);
			
			// Views
			if($event->event == 'viewed'){
				
				if($data->user){
					$viewer = $api->user->getUserdetails($data->user,$event->document_id);				
					$viewer_txt = $viewer->first_name . ' - ' . $viewer->user_email;
				}
				$viewer_txt = $viewer_txt ? " by $viewer_txt" : '';
				$log = "Document viewed $viewer_txt<br/>\n" . "IP: {$data->ip}\n";
				
			// Signed by all
			$timeline[strtotime($event->date)] = array( 
				"date" => $event->date,
				"log" => $log 
			);
			} 
			
			
		}
		
		return $timeline ; 
	  
   }
   
   /**
   * record view save and notification to owner . 
   * Since 1.0.1 
   **/
   public function record_view_save($args) {
       
	     $document_id = $args['document_id'];
		 $user_id = $args['user_id'] ; 
		 if(! function_exists('WP_E_Sig'))
					return ;
				
				$esig = WP_E_Sig();
				$api = $esig->shortcode;
				
				$document = $api->document->getDocument($document_id);
				$recipient =$api->user->getUserBy('user_id',$user_id) ;
				
				$owner = $api->user->getUserByID($document->user_id);

		// Prepare emails
		$recipient_email = $recipient->user_email;
		$user_email = $api->user->getUserEmail($document->user_id);
		
		$pageID = $api->setting->get('default_display_page', $document->user_id);
		
		$view_url = add_query_arg(array('preview'=>1,'document_id'=>$document->document_id), get_permalink($pageID));

		// adding apply filters 
        $esig_logo = apply_filters('esig_invitation_logo_filter','');
        if(empty($esig_logo)){
             $esig_logo ='<a href="http://www.approveme.me/?ref=1" target="_blank"><img
                               src=' . ESIGN_ASSETS_DIR_URI . '"/images/logo.png"    alt="WP E-Signature" border="0" style="margin-top: -8px;"
                              height="49" width="154"></a> '  ; 
        }
        
        $esig_header_tagline = apply_filters('esig_invitation_header_tagline_filter','');
        if(empty($esig_header_tagline)){
             $esig_header_tagline = 'Sign Legally Binding Documents using a WordPress website'  ; 
        }	
		$template_data = array(
            'esig_logo'=> $esig_logo , 
            'esig_header_tagline'=>$esig_header_tagline,
			'document_title' =>  $document->document_title,
			'document_id' =>isset($audit_hash)?$audit_hash:'',
			'document_checksum' => $document->document_checksum,
			'owner_first_name' => $owner->first_name,
			'owner_last_name' => $owner->last_name,
			'owner_email' => $owner->user_email,
			'signer_name' => $recipient->first_name,
			'signer_email' => $recipient->user_email,
			'view_url' => $view_url,
			'assets_dir' => ESIGN_ASSETS_DIR_URI,
		);
		
		//$signed_template =@file_get_contents();
		//$signed_message = $api->view->whiskers->whisk($signed_template, $template_data, false);
		$notify_template = dirname(__FILE__).'/views/notify.php';
		$signed_message = $api->view->renderPartial('', $template_data, false, '', $notify_template);
		
		$subject = "Document Viewed:{$document->document_title} ";

		$headers = array(
			"From: {$owner->first_name} {$owner->last_name} <{$owner->user_email}>",
			"Reply-To: {$owner->first_name} {$owner->last_name} <{$owner->user_email}>"
		);
		
		// send Email
		add_filter( 'wp_mail_content_type', array($this, 'set_html_content_type') );
		$mailsent = wp_mail($owner->user_email, $subject, $signed_message, $headers);
		remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
		
		if(!$mailsent) {
		  $headers  = 'MIME-Version: 1.0' . "\r\n";
		  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		  $headers .= 'From: '. $owner->first_name . " " . $owner->last_name . '<'. $owner->user_email  .'>' . "\r\n";  
		 $mailsent = wp_mail($owner->user_email, $subject, $signed_message, $headers); 
		} 
      }
    
/**
	 * Necessary callback method for wp_mail_content_type filter
	 *
	 * @since 1.0.3
	 */
	public function set_html_content_type(){
		return 'text/html';
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

