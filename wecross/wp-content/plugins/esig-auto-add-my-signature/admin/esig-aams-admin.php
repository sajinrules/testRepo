<?php
/**
 *
 * @package ESIG_AAMS_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_AAMS_Admin')) :
class ESIG_AAMS_Admin {

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
		$plugin = ESIG_AAMS::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		
		add_filter('esig-edit-document-template-data', array($this, 'show_aams_more_action'), 10, 2);
		add_filter('esig-edit-document-template-data', array($this, 'show_aams_add_signature'), 10, 2);
		add_filter('esig-shortcode-display-owner-signature', array($this, 'record_view_shortcode'), 10,2);
	}
    
	/**
	 * Filter: 
	 * allow add signature checkable
	 * Since 1.0.1
	 */		
	public function show_aams_add_signature($template_data){

        $checked = apply_filters('esig-add-signature-checked-filter','');
        
		$template_data['add_signature_select']="onclick='javascript:return true;' $checked"; 
	
		return $template_data;
		
	}
	/**
	 * Filter: 
	 * Show aams document in view document opton 
	 * Since 1.0.1
	 */		
	public function show_aams_more_action($template_data){

		$template_data['document_add_signature_txt'] = __("Add my signature", 'esig-aasm'); 
		//$template_data['add_signature_select']="";//
		return $template_data;
		
	}
   
   public function record_view_shortcode($template_data,$args){
    
	   $document=$args['document'] ;
	   
	    if(! function_exists('WP_E_Sig'))
				return ;
				
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
   
        
         $owner = $api->user->getUserBy('wp_user_id', $document->user_id);
		
			$owner_signature = $document->add_signature ? stripslashes($api->signature->getUserSignature($owner->user_id)) : '';
		
			// Add owner's signature (if required)
			$owner_sig_html='';
			if($document->add_signature){
				$owner_data = array(
					'user_name' => $owner->first_name . ' ' . $owner->last_name,
					'user_id' => '0',
					'signature' => $owner_signature,
				'output_type' =>$api->signature->getUserSignature_by_type($owner->user_id,'typed') , 
				'font_type'=>$api->setting->get_generic('esig-signature-type-font'.$owner->user_id),
					'input_name' => 'owner_signature',
					'css_classes' => $owner_signature ? 'signed':'',
					'by_line' => 'Signed by',
					'sign_date' => "Signed On: " . mysql2date('n/j/Y',
					$document->last_modified),
				);
				$owner_sig_html = $api->view->renderPartial('_signature_display', $owner_data);
			}
			 
			$template_data['owner_signature']= $owner_sig_html ;
			return $template_data ;  
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

