<?php



if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Simple_Social_Account_Data_List_Table extends WP_List_Table {

	public $action_key = 'ID';
	public $table_data = array();

	function __construct($args = array()) {
		global $status, $page;

		$args = wp_parse_args( $args, array(
			'plural' => __( 'accounts', 'simple_social_inbox' ),
			'singular' => __( 'account', 'simple_social_inbox' ),
			'ajax' => false,
		) );

		parent::__construct( $args );

		$this->set_columns( array(
			'account' => __( 'Account', 'simple_social_inbox' ),
			'last_checked'    => __( 'Last Checked', 'simple_social_inbox' ),
		) );


		add_action( 'admin_head', array( $this, 'admin_header' ) );

	}

	function admin_header() {
		$page = ( isset( $_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
		if ( 'my_list_test' != $page ) {
			return;
		}
		echo '<style type="text/css">';
		echo '.wp-list-table .column-id { width: 5%; }';
		echo '.wp-list-table .column-booktitle { width: 40%; }';
		echo '.wp-list-table .column-author { width: 35%; }';
		echo '.wp-list-table .column-isbn { width: 20%;}';
		echo '</style>';
	}

	function no_items() {
		_e( 'Nothing found.' );
	}

	function column_default( $item, $column_name ) {
		if($this->row_callback !== false){
			$res = call_user_func($this->row_callback, $item, $column_name);
			if($res){
				return $res;
			}
		}
		return isset($item[ $column_name ]) ? $item[ $column_name ] : 'N/A';
	}


	function set_data($data){
		$this->items = $data;
	}
	private $row_callback = false;
	function set_callback($function){
		$this->row_callback = $function;
	}
	function set_columns($columns){
		$this->columns = $columns;
	}
	function get_columns() {
		return $this->columns;
	}

	function column_account( $item ) {
		if(isset($item['edit_link'])){
			$actions = array(
				'edit'   => '<a href="'.$item['edit_link'].'">'.__('Edit','simple_social_inbox').'</a>',
				//'delete' => sprintf( '<a href="?page=%s&action=%s&book=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['ID'] ),
			);
			return sprintf( '%1$s %2$s', $item['title'], $this->row_actions( $actions ) );
		}/*else {
			$actions = array(
				'edit' => sprintf( '<a href="?page=%s&' . $this->action_key . '=%s">'.__('Edit','simple_social_inbox').'</a>', htmlspecialchars( $_REQUEST['page'] ), $item[ $this->action_key ] ),
				//'delete' => sprintf( '<a href="?page=%s&action=%s&book=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['ID'] ),
			);
		}*/


	}

	function set_bulk_actions($actions) {
		$this->bulk_actions = $actions;
	}
	function get_bulk_actions() {
		return isset($this->bulk_actions) ? $this->bulk_actions : array();
	}


	function prepare_items() {
		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = array(); //
		$this->_column_headers = array( $columns, $hidden, $sortable );
		//usort( $this->example_data, array( $this, 'usort_reorder' ) );

		$per_page     = 20;
		$current_page = $this->get_pagenum();


		$total_items  = count( $this->items );

		// only ncessary because we have sample data
		$this->found_data = array_slice( $this->items, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->set_pagination_args( array(
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		) );
		$this->items = $this->found_data;

	}

} //class






class SimpleSocialMessageList extends Simple_Social_Account_Data_List_Table{
    private $row_output = array();

	public $available_networks = array(
		'facebook',
		'google',
		'twitter',
		'linkedin',
	);

	function __construct($args = array()) {
		$args = wp_parse_args( $args, array(
			'plural'   => __( 'messages', 'simple_social_inbox' ),
			'singular' => __( 'message', 'simple_social_inbox' ),
			'ajax'     => false,
		) );
		parent::__construct( $args );
	}

	function column_cb( $item ) {
		foreach($this->available_networks as $network){
			if(isset($item['social_'.$network.'_message_id'])){
			    return sprintf(
				    '<input type="checkbox" name="social_message['.$network.'][]" value="%s" />', $item['social_'.$network.'_message_id']
			    );
			}
		}
	    return '';
	}
	public function get_bulk_actions(){
		return array(
	        'archive'    => __('Archive'),
	        'un-archive'  => __('Move to Inbox')
	    );
	}
	public function process_bulk_action() {
		$action = $this->current_action();
		$change_count = 0;
		if($action){
	        // security check!
	        if ( isset( $_POST['_wpnonce'] ) && ! empty( $_POST['_wpnonce'] ) ) {
	            $nonce  = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
	            if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) )
	                wp_die( 'Nope! Security check failed!' );
	        }
	        switch ( $action ) {
	            case 'archive':
					$messages = isset($_POST['social_message']) && is_array($_POST['social_message']) ? $_POST['social_message'] : array();
					// any facebook messages to process?
					if(isset($messages) && isset($messages['facebook']) && is_array($messages['facebook'])){
						foreach($messages['facebook'] as $facebook_message_id){
							// archive this one.
							$ucm_facebook_message = new ucm_facebook_message(false, false, $facebook_message_id);
							if($ucm_facebook_message->get('social_facebook_message_id') == $facebook_message_id){
								$ucm_facebook_message->update('status',_SOCIAL_MESSAGE_STATUS_ANSWERED);
								$change_count++;
							}
						}
					}
					if(isset($messages) && isset($messages['twitter']) && is_array($messages['twitter'])){
						foreach($messages['twitter'] as $twitter_message_id){
							// archive this one.
							$ucm_twitter_message = new ucm_twitter_message(false, $twitter_message_id);
							if($ucm_twitter_message->get('social_twitter_message_id') == $twitter_message_id){
								$ucm_twitter_message->update('status',_SOCIAL_MESSAGE_STATUS_ANSWERED);
								$change_count++;
							}
						}
					}
					if(isset($messages) && isset($messages['google']) && is_array($messages['google'])){
						foreach($messages['google'] as $google_message_id){
							// archive this one.
							$ucm_google_message = new ucm_google_message(false, $google_message_id);
							if($ucm_google_message->get('social_google_message_id') == $google_message_id){
								$ucm_google_message->update('status',_SOCIAL_MESSAGE_STATUS_ANSWERED);
								$change_count++;
							}
						}
					}
	                break;
	            case 'un-archive':
					$messages = isset($_POST['social_message']) && is_array($_POST['social_message']) ? $_POST['social_message'] : array();
					// any facebook messages to process?
					if(isset($messages) && isset($messages['facebook']) && is_array($messages['facebook'])){
						foreach($messages['facebook'] as $facebook_message_id){
							// archive this one.
							$ucm_facebook_message = new ucm_facebook_message(false, false, $facebook_message_id);
							if($ucm_facebook_message->get('social_facebook_message_id') == $facebook_message_id){
								$ucm_facebook_message->update('status',_SOCIAL_MESSAGE_STATUS_UNANSWERED);
								$change_count++;
							}
						}
					}
					if(isset($messages) && isset($messages['twitter']) && is_array($messages['twitter'])){
						foreach($messages['twitter'] as $twitter_message_id){
							// archive this one.
							$ucm_twitter_message = new ucm_twitter_message(false, $twitter_message_id);
							if($ucm_twitter_message->get('social_twitter_message_id') == $twitter_message_id){
								$ucm_twitter_message->update('status',_SOCIAL_MESSAGE_STATUS_UNANSWERED);
								$change_count++;
							}
						}
					}
					if(isset($messages) && isset($messages['google']) && is_array($messages['google'])){
						foreach($messages['google'] as $google_message_id){
							// archive this one.
							$ucm_google_message = new ucm_google_message(false, $google_message_id);
							if($ucm_google_message->get('social_google_message_id') == $google_message_id){
								$ucm_google_message->update('status',_SOCIAL_MESSAGE_STATUS_UNANSWERED);
								$change_count++;
							}
						}
					}
	                break;
	            default:
	                return $change_count;
	                break;
	        }
		}
        return $change_count;
    }

	public $row_count = 0;
    function column_default($item, $column_name){

	    foreach($this->available_networks as $network){
			if(isset($item['social_'.$network.'_message_id'])){
				// pass this row rendering off to the facebook plugin
			    // todo - don't hack the <td> outputfrom the existing plugin, move that back into this table class
			    if(!isset($this->row_output[$network][$item['social_'.$network.'_message_id']])){
				    $this->row_output[$network] = array();
				    ob_start();
				    $item['message_manager']->output_row($item, array(
					    'row_class' => $this->row_count++%2 ? 'alternate' : '',
				    ));
				    $this->row_output[$network][$item['social_'.$network.'_message_id']] = ob_get_clean();
			    }
			    if(isset($this->row_output[$network][$item['social_'.$network.'_message_id']])){
				    // grep the <td class="column_name"></td>
				    if(preg_match('#class="'.$column_name.'">(.*)</td>#imsU',$this->row_output[$network][$item['social_'.$network.'_message_id']],$matches)){
					    return $matches[1];
				    }
			    }
			}
		}
	    return false;
    }
}


class SimpleSocialSentList extends Simple_Social_Account_Data_List_Table{
    private $row_output = array();

	function __construct($args = array()) {
		$args = wp_parse_args( $args, array(
			'plural'   => __( 'sent_messages', 'simple_social_inbox' ),
			'singular' => __( 'sent_message', 'simple_social_inbox' ),
			'ajax'     => false,
		) );
		parent::__construct( $args );
	}



	private $message_managers = array();
	function set_message_managers($message_managers){
		$this->message_managers = $message_managers;
	}

	private $column_details = array();
    function column_default($item, $column_name){

	    if(!$item['social_message_id'])return 'DBERR';
	    if(!isset($this->column_details[$item['social_message_id']])){
		    $this->column_details[$item['social_message_id']] = array();
	    }
	    // pass this off to our media managers and work out which social accounts sent this message.
		foreach($this->message_managers as $type => $message_manager){
			if(!isset($this->column_details[$item['social_message_id']][$type])) {
				$this->column_details[ $item['social_message_id'] ][ $type ] = $message_manager->get_message_details( $item['social_message_id'] );
			}
		}

	    switch($column_name){
		    case 'social_column_time':
				$column_data = '';
				foreach($this->column_details[ $item['social_message_id'] ] as $message_type => $data){
					if(isset($data['message']) && $data['message']->get('status') == _SOCIAL_MESSAGE_STATUS_PENDINGSEND){
						$time = $data['message']->get('last_active');
						if(!$time)$time = $data['message']->get('message_time');
						$now = current_time('timestamp');
						if($time <= $now){
							return __('Pending Now');
						}else{
							$init = $time - $now;
							$hours = floor($init / 3600);
							$minutes = floor(($init / 60) % 60);
							$seconds = $init % 60;
							return sprintf(__('Pending %s hours, %s minutes, %s seconds','simple_social_inbox'),$hours, $minutes, $seconds);
						}

					}
				}
				$column_data = ucm_print_date($item['sent_time'],true);
				return $column_data;
			    break;
		    case 'social_column_action':
			    return '<a href="#" class="button">'. __( 'Open','simple_social_inbox' ).'</a>';
			    break;
		    case 'social_column_post':
			    if($item['post_id']){
				    $post = get_post( $item['post_id'] );
				    if(!$post){
					    return 'N/A';
				    }else{
					    return '<a href="'.get_permalink($post->ID).'">' . htmlspecialchars($post->post_title).'</a>';
				    }
			    }else{
				    return __('No Post','simple_social_inbox');
			    }
			    break;
		    case 'social_column_social':
		    default:
				$column_data = '';
				foreach($this->column_details[ $item['social_message_id'] ] as $message_type => $data){
					if(isset($data[$column_name]))$column_data .= $data[$column_name];
				}
				return $column_data;
			    break;

	    }
    }
}