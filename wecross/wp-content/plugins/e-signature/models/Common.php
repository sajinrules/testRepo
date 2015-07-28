<?php


class WP_E_Common extends WP_E_Model {
	
	public function __construct()
	{
		parent::__construct();
		
		$this->settings = new WP_E_Setting();
		$this->document = new WP_E_Document();
		// adding action 
		
	}
	
	public function esig_document_search_form()
	{
		$html = '<form id="esig_document_search_form"  name="esig_document_search_form" action="" method="post"> ';
		
		$html .= apply_filters('esig_documents_search_filter','');
		
		$html .='<input type="text" class="esig_document_search" name="esig_document_search" style="min-width:250px;" placeholder="Document title or Signer name" value="">
		
		<input type="submit" name="esig_search" class="button-primary" value="Search">
		</form>';
		
		return $html ; 
	}
	
	
	/***
	 * adddmin admin user from to set admin role . 
	 * Since 1.0.13 
	 * */
	public function esig_user_admin_dialog()
	{
		// previewing admin user settings dialog if doc super admin false . 
		if(ESIGN_DOC_SUPERADMIN_USERID === FALSE)
		{
			wp_enqueue_script('jquery-ui-dialog');
			add_thickbox();
			_e("<div id='esig_show_dialog' style='display:none;'>
					<div class='esig-show-dialog-content'>
					<h3>Setup your E-signature Admin</h3>
					<p>Select User:<select name='esig_admin_user' id='esig_admin_user'>
					", 'esig');
					
			$blogusers = get_users();
			
			foreach ($blogusers as $buser) 
			{
				echo '<option value="'. $buser->ID .'">'. $buser->display_name .' </option>';	
			}
			
			echo "	</select></p>
					</div>
				</div>";
		}
	}
	
	/***
	 * Saving administrator from settings 
	 * Since 1.0.13 
	 * 
	 * */
	public function esig_save_administrator()
	{
		
		$wpipd=get_current_user_id();
		
		if(count($_POST)>0)
		{
			if(isset($_POST['esig_admin_user_id']))
			{
				$admin_user_id = $_POST['esig_admin_user_id'];
				//getting settings class 
				
				$this->settings->set('esig_superadmin_user' , $admin_user_id );
				// getting admin environment .php
			
				if($wpipd!=$admin_user_id)
				{
					wp_redirect("admin.php?page=esign-docs");	
					exit ; 
				}
			}
			
		} 
			
		$admin_user_id=$this->settings->get_generic('esig_superadmin_user');
		$html = '' ; 
		if($wpipd == $admin_user_id || $admin_user_id==null)
		{
			
			$html = "<select name='esig_admin_user_id' class='esig-select2' style='width:288px;'>
					";		
			$blogusers = get_users();
			foreach ($blogusers as $buser) 
			{
				if($buser->ID == $wpipd)
				{
					$selected = "selected " ;	
				}
				else 
				{
					$selected = " ";
				}
				$html .='<option value="'. $buser->ID .'" data-used="'. $wpipd . '" '. $selected .'>'. $buser->user_login .' </option>';	
			}
			$html .="</select>";
			return $html ; 
		}
		else 
		{
			// not super admin return plain text 
			$user_info = get_userdata($admin_user_id);
			$html .='<b>'. $user_info->display_name .'</b>' ;
			return $html ; 
		}
		
		// checking esig settngs table for already defined super admin . 				  
	}
    /**
	 * Activate the license key
	 *
	 * @access  public
	 * @return  void
	 */
	public function esig_get_terms_conditions() 
	{
	  
			if (!function_exists('WP_E_Sig'))
					return ;
					
			     $esig = WP_E_Sig();
		
       
		$api_url = 'https://www.approveme.me';
		// Data to send to the API
		$api_params = array(
			'esig_action_terms' => 'esig_get_terms',
		);
       
		// Call the API
		$response = wp_remote_get(
			esc_url_raw(add_query_arg( $api_params, $api_url)),
			array(
				'timeout'   => 15,
				'body'      => $api_params,
				'sslverify' => false
			)
		);
      
		// Make sure there are no errors
		if ( is_wp_error( $response ) )
			     return;

		// Decode license data
		$condition_data= json_decode( wp_remote_retrieve_body( $response ) );
       
	    return $condition_data->terms_content;
     
	}
    /***
    * Return bult action form element
    * @Since 1.1.3
    */
    
    public function esig_bulk_action_form()
	{
	
        $screen = get_current_screen();
		$current_screen = $screen->id;
        
        $admin_screens = array(
			
			'toplevel_page_esign-docs',
          
		);
        
        
        
        $html='';
	    if (in_array($screen->id, $admin_screens)) 
	    {
		    $html .='<select name="esig_bulk_option" id="bulk-action-selector-top">
            <option value="-1" selected="selected">Bulk Actions</option>';
	        if(isset($_GET['document_status']) && $_GET['document_status']=="trash"){
	        $html .='<option value="restore">Restore Again</option>';
            $html .='<option value="del_permanent">Delete Permanently</option>';
            } else {
              $html .='<option value="trash">Move to Trash</option>';
            }
           
            $html .=' </select><input type="submit" name="esigndocsubmit" id="esig-action" class="button action" value="Apply"  />';
        }
		return $html ; 
        
	}
    
    public function esig_latest_version() 
    {

		global $wp_version;

		  if (!function_exists('WP_E_Sig'))
					return ;
					
			     $esig = WP_E_Sig(); 
		            
		$api_params = array(
			'edd_action' 	=> 'get_version',
			'license' 		=> trim( $esig->setting->get_generic('esig_wp_esignature_license_key')),
			'name' 			=> 'WP E-Signature',
			'slug' 			=>  basename( ESIGN_PLUGIN_PATH, '/e-signature.php'),
			'author'		=> 'Approve Me'
		);
        
		$request = wp_remote_post('http://www.approveme.me/', array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		if ( !is_wp_error( $request ) ):
			$request = json_decode( wp_remote_retrieve_body( $request ) );
			if( $request )
				$request->sections = maybe_unserialize( $request->sections );
            
            $esig->setting->set('esig_new_update_version',$request->new_version); 
            
			return $request->new_version;
		else:
			return false;
		endif;
	}
    
    /***
    *  report bug form 
    *  @Since 1.1.3
    */
     public function esig_report_bug_form()
     {
     
            $assets_dir=ESIGN_ASSETS_DIR_URI;
            
             $user = new WP_E_User();
             
                $admin_user = $user->getUserByWPID(get_current_user_id());
                if($admin_user)
                {
                    $first_name =$admin_user->first_name ;
                    $user_email = $admin_user->user_email;
                }
                else 
                {
                    $first_name ='' ;
                    $user_email = '';
                }
                $newabout = new WP_E_aboutsController();
                $view = new WP_E_View();
                $systeminfo_data =$newabout->systeminfo(true);
                $sytem_template = ESIGN_PLUGIN_PATH ."/views/about/systeminfo.php";
                $system_result =$view->renderPartial('', $systeminfo_data, false, '', $sytem_template);
            // report bug form part 1
                 $report_bug_html = '<div id="report_bug_loading" style="display:none;"><br><br><h1>Loading...</h1></div>
                 <div id="report-bug-step1" class="esign-form-panel" style="display:none;">
			
				<span class="invitations-container">	

				<div align="center"><img src="' . $assets_dir .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
					<h2 class="esign-form-header">'.__('What Would You Like To Do?', 'esig').'</h2>

				</span>';
                $setting = new WP_E_Setting();
                $license_valid = $setting->get_generic('esig_wp_esignature_license_active');
                
                if($license_valid  == "valid")
                {
                
                
    				 $report_bug_html .= '<p id="report-bug-radio-button" style="margin-left:35%;">
    				<input type="radio" name="esig_report_bug_type" value="bug"> Report a Bug</br>
                        <input type="radio" name="esig_report_bug_type" value="ticket"> Open a Support Ticket</br>
                        <input type="radio" name="esig_report_bug_type" value="idea"> Submit an Idea!</br>
    				</p>';
                     $report_bug_html .= '<p id="report_bug_button" align="center">
    					<a href="#" id="esig_report_bug_upload" class="button-primary esig-button-large">'.__('Next Step', 'esig').'</a>	
    				</p></div> 
    				' ;
			    } 
			    else 
			    {
                
                    $report_bug_html .= '<p id="report-bug-radio-button">  ';  if($admin_user){ $report_bug_html .= $admin_user->first_name ;} 
                    $report_bug_html .= ' it looks You do not have WP e-Signature valid license .  Please purchase a valid license first. <a href="http://www.approveme.me/#pricingPlans/" target="_blank">Buy Now</a></p></div>';
                
                }
				
               
        // report bug step two start here 
                    $report_bug_html .= '<div id="report-bug-step-bug" class="esign-form-panel" style="display:none;">
			
                     <form action="//approve.activehosted.com/proc.php" method="post" name="_form_281" id="_form_281" accept-charset="utf-8" enctype="multipart/form-data">
				
                     <input type="hidden" name="f" value="281">
                      <input type="hidden" name="s" value="">
                      <input type="hidden" name="c" value="0">
                      <input type="hidden" name="m" value="0">
                      <input type="hidden" name="act" value="sub">
                      <input type="hidden" name="nlbox[]" value="21">
                      <input type="hidden" name="fullname" placeholder="'. $first_name .'"  value="'. $first_name .'">
         	
          
                        <input type="hidden" name="email" placeholder="'. $user_email .'" value="'. $user_email .'">
                    
                    	
                        <textarea name="field[14]" rows="5" style="display:none;clear:both" cols="40" placeholder="System Info"> '. str_replace('<br>', "\n",strip_tags($system_result,'<br>'))  .' </textarea>
                              
                        <span class="invitations-container">	
            
            				<div align="center"><img src="' . $assets_dir .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
            					
            				</span>
                            
            				<p id="report-bug-form">
            					
           
                         <label>Plugin Name</label>
                         <select name="field[15]" style="width:500px;" tabindex="9" multiple  data-placeholder="Choose a Plugin name..." class="chosen-select">
                         <option value=""></option>';
                         
                         $array_Plugins = get_plugins();
            				
            				if(!empty($array_Plugins))
            				{
            				    foreach($array_Plugins as $plugin_file => $plugin_data) 
            				     {
            				        if(is_plugin_active($plugin_file)) 
            				         {
            				                $plugin_name=$plugin_data['Name'] ; 
            						
                    						// if($plugin_name=="WP E-Signature")
                    						// {  
                    						   if(preg_match("/WP E-Signature/",$plugin_name))
                    						   {  
                                                 $report_bug_html .= '<option value="'. $plugin_name  .'">' . $plugin_name . '</option>';   
                                               }
                                            // }
                                     }
                                 }
                            }
                           
                         
             
                    $report_bug_html .= ' </select>   <br><br>
                    <label>Name of bug</label>
                    <input type="text" name="field[15]" value="" ><br>
                  	<label>Action (What action did you take?)</label>
                    <textarea name="field[16]" rows="5" cols="40"></textarea><br>
        			<label>Expectation: What did you expect?</label>
                    <textarea name="field[17]" rows="5" cols="40"></textarea><br>
        			<label>Result: What was the actual result?</label>
                    <textarea name="field[18]" rows="5" cols="40"></textarea><br>
        			<label>Outcome: What outcome do you want?</label>
                    <textarea name="field[19]" rows="5" cols="40"></textarea><br>
                    
                     
        				</p>
                        
        			 <label style="color:red;">
                        <input type="checkbox" name="field[20][]" value="I understand this is NOT a support ticket but rather a bug submission, therefore it will not be handled like a support ticket and i will not receive a response." >
                        I understand this is NOT a support ticket but rather a bug submission, therefore it will not be handled like a support ticket and i will not receive a response.
                      </label>
                      
                      <p id="report_bug_button" align="center">
        					<a href="#" id="esig_report_bug_submit" class="button-primary esig-button-large">'.__('Submit Form', 'esig').'</a>	
        				</p>
                        </form>
        				</div> 
        				' ;
                
                 $report_bug_html .= '<div id="report-bug-step-ticket" class="esign-form-panel" style="display:none;">
        			    
                            
                        <span class="invitations-container">	
        
        				<div align="center"><img src="' . $assets_dir .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
        					
        				</span>
                        
        				<p id="report-bug-form">
        				
        				Hi '. $first_name .', so we can better serve you we ask that you login to your account and submit a support ticket at www.approveme.me/support 
        				
        				
        				<p align="center"><a target="_blank" href="https://www.approveme.me/wp-digital-e-signature-document-support" class="button-primary esig-button-large">Open a Ticket </a></p><br><p align="center"><em>Bug requests will <strong>not</strong> be treated like a support ticket</em></p></div>';
                     
                        $report_bug_html .= '<div id="report-bug-step-idea" class="esign-form-panel" style="display:none;">
        			      <span class="invitations-container">	
        
        				<div align="center"><img src="' . $assets_dir .'/images/logo.png" width="200px" height="45px" alt="Sign Documents using WP E-Signature" width="100%" style="text-align:center;"></div>
        					
        				</span>
                        
        				<p id="report-bug-form">
                        
                        Hi '. $first_name .', we love user feedback! If you have an idea for WP E-Signature feel free to shout it out using our user voice page.<br>
                         </p><p align="center"><a target="_blank" href="http://approveme.uservoice.com/forums/243780-general" class="button-primary esig-button-large">Submit an Idea </a></p>
        				</div> 
        				' ;

	       echo $report_bug_html;
     
     }
	
	/***
	 *  Esign checking update
	 *  @since 1.1.6
	 * 
	 * */
	
	public function esign_check_update()
	{
		if(!get_transient('esign-update-list'))
		{
			$addons=new WP_E_Addon();
			$update_list=$addons->esig_get_addons_update_list();
			set_transient('esign-update-list',$update_list, 60 * 60 * 12);
		}
		
	}
	
	
	/***
	*  Making update list
	*  @since 1.1.6
	* 
	* */

	public function making_update_list() {
		
		$array_Plugins = get_plugins();
		
		$update_list=array();
		
		$plugin_info=array();
		
		if(!empty($array_Plugins))
		{
			foreach($array_Plugins as $plugin_file => $plugin_data) 
			{
				if(is_plugin_active($plugin_file)) 
				{
					$plugin_name=$plugin_data['Name'] ; 
				
					
					if(preg_match("/WP E-Signature/",$plugin_name))
					{   
						
						if($plugin_name!="WP E-Signature")
						{ 
							$this->item_plugshortname=str_replace("WP E-Signature - ", "", "$plugin_name");
							
							$plugin_version = $plugin_data['Version'];
						    $plugin_info['item_name']=$this->item_plugshortname; 
							$plugin_info['version']=$plugin_version; 
							$update_list[]=$plugin_info;
							
						}
						
					}
					
					
				}
			// foreach end here
			}
			
			if(!get_transient('esign-local-update-list'))
			{
				set_transient('esign-local-update-list',json_encode($update_list), 60 * 60 * 12);
			}
			
		}
			
	}

	/**
	 * Returns the timezone string for a site, even if it's set to a UTC offset
	 *
	 * Adapted from http://www.php.net/manual/en/function.timezone-name-from-abbr.php#89155
	 *
	 * @return string valid PHP timezone string
	 */

	public function wp_get_timezone_string() {
		
		// if site timezone string exists, return it
		if ( $timezone = get_option( 'timezone_string' ) )
				return $timezone;
	
		
		// get UTC offset, if it isn't set then return UTC
		if($utc_offset = get_option( 'gmt_offset', 0 )) 
				return $utc_offset;
		
		$utc_offset *= 3600;
		
	
		
		//print_r(timezone_abbreviations_list() );
		//exit ; 
		// last try, guess timezone string manually
		$is_dst = date( 'I' );
		
		foreach ( timezone_abbreviations_list() as $abbr ) {
			
			foreach ( $abbr as $city ) {
				if ( $city['offset'] == $utc_offset )
				return $city['timezone_id'];
			}
		}
		
		// fallback to UTC
		return 'UTC';
	}
	
	
	
	public function esig_get_timezone()
	{
		
		if($utc_offset = get_option( 'gmt_offset', 0 )) 
			return $utc_offset;
	
		$timezone_string =$this->wp_get_timezone_string();
		
		try {
			
			$dt = new DateTime(null, new DateTimeZone($timezone_string));
			
			$offset = $dt->getOffset()/3600; // 11
			
			if($offset <0)
			{
				return $offset; 
			}
			else 
			{
				return $offset ;
			}
		} catch(Exception $e) {
			echo $e->getMessage() . '<br />';
		}
		
		
	}
	
    
}

