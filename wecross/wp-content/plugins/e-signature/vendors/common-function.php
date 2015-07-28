<?php


/** 
*  add recipients from edit documents 
*
* Since 1.0.4 
*/

add_action('wp_ajax_addRecipient', 'esig_addRecipient');
add_action('wp_ajax_nopriv_addRecipient', 'esig_addRecipient');
/**
	* Signer edit popup window ajax 
	*
	* Since 1.0.4 
	*/
function esig_addRecipient(){
	
    
	//$documentcontroller=new WP_E_DocumentsController(); 
	
	
	$docmodel = new WP_E_Document();
	$docuser = new WP_E_User();
	$docinvite = new WP_E_Invite();
	
	$doc = $docmodel->getDocument(isset($_POST['document_id']));
	
	// grab the owner of this invitation
	
	$recipients = array();
	$invitations = array();
	$content ='';
	
	$document_id = isset($_POST['document_id'])?$_POST['document_id']: $docmodel->document_max()+1 ; 
	if($docinvite->getInvitationExists($document_id) > 0)
	{
		$docinvite->deleteDocumentInvitations($document_id) ; 
	}
	
	for($i=0; $i < count($_POST['recipient_emails']); $i++){
		
		
		if(!$_POST['recipient_emails'][$i]) continue; // Skip blank emails
		
		
		$user_id = $docuser->getUserID($_POST['recipient_emails'][$i]);

		if(!empty($_POST['recipient_fnames'])) {$fname=$_POST['recipient_fnames']; } else {$fname="";}
		if(!empty($_POST['recipient_lnames'])) {$lname=$_POST['recipient_lnames'] ;} else {$lname="";}
		
		
		$recipient = array(
			"user_email" => $_POST['recipient_emails'][$i],
			"first_name" => $fname[$i],
			"wp_user_id"=>  '0',
			"user_title"=> '',
			"document_id" => $document_id,
			"last_name" => $lname ? $lname[$i] : ''
			);
		
		
		$recipient['id'] = $docuser->insert($recipient);
		
		$invitationsController = new WP_E_invitationsController;
		
		
		$recipients[] = $recipient;
		
		$invitation = array(
			"recipient_id" => $recipient['id'],
			"recipient_email" => $recipient['user_email'],
			"recipient_name" => $recipient['first_name'],
			"document_id" =>$document_id,
			"document_title" => '',
			"sender_name" => '',
			"sender_email" =>'',
			"sender_id" => $_SERVER['REMOTE_ADDR'],
			"document_checksum" => ''
			);
		$invitations[] = $invitation;
		$invitationsController->save($invitation);
		
		$content .= '<p>
					<input type="text" name="recipient_fnames_ajax[]" placeholder="Signers Name" value="'. stripslashes_deep($fname[$i]) .'" readonly />
					<input type="text" name="recipient_emails_ajax[]" placeholder="Signers Email" value="'. $_POST['recipient_emails'][$i] .'" readonly /><a href="#" id="standard_view">Edit</a>';
		$content .= '</p>'; 
	} 
	if(!empty($content))
	echo $content ; 

	die();
} 



/** 
* removing all theme style 
* Since 1.0.7 
*/
function esig_remove_styles() {
	global $wp_styles;
	$current_page = get_queried_object_id();
	global $wpdb;
	
	$table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';
	$default_page=array();
	if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
		$default_page= $wpdb->get_col("SELECT page_id FROM {$table}");
	}
	$setting = new WP_E_Setting();
	$default_normal_page=$setting->get_generic('default_display_page');
	
	$esig_handle= array(
		'jquery-validate',
		'signdoc',
		'signaturepad',
		'page-loader',
		'thickbox',
		'esig-tooltip-jquery',
		'bootstrap',
		'bootstrap-theme',
		);
	// If we're on a stand alone page
	
	if( is_page($current_page) && in_array($current_page,$default_page)){
		foreach( $wp_styles->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_deregister_style($handle);
						wp_dequeue_style($handle);
					}
				}
			}	   
			endforeach;
	}
	else if( is_page($current_page) && $current_page == $default_normal_page){
		foreach( $wp_styles->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_deregister_style($handle);
						wp_dequeue_style($handle);
					}
				}
			}	   
			endforeach;
	}
}
add_action('wp_print_styles', 'esig_remove_styles',100 );
/** 
	* removing all theme scripts
	* Since 1.0.11 
	*/ 
