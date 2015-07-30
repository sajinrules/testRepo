<?php
/* Plugin Name: Better Admin Pointers
Plugin URI: http://stephensuess.com/
Description: Admin pointers for WP Admin. These will work on any post type or page.
Version: 2.0
Author: Stephen Suess
Text Domain: better-admin-pointers
Domain Path: /languages
Author URI: http://stephensuess.com/
License: GPLv2 or later
*/


function sbap_activation() {
}
register_activation_hook(__FILE__, 'sbap_activation');
function sbap_deactivation() {
}
register_deactivation_hook(__FILE__, 'sbap_deactivation');

function sbap_load_plugin_textdomain() {
    load_plugin_textdomain( 'better-admin-pointers', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'sbap_load_plugin_textdomain' );


// LINK LOVE
function sbap_custom_plugin_row_meta( $links, $file ) {
 
   if ( strpos( $file, 'better-admin-pointers.php' ) !== false ) {
      $new_links = array(
               '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=paypal%40c%2esatoristephen%2ecom&lc=US&item_name=Stephen%20Suess&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted" target="_blank">' . __( 'Donate', 'better-admin-pointers' ) . '</a>',
            );
       
      $links = array_merge( $links, $new_links );
   }
    
   return $links;
}
 
add_filter( 'plugin_row_meta', 'sbap_custom_plugin_row_meta', 10, 2 );



include_once( dirname( __FILE__ ) . '/sbap-options.php');

add_action('init', 'sbap_register_pointer');

function sbap_register_pointer() {

    $labels = array(

		'name'               => _x( 'Pointers', 'post type general name', 'better-admin-pointers' ),
		'singular_name'      => _x( 'Pointer', 'post type singular name', 'better-admin-pointers' ),
		'menu_name'          => _x( 'Pointers', 'admin menu', 'better-admin-pointers' ),
		'name_admin_bar'     => _x( 'Pointer', 'add new on admin bar', 'better-admin-pointers' ),
		'add_new'            => _x( 'Add New', 'pointer', 'better-admin-pointers' ),
		'add_new_item'       => __( 'Add New Pointer', 'better-admin-pointers' ),
		'new_item'           => __( 'New Pointer', 'better-admin-pointers' ),
		'edit_item'          => __( 'Edit Pointer', 'better-admin-pointers' ),
		'view_item'          => __( 'View Pointer', 'better-admin-pointers' ),
		'all_items'          => __( 'All Pointers', 'better-admin-pointers' ),
		'search_items'       => __( 'Search Pointers', 'better-admin-pointers' ),
		'parent_item_colon'  => __( 'Parent Pointers:', 'better-admin-pointers' ),
		'not_found'          => __( 'No pointers found.', 'better-admin-pointers' ),
		'not_found_in_trash' => __( 'No pointers found in Trash.', 'better-admin-pointers' ),
    );

    $args = array(

       'labels' => $labels,

       'hierarchical' => true,

       'description' => 'Pointers',
        
       'menu_icon' => 'dashicons-flag',

       'supports' => array('title', 'editor'),

       'public' => true,

       'show_ui' => true,

       'show_in_menu' => true,

       'show_in_nav_menus' => true,

       'publicly_queryable' => false,

       'exclude_from_search' => true,

       'has_archive' => false,

       'query_var' => true,

       'can_export' => true,

       'rewrite' => true,

       'capability_type' => 'post'

    );

    register_post_type('sbap_pointer', $args);

}


//INITIALIZE THE METABOX CLASS

function sbap_initialize_cmb_meta_boxes() {
	if ( ! class_exists( 'cmb_Meta_Box' ) )
		require_once(plugin_dir_path( __FILE__ ) . 'cmbclass/init.php');
}

add_action( 'init', 'sbap_initialize_cmb_meta_boxes', 9999 );

//Add Meta Boxes

function sbap_metaboxes( $meta_boxes ) {
	$prefix = '_sbap_'; // Prefix for all fields

	$meta_boxes[] = array(
		'id' => 'sbap_metabox',
		'title' => 'Better Admin Pointers Box info',
		'pages' => array('sbap_pointer'), // post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
		'fields' => array(
			array(
				'name' => __( 'Pointer id', 'better-admin-pointers' ),
				'desc' => __( 'Give your pointer a unique id (so that it can be tracked in the db as dismissed)', 'better-admin-pointers' ),
				'id' => $prefix . 'pointerid_text',
				'type' => 'text',
				'sanitization_cb' => 'sanitize_title'
			),
			array(
				'name' => __( 'Screen', 'better-admin-pointers' ),
				'desc' => __( 'What screen (or post/page ID if front end) will this show up on (example: my-custom-post-type). To have it show on ALL pages, use "ALL_ADMIN" for admin and "ALL_FRONT" for front end. (without the quotes)', 'better-admin-pointers' ),
				'id' => $prefix . 'screen_text',
				'type' => 'text'
			),		
			array(
				'name' => __( 'Target', 'better-admin-pointers' ),
				'desc' => __( 'What CSS id or class on the screen above are we targeting? (example: #delete-action)', 'better-admin-pointers' ),
				'id' => $prefix . 'target_text',
				'type' => 'text'
			),
			array(
				'name' => __( 'Position Edge', 'better-admin-pointers' ),
				'desc' => __( 'Which edge should be adjacent to the target? (left, right, top, or bottom)', 'better-admin-pointers' ),
				'id' => $prefix . 'positionedge_text',
				'type' => 'text'
			),			
			array(
				'name' => __( 'Position Align', 'better-admin-pointers' ),
				'desc' => __( 'How should the pointer be aligned on this edge, relative to the target? (top, bottom, left, right, or middle)', 'better-admin-pointers' ),
				'id' => $prefix . 'positionalign_text',
				'type' => 'text'
			),
			array(
				'name' => __( 'Nudge Horizontal', 'better-admin-pointers' ),
				'desc' => __( 'How much should we nudge the pointer horizontally? (Value in pixels. ex: -50, from edge value above, only works if edge above is left or right)', 'better-admin-pointers' ),
				'id' => $prefix . 'nudgehorizontal_text',
				'type' => 'text'
			),
			array(
				'name' => __( 'Nudge Vertical', 'better-admin-pointers' ),
				'desc' => __( 'How much should we nudge the pointer vertically? (Value in pixels. ex: -50, from align value above, only works if align above is top or bottom)', 'better-admin-pointers' ),
				'id' => $prefix . 'nudgevertical_text',
				'type' => 'text'
			),
			array(
				'name' => __( 'Z-index', 'better-admin-pointers' ),
				'desc' => __( 'What z-index should the pointer have? (in case you need to order them at different depths)', 'better-admin-pointers' ),
				'id' => $prefix . 'zindexitem_text',
				'type' => 'text'
			),
		),
	);

	return $meta_boxes;
}
add_filter( 'cmb_meta_boxes', 'sbap_metaboxes' );

//CUSTOMIZE THE COLUMNS

add_filter( 'manage_edit-sbap_pointer_columns', 'my_edit_sbap_pointer_columns' ) ;

function my_edit_sbap_pointer_columns( $columns ) {

	$columns = array(
		'title' => __( 'Pointer Title' , 'better-admin-pointers'),
		'_sbap_screen_text' => __( 'Screen/Page' , 'better-admin-pointers'),
		'_sbap_target_text' => __( 'Target' , 'better-admin-pointers'),
		'date' => __( 'Date' , 'better-admin-pointers')
	);

	return $columns;
}

add_action( 'manage_sbap_pointer_posts_custom_column', 'my_manage_sbap_pointer_columns', 10, 2 );

function my_manage_sbap_pointer_columns( $column, $post_id ) {
	global $post;

	switch( $column ) {

		/* If displaying the 'screen' column. */
		case '_sbap_screen_text' :

			/* Get the post meta. */
			$screentext = get_post_meta( $post_id, '_sbap_screen_text', true );

			/* If no screentext is found, output a default message. */
			if ( empty( $screentext ) )
				echo __( 'Unknown' , 'better-admin-pointers');
			else
			echo $screentext;
			break;

		/* If displaying the 'target' column. */
		case '_sbap_target_text' :

			/* Get the post meta. */
			$targettext = get_post_meta( $post_id, '_sbap_target_text', true );

			/* If no target is found, output a default message. */
			if ( empty( $targettext ) )
				echo __( 'Unknown' , 'better-admin-pointers');
			echo $targettext;

			break;
			
		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}



function makePointerQuery(){
$pointer_query = array();
$the_query = get_posts('post_type=sbap_pointer&posts_per_page=-1');
foreach ( $the_query as $post ) : setup_postdata( $post );
       $pointer_query[] = array(
            'id'       => get_post_meta($post->ID, '_sbap_pointerid_text', true ),
            'screen'   => get_post_meta($post->ID, '_sbap_screen_text', true ),
            'target'   => get_post_meta($post->ID, '_sbap_target_text', true ),
            'title'    => get_the_title($post->ID),
            'content'  => get_the_content($post->ID),
            'position' => array(
                'edge'  => get_post_meta( $post->ID, '_sbap_positionedge_text', true ), 
                'align' => get_post_meta( $post->ID, '_sbap_positionalign_text', true ),
                'nudgehorizontal'  => get_post_meta( $post->ID, '_sbap_nudgehorizontal_text', true ), 
                'nudgevertical' => get_post_meta( $post->ID, '_sbap_nudgevertical_text', true ), 
                'zindexitem' => get_post_meta( $post->ID, '_sbap_zindexitem_text', true ) 
                )
        );
endforeach; 
wp_reset_postdata();
return $pointer_query;
}    

add_action( 'admin_enqueue_scripts', 'BetterHelpPointers' );

function BetterHelpPointers()
{
new BHP_Admin_Pointer(makePointerQuery());
}

class BHP_Admin_Pointer
{
    public $screen_id;
    public $valid;
    public $pointers;

    /**
     * Register variables and start up plugin
     */
    public function __construct( $pointers = array( ) )
    {
        if( get_bloginfo( 'version' ) < '3.3' )
            return;

        $screen = get_current_screen();
        $this->screen_id = $screen->id;
        $this->register_pointers( $pointers );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_pointers' ), 1000 );
        add_action( 'admin_print_footer_scripts', array( $this, 'add_scripts' ) );
    }


    /**
     * Register the available pointers for the current screen
     */
    public function register_pointers( $pointers )
    {
        $screen_pointers = null;
        foreach( $pointers as $ptr )
        {
            if(( $ptr['screen'] == $this->screen_id )||( strtoupper($ptr['screen']) == "ALL_ADMIN" ))
            {
                $options = array(
                    'content'  => sprintf(
                        '<h3> %s </h3> <p> %s </p>', 
                        __( $ptr['title'], 'better-admin-pointers' ), 
                        __( $ptr['content'], 'better-admin-pointers' )
                    ),
                    'position' => $ptr['position']
                );
                $screen_pointers[$ptr['id']] = array(
                    'screen'  => $ptr['screen'],
                    'target'  => $ptr['target'],
                    'options' => $options
                );
            }
        }
        $this->pointers = $screen_pointers;
    }


    /**
     * Add pointers to the current screen if they were not dismissed
     */
    public function add_pointers()
    {
        if( !$this->pointers || !is_array( $this->pointers ) )
            return;

        // Get dismissed pointers
        $get_dismissed = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
        $dismissed = explode( ',', (string) $get_dismissed );

        // Check pointers and remove dismissed ones.
        $valid_pointers = array( );
        foreach( $this->pointers as $pointer_id => $pointer )
        {
            if(
                in_array( $pointer_id, $dismissed ) 
                || empty( $pointer ) 
                || empty( $pointer_id ) 
                || empty( $pointer['target'] ) 
                || empty( $pointer['options'] )
            )
                continue;

            $pointer['pointer_id'] = $pointer_id;
            $valid_pointers['pointers'][] = $pointer;
        }

        if( empty( $valid_pointers ) )
            return;

        $this->valid = $valid_pointers;
        wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script('wp-pointer-sbap',plugins_url( '/better-admin-pointers/js/wp-pointer.sbap.js' , dirname(__FILE__) ),array( 'jquery','jquery-ui-widget', 'jquery-ui-position' ),'1.0',true);
     wp_localize_script( 'wp-pointer-sbap', 'wpPointerL10n', array(
		'dismiss' => __('Dismiss', 'better-admin-pointers'),
	) );      
    }

    /**
     * Print JavaScript if pointers are available
     */
    public function add_scripts()
    {
        if( empty( $this->valid ) )
            return;

        $pointers = json_encode( $this->valid );

        echo <<<HTML
<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready( function($) {
		var WPHelpPointer = {$pointers};

		$.each(WPHelpPointer.pointers, function(i) {
			wp_help_pointer_open(i);
		});

		function wp_help_pointer_open(i) 
		{
			pointer = WPHelpPointer.pointers[i];
			$( pointer.target ).pointer( 
			{
				content: pointer.options.content,
				position: 
				{
					edge: pointer.options.position.edge,
					align: pointer.options.position.align,
                    nudgehorizontal: pointer.options.position.nudgehorizontal,
                    nudgevertical: pointer.options.position.nudgevertical,
					zindexitem: pointer.options.position.zindexitem
				},
				close: $.proxy(function () {
    $.post(ajaxurl, this);
}, {
    pointer: pointer.pointer_id,
    action: 'dismiss-wp-pointer'
}),
				
			}).pointer('open');
		}
	});
//]]>
</script>
HTML;
    }
    
}

add_action( 'wp_enqueue_scripts', 'BetterHelpPointers_front' );


add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

function BetterHelpPointers_front()
{
new BHP_Admin_Pointer_front(makePointerQuery());
}

class BHP_Admin_Pointer_front
{
    public $screen_id;
    public $valid;
    public $pointers;

    /**
     * Register variables and start up plugin
     */
    public function __construct( $pointers = array( ) )
    {
        if( get_bloginfo( 'version' ) < '3.3' )
            return;

        //$screen = get_current_screen();
        $this->screen_id = get_the_id();
        $this->register_pointers( $pointers );
        add_action( 'wp_enqueue_scripts', array( $this, 'add_pointers' ), 1000 );
        add_action( 'wp_print_footer_scripts', array( $this, 'add_scripts' ) );
    }


    /**
     * Register the available pointers for the current screen
     */
    public function register_pointers( $pointers )
    {
        $screen_pointers = null;
        foreach( $pointers as $ptr )
        {
            if(( $ptr['screen'] == $this->screen_id )||( strtoupper($ptr['screen']) == "ALL_FRONT" ))
            {
                $options = array(
                    'content'  => sprintf(
                        '<h3> %s </h3> <p> %s </p>', 
                        __( $ptr['title'], 'better-admin-pointers' ), 
                        __( $ptr['content'], 'better-admin-pointers' )
                    ),
                    'position' => $ptr['position']
                );
                $screen_pointers[$ptr['id']] = array(
                    'screen'  => $ptr['screen'],
                    'target'  => $ptr['target'],
                    'options' => $options
                );
            }
        }
        $this->pointers = $screen_pointers;
    }


    /**
     * Add pointers to the current screen if they were not dismissed
     */
    public function add_pointers()
    {
    
    if ( is_user_logged_in() ) {
        if( !$this->pointers || !is_array( $this->pointers ) )
            return;

        // Get dismissed pointers
        $get_dismissed = get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true );
        $dismissed = explode( ',', (string) $get_dismissed );

        // Check pointers and remove dismissed ones.
        $valid_pointers = array( );
        foreach( $this->pointers as $pointer_id => $pointer )
        {
            if(
                in_array( $pointer_id, $dismissed ) 
                || empty( $pointer ) 
                || empty( $pointer_id ) 
                || empty( $pointer['target'] ) 
                || empty( $pointer['options'] )
            )
                continue;

            $pointer['pointer_id'] = $pointer_id;
            $valid_pointers['pointers'][] = $pointer;
        }

        if( empty( $valid_pointers ) )
            return;

        $this->valid = $valid_pointers;
        wp_enqueue_style( 'wp-pointer' );
    wp_enqueue_script('wp-pointer-sbap',plugins_url( '/better-admin-pointers/js/wp-pointer.sbap.js' , dirname(__FILE__) ),array( 'jquery','jquery-ui-widget', 'jquery-ui-position' ),'1.0',true);
     wp_localize_script( 'wp-pointer-sbap', 'wpPointerL10n', array(
		'dismiss' => __('Dismiss', 'better-admin-pointers'),
	) );      
    } }

    /**
     * Print JavaScript if pointers are available
     */
    public function add_scripts()
    {
        if( empty( $this->valid ) )
            return;

        $pointers = json_encode( $this->valid );

        echo <<<HTML
<script type="text/javascript">
//<![CDATA[
	jQuery(document).ready( function($) {
		var WPHelpPointer = {$pointers};

		$.each(WPHelpPointer.pointers, function(i) {
			wp_help_pointer_open(i);
		});

		function wp_help_pointer_open(i) 
		{
			pointer = WPHelpPointer.pointers[i];
			$( pointer.target ).pointer( 
			{
				content: pointer.options.content,
				position: 
				{
					edge: pointer.options.position.edge,
					align: pointer.options.position.align,
                    nudgehorizontal: pointer.options.position.nudgehorizontal,
                    nudgevertical: pointer.options.position.nudgevertical,
					zindexitem: pointer.options.position.zindexitem
				},
				close: $.proxy(function () {
    $.post(ajaxurl, this);
}, {
    pointer: pointer.pointer_id,
    action: 'dismiss-wp-pointer'
}),
				
			}).pointer('open');
		}
	});
//]]>
</script>
HTML;
    }
    
}

// GET CURRENT USER ROLE
function get_user_role() {
    global $current_user;

    $user_roles = $current_user->roles;
    $user_role = array_shift($user_roles);

    return $user_role;
}

//REMOVE POINTERS CPT IF USER ROLE NOT IN LIST

function sbap_remove_menu_items() {
 $takenoptions = get_option('sbap_options');
 if ($takenoptions['sbap_view_cpt']) {
 $takenroles = array_map('strtolower', $takenoptions['sbap_view_cpt']);
 if (!in_array(get_user_role(),$takenroles)) {
        remove_menu_page( 'edit.php?post_type=sbap_pointer' );
    }
} else {

if (get_user_role() != 'administrator') {
        remove_menu_page( 'edit.php?post_type=sbap_pointer' );
}
}
}

add_action( 'admin_menu', 'sbap_remove_menu_items' );


if (!(isset($_GET['page']) && $_GET['page'] == 'sbap_options')) {
global $wpdb;

$checkforold = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key IN ('_wpb_nudgehorizontal_text','_wpb_nudgevertical_text','_wpb_pointerid_text','_wpb_positionalign_text','_wpb_positionedge_text','_wpb_screen_text','_wpb_target_text')");
if ($checkforold) {

// SHOW ADMIN MESSAGE IF NOT UPDATED TO NEW FORMAT 
add_action( 'admin_notices', 'sbap_wp_admin_area_notice' );

function sbap_wp_admin_area_notice() {  
if ( current_user_can( 'manage_options' ) ) {
   echo '<br><div class="error">
        <p>You have pointers in your database that need updating. Please go <a href="'.get_admin_url().'edit.php?post_type=sbap_pointer&page=sbap_options">here to update.</a> </p>
          </div>';
}}
}}

//SHOW SCREEN IF OPTION CHECKED


add_action( 'admin_notices', 'sbap_show_current_screen' );
function sbap_show_current_screen() {
$screenoptions = get_option('sbap_options');
if(isset($screenoptions['sbap_view_screen'][0])) {
$takenscreen = $screenoptions['sbap_view_screen'][0];
}
 //var_dump($takenscreen);
if(isset($takenscreen)) { 

	if( defined( 'DOING_AJAX' ) && DOING_AJAX ) return;
	
	global $current_screen;
	
	echo "<div id='screennotice' style='border:1px solid black;background:white;padding:10px;margin-top:15px;float:left;'><strong>This is your current screen ID ( You can turn this off <a href='/wp-admin/edit.php?post_type=sbap_pointer&page=sbap_options'>here</a> ) :</strong>  <span style='color:red;'>".$current_screen->id. "</span></div><div style='clear:both;'></div>";
}
}


?>