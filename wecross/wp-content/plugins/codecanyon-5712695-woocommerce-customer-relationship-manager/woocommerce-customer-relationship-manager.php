<?php
/**
 * Plugin Name: WooCommerce Customer Relationship Manager
 * Plugin URI: http://actualityextensions.com/
 * Description: Allows for better overview and management of WooCommerce customers, accounts and the communication between your business and theirs.
 * Version: 2.6.8
 * Author: Actuality Extensions
 * Author URI: http://actualityextensions.com/
 * Tested up to: 4.0
 *
 * Copyright: (c) 2015 Actuality Extensions (info@actualityextensions.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package     WC-Customer-Relationship-Manager
 * @author      Actuality Extensions
 * @category    Plugin
 * @copyright   Copyright (c) 2015, Actuality Extensions
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if (function_exists('is_multisite') && is_multisite()) {
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    if ( !is_plugin_active( 'woocommerce/woocommerce.php' ) )
        return;
}else{
    if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))))
        return; // Check if WooCommerce is active    
}

require_once( plugin_dir_path( __FILE__ ) . 'functions.php' );

if ( !class_exists( 'MCAPI_Wc_Crm' ) ) {
	require_once( 'admin/classes/api/MCAPI.class.php' );
}
if ( !class_exists( 'WooCommerce_Customer_Relationship_Manager' ) ) :

/**
 * Main WooCommerceCRM Class
 *
 * @class WooCommerce_Customer_Relationship_Manager
 * @version 2.1.0
 */
final class WooCommerce_Customer_Relationship_Manager {

    /**
     * The plugin's id
     * @var string
     */
    var $id         = 'wc-customer-relationship-manager';

    /**
     * @var string
     */
    var $version    = '4.3.1';

    /**
     * @var string
     */
    var $db_version = '4.0';

    /**
   * @var WC_Shipping The single instance of the class
   * @since 2.4.3
   */
    protected static $_instance = null;

    /**
     * Main WC_Shipping Instance
     *
     * Ensures only one instance of WC_Shipping is loaded or can be loaded.
     *
     * @since 2.4.3
     * @static
     * @return WC_Shipping Main instance
     */
    public static function instance() {
      if ( is_null( self::$_instance ) )
        self::$_instance = new self();
      return self::$_instance;
    }

