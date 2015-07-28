<?php
//Options Page 

/** Step 2 (from text above). */
add_action( 'admin_menu', 'sbap_plugin_menu' );

/** Step 1. */
function sbap_plugin_menu() {
	global $sbap_pointer;
	$sbap_pointer = add_submenu_page( 'edit.php?post_type=sbap_pointer', 'Better Admin Pointers Options', 'BAP Options', 'manage_options', 'sbap_options', 'sbap_plugin_options');
 // Adds my_help_tab when my_admin_page loads
    add_action('load-'.$sbap_pointer, 'sbap_pointer_add_help_tab');
}

function sbap_pointer_add_help_tab () {
    global $sbap_pointer;
    $screen = get_current_screen();

    /*
     * Check if current screen is pointer
     * Don't add help tab if it's not
     */
    if ( $screen->id != $sbap_pointer )
        return;

    // Add my_help_tab if current screen is Options page
    $screen->add_help_tab( array(
        'id'	=> 'sbap_help_options',
        'title'	=> __('Show Current Screen'),
        'content'	=> '<p>' . __( 'This option, when checked, will load a small bar in the header that tells you what your current screen is. Handy for knowing what to put in the "screen" field.' ) . '</p>',
    ) );
    
}

/** Step 3. */
function sbap_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' , 'better-admin-pointers') );
	} ?>
	<div><?php
global $wpdb;
$checkforold = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key IN ('_wpb_nudgehorizontal_text','_wpb_nudgevertical_text','_wpb_pointerid_text','_wpb_positionalign_text','_wpb_positionedge_text','_wpb_screen_text','_wpb_target_text')");
if ($checkforold) {
  // do something with the link 
  echo "<br><div class='error' style='margin-left:0 !important;'><p>". __( 'You have pointers in your database (prior to version 1.3) that are in the old format. Until you update the db, your old pointers will not show up (although any newly created ones will). Click the button below to update it and import your old pointers.' , 'better-admin-pointers') ."</p></div>";
$other_attributes = array( 'id' => 'wpdocs-button-id' );?>
<form action="" method="post">
<input type="hidden" id="updateold" value="updateold" name="updateold" />
<?php
submit_button( 'Import Old Pointers', 'primary', 'sbap-update-old', true, $other_attributes );
?>
</form><?php
} 

 if(isset($_POST['updateold'])) { 
update_old_pointers_callback();
echo "<div class='updated' style='margin-left:0 !important;padding-top:8px;padding-bottom:8px;'>". __( 'OLD POINTERS SUCESSFULLY IMPORTED' , 'better-admin-pointers') ."</div>";
 } 
 
 
 
 echo '<h3>'. __( 'Reset all Pointers' , 'better-admin-pointers') . '</h3>';
 echo '<p>' . __( 'Click the button below to reset all pointers (so they will show anew) for all users. This will include all the default WP built-in pointers, as well as any you have defined with the plugin.' , 'better-admin-pointers') . '</p>';
 ?>
<form action="" method="post" id="resetallpointers">
<input type="hidden" id="resetallp" value="resetallp" name="resetallp" />
<?php  $other_attributes = array( 'id' => 'sbap-button-id' );

submit_button( 'Reset All Pointers', 'primary', 'sbap-reset-pointers', true, $other_attributes );
?>
</form>
 
 <?php 
 
  if(isset($_POST['resetallp'])) { 
delete_old_pointers();
echo "<div class='updated' style='margin-left:0 !important;padding-top:8px;padding-bottom:8px;'>" . __( 'DISMISSED POINTERS SUCESSFULLY RESET' , 'better-admin-pointers') ."</div>";
 }
 
 ?>
 
<form action="options.php" method="post">
<?php settings_fields('sbap_options'); ?>
<?php do_settings_sections('sbap_options'); ?>
<?php submit_button(); ?>
</form></div>
 
<?php
printf( __( '<p>You are running version %s of Better Admin Pointers.</p>', 'better-admin-pointers' ), sbap_version_init() );


}



 // ------------------------------------------------------------------
 // Add all your sections, fields and settings during admin_init
 // ------------------------------------------------------------------
 //
 
 function sbap_settings_api_init() {
 	// Add the section to reading settings so we can add our
 	// fields to it
 	add_settings_section(
		'sbap_setting_section',
		__( 'BAP Options' , 'better-admin-pointers'),
		'sbap_setting_section_callback_function',
		'sbap_options'
	);
 	
 	// Add the field with the names and function to use for our new
 	// settings, put it in our new section
 	add_settings_field(
		'sbap_view_cpt',
		__( 'Pointers Admin Viewable By:' , 'better-admin-pointers'),
		'sbap_setting_callback_function',
		'sbap_options',
		'sbap_setting_section'
	);
	add_settings_field(
		'sbap_view_screen',
		__( 'Show Current Screen' , 'better-admin-pointers'),
		'sbap_setting_screen_callback_function',
		'sbap_options',
		'sbap_setting_section'
	);
 	
 	
 	// Register our setting so that $_POST handling is done for us and
 	// our callback function just has to echo the <input>
 	register_setting( 'sbap_options', 'sbap_options' );
 } // sbap_settings_api_init()
 
 add_action( 'admin_init', 'sbap_settings_api_init' );
 
