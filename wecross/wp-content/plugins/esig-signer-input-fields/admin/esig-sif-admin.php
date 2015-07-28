<?php
/**
 *
 * @package ESIG_SIF_Admin
 * @author  Michael Medaglia <mm@michaelmedaglia.com>
 */

if (! class_exists('ESIG_SIF_Admin')) :
class ESIG_SIF_Admin {

	/**
	 * Instance of this class.
	 * @since    0.1
	 * @var      object
	 */
	protected static $instance = null;
	private $inputs_table = 'esign_documents_signer_field_data';
	private $docs_table = 'esign_documents';
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
		$plugin = ESIG_SIF::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );

		//add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		// Text Editor Buttons
		add_action( 'init', array($this, 'sif_buttons' ));
		
		add_action('admin_print_footer_scripts', array($this, 'quicktags'));

		//add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		
		add_filter( 'esig-edit-document-template-data', array($this, 'edit_document'));
		
		add_filter( 'esig-search-document-filter', array($this, 'search_sif_document'),10,2);
		
		//ajax 
		add_action('wp_ajax_signerdefine', array($this,'signerdefine'));
		add_action('wp_ajax_nopriv_signerdefine', array($this, 'signerdefine'));
      
	}
	
	
	public function search_sif_document($docs,$esig_document_search)
	{
		global $wpdb;
		if(! function_exists('WP_E_Sig'))
			return ;
		
		$esig =WP_E_Sig();
		$api = $esig->shortcode;
		
		$result = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}{$this->inputs_table} " .
			"ORDER BY date_created DESC");
		
		$docs1 = array(); 
		foreach($result as $fields)
		{
			//print_r($fields);
			$decrypt_fields=json_decode($api->signature->decrypt("esig_sif",$fields->input_fields));
			$sif_array=get_object_vars($decrypt_fields);
			
			if (in_array($esig_document_search, $sif_array)) {
				//echo $fields->document_id . "<br>" ;
				$docs1 = $wpdb->get_results(
					$wpdb->prepare(
							"SELECT * FROM " . $wpdb->prefix . $this->docs_table . " WHERE  document_id=%s", $fields->document_id
							)
						);
				if(!empty($docs1))	
				{
					$docs = array_merge($docs1,$docs);
				}
			}
			
		}
		
		
		//$fields =json_decode($decrypt_fields);
		
		return $docs ;
	}
	
	/**
	* ajax signer textbox define here 
	*
	* Since 1.0.4
	**/
	public function signerdefine()
		{
		
			if(!function_exists('WP_E_Sig'))
				    return ;
				
			$esig = WP_E_Sig();
			$api = $esig->shortcode;
			
			$document_id=$_POST['esig_sif_document_id'];
			$sif_signer=$_POST['sif_signer'];
		     
			  if($sif_signer)
					$api->invite->deleteDocumentInvitations($document_id);
			
			if($api->document->getDocumenttype($document_id) == 'stand_alone') 
				{
				 echo ""; 
				die();			
				}
			
			$allinvitations=$api->invite->getInvitations($document_id);
			if(!empty($allinvitations)) {
			echo '<div id="signer_display">Who fills this out?</br>';
			
			echo '<select name="sif_invite_select" data-placeholder="Choose a Option..." class="chosen-select" style="width:250px;" id="sif_invite_select" tabindex="2">';
				$html='' ;
			foreach($allinvitations as $invite){
			
				$userdetails =$api->user->getUserdetails($invite->user_id,$document_id); 
				$html .='<option value="'. $invite->user_id . "ud" . $document_id .'">'.$userdetails->first_name .' </option>' ;
			
			}
			 echo $html ;
			echo '</select></div>';
			}
			else {
			 $sif_content=apply_filters('esig_sif_shortcode_content','',array('document_id'=>$document_id,'sif_signer'=>$sif_signer));
			 echo $sif_content ; 
			}
		  die();
		}
		
	/**
	 * Adds popup dialog
	 */		
	public function edit_document($data) {
		
		$view_path =  plugin_dir_path( __FILE__ ) . 'views/shortcode_panel.php';
		
		if(file_exists($view_path)){
			include_once($view_path);
		}else{
			die($this->plugin_slug . ": edit_document failed: '" . $view_path . "' does not exist");
		}
		
		return $data;
	}
	

	/**
	 * Handle new tinymce buttons
	 */		
	public function sif_buttons() {
	
		
		add_filter( "mce_external_plugins", array($this, "sif_add_buttons"),10,1 );
		add_filter( 'mce_buttons', array($this, 'sif_register_buttons'),10,1);
		
	}


	/**
	 * Add tinymce buttons
	 */	
	public function sif_add_buttons( $plugin_array ) {
	$screen = get_current_screen();
		$admin_screens = array(
			'admin_page_esign-add-document',
			'admin_page_esign-edit-document'
		);
		if (in_array($screen->id, $admin_screens)) {
	    $wp_version=get_bloginfo ( 'version' );
		if($wp_version<3.9){
		$plugin_array['esig_sif'] = plugin_dir_url( __FILE__ ) . 'assets/js/mce_less.js';
		}
		else {
	    $plugin_array['esig_sif'] = plugin_dir_url( __FILE__ ) . 'assets/js/mce.js';
		}
	    return $plugin_array;
		}
	}


	/**
	 * Register tinymce buttons
	 */	
	public function sif_register_buttons( $buttons ) {
	    array_push( $buttons, 'esig_sif', 'esig_sif2' );
	    return $buttons;
	}	

	/**
	 * Create quicktag for admins using non-visual mode
	 */
	public function quicktags( ){
		echo '<script type="text/javascript">';
		echo "if(typeof(esig_sif_quicktag) != 'undefined'){\n QTags.addButton( 'esig_1', 'Signer Field',esig_sif_quicktag, null, null, 'Add a signer input field', 309 ); \n}";
		echo "</script>";
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

	/**
	 * Register and enqueue admin-specific style sheet.
	 * @since     0.1
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		$screen = get_current_screen();
		$current = $screen->id;
		
		// Show if we're adding or editing a document
		if ( ($current == 'admin_page_esign-add-document') || ($current == 'admin_page_esign-edit-document')){
			
            wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin_input.css', __FILE__ ), array(), ESIG_SIF::VERSION );
            wp_enqueue_style( $this->plugin_slug .'-bootstrap-styles',plugins_url( 'assets/css/pop-over.css', __FILE__ ), array(), ESIG_SIF::VERSION);
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 * @since     0.1
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		$screen = get_current_screen();
		$current = $screen->id;
		// Show if we're adding or editing a document
		if ( ($current == 'admin_page_esign-add-document') || ($current == 'admin_page_esign-edit-document')) {
        
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin_input.js', __FILE__ ), array( 'jquery' ), ESIG_SIF::VERSION,true );
			
			wp_enqueue_script($this->plugin_slug . '-bootstrap-scripts', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), array('jquery'), '3.2.0', true );
            
            
			// Text Editor Buttons
			$this->sif_buttons();
			$this->quicktags();
            
			$document_id =isset($_GET['document_id']) ? $_GET['document_id'] : '' ;
			$sif_signer =isset($_GET['sif_signer']) ? $_GET['sif_signer'] : '' ;
            
            $invite = new WP_E_Invite();
            $invitationcount = $invite->getInvitationCount($document_id); 
            
				wp_localize_script($this->plugin_slug . '-admin-script','mysifAjax', array('ajaxurl' => admin_url( 'admin-ajax.php'),
				'document_id' =>$document_id,'sif_signer' =>$sif_signer,'invite_count'=>$invitationcount));
			
		}
		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {	
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 * @since    0.1
	 */
	public function add_plugin_admin_menu() {
		
		$this->plugin_screen_hook_suffix = true;

		/*
		$this->plugin_screen_hook_suffix = add_submenu_page(
			'esign-docs',
			__( 'Signer Input Fields', $this->plugin_slug ),
			__( 'Signer Input Fields', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page')
		);
		*/

	}

	/**
	 * Render the settings page for this plugin.
	 * @since    0.1
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 * @since    0.1
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}


}

endif;



