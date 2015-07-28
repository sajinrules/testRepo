<?php
/**
 *
 * @package ESIG_AAMS_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_LOGO_BRANDING_Admin')) :
class ESIG_LOGO_BRANDING_Admin {

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
	public function __construct() {

		/*
		 * Call $plugin_slug from public plugin class.
		 */
		$plugin = ESIG_LOGO_BRANDING::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
        // Load admin style sheet and JavaScript.
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		// usr action 
		add_action('admin_menu', array(&$this, 'esig_upload_branding_adminmenu'));
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		// fitler start here 
        add_filter('esig_misc_more_document_actions',array($this,'esig_misc_page_more_acitons'),10,1);
        
        add_filter('esig_invitation_header_tagline_filter',array($this,'esig_invitation_header_tagline'),10,1);
        
        add_filter('esig_invitation_footer_head_filter',array($this,'esig_invitation_footer_head'),10,1);
        
        add_filter('esig_invitation_footer_text_filter',array($this,'esig_invitation_footer_text'),10,1);
        
         add_filter('esig_invitation_logo_filter',array($this,'esig_invitation_logo'),10,1);
		
         add_filter('esign_logo_and_branding',array($this,'esig_invitation_logo'),10,1);
         
         add_filter('esig_document_logo_filter',array($this,'esig_document_logo'),10,1);
         
         add_filter('esig_save_as_pdf_front_page',array($this,'pdf_front_page'),10,2);
         
         add_filter('esig_save_as_pdf_header',array($this,'pdf_header'),10,2);
          
         add_filter('esig_save_as_pdf_footer',array($this,'pdf_footer'),10,2);
         
         add_filter('esig-success-page-filter',array($this,'success_page_filter'),10,2);
         
        add_filter('esig-invite-button-background-color',array($this,'button_background_color_filter'),10);
        add_filter('esig-email-footer-text-enable',array($this,'branding_footer_disable'),10);
        
        // logo alignment filters 
        add_filter('esig-logo-alignment',array($this,'logo_alignment'));
        
        // sender type filter has been added . 
        
        add_filter('esig-sender-name-filter',array($this,'sender_name_filter'),10,1);
          
	}
    
    public function sender_name_filter($sender)
    {
            
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
            
            $sender_type = $api->setting->get_generic('esig_email_invitation_sender_checked');
             
            if($sender_type == "company")
            {
                 $sender=$api->setting->get('company_logo'); 
               
                 return $sender ;
            }
            else
            {
             
                $admin_user = $api->user->getUserByWPID(get_current_user_id());
                $sender = $admin_user->first_name . " " .$admin_user->last_name ;
                return $sender ;
            }
            
    }
    
    public function logo_alignment()
    {
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
            
             $align= $api->setting->get_generic('esig_document_head_img_alignment');
            
            $alignment = 'align="'. $align  .'"';
            
            return $alignment ;
    }
    
    
    public function branding_footer_disable()
    {   
        if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
            
		    $esig_settings = new WP_E_Setting();
            
             $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
           
            $footer_text= $esig_settings->get_generic('esig_brandhing_disable'.$wp_user_id); 
           
            if($footer_text)
            {
              $display="hide";
               return $display;
            }
           
           return ;
    
    }
    
    public function button_background_color_filter()
    {   
        if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
            
		    $esig_settings = new WP_E_Setting();
            
           
           
            $display_color =  $esig_settings->get_generic('esig_branding_back_color');
            
            if( $display_color)
            {
                return $display_color ; 
            }
            else
            {
               return $display_color ; 
            }
    
    }
    
     public function pdf_footer($pdf_footer,$doc_id)
     {
           
            $pdf_footer .='<div align="center" class="esig_branding_cover_footer">' .  home_url() . '</div>';
            
            return $pdf_footer; 
     }
    
    public function pdf_header($pdf_header,$doc_id){
             if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
            
            $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
            

                $company_logo =  $api->setting->get_generic('esig_branding_header_image'.$wp_user_id);
                if(!$company_logo){
                   $company_logo = ESIGN_ASSETS_DIR_URI . "/images/logo.svg";
                }
             
            $pdf_header .='<div align="center">' . ' <img src=" ' . $company_logo .'"></div>';
            return $pdf_header; 
    }
    
    public function pdf_front_page($pdf_front_page,$doc_id){
    
             if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
            
            $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
            
            if(!$api->document->getSignedresult($doc_id)){
                        return ; 
            }
            
            $esig_cover_page=$api->setting->get_generic('esig_cover_page'.$wp_user_id);
           
            // settings start here 
		    if(isset($esig_cover_page) && $esig_cover_page==1){	

               
            $documents = $api->document->getDocument($doc_id);
            $signed_date =$api->document->getEventDate($doc_id) ; 
            $pdf_front_page .=' 
            <div class="cover_container">
            <div class="cover_title" align="center"><h1> ' . $documents->document_title  .  '</h1><span>' . mysql2date(get_option('date_format'),$signed_date)  . '</span></div>

            <div class="cover_body" align="center">' ; 
            

             $pdf_front_page .='<span class="company_title">'. stripslashes($api->setting->get_generic("company_logo")) . '</span><br>';

            
             $admin_user =$api->user->getUserby('wp_user_id',$documents->user_id);
            
             $pdf_front_page .='<span class="document_sender">Document Sender : '. $admin_user->first_name . " " . $admin_user->last_name . '</span><br>' ;
             
             $allinvitation = $api->invite->getInvitations($doc_id);
             if($documents->add_signature){
                $pdf_front_page .='<span class="document_signer">Signer: ' . $admin_user->first_name . " " . $admin_user->last_name .  "</span> <br>" ; 
             }
             foreach($allinvitation as $invite){
             
              $pdf_front_page .='<span class="document_signer">Signer: ' . $invite->first_name . " " . $invite->last_name . "</span> <br>" ; 
             
             }
               
            $pdf_front_page .= ' </div></div>';

          
            
            return  $pdf_front_page;
            } 
            
          return ;  
    }
    
    public function esig_document_logo($document_logo){
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();
            $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
            $logo =  $esig_settings->get_generic('esig_branding_header_image'. $wp_user_id);
            $display =  $esig_settings->get_generic('esig_document_head_img'. $wp_user_id);
            if($display=="1"){
            $document_logo .='<a href="' . home_url() . '" target="_blank"><img
                               src=' . $logo . ' alt="' . get_bloginfo('name') . '" border="0" style="margin-top: -8px;"></a>';
            }
            return $document_logo ; 
    }
    
    public function esig_invitation_logo($esig_logo)
	{
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();
            $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
            $logo =  $esig_settings->get_generic('esig_branding_header_image'. $wp_user_id);
            if($logo !=false){
            $esig_logo .='<a href="' . home_url() . '" target="_blank"><img
                               src=' . $logo . ' alt="' . get_bloginfo('name') . '" border="0" align="center" style="margin-top: -8px;" class="esig_company_logo"></a>';
            } 
            return $esig_logo ; 
    }
    
    public function esig_invitation_header_tagline($esig_header_tagline){
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();
            $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
            $esig_header_tagline .=$esig_settings->get_generic('esig_branding_logo_tagline'. $wp_user_id);
            
            return htmlspecialchars(stripslashes($esig_header_tagline)) ; 
    }
    
    public function esig_invitation_footer_head($esig_footer_head){
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
            $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();
            $disabled = $esig_settings->get_generic(' esig_brandhing_disable'. $wp_user_id);
                if( $disabled == "1"){
                        return $esig_footer_head = ' ' ; 
                }
            $esig_footer_head .=$esig_settings->get_generic('esig_branding_footer_text_headline'. $wp_user_id);
            
            return htmlspecialchars(stripslashes($esig_footer_head)) ; 
    }
    
    public function esig_invitation_footer_text($esig_footer_text){
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();
            $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
            $disabled = $esig_settings->get_generic(' esig_brandhing_disable'.$wp_user_id);
                if( $disabled == "1"){
                        return $esig_footer_text = ' ' ; 
                }
            $text = $esig_settings->get_generic('esig_branding_email_footer_text'.$wp_user_id);
            if($text !=false){
            $esig_footer_text .= '<div width="100%" class="esig_branding_cover_footer"> ' . htmlspecialchars(stripslashes($text)) .  ' </div>';
            }
            return $esig_footer_text ; 
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
			'admin_page_esign-upload-logo-branding',
			
			);
        $success_screens = array(
			'admin_page_esign-upload-success-page',
			);

		// Add/Edit Document scripts
		
        
		if(in_array($screen->id, $admin_screens)){
            // Add the color picker css file       
            wp_enqueue_style( 'wp-color-picker' ); 
			wp_enqueue_script('media-upload'); //Provides all the functions needed to upload, validate and give format to files.
            wp_enqueue_script('thickbox'); //Responsible for managing the modal window.
            wp_enqueue_style('thickbox'); //Provides the styles needed for this window.
            wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/esig_branding.css', __FILE__ ), array(),ESIG_LOGO_BRANDING::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/esig_branding.js', __FILE__ ), array('jquery', 'jquery-ui-dialog'), ESIG_LOGO_BRANDING::VERSION ,true);	
            // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'assets/js/esig-brand-color.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
			
		}
		if(in_array($screen->id, $success_screens)){
			wp_enqueue_script('media-upload'); //Provides all the functions needed to upload, validate and give format to files.
            wp_enqueue_script('thickbox'); //Responsible for managing the modal window.
            wp_enqueue_style('thickbox'); //Provides the styles needed for this window.
            
            wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/esig_branding.css', __FILE__ ), array(),ESIG_LOGO_BRANDING::VERSION );
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/esig_success.js', __FILE__ ), array('jquery', 'jquery-ui-dialog'), ESIG_LOGO_BRANDING::VERSION ,true);	
			
		}
		
	}
    /**
	 * This is method esig_usr_adminmenu
	 *   Create a admin menu for esinature roles . 
	 * @return mixed This is the return value description
	 */    
	public function esig_upload_branding_adminmenu(){
		
			if(!function_exists('WP_E_Sig'))
				    return;
                    
		$esigrole = new WP_E_Esigrole();
		if($esigrole->esig_current_user_can('have_licenses')){
			add_submenu_page(null, __('Esig Upload Logo Branding','esig-ulab'), __('Esig Upload Logo Branding','esig-ulab'), 'read','esign-upload-logo-branding', array(&$this, 'esign_upload_logo_branding_view'));
            add_submenu_page(null, __('Esig Upload Success Page','esig-ulab'), __('Esig Upload Success Page','esig-ulab'), 'read','esign-upload-success-page', array(&$this, 'esign_upload_success_page_view'));	
		}
	}
    /***
    * Adding success page filter
    * @Since 1.1.3
    */
    public function success_page_filter($success_msg,$args){
           
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();
             $wp_user_id = $api->setting->get_generic('esig_superadmin_user');
            $display =  $esig_settings->get_generic('esig_success_page_image_disable'. $wp_user_id);
           
            
                 $success_image=$esig_settings->get_generic('esig_success_page_image'.$wp_user_id);
                 $success_paragraph=htmlspecialchars(stripslashes($esig_settings->get_generic('esig_success_page_paragraph_text'.$wp_user_id)));
                 $assets_dir=ESIGN_ASSETS_DIR_URI ;
            
            if($display !="1"){
                if($success_paragraph){
                         $success_msg = "<p class=\"success_title\" align=\"center\">{$success_paragraph}</h2> <p align='center' class='s_logo'><img src='$success_image'></p>";
                 }else {
				$success_msg = "<p class=\"success_title\" align=\"center\"><h2>You're done signing! {$args['document']->document_title}</h2> <p align='center' class='s_logo'><span class=\"icon-success-check\"></span></p>";       
                 }
            }else {
            
                 if($success_paragraph){
                    $success_msg = "<p class=\"success_title\" align=\"center\">{$success_paragraph}</h2> <p align='center' class='s_logo'><img src='$assets_dir/images/boss.svg'></p>";
                 } 
            
            }
            
        return $success_msg;
    }
    
    /***
    * Adding success page content view 
    * @Since 1.1.3
    */
     public function esign_upload_success_page_view(){
    
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();

            // loading whiskers with constructing initials 
            //$esig_whisker=new WP_E_Whiskers(array("partials_path" => ESIGN_PLUGIN_PATH . DS . "views" . DS . "partials"));
            $wp_user_id = get_current_user_id();
            $msg='';
            // doing after form submit actions . 
            if(count($_POST) > 0  && isset($_POST['esig_success_submit']) && $_POST['esig_success_submit']=='Save Settings'){
                    
                     $success_image= isset($_POST['esig_branding_success_image'])?$_POST['esig_branding_success_image']:'';
                     $success_paragraph=isset($_POST['esig_success_paragraph_text'])? $_POST['esig_success_paragraph_text']:'';
           
                     $success_image_disabled=isset($_POST['esig_success_image_show'])?$_POST['esig_success_image_show']:'';
                     
                     // saving email settings into databse 
                     $esig_settings->set('esig_success_page_image'.$wp_user_id,$success_image);
              
                     $esig_settings->set('esig_success_page_paragraph_text'.$wp_user_id, $success_paragraph);
                     $esig_settings->set('esig_success_page_image_disable'.$wp_user_id,$success_image_disabled);
                     
                     
                     $msg .= '<div class="alert e-sign-alert esig-updated"><div class="title"></div><p class="message">'.__('<strong>Well done sir</strong> :  Your E-Signature Success page settings have been updated!','esig-ulab').'</p></div>';
            }
            
            
            $misc_more_actions = apply_filters('esig_misc_more_document_actions','');
            // getting settings value from database .
            $success_image=$esig_settings->get_generic('esig_success_page_image'.$wp_user_id);
            $success_paragraph=$esig_settings->get_generic('esig_success_page_paragraph_text'.$wp_user_id);
            $success_image_disabled=$esig_settings->get_generic('esig_success_page_image_disable'.$wp_user_id);
            
            $class=(isset($_GET['page']) && $_GET['page']=='esign-upload-success-page')?'misc_current':'';
            // setting contents templates 
            $esig_cover_page=$esig_settings->get_generic('esig_cover_page'.$wp_user_id);
            
		    $success_image_disabled=(isset($success_image_disabled) && $success_image_disabled==1)?"checked":"";	
            
            $template_data=array(
			"ESIGN_ASSETS_DIR_URI"=>ESIGN_ASSETS_DIR_URI,
			"Licenses"=> $esig_general->checking_extension(),
			"misc_tab_class"=>'nav-tab-active',
             "customizztion_more_links"=> $misc_more_actions,
             "message"=>$msg,
             "esig_success_page_image"=>$success_image,
             "esig_success_page_paragraph_text"=>$success_paragraph,
             "esig_success_page_image_disable"=>$success_image_disabled,
           
			);

            $branding_template = dirname(__FILE__) ."/view/esig-success-view.php";
            $api->view->renderPartial('', $template_data, true, '', $branding_template);
		
    }
    
    public function esign_upload_logo_branding_view(){
    
            if(!function_exists('WP_E_Sig'))
				return;
			
		    $esig = WP_E_Sig();
		    $api = $esig->shortcode;
		    //calling esignature setings class to save data in settings table
            $esig_general = new WP_E_General();
		    $esig_settings = new WP_E_Setting();

            // loading whiskers with constructing initials 
            //$esig_whisker=new WP_E_Whiskers(array("partials_path" => ESIGN_PLUGIN_PATH . DS . "views" . DS . "partials"));
            $wp_user_id =$esig_settings->get_generic('esig_superadmin_user');
            $branding_view_content=file_get_contents( dirname(__FILE__) ."/view/esig-branding-view.php");
            $msg='';
            // doing after form submit actions . 
            if(count($_POST) > 0  && isset($_POST['branding_submit']) && $_POST['branding_submit']=='Save Settings'){
                    
                     $branding_logo = isset($_POST['esig_branding_header_image'])?$_POST['esig_branding_header_image']:'';
                     $branding_tagline =isset($_POST['esig_branding_logo_tagline'])? $_POST['esig_branding_logo_tagline']:'';
                     $branding_textheadline =isset($_POST['esig_branding_footer_text_headline'])? $_POST['esig_branding_footer_text_headline']:'';
                     $branding_footertext =isset($_POST['esig_branding_email_footer_text'])? $_POST['esig_branding_email_footer_text']:'';
                     $branding_disabled =isset($_POST['esig_brandhing_disable'])?$_POST['esig_brandhing_disable']:'';
                     $esig_document_head_img =isset($_POST['esig_document_head_img'])? $_POST['esig_document_head_img']:'';
                     $esig_cover_page =isset($_POST['esig_cover_page'])? $_POST['esig_cover_page']:'';
                     $esig_background_color =isset($_POST['esig_button_background'])? $_POST['esig_button_background']:'';
                   
                     $esig_document_head_img_alignment =isset($_POST['esig_document_head_img_alignment'])? $_POST['esig_document_head_img_alignment']:'';
                     // saving email settings into databse 
                     $esig_settings->set('esig_branding_header_image'.$wp_user_id,$branding_logo);
                     $esig_settings->set('esig_branding_logo_tagline'.$wp_user_id,$branding_tagline);
                     $esig_settings->set('esig_branding_footer_text_headline'.$wp_user_id,$branding_textheadline);
                     $esig_settings->set('esig_branding_email_footer_text'.$wp_user_id,$branding_footertext);
                     $esig_settings->set('esig_brandhing_disable'.$wp_user_id,$branding_disabled);
                     $esig_settings->set('esig_document_head_img'.$wp_user_id,$esig_document_head_img);
                     $esig_settings->set('esig_cover_page'.$wp_user_id,$esig_cover_page);
                     $esig_settings->set('esig_branding_back_color',$esig_background_color);
                     $esig_settings->set('esig_document_head_img_alignment',$esig_document_head_img_alignment);
                     
                      $esig_settings->set('esig_email_invitation_sender_checked',$_POST['esig_email_invitation_sender_checked']);
                     
                     $msg .= '<div class="alert e-sign-alert esig-updated"><div class="title"></div><p class="message">'.__('<strong>Well done sir</strong> :  Your E-Signature E-mail templates settings have been updated!','esig-ulab').'</p></div>';
            }
            
            
            $misc_more_actions = apply_filters('esig_misc_more_document_actions','');
            // getting settings value from database .
            $esig_branding_header_image=$esig_settings->get_generic('esig_branding_header_image'.$wp_user_id);
            $esig_branding_logo_tagline=$esig_settings->get_generic('esig_branding_logo_tagline'.$wp_user_id);
            $esig_branding_footer_text_headline=$esig_settings->get_generic('esig_branding_footer_text_headline'.$wp_user_id);
            $esig_branding_email_footer_text=$esig_settings->get_generic('esig_branding_email_footer_text'.$wp_user_id);
            
            $esig_document_head_img=$esig_settings->get_generic('esig_document_head_img'.$wp_user_id);
            
		    $esig_document_head_img=(isset($esig_document_head_img) && $esig_document_head_img==1)?"checked":"";	
            
            $esig_brandhing_disable=$esig_settings->get_generic('esig_brandhing_disable'.$wp_user_id);
            
		    $esig_brandhing_disable=(isset($esig_brandhing_disable) && $esig_brandhing_disable==1)?"checked":"";	
            
            $esig_branding_extra=($esig_brandhing_disable=="checked")?"readonly":"";
            
            $class=(isset($_GET['page']) && $_GET['page']=='esign-upload-logo-branding')?'misc_current':'';
            // setting contents templates 
            $esig_cover_page=$esig_settings->get_generic('esig_cover_page'.$wp_user_id);
            
		    $esig_cover_page=(isset($esig_cover_page) && $esig_cover_page==1)?"checked":"";	
            
             $esig_branding_back_color=$esig_settings->get_generic('esig_branding_back_color');
             
            $template_data=array(
			"ESIGN_ASSETS_DIR_URI"=>ESIGN_ASSETS_DIR_URI,
			"Licenses"=> $esig_general->checking_extension(),
			"misc_tab_class"=>'nav-tab-active',
             "customizztion_more_links"=> $misc_more_actions,
             "message"=>$msg,
             "esig_branding_header_image"=>$esig_branding_header_image,
             "esig_branding_logo_tagline"=>$esig_branding_logo_tagline,
             "esig_branding_footer_text_headline"=>$esig_branding_footer_text_headline,
             "esig_branding_email_footer_text"=>$esig_branding_email_footer_text,
             "esig_brandhing_disable"=> $esig_brandhing_disable,
              "esig_cover_page"=> $esig_cover_page,
             "esig_extra_attr" => $esig_branding_extra,
             "esig_document_head_img"=>$esig_document_head_img,
             "esig_branding_back_color"=>$esig_branding_back_color,
             'esig_head_img_alignment'=>$esig_settings->get_generic('esig_document_head_img_alignment'),
             'esig_email_invitation_sender_checked'=>$esig_settings->get_generic('esig_email_invitation_sender_checked'),
			);

            $branding_template = dirname(__FILE__) ."/view/esig-branding-view.php";
            $api->view->renderPartial('', $template_data, true, '', $branding_template);
		
    }
    
	public function esig_misc_page_more_acitons($misc_more_actions){
    
        $class=(isset($_GET['page']) && $_GET['page']=='esign-upload-logo-branding')?'misc_current':'';
        $misc_more_actions .=' | <a class="misc_link '. $class .'" href="admin.php?page=esign-upload-logo-branding">'.__('White Label Options','esig-ulab').'</a>';
        $class=(isset($_GET['page']) && $_GET['page']=='esign-upload-success-page')?'misc_current':'';
        $misc_more_actions .=' | <a class="misc_link '. $class .'" href="admin.php?page=esign-upload-success-page">'.__('Success Page','esig-ulab').'</a>';
       
         return $misc_more_actions ; 

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