function esig_remove_scripts() {
	global $wp_scripts;
	$current_page = get_queried_object_id();
	global $wpdb;
	
	$table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';
	$default_page=array();
	if($wpdb->get_var("SHOW TABLES LIKE '$table'") == $table) {
		$default_page= $wpdb->get_col("SELECT page_id FROM {$table}");
	}
	$setting = new WP_E_Setting();
	
	$default_normal_page=$setting->get_generic('default_display_page');
	
	$esig_handle= array(
		'jquery-validate',
		'signdoc',
		'jquery',
		'thickbox',
		'signaturepad',
		'page-loader',
		'esig-tooltip-jquery',
		'bootstrap',
		'bootstrap-theme',
		);
	// If we're on a stand alone page
	
	if( is_page($current_page) && in_array($current_page,$default_page)){
		foreach( $wp_scripts->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_dequeue_script($handle);
						
					}
				}
			}		
			endforeach;
	}
	else if( is_page($current_page) && $current_page == $default_normal_page){
		foreach( $wp_scripts->queue as $handle ) :
			if($handle != 'admin-bar'){
				if (strpos($handle,'esig') === false) {
					if(!in_array($handle,$esig_handle)){
						wp_dequeue_script($handle);
					}
				}
			}
			endforeach;
	}
	
}
add_action('wp_print_scripts', 'esig_remove_scripts',100 );

function remove_template(){
	if(has_filter('template_include'))
	remove_all_filters( 'template_include',9999);	// we want this to run after everything else that filters template_include() 
}

/***
 * adding ajax scripts for saving admin role details 
 * Since 1.0.13 
 * */

add_action('wp_ajax_esig_admin_saving','esig_admin_saving_ajax');
add_action('wp_ajax_nopriv_esig_admin_saving','esig_admin_saving_ajax');
function esig_admin_saving_ajax(){
	
	$admin_user_id = $_POST['admin_user_id'];
	//getting settings class 
	$settings=new WP_E_Setting();
	$settings->set('esig_superadmin_user' , $admin_user_id );
	// getting admin environment .php
	$wp_config_write=new WP_E_Adminenvironment();
	$wp_config_write->esign_config_remove_directive();
	$wp_config_write->esign_config_save_directive();
	die();
}

/***
 * adding ajax scripts for getting terms and conditions
 * Since 1.0.13 
 * */

add_action('wp_ajax_esig_terms_condition','esig_terms_condition_ajax');
add_action('wp_ajax_nopriv_esig_terms_condition','esig_terms_condition_ajax');
function esig_terms_condition_ajax(){
	
	$common = new WP_E_Common();
	
	$terms=$common->esig_get_terms_conditions();
	$content_terms = apply_filters('the_content', $terms);
	echo $content_terms;
	die();
}

/***
 * ajax for latest version compare and display out date msg . 
 * Since 1.1.3
 * */

add_action('wp_ajax_esig_out_date_msg','esig_out_date_msg_ajax');
add_action('wp_ajax_nopriv_esig_out_date_msg','esig_out_date_msg_ajax');
function esig_out_date_msg_ajax(){
	
	$common = new WP_E_Common();
	$user = new WP_E_User();
	$admin_user = $user->getUserByWPID(get_current_user_id());   
	$new_version=$common->esig_latest_version();
	
	$old_version = esig_plugin_name_get_version();
	if($new_version){
		
		if( version_compare($old_version, $new_version, '<' ) ){
			echo '<p id="report-bug-radio-button">  '.  $admin_user->first_name .' it looks WP e-Signature is out of date.  Since bugs are often fixed in our newer releases please update your plugin(s) before submitting a bug request</p></div>';
		}else {
			echo 'updateok';
		} 
		
	} else  {
		echo '<p id="report-bug-radio-button">  '.  $admin_user->first_name .', it looks You do not have valid E-signature license. <ol><li>To retreive your license follow these <a href="/wp-admin/admin.php?page=esign-licenses-general">three simple steps</a>.</li><br><li>To renew your license visit <a href="http://www.approveme.me/profile" target="blank">www.approveme.me</a></li><ol></p></div>';
	}
	die();
}


/***
 * ajax for latest version compare and display out date msg . 
 * Since 1.1.3
 * */

