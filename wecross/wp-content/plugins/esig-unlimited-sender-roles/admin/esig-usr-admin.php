<?php
/**
 *
 * @package ESIG_AAMS_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_USR_Admin')) :
class ESIG_USR_Admin {

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
		$plugin = ESIG_USR::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		
		// usr action 
		add_action('admin_menu', array(&$this, 'esig_usr_adminmenu'));
		
		//usr filter 
		add_filter('esig-document-index-data', array($this, 'esign_unlimited_sender_role_tab'), 10, 1);
		add_filter('esig-settings-tab-data', array($this, 'esign_unlimited_sender_role_tab'), 10, 1);
		add_filter('esig-license-tab-data', array($this, 'esign_unlimited_sender_role_tab'), 10, 1);
		add_filter('esig-addons-tab-data', array($this, 'esign_unlimited_sender_role_tab'), 10, 1);
		add_filter('esig-support-tab-data', array($this, 'esign_unlimited_sender_role_tab'), 10, 1);
		add_filter('esig-misc-form-data', array($this, 'esign_unlimited_sender_role_tab'), 10, 1);
		add_filter('esig-sender-roles-permission', array($this, 'document_template_allow'), 10, 1);
		add_filter('esig_plugin_access_control',array($this,'esign_unlimited_access_control'));
		
        add_filter('esig_user_role_filter',array($this,'esign_unlimited_role_control'),10,1);
		// imporatant filter 
		add_filter('esig_documents_search_filter',array($this,'esig_document_search_filter'));
		
		add_filter('esig-template-option',array($this,'esig_template_role'));
		
		add_action('esig_document_after_save', array($this, 'esig_document_after_save_data'), 10, 1);
		
        if(function_exists('WP_E_Sig')){
        
            $esig_settings = new WP_E_Setting();
		    //define array 
		    if(!$esig_settings->esign_super_admin()){
                  add_filter( 'user_has_cap', array($this,'give_permissions'), 0, 3 );
            }
        }
	}
	
	public function esig_document_after_save_data($args)
	{
		global $wpdb;
		
		$doc_id= $args['document']->document_id;
		
		
		
		if(!function_exists('WP_E_Sig'))
				return ;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		$document_status = $api->document->getStatus($doc_id);
		
		if($document_status !='esig_template')
		{
			$doc_id = $doc_id + 1 ; 
		}
		
		//calling esignature setings class to save data in settings table 
		$esig_settings = new WP_E_Setting();
		$msg='';
		if(count($_POST) > 0){
			
			// defining two array for user roles option 
			$esig_roles_option=array();
			$esig_roles_user_option=array();
			// recieving esig roles option in array 
			if(isset($_POST['esig_roles_option_template'])) {
				foreach ($_POST['esig_roles_option_template'] as $key => $value)
				{
					$esig_roles_option[$key]=$value; 
				}
			}
			// recieving user roles option in array 
			if(isset($_POST['esig_roles_user_option_template'])) {
				foreach ($_POST['esig_roles_user_option_template'] as $key => $value)
				{
					$esig_roles_user_option[$key]=$value; 
				}
			}
			
			// saving roles option 
			$esig_settings->set('esig_unlimited_roles_'.$doc_id,json_encode($esig_roles_option));
			// saving user roles option 
			$esig_settings->set('esig_unlimited_users_'.$doc_id,json_encode($esig_roles_user_option));
			// saving hide settings 
		} 
		
		
	}
	
	public function esig_template_role()
	{
		if(!function_exists('WP_E_Sig'))
		return;
		
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		$esig_settings = new WP_E_Setting();
		
		$esig_general = new WP_E_General();
		

		//defining role option 
		//getting roles option from settings table 
		$esig_unlimited_roles_option=json_decode($esig_settings->get_generic('esig_unlimited_roles_option')) ;
		if($esig_unlimited_roles_option==null){
			$esig_unlimited_roles_option=array();    
		}
		global $wp_roles;
		$all_roles = $wp_roles->roles;
		$role_options='';
		foreach (get_editable_roles() as $role_name => $role_info){
			$role_options .= '<option value="'. $role_name .'" '; if(in_array($role_name,$esig_unlimited_roles_option)) $role_options .= "selected"; $role_options .='> ' . $role_name . '</option>' ; 
		} 
		
		
		// displaying users roles option 	
		$users_role_option=''; 
		$wp_users = get_users();
		//getting users roles option from settings table 
		
		$esig_unlimited_users_option=json_decode($esig_settings->get_generic('esig_unlimited_users_option')) ;
		if($esig_unlimited_users_option==null){
			$esig_unlimited_users_option=array();    
		}
		foreach($wp_users as $key => $user){
			$users_role_option .= '<option value="'. $user->ID .'" '; if(in_array($user->ID,$esig_unlimited_users_option)) $users_role_option .= "selected"; $users_role_option .='> ' . $user->display_name . '</option>' ; 
		}
		
		$html ='<table class="form-table">
	<tbody>
		
		<tr>
			<td width="20px"><a href="#" class="tooltip">

					<img src="'. ESIGN_ASSETS_DIR_URI .'/images/help.png" height="20px" width="20px" align="left" />

					<span>' .
			__('This option lets you easily give document sending permissions to all users of an entire WordPress user role.','esig-usr') . '
					</span>
					</a></td>
			<td>
					
					<p class="esig-chosen-drop">
					
					
					<label><b>' .  __('Select one (or multiple) ROLES that can access this template','esig-usr') . '</b></span></label>

						<select name="esig_roles_option_template[]" style="width:500px;" tabindex="9" data-placeholder="Choose a Option..." multiple class="esig-select2">

							' . $role_options . '

							
						</select> 	
					</p>	
				
	   			
			</td>
		</tr>

		<tr>
			<td width="20px"><a href="#" class="tooltip">

					<img src="' . ESIGN_ASSETS_DIR_URI . '/images/help.png" height="20px" width="20px" align="left" />

					<span>
					' . __('Want to give document sending access to one single user?  This is space to turn that dream into reality!','esig-usr') . '
					</span>
					</a></td>
					<td>
					
					<p class="esig-chosen-drop">
					<label><b>' . __('Select one (or multiple) USERS that can access this template') . '</b></span></label>

						<select name="esig_roles_user_option_template[]" style="width:500px;" tabindex="9" data-placeholder="' . __('Choose a Option...','esig-usr') . '" multiple class="esig-select2">
									  

							' . $users_role_option . '

							
						</select> 	
					</p>	
				
	   			
			</td>
		</tr>
		
		
	</tbody>
</table>

';
	return $html ; 	
	
	}
    
    public function remove_menu () 
     {
           remove_menu_page('edit.php');
          remove_menu_page( 'tools.php' ); 
          remove_menu_page( 'admin.php' ); 
          remove_menu_page( 'edit-comments.php' );
          remove_menu_page( 'post-new.php' ); 
          
     } 
     
    public function remove_wp_nodes() 
        {
            global $wp_admin_bar;   
            $wp_admin_bar->remove_node( 'new-content' );
            
        }
	public function give_permissions( $allcaps, $cap, $args ) {
	   
       if(!function_exists('WP_E_Sig'))
					return;
		//calling esignature setings class to save data in settings table 
		$esig_settings = new WP_E_Setting();
		//define array 
		
		
		$esign_unlimited_roles=json_decode($esig_settings->get_generic('esig_unlimited_roles_option'));// getting users from settings table 
		$wp_user_id=get_current_user_id(); // getting current wp user id 
		$esign_unlimited_users=json_decode($esig_settings->get_generic('esig_unlimited_users_option'));// getting users from settings table 
		if($esign_unlimited_users == null && $esign_unlimited_roles== null){
			return $allcaps; 
		}
		if(in_array($wp_user_id,$esign_unlimited_users)){
			    add_thickbox();
              $allcaps['edit_posts'] = true;
             $user_role = $this->get_current_user_role();
              if($user_role == "subscriber"){
                add_action( 'wp_before_admin_bar_render',array($this, 'remove_wp_nodes'),999);
                  add_action('admin_menu', array($this,'remove_menu'));
                 
                  }
	          return $allcaps;
			 
		} else {
			$user_role = $this->get_current_user_role();
			if(in_array($user_role,$esign_unlimited_roles)){
                add_thickbox();
                 $allcaps['edit_posts'] = true;
                
                  if($user_role == "subscriber"){
                    add_action( 'wp_before_admin_bar_render',array($this, 'remove_wp_nodes'),999);
                  add_action('admin_menu', array($this,'remove_menu'));
                 
                  }
	             return $allcaps;
			} else {
				// if not found this role return false .
				 return $allcaps;
			}
		} 
		 return $allcaps;
       
    }
	
	public function document_template_allow($document_id)
	{
		if(!function_exists('WP_E_Sig'))
				return;
		//calling esignature setings class to save data in settings table 
		$esig_settings = new WP_E_Setting();
		//define array 
		
		
		
		$esign_unlimited_roles=json_decode($esig_settings->get_generic('esig_unlimited_roles_'.$document_id));// getting users from settings table 
		
		$wp_user_id=get_current_user_id(); // getting current wp user id 
		$esign_unlimited_users=json_decode($esig_settings->get_generic('esig_unlimited_users_'.$document_id));// getting users from settings table 
		
		if(is_array($esign_unlimited_users) && in_array($wp_user_id,$esign_unlimited_users)){
			echo "ok";
			 return true ; 
			
		} else {
			$user_role = $this->get_current_user_role();
			if(is_array($esign_unlimited_roles) && in_array($user_role,$esign_unlimited_roles)){
				return true ;
			} else {
				// if not found this role return false .
				return false;
			}
		} 
		return false ; 
		
	}
	public function esig_document_search_filter()
	{
		if(!function_exists('WP_E_Sig'))
				return;
		//calling esignature setings class to save data in settings table 
		$esig_settings = new WP_E_Setting();
		$admin_user_id=$esig_settings->get_generic('esig_superadmin_user');
		$wp_user_id=get_current_user_id(); // getting current wp user id
		$all_sender = null ; 
		if($admin_user_id == $wp_user_id){
			$wp_users = get_users();
			//getting users roles option from settings table
			$all_sender = '<select name="esig_all_sender" id="esig_document_search" style="min-width:150px;">'; 
			$all_sender .='<option value="All Sender" selected>'.__('All Sender','esig-usr').'</option>';
			foreach($wp_users as $key => $user){
				$all_sender .= '<option value="'. $user->ID .'" > ' . $user->display_name . '</option>' ; 
			} 
			$all_sender .='</select>';
		}
		return $all_sender ; 
	}
	
	public function esign_unlimited_role_control($cap){
		
		if(!function_exists('WP_E_Sig'))
					return;
		//calling esignature setings class to save data in settings table 
		$esig_settings = new WP_E_Setting();
		//define array 
		
		
		$esign_unlimited_roles=json_decode($esig_settings->get_generic('esig_unlimited_roles_option'));// getting users from settings table 
		$wp_user_id=get_current_user_id(); // getting current wp user id 
		$esign_unlimited_users=json_decode($esig_settings->get_generic('esig_unlimited_users_option'));// getting users from settings table 
		
        if($esign_unlimited_users == null || $esign_unlimited_roles== null){
        
           
			return true ; 
		}
         
		if(is_array($esign_unlimited_users) && in_array($wp_user_id,$esign_unlimited_users)){
			//if found then check role 
			if($cap == "edit_document"){
					return true ;
			}elseif($cap == "view_document"){
				return true ;
			}
			 
		} else {
			$user_role = $this->get_current_user_role();
			if(is_array($esign_unlimited_roles) && in_array($user_role,$esign_unlimited_roles)){
				//if found this role return true 
				if($cap == "edit_document"){
					return true ;
				}elseif($cap == "view_document"){
					return true ;
					}
			} else {
				// if not found this role return false .
				return false;
			}
		} 
		return false ;  
	}
	
	/**
	 * This is method get_current_user_role
	 *
	 * @return mixed This is the return value description
	 *
	 */
		
	public function get_current_user_role() {
		global $current_user;

		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);

		return $user_role;
	}
	
	/**
	 * This is method esign_unlimited_access_control
	 * return allow if signer has been given access . 
	 * @return mixed This is the return value description
	 *
	 */	
	public function esign_unlimited_access_control(){
		
		if(!function_exists('WP_E_Sig'))
					return;
		//calling esignature setings class to save data in settings table 
		$esig_settings = new WP_E_Setting();
		$esign_unlimited_users=json_decode($esig_settings->get_generic('esig_unlimited_users_option'));// getting users from settings table 
		$esign_unlimited_roles=json_decode($esig_settings->get_generic('esig_unlimited_roles_option'));// getting users from settings table 
		$wp_user_id=get_current_user_id(); // getting current wp user id 
		$user_role = $this->get_current_user_role();
        
		if(is_array($esign_unlimited_users) && in_array($wp_user_id,$esign_unlimited_users)){
			
			return "allow" ;
		}
		elseif(is_array($esign_unlimited_roles) && in_array($user_role,$esign_unlimited_roles)){
			return "allow" ;
		}
		else {
			return "deny" ;	
		}
		
		 
	}
	
	

	/**
	 * This is method esign_unlimited_sender_role_tab
	 *  filter template data for tab 
	 * @param mixed $template_data This is a description
	 * @return mixed This is the return value description
	 *
	 */	
	public function esign_unlimited_sender_role_tab($template_data){
		
        if(!function_exists('WP_E_Sig'))
				return;
			
		$esig = WP_E_Sig();
        
		$api = $esig->shortcode;
        
        if(!$api->setting->esign_super_admin()){
                return $template_data ; 
        }
        
        $esigrole = new WP_E_Esigrole();
       
		// checking user have access or not 
        
		if(!$esigrole->esig_current_user_can('have_licenses')){
			return $template_data ; 
		}
		
		if(isset($_GET['page']) && $_GET['page'] =="esign-unlimited-sender-role"){
			$css_class="nav-tab-active";
		} else {
			$css_class= " ";
		}
		
		$html_data ='<a class="nav-tab '. $css_class .'" href="?page=esign-unlimited-sender-role">Roles</a>' ; 
		
		$template_data['esig_more_tab']=$html_data ; 
		
		return $template_data ; 
	}
    
    
	
	/**
	 * This is method esig_usr_adminmenu
	 *   Create a admin menu for esinature roles . 
	 * @return mixed This is the return value description
	 */    
	public function esig_usr_adminmenu(){
		
			if(!function_exists('WP_E_Sig'))
				return;
		$esigrole = new WP_E_Esigrole();
		if($esigrole->esig_current_user_can('have_licenses')){
			add_submenu_page(null, 'Esig Unlimited Sender Role', 'Esig Unlimited Sender Role', 'read','esign-unlimited-sender-role', array(&$this, 'esign_unlimited_sender_role_view'));	
		}
	}
	/**
	 *  this is method esig_unlimited_sender_role_view
	 *   view admin page . 
	 *   @since 1.0.0
	 *   @return null 
	 **/
	
	public function esign_unlimited_sender_role_view(){
		
		if(!function_exists('WP_E_Sig'))
				return;
			
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		//calling esignature setings class to save data in settings table 
		$esig_settings = new WP_E_Setting();
		$msg='';
		if(count($_POST) > 0){
			
			// defining two array for user roles option 
			$esig_roles_option=array();
			$esig_roles_user_option=array();
			// recieving esig roles option in array 
			if(isset($_POST['esig_roles_option'])) {
				foreach ($_POST['esig_roles_option'] as $key => $value)
				{
					$esig_roles_option[$key]=$value; 
				}
			}
			// recieving user roles option in array 
			if(isset($_POST['esig_roles_user_option'])) {
				foreach ($_POST['esig_roles_user_option'] as $key => $value)
				{
					$esig_roles_user_option[$key]=$value; 
				}
			}
			
			// saving roles option 
			$esig_settings->set('esig_unlimited_roles_option',json_encode($esig_roles_option));
			// saving user roles option 
			$esig_settings->set('esig_unlimited_users_option',json_encode($esig_roles_user_option));
			// saving hide settings 
			
			$msg .= '<div class="alert e-sign-alert esig-updated"><div class="title"></div><p class="message"><strong>'.__('Well done sir','esig-usr').'</strong> :  '.__('Your E-Signature roles have been updated!','esig-usr').'</p></div>';
		} 
			
		
		$esig_general = new WP_E_General();
        
		$esig_whisker=new WP_E_View();
       
        $data['ESIGN_ASSETS_DIR_URI'] = ESIGN_ASSETS_DIR_URI;
		$data['ESIGN_PLUGIN_PATH'] = ESIGN_PLUGIN_PATH;
        $data['Licenses']= $esig_general->checking_extension();
        $data = apply_filters('esig-license-tab-data', $data);
        $esig_whisker->render('partials','_tab-nav',$data);	
		//defining role option 
		//getting roles option from settings table 
		$esig_unlimited_roles_option=json_decode($esig_settings->get_generic('esig_unlimited_roles_option')) ;
		if($esig_unlimited_roles_option==null){
                $esig_unlimited_roles_option=array();    
        }
		global $wp_roles;
		$all_roles = $wp_roles->roles;
		$role_options='';
		foreach (get_editable_roles() as $role_name => $role_info){
			$role_options .= '<option value="'. $role_name .'" '; if(in_array($role_name,$esig_unlimited_roles_option)) $role_options .= "selected"; $role_options .='> ' . $role_name . '</option>' ; 
		} 
		
			
		// displaying users roles option 	
		$users_role_option=''; 
		$wp_users = get_users();
		//getting users roles option from settings table 
       
		$esig_unlimited_users_option=json_decode($esig_settings->get_generic('esig_unlimited_users_option')) ;
		if($esig_unlimited_users_option==null){
                $esig_unlimited_users_option=array();    
        }
        foreach($wp_users as $key => $user){
			$users_role_option .= '<option value="'. $user->ID .'" '; if(in_array($user->ID,$esig_unlimited_users_option)) $users_role_option .= "selected"; $users_role_option .='> ' . $user->display_name . '</option>' ; 
		}
       
		// getting esign hide data 
		$esign_hide=$esig_settings->get_generic('esig_unlimited_hide_settings');
        
		$esign_hide_checked=(isset($esign_hide) && $esign_hide==1)?"checked":"";	
		// setting data
			
		$template_data=array(
			"ESIGN_ASSETS_DIR_URI"=>ESIGN_ASSETS_DIR_URI,
			
			"roles_option" => $role_options ,
			"user_roles_option" => $users_role_option,
			"message"=>$msg
			);
		
		//getting roles tab 
		$template_data=$this->esign_unlimited_sender_role_tab($template_data);
       
		include_once( dirname(__FILE__) ."/view/esig-role-view.php");
		
       
		
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

