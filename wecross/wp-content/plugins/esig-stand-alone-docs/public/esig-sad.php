<?php
/**
 * 
 * @package ESIG_SAD
 * @author  Approve Me / WP E-Signature
 */
class ESIG_SAD {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1
	 *
	 * @var     string
	 */
	const VERSION = '1.0.10';
	
	private $table = null; // Table name for plugin data

	/**
	 *
	 * Unique identifier for plugin.
	 *
	 * @since     0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'esig-sad';
	
	protected $sad_pages = null;

	/**
	 * Instance of this class.
	 *
	 * @since     0.1
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.1
	 */
	private function __construct() {

		global $wpdb;
		
		$this->table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';
		$this->doctable =  $wpdb->prefix . 'esign_documents';
		// Load plugin text domain
		add_action('init', array($this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		add_action('wpmu_new_blog', array($this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action('esig_head', array($this, 'enqueue_styles' ) );
		add_action('esig_footer', array($this, 'enqueue_scripts' ) );

		add_filter('esig_document_template', array($this, 'document_template'), 20, 3);
		
		add_shortcode('wp_e_signature_sad', array($this, 'display_document'));
		
		add_action('esig_signature_saved', array($this, 'signature_saved' ) );
		
		add_action('esig_document_after_delete', array($this, 'sad_permanent_delete' ),20,1);
		
		add_filter('esig-shortcode-display-template-data', array($this, 'shortcode_display_template'), 20, 2);

	}
	
	/**
	 * This is method sad_permanent_delete
	 *  delete sad document when permanently delete . 
	 * @return mixed This is the return value description
	 *
	 */	
	public function sad_permanent_delete($args){
		
		global $wpdb;
		 $doc_id=$args['document_id'];
		
		$page_id = $wpdb->get_var("SELECT page_id FROM {$this->table} WHERE document_id='$doc_id'");
		$page_data = get_page($pageID);
		// striping sad shortcode from page . 
		$remove_sad_content=str_replace('[wp_e_signature_sad doc="'. $doc_id .'"]', '',$page_data->post_content);
		$my_post = array(
			'ID'=> $page_id,
			'post_content' =>$remove_sad_content
			);
		// Update the post into the database
		wp_update_post( $my_post );
		// delete sad document from sad table 
		return $wpdb->query(
			$wpdb->prepare(
				"DELETE FROM " . $this->table . " WHERE page_id=%d", $page_id
				)
			);
	}

	/**
	 * Returns the plugin slug.
	 *
	 * @since     0.1
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Returns an instance of this class.
	 *
	 * @since     0.1
	 *
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
	 * Shortcode for displaying stand alone docs
	 * 
	 * @since 1.0.1
	 * @param null
	 * @return void
	 */	
	public function display_document($atts){

		// Extract the attributes
		extract(shortcode_atts(array(
			'doc' => '',
			), $atts, 'wp_e_signature_sad'));

		$doc_id = (int) $doc;
		$esig = WP_E_Sig();
		$api = $esig->shortcode;
		
		$html = '
			<p>
				<input required type="email" class="form-control" placeholder="'.__('Your email address','esig-sad').'" name="esig-sad-email" value=""/>
			</p>
        ';

		// Viewing
		if(!isset($_POST['recipient_signature']) && empty($_POST['recipient_signature']) && !isset($_POST['esignature_in_text']) && empty($_POST['esignature_in_text'])){
			
			// If document_id is set, show that
			if(isset($_GET['document_id'])){
				$doc_id = intval($_GET['document_id']);
			}
			
			// Admins & Readers
			$template_data = array(
				"viewer_needs_to_sign" => true,
				"extra_attr" => "",
				"signer_sign_pad_before" => $html,
				"is_standalone_page" => true,
				"ESIGN_ASSETS_URL" => ESIGN_ASSETS_DIR_URI
			);
			
			$document_status=$api->document->getStatus($doc_id);
			if($document_status == 'trash'){
				$template_data1 = array( 
					"message" => "<p align='center'><a href='http://www.approveme.me/wp-digital-e-signature/' title='".__('Wordpress Digital E-Signature by Approve Me','esig-sad')."' target='_blank'><img src='" . ESIGN_ASSETS_DIR_URI . "/images/logo.svg' alt='Sign Documents Online using WordPress E-Signature by Approve Me'></a></p><p align='center' class='esig-404-page-template'>".__('Well this is embarrassing, but we can\'t seem to locate the document you\'re looking to sign online.<br>You may want to send an email to the website owner. <br>Thank you for using Wordpress Digital E-Signature By','esig-sad')." <a href='http://www.approveme.me/wp-digital-e-signature/' title='Free Document Signing by Approve Me'>".__('Approve Me','esig-sad')."</a></p> <p align='center'><img src='" . ESIGN_ASSETS_DIR_URI . "/images/search.svg' alt='esignature by Approve Me' class='esig-404-search'><br><a class='esig-404-btn' href='http://www.approveme.me/wp-digital-e-signature?404'>".__('Download WP E-Signature Free!','esig-sad')."</a></p><p>&nbsp;</p>",
					);
				$api->displayDocumentToSign(null, '404', $template_data1);
				return;
			}
			
			$api->displayDocumentToSign($doc_id, "sign-document", $template_data);
			
			//wp_localize_script($this->plugin_slug . '-plugin-script', 'esigSad', array('is_unsigned' => 1) );
			echo "<script type='text/javascript'>";
			echo ' /* <![CDATA[ */
					var esigSad = {"is_unsigned":"1"};
					/* ]]> */
					</script>';
					
			add_thickbox();
			
		// Signing
		} else {
			
			
			global $wpdb;
			
			$doc_table = $api->document->table_prefix . 'documents';
			$old_doc_id = $doc_id;
			$old_doc = $api->document->getDocument($old_doc_id);

			// Create the user
			$recipient = array(
				"user_email" => $_POST['esig-sad-email'],
				"first_name" => $_POST['recipient_first_name']
			);
			$recipient['id'] = $api->user->insert($recipient);
			
			// Copy the document
			$doc_id = $api->document->copy($old_doc_id);
			
			// Update the doc title
			$wpdb->query($wpdb->prepare(
				"UPDATE $doc_table SET document_title = '%s' where document_id = %d", 
				$old_doc->document_title . ' - ' . $recipient['first_name'], 
				$doc_id));
			
			$doc = $api->document->getDocument($doc_id);
			
			// Get Owner
			$owner = $api->user->getUserByID($doc->user_id);
						
			// Create the invitation?
			$invitation = array(
				"recipient_id" => $recipient['id'],
				"recipient_email" => $recipient['user_email'],
				"recipient_name" => $recipient['first_name'],
				"document_id" => $doc_id,
				"document_title" => $doc->document_title,
				"sender_name" => $owner->first_name . ' ' . $owner->last_name,
				"sender_email" => $owner->user_email,
				"sender_id" => 'stand alone',
				"document_checksum" => $doc->document_checksum,
				"sad_doc_id" => $old_doc_id,
			);
			$invite_controller = new WP_E_invitationsController;
			$invitation_id = $invite_controller->save($invitation);
			$invite_hash = $api->invite->getInviteHash($invitation_id);
						
			// Create the signature
				//$signature_id = $api->signature->add(
				//$_POST['recipient_signature'], 
				//$recipient['id']);
			
			// adding signature here 
			
			if(isset($_POST['esig_signature_type']) && $_POST['esig_signature_type'] =="typed")
			{
				
				$signature_id = $api->signature->add($_POST['esignature_in_text'],$recipient['id'],$_POST['esig_signature_type']);
				
				$api->setting->set('esig-signature-type-font'.$recipient['id'],$_POST['font_type']);
			}
			
			if(isset($_POST['recipient_signature']) && $_POST['recipient_signature'] != "")
			{
				
				$signature_id = $api->signature->add($_POST['recipient_signature'],$recipient['id']);
			}
			// save signing device information
			if (wp_is_mobile())
			{
				$api->document->save_sign_device($doc_id,'mobile');
			} 
			// Link signature to document in the document_signature join table
			$join_id = $api->signature->join($doc_id, $signature_id);
			
			
			$recipient_obj = $api->user->getUserByID($recipient['id']);
			
			$api->document->recordEvent($doc_id, 'all_signed', null, null);
			
			
			// Update the document's status to signed
			$api->document->updateStatus($doc_id, "signed");
			
			$invitation = $api->invite->getInviteBy('invite_hash', $invite_hash);
			
			// Fire post-sign action
			do_action('esig_signature_saved', array(
				'signature_id' => $signature_id,
				'recipient' => $recipient_obj,
				'invitation' => $invitation,
				'post_fields' => $_POST,
				'sad_doc_id'=>$old_doc_id
				));
			
			
			$attachments = apply_filters('esig_email_attachment',array('document' => $doc));
			
			$audit_hash = $api->auditReport($doc_id, $doc, true);
			
			if(is_array($attachments) || empty($attachments)){
				
				$attachments=false ; 
			}
			
			$api->notify_owner($doc, $recipient_obj, $audit_hash,$attachments); // Notify admin
			
			$post = array('invite_hash'=>$invite_hash, 'checksum'=>$doc->document_checksum);
			
			$api->notify_signer($doc, $recipient_obj, $post, $audit_hash,$attachments); // Notify signer
			
			// do action after sending email 
			do_action('esig_email_sent',array('document'=>$doc));
			
			
			
			
			$assets_dir = ESIGN_ASSETS_DIR_URI;
            
			 $success_msg = "<p class=\"success_title\" align=\"center\">Excellent work! You signed {$document->document_title} like a boss.</h2> <p align='center' class='s_logo'><img src='$assets_dir/images/boss.svg'></p>";
				
             $success_msg = apply_filters('esig-success-page-filter',$success_msg,array('document'=>$document));
                
			$template_data = array(
				"invite_hash" => $invite_hash,
				"recipient_signature" => $_POST['recipient_signature'],
				"recipient_first_name" => $recipient['first_name'],
				"message" => sprintf(__($success_msg, 'esig-sad'))
			);
			
			// sad print option settings 
			$current_page = get_queried_object_id();
			$sad_document_id = $wpdb->get_var("SELECT document_id FROM {$this->table} WHERE page_id='$current_page'");
			if($api->setting->get_generic('esig_print_option'.$sad_document_id)){
			  $sad_document_print_option=$api->setting->get_generic('esig_print_option'.$sad_document_id);
			  $api->setting->set('esig_print_option'.$doc_id,$sad_document_print_option);
			}else {
			  $sad_document_print_option=$api->setting->get_generic('esig_print_option');
			  $api->setting->set('esig_print_option'.$doc_id,$sad_document_print_option);			  
			}
			
			// pdf option adding here .
			
			if($api->setting->get_generic('esig_pdf_option'.$sad_document_id)){
			  $sad_document_pdf_option=$api->setting->get_generic('esig_pdf_option'.$sad_document_id);
			  $api->setting->set('esig_pdf_option'.$doc_id,$sad_document_pdf_option);
			}else {
			  $sad_document_pdf_option=$api->setting->get_generic('esig_pdf_option');
			  $api->setting->set('esig_pdf_option'.$doc_id,$sad_document_pdf_option);			  
			}
			// sad pring opton settings end here 
			
			
			
			$api->displayDocumentToSign($doc_id, "sign-preview", $template_data);
			
		}

		return "";
	}
	

	/**
	 * Use esig page template for stand alone docs
	 * 
	 * @since 1.0.1
	 * @param null
	 * @return void
	 */		
	public function document_template($template, $esig_doc_id, $current_page){

		$current_page = get_queried_object_id();
		
		$esig_template_path = ESIGN_TEMPLATES_PATH . "default/index.php";
		global $wpdb;
		
		// We're already showing the esig template
		if($template == $esig_template_path){
			// Do nothing
		
		} else {
			
			if(!$this->sad_pages){
				$this->sad_pages = $wpdb->get_col("SELECT page_id FROM {$this->table}");
			}
			$document_id = $wpdb->get_var("SELECT document_id FROM {$this->table} WHERE page_id='$current_page'");
			
			$document_status= $wpdb->get_var("SELECT document_status FROM {$this->doctable} WHERE document_id='$document_id'");
		
			// If we're on a stand alone page
			if($document_status=='draft'){
				remove_all_shortcodes();
				return $template;
			}
						
			if( is_page($current_page) && in_array($current_page, $this->sad_pages)){
				$template = $esig_template_path;
			}
		}
		
		return $template;
	}


	public function shortcode_display_template($template_data){
		if(array_key_exists('is_standalone_page', $template_data)){
			if($template_data['is_standalone_page'] == true){
				//$template_data['audit_report'] = ''; //hide the audit report
			}
		}
		return $template_data;
	}


	public function signature_saved($args){

		global $wpdb;
		
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since     0.1
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		self::single_activate();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since     0.1
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		self::single_deactivate();
	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since     0.1
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since     0.1
	 */
	private static function single_activate() {

		global $wpdb;
		$table =  $wpdb->prefix . 'esign_documents_stand_alone_docs';

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        if($wpdb->get_var("SHOW TABLES LIKE '$table'") != $table) 
        {
		    $sql = "CREATE TABLE IF NOT EXISTS `" . $table . "`(
			`document_id` int(11) NOT NULL PRIMARY KEY,
			`page_id` int(11) NOT NULL,
			`date_created` datetime NOT NULL,
			`date_modified` datetime NOT NULL) ENGINE = INNODB";
		//dbDelta($sql);
            $wpdb->query($sql);
        }
        
         if(get_option('WP_ESignature__Stand_Alone_Documents_documentation'))
        {
            update_option('WP_ESignature__Stand_Alone_Documents_documentation','https://www.approveme.me/wp-digital-signature-plugin-docs/article/stand-alone-documents-add-on/');
            
        }
        else
        {
           
           add_option('WP_ESignature__Stand_Alone_Documents_documentation','https://www.approveme.me/wp-digital-signature-plugin-docs/article/stand-alone-documents-add-on/');
        }

	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since     0.1
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     0.1
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since     0.1
	 */
	public function enqueue_styles() {

		$current_page = get_queried_object_id();
		global $wpdb;
			
		if(!$this->sad_pages){
			$this->sad_pages = $wpdb->get_col("SELECT page_id FROM {$this->table}");
		}

		// If we're on a stand alone page
		if( is_page($current_page) && in_array($current_page, $this->sad_pages)){	
			echo "<link rel='stylesheet' id='esig-sad-plugin-styles-css'  href='". plugins_url('public/assets/css/public.css?ver='. self::VERSION ,dirname(__FILE__)) ."' type='text/css' media='all' />";	
		}
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since     0.1
	 */
	public function enqueue_scripts() {
		$current_page = get_queried_object_id();
		global $wpdb;
			
		if(!$this->sad_pages){
			$this->sad_pages = $wpdb->get_col("SELECT page_id FROM {$this->table}");
		}

		// If we're on a stand alone page
		if( is_page($current_page) && in_array($current_page, $this->sad_pages)){
			echo "<script type='text/javascript' src='". plugins_url('public/assets/js/public.js?ver='. self::VERSION,dirname(__FILE__)) ."'></script>";
		}
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since     0.1
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since     0.1
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

}