function sbap_version_init() {
$plugin_file = plugin_dir_path( __FILE__ ) . 'better-admin-pointers.php';
//echo $plugin_file;
$pdata= get_plugin_data( $plugin_file, $markup = true );
$currVersion = $pdata['Version'];
return $currVersion;
  }
 
  add_action( 'admin_init', 'sbap_version_init' );
 
  
 // ------------------------------------------------------------------
 // Settings section callback function
 // ------------------------------------------------------------------
 //
 // This function is needed if we added a new section. This function 
 // will be run at the start of our section
 //
 
 function sbap_setting_section_callback_function() {
 	echo '<p></p>';
 }
 
 // ------------------------------------------------------------------
 // Callback function for our  setting
 // ------------------------------------------------------------------
 //
 //
 
 function sbap_setting_callback_function() {

 global $wp_roles;
 $all_roles = $wp_roles->roles;
 $editable_roles = apply_filters('editable_roles', $all_roles);
 Foreach ($editable_roles as $role) {
 $takenoptions = get_option('sbap_options');
 $takenroles = $takenoptions['sbap_view_cpt'];
 $rolename = $role["name"];

if ($takenoptions['sbap_view_cpt']) {

 if (in_array($rolename,$takenroles) || ($rolename == __( 'Administrator' , 'better-admin-pointers'))) {
 $ischecked = 'checked';
 } else {
 $ischecked = '';
 }
 } else {
 
 if (($rolename == __( 'Administrator' , 'better-admin-pointers'))) {
 $ischecked = 'checked';
 } else {
 $ischecked = '';
 }
 }
if ($rolename == __( 'Administrator' , 'better-admin-pointers')) {
$isdisabled = 'disabled';
} else {
$isdisabled = '';
} 
 echo '<input type="checkbox" id="'.$rolename.'" value="'.$rolename.'" name="sbap_options[sbap_view_cpt][]"' . $ischecked . ' ' . $isdisabled. '/>';
 if ($rolename == __( 'Administrator' , 'better-admin-pointers')) {
 echo '<label for="'.$rolename.'">' . __( 'Administrator (Can always see pointers and is only role to see options page)' , 'better-admin-pointers') . '</label><br>';
 } else {
  echo '<label for="'.$rolename.'">'.$rolename.'</label><br>';
 }
 }
 echo '<input type="hidden" id="Administrator" value="Administrator" name="sbap_options[sbap_view_cpt][]" />';
 }
 

 
 
  function sbap_setting_screen_callback_function() {
$screenoptions = get_option('sbap_options');
if(isset($screenoptions['sbap_view_screen'][0])) {
$takenscreen = $screenoptions['sbap_view_screen'][0];
} //var_dump($takenscreen);

if(isset($takenscreen)) { 
 $ischecked = 'checked';
 } else {
 $ischecked = '';
 }
 echo '<input type="checkbox" id="sbap_view_screen" value="1" name="sbap_options[sbap_view_screen][0]" '.$ischecked.' />';
 
 }
 
 
 
if (isset($_GET['page']) && $_GET['page'] == 'sbap_options') { 


function update_old_pointers_callback() {
     global $wpdb; // this is how you get access to the database
     
     $wpdb->query(
	"
	UPDATE $wpdb->postmeta 
	SET meta_key = replace(meta_key, '_wpb', '_sbap')
	WHERE meta_key IN ('_wpb_nudgehorizontal_text','_wpb_nudgevertical_text','_wpb_pointerid_text','_wpb_positionalign_text','_wpb_positionedge_text','_wpb_screen_text','_wpb_target_text');
	"
);
}

function delete_old_pointers() {
global $wpdb;
$wpdb->query( 
	$wpdb->prepare( 
		"
        DELETE FROM $wpdb->usermeta
		 WHERE meta_key = %s
		",
	        'dismissed_wp_pointers' 
        )
);


}



}
?>