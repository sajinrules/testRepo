<?php
/**
 * Table with list of Accounts.
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

class WC_Crm_Accounts_Table extends WP_List_Table {
	protected static $data;
  	protected $found_data;

	
	public $pending_count = array();

	function __construct() {
		parent::__construct( array(
			'singular' => __( 'Account', 'wc_customer_relationship_manager' ), //singular name of the listed records
			'plural' => __( 'Accounts', 'wc_customer_relationship_manager' ), //plural name of the listed records
			'ajax' => false
		) );
	}
	
  function no_items() {
    _e( 'Accounts not found.', 'wc_point_of_sale' );
  }
  function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'name':
				return $item[$column_name];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}
  function get_sortable_columns() {
		$sortable_columns = array(
      'name' => array('name', true)
		);
		return $sortable_columns;
	}

  function get_columns() {
		$columns = array(
      'cb'   => '<input type="checkbox" />',
			'name' => __( 'Name', 'wc_customer_relationship_manager' )
		);
		
		$columns = apply_filters( 'wc_crm_account_custom_column', $columns );
		return $columns;
	}
  function usort_reorder( $a, $b ) {
		// If no sort, default to last purchase
		$orderby = ( !empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'name';
		// If no order, default to desc
		$order = ( !empty( $_GET['order'] ) ) ? $_GET['order'] : 'desc';
		// Determine sort order
		if ( $orderby == 'order_value' ) {
			$result = $a[$orderby] - $b[$orderby];
		} else {
			$result = strcmp( $a[$orderby], $b[$orderby] );
		}
		// Send final sort direction to usort
		return ( $order === 'asc' ) ? $result : -$result;
	}

  function get_bulk_actions() {
		$actions = array(
			'delete' => __( 'Delete', 'wc_customer_relationship_manager' ),
		);
		return $actions;
	}

  function column_cb( $item ) {
      return "<input type='checkbox' name='account_id[]' id='account_id_".$item['ID']."' value='".$item['ID']."' />";
  }
  function column_name( $item ) {
		return "test";

  } 

  function prepare_items() {
    $columns  = $this->get_columns();
    $hidden   = array();    

    $sortable = $this->get_sortable_columns();
    $this->_column_headers = array( $columns, $hidden, $sortable );
    

    $user = get_current_user_id();
    $screen = get_current_screen();
    $option = $screen->get_option('per_page', 'option');

    $per_page = get_user_meta($user, $option, true);
    if ( empty ( $per_page) || $per_page < 1 ) {
        $per_page = $screen->get_option( 'per_page', 'default' );
    }
    $per_page = 10;

    $current_page = $this->get_pagenum();

    self::$data = array();
    $total_items = count(self::$data);
    if(!empty(self::$data)){
      usort( self::$data, array( &$this, 'usort_reorder' ) );
      $this->found_data = array_slice( self::$data,( ( $current_page-1 )* $per_page ), $per_page );
    }else{
      $this->found_data = self::$data;
    }

    $this->set_pagination_args( array(
      'total_items'   => $total_items,                  //WE have to calculate the total number of items
      'per_page' => $per_page                     //WE have to determine how many items to show on a page
    ) );
    $this->items = $this->found_data;
    
  }


	function extra_tablenav( $which ) {
		if ( $which == 'top' ) {
			do_action( 'wc_crm_restrict_list_accounts' );
		}
	}


}