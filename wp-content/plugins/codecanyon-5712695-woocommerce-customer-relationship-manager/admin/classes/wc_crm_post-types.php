<?php

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

class WC_CRM_Post_Types {

    /**
     * Hook into ajax events
     */
    public function __construct() {

      add_action( 'init', array($this, 'register_post_types') );
      add_action( 'add_meta_boxes', array( $this, 'remove_meta_boxes' ), 10 );
      add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 30 );
      add_action( 'save_post', 'WC_Crm_Accounts::save_meta_boxes', 1, 2 );
      add_action( 'wc_crm_process_accounts_meta', 'WC_Crm_Accounts::save', 10, 2 );
      add_filter( 'post_row_actions','WC_Crm_Accounts::remove_quick_edit',10,1);

      add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ), 10, 1 );

      add_filter( 'comments_clauses', array( $this, 'exclude_comments' ), 10, 1 );
      add_action( 'comment_feed_join', array( $this, 'exclude_comments_from_feed_join' ) );
      add_action( 'comment_feed_where', array( $this, 'exclude_comments_from_feed_where' ) );
    }

    /**
   * Register core post types.
   */
  public static function register_post_types() {
    if ( post_type_exists('wc_crm_accounts') ) {
      return;
    }

    $args = array(
          'labels'              => array(
              'name'               => __( 'Accounts', 'wc_customer_relationship_manager' ),
              'singular_name'      => __( 'Account', 'wc_customer_relationship_manager' ),
              'add_new'            => __( 'Add Account', 'wc_customer_relationship_manager' ),
              'add_new_item'       => __( 'Add New Account', 'wc_customer_relationship_manager' ),
              'edit'               => __( 'Edit', 'wc_customer_relationship_manager' ),
              'edit_item'          => __( 'Edit Account', 'wc_customer_relationship_manager' ),
              'new_item'           => __( 'New Account', 'wc_customer_relationship_manager' ),
              'view'               => __( 'View Account', 'wc_customer_relationship_manager' ),
              'view_item'          => __( 'View Account', 'wc_customer_relationship_manager' ),
              'search_items'       => __( 'Search Accounts', 'wc_customer_relationship_manager' ),
              'not_found'          => __( 'No Accounts found', 'wc_customer_relationship_manager' ),
              'not_found_in_trash' => __( 'No Accounts found in trash', 'wc_customer_relationship_manager' ),
              'parent'             => __( 'Parent Account', 'wc_customer_relationship_manager' ),
              'menu_name'          => _x( 'Accounts', 'Admin menu name', 'wc_customer_relationship_manager' )
            ),
          'description'         => __( 'This is where Accounts are stored.', 'wc_customer_relationship_manager' ),
          'public'              => false,
          'show_ui'             => true,
          'capability_type'     => 'post',
          'map_meta_cap'        => true,
          'publicly_queryable'  => false,
          'exclude_from_search' => true,
          'show_in_menu'        => false,
          'hierarchical'        => false,
          'show_in_nav_menus'   => false,
          'rewrite'             => false,
          'query_var'           => false,
          'supports'            => array( 'custom-fields'),
          'has_archive'         => false,
        );
    register_post_type( 'wc_crm_accounts', $args);
  }

  function post_updated_messages($messages)
  {
    global $post_type;
    switch ($post_type) {
      case 'wc_crm_accounts':
        $messages['wc_crm_accounts'] = array(
           0 => '', // Unused. Messages start at index 1.
           1 => __('Account updated.', 'wc_customer_relationship_manager'),
           2 => __('Custom field updated.', 'wc_customer_relationship_manager'),
           3 => __('Custom field deleted.', 'wc_customer_relationship_manager'),
           4 => __('Account updated.', 'wc_customer_relationship_manager'),
          /* translators: %s: date and time of the revision */
           5 => isset($_GET['revision']) ? sprintf( __('Account restored to revision from %s', 'wc_customer_relationship_manager'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
           6 => __('Account published.', 'wc_customer_relationship_manager'),
           7 => __('Account saved.', 'wc_customer_relationship_manager'),
           8 => __('Account submitted.', 'wc_customer_relationship_manager'),
        );
        break;
    }
    return $messages;
  }

  /**
   * Remove bloat
   */
  public function remove_meta_boxes() {
      remove_meta_box( 'commentsdiv', 'wc_crm_accounts', 'normal' );
      remove_meta_box( 'woothemes-settings', 'wc_crm_accounts', 'normal' );
      remove_meta_box( 'commentstatusdiv', 'wc_crm_accounts', 'normal' );
      remove_meta_box( 'slugdiv', 'wc_crm_accounts', 'normal' );
  }

  /**
   * Add WC_CRM Accounts Meta boxes
   */
  public function add_meta_boxes() {
  add_meta_box( 'wc_crm_account_data', __( 'Account Data', 'wc_customer_relationship_manager' ), 'WC_Crm_Accounts::output', 'wc_crm_accounts', 'normal', 'high' );
  add_meta_box( 'wc_crm_account_customers', __( 'Customers', 'wc_customer_relationship_manager' ), 'WC_Crm_Accounts::output_customers', 'wc_crm_accounts', 'normal', 'high');
  add_meta_box( 'wc_crm-account-actions', __( 'Account Actions', 'wc_customer_relationship_manager' ), 'WC_Crm_Accounts::output_actions', 'wc_crm_accounts', 'side', 'high' );
  add_meta_box( 'woocommerce-order-notes', __( 'Account Notes', 'wc_customer_relationship_manager' ), 'WC_Crm_Accounts::output_notes', 'wc_crm_accounts', 'side', 'default' );
  }

  public static function exclude_comments( $clauses ) {
    global $wpdb, $typenow;

    if ( is_admin() && in_array( $typenow, wc_get_order_types() ) && current_user_can( 'manage_woocommerce' ) ) {
      return $clauses; // Don't hide when viewing orders in admin
    }

    if ( ! $clauses['join'] ) {
      $clauses['join'] = '';
    }

    if ( ! strstr( $clauses['join'], "JOIN $wpdb->posts" ) ) {
      $clauses['join'] .= " LEFT JOIN $wpdb->posts ON comment_post_ID = $wpdb->posts.ID ";
    }

    if ( $clauses['where'] ) {
      $clauses['where'] .= ' AND ';
    }

    $clauses['where'] .= " $wpdb->posts.post_type NOT IN ('" . implode( "','", wc_crm_get_exclude_comments_post_types() ) . "') ";

    return $clauses;
  }

  /**
   * Exclude order comments from queries and RSS
   * @param  string $join
   * @return string
   */
  public static function exclude_account_from_feed_join( $join ) {
    global $wpdb;

    if ( ! strstr( $join, $wpdb->posts ) ) {
      $join = " LEFT JOIN $wpdb->posts ON $wpdb->comments.comment_post_ID = $wpdb->posts.ID ";
    }

    return $join;
  }

  /**
   * Exclude order comments from queries and RSS
   * @param  string $where
   * @return string
   */
  public static function exclude_account_from_feed_where( $where ) {
    global $wpdb;

    if ( $where ) {
      $where .= ' AND ';
    }

    $where .= " $wpdb->posts.post_type NOT IN ('" . implode( "','", wc_crm_get_exclude_comments_post_types() ) . "') ";

    return $where;
  }

  


}

new WC_CRM_Post_Types();
