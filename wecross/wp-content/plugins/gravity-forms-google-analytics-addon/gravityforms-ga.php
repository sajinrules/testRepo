<?php
/*
Plugin Name: Gravity Forms: Google Analytics Addon
Plugin URI: http://onlineboswachters.nl
Description: When using Gravity Forms and Google Analytics you want to measure conversion rates of forms. When using a Page as Confirmation you can track that pretty easy in GA. But when you choose Text because you don't want visitors to leave the page the form is on you can't track that by default. So you want to use a Google Analytics Pageview.
Version: 0.7.3
Author: Online Boswachters
Author URI: http://onlineboswachters.nl
*/

/* Copyright 2014 Online Boswachters (email : info@onlineboswachters.nl) */

$pluginurl = plugin_dir_url(__FILE__);	
define( 'gfga_FRONT_URL', $pluginurl );
define( 'gfga_URL', plugin_dir_url(__FILE__) );
define( 'gfga_PATH', plugin_dir_path(__FILE__) );
define( 'gfga_BASENAME', plugin_basename( __FILE__ ) );
define( 'wpplugin_VERSION', '0.7.3' );

class gravityforms_ga {
	
	function __construct() {
		
		$this->get_options();
		
		if (is_admin()) :
			$this->add_admin_includes();
			add_action( 'admin_init', array( $this, 'options_init' ) );
			add_filter( 'gform_addon_navigation', array(&$this,'gform_addon_navigation'));
		endif;
		
		add_filter("gform_confirmation", array($this, "custom_confirmation"), 10, 4);
		add_action("gform_post_paging", array($this, "custom_confirmation_page"), 10, 3);
		add_action("wp_footer", array(&$this,'enqueue_custom_scripts'));
		
	}
	
	/* OPTIONS */
	
	/**
	 * Register the options needed for this plugins configuration pages.
	 */
	function options_init() {
		register_setting( 'gravityforms_ga_settings', 'gfga_settings' );
	}
	
	/**
	 * Retrieve an option for the configuration page.
	 */
	function get_option($key = '') {
		if (!empty($this->options) && isset($this->options[$key])) {
			if (is_array($this->options)) :
				return $this->options[$key];
			else :
				return stripslashes($this->options[$key]);
			endif;
		}
		return false;
	}
	/**
	 * Retrieve all options for the configuration page from WP Options.
	 */
	function get_options() {
		if (isset($this->options)) return $this->options;
		if ($options = get_option('gfga_settings')) {
			if (is_array($options)) :
				$this->options = $options;
			else :
				$this->options = unserialize($options);	
			endif;
		}
	}
	
	/**
	 * Save all options to WP Options database
	 */
	function save_options() {
		if (!empty($this->options)) {
			update_option('gfga_settings', serialize($this->options));	
		}
	}
	
	/**
	 * Save a specifix option to WP Option database
	 */
	function save_option($key, $value, $save_to_db = false) {
		if (!empty($this->options)) {
			$this->options[$key] = $value;
		}
		if ($save_to_db == true) {
			$this->save_options();	
		}
	}
	
	/* INCLUDES */
	
	/**
	 * Include specific PHP files when visiting an admin page
	 */
	function add_admin_includes() {
		$includes = array('plugin-admin'); //add includes here that are in the includes fodler, without the .php
		$this->add_includes($includes);
	}
	
	/**
	 * Include specific PHP files when visiting a page on the website
	 */
	function add_includes($includes_new = array()) {
		$includes = array(); //add includes here that are in the includes fodler, without the .php
		if (is_array($includes_new)) $includes = $includes_new;
		if (!count($includes)) return false;
		foreach ($includes as $_include) :		
			$path = gfga_PATH.'includes/'.$_include.'.php';
			if (!file_exists($path)) continue;
			include_once($path);
		endforeach;
	}
	
	/* HELPERS */
	
