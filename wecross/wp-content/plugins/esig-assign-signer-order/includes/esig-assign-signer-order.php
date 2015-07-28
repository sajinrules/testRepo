<?php
/**
 * 
 * @package ESIG_ASSIGN_ORDER
 * @author  Approve me <abushoaib73@gmail.com>
 */
if (!class_exists('ESIG_ASSIGN_ORDER')) :
class ESIG_ASSIGN_ORDER {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.1
	 *
	 * @var     string
	 */
	const VERSION = '1.1.1';
	
	

	/**
	 *
	 * Unique identifier for plugin.
	 *
	 * @since     0.1
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'esig-order';

	/**
	 * Instance of this class.
	 *
	 * @since     1.0.1
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

		// Load plugin text domain
		add_action( 'init', array($this, 'load_plugin_textdomain') );
	
	}
   
  
	
	/**
	 * Returns the plugin slug.
	 *
	 * @since     0.1
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Returns an instance of this class.
	 *
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
	 * Fired when the plugin is activated.
	 *
	 * @since     0.1
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
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		self::single_deactivate();
	}

	

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since     0.1
	 */
	private static function single_activate() {
		//@TODO: Define activation functionality here
        
         if(get_option('WP_ESignature__Assign_Signer_Order_documentation'))
        {
            update_option('WP_ESignature__Assign_Signer_Order_documentation','http://www.approveme.me');
            update_option('WP_ESignature__Assign_Signer_Order_setting_page','admin.php?page=esign-misc-general');
        }
        else
        {
           add_option('WP_ESignature__Assign_Signer_Order_setting_page','admin.php?page=esign-misc-general');
           add_option('WP_ESignature__Assign_Signer_Order_documentation','http://www.approveme.me');
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
	
	
}
endif;
