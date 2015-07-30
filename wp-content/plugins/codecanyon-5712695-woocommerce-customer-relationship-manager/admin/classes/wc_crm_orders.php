<?php
/**
 * WooCommerce API Orders Class
 *
 * Handles requests to the /orders endpoint
 *
 * @author      WooThemes
 * @category    API
 * @package     WooCommerce/API
 * @since       2.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WC_Crm_Orders {

  protected static $_instance = null;

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

  /**
   * __construct function.
   *
   * @access public
   * @return void
   */
  public function __construct() {
    
  }

	public function get_sql($account_customer_emails = '') {

		global $wpdb;
    $identifier   = get_option('woocommerce_crm_unique_identifier');
    $display_name = get_option('woocommerce_crm_customer_name');

		$woocommerce_crm_user_roles = get_option('woocommerce_crm_user_roles');
    if(!$woocommerce_crm_user_roles || empty($woocommerce_crm_user_roles)){
      $woocommerce_crm_user_roles[] = 'customer';
    }
    $add_guest_customers = WC_Admin_Settings::get_option( 'woocommerce_crm_guest_customers', 'yes' );
    $user_role_filter = '';
    foreach ($woocommerce_crm_user_roles as $value) {
      if ( !empty($user_role_filter)) $user_role_filter .=  ' OR ';
      $user_role_filter .= "customer.capabilities LIKE '%{$value}%'";
    }

    /******************/
    
    $filter = '';
    $join   = '';
    $inner  = '';
    $select = '';
    /******************/
      $select .= ', total_value.o_total as order_value';

      $orders_status = get_option('woocommerce_crm_total_value');
      if(!$orders_status || empty($orders_status)){
        $orders_status[] = 'wc-completed';
      }
      $orders_statuses = "'" . implode("','", $orders_status) . "'";
      $order_types     = "'" . implode( "','", wc_get_order_types( 'order-count' ) ) . "'";

      
      if( $identifier == 'username_email' ){
        $new_sql = "SELECT IF( pmc.meta_value = 0, CONCAT(pmc.meta_value, '-', pmc_email.meta_value), pmc.meta_value)  AS userUniqueID, pmc.meta_value as user_id, pmc_email.meta_value as user_email, SUM(pmc_total.meta_value) as o_total
            FROM {$wpdb->postmeta} as pmc
            LEFT JOIN {$wpdb->postmeta} pmc_email
              ON ( pmc.meta_key = '_customer_user' AND pmc_email.meta_key = '_billing_email' AND pmc.post_id = pmc_email.post_id)

            LEFT JOIN {$wpdb->postmeta} pmc_total
              ON ( pmc_total.meta_key = '_order_total' AND pmc.post_id = pmc_total.post_id) 

            LEFT JOIN {$wpdb->posts} ps
              ON ( ps.ID = pmc.post_id) 

            WHERE pmc.meta_key = '_customer_user'
            AND IF(pmc.meta_value = 0, pmc_email.meta_value, pmc.meta_value) != ''
            AND ps.post_status IN({$orders_statuses})
            AND ps.post_type   IN ({$order_types})

            GROUP BY userUniqueID
          ";
          $join .= "LEFT JOIN ($new_sql) total_value ON ( total_value.user_id = customer.user_id OR (total_value.user_email = customer.email AND (total_value.user_id = 0 OR total_value.user_id = '' ) ) )";
      }else{

      $new_sql = "SELECT pmc.meta_value as user_id, pmc_email.meta_value as user_email, SUM(pmc_total.meta_value) as o_total
            FROM {$wpdb->postmeta} as pmc_email
            LEFT JOIN {$wpdb->postmeta} pmc
              ON ( pmc.meta_key = '_customer_user' AND pmc_email.meta_key = '_billing_email' AND pmc.post_id = pmc_email.post_id)

            LEFT JOIN {$wpdb->postmeta} pmc_total
              ON ( pmc_total.meta_key = '_order_total' AND pmc.post_id = pmc_total.post_id) 

            LEFT JOIN {$wpdb->posts} ps
              ON ( ps.ID = pmc.post_id) 

            WHERE pmc_email.meta_key = '_billing_email'
            AND pmc_email.meta_value != ''
            AND pmc_email.meta_value IS NOT NULL
            AND ps.post_status IN({$orders_statuses})
            AND ps.post_type   IN ({$order_types})

            GROUP BY user_email
          ";
          $join .= "LEFT JOIN {$wpdb->usermeta} wp_users ON ( wp_users.user_id = customer.user_id AND wp_users.meta_key = 'billing_email')";
          $join .= "LEFT JOIN ($new_sql) total_value ON ( (total_value.user_email = wp_users.meta_value AND wp_users.user_id = customer.user_id ) OR (total_value.user_email = customer.email AND (total_value.user_id = 0 OR total_value.user_id = '' ) ) )";
      }
      #echo '<textarea>'.$new_sql.'</textarea>'; die;
    
    /******************/
    if( !empty($account_customer_emails) ){      
      $emails =implode("','", $account_customer_emails);
      $filter .= " AND customer.email IN ('{$emails}')";
    }


    /*****************/

    if( ( isset($_REQUEST['group']) && !empty( $_REQUEST['group'] ) ) ){
      $group_id = $_REQUEST['group'];
      $group_data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_crm_groups WHERE ID = $group_id");
      if($group_data[0]->group_type == 'static'){
        $inner .= "
        inner join {$wpdb->prefix}wc_crm_groups_relationships groups_rel on (groups_rel.customer_email = customer.email AND groups_rel.group_id = {$group_id} )
        ";
      }else if( ( isset($_REQUEST['group']) && !empty( $_REQUEST['group'] ) ) ){
        $group_id = $_REQUEST['group'];
        $group_data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}wc_crm_groups WHERE ID = $group_id");
        if($group_data[0]->group_type == 'dynamic'){
          if(!empty($group_data[0]->group_total_spent)){
            $spent = $group_data[0]->group_total_spent;
            $mark  = $group_data[0]->group_total_spent_mark;
            switch ($mark) {
              case 'greater':
                $mark = '>';
                break;            
              case 'less':
                $mark = '<';
                break;            
              case 'greater_or_equal':
                $mark = '>=';
                break;
              case 'less_or_equal':
                $mark = '<=';
                break;
              default:
                $mark = '=';
                break;
            }
            #$filter .= " AND {$wpdb->prefix}wc_crm_customers.total_spent $mark $spent 
            #";
            
          }
          if( !empty($group_data[0]->group_user_role) ){
            $group_user_role = $group_data[0]->group_user_role;
              if($group_user_role != 'any'){
                if($group_user_role == 'guest')
                    $filter .= "AND user_id = 0
                    ";
                else
                    $filter .= "AND capabilities LIKE '%".$group_user_role."%'
                    ";
              }
          }
          if( !empty($group_data[0]->group_customer_status) ){
            $group_customer_status = unserialize($group_data[0]->group_customer_status);
            if(!empty($group_customer_status)){
              if(count($group_customer_status) > 1 || !empty($group_customer_status[0]) )
                $filter .= "AND  status IN( '". implode("', '", $group_customer_status) . "' )
                ";
            }
          }

          if( !empty($group_data[0]->group_order_status) ){
            $group_order_status = unserialize($group_data[0]->group_order_status);
            if(!empty($group_order_status)){
              if(count($group_order_status) > 1 || !empty($group_order_status[0]) )
                $_REQUEST['_order_status'] = $group_order_status;
            }
          }
          $d_from = false;
          if( !empty($group_data[0]->group_last_order_from) &&  strtotime( $group_data[0]->group_last_order_from ) !== false ){            
              $d_from = strtotime( $group_data[0]->group_last_order_from );
          }
          $d_to = false;
          if( !empty($group_data[0]->group_last_order_to) &&  strtotime( $group_data[0]->group_last_order_to ) !== false ){            
              $d_to = strtotime( $group_data[0]->group_last_order_to );
          }
          if( $d_to || $d_from ){
              $mark = $group_data[0]->group_last_order;
              switch ($mark) {
                case 'before':
                  $filter .= "AND  DATE(posts.post_date) <= '".date( 'Y-m-d', $d_from ) . "'
                  ";
                  break;
                case 'after':
                  $filter .= "AND  DATE(posts.post_date) >= '".date( 'Y-m-d', $d_from ) . "'
                  ";
                  break;
                case 'between':
                  $filter .= "AND  DATE(posts.post_date) >= '".date( 'Y-m-d', $d_from ) . "' AND  DATE(posts.post_date) <= '".date( 'Y-m-d', $d_to ) . "'
                  ";
                  break;
              }
          }

          /****************/
        }
      }
    }
    /*****************/


    if( (isset($_REQUEST['_customer_product']) && !empty( $_REQUEST['_customer_product'] ) ) 
      || (isset($_REQUEST['_products_variations']) && !empty( $_REQUEST['_products_variations'] ))
      || (isset($_REQUEST['_order_status']) && !empty( $_REQUEST['_order_status'] ))
      || (isset($_REQUEST['_products_categories']) && !empty( $_REQUEST['_products_categories'] ))
      || (isset($_REQUEST['_products_brands']) && !empty( $_REQUEST['_products_brands'] ))
      ){
      $inner .= "
      inner join {$wpdb->postmeta} on ({$wpdb->postmeta}.meta_value = total_value.user_email AND {$wpdb->postmeta}.meta_key = '_billing_email' AND total_value.user_email != '' )
      ";
    }
    if( (isset($_REQUEST['_customer_product']) && !empty( $_REQUEST['_customer_product'] )) 
      || (isset($_REQUEST['_products_variations']) && !empty( $_REQUEST['_products_variations'] ))
      || (isset($_REQUEST['_products_categories']) && !empty( $_REQUEST['_products_categories'] ))
      || (isset($_REQUEST['_products_brands']) && !empty( $_REQUEST['_products_brands'] ))
      ){
      $inner .= "
      inner join {$wpdb->prefix}woocommerce_order_items on {$wpdb->prefix}woocommerce_order_items.order_id = {$wpdb->postmeta}.post_id
      ";
    }
    if( (isset($_REQUEST['_customer_product']) && !empty( $_REQUEST['_customer_product'] )) 
      || (isset($_REQUEST['_products_categories']) && !empty( $_REQUEST['_products_categories'] ))
      || (isset($_REQUEST['_products_brands']) && !empty( $_REQUEST['_products_brands'] ))
      ){
      $inner .= "     
      inner join  {$wpdb->prefix}woocommerce_order_itemmeta as product on ( product.order_item_id = {$wpdb->prefix}woocommerce_order_items.order_item_id and product.meta_key = '_product_id' ) ";
    }

    if((isset($_REQUEST['_products_categories']) && !empty( $_REQUEST['_products_categories'] ))
      || (isset($_REQUEST['_products_brands']) && !empty( $_REQUEST['_products_brands'] ))
      ){
      $tax = '';
      if(isset($_REQUEST['_products_categories'])) $tax .= "taxonomy.taxonomy = 'product_cat'";
      if(isset($_REQUEST['_products_brands'])){
        if(!empty($tax))
          $tax .= ' OR ';
        $tax .= "taxonomy.taxonomy = 'product_brand'";
      }
      $inner .= "
          inner join  {$wpdb->prefix}term_relationships as relationships on (relationships.object_id =  product.meta_value ) 
          inner join  {$wpdb->prefix}term_taxonomy as taxonomy on (relationships.term_taxonomy_id = taxonomy.term_taxonomy_id AND ($tax) ) 
          ";            
    }

    if( isset($_REQUEST['_order_status']) && !empty( $_REQUEST['_order_status'] ) ){
      $request = $_REQUEST['_order_status'];

      if(is_array($request)){
        $inner .= "
              inner JOIN {$wpdb->posts} posts_status
              ON ({$wpdb->postmeta}.post_id= posts_status.ID AND posts_status.post_status IN( '". implode("', '", $request) . "') AND posts_status.post_type =  'shop_order' )
        ";  
      }else if(is_string($request)){
        $inner .= "
              inner JOIN {$wpdb->posts} posts_status
              ON ({$wpdb->postmeta}.post_id= posts_status.ID AND posts_status.post_status = '{$request}'  AND posts_status.post_type =  'shop_order' )
          ";
      }
      
    }
    if( isset($_REQUEST['_products_categories']) && !empty( $_REQUEST['_products_categories'] ) ){
        $y = '';
        foreach ($_REQUEST['_products_categories'] as $v){
          if ($y){
            $ff .= ' OR ';
          }
          else{
            $y = 'OR';
            $ff = '
            AND (';
          }
          $ff .= " (taxonomy.term_id = " . $v . " AND taxonomy.taxonomy = 'product_cat' )";
        }
        $filter .= $ff . ')';
        
    }
    if( isset($_REQUEST['_products_brands']) && !empty( $_REQUEST['_products_brands'] ) ){
      $y = '';
        foreach ($_REQUEST['_products_brands'] as $v){
          if ($y){
            $ff .= ' OR ';
          }
          else{
            $y = 'OR';
            $ff = '
            AND (';
          }
          $ff .= " (taxonomy.term_id = " . $v . " AND taxonomy.taxonomy = 'product_brand' )";
        }
        $filter .= $ff . ')';
    }

    if( isset($_REQUEST['_customer_product']) && !empty( $_REQUEST['_customer_product'] ) ){
      $filter.= " AND product.meta_value = " . $_REQUEST['_customer_product'];
    }

    if( isset($_REQUEST['_products_variations']) && !empty( $_REQUEST['_products_variations'] ) ){
      $y = '';
      $products_variations = $_REQUEST['_products_variations'];
      if( !is_array($products_variations) )
        $products_variations = explode(',', $products_variations);
      foreach ($products_variations as $v){
        if ($y){
          $ff .= ' OR ';
        }
        else{
          $y = ' OR ';
          $ff = ' AND (';
        }
        $ff .= 'variation.meta_value = ' . $v;
      }
      $filter .= $ff . ')';
      $inner .= "
        inner join  {$wpdb->prefix}woocommerce_order_itemmeta as variation on (variation.order_item_id =  {$wpdb->prefix}woocommerce_order_items.order_item_id and variation.meta_key = '_variation_id' ) 
        ";
    }
    /*****************/
    if( isset($_REQUEST['_customer_date_from']) && !empty( $_REQUEST['_customer_date_from'] ) ){
      $filter .= " AND  DATE(posts.post_date) >= '".date( 'Y-m-d', strtotime( $_REQUEST['_customer_date_from'] ) ) . "'
            ";
    }
    if( isset($_REQUEST['_customer_state']) && !empty( $_REQUEST['_customer_state'] ) ){
      $filter .= " AND customer.state = '". $_REQUEST['_customer_state'] . "'
      ";
    }
    if( isset($_REQUEST['_customer_city']) && !empty( $_REQUEST['_customer_city'] ) ){
        $filter .= " AND customer.city = '". $_REQUEST['_customer_city'] . "'
        ";
    }
    if( isset($_REQUEST['_customer_country']) && !empty( $_REQUEST['_customer_country'] ) ){
        $filter .= " AND customer.country = '". $_REQUEST['_customer_country'] . "'
        ";
    }
    if( isset($_REQUEST['_customer_status']) && !empty( $_REQUEST['_customer_status'] ) ){
          $filter .= " AND  customer.status LIKE '". $_REQUEST['_customer_status'] . "'
          ";
      }
    if( isset($_REQUEST['_customer_user']) && !empty( $_REQUEST['_customer_user'] ) ){
      $term  = $_REQUEST['_customer_user'];
      $filter .= " AND (customer.email = '$term' OR customer.user_id = $term)
        ";
    }
      $join .= " LEFT JOIN {$wpdb->usermeta} fname ON (customer.user_id = fname.user_id AND fname.meta_key = 'first_name')";
      $join .= " LEFT JOIN {$wpdb->usermeta} lname ON (customer.user_id = lname.user_id AND lname.meta_key = 'last_name')";
      $join .= " LEFT JOIN {$wpdb->postmeta} pfname ON (customer.order_id = pfname.post_id AND pfname.meta_key = '_billing_first_name')";
      $join .= " LEFT JOIN {$wpdb->postmeta} plname ON (customer.order_id = plname.post_id AND plname.meta_key = '_billing_last_name')";

      if( $display_name == 'fl' ){
        $select .= ', IF(customer.user_id > 0 AND customer.user_id IS NOT NULL, CONCAT(fname.meta_value, " ", lname.meta_value), CONCAT(pfname.meta_value, " ", plname.meta_value) )  as customer_name';
      }else{
        $select .= ', IF(customer.user_id > 0 AND customer.user_id IS NOT NULL, CONCAT(lname.meta_value, ", ", fname.meta_value), CONCAT(plname.meta_value, ", ", pfname.meta_value) ) as customer_name';
      }
    if( isset($_REQUEST['s']) && !empty( $_REQUEST['s'] ) ){
      $term = $_REQUEST['s'];

      $filter .= " AND (
        (LOWER(fname.meta_value) LIKE LOWER('%$term%') OR LOWER(lname.meta_value) LIKE LOWER('%$term%') OR LOWER(customer.email) LIKE LOWER('%$term%') OR concat_ws(' ',fname.meta_value,lname.meta_value) LIKE '%$term%' )
        OR
        (LOWER(pfname.meta_value) LIKE LOWER('%$term%') OR LOWER(plname.meta_value) LIKE LOWER('%$term%') OR concat_ws(' ',pfname.meta_value,plname.meta_value) LIKE '%$term%' )
        )
        ";
    }
    /******************/ 
    if(!empty($user_role_filter)){

      if($add_guest_customers == 'yes'){
        $user_role_filter = ' AND ('.$user_role_filter.' OR customer.user_id IS NULL OR customer.user_id = 0 ) ';
      }else{
        $user_role_filter = ' AND ('.$user_role_filter.') ';
      }

    }
    if(isset($_REQUEST['_user_type']) && !empty($_REQUEST['_user_type']) ){
      if($_REQUEST['_user_type'] == 'guest_user'){
        $user_role_filter = " AND ( customer.user_id IS NULL  OR customer.user_id = 0 )
        ";
      }
      else{
        $user_role_filter = " AND (customer.capabilities LIKE '%".$_REQUEST['_user_type']."%') ";
      }
    }
    $filter .= $user_role_filter;

    

		$sql = "SELECT customer.*, posts.post_date as last_purchase {$select} FROM {$wpdb->prefix}wc_crm_customer_list as customer
      LEFT JOIN {$wpdb->posts} posts ON (customer.order_id = posts.ID)
			{$join}
      {$inner}

			WHERE 1=1
			{$filter}

      GROUP BY customer.email

		 ";
     #echo '<textarea name="" id="" style="width: 100%; height: 200px; ">'.$sql.'</textarea>';die;
		return $sql;
	}
	public function get_orders( $account_customer_emails = '' ) {
		global $wpdb;

    $sql = $this->get_sql($account_customer_emails);
    

    $wpdb->query('SET OPTION SQL_BIG_SELECTS = 1');
		$result = $wpdb->get_results($sql, ARRAY_A );
    $this->orders_ount = count($result);
		return $result;
	}

	public function get_orders_ount() {

		global $wpdb;
    $sql = $this->get_sql();

    $wpdb->query('SET OPTION SQL_BIG_SELECTS = 1');
		$result = count($wpdb->get_results($sql) );

		return $result;
	}



}