	/**
	 * Custom function to retrieve an specific value from the options
	 */
	function get_form_setting($key,$form_id) {		
		$form_settings = $this->get_options();
		if (!isset($form_settings['form_'.$key.'_'.$form_id])) return;
		return $form_settings['form_'.$key.'_'.$form_id];
	}
	
	/**
	 * Build Confirmation in the format Gravity Forms does it
	 * (with pageview most likely)
	 */
	function get_gf_confirmation($content, $form_id = '') {
		if (empty($form_id)) :
			$form_id = (isset($this->form_id)) ? $this->form_id : '';		
		endif;
		if (empty($form_id)) $form_id = 1;
		
		$start = "<div id='gform_confirmation_wrapper_".$form_id."' class='gform_confirmation_wrapper '><div id='gform_confirmation_message_".$form_id."' class='gform_confirmation_message_".$form_id." gform_confirmation_message'>";
		$end = '</div></div>';
		return $start . $content . $end;
	}
	
	/* CORE */
	
	/**
	 * Check if a form has a URL set for the pageview
	 */
	function do_pageview($form_id) {
		
		if (!isset($this->options['form_url_'.$form_id])) return false;
		if (!empty($this->options['form_url_'.$form_id])) return true;
		else return false;
	}
	
	/**
	 * Hook for Gravity Forms to include pageview in the Confirmation
	 */
	function custom_confirmation($confirmation, $form, $lead, $ajax) {
		
		//We have to know if form checkbox is checked in our settings page
		if (!$this->do_pageview($form['id'])) return $confirmation;
		
		//we only have to be activated when confirmation is Text, so not Redirect or Page
		if ($form['confirmation']['type'] != 'message')  return $confirmation;
		//if (strpos($confirmation,'document.location.href')) (fallback)
		
		$this->form_id 	= $form['id'];
		$message 		= $form['confirmation']['message'];
		$url 			= esc_js(esc_url($this->get_form_setting('url',$form['id'])));
		if (empty($url)) return;
		
		$type 			= $this->get_option('universal_gaq');
		
		if ($type == 'on') :
			$script 		= "<script>if (typeof __gaTracker == 'function') { __gaTracker('send', 'pageview', '".$url."'); } else if (typeof ga == 'function') { ga('send', 'pageview', '".$url."'); } </script>";
		else :
			$script 		= "<script>var _gaq = _gaq || []; _gaq.push(['_trackPageview', '".$url."']); </script>";
		endif;
		
		$message .= $script;
		
		$confirmation = $this->get_gf_confirmation($message);
		return $confirmation;
	}
	
	/**
	 * Hook for Gravity Forms to include pageview when working with multiple pages
	 */
	function custom_confirmation_page($form, $source_page_number, $current_page_number) {
		
		//Q: What when we dont have ajax pages?
		
		$this->form_id 	= $form['id'];
		$url 			= esc_js(esc_url($this->get_form_setting('url_page_'.$current_page_number,$form['id'])));
		//remove domain from URL
		
		
		if (empty($url)) return;
		
		$type 			= $this->get_option('universal_gaq');
		if ($type == 'on') :
			$type = 'ua';
		else :
			$type = 'ga';
		endif;
		
		//TODO get all post variables and add those as GA Events
		
		$script 		= "<script>var gfga_parent = window.parent; if (typeof gfga_parent.gfga_pageview == 'function') { gfga_parent.gfga_pageview('".esc_js($url)."','".$type."'); }</script>";		
		
		echo $script;
	}

	function enqueue_custom_scripts() {
		//TODO: we only want to add this is the current form on the page has multiple pages	
		$type 			= $this->get_option('universal_gaq');	
		?>
        <script>//<![CDATA[
			<?php if ($type == 'on') : ?>
			<?php else: ?>
			var _gfga_gaq = _gaq || [];
			<?php endif; ?>
			function gfga_pageview(url,type) {
				if (type == 'ua') {
					if (typeof __gaTracker == 'function') {
						__gaTracker('send', 'pageview', url);
					} else if (typeof ga == 'function') {
						ga('send', 'pageview', url);
					}
				} else {
					_gfga_gaq.push(['_trackPageview', url]);
				}
			}
		//]]></script>
        <?php
	}
	