    /**
     * Cloning is forbidden.
     *
     */
    public function __clone() {
      _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.4.3' );
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     */
    public function __wakeup() {
      _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce' ), '2.4.3' );
    }

		public function __construct() {
      global $statuses;

      
      define('WC_CRM_FILE', __FILE__);

      if( isset($_GET['page']) && ($_GET['page'] == 'wc_new_customer' || $_GET['page'] == 'wc_crm_import' ) )
        add_action( 'init', array( $this, 'wc_crm_add_filter_action_customer_created' ) );

    	add_action( 'woocommerce_init', array($this, 'includes') );

      // Define constants
      $this->define_constants();

      $this->current_tab = ( isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';

      // settings tab
      $this->settings_tabs = array();

      $statuses = $this->statuses =  array(
          'Customer' => 'Customer',
          'Lead' => 'Lead',
          'Follow-Up' => 'Follow-Up',
          'Prospect' => 'Prospect',
          'Favourite' => 'Favourite',
          'Blocked' => 'Blocked',
          'Flagged' => 'Flagged'
          );

      register_deactivation_hook( __FILE__, array($this, 'deactivate_pl') );
      
      add_action( 'admin_enqueue_scripts', array($this, 'enqueue_dependencies_admin') );

      add_action( 'admin_head', array($this, 'view_customer_button') );
      add_action( 'admin_head', array($this, 'view_customer_link') );

      
      add_action( 'wc_crm_restrict_list_logs', array($this, 'woocommerce_crm_restrict_list_logs') );
      

			add_filter( 'woocommerce_shop_order_search_fields', array($this, 'woocommerce_crm_search_by_email'), 50 );
			add_filter( 'views_edit-shop_order', array($this, 'views_shop_order') );
      add_filter( 'woocommerce_checkout_customer_userdata', array($this, 'wc_crm_add_customer_status'));		

			
      
      add_filter( 'user_contactmethods', array( $this, 'modify_contact_methods') );
      add_action( 'show_user_profile', array( $this, 'add_user_field_status') );
      add_action( 'edit_user_profile', array( $this, 'add_user_field_status') );

      add_action( 'personal_options_update', array( $this, 'save_user_field_status')  );
      add_action( 'edit_user_profile_update', array( $this, 'save_user_field_status')  );

      add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'select_customer_id' ) );	  

      add_action('plugins_loaded', array( $this, 'init') );

      add_filter( 'woocommerce_screen_ids', array( $this, 'woocommerce_screen_ids') );
      
      
		}
    function wc_crm_add_filter_action_customer_created()
    {
      add_filter('woocommerce_email_actions', 'wc_crm_automatic_emails_new_customer', 150, 1);
    }
    public function init() {
        load_plugin_textdomain( 'wc_customer_relationship_manager', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
    }
    public function woocommerce_screen_ids($screen_ids)
    {
      $screen = get_current_screen();
      #var_dump($screen->id);
      $screen_ids[] = 'wc_crm_accounts';
      $screen_ids[] = 'toplevel_page_wc-customer-relationship-manager';
      return $screen_ids;
    }
    
    function view_customer_button(){
      $screen = get_current_screen();
      if($screen->id != 'shop_order' || !isset($_GET['post']) || empty($_GET['post']) ) return;
      $crm_customer_link = get_option( 'woocommerce_crm_customer_link', 'customer' );

      $url = '';
      if($crm_customer_link == 'customer')
        $url = 'admin.php?page=wc_new_customer&order_id='.$_GET['post'];
        

      $user_id = get_post_meta( $_GET['post'], '_customer_user', true ); 
      if($user_id){
        if($crm_customer_link == 'customer')
          $url = get_admin_url().'admin.php?page=wc_new_customer&user_id='.$user_id;
        else
          $url = get_admin_url().'user-edit.php?user_id='.$user_id;
      }

      if(empty($url)) return false;
      ?>
      <script>
      jQuery(document).ready(function($){
        $('h2 .add-new-h2').after('<a class="add-new-h2 add-new-view-customer" href="<?php echo $url; ?>"><?php _e("View Customer", "wc_customer_relationship_manager"); ?></a>');
      });
      </script>
      <style>
        .wrap .add-new-h2.add-new-view-customer, .wrap .add-new-h2.add-new-view-customer:active{
          background: #2ea2cc;
          color:#fff
        }
        .wrap .add-new-h2.add-new-view-customer:hover{
          background: #1e8cbe;
          border-color: #0074a2;
        }
      </style>
      <?php
    }
    function view_customer_link(){
      $screen = get_current_screen();
      
      if($screen->id != 'edit-shop_order' && ( !isset($_GET['page']) || $_GET['page'] != 'wc_new_customer') ) return;
      $crm_customer_link = get_option( 'woocommerce_crm_customer_link', 'customer' );

      $url = '';
      if($crm_customer_link == 'customer'){
        $url = get_admin_url().'admin.php?page=wc_new_customer&user_id=';
        ?>
        <script>
        jQuery(document).ready(function($){

          $('td.column-order_title').each(function(){
            var $a = $(this).find('a').first().nextAll('a');
            if($a.length > 0 ){
              var user_id = $a.attr('href').replace('user-edit.php?user_id=', '');
              if(user_id){
                $a.attr('href', '<?php echo $url; ?>'+user_id);
              }
            }
          });
        });
        </script>
        <?php
      }       
    }

    /**
   * Define WC Constants
   */
  private function define_constants() {
    define( 'WC_CRM_PLUGIN_FILE', __FILE__ );
    define( 'WC_CRM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
    define( 'WC_CRM_VERSION', $this->version );
  }

    
    
  	
    

		public function select_customer_id(){
      if( isset($_GET['post_type']) && $_GET['post_type'] == 'shop_order' && isset($_GET['user_id']) && !empty($_GET['user_id']) ){
        $user_id = $_GET['user_id'];

        ob_start(); 
        $c_name = get_the_author_meta( 'user_firstname', $user_id ) . ' ' . get_the_author_meta( 'user_lastname', $user_id ) . ' (#'.$user_id . ' - ' . get_the_author_meta( 'user_email', $user_id ) . ')';
        ?>
          if( $().select2 ){
            jQuery("#customer_user").select2("data", {"id":"<?php echo $user_id; ?>","text":"<?php echo $c_name; ?>"});          
          }else{
            jQuery('#customer_user').append('<option selected="selected" value="<?php echo $user_id; ?>"><?php echo $c_name; ?>)</option>').val(<?php echo $user_id?>).trigger('chosen:updated');
          }


          <?php
          $customer_details = new WC_Crm_Customer_Details($user_id, 0);
          $customer_details->init_address_fields();
          $__b_address = $customer_details->billing_fields;
          $__s_address = $customer_details->shipping_fields;
          $formatted_shipping_address = wp_kses( $customer_details->get_formatted_shipping_address(), array( "br" => array() ) );
          //$formatted_shipping_address = str_replace('<br />', '<br />\\', $formatted_shipping_address);

          $formatted_billing_address = wp_kses( $customer_details->get_formatted_billing_address(), array( "br" => array() ) );
          //$formatted_billing_address = str_replace('<br />', '<br />\\', $formatted_billing_address);
            foreach ($__b_address as $key => $field ) { ?>
             jQuery('#_billing_<?php echo $key; ?>').val( "<?php echo addslashes(get_the_author_meta( 'billing_'.$key, $user_id ) ) ;?>" );
          <?php
            }
            foreach ($__s_address as $key => $field ) { ?>
             jQuery('#_shipping_<?php echo $key; ?>').val( "<?php echo addslashes(get_the_author_meta( 'shipping_'.$key, $user_id ) ) ;?>" );
          <?php
            }
          ?>
            jQuery('.order_data_column_container .order_data_column').last().find('.address')
            .html("<?php echo "<p><strong>" . __( "Address", "woocommerce" ) . ":</strong>". addslashes($formatted_shipping_address) . "</p>"; ?>");

            jQuery('.order_data_column_container .order_data_column').first().next().find('.address')
            .html("<?php echo "<p><strong>" . __( "Address", "woocommerce" ) . ":</strong>". addslashes($formatted_billing_address) . "</p>"; ?>");
        <?php

        $js_string = ob_get_contents();

        ob_end_clean();
        wc_enqueue_js( $js_string );
      }else if( isset($_GET['post_type']) && $_GET['post_type'] == 'shop_order' && isset($_GET['last_order_id']) && !empty($_GET['last_order_id']) ){
        $last_order_id = $_GET['last_order_id'];

        ob_start();
          $customer_details = new WC_Crm_Customer_Details(0, $last_order_id);
          $customer_details->init_address_fields();
          $__b_address = $customer_details->billing_fields;
          $__s_address = $customer_details->shipping_fields;
          $formatted_shipping_address = wp_kses( $customer_details->get_formatted_shipping_address(), array( "br" => array() ) );
          $formatted_shipping_address = str_replace('<br />', '<br />\\', $formatted_shipping_address);

          $formatted_billing_address = wp_kses( $customer_details->get_formatted_billing_address(), array( "br" => array() ) );
          $formatted_billing_address = str_replace('<br />', '<br />\\', $formatted_billing_address);
            foreach ($__b_address as $key => $field ) {
              $name_var = 'billing_'.$key;
              $field_val = $customer_details->order->$name_var;
              ?>
             jQuery('#_billing_<?php echo $key; ?>').val( "<?php echo addslashes($field_val);?>" );
          <?php
            }
            foreach ($__s_address as $key => $field ) {
            $name_var = 'shipping_'.$key;
            $field_val = $customer_details->order->$name_var;
            ?>
            jQuery('#_shipping_<?php echo $key; ?>').val( "<?php echo addslashes($field_val);?>" );
          <?php
            }
          ?>
            jQuery('.order_data_column_container .order_data_column').last().find('.address')
            .html("<?php echo "<p><strong>" . __( "Address", "woocommerce" ) . ":</strong>". addslashes($formatted_shipping_address) . "</p>"; ?>");

            jQuery('.order_data_column_container .order_data_column').first().next().find('.address')
            .html("<?php echo "<p><strong>" . __( "Address", "woocommerce" ) . ":</strong>". addslashes($formatted_billing_address) . "</p>"; ?>");
        <?php

        $js_string = ob_get_contents();

        ob_end_clean();
        wc_enqueue_js( $js_string );
      }
    }

    public function deactivate_pl($networkwide)
    {
      global $wpdb;
 
      if (function_exists('is_multisite') && is_multisite()) {
          // check if it is a network activation - if so, run the activation function 
          // for each blog id
          if ($networkwide) {
              $old_blog = $wpdb->blogid;
              // Get all blog ids
              $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
              foreach ($blogids as $blog_id) {
                  switch_to_blog($blog_id);
                  $this->deactivate();
              }
              switch_to_blog($old_blog);
              return;
          }   
      } 
      $this->deactivate();
    }

    public function deactivate(){      
      global $wpdb;
      $table = $wpdb->prefix."wc_crm_customer_list";
      delete_option('wc_crm_db_version');

      $wpdb->query("DROP TABLE IF EXISTS $table");
    }

		/**
		 * Enqueue admin CSS and JS dependencies
		 */
		public function enqueue_dependencies_admin() {
      global $post_type, $wp_query, $post, $current_user;
      $screen       = get_current_screen();
      $wc_screen_id = sanitize_title( __( 'WooCommerce', 'woocommerce' ) );
      $suffix       = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
      if( $screen->id == 'wc_crm_accounts'){
          global $post;
          wp_register_script( 'wc-admin-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes' . $suffix . '.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'accounting', 'round', 'wc-enhanced-select', 'plupload-all', 'stupidtable' ), WC_VERSION );

          wp_enqueue_script( 'wc-admin-order-meta-boxes', WC()->plugin_url() . '/assets/js/admin/meta-boxes-order' . $suffix . '.js', array( 'wc-admin-meta-boxes' ), WC_VERSION );
          
          $params = array(
            'countries'              => json_encode( array_merge( WC()->countries->get_allowed_country_states(), WC()->countries->get_shipping_country_states() ) ),
            'i18n_select_state_text' => esc_attr__( 'Select an option&hellip;', 'woocommerce' )
          );

          wp_localize_script( 'wc-admin-order-meta-boxes', 'woocommerce_admin_meta_boxes_order', $params );

          $params = array(
            'remove_item_notice'            => __( 'Are you sure you want to remove the selected items? If you have previously reduced this item\'s stock, or this order was submitted by a customer, you will need to manually restore the item\'s stock.', 'woocommerce' ),
            'i18n_select_items'             => __( 'Please select some items.', 'woocommerce' ),
            'i18n_do_refund'                => __( 'Are you sure you wish to process this refund? This action cannot be undone.', 'woocommerce' ),
            'i18n_delete_refund'            => __( 'Are you sure you wish to delete this refund? This action cannot be undone.', 'woocommerce' ),
            'i18n_delete_tax'               => __( 'Are you sure you wish to delete this tax column? This action cannot be undone.', 'woocommerce' ),
            'remove_item_meta'              => __( 'Remove this item meta?', 'woocommerce' ),
            'remove_attribute'              => __( 'Remove this attribute?', 'woocommerce' ),
            'name_label'                    => __( 'Name', 'woocommerce' ),
            'remove_label'                  => __( 'Remove', 'woocommerce' ),
            'click_to_toggle'               => __( 'Click to toggle', 'woocommerce' ),
            'values_label'                  => __( 'Value(s)', 'woocommerce' ),
            'text_attribute_tip'            => __( 'Enter some text, or some attributes by pipe (|) separating values.', 'woocommerce' ),
            'visible_label'                 => __( 'Visible on the product page', 'woocommerce' ),
            'used_for_variations_label'     => __( 'Used for variations', 'woocommerce' ),
            'new_attribute_prompt'          => __( 'Enter a name for the new attribute term:', 'woocommerce' ),
            'calc_totals'                   => __( 'Calculate totals based on order items, discounts, and shipping?', 'woocommerce' ),
            'calc_line_taxes'               => __( 'Calculate line taxes? This will calculate taxes based on the customers country. If no billing/shipping is set it will use the store base country.', 'woocommerce' ),
            'copy_billing'                  => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
            'load_billing'                  => __( 'Load the customer\'s billing information? This will remove any currently entered billing information.', 'woocommerce' ),
            'load_shipping'                 => __( 'Load the customer\'s shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
            'featured_label'                => __( 'Featured', 'woocommerce' ),
            'prices_include_tax'            => esc_attr( get_option( 'woocommerce_prices_include_tax' ) ),
            'round_at_subtotal'             => esc_attr( get_option( 'woocommerce_tax_round_at_subtotal' ) ),
            'no_customer_selected'          => __( 'No customer selected', 'woocommerce' ),
            'plugin_url'                    => WC()->plugin_url(),
            'ajax_url'                      => admin_url( 'admin-ajax.php' ),
            'order_item_nonce'              => wp_create_nonce( 'order-item' ),
            'add_attribute_nonce'           => wp_create_nonce( 'add-attribute' ),
            'save_attributes_nonce'         => wp_create_nonce( 'save-attributes' ),
            'calc_totals_nonce'             => wp_create_nonce( 'calc-totals' ),
            'get_customer_details_nonce'    => wp_create_nonce( 'get-customer-details' ),
            'search_products_nonce'         => wp_create_nonce( 'search-products' ),
            'grant_access_nonce'            => wp_create_nonce( 'grant-access' ),
            'revoke_access_nonce'           => wp_create_nonce( 'revoke-access' ),
            'add_order_note_nonce'          => wp_create_nonce( 'add-order-note' ),
            'delete_order_note_nonce'       => wp_create_nonce( 'delete-order-note' ),
            'calendar_image'                => WC()->plugin_url().'/assets/images/calendar.png',
            'post_id'                       => isset( $post->ID ) ? $post->ID : '',
            'base_country'                  => WC()->countries->get_base_country(),
            'currency_format_num_decimals'  => wc_get_price_decimals(),
            'currency_format_symbol'        => get_woocommerce_currency_symbol(),
            'currency_format_decimal_sep'   => esc_attr( wc_get_price_decimal_separator() ),
            'currency_format_thousand_sep'  => esc_attr( wc_get_price_thousand_separator() ),
            'currency_format'               => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ), // For accounting JS
            'rounding_precision'            => WC_ROUNDING_PRECISION,
            'tax_rounding_mode'             => WC_TAX_ROUNDING_MODE,
            'product_types'                 => array_map( 'sanitize_title', get_terms( 'product_type', array( 'hide_empty' => false, 'fields' => 'names' ) ) ),
            'default_attribute_visibility'  => apply_filters( 'default_attribute_visibility', false ),
            'default_attribute_variation'   => apply_filters( 'default_attribute_variation', false ),
            'i18n_download_permission_fail' => __( 'Could not grant access - the user may already have permission for this file or billing email is not set. Ensure the billing email is set, and the order has been saved.', 'woocommerce' ),
            'i18n_permission_revoke'        => __( 'Are you sure you want to revoke access to this download?', 'woocommerce' ),
            'i18n_tax_rate_already_exists'  => __( 'You cannot add the same tax rate twice!', 'woocommerce' ),
            'i18n_product_type_alert'       => __( 'Your product has variations! Before changing the product type, it is a good idea to delete the variations to avoid errors in the stock reports.', 'woocommerce' )
          );

          wp_localize_script( 'wc-admin-meta-boxes', 'woocommerce_admin_meta_boxes', $params );

        wp_register_style( 'wc_crm_accounts', plugins_url( 'assets/css/admin-accounts.css', __FILE__ ) );
        wp_enqueue_style( 'wc_crm_accounts' );
        
      }
      if($this->is_crm_page()){
      wp_enqueue_media();
      wp_enqueue_script( array('jquery', 'editor', 'thickbox', 'media-upload') );
      wp_enqueue_style( 'thickbox' );
      wp_enqueue_style('wp-color-picker');
      wp_enqueue_script('custom-background');

			wp_register_script( 'textbox_js', plugins_url( 'assets/js/TextboxList.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'textbox_js' );
			wp_register_script( 'timer', plugins_url( 'assets/js/jquery.timer.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'timer' );
			wp_register_script( 'jquery-ui', plugins_url( 'assets/js/jquery-ui.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'jquery-ui' );
			wp_register_script( 'growing_input', plugins_url( 'assets/js/GrowingInput.js', __FILE__ ), array('jquery') );
			wp_enqueue_script( 'growing_input' );
			wp_register_style( 'textbox_css', plugins_url( 'assets/css/TextboxList.css', __FILE__ ) );
			wp_enqueue_style( 'textbox_css' );
			wp_register_style( 'jquery-ui-css', plugins_url( 'assets/css/jquery-ui.css', __FILE__ ) );
			wp_enqueue_style( 'jquery-ui-css' );

      wp_register_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css' );

			wp_register_style( 'woocommerce-customer-relationship-style-admin', plugins_url( 'assets/css/admin.css', __FILE__ ), array('textbox_css', 'woocommerce_admin_styles') );
			wp_enqueue_style( 'woocommerce-customer-relationship-style-admin' );
			wp_register_script( 'woocommerce-customer-relationship-script-admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array('jquery', 'textbox_js', 'growing_input') );
			wp_enqueue_script( 'woocommerce-customer-relationship-script-admin' );

			wp_register_script( 'woocommerce_admin_crm', plugins_url() . '/woocommerce/assets/js/admin/woocommerce_admin.min.js', array('jquery', 'jquery-blockui', 'jquery-placeholder', 'jquery-ui-sortable', 'jquery-ui-widget', 'jquery-ui-core', 'jquery-tiptip') );
			wp_enqueue_script( 'woocommerce_admin_crm' );
			wp_register_script( 'woocommerce_tiptip_js', plugins_url() . '/woocommerce/assets/js/jquery-tiptip/jquery.tipTip.min.js' );
			wp_enqueue_script( 'woocommerce_tiptip_js' );

      if ( defined('WC_VERSION') && version_compare( WC_VERSION, '2.3', '>=' ) ) {
            if( !wp_script_is( 'select2', 'enqueued' ) ){
                wp_register_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2' . $suffix . '.js', array( 'jquery' ), '3.5.2' );
                wp_register_script( 'wc-enhanced-select', WC()->plugin_url() . '/assets/js/admin/wc-enhanced-select' . $suffix . '.js', array( 'jquery', 'select2' ), WC_VERSION );
                wp_localize_script( 'select2', 'wc_select_params', array(
                    'i18n_matches_1'            => _x( 'One result is available, press enter to select it.', 'enhanced select', 'woocommerce' ),
                    'i18n_matches_n'            => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'woocommerce' ),
                    'i18n_no_matches'           => _x( 'No matches found', 'enhanced select', 'woocommerce' ),
                    'i18n_ajax_error'           => _x( 'Loading failed', 'enhanced select', 'woocommerce' ),
                    'i18n_input_too_short_1'    => _x( 'Please enter 1 or more characters', 'enhanced select', 'woocommerce' ),
                    'i18n_input_too_short_n'    => _x( 'Please enter %qty% or more characters', 'enhanced select', 'woocommerce' ),
                    'i18n_input_too_long_1'     => _x( 'Please delete 1 character', 'enhanced select', 'woocommerce' ),
                    'i18n_input_too_long_n'     => _x( 'Please delete %qty% characters', 'enhanced select', 'woocommerce' ),
                    'i18n_selection_too_long_1' => _x( 'You can only select 1 item', 'enhanced select', 'woocommerce' ),
                    'i18n_selection_too_long_n' => _x( 'You can only select %qty% items', 'enhanced select', 'woocommerce' ),
                    'i18n_load_more'            => _x( 'Loading more results&hellip;', 'enhanced select', 'woocommerce' ),
                    'i18n_searching'            => _x( 'Searching&hellip;', 'enhanced select', 'woocommerce' ),
                ) );
                wp_localize_script( 'wc-enhanced-select', 'wc_enhanced_select_params', array(
                    'ajax_url'                         => admin_url( 'admin-ajax.php' ),
                    'search_products_nonce'            => wp_create_nonce( 'search-products' ),
                    'search_customers_nonce'           => wp_create_nonce( 'search-customers' )
                ) );

                wp_enqueue_script('wc-enhanced-select');
            }

        }else{
            wp_register_script('chosen_js', WC()->plugin_url() . '/assets/js/chosen/chosen.jquery.min.js', array('jquery'), '2.66', true);
            wp_register_script('ajax-chosen_js', WC()->plugin_url() . '/assets/js/chosen/ajax-chosen.jquery.min.js', array('jquery'), '2.66', true);

            wp_enqueue_script('chosen_js');
            wp_enqueue_script('ajax-chosen_js');                
        }

				wp_register_script( 'mousewheel', plugins_url( 'assets/js/jquery.mousewheel.js', __FILE__ ), array('jquery') );
				wp_enqueue_script( 'mousewheel' );


        if( isset($_GET['page']) && $_GET['page'] == 'wc_new_customer' ){
            wp_register_script( 'jquery-blockui', WC()->plugin_url() . '/assets/js/jquery-blockui/jquery.blockUI.min.js', array( 'jquery' ), '2.66', true );
            wp_enqueue_script( 'jquery-blockui' );
            if( (isset($_GET['user_id']) && !empty($_GET['user_id']) )
            || ( isset($_GET['order_id']) && !empty($_GET['order_id']) ) ){
              if( !class_exists('acf') ){
                wp_register_script( 'google_map', 'http://maps.google.com/maps/api/js?sensor=true', array( 'jquery' ), '3.0', true );
                wp_register_script( 'jquery_ui_map', plugins_url( 'assets/js/jquery.ui.map.full.min.js', __FILE__ ), array( 'google_map' ), '3.0', true );
                wp_enqueue_script( 'google_map' );
                wp_enqueue_script( 'jquery_ui_map' );
              }
            }
            $params = array(
              'ajax_loader_url'     => apply_filters( 'woocommerce_ajax_loader_url', WC()->plugin_url() . '/assets/images/ajax-loader@2x.gif' ),              
              'ajax_url'            => admin_url('admin-ajax.php'),
              'wc_crm_loading_states'    => wp_create_nonce("wc_crm_loading_states"),
              'update_customer_table'    => wp_create_nonce("update_customer_table"),
              'copy_billing'          => __( 'Copy billing information to shipping information? This will remove any currently entered shipping information.', 'woocommerce' ),
            );

            wp_localize_script( 'woocommerce-customer-relationship-script-admin', 'wc_crm_customer_params', $params );

        }
        if( isset($_GET['page']) && $_GET['page'] == 'wc-customer-relationship-manager' ){
            $params = array(
              'ajax_url'            => admin_url('admin-ajax.php'),
              'update_customer_table'    => wp_create_nonce("update_customer_table"),
            );

            wp_localize_script( 'woocommerce-customer-relationship-script-admin', 'wc_crm_customer_params', $params );

        }
       if( isset($_GET['page']) && $_GET['page'] == 'wc-customer-relationship-manager' ){
         $params_crm = array(
              'curent_time'    => current_time('Y-m-d'),
              'curent_time_h'  => current_time('g'),
              'curent_time_m'  => current_time('i'),
              'curent_time_s'  => current_time('s'),
            );
          wp_localize_script( 'woocommerce-customer-relationship-script-admin', 'wc_crm_params', $params_crm );
      }
      if( isset($_GET['page']) && ( ($_GET['page'] == 'wc_crm_settings' && isset($_GET['tab']) && $_GET['tab'] == 'statuses') || $_GET['page'] == 'wc-customer-relationship-manager') ){
        wp_enqueue_script( 'fonticonpicker', plugins_url('/assets/plugins/fontpicker/jquery.fonticonpicker.js', __FILE__ ));

        wp_enqueue_style( 'fonticonpicker_styles', plugins_url('/assets/plugins/fontpicker/css/jquery.fonticonpicker.css', __FILE__ ) );
        wp_enqueue_style( 'fonticonpicker_theme', plugins_url('/assets/plugins/fontpicker/theme/jquery.fonticonpicker.grey.css', __FILE__ ) );
        wp_enqueue_style( 'fonticonpicker_theme_darkgrey', plugins_url('/assets/plugins/fontpicker/theme/jquery.fonticonpicker.darkgrey.css', __FILE__ ) );
        
        wp_enqueue_style( 'fonticonpicker_fonts_fontello', plugins_url('/assets/plugins/fontpicker/fonts/fontello/css/fontello.css', __FILE__ ) );
        wp_enqueue_style( 'fonticonpicker_fonts_icomoon', plugins_url('/assets/plugins/fontpicker/fonts/icomoon/style.css', __FILE__ ) );
      }

			add_thickbox();
      }else{
        wp_enqueue_style( 'woocommerce-customer-relationship-style-admin', plugins_url( 'assets/css/admin.css', __FILE__ ) );
      }
		}


		/**
		 * Include required files
		 */
		public function includes() {
      require_once( 'admin/classes/wc_crm_customer.php' );
      if ( is_admin() && !is_network_admin() ) {
        require_once( 'admin/admin-init.php' ); // Admin section

        require_once( 'admin/classes/wc_crm_orders.php' );

        require_once( 'admin/classes/wc_crm_customers_table.php' );
        require_once( 'admin/classes/wc_crm_email_handling.php' );
        require_once( 'admin/classes/wc_crm_phone_call.php' );


        require_once( 'admin/classes/wc_crm_post-types.php' );
        require_once( 'admin/accounts.php' );

        if (defined('DOING_AJAX')) {
          require_once( 'admin/classes/wc_crm_ajax.php' );
        }else{
          require_once( 'admin/classes/wc_crm_install.php' );
        }

        /*********** ACF ************/
        if (class_exists('acf_controller_post'))
          require_once( 'admin/classes/wc_crm_acf.php' );       
        /***********************/
        
        
			}
		}

		
    /****************/

    public function woocommerce_crm_types_of_activity_filter() {
      global $activity_types;
      ?>
      <select name='activity_types' id='dropdown_activity_types'>
        <option value=""><?php _e( 'Show all types', 'woocommerce' ); ?></option>
        <?php
          foreach ( $activity_types as $type=>$count ) {
            echo '<option value="' . esc_attr( $type ) . '"';

            if ( isset( $_REQUEST['activity_types'] ) ) {
              selected( $type, $_REQUEST['activity_types'] );
            }
            echo '>' . esc_html__( $type, 'woocommerce' ) . ' (' . absint( $count ) . ')</option>';
          }
        ?>
      </select>
      <?php
    }
    public function woocommerce_crm_created_date_filter() {
      global $months, $wp_locale;
      $month_count = count( $months );

      if ( !$month_count || ( 1 == $month_count && 0 == $months[0]->month ) )
        return;

      $m = isset( $_GET['created_date'] ) ? (int) $_GET['created_date'] : 0;
      $m = isset( $_POST['created_date'] ) ? (int) $_POST['created_date'] : $m;
        ?>
            <select name='created_date' id="created_date">
              <option<?php selected( $m, 0 ); ?> value='0'><?php _e( 'Show all dates' ); ?></option>
        <?php
            foreach ( $months as $arc_row ) {
              if ( 0 == $arc_row->year )
                continue;

              $month = zeroise( $arc_row->month, 2 );
              $year = $arc_row->year;

              printf( "<option %s value='%s'>%s</option>\n",
                selected( $m, $year . $month, false ),
                esc_attr( $arc_row->year . $month ),
                /* translators: 1: month name, 2: 4-digit year */
                sprintf( __( '%1$s %2$d' ), $wp_locale->get_month( $month ), $year )
              );
            }
        ?>
      </select>
        <?php
    }
    public function woocommerce_crm_log_username_filter() {
      global $log_users;
      ?>
      <select name='log_users' id='dropdown_log_users'>
        <option value=""><?php _e( 'Show all authors', 'woocommerce' ); ?></option>
        <?php
          foreach ( $log_users as $userid=>$count ) {
            $userdata = get_userdata( $userid );
            echo '<option value="' . absint( $userid ) . '"';

            if ( isset( $_REQUEST['log_users'] ) ) {
              selected( $userid, $_REQUEST['log_users'] );
            }
            echo '>' . $userdata->first_name.' '.$userdata->last_name  . ' (' . absint( $count ) . ')</option>';
          }
        ?>
      </select>
      <?php
    }

    /**
     * Filter for Logs page
     *
     */
    public function woocommerce_crm_restrict_list_logs() {
        $woocommerce_crm_filters_log = array(
            'types_of_activity',
            'created_date',
            'log_username'
          );
          ?>
          <div class="alignleft actions">
          <?php
            foreach ($woocommerce_crm_filters_log as $key => $value) {
                add_action( 'woocommerce_crm_add_filters_log', array($this, 'woocommerce_crm_'.$value.'_filter') );
            }
            do_action( 'woocommerce_crm_add_filters_log');
          ?>
          <input type="submit" id="post-query-submit" class="button action" value="Filter"/>
        </div>
          <?php
          $js = "
              if( $().select2 ){
                jQuery('select#dropdown_activity_types').css('width', '150px').select2();

                jQuery('select#dropdown_log_users').css('width', '150px').select2();

                jQuery('select#created_date').css('width', '150px').select2();
              }else{
                jQuery('select#dropdown_activity_types').css('width', '150px').chosen();

                jQuery('select#dropdown_log_users').css('width', '150px').chosen();

                jQuery('select#created_date').css('width', '150px').chosen();
              }
            ";

      if ( class_exists( 'WC_Inline_Javascript_Helper' ) ) {
        $woocommerce->get_helper( 'inline-javascript' )->add_inline_js( $js );
      } elseif( function_exists('wc_enqueue_js') ){
        wc_enqueue_js($js);
      }  else {
        $woocommerce->add_inline_js( $js );
      }
    }
		

		

    public function modify_contact_methods($profile_fields) {

        // Add new fields
        $profile_fields['twitter'] = 'Twitter Username';
        $profile_fields['skype'] = 'Skype';

        return $profile_fields;
      }

    public function save_user_field_status($user_id ) {
        if ( !current_user_can( 'edit_user', $user_id ) )
            return false;
        update_user_meta( $user_id, 'customer_status', $_POST['customer_status'] );
      }

    public function add_user_field_status($user ) {
      ?>
      <table class="form-table">
          <tr>
              <th><label for="dropdown"><?php _e( 'Customer status', 'wc_customer_relationship_manager' ) ?></label></th>
              <td>
                  <select id="customer_status" name="customer_status" class="chosen_select">
                      <?php
                      $selected = get_the_author_meta( 'customer_status', $user->ID );
                      if ( empty($selected) ) $selected ='Lead';
                      $statuses = wc_crm_get_statuses_slug();
                      foreach ( $statuses as $key => $status ) {
                        echo '<option value="' . esc_attr( $key ) . '" ' . selected( $key, $selected, false ) . '>' . esc_html__( $status, 'wc_customer_relationship_manager' ) . '</option>';
                      }
                  ?>
                  </select>
                  <span class="description"></span>
              </td>
          </tr>
      </table>
      <?php
      }

		

		/**
		 * Overrides the WooCommerce search in orders capability if we search by customer.
		 *
		 * @param $fields
		 * @return array
		 */
		public function woocommerce_crm_search_by_email( $fields ) {
			if ( isset( $_GET["search_by_email_only"] ) ) {
				return array('_billing_email');
			}
			return $fields;
		}

		/**
		 * @param $views
		 * @return array
		 */
		public function views_shop_order( $views ) {
			if ( isset( $_GET["search_by_email_only"] ) ) {
				return array();
			}
			return $views;
		}

		/**
		 * get_tab_in_view()
		 *
		 * Get the tab current in view/processing.
		 */
		function get_tab_in_view( $current_filter, $filter_base ) {
			return str_replace( $filter_base, '', $current_filter );
		}

		
    public function wc_crm_add_customer_status($userdata){
      update_user_meta( $userdata['ID'], 'customer_status', 'Customer' );
      return $userdata;
    }

    /** Helper functions ******************************************************/

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url() {
        return untrailingslashit( plugins_url( '/', __FILE__ ) );
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path() {
        return untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Check if current page is crm screen
     *
     * @return boolean
     */
    public function is_crm_page(){
      if(isset($_GET['page']) && (
        $_GET['page'] == 'wc-customer-relationship-manager' ||
        $_GET['page'] == 'wc_new_customer' ||
        $_GET['page'] == 'wc_crm_logs' ||
        $_GET['page'] == 'wc_crm_settings' ||
        $_GET['page'] == 'wc_crm_import' ||
        $_GET['page'] == 'wc_crm_accounts' ||
        $_GET['page'] == 'wc_user_grps'
        )
      ){
        return true;
      }
      return false;
    }

    /** Load Instances on demand **********************************************/

    /**
     * Get Customer Class.
     *
     * @return WC_Crm_Customer
     */
    public function customer() {
      return WC_Crm_Customer::instance();
    }

    public function customer_detail() {
      return new WC_Crm_Customer_Details;
    }

    public function customers_table() {
      return new WC_Crm_Customers_Table;
    }

    public function email_handling() {
      return new WC_Crm_Email_Handling;
    }

    public function phone_call() {
      return new WC_Crm_Email_Handling;
    }
    public function orders() {
      return WC_Crm_Orders::instance();
    }


	}

endif;

/**
 * Returns the main instance of WC_CRM to prevent the need to use globals.
 *
 * @since  2.1
 * @return WooCommerce_Customer_Relationship_Manager
 */
function WC_CRM() {
  return WooCommerce_Customer_Relationship_Manager::instance();
}

// Global for backwards compatibility.
$GLOBALS['woocommerce'] = WC_CRM();
