<?php
/**
 * Table with list of customers.
 *
 * @author   Actuality Extensions
 * @package  WooCommerce_Customer_Relationship_Manager
 * @since    1.0
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

//require_once( 'wc_crm_customer_details.php' );

//require_once( plugin_dir_path( __FILE__ ) . '../../functions.php' );

class WC_Crm_Customers_Table extends WP_List_Table {
  protected $data;
	protected $found_data;

	
	public $pending_count = array();

	function __construct() {
		parent::__construct( array(
			'singular' => __( 'customer', 'wc_customer_relationship_manager' ), //singular name of the listed records
			'plural' => __( 'customers', 'wc_customer_relationship_manager' ), //plural name of the listed records
			'ajax' => false //does this table support ajax?
			//'screen'   => isset( $args['screen'] ) ? $args['screen'] : null,
		) );
		add_action( 'admin_head', array(&$this, 'admin_header') );

		$this->mailchimp = array();
		if ( woocommerce_crm_mailchimp_enabled() ) {
			$this->mailchimp = woocommerce_crm_get_members();
		}
	}
	function admin_header() {
		$page = ( isset( $_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if ( WC_CRM()->id != $page )
			return;
		echo '<style type="text/css">';
		if ( woocommerce_crm_mailchimp_enabled() ) {
			echo '.wp-list-table .column-id {}';
			echo '.wp-list-table .column-customer_status { width: 45px;}';
			echo '.wp-list-table .column-customer_name {width: 20%;}';
			echo '.wp-list-table .column-email { width: 20%;}';
			echo '.wp-list-table .column-last_purchase { width: 15%;}';
			echo '.wp-list-table .column-order_value { width: 10%;}';
			echo '.wp-list-table .column-enrolled { width: 50px;}';
			echo '.wp-list-table .column-customer_notes { width: 50px;}';
			echo '.wp-list-table .column-crm_actions { width: 120px;}';
		} else {
			echo '.wp-list-table .column-id {}';
			echo '.wp-list-table .column-customer_status { width: 45px;}';
			echo '.wp-list-table .column-customer_name { width: 20%;}';
			echo '.wp-list-table .column-email { width: 20%;}';
			echo '.wp-list-table .column-last_purchase { width: 15%;}';
			echo '.wp-list-table .column-order_value { width: 10%;}';
			echo '.wp-list-table .column-customer_notes { width: 50px;}';
			echo '.wp-list-table .column-crm_actions { width: 120px;}';
		}
		echo '</style>';
	}

  public function display_tablenav( $which )
  {
    $screen = get_current_screen();
    if( $screen->id != 'wc_crm_accounts'){
      parent::display_tablenav($which );
    }
  }
	
  function no_items() {
    $screen = get_current_screen();
    if( $screen->id == 'wc_crm_accounts'){
      _e( 'Customers not found.', 'wc_point_of_sale' );
    }else{
      _e( 'Customers not found. Try to adjust the filter.', 'wc_point_of_sale' );
    }
  }
  function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'customer_status':
			case 'customer_name':
			case 'email':
			case 'last_purchase':
			case 'order_value':
			case 'enrolled':
			case 'customer_notes':
			case 'crm_actions':
				return $item[$column_name];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
  function get_sortable_columns() {
    $screen = get_current_screen();
    if( $screen->id == 'wc_crm_accounts'){
      $sortable_columns = array();
    }else{
  		$sortable_columns = array(
        'customer_name' => array('customer_name', true),
  		  'order_value'   => array('order_value', true),
        'last_purchase' => array('last_purchase', true),
  		);     
    }
		return $sortable_columns;
	}

  function get_columns() {
    $screen = get_current_screen();
    if( $screen->id == 'wc_crm_accounts'){
      $columns = array(
        'customer_status' => '<span class="status_head tips" data-tip="' . esc_attr__( 'Customer Status', 'wc_customer_relationship_manager' ) . '">' . esc_attr__( 'Customer Status', 'wc_customer_relationship_manager' ) . '</span>',
        'customer_name' => __( 'Customer', 'wc_customer_relationship_manager' ),
        'email' => __( 'Contact Details', 'wc_customer_relationship_manager' ),
        'customer_notes' => '<span class="ico_notes tips" data-tip="' . esc_attr__( 'Customer Notes', 'wc_customer_relationship_manager' ) . '">' . esc_attr__( 'Customer Notes', 'wc_customer_relationship_manager' ) . '</span>',
        'last_purchase' => __( 'Last Order', 'wc_customer_relationship_manager' ),
        'order_value' => __( 'Value', 'wc_customer_relationship_manager' ),
      );
      if ( woocommerce_crm_mailchimp_enabled() ) {
        $columns['enrolled'] = '<span class="ico_news tips" data-tip="' . esc_attr__( 'Newsletter Subscription', 'wc_customer_relationship_manager' ) . '">'.esc_attr__( 'Newsletter Subscription', 'wc_customer_relationship_manager' ).'</span>';
      };
    }
    else{
  		$columns = array(
  			'cb' => '<input type="checkbox" />',
  			'customer_status' => '<span class="status_head tips" data-tip="' . esc_attr__( 'Customer Status', 'wc_customer_relationship_manager' ) . '">' . esc_attr__( 'Customer Status', 'wc_customer_relationship_manager' ) . '</span>',
  			'customer_name' => __( 'Customer', 'wc_customer_relationship_manager' ),
  			'email' => __( 'Contact Details', 'wc_customer_relationship_manager' ),
  			'customer_notes' => '<span class="ico_notes tips" data-tip="' . esc_attr__( 'Customer Notes', 'wc_customer_relationship_manager' ) . '">' . esc_attr__( 'Customer Notes', 'wc_customer_relationship_manager' ) . '</span>',
  			'last_purchase' => __( 'Last Order', 'wc_customer_relationship_manager' ),
  			'order_value' => __( 'Value', 'wc_customer_relationship_manager' ),
  		);
  		if ( woocommerce_crm_mailchimp_enabled() ) {
  			$columns['enrolled'] = '<span class="ico_news tips" data-tip="' . esc_attr__( 'Newsletter Subscription', 'wc_customer_relationship_manager' ) . '">'.esc_attr__( 'Newsletter Subscription', 'wc_customer_relationship_manager' ).'</span>';
  		};
  		$columns['crm_actions'] = __( 'Actions', 'wc_customer_relationship_manager' );
    }
		$columns = apply_filters( 'wc_pos_customer_custom_column', $columns );
		return $columns;
	}
  function usort_reorder( $a, $b ) {
		// If no sort, default to last purchase
		$orderby = ( !empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'order_value';
		// If no order, default to desc
		$order = ( !empty( $_GET['order'] ) ) ? $_GET['order'] : 'desc';
		// Determine sort order
		if ( $orderby == 'order_value' ) {
			$result = $a[$orderby] - $b[$orderby];
		} else {
			$result = strcasecmp( $a[$orderby], $b[$orderby] );
		}
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}

  function get_bulk_actions() {
    $screen = get_current_screen();
    if( $screen->id != 'wc_crm_accounts'){
  		$actions = array(
  			'email' => __( 'Send Email', 'wc_customer_relationship_manager' ),
  			'export_csv' => __( 'Export Contacts', 'wc_customer_relationship_manager' ),
  		);
  		$statuses = wc_crm_get_statuses();
  		foreach ($statuses as $status) {
  			$actions[$status->status_slug] = sprintf( __( 'Mark as %s', 'wc_customer_relationship_manager' ), $status->status_name );
  		}
  		$groups = wc_get_static_groups();
  		
  		foreach ($groups as $group) {
  			$actions['crm_add_to_group_'.$group->ID] = sprintf( __( 'Add to %s', 'wc_customer_relationship_manager' ), $group->group_name );
  		}
		  return $actions;
    }
    return array();
	}

  function column_cb( $item ) {
    if($item['user_id'] && !empty($item['user_id']) && $data = get_userdata($item['user_id']) ){
      return '<label class="screen-reader-text" for="cb-select-' . $item['user_id'] . '">' . sprintf( __( 'Select %s' ), $data->user_nicename ) . '</label>'
            . "<input type='checkbox' name='user_id[]' id='user_" . $item['user_id'] . "' value='" . $item['user_id'] . "' />";
    }else if($item['order_id'] && !empty($item['order_id'])){
      return "<input type='checkbox' name='order_id[]' id='user_order_id".$item['order_id']."' value='".$item['order_id']."' />"; 
    }
  }
  function column_customer_status( $item ) {
    if($item['user_id'] && !empty($item['user_id']) && $data = get_userdata($item['user_id'])){

	  	if($item['status'] && !empty($item['status']) ){
	  			$default_statuses = WC_CRM()->statuses;
	  			$_status = $item['status'];

	    		if(array_key_exists($_status, $default_statuses) ){
						$customer_status = '<div style="position: relative;"><span class="'.$_status.' tips" data-tip="' . esc_attr( $_status ) . '"></span></div>';						
					}else{
						$custom_status = wc_crm_get_status_by_slug($_status);
						if($custom_status){
							$s = wc_crm_get_status_icon_code($custom_status['status_icon']);    
	    				$customer_status =  sprintf('<i data-icomoon="%s" data-fip-value="%s" style="color: %s;" class="tips" data-tip="' . esc_attr( $custom_status['status_name'] ) . '"></i>', $s, $custom_status['status_icon'],  $custom_status['status_colour']);							
						}else{
							$customer_status = '<div style="position: relative;">'.$_status.'</div>';							
						}
					}
					return $customer_status;
	  	}
	    else{
	    	return '<div style="position: relative;"><span class="Customer tips" data-tip="Customer"></span></div>';
	    }
  	}else{
  		return '';
  	}
  }
  function column_customer_name( $item ) {

    $edit = '';
    $name = str_replace(',', '', $item['customer_name']);
    $name = trim($name);


    if($item['user_id'] && !empty($item['user_id'])  && $data = get_userdata($item['user_id']) ){
      $avatar = get_avatar( $item['user_id'], 32 );

      if(!empty($name)){
        $edit .= "<strong><a href='admin.php?page=wc_new_customer&user_id=" . $item['user_id'] . "'>".$item['customer_name']."</a></strong><br>";        
      }
      if($data){
        $edit .= '<small class="meta">' . $data->user_login . "</small>";
      }
      return "<a href='admin.php?page=wc_new_customer&user_id=" . $item['user_id'] . "'>$avatar</a> $edit";
    }else if($item['order_id'] && !empty($item['order_id'])){
      if(!empty($name)){
        $edit .= "<strong><a href='admin.php?page=wc_new_customer&order_id=" . $item['order_id'] . "'>".$item['customer_name']."</a></strong>";
      }
      $avatar = get_avatar( 0 , 32 );
      return "<a href='admin.php?page=wc_new_customer&order_id=" . $item['order_id'] . "'>$avatar</a> $edit";
  	}else{
  		return '';
  	}
  }
  function column_email( $item ) {
    $email = '';
    $phone = '';
    if($item['user_id'] && !empty($item['user_id']) && get_userdata($item['user_id'])){
    	$identifier = get_option('woocommerce_crm_unique_identifier');
	  	$meta = get_user_meta($item['user_id']);

	  	if( $identifier == 'username_email' ){
	  		$data  = get_userdata($item['user_id']);
	  		$email = $data->user_email;
	  	}else if( $meta && isset($meta['billing_email']) ){
	    	$email = $meta['billing_email'][0];
	  	}
	    else{
	    	$email = '';
	    }

      if($meta && isset($meta['billing_phone']))
        $phone = $meta['billing_phone'][0];

  	}else if($item['order_id'] && !empty($item['order_id'])){
  		$email = get_post_meta($item['order_id'], '_billing_email', true);
      $phone = get_post_meta($item['order_id'], '_billing_phone', true);
  	}
		return "<a href='mailto:$email' title='" . esc_attr( sprintf( __( 'Email: %s' ), $email ) ) . "'>{$email}</a><br><span class='crm_phone'>{$phone}</span>";

  }

  function column_last_purchase( $item ) {
    if($item['order_id'] && !empty($item['order_id']) ){
      $order = wc_get_order($item['order_id']);
      if($order){
			 return '<a href="'. get_edit_post_link( $item['order_id']) .'">#'.$order->get_order_number().'</a> - ' . woocommerce_crm_get_pretty_time( $item['order_id'] );
      }else{
        return '';  
      }
		}else{
			return '';
		}
  }

  function column_order_value( $item ) {
    $num_orders = 0;
    $total_spent = 0;
    $identifier = get_option('woocommerce_crm_unique_identifier');
    if(isset($item['order_value'])){
      $total_spent = $item['order_value'];
    }else{
    	if($item['user_id'] && !empty($item['user_id']) && get_userdata($item['user_id'])){
        $meta = get_user_meta($item['user_id']);
        if( $identifier == 'username_email' ){
          $total_spent =  wc_crm_get_order_value($item['user_id']);
        }else if( $meta && isset($meta['billing_email']) ){
          $email = $meta['billing_email'][0];
          $total_spent = wc_crm_get_order_value($email, '_billing_email', true);
        }
  	  	
    	}else if($item['order_id'] && !empty($item['order_id'])){
    		$email = get_post_meta($item['order_id'], '_billing_email', true);
    		$total_spent = wc_crm_get_order_value($email, '_billing_email', true);
    	}      
    }
    if(isset($item['num_orders'])){
      $num_orders = (int)$item['num_orders'];
    }else{
      if($item['user_id'] && !empty($item['user_id']) && get_userdata($item['user_id'])){
        $meta = get_user_meta($item['user_id']);
        if( $identifier == 'username_email' ){
          $num_orders =  wc_crm_get_num_orders($item['user_id']);
        }else if( $meta && isset($meta['billing_email']) ){
          $email = $meta['billing_email'][0];
          $num_orders =  wc_crm_get_num_orders($email, '_billing_email', true);
        }
        
      }else if($item['order_id'] && !empty($item['order_id'])){
        $email = get_post_meta($item['order_id'], '_billing_email', true);
        $num_orders =  wc_crm_get_num_orders($email, '_billing_email', true);
      }
    }
    $num_orders = $num_orders > 0 ? '<br><small class="meta">' . sprintf( _n( '%d order', '%d orders', $num_orders, 'wc_customer_relationship_manager' ), $num_orders ) . '</small>' : '';
    return wc_price( $total_spent ) . $num_orders;
  }
  function column_customer_notes( $item ) {
  	if($item['user_id'] && !empty($item['user_id']) && get_userdata($item['user_id'])){
	    $wc_crm_customer_details = new WC_Crm_Customer_Details($item['user_id']);
			$notes = $wc_crm_customer_details->get_last_customer_note();
			if($notes == 'No Customer Notes')
				$customer_notes = '<span class="note-off">-</span>';
			else
			  $customer_notes = '<a href="admin.php?page=wc_new_customer&screen=customer_notes&user_id='.$item['user_id'].'" class="open_c_notes note-on tips" data-tip="'.$notes.'"></a>';

			return $customer_notes;

		}else if($item['order_id'] && !empty($item['order_id'])){
  		return '<span class="note-off">-</span>';
  	}
  }
  function column_enrolled( $item ) {
		$email = '';
  	if($item['user_id'] && !empty($item['user_id']) && get_userdata($item['user_id'])){
  		$data = get_user_meta($item['user_id']);
	  		
  		if(!$data || !isset($data['billing_email']) ){
	  		$data  = get_userdata($item['user_id']);
	  		$email = $data->user_email;
	  	}else{
	    	$email = $data['billing_email'];
	  	}			
  	}else if($item['order_id'] && !empty($item['order_id'])){
  		$email = get_post_meta($item['order_id'], '_billing_email', true);
  	}

  	if ( woocommerce_crm_mailchimp_enabled() ) {
			return (is_array($this->mailchimp) && in_array( $email, $this->mailchimp ) ) ? "<span class='enrolled-yes'></span>" : "<span class='enrolled-no'></span>";
		}

  }
  function column_crm_actions( $item ) {
  	$actions = array();
    if($item['user_id'] && !empty($item['user_id']) && get_userdata($item['user_id'])){

    	$email = get_user_meta($item['user_id'], 'billing_email', true);

    	$phone = get_user_meta($item['user_id'], 'billing_phone', true);

    	if ( $item['order_id'] && !empty($item['order_id']) ){
					$actions['orders'] = array(
						'classes' => 'view',
						'url' => sprintf( 'edit.php?s=%s&post_status=%s&post_type=%s&shop_order_status&_customer_user&paged=1&mode=list&search_by_email_only', urlencode( $email ), 'all', 'shop_order' ),
						'action' => 'view',
						'name' => __( 'View Orders', 'wc_customer_relationship_manager' ),
						'target' => ''
					);					
				}
				$actions['email'] = array(
					'classes' => 'email',
					'url' => sprintf( '?page=%s&action=%s&user_id=%s', $_REQUEST['page'], 'email', $item['user_id'] ),
					'name' => __( 'Send Email', 'wc_customer_relationship_manager' ),
					'target' => ''
				);
				if ($phone){
					$actions['phone'] = array(
						'classes' => 'phone',
						'url' => sprintf( '?page=%s&action=%s&user_id=%s', $_REQUEST['page'], 'phone_call', $item['user_id'] ),
						'name' => __( 'Call Customer', 'wc_customer_relationship_manager' ),
						'target' => ''
					);
				}
    }else if($item['order_id'] && !empty($item['order_id']) && $item['order_id'] !=  null){
    	$email = get_post_meta($item['order_id'], '_billing_email', true);
    	$phone = get_post_meta($item['order_id'], '_billing_phone', true);
  		$actions['orders'] = array(
					'classes' => 'view',
					'url' => sprintf( 'edit.php?s=%s&post_status=%s&post_type=%s&shop_order_status&_customer_user&paged=1&mode=list&search_by_email_only', urlencode( $email ), 'all', 'shop_order' ),
					'action' => 'view',
					'name' => __( 'View Orders', 'wc_customer_relationship_manager' ),
					'target' => ''
				);
				$actions['email'] = array(
					'classes' => 'email',
					'url' => sprintf( '?page=%s&action=%s&order_id=%s', $_REQUEST['page'], 'email', $item['order_id'] ),
					'name' => __( 'Send Email', 'wc_customer_relationship_manager' ),
					'target' => ''
				);
				if ($phone){
					$actions['phone'] = array(
						'classes' => 'phone',
						'url' => sprintf( '?page=%s&action=%s&order_id=%s', $_REQUEST['page'], 'phone_call', $item['order_id'] ),
						'name' => __( 'Call Customer', 'wc_customer_relationship_manager' ),
						'target' => ''
					);
				}
  	}

		$crm_actions = '';
  	if(!empty($actions)){
			foreach ( $actions as $action ) {
				$crm_actions .= '<a class="button tips '.esc_attr($action['classes']).'" href="'.esc_url( $action['url'] ).'" data-tip="'.esc_attr( $action['name'] ).'" '.esc_attr( $action['target'] ).' >'.esc_attr( $action['name'] ).'</a>';
			}
		}
		return $crm_actions;
  }
  

  function prepare_items() {
    $columns  = $this->get_columns();
    $hidden   = array();   

    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array( $columns, $hidden, $sortable );   

    $user = get_current_user_id();
    $screen = get_current_screen();
    $o = WC_CRM()->orders();
    if( $screen->id == 'wc_crm_accounts'){
      global $post;
      $account_id = $post->ID;
      $emails = get_post_meta($account_id, '_wc_crm_customer_email');
      if(!empty($emails)){
        $this->data = $o->get_orders($emails);
        usort( $this->data, array( &$this, 'usort_reorder' ) );
        $this->items = $this->data;
      }else{
        $this->items = array();
      }

    }else{
      $option = $screen->get_option('per_page', 'option');
      $per_page = get_user_meta($user, $option, true);
      if ( empty ( $per_page) || $per_page < 1 ) {
          $per_page = $screen->get_option( 'per_page', 'default' );
      }

      $current_page = $this->get_pagenum();
      
      $this->data = $o->get_orders();
      usort( $this->data, array( &$this, 'usort_reorder' ) );

      $total_items = $o->orders_ount;
        $this->found_data = array_slice( $this->data,( ( $current_page-1 )* $per_page ), $per_page );

        $this->set_pagination_args( array(
          'total_items'   => $total_items,                  //WE have to calculate the total number of items
          'per_page' => $per_page                     //WE have to determine how many items to show on a page
        ) );
        $this->items = $this->found_data;
    }
    
  }


	function extra_tablenav( $which ) {
    $screen = get_current_screen();
		if ( $which == 'top' && $screen->id != 'wc_crm_accounts') {
			do_action( 'wc_crm_restrict_list_customers' );
		}
	}

  function get_views(){
    global $wpdb;
    $statuses = $wpdb->get_results("SELECT status, count(status) as count FROM {$wpdb->prefix}wc_crm_customer_list GROUP BY status");
    $all = 0;
    if($statuses){
      foreach ($statuses as $st) {
        $all += (int)$st->count;
      }
    }
     $views = array();
     $current = ( !empty($_REQUEST['_customer_status']) ? $_REQUEST['_customer_status'] : 'all');

     //All link
     $class = ($current == 'all' ? ' class="current"' :'');
     $all_url = remove_query_arg('_customer_status');
     $views['all'] = "<a href='{$all_url }' {$class} >All <span class='count'>({$all})</span></a>";

     if($statuses){
      foreach ($statuses as $st) {
        $url = add_query_arg('_customer_status',$st->status);
        $class = ($current == $st->status ? ' class="current"' :'');
        $views[$st->status] = "<a href='{$url}' {$class} >{$st->status} <span class='count'>({$st->count})</span></a>";
      }
    }
     return $views;
  }

  public function views() {
      $views = $this->get_views();
      /**
       * Filter the list of available list table views.
       *
       * The dynamic portion of the hook name, `$this->screen->id`, refers
       * to the ID of the current screen, usually a string.
       *
       * @since 3.5.0
       *
       * @param array $views An array of available list table views.
       */
      $views = apply_filters( "views_{$this->screen->id}", $views );
   
      if ( empty( $views ) )
          return;
   
      echo "<ul class='subsubsub'>\n";
      foreach ( $views as $class => $view ) {
          $views[ $class ] = "\t<li class='st-$class'>$view";
      }
      echo implode( " |</li>\n", $views ) . "</li>\n";
      echo "</ul>";
}


}