add_action('wp_ajax_esig_auto_save','esig_auto_save_ajax');
add_action('wp_ajax_nopriv_esig_auto_save','esig_auto_save_ajax');
function esig_auto_save_ajax(){
	
	//$data=unserialize ( $_POST['formData'] );
	if(!function_exists('WP_E_Sig'))
	return;
	
	$esig = WP_E_Sig();
	$api = $esig->shortcode;
	// var_dump($_POST['formData']);
	parse_str($_POST['formData'], $data);
	
	$document_id = $data['document_id'];
	
	$exists=$api->document->document_exists($document_id);
	$data['document_content'] = $_POST['document_content']; 
	if($exists>0)
	{    
		
		$doc_status=$api->document->getStatus($document_id);
		
		$doc_status=$api->document->getStatus($document_id);
		
		
		
		$api->document->auto_update($data); 
		
		
		
		$api->document->recordEvent($document_id, 'Auto Saved', null, null);
		//echo $data['document_content'];
	}
	else
	{
		$data['document_action']='save';
		$doc_id = $api->document->insert($data);
		$api->document->recordEvent($doc_id, 'Auto Saved', null, null);
	}
	
	$api->setting->set("esig_print_option".$document_id ,$data['esig_print_option']);
	$doc = $api->document->getDocument($document_id);
	
	$recipients = array();
	$invitations = array();
	
	// trigger an action after document save .   
	do_action('esig_document_auto_save', array(
		'document' => $doc,
		'recipients' => $recipients,
		'invitations' => $invitations,
		)); 
	
	die();
}



// this filter has been used to remove esig 
// default page form main navigation menu 

function ep_exclude_esig_default_page($pages,$r) {
	
	$setting = new WP_E_Setting();
	
	$hide_default_page =$setting->get('esig_default_page_hide'); 
	
	if($hide_default_page == 1 ){
		
		$default_display_page =$setting->get('default_display_page'); 
		//for ($i = 0; $i < sizeof($pages); $i++) {
		$i = 0;
		foreach($pages as $page) {
			
			if($default_display_page == $page->ID){
				unset( $pages[$i] );
			}
			
			$i++;
		}
		
	}
	
	return $pages;
	
}

if ( ! is_admin() ) {
	add_filter("get_pages", "ep_exclude_esig_default_page", 100,2);
}

// post type
add_action( 'init', 'esig_create_post_type' );
function esig_create_post_type() {
	register_post_type( 'esign',
		array(
				'labels' => array(
					'name' => __( 'E-signature'),
					'singular_name' => __( 'E-signature')
					),
				'public' => true,
				'show_ui'            => false,
				'show_in_menu'=>'edit.php?post_type=esign',
				'rewrite' => array('slug' => 'esign'),
				)
			);
}

// apply bull action start here 
function esig_apply_bulk_action(){

	$screen = get_current_screen();
	$current_screen = $screen->id;
	
	$admin_screens = array(
		
		'toplevel_page_esign-docs',
		
		);
	
	// bulk action submit .
	if (in_array($screen->id, $admin_screens)) {
		if(isset($_POST['esigndocsubmit']) && $_POST['esigndocsubmit']=='Apply'){
			
			$apidoc = new WP_E_Document();
			
			if(isset($_POST['esig_bulk_option'])){
				
				// trash start here 
				
				if($_POST['esig_bulk_option'] == 'trash'){
					
					for($i=0; $i < count($_POST['esig_document_checked']); $i++){
						$document_id =$_POST['esig_document_checked'][$i] ;
						
						$apidoc->trash($document_id);
					}
				}
				
				// permanenet delete start here 
				if($_POST['esig_bulk_option'] == 'del_permanent'){
					
					for($i=0; $i < count($_POST['esig_document_checked']); $i++){
						$document_id =$_POST['esig_document_checked'][$i] ;
						
						$apidoc->delete($document_id);
					}
				}
				
				// restore start here 
				if($_POST['esig_bulk_option'] == 'restore'){
					
					for($i=0; $i < count($_POST['esig_document_checked']); $i++){
						$document_id =$_POST['esig_document_checked'][$i] ;
						
						$apidoc->restore($document_id);
					}
				}
			}
		}
	}
	
}

add_action('esig-init','esig_apply_bulk_action');

