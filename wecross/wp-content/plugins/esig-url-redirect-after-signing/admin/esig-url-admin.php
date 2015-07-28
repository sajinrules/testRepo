<?php
/**
 *
 * @package ESIG_URL_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */

if (! class_exists('ESIG_URL_Admin')) :
class ESIG_URL_Admin {

	/**
	 * Instance of this class.
	 * @since    0.1
	 * @var      object
	 */
	protected static $instance = null;

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
		$plugin = ESIG_URL::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();
		
		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' );
		 
		add_action('esig_document_before_save', array($this, 'add_document_sidebar'), 10, 1);
		add_action('esig_document_before_edit_save', array($this, 'add_document_sidebar'), 10,1);
		add_action('esig_email_sent', array($this, 'esig_url_redirect'),10,1);
		
	    add_action('admin_enqueue_scripts', array($this, 'queueScripts'));
		add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );	
	}
	
	public function enqueue_admin_styles() {

		$screen = get_current_screen();
		$admin_screens = array(
			'admin_page_esign-add-document',
			'admin_page_esign-edit-document'
		);
		
		if (in_array($screen->id, $admin_screens)) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/esig_url_redirect.css', __FILE__ ), array(), ESIG_URL::VERSION );
		}

	}
	
	public function queueScripts(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('esig_url_redirect',plugins_url( '/assets/js/redirect.js', __FILE__), false, '1.0.1', true );
		wp_enqueue_script('esig_url_redirect1',plugins_url( '/assets/js/redirect_other.js', __FILE__), false, '1.0.1', true );
		wp_localize_script(
      'esig_url_redirect',
      'ajax_script',
      array( 'ajaxurl' => admin_url('admin-ajax.php?action=redirectForm')));
	  
	  if(! function_exists('WP_E_Sig'))
				return ;
				
	  $esig = WP_E_Sig();
	  $api = $esig->shortcode;
	 
	  $document_max_id=$api->document->document_max()+1;
	  
	  wp_localize_script(
      'esig_url_redirect1',
      'esig_url_ajax_script',
      array( 'ajaxurl' => admin_url('admin-ajax.php?action=redirecturlForm'),
			 'urlid'=>$document_max_id));
	 
	}
	
	
  public function esig_url_redirect($args)
      { 
		$doc_id = $args['document']->document_id;
		   
		   if(! function_exists('WP_E_Sig'))
					return ;
					
		    $esig = WP_E_Sig();
			$api = $esig->shortcode;
			$doc_table = $api->document->table_prefix . 'documents';
			$stand_table = $api->document->table_prefix  . 'documents_stand_alone_docs';
			
		    global $wpdb;
			
			$sad_document=$wpdb->get_var("SELECT document_type FROM " . $doc_table . " WHERE document_id='" . $doc_id ."'");
			
		    $page_id = get_the_ID();
			 
		   if($sad_document=="stand_alone") 
					$doc_id=$wpdb->get_var("SELECT max(document_id) FROM " . $stand_table . " WHERE page_id='" .$page_id . "'");
		    
			
		  if(!$api->setting->get_generic('esig_url_redirect_'.$doc_id))
					 return ;
		     
			 
	       $get_url_redirect=$api->setting->get_generic('esig_url_redirect_'.$doc_id);
		   
		
			if(!preg_match("/http/",$get_url_redirect)) 
				{ 
					$get_url_redirect='http://' . $get_url_redirect;
				}
				
		   
		   wp_redirect($get_url_redirect, 301 );
		   exit;
	  }
	  
  public function add_document_sidebar()
		{
		   if(! function_exists('WP_E_Sig'))
						return ;
			
		   $esig = WP_E_Sig();
			$api = $esig->shortcode;
			
			$document_id=isset($_GET['document_id'])?$_GET['document_id']:''; 
		$load=apply_filters('esig_url_redirect_load',$document_id);
			  $content = '' ; 
				$file_name=plugins_url( 'assets\images/help.png', __FILE__);
			   $title = ' <a href="#" class="tooltip">
    <img src="'.$file_name.'" height="20px" align="left" />
    <span>
        '.__('If you would like to redirect the signer to a specific URL after succesfully signing your document, you can add the URL here.','esig-url').'
    </span>
</a> '.__('Document URL Redirect','esig-url') ; 
			   
			  // $content .= '<form name="redirectform" id="redirectForm" action="#" method="POST"><p>
			   
			  
			   
			  $content .= ' <input type="textbox" class="require"  name="esig_redirect_url" value=""> 
			   <input type="button" name="Add-submit" id="redirectForm" class="button-appme button" value="Add" /></p>' ;
			   
               $document_id = isset($_GET['document_id'])?$_GET['document_id']:$api->document->document_max()+1;
              
			   $content .= '<input type="hidden" name="esig_url_id" value="'. $document_id .'">';
               if(isset( $document_id) && $api->setting->get_generic('esig_url_redirect_'.$document_id)) 
			   {
              $content .=  '<p class="tagchecklist" id="esig_url_redirect"><span ><a href="#" id="urlid" class="ntdelbutton">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;</a>&nbsp;' . $api->setting->get_generic('esig_url_redirect_'. $document_id) . '</span></p>';
			  }else {
			   $content .= '<p class="tagchecklist" id="esig_url_redirect">'.__('www.domain.com or domain.com','esig-url').'</p>';
			  }
			   
			   
			   $api->view->setSidebar($title,$content,"urlredirect","urlredirectbody");

			   echo   $api->view->renderSidebar(); 	   
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

add_action('wp_ajax_redirectForm', 'redirectForm');
add_action('wp_ajax_nopriv_redirectForm', 'redirectForm');
function redirectForm(){  
	
	if(!function_exists('WP_E_Sig'))
				return ;
					
					
	  $esig = WP_E_Sig();
	  $api = $esig->shortcode;
	 
		if(isset($_POST['esig_url_id']) && $_POST['esig_url_id']==""){
				$document_max_id=$api->document->document_max()+1;
				}
				else {
				$document_max_id=$_POST['esig_url_id'];
				}
	  
	  if(!$api->setting->get_generic('esig_url_redirect_'.$document_max_id))
					 {
					
					$api->setting->set('esig_url_redirect_'.$document_max_id,$_POST['esig_redirect_url']);
					 }
					 else
					 {  
					 $api->setting->set('esig_url_redirect_'.$document_max_id,$_POST['esig_redirect_url']);
					 }	
			
    echo '<span class="url_redirect"><a href="#" id="urlid" class="ntdelbutton">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;</a>&nbsp;' . $api->setting->get_generic('esig_url_redirect_'.$document_max_id) . '</span>';
    
 die(); 
 } 

 
add_action('wp_ajax_redirecturlForm', 'redirecturlForm');
add_action('wp_ajax_nopriv_redirecturlForm', 'redirecturlForm');

function redirecturlForm(){  
	
	  $urlid=$_GET['url_id'];
	   
	   if(!function_exists('WP_E_Sig'))
				return ;
					
					
	  $esig = WP_E_Sig();
	  $api = $esig->shortcode;
	  
	  if(!$api->setting->get_generic('esig_url_redirect_'.$urlid))
					 {
					  _e('This url not exists','esig-url');
					 }
					 else
					 {
					$api->setting->delete('esig_url_redirect_'.$urlid);
					 }

   _e('www.domain.com or domain.com','esig-url');
    
 die(); 
 }   

