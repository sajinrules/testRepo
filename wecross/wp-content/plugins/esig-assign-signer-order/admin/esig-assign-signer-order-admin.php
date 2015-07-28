<?php
/**
 *
 * @package ESIG_AAMS_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_ASSIGN_ORDER_Admin')) :
class ESIG_ASSIGN_ORDER_Admin {

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
		$plugin = ESIG_ASSIGN_ORDER::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		
		add_filter('esig-edit-document-template-data', array($this, 'show_signer_order_link_ajax'), 10,1);
        
        add_filter('esig-view-document-template-data', array($this, 'show_signer_order_link_view'), 10,1);
        
        add_filter('esig-signer-order-filter', array($this, 'show_signer_order_link'));
        
        
        add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        
        add_action('esig_document_after_save', array($this, 'document_after_save'), 10, 1);
        
        add_filter('esig_email_sending_invitation', array($this, 'esig_email_sending_filter'), 10,2);
        
        add_action('esig_view_action_done', array($this, 'signer_order_save' ));
        
        add_action('esig_signature_saved', array($this, 'signature_saved' ),10,1 );
	}
    
    /***
    * trigger this function when signature saved . 
    */
    
    public function signature_saved($args){
            
            if(!function_exists('WP_E_Sig'))
				     return ;
                     
            $esig = WP_E_Sig();
			$api = $esig->shortcode;
            
             $user_id= $args['recipient']->user_id;
             
             $document_id= $args['invitation']->document_id;
             
             $signer_order_active =  $api->setting->get_generic('esig_assign_signer_order_active'.$document_id);
             
             if($signer_order_active == '1'){
             
                   $signer_order =json_decode($api->setting->get_generic('esig_assign_signer_order'.$document_id)); 
                   
                   if(in_array($user_id,$signer_order)){
                   
                         $signer_position= array_search($user_id,$signer_order);
                         
                         $sender_id = $signer_order[$signer_position+1];
                         
                         if($sender_id){
                             if(!$api->signature->userHasSignedDocument($sender_id, $document_id)){ 
                                   
                                    $invitation_id =$api->invite->getInviteID_By_userID_documentID($sender_id,$document_id);
                                    $api->invite->send_invitation($invitation_id,$sender_id,$document_id);
                                    
                             }
                         }
                   }
             
             }
    }
    
    
    /***
    * this filter executed for email permission . 
    */
    
    public function esig_email_sending_filter($send,$args) {
    
             if(!function_exists('WP_E_Sig'))
				     return ;
                     
            $esig = WP_E_Sig();
			$api = $esig->shortcode;
            $invitelist=$args['invitelist'];
           
            $document_id = $args['document_id'];
           
            
            $signer_order_active =  $api->setting->get_generic('esig_assign_signer_order_active'. $document_id);
            if($signer_order_active != '1'){
                     $send="1";
                     return $send;
            }
            
            $user_id = $args['user_id'];
           
            $signer_order =json_decode($api->setting->get_generic('esig_assign_signer_order'. $document_id));
            
            if(empty($signer_order)){
                 $send="0";
                 return $send;
            }
          
           
            if(in_array($user_id,$signer_order)){
                  
                  foreach($signer_order as $signer) {
                        
                            if(!$api->signature->userHasSignedDocument($user_id, $document_id)){
                                 
                                    $signer_position= array_search($user_id,$signer_order);
                                    
                                    if($signer_position !=0){
                                    $previous_position =$signer_position-1 ;
                                    $previous_user_id = $signer_order[$previous_position];
                                    }else {
                                       $send="1";
                                       return  $send;
                                    }
                                    
                                    if($api->signature->userHasSignedDocument($previous_user_id, $document_id)){
                                           $send="1";
                                            return  $send;
                                    }else {
                                            $send="0";
                                            return  $send;
                                    }
                               
                            } else {
                                  $send="0";
                                   return  $send ;
                            }
                  }
           } else {
                $send="0";
               return  $send ;
           }
           
        
    }
    
     /**
	 * Action:
	 * Fires after document save. Updates page/document_id data and shortcode on page.
	 */	
     
	public function signer_order_save() {
    
            if(!function_exists('WP_E_Sig'))
				     return ;
                     
            $esig = WP_E_Sig();
			$api = $esig->shortcode;    
            $doc_id = $api->document->document_max()+1;
            
            $assign_signer_order = array();
            
            if(isset($_POST['esign-assign-signer-order-view']) && $_POST['esign-assign-signer-order-view'] == 1){
                    
                    for($i=0; $i < count($_POST['recipient_emails']); $i++){
		
		                    if(!$_POST['recipient_emails'][$i]) continue; // Skip blank emails
		                    $user_id = $api->user->getUserID($_POST['recipient_emails'][$i]);
                            
                            $assign_signer_order[]=$user_id ; 
                    }
                    
                    $api->setting->set('esig_assign_signer_order'.$doc_id,json_encode($assign_signer_order));
                    
                    $api->setting->set('esig_assign_signer_order_active'.$doc_id,$_POST['esign-assign-signer-order-view']);
            }
            
    }
    
    /**
	 * Action:
	 * Fires after document save. Updates page/document_id data and shortcode on page.
	 */	
     
	public function document_after_save($args) {
    
            if(!function_exists('WP_E_Sig'))
				     return ;
                     
            $esig = WP_E_Sig();
			$api = $esig->shortcode;    
            $doc_id = $args['document']->document_id; 
            
            $assign_signer_order = array();
            
            if(isset($_POST['esign-assign-signer-order']) && $_POST['esign-assign-signer-order'] == 1){
                    
                    for($i=0; $i < count($_POST['recipient_emails_ajax']); $i++){
		
		                    if(!$_POST['recipient_emails_ajax'][$i]) continue; // Skip blank emails
		                    $user_id = $api->user->getUserID($_POST['recipient_emails_ajax'][$i]);
                            
                            $assign_signer_order[]=$user_id ; 
                    }
                    
                    $api->setting->set('esig_assign_signer_order'.$doc_id,json_encode($assign_signer_order));
                    
                    $api->setting->set('esig_assign_signer_order_active'.$doc_id,$_POST['esign-assign-signer-order']);
            }
            
    }
    
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();
		$admin_screens = array(
			'admin_page_esign-add-document',
			'admin_page_esign-edit-document',
            'e-signature_page_esign-view-document'
		);

		// Add/Edit Document scripts
		
		if(in_array($screen->id, $admin_screens)){
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/esig-assign-signer-order.js', __FILE__ ), array('jquery'), ESIG_ASSIGN_ORDER::VERSION ,true);
        }
    }
    /**
	 * Filter: 
	 * showing signer order link
	 * Since 1.0.1
	 */		
	public function show_signer_order_link(){
        
        if(!function_exists('WP_E_Sig'))
				     return ;
                     
            $esig = WP_E_Sig();
			$api = $esig->shortcode;
            $checked = '';
         if(isset($_GET['document_id'])){
                $signer_order_active =  $api->setting->get_generic('esig_assign_signer_order_active'.$_GET['document_id']);
                if($signer_order_active){
                    $checked = 'checked';
                }
         }
            $document_id = isset($_GET['document_id'])?$_GET['document_id']:'';
            $invitation_count = $api->invite->getInvitationCount($document_id);
           if($invitation_count ==0)
           {
                echo '&nbsp;';
           }
            elseif($invitation_count >1)
            {
		        echo '<input type="checkbox" id="esign-assign-signer-order" name="esign-assign-signer-order" '. $checked .' value="1">' . __('Assign signer order','esig_order') ; 
	        }
            else 
            {
               echo '<span id="esign-signer-order-show" style="display:none;"><input type="checkbox" id="esign-assign-signer-order" name="esign-assign-signer-order" '. $checked .' value="1">' . __('Assign signer order','esig_order') . '</span>'; 
            }
		
		
	}
     /**
	 * Filter: 
	 * showing signer order link
	 * Since 1.0.1
	 */		
	public function show_signer_order_link_view($template_data){
    
		    $template_data['esig-signer-order']='<span id="esign-signer-order-link" style="display:none"><input type="checkbox" id="esign-assign-signer-order-view" name="esign-assign-signer-order-view" value="1">' . __('Assign signer order','esig_order') . '</span>'; 
	        
		    return $template_data;
		
	}
    
    /**
	 * Filter: 
	 * showing signer order link
	 * Since 1.0.1
	 */		
	public function show_signer_order_link_ajax($template_data){
    
		    $template_data['esig-signer-order']='<span id="esign-signer-order-link" style="display:none"><input type="checkbox" id="esign-assign-signer-order-ajax" name="esign-assign-signer-order-ajax" value="1">' . __('Assign signer order','esig_order') . '</span>'; 
	        
		    return $template_data;
		
	}
    
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

}

endif;