//Add "esig" Prefix to ALL Alert messages and only display our own messages #258
function remove_admin_header_footer(){
	$admin_screens = array(
		'esign-add-document',
		'esign-settings',
		'esign-edit-document',
		'esign-view-document',
		'esign-misc-general',
		'esign-unlimited-sender-role',
		'esign-docs',
		'esign-systeminfo-about',
		'esign-addons-general',
		'esign-about',
		'esign-licenses-general',
		'esign-support-general',
		'esign-upload-logo-branding',
		'esign-upload-success-page',
		'esign-addons'
		);
	$current_screen =isset($_GET['page'])?$_GET['page']:'';
	if (in_array($current_screen, $admin_screens)) {
		remove_all_actions('admin_footer',10);
		remove_all_actions('admin_header',10);
	}
}
add_action('esig-init','remove_admin_header_footer');

// doing shortcode for esignagture user list

add_shortcode( 'esig-email-list', 'esig_email_list_shortcode');

function esig_email_list_shortcode()
{
	
	
	global $woocommerce;
	
	extract(shortcode_atts(array(
		
		), $atts, 'esig-email-list'));

	$this_user = new WP_E_User();
	
	$users=$this_user->fetchAll();
	
	$html ='<table border="1">';
	$html .='<tr><td>Firstname<td><td>E-mail<td></tr>';
	foreach($users as $user)
	{
		$html .='<tr><td>'. $user->first_name .'<td><td>'. $user->user_email .'<td></tr>';
	}
	$html .='</table>';
	
	return $html ;
	
}

// Esignature page break shortcode for print and pdf page.
add_shortcode( 'esig-page-break','esig_page_break');
function esig_page_break()
{
	extract(shortcode_atts(array(
		
		), $atts, 'esig-page-break'));

	
	$html ='<div style="page-break-after:always"></div>';
	
	return $html ; 
}

/***
 * return true if current user is super admin
 * return bool
 * Since 1.0.13 
 * */
function is_esig_super_admin(){
	
	$settings = new WP_E_Setting();
	$wp_user_id = get_current_user_id();
	$admin_user_id=$settings->get_generic('esig_superadmin_user');
	
	if($wp_user_id == $admin_user_id)
	{
		return true ; 
	}
	else 
	{
		return false ; 
	}
}

function esig_document_tail_filter($loop_tail, $args)
{
    
	$current_screen =isset($_GET['page'])?$_GET['page']:'';
	
	$signature_screens = array(
			'esign-add-document',
			'esign-settings',
			'esign-edit-document',
		    'esign-docs',
		    'esign-view-document'
			);
       
	
		if (!in_array($current_screen, $signature_screens)) 
		{
			return $loop_tail; 
		}
	
	if (!function_exists('WP_E_Sig'))
			return $loop_tail; 
					
		$esig = WP_E_Sig(); 
	
		$api = $esig->shortcode;
		$settings = new WP_E_Setting();
		
		if(!$settings->esign_super_admin())
		{ 
			return $loop_tail ;
		}
		
		if(get_transient('esign-update-remind'))
		{
		    return $loop_tail;
		}
		
		if(get_option('esig-core-update'))
		{
			$esig_view = new WP_E_View();
			$template_data=array(
			"ESIGN_ASSETS_DIR_URI"=>ESIGN_ASSETS_DIR_URI,
			
			);

			$document_tail= ESIGN_PLUGIN_PATH . "/views/about/update-core.php";
			$loop_tail .=$esig_view->renderPartial('', $template_data, false, '', $document_tail);
			return $loop_tail; 
		}
		
		$esign_auto_update =$settings->get_generic("esign_auto_update");
		if(isset($esign_auto_update) && !empty($esign_auto_update) )
		{
			return $loop_tail ;
		}
		
		
		if(!get_transient('esign-auto-downloads'))
		{
			 return $loop_tail; 
		}
		$esig_license =$settings->get_generic("esig_wp_esignature_license_active"); 
		
		if(empty($esig_license) || $esig_license == 'invalid')
		{
			return $loop_tail; 
		}
		if(!get_transient('esign-update-list'))
		{
			 return $loop_tail; 
		}
		else
		{
			$esig_view = new WP_E_View();
			$template_data=array(
			"ESIGN_ASSETS_DIR_URI"=>ESIGN_ASSETS_DIR_URI,
			
			);

			$document_tail= ESIGN_PLUGIN_PATH . "/views/about/update.php";
			$loop_tail .=$esig_view->renderPartial('', $template_data, false, '', $document_tail);
			return $loop_tail; 
		}
	
}

add_filter('esig-document-index-footer','esig_document_tail_filter',10,2);
add_filter('esig-document-footer-content','esig_document_tail_filter',10,2);

