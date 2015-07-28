<?php
/**
 *
 * @package ESIG_AAMS_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_AT_Admin')) :
class ESIG_AT_Admin {

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
	
	public $temp_short ;
    
    public $temp_id ;
	
	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 * @since     0.1
	 */
	private function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = ESIG_AT::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		global $wpdb;
		$this->documents_table = $wpdb->prefix . 'esign_documents';
		// Load admin style sheet and JavaScript.
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		// adding filter . 
		add_filter('esig_admin_view_document_more_actions', array($this, 'show_template_actions'), 10,2);
		add_filter('esig-document-index-data', array($this, 'document_index_data'), 10, 1);
		add_filter('esig_admin_more_document_actions', array($this, 'show_more_actions'), 10, 2);
		add_filter('esig_admin_more_document_contents', array($this, 'document_add_data'), 10,1);
		add_filter('esig_sif_shortcode_content', array($this, 'sif_shortcode_content'), 10,2);
		add_filter('esig_url_redirect_load',array($this,'esig_temp_url_redirect'),10,1);
		
		add_filter('esig-add-document-form-meta-box',array($this,'document_meta_box'));
		// adding action 
		add_action('esig_document_after_save', array($this, 'template_after_save'), 10, 1);
		add_action('admin_init',array($this,'esig_insert_template_submitted'));
		add_action('esig_view_action_done',array($this,'esig_view_action_done'));
		// ajax 
		
		
		//ajax 
		add_action('wp_ajax_templateupload', array($this,'templateupload'));
		add_action('wp_ajax_nopriv_templateupload', array($this, 'templateupload'));
		// check sif item 
		add_action('wp_ajax_sifinputfield', array($this,'sifinputfield'));
		add_action('wp_ajax_nopriv_sifinputfield', array($this, 'sifinputfield'));
		// shortcode 
		add_shortcode('esigtemptextfield',array($this,'render_temp_textfield'));
		add_shortcode('esigtempradio',array($this,'render_temp_radio'));
		add_shortcode('esigtempcheckbox',array($this,'render_temp_checkbox'));
	}
	
	
	public function document_meta_box()
	{
		
		$esig_type = isset($_GET['esig_type'])?$_GET['esig_type']:false; 
		$html ='' ; 
		if($esig_type == "template")
		{
			
			$html .= '<div id="postimagediv" class="postbox">
       <h3 class="hndle"><span>Document Permission</span></h3>
       <div class="inside">';
			$html .=apply_filters('esig-template-option','');
			$html .= '</div></div>' ; 
		}
	   return $html ;
	
	}
	
	
	
	public function esig_temp_url_redirect($document_id)
	{
		if(!function_exists('WP_E_Sig'))
		return ;
		
		global $wpdb;	
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		$temp_id=isset($_GET['temp_id'])?$_GET['temp_id']:''; 
		if($api->setting->get_generic('esig_url_redirect_'.$temp_id))
		{
			$value=$api->setting->get_generic('esig_url_redirect_'.$temp_id);
			$api->setting->set('esig_url_redirect_'.$document_id,$value);
		}
		return ;
		
	}
	/*
	* sif signer content filter . 
	* Since 1.0.0
	*/
	public function sif_shortcode_content($sif_content,$args){
	   $document_id = $args['document_id'] ; 
	  $sif_content .='<div id="signer_display" class="sif_popup_right"><p>'.__('Who fills this out?', 'esig-at').'</p>';
	   
	  $noofsif=$args['sif_signer'] ; 
	
	  $sif_content .='<select name="sif_invite_select" data-placeholder="Choose a Option..." class="chosen-select" style="width:250px;" id="sif_invite_select" tabindex="2">';
	   
	   for($i=1;$i<=$noofsif;$i++){
	   $sif_content .='<option value="'. $i* rand()  . "ud" . $document_id .'">Signer '. $i .' </option>' ;
	   } 
	  $sif_content .='</select></div>';
		return $sif_content ; 
	}
	
	
	/**
	 * Temp Textfield Shortcode
	 * Usage: [esigtexttempfield label="First Name" required=""]
	 */
	public function render_temp_textfield($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'name' => 'textfield',
			'label' => 'Text', //foo is a default value
			'required' => '',
			'verifysigner'=>'',
			), $atts, 'esigtemptextfield'));
		if($verifysigner !='undefined'){
		$pieces = explode("ud", $verifysigner);
		$user_id =$pieces[0];
		$document_id = $pieces[1];
		}
		$this->temp_short[$user_id]['user_id'] = $user_id; 
		$this->temp_short[$user_id]['document_id'] = $document_id;
		$this->temp_short[$user_id]['text'.$user_id] = $label; 
		 
	}
	
	/**
	 * Radio Button Shortcode
	 * Usage: [esigtempradio]
	 */
	public function render_temp_radio($atts) {
		// Extract the attributes
		extract(shortcode_atts(array(
			'name' => 'radios',
			'labels' => 'Text', //foo is a default value
			'required' => '',
			'verifysigner'=>''
			), $atts, 'esigtempradio'));
			
		if($verifysigner !='undefined'){
		$pieces = explode("ud", $verifysigner);
		$user_id =$pieces[0];
		$document_id = $pieces[1];
		}
		
		$this->temp_short[$user_id]['user_id'] = $user_id;
		$this->temp_short[$user_id]['document_id'] = $document_id; 		
		$this->temp_short[$user_id]['radio'.$user_id]= $labels; 	
	}
	
	/**
	 * Checkbox temp Shortcode
	 * Usage: [esigtempcheckbox]
	 */
	public function render_temp_checkbox($atts) {
		// Extract the attributes
		
		extract(shortcode_atts(array(
			'name' => 'checkboxes',
			'boxes' => '', //foo is a default value
			'verifysigner'=>'',
			'required' => '',
			), $atts, 'esigtempcheckbox'));
			
		if($verifysigner !='undefined'){
		$pieces = explode("ud", $verifysigner);
		$user_id =$pieces[0];
		$document_id = $pieces[1];
		}
		$this->temp_short[$user_id]['user_id'] = $user_id; 
		$this->temp_short[$user_id]['document_id'] = $document_id;
		$this->temp_short[$user_id]['check'.$user_id]= $boxes;
	}
	/* when a insert submit button click 
	* Since 1.0.0 
	*/
	public function esig_view_action_done(){
	      if(!function_exists('WP_E_Sig'))
				return ;
				
			global $wpdb;	
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
			
		 if(isset($_POST['insert_template']) || $_POST['insert_template']=="Insert template"){
   		   
			  $template_id =isset($_POST['template_id']) ? $_POST['template_id'] : $_POST['template_id'] ;   
			if($_POST['esig_temp_document_type']=='sad'){
				wp_redirect("edit.php?post_type=esign&page=esign-add-document&esig_type=sad&add_tmp=1&temp_id=".$template_id);
			}
		 }
		  if(isset($_POST['nextstep'])){
		  $doc_id = $api->document->document_max()+1;
		  $template_id =isset($_POST['template_id']) ? $_POST['template_id'] : $_POST['template_id'] ;   
		if($_POST['esig_temp_document_type']=='basic'){
		
		    // sif filtering start here
			$documents=$api->document->getDocument($template_id);
			$document_content=$api->signature->decrypt(ENCRYPTION_KEY,$documents->document_content) ; 
			 $i=0 ; 
			 foreach($_POST['recipient_emails'] as $key => $value){
				$previous_sif=$_POST['recipient_siftemp'][$i]; 
				
				$user_id = $api->user->getUserID($value);
				
				$present_sif = $user_id . "ud" . $doc_id ; 
					
			   $document_content=str_replace($previous_sif,$present_sif,$document_content);
				
			  $i++ ; 
			}
				
			//$document_content=$api->signature->encrypt(ENCRYPTION_KEY,$document_content);
			// going to update database
			/*$wpdb->update($this->documents_table, 
				array('document_content'=>$document_content),
				array('document_id'=>$template_id),
				array('%s'),
				array('%d')
			); */
			
			 add_option('esigtemp_content'.$template_id,$document_content);
			// sif filtering end here 
		        
				wp_redirect('edit.php?post_type=esign&page=esign-add-document&esig_type=basic&document_id='.$doc_id .'&add_tmp=1&temp_id='.$template_id);
			}
		}
		
	}
	/* when a insert submit button click 
	* Since 1.0.0 
	*/
	public function esig_insert_template_submitted(){
	      if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
		
		 // adding template content 
		 if(isset($_GET['add_tmp'])=='1' || isset($_POST['nextstep']))
		 {
		   $temp_id =isset($_GET['temp_id']) ? $_GET['temp_id'] : $_GET['temp_id'] ;   
		    $this->temp_id = $temp_id ; 
		   $temp_settings=json_decode($api->setting->get_generic('esig-template-'.$temp_id));
			
		    $document_contents=$api->document->getDocument($temp_id);
			
			
			$template_title=$document_contents->document_title ;
			
			$print_option=is_array( $temp_settings)?$temp_settings->esig_print_option:null;
			 		 			
			
			
			// document title filter 
			add_filter('esig_admin_document_title_filter',array($this,'esig_template_document_title_insert'));
			// content filter start here 
			add_filter('esig_admin_document_contents_filter',array($this,'esig_template_document_content_insert'));
			// notify filter start here 
			add_filter('esig_admin_document_notify_filter',array($this,'esig_template_document_notify_insert'));
		    // print filter start here 
			add_filter('esig_admin_document_print_filter',array($this,'esig_template_document_print_insert'));
			// pdf filter start here 
			add_filter('esig_pdf_button_filter',array($this,'esig_template_document_pdf_insert'));
			// add signature filter start here
            add_filter('esig-add-signature-checked-filter',array($this,'esig_template_add_signature_insert'));
            // pdf attachment  filter start here
            add_filter('esig-pdf-attachment-check-filter',array($this,'esig_template_pdf_attachment_insert'));
            // pdf signer reminder filter  filter start here
            add_filter('esig-signer-reminder-checked-filter',array($this,'esig_template_signer_reminder_insert'));
            // Dropbox settins  filter start here
            add_filter('esig-dropbox-settings-checked-filter',array($this,'esig_template_dropbox_save_insert'));
		 }
	     
	}
    /***
    * Document title filter . 
    * Since 1.0.0
    */
    public function esig_template_document_title_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            $document_contents=$api->document->getDocument($this->temp_id);
			
			
            
          return $document_contents->document_title ;  
    }
    
    /***
    * Document print option filter . 
    * Since 1.0.0
    */
    public function esig_template_document_print_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            $document_contents=$api->document->getDocument($this->temp_id);
			$temp_settings=json_decode($api->setting->get_generic('esig-template-'.$this->temp_id));
			$print_option=is_array( $temp_settings)?$temp_settings->esig_print_option:null;
            
          return $print_option ;  
    }
    /***
    * Document pdf option filter . 
    * Since 1.0.0
    */
    public function esig_template_document_pdf_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            $document_contents=$api->document->getDocument($this->temp_id);
			$temp_settings=json_decode($api->setting->get_generic('esig-template-'.$this->temp_id));
			$pdf_option=is_array( $temp_settings)?$temp_settings->esig_pdf_option:null;
            
          return $pdf_option ;  
    }
    /***
    * Document notify option filter . 
    * Since 1.1.3
    */
    public function esig_template_document_notify_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            $document_contents=$api->document->getDocument($this->temp_id);
			
			if($document_contents->notify){
              $notify ="checked" ; 
            }else {
              $notify ="" ; 
            }
            
          return $notify ;  
          
    }
    
    /***
    * Document add signature option filter . 
    * Since 1.1.3
    */
    public function esig_template_add_signature_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            $document_contents=$api->document->getDocument($this->temp_id);
			
			if($document_contents->add_signature){
              $checked ="checked" ; 
            }else {
              $checked ="" ; 
            }
            
          return $checked ;  
          
    }
    
    /***
    * Document pdf attachment filter . 
    * Since 1.1.3
    */
    public function esig_template_pdf_attachment_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            
            $pdf_attachment=$api->setting->get_generic('esig_pdf_attachment_'.$this->temp_id);
           
			if($pdf_attachment){
              $checked ="checked" ; 
            }else {
              $checked ="" ; 
            }
            
          return $checked ;  
          
    }
    
     /***
    * Document signer reminders filter . 
    * Since 1.1.3
    */
    public function esig_template_dropbox_save_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            
            $signer_reminder=$api->setting->get_generic('esig_dropbox'.$this->temp_id);
           
			if($signer_reminder){
              $checked ="checked" ; 
            }else {
              $checked ="" ; 
            }
            
          return $checked ;  
          
    }
    
    /***
    * Document signer reminders filter . 
    * Since 1.1.3
    */
    public function esig_template_signer_reminder_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            
            $signer_reminder=$api->setting->get_generic('esig_reminder_send_'.$this->temp_id);
           
			if($signer_reminder){
              $checked ="checked" ; 
            }else {
              $checked ="" ; 
            }
            
          return $checked ;  
          
    }
    
    /***
    * Document Content  filter . 
    * Since 1.0.0
    */
    public function esig_template_document_content_insert(){
    
            // title filter here 
			
             if(!function_exists('WP_E_Sig'))
					return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
            $document_contents=$api->document->getDocument($this->temp_id);
			
			if(isset($_GET['esig_type']) && $_GET['esig_type']=='basic'){
			 $document_content=get_option('esigtemp_content'.$this->temp_id);
				delete_option('esigtemp_content'.$this->temp_id);	
		      } else {
		      $document_content = $api->signature->decrypt(ENCRYPTION_KEY, $document_contents->document_content);
		      }
             
            // checking sif shortcode 
			if(has_shortcode($document_content,'esigtemptextfield'))
			{
			   
			  $document_content=str_replace("esigtemptextfield","esigtextfield",$document_content);
			}
			if(has_shortcode($document_content,'esigtempradio'))
			{
			  $document_content=str_replace("esigtempradio","esigradio",$document_content);
			}
			if(has_shortcode($document_content,'esigtempcheckbox'))
			{
			 $document_content=str_replace("esigtempcheckbox","esigcheckbox",$document_content);
			}
          return $document_content ;  
    }
	/* return document content filter 
	* Since 1.0.0 
	*/ 
	
	public function template_document_content($document_contents_filter){
	
	     $document_contents_filter .=$document_content  ; 
		 return $document_contents_filter ;  
	}
	
	/**
	* ajax template upload  here 
	*
	* Since 1.0.0
	**/
	
	public function sifinputfield()
		{
		
			if(!function_exists('WP_E_Sig'))
				return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
			
			$template_id=$_POST['template_id'];
			// getting documents 
			$documents=$api->document->getDocument($template_id);
			
			$document_content=$api->signature->decrypt(ENCRYPTION_KEY,$documents->document_content) ; 
			$html='';
			
			if(has_shortcode($document_content,'esigtemptextfield'))
			{
			    do_shortcode($document_content,'[esigtemptextfield]');
			}
			elseif(has_shortcode($document_content,'esigtempcheckbox'))
			{
			do_shortcode($document_content,'[esigtempcheckbox]');
			}
			elseif(has_shortcode($document_content,'esigtempradio'))
			{
			do_shortcode($document_content,'[esigtempradio]');
			}
			else{
			
			die();
			}
			$signer_id =null ;
			$document_id = null ; 
			// print_r($this->temp_short);
			foreach($this->temp_short as $temp => $value){
			    
				  //if(empty($signer_id))
						$signer_id=$value['user_id'];
				  //if(empty($document_id))
						$document_id=$value['document_id'];
				
				$user_details=$api->user->getUserdetails($signer_id,$document_id);
					$first_name=!empty($user_details)?$user_details->first_name:'';
					$user_email=!empty($user_details)?$user_details->user_email:'';
			     $html .= '<div id="signer_main_pop">
				 <input type="hidden" class="recipient_fnames" name="recipient_siftemp[]" value="'. $signer_id .'ud'. $document_id .'"  />
							    <input type="text" class="recipient_fnames" name="recipient_fnames[]" placeholder="Signers Name" value="'. $first_name .'" />
								<input type="text" class="recipient_emails" name="recipient_emails[]" placeholder="email@address.com" value="'. $user_email .'" /><br>
							 '; 
				 foreach($value as $key=>$val){
				 
				       if($val != $signer_id ){
					        if($val != $document_id)
									$html .='{'. $val .'},' ; 
						}
					}
							 
				$html .='</div>' ; 
				
			}
			echo $html ; 
		  die();
		}
	/**
	* ajax template upload  here 
	*
	* Since 1.0.0
	**/
	
	public function templateupload()
		{
		
			if(!function_exists('WP_E_Sig'))
				return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
			
		$documents=$api->document->fetchAllOnStatus('esig_template',true);
			
			
			$html .='<option value="sel_temp_name">'.__('Select Template Name', 'esig-at').'</option>' ;
			foreach($documents as $template){
				
			if (is_plugin_active( 'esig-unlimited-sender-roles/esig-usr.php' )) 
				{
					
				$document_allow= apply_filters('esig-sender-roles-permission',$template->document_id);
						
						if($document_allow)
						{
							$html .='<option value="'. $template->document_id .'">'. $template->document_title .' </option>' ;
						}
						
				}
				else 
				{
					$html .='<option value="'. $template->document_id .'"> '. $template->document_title .' </option>' ;
				}
			}
			
			echo $html ; 
		  die();
		}
	
	/**
	 * Filter:
	 * Adds options to the document-add and document-edit screens
	 */		
	public function document_add_data($more_contents) {
        
        if(isset($_GET['esig_type']) && $_GET['esig_type'] == 'template')
					return $more_contents;
		
		if(!function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		global $wpdb;
		
		$selected = '';
		$checked='';
		$display_select='display:block;';
		
		
		//$doc_type = $api->document->getDocumenttype($document_id) ; 
		 
	       $assets_url=ESIGN_ASSETS_DIR_URI ; 
		
		$more_contents .='<p id="esig_template_option">';
		
		$more_contents .='
			<a href="#" class="tooltip">
					<img src="' .$assets_url. '/images/help.png" height="20px" width="20px" align="left" />
					<span>
					Selecting this option allows you to easily save this document as a template and create future documents from its contents.
					</span>
					</a>
				<input type="checkbox" '. $checked .' id="esig_template" name="esig_template"> ' . __('Save document as template','esig-at')  ; 
				
		$more_contents .='	<div id="esig_template_input" style="display:none;padding-left:50px;">
				
				<input type="text" name="esig_template_title" placeholder="Name of Template" value="" > ' ;
				$more_contents .=apply_filters('esig-template-option','');
			$more_contents .='</div></p>';
        
		if(isset($_GET['esig_type']) !='template'){
						return $more_contents;
          }
		return $more_contents;
	}
	
	/**
	 * Filter: 
	 * Show more document actions in the document list
	 */		
	public function show_more_actions($more_actions, $args){

		global $wpdb;
		$doc = $args['document'];
		
		if($doc->document_type == 'esig_template'){
			if($doc->document_status != "draft"){
						$more_actions .=  '| <a href="admin.php?page=esign-edit-document&esig_type=template&document_id=' . $doc->document_id . '">Edit</a> ';
						$more_actions .=  '| <a href="admin.php?page=esign-view-document&esig_type=template&esig_do=add_template&document_id=' . $doc->document_id . '">Add Document</a>';
						}
		}	
		return $more_actions;	
	}
	
	/**
	 * Filter: 
	 * Adds filter link to top of document index page
	 */
	public function document_index_data($template_data) {

		global $wpdb;

		$count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) " .
			"FROM {$this->documents_table} " .
			"WHERE document_type = '%s' AND document_status != '%s' " .
			" AND document_status !='%s'",
			'esig_template',
			'trash',
			'archive'));
			
		$url = "admin.php?page=esign-docs&amp;document_status=esig_template";
			 $css_class='';
			if( isset($_GET['document_status']) && $_GET['document_status'] == 'esig_template' ){
			  $css_class='class="current"';
			}
			
		if(array_key_exists('document_filters', $template_data) ){
			$template_data['document_filters'] .= "| <a $css_class href=\"$url\">Templates</a> ($count)  "; 
		}
		
		return $template_data;
	}
	
	/**
	 * Action:
	 * Fires after document save. Updates page/document_id data and shortcode on page.
	 */		
	public function template_after_save($args) {
		
		global $wpdb;
		
		 $doc_id= $args['document']->document_id;
		 
		 if(!function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		 $esig_template_array=array();
		 
		  
				
		 if(!isset($_POST['esig_template']) || !$_POST['esig_template'] ){
		  
		
		if(isset($_POST['add_template']) == 'Add Template')
				{
				$document_status='esig_template';   
				} else if(isset($_POST['save_template']) == 'Save as Draft') {
				  $document_status='draft';
				}  
				else {
				  return ; 
				}
				$documents=$api->document->getDocument($doc_id) ; 
				$document_content=$api->signature->decrypt(ENCRYPTION_KEY,$documents->document_content) ; 
			
			if(has_shortcode($document_content,'esigtextfield'))
			{
			   
			  $document_content=str_replace("esigtextfield","esigtemptextfield",$document_content);
			}
			if(has_shortcode($document_content,'esigradio'))
			{
			  $document_content=str_replace("esigradio","esigtempradio",$document_content);
			}
			if(has_shortcode($document_content,'esigcheckbox'))
			{
			 $document_content=str_replace("esigcheckbox","esigtempcheckbox",$document_content);
			}
			
		 $document_content=$api->signature->encrypt(ENCRYPTION_KEY,$document_content);
		    
				$wpdb->update($this->documents_table, 
				array('document_content'=>$document_content,'document_type'=>'esig_template','document_status'=> $document_status),
				array('document_id'=>$doc_id),
				array('%s','%s','%s'),
				array('%d')
			);
		  $esig_template_array['esig_print_option']=$_POST['esig_print_option'];
		  $esig_template_array['esig_pdf_option']=$_POST['esig_pdf_option'];
		  
		   $api->setting->set('esig-template-'.$doc_id,json_encode($esig_template_array));
		 } else {
		 
		 $user_id = get_current_user_id();
		 
		   $documents=$api->document->getDocument($doc_id) ; 
		   
		 $post_id = 0; // future versions may allow document to be displayed on a specific page
		$notify  = $documents->notify;
		$add_signature =$documents->add_signature;
		$document_status = 'esig_template';
		$document_type = 'esig_template';
		$document_hash = $documents->document_checksum; // will be added after insert; will need document id 
		$document_uri =$documents->document_uri; // relies on checksum, will be created after checsum, then updated
		$date_created  = date("Y-m-d H:i:s");
		$document_title =stripslashes($_POST['esig_template_title']); 
	
		$document_content=$api->signature->decrypt(ENCRYPTION_KEY,$documents->document_content) ; 
			
			if(has_shortcode($document_content,'esigtextfield'))
			{
			   
			  $document_content=str_replace("esigtextfield","esigtemptextfield",$document_content);
			}
			if(has_shortcode($document_content,'esigradio'))
			{
			  $document_content=str_replace("esigradio","esigtempradio",$document_content);
			}
			if(has_shortcode($document_content,'esigcheckbox'))
			{
			 $document_content=str_replace("esigcheckbox","esigtempcheckbox",$document_content);
			}
			
		 $document_content=$api->signature->encrypt(ENCRYPTION_KEY,$document_content);
		    
		 
		 $wpdb->query(
			$wpdb->prepare(
				"INSERT INTO " . $this->documents_table . " (document_id, user_id, post_id, document_title, document_content, notify, add_signature, document_type, document_status, document_checksum, document_uri,  ip_address, date_created, last_modified) VALUES(null, %d,%d,%s,%s,%d,%d,%s,%s,%s,%s,%s,%s,%s)",
				$user_id,
				$post_id,
				$document_title,
				$document_content,
				$notify,
				$add_signature,
				$document_type,
				$document_status,
				$document_hash,
				$document_uri,
				$_SERVER['REMOTE_ADDR'],
				$date_created,
				$date_created
			)
		);
		
		$doc_id = $this->wpdb->insert_id;
		
		 $esig_template_array['esig_print_option']=$_POST['esig_print_option'];
		  $esig_template_array['esig_pdf_option']=$_POST['esig_pdf_option'];
		  
		   $api->setting->set('esig-template-'.$doc_id,json_encode($esig_template_array));
		
		 }
			/*  */
		
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
			'e-signature_page_esign-view-document'
		);

		// Add/Edit Document scripts
		if(in_array($screen->id, $admin_screens)){
		    wp_enqueue_style( $this->plugin_slug . '-admin-style', plugins_url( 'assets/css/esig_template.css', __FILE__ ));	
			wp_enqueue_script('jquery');
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/esig-add-template.js', __FILE__ ), array('jquery', 'jquery-ui-dialog'), ESIG_AT::VERSION ,true);	
		}
		if(isset($_POST['esig_temp_document_type'])=="basic"){
		 wp_localize_script($this->plugin_slug . '-admin-script','esigtemplateAjax', array('ajaxurl' => admin_url('admin-ajax.php'),'esig_template_signer'=>$_POST['esig_temp_document_type']));
		}
		
		//if(isset($_GET['esig_type']) == 'template'){
		
		//wp_localize_script($this->plugin_slug . '-admin-script','esigtemplateAjax', array('ajaxurl' => admin_url('admin-ajax.php'),'esig_template_preview'=>$_GET['esig_type'],'esig_add_template'=>'','esig_add_template'=>$_GET['document_id']));
		//}
		if(isset($_GET['esig_type']) == 'template' && isset($_GET['esig_do'])=='add_template'){
		wp_localize_script($this->plugin_slug . '-admin-script','esigtemplateAjax', array('ajaxurl' => admin_url('admin-ajax.php'),'esig_add_template'=>$_GET['document_id']));
		}else {
		 $esig_type = isset($_GET['esig_type'])?$_GET['esig_type']:'';
         $temp_todo=isset($_GET['page'])=="esign-add-document"?"0":"1";
		if($esig_type=='template'){
		       wp_localize_script($this->plugin_slug . '-admin-script','esigtemplateAjax', array('ajaxurl' => admin_url('admin-ajax.php'),'esig_template_preview'=>$esig_type,'esig_template_edit'=>$temp_todo,'esig_add_template'=>''));
			   } else {
			   wp_localize_script($this->plugin_slug . '-admin-script','esigtemplateAjax', array('ajaxurl' => admin_url('admin-ajax.php'),'esig_template_preview'=>'','esig_add_template'=>''));
			   }
		}
	}
	
	
	/**
	 * Filter: 
	 * Show template action in view document option 
	 * Since 1.0.0
	 */		
	public function show_template_actions($more_option_page, $args){
		
		if(! function_exists('WP_E_Sig'))
					return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		$assets_dir = ESIGN_ASSETS_DIR_URI;
		$more_option_page .= '<div id="esig-settings-col3">
	 		
		
			<div class="esign-signing-options-col1 esign-signing-options">	
            <a href="#" id="template_view">
				<div id="esig-add-template" class="esig-doc-options esig-add-document-hover">
					<div class="icon"></div>
					<div class="text">'.__('+ Template','esig-at').'</div>
				</div>
            </a> 
                <!-- templates benefits start -->
               <div class="benefits">
					<p>' .  __('Template Benefits','esig_at') .'</p>
					<div class="plus-li">'. __('1 or more signers','esig_at').'</div>
					<div class="plus-li">'. __('Store a document as a reusable template','esig_at').'</div>
					<div class="plus-li">'. __('Create a document from a template','esig_at').'</div>
					<div class="plus-li">'. __('Great for saving time!','esig_at').'</div>

				</div> 
			</div>
	 
	</div>';
	
	
	$more_option_page .= '<div id="template-option-step2" class="esign-form-panel" style="display:none;">
				<div id="template_top">
				<span class="invitations-container">	

				<div align="center"><img src="' . $assets_dir .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
					<h2 class="esign-form-header">'.__('What Are You Trying To Do?', 'esig-at').'</h2>

				</span>
				<p id="create_template" align="center">';
	 if (is_plugin_active('esig-signer-input-fields/esig-sif.php')){
					
					$more_option_page .='<a href="#"  id="esig_template_create" class="button-primary esig-button-large">'.__('+ Create template', 'esig-at').'</a>';
      }
	 else {
	 $more_option_page .='<a href="edit.php?post_type=esign&page=esign-add-document&esig_type=template"  class="button-primary esig-button-large">'.__('+ Create template', 'esig-at').'</a>';
	 }
			$more_option_page .=	'</p>
				<form id="esig_create_template" name="esig-view-document" action="" method="post">
				<p id="no_of_signer" style="display:none;" align="center">
				<input type="text" name="signerno" placeholder="how many signers?" list="signer_list" />
				<datalist  id="signer_list">
				<option value="1">'.__('Signer 1', 'esig-at').'</option>
				<option value="2">'.__('Signer 2', 'esig-at').' </option>
				<option value="3"> '.__('Signer 3', 'esig-at').' </option>
				<option value="5"> '.__('Signer 5', 'esig-at').' </option>
				<option value="10"> '.__('Signer 10', 'esig-at').' </option>
				</datalist> 
				</p>
			
			<p id="create_template_basic_next" style="display:none;" align="center">';
				$doc_id = $api->document->document_max()+1;	
		//$api->invite->deleteDocumentInvitations($doc_id);				
	 $more_option_page .='<a href="#" id="esig_template_basic_next" data-document="' . $doc_id . '" class="button-primary esig-button-large">'.__('+ Next Step', 'esig-at').'</a>	
				</p>
			</form>
			
				<p id="upload_template_button" align="center">
					<a href="#" id="esig_template_upload" class="button-primary esig-button-large">'.__('+ Use existing template', 'esig-at').'</a>	
				</p>
				<form id="esig_select_template" name="esig-view-document" action="" method="post">
				<p id="template_type" style="display:none;" align="center">
				<select data-placeholder="Choose a Option..." class="chosen-select" tabindex="2" id="esig_temp_doc_type" name="esig_temp_document_type">
			<option value="doctype">'.__('Select Document Type', 'esig-at').'</option>
			<option value="basic"> '.__('Basic Document', 'esig-at').'  </option>';
            if ( is_plugin_active('esig-stand-alone-docs/esig-sad.php')){
			$more_option_page .='<option value="sad"> '.__('Stand Alone Document', 'esig-at').' </option>';
			}
		
            $more_option_page .='</select>
			</p>

				<p align="center" id="upload_template_content" style="display:none;">
				<select class="chosen-select" tabindex="2" id="template_id" name="template_id">
				
				</select>
				</p>
				<p id="insert_template_button" style="display:none;" align="center">
					<input type="hidden" value="Insert template" class="submit button button-primary esig-button-large" id="submit_insert"  name="insert_template">
					<input type="button" value="Insert Template" class="submit button button-primary esig-button-large" id="template_insert"  name="template_button">
				</p>
				
				</form></div> ' ;
				
				$more_option_page .='<div id="standard_view_popup_bottom" style="display:none">
					<form name="esig-view-document" action="" method="post">
					<input type="hidden" id="hidden_temp_type" name="esig_temp_document_type" value="">
					<input type="hidden" id="hidden_temp_id" name="template_id" value="20">
				<span class="invitations-container">	
				<div align="center"><img src="'. $assets_dir .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
					<h2 class="esign-form-header">'.__('Who needs to sign this document?', 'esig-at').'</h2>
		
					<div class="af-inner">
						<div id="recipient_emails_temp">
							
							<div id="signer_main">
							    <input type="text" class="recipient_fnames" name="recipient_fnames[]" placeholder="Signers Name" />
								<input type="text" class="recipient_emails" name="recipient_emails[]" placeholder="email@address.com" />
								
								<!--<input type="text" name="recipient_lnames[]" placeholder="Signers last name" /> -->
							</div>
							
						</div><!-- [data-group=recipient-emails] -->
						<p><a href="#" id="addRecipient_temp">+ Add Signer</a></p>
					</div>
					
				</span>
				<p align="center">
					<input type="submit" value="Insert template" class="submit button button-primary button-large" id="submit_insert"  name="nextstep">
				</p>				
</div></form>' ;

$more_option_page .= '</div> ' ; 
	
	
	
	
	
	
		return $more_option_page;
		
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