	/* ADMIN */
	
	/**
	 * Add this plugin to the Gravity Forms menu in the WP Admin
	 */
	function gform_addon_navigation($menu_items){
   		$menu_items[] = array("name" => "gfga_settings", "label" => "Google Analytics", "callback" => array( $this, 'gfga_settings' ), "permission" => "edit_posts");
    	return $menu_items;
	}
	
	/**
	 * The settings page where you can edit the options of this plugin
	 */
	function gfga_settings() {
		
		global $table_prefix;
		global $plugin_admin;
		
		if ( !is_plugin_active('gravityforms/gravityforms.php') ) :
			?>
            <p>You need Gravity Forms for this plugin to work properly.</p>
			<?php
		else :
			
			/* GRAVITY FORMS */
			$forms = RGFormsModel::get_forms( null, 'title' );
			
			$plugin_admin->admin_header(true, 'gravityforms_ga_settings', 'gfga_settings');
			
			$content = '<p>Your overview of forms in Gravity Forms. You can select a url to use as Pageview if you have Text as confirmation. With URL we don\'t mean domain and extention. So only /tank-you-page instead of domain.com/thank-you-page.</p>';
			$content .= '<p>This plugin only looks at your first Confirmation. So if you have more and your second one is a Text Confirmation, then the Pageview isn\'t triggered.</p>';
			
			$content .= $plugin_admin->checkbox('universal_gaq',__('Use Universal implementation instead of ga.js implementation (_gaq)?'));
			
			foreach ($forms as $_form) :
				$form_id = $_form->id;
				$this->form_id = $form_id;
				$content .= '<h3>'.__('Form:').' '.strip_tags($_form->title).'</h3>';
				$meta = GFFormsModel::get_form_meta($form_id);
				
				$confirmation = array_shift($meta['confirmations']);
				
				$admin_url = get_admin_url().'admin.php?page=gf_edit_forms&view=settings&subview=confirmation&id='.$form_id;
				$content .= '<strong>Text Confirmation</strong><br/>';
				if ($confirmation['type'] != 'message') :
					$content .= '<p>'.sprintf(__('Form \'%s\' doesn\'t have a Text as Confirmation.'),strip_tags($_form->title)).'<br/><a href="'.$admin_url.'">Edit Form Confirmations</a><br/>' . '</p>';
				else :
					$content .= $plugin_admin->textinput('form_url_'.$form_id.'',__('URL for form ').strip_tags($_form->title)).'<a href="'.$admin_url.'">Edit Form Confirmations</a><br/><br/>';
				endif;
				$page_fields = $this->has_form_page_field($meta);
				if (count($page_fields)) :
					$content .= '<strong>This form has multiple pages, add pageview URL\'s</strong></br>';
					$i = 1;
					foreach ($page_fields as $_field) :
						//if ($i == 1) continue; //we skip the first page
						$page_number = $i + 1;
						$content .= $plugin_admin->textinput('form_url_page_'.$page_number.'_'.$form_id.'',sprintf(__('URL for page #%d'),$page_number)).'<br/>';
						++$i;
					endforeach;
				endif;
								
				
			endforeach;
			
			$plugin_admin->postbox( 'gfga_settings', __( 'Pageview Settings', 'gravityforms-ga' ), $content );
			
			$plugin_admin->admin_footer();
			
		endif;
	}
	
	function has_form_page_field($meta) {
		if (!isset($meta['fields'])) return false;
		$page_fields = array();
		foreach ($meta['fields'] as $_field) :
			if ($_field['type'] == 'page') $page_fields[] = $_field;
		endforeach;
		return $page_fields;
	}

}
$gravityforms_ga = new gravityforms_ga();
?>