/**

*/
function esig_auto_update()
{
	// determine screen 
	/*$current_screen =isset($_GET['page'])?$_GET['page']:'';
	$signature_screens = array(
			'esign-settings',
		    'esign-docs',
			);
       
		if (!in_array($current_screen, $signature_screens)) 
		{
			 return ;
		} */
	
	 if ( !current_user_can( 'install_plugins' ) ) 
	 {
		 return ;
	 }
	 
	 
	 
	if (!function_exists('WP_E_Sig'))
					return ;
					
		$esig = WP_E_Sig(); 
	
		$api = $esig->shortcode;
		$settings = new WP_E_Setting();
		
		if(!$settings->esign_super_admin())
		{ 
			return  ;
		}
		$esig_license =$settings->get_generic("esig_wp_esignature_license_active"); 
		
		if(empty($esig_license) || $esig_license == 'invalid')
		{
			return ; 
		}
		if(!get_transient('esign-auto-downloads'))
		{
			 return ;
		}
		if(!get_transient('esign-update-list'))
		{
			 return ;
		}
		else
		{
			$esign_auto_update =$settings->get_generic("esign_auto_update");
			 $install_now = isset($_GET['esig-auto'])?$_GET['esig-auto'] : null ; 
			 if($install_now == 'now')
			 {
				 $esign_auto_update='yes';
			 }
			$auto_downloads =get_transient('esign-auto-downloads');			    
			if(isset($esign_auto_update) && !empty($esign_auto_update) )
			{
				$plugin_list=json_decode(get_transient('esign-update-list'));
				$esign_addon=new WP_E_Addon();
				foreach($plugin_list as $plugin)
				{
					   if(array_key_exists($plugin->addon_id,$auto_downloads))
					   {
						  $plugin_root_folder= trim($plugin->download_name, ".zip");
                   
						   $plugin_file = $esign_addon->esig_get_addons_file_path($plugin_root_folder);
						   
						  $installed= $esign_addon->esig_addons_update($plugin->download_link,$plugin_file); 
						 
						  if($installed)
						  {	
							
							  // after installing updates it unset from auto install
							  unset($auto_downloads[$plugin->addon_id])	;
							  delete_transient('esign-auto-downloads');
    						  set_transient('esign-auto-downloads',$auto_downloads,60*60*1);
						  }
					   }
				}
				// redirect same page after updating. 
				
			}
			
			
		}
}

add_action('shutdown','esig_auto_update');


 function esig_update_progress_content()
 {
	 if ( !current_user_can( 'install_plugins' ) ) 
	 {
		 return ;
	 }
	if(!get_transient('esign-auto-downloads'))
	{
			 return ;
	}
	$settings = new WP_E_Setting();
	$esign_auto_update =$settings->get_generic("esign_auto_update");
    $install_now = isset($_GET['esig-auto'])?$_GET['esig-auto'] : null ; 	
	if(isset($esign_auto_update) && !empty($esign_auto_update) )
	{
	  include_once ESIGN_PLUGIN_PATH . "/views/about/progress-bar.php";
	  //do_action('esig_run_update');
	}
	elseif($install_now == 'now')
	{
		 include_once ESIGN_PLUGIN_PATH . "/views/about/progress-bar.php";
	}
 }
 
 add_action('all_admin_notices','esig_update_progress_content');

/***
 * ajax for latest version compare and display out date msg .
 * Since 1.1.3
 * */

add_action('wp_ajax_esig_update_remind_settings','esig_update_remind_settings');
add_action('wp_ajax_nopriv_esig_update_remind_settings','esig_update_remind_settings');

function esig_update_remind_settings()
{
    
    if(!get_transient('esign-update-remind'))
    {
        set_transient('esign-update-remind','esig-remind',60*60*72);
    }
    else
    { 
        delete_transient('esign-update-remind');
        set_transient('esign-update-remind','esig-remind',60*60*72);
    }
    
    die();
    
}

/***
 * ajax for latest version compare and display out date msg .
 * Since 1.1.3
 * */

add_action('wp_ajax_esig_update_auto_settings','esig_update_auto_settings');
add_action('wp_ajax_nopriv_esig_update_auto_settings','esig_update_auto_settings');

function esig_update_auto_settings()
{

    $settings = new WP_E_Setting();
    $settings->set("esign_auto_update","1");

    die();

}

