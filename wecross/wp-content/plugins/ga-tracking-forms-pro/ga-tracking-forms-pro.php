<?php
/*
Plugin Name: Google Analytics Tracking for Forms - PRO
Plugin URI: http://HelpForWP.com
Description: This plugin works WordPress sites that use Google Analytics and makes it possible to send visitor tracking data through web forms as hidden fields or normal fields, great for seeing the source of your leads.
Version: 2.5
Author: HelpForWP.com
Author URI: http://HelpForWP.com
*/

global $_gattf_plugin_name, $_gattf_version, $_gattf_home_url, $_gattf_plugin_author, $_gattf_messager, $_gatff_menu_url;

$_gattf_plugin_name = 'Google Analytics Tracking For Forms';
$_gattf_version = '2.5';
$_gattf_home_url = 'http://helpforwp.com';
$_gattf_plugin_author = 'HelpForWP';
$_gatff_menu_url = admin_url('options-general.php?page=GATFF-options');


if( !class_exists( 'EDD_SL_Plugin_Updater_4_GaTrackingFormsPro' ) ) {
	// load our custom updater
	require_once(dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php');
}

$_gattf_license_key = trim( get_option( 'gattf_license_key' ) );
// setup the updater
$_gattf_updater = new EDD_SL_Plugin_Updater_4_GaTrackingFormsPro( $_gattf_home_url, __FILE__, array( 
		'version' 	=> $_gattf_version, 				// current version number
		'license' 	=> $_gattf_license_key, 		// license key (used get_option above to retrieve from DB)
		'item_name' => $_gattf_plugin_name, 	// name of this plugin
		'author' 	=> $_gattf_plugin_author  // author of this plugin
	)
);

//for new version message and expiring version message shown on dashboard
if( !class_exists( 'EddSLUpdateExpiredMessagerV3forGaTrackingFormsPro' ) ) {
	// load our custom updater
	require_once(dirname( __FILE__ ) . '/inc/edd-sl-update-expired-messager.php');
}
$init_arg = array();
$init_arg['plugin_name'] = $_gattf_plugin_name;
$init_arg['plugin_download_id'] = 1193;
$init_arg['plugin_folder'] = 'ga-tracking-forms-pro';
$init_arg['plugin_file'] = basename(__FILE__);
$init_arg['plugin_version'] = $_gattf_version;
$init_arg['plugin_home_url'] = $_gattf_home_url;
$init_arg['plugin_sell_page_url'] = 'http://helpforwp.com/downloads/google-analytics-tracking-for-forms/';
$init_arg['plugin_author'] = $_gattf_plugin_author;
$init_arg['plugin_setting_page_url'] = $_gatff_menu_url;
$init_arg['plugin_license_key_opiton_name'] = 'gattf_license_key';
$init_arg['plugin_license_status_option_name'] = 'gattf_license_key_status';
$_gattf_messager = new EddSLUpdateExpiredMessagerV3forGaTrackingFormsPro( $init_arg );

class GoogleAnalyticsTracking4FormsPRO{
	
	var $_gatff_saved_settings_option_name = '_gatff_saved_settings_V_2_4_';
	var $_database_option_name = '_gatff_database_version_';
	var $_database_version = 240;
	
	public function __construct() {
		if( is_admin() ) {
			add_action( 'admin_menu', array($this, 'gatff_options_menu') );
			add_action( 'admin_enqueue_scripts', array($this, 'gatff_enqueue_scripts') );
			
			add_action( 'wp_ajax_gatff_get_gform_field', array($this, 'gatff_get_gravity_form_fields_option') );
		}
		//Plugin update actions
		register_uninstall_hook(__FILE__, array($this, 'gatff_deinstall') );
		register_deactivation_hook(__FILE__, array($this, 'gatff_pre_deactivate') );
	
		add_action( 'admin_init', array($this, 'gattf_activate_license') );
		add_action( 'admin_init', array($this, 'gattf_deactivate_license') );
		
		add_action( 'template_redirect',  array($this, 'gatff_setupJS') );
		add_action( 'wp_head', array($this, 'gatff_head_script') );
		add_action( 'wp_footer', array($this, 'gatff_init') );
		
		add_action( 'init', array($this, 'gatff_post_action') );
		
		add_action( 'gatff_action_save_settings', array($this, 'gatff_save_settings') );
		
		//upgrade old database
		$this->gatff_v_24_upgrade_old_database();
	}
	
	function gatff_options_menu() {
		require_once 'inc/gatff-options.php';
		
		add_options_page('Google Analytics Tracking for Forms - PRO', 'Google Analytics Tracking for Forms - PRO', 'manage_options', 'GATFF-options', 'gatff_options');
	}

	function gatff_enqueue_scripts() {
		if( isset($_GET['page']) && $_GET['page'] == 'GATFF-options' ){
			wp_enqueue_script( 'gatff-admin', plugin_dir_url( __FILE__ ) . 'js/gatff-admin.js', array( 'jquery' ) );
		}
	}
	
	function gatff_post_action(){
		if( isset( $_POST['gatff_action'] ) && strlen($_POST['gatff_action']) >0 ) {
			do_action( 'gatff_action_' . $_POST['gatff_action'], $_POST );
		}
	}
	
	function gatff_setupJS(){
		global $post;
		
		$saves_settings = get_option( $this->_gatff_saved_settings_option_name, '' );

		wp_enqueue_script( 'jquery' );
		if( $saves_settings && isset($saves_settings['option_1_setting']) && count($saves_settings['option_1_setting']) > 0 ){
			wp_enqueue_script( 'urchin' , 'http://www.google-analytics.com/urchin.js');
			wp_enqueue_script( 'ga-tracking' , plugin_dir_url( __FILE__ ) . 'js/gatff.js');
		}else if( $saves_settings && isset($saves_settings['option_2_setting']) && count($saves_settings['option_2_setting']) > 0 ){
			if ( isset($saves_settings['option_2_setting'][$post->ID]) ){
				wp_enqueue_script( 'urchin' , 'http://www.google-analytics.com/urchin.js');
				wp_enqueue_script( 'ga-tracking' , plugin_dir_url( __FILE__ ) . 'js/gatff.js');      
			}
		}
	}
	
	function gatff_head_script(){
		global $post;

		$gattf_license_key = trim(get_option('gattf_license_key'));
		$gattf_license_status = trim(get_option('gattf_license_key_status'));
		if (!$gattf_license_key || $gattf_license_status != 'valid'){
			$gattf_license_status = 'invalid';
			delete_option( 'gattf_license_key_status' );
		
			return;
		}
		
		$saves_settings = get_option( $this->_gatff_saved_settings_option_name, '' );
		
		if( $saves_settings && isset($saves_settings['option_1_setting']) && count($saves_settings['option_1_setting']) > 0 && 
			$saves_settings['option_1_setting']['fields_id']['source'] && 
			$saves_settings['option_1_setting']['fields_id']['medium'] && 
			$saves_settings['option_1_setting']['fields_id']['term'] && 
			$saves_settings['option_1_setting']['fields_id']['content'] && 
			$saves_settings['option_1_setting']['fields_id']['campagin'] && 
			$saves_settings['option_1_setting']['fields_id']['segment'] ){
		?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				if( jQuery("#<?php echo $saves_settings['option_1_setting']['form_id']; ?>").length > 0 ){
					populateHiddenFields( document.getElementById('<?php echo $saves_settings['option_1_setting']['form_id']; ?>') );
				}
			});
			function populateHiddenFields(f) {
				f.<?php echo $saves_settings['option_1_setting']['fields_id']['source']; ?>.value  = decodeURIComponent(source);
				f.<?php echo $saves_settings['option_1_setting']['fields_id']['medium']; ?>.value  = decodeURIComponent(medium);
				f.<?php echo $saves_settings['option_1_setting']['fields_id']['term']; ?>.value    = decodeURIComponent(term);
				f.<?php echo $saves_settings['option_1_setting']['fields_id']['content']; ?>.value = decodeURIComponent(content);
				f.<?php echo $saves_settings['option_1_setting']['fields_id']['campagin']; ?>.value = decodeURIComponent(campaign);
				f.<?php echo $saves_settings['option_1_setting']['fields_id']['segment'];?>.value = decodeURIComponent(csegment);
				return true;
			}
			</script>
		
		<?php
		}
		
		if( $saves_settings && isset($saves_settings['option_2_setting']) && count($saves_settings['option_2_setting']) > 0 && isset($saves_settings['option_2_setting'][$post->ID]) && count($saves_settings['option_2_setting'][$post->ID]) > 0 ){
		?>
			<script type="text/javascript">
			jQuery(document).ready(function() {
				<?php 
				foreach( $saves_settings['option_2_setting'][$post->ID] as $form_id => $fields_id_array ){ 
					if( $fields_id_array['fields_id']['source'] && 
						$fields_id_array['fields_id']['medium'] && 
						$fields_id_array['fields_id']['term'] && 
						$fields_id_array['fields_id']['content'] &&
						$fields_id_array['fields_id']['campagin'] &&
						$fields_id_array['fields_id']['segment'] ){
				?>
				if (jQuery("#<?php echo $form_id; ?>").length > 0){
					populateHiddenFields_4_option_2('<?php echo $form_id; ?>', '<?php echo $fields_id_array['fields_id']['source']; ?>', '<?php echo $fields_id_array['fields_id']['medium']; ?>', '<?php echo $fields_id_array['fields_id']['term']; ?>', '<?php echo $fields_id_array['fields_id']['content']; ?>', '<?php echo $fields_id_array['fields_id']['campagin']; ?>', '<?php echo $fields_id_array['fields_id']['segment']; ?>'  );
				}
				<?php
					}
				} 
				?>
			});
			function populateHiddenFields_4_option_2( form_id, field_source, field_medium, field_term, field_content, field_campagin, field_segment ) {
				eval( 'document.getElementById("' + form_id + '").' + field_source + '.value = decodeURIComponent(source);' );
				eval( 'document.getElementById("' + form_id + '").' + field_medium + '.value = decodeURIComponent(medium);' );
				eval( 'document.getElementById("' + form_id + '").' + field_term + '.value = decodeURIComponent(term);' );
				eval( 'document.getElementById("' + form_id + '").' + field_content + '.value = decodeURIComponent(content);' );
				eval( 'document.getElementById("' + form_id + '").' + field_campagin + '.value = decodeURIComponent(campaign);' );
				eval( 'document.getElementById("' + form_id + '").' + field_segment + '.value = decodeURIComponent(csegment);' );

				return true;
			}
			</script>
			
			<?php
		}
	}
	
	function gatff_init(){
		$gattf_license_key = trim(get_option('gattf_license_key'));
		$gattf_license_status = trim(get_option('gattf_license_key_status'));
		if (!$gattf_license_key || $gattf_license_status != 'valid'){
			$gattf_license_status = 'invalid';
			delete_option( 'gattf_license_key_status' );
			return;
		}
	
		if ( !is_admin() ){
			require_once( 'lib/class.gaparse.php' );
		}
	}
	
	function gatff_pre_deactivate() {
		if( function_exists('is_multisite') && is_multisite() ) {
			static $deact = 0;
			global $wpdb;
			// check if it is a network activation - if so, run the activation function for each blog id
			if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
				$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					delete_option('gattf_license_key');
					delete_option('gattf_license_key_status');
				}
				switch_to_blog($old_blog);
			} 
		}
	}
	
	function gatff_deinstall() {
		global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name like 'gatff_pid_%'");
	}
	
	
	
	function gattf_activate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['gattf_license_activate'] ) ) {
			global $_gattf_plugin_name, $_gattf_home_url;
	
			// run a quick security check 
			if( ! check_admin_referer( 'gattf_license_key_nonce', 'gattf_license_key_nonce' ) ) 	
				return; // get out if we didn't click the Activate button
	
			// retrieve the license from the database
			$license = trim( $_POST['gattf_license_key'] );
				
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'activate_license', 
				'license' 	=> $license,
				'url'		=> get_option('home'),
				'item_name' => urlencode( $_gattf_plugin_name ) // the name of our product in EDD
			);
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $_gattf_home_url ), array( 'timeout' => 15, 'sslverify' => false ) );
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
			update_option( 'gattf_license_key', $license );
			if( $license_data && isset($license_data->license) ){
				update_option( 'gattf_license_key_status', $license_data->license );
			}
		}
	}
	
	function gattf_deactivate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['gattf_license_deactivate'] ) ) {
			global $_gattf_plugin_name, $_gattf_home_url;
			
			// run a quick security check 
			if( ! check_admin_referer( 'gattf_license_key_nonce', 'gattf_license_key_nonce' ) ) 	
				return; // get out if we didn't click the Activate button
	
			// retrieve the license from the database
			$license = trim( get_option( 'gattf_license_key' ) );
				
	
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'deactivate_license', 
				'license' 	=> $license, 
				'url'		=> get_option('home'),
				'item_name' => urlencode( $_gattf_plugin_name ) // the name of our product in EDD
			);
			
			// Call the custom API.
			global $_gattf_home_url;
			$response = wp_remote_get( add_query_arg( $api_params, $_gattf_home_url ), array( 'timeout' => 15, 'sslverify' => false ) );
	
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data && isset($license_data->license) && $license_data->license == 'deactivated' )
				delete_option( 'gattf_license_key_status' );
		}
	}

	function gatff_get_gravity_form_fields_option(){
		global $current_user;
		if( $current_user->ID < 1 || !current_user_can( 'manage_options' ) ){
			wp_die( 'ERROR: Invalid Operation' );
		}
		$form_id = $_POST['formid'];
		if( $form_id < 1 || empty($form_id) ){
			wp_die( 'ERROR: Invalid Form Id: '.$form_id );
		}
		
		$form_fields = $this->gatff_get_gravity_plain_form( $form_id );
		echo $form_fields;
		
		wp_die();
	}
	
	function gatff_get_gravity_plain_form( $formid ){
	
		if( class_exists('GFAPI') ){
			$uns_gf = GFAPI::get_form( $formid );
		}else if( class_exists('GFFormsModel') ){
			$uns_gf = GFFormsModel::get_form_meta( $formid );
		}else{
			global $wpdb;
			$rg_form_meta_table = $wpdb->prefix . 'rg_form_meta';
			$f = $wpdb->get_results("SELECT * FROM {$rg_form_meta_table} WHERE form_id = " . $formid);
			$uns_gf = maybe_unserialize($f[0]->display_meta);
		}
		$form_id = $uns_gf['id'];
	
		$out .= '<option value="0">Select...</option>';
		foreach($uns_gf['fields'] as $field) {
			// check for displayOnly fields
			if(isset($field['displayOnly']) && $field['displayOnly'] == 1 || $field['type'] == 'fileupload') {
				continue;
			}
			if( isset($field['inputs']) && is_array($field['inputs']) && count($field['inputs']) > 0 && !isset($field['choices']) ) {
				foreach($field['inputs'] as $input) {
					$out .= '<option value="input_'.$formid.'_'.str_replace('.', '_', $input['id']).'">'.$input['label'].'</option>';
				}
			}else {
				$out .= '<option value="input_'.$formid.'_'.str_replace('.', '_', $field['id']).'">'.$field['label'].'</option>';
			}
		}
				
		return $out;
	}
	
	function gatff_save_settings( $data ){
		
		//for option Load on all Pages
		$option_1_array = array();
		if( isset($data['gatff_active_option_1']) ){
			$option_1_array['form_id'] = '';
			if( $data['gatff_option_1_form_type'] == 'html_form' ){
				$option_1_array['form_type'] = 'html_form';
				$option_1_array['form_id'] = $data['gatff_option_1_html_form_ID'];
				$option_1_array['fields_id'] = array();
				$option_1_array['fields_id']['source'] 	= $data['gatff_option_1_field_source'];
				$option_1_array['fields_id']['medium'] 	= $data['gatff_option_1_field_medium'];
				$option_1_array['fields_id']['term'] 	= $data['gatff_option_1_field_term'];
				$option_1_array['fields_id']['content'] = $data['gatff_option_1_field_content'];
				$option_1_array['fields_id']['campagin']= $data['gatff_option_1_field_campaign'];
				$option_1_array['fields_id']['segment'] = $data['gatff_option_1_field_segment'];
			}else if( $data['gatff_option_1_form_type'] == 'gravity_form' ){
				$option_1_array['form_type'] = 'gravity_form';
				$option_1_array['form_id'] = $data['gatff_option_1_gravity_form_ID'];
				$option_1_array['fields_id']['source'] 	= $data['gatff_option_1_gravity_form_fields_list_source'];
				$option_1_array['fields_id']['medium'] 	= $data['gatff_option_1_gravity_form_fields_list_medium'];
				$option_1_array['fields_id']['term'] 	= $data['gatff_option_1_gravity_form_fields_list_term'];
				$option_1_array['fields_id']['content'] = $data['gatff_option_1_gravity_form_fields_list_content'];
				$option_1_array['fields_id']['campagin']= $data['gatff_option_1_gravity_form_fields_list_campaign'];
				$option_1_array['fields_id']['segment'] = $data['gatff_option_1_gravity_form_fields_list_segment'];
			}
		}
		
		//for Load on specified Pages
		$option_2_array = array();
		if( isset($data['gatff_active_option_2']) ){
			$page_form_ids_array = explode(';', trim($data['gatff_option_2_exist_configuration'], ';'));
			foreach($page_form_ids_array as $option_2_setting){
				$option_2_setting_array = explode('#', $option_2_setting);
				$form_id_fields_array = array();
				$form_id_fields_array['form_id'] = $option_2_setting_array[1];
				$form_id_fields_array['fields_id'] = array();
				$form_id_fields_array['fields_id']['source'] 	= $option_2_setting_array[2];
				$form_id_fields_array['fields_id']['medium'] 	= $option_2_setting_array[3];
				$form_id_fields_array['fields_id']['term'] 	= $option_2_setting_array[4];
				$form_id_fields_array['fields_id']['content'] = $option_2_setting_array[5];
				$form_id_fields_array['fields_id']['campagin']= $option_2_setting_array[6];
				$form_id_fields_array['fields_id']['segment'] = $option_2_setting_array[7];
				
				if( !isset($option_2_array[$option_2_setting_array[0]]) ){
					$option_2_array[$option_2_setting_array[0]] = array();
				}
				$option_2_array[$option_2_setting_array[0]][$form_id_fields_array['form_id']] = $form_id_fields_array;
			}
		}
		update_option( $this->_gatff_saved_settings_option_name, array('option_1_setting' => $option_1_array,  'option_2_setting' => $option_2_array) );
	}
	
	function gatff_v_24_upgrade_old_database(){
		//get database version
		$current_db_version = intval(get_option($this->_database_option_name, 0));
		if( $current_db_version >= $this->_database_version ){
			return;
		}
		
		$opt = get_option('gatff_load_on');
		$option_1_array = array();
		$option_2_array = array();
		
		if( $opt == "all" ){
			$parts = get_option('gatff_all');
			$parts_arr = explode(':', $parts);
			$f_all = get_option('gatff_pid_all');
			
			$form_id = $parts_arr[0];
			
			$fields_id_source = $f_all['gatff_source'];
			$fields_id_medium = $f_all['gatff_medium'];
			$fields_id_term   = $f_all['gatff_term'];
			$fields_id_content = $f_all['gatff_content'];
			$fields_id_campaign = $f_all['gatff_campaign'];
			$fields_id_segment = $f_all['gatff_segment'];
			
			$option_1_array['form_type'] = 'html_form';
			$option_1_array['form_id'] = $form_id;
			$option_1_array['fields_id'] = array();
			$option_1_array['fields_id']['source'] 	= $fields_id_source;
			$option_1_array['fields_id']['medium'] 	= $fields_id_medium;
			$option_1_array['fields_id']['term'] 	= $fields_id_term;
			$option_1_array['fields_id']['content'] = $fields_id_content;
			$option_1_array['fields_id']['campagin']= $fields_id_campaign;
			$option_1_array['fields_id']['segment'] = $fields_id_segment;
		}else{
			$parts = get_option('gatff_pids');
			if( is_array( $parts ) ){
				foreach($parts as $htmlids){
					$arr = explode( ":", $htmlids );
					$page_id = $arr[0];
					$form_id = $arr[1];
					$fields_id_array = get_option('gatff_pid_' . $page_id);
					
					$form_id_fields_array = array();
					$form_id_fields_array['form_id'] = $form_id;
					$form_id_fields_array['fields_id'] = array();
					$form_id_fields_array['fields_id']['source'] 	= $fields_id_array['gatff_source'];
					$form_id_fields_array['fields_id']['medium'] 	= $fields_id_array['gatff_medium'];
					$form_id_fields_array['fields_id']['term'] 	= $fields_id_array['gatff_term'];
					$form_id_fields_array['fields_id']['content'] = $fields_id_array['gatff_content'];
					$form_id_fields_array['fields_id']['campagin']= $fields_id_array['gatff_campaign'];
					$form_id_fields_array['fields_id']['segment'] = $fields_id_array['gatff_segment'];
					
					if( !isset($option_2_array[$page_id]) ){
						$option_2_array[$page_id] = array();
					}
					$option_2_array[$page_id][$form_id] = $form_id_fields_array;
				}
			}
		}
		
		update_option( $this->_gatff_saved_settings_option_name, array('option_1_setting' => $option_1_array,  'option_2_setting' => $option_2_array) );
		update_option( $this->_database_option_name, $this->_database_version );
	}
}

$gatff_pro_instance = new GoogleAnalyticsTracking4FormsPRO();

