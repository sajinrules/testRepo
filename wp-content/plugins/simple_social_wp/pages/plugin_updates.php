<?php
$licence_code = get_site_option("_envato_licence7478754","");
if(isset($_REQUEST["save_envato_licence"])){
    $licence_code = $_REQUEST["save_envato_licence"];
    update_site_option("_envato_licence7478754",$licence_code);
}
?><div class="wrap">

	<?php wp_nonce_field('dtbaker-header');
	if ( isset( $_REQUEST['saved'] ) ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved, thank you!').'</strong></p></div>';
	?>
    <h2>CodeCanyon Plugin Updates:</h2>
    <p>Thank you for purchasing this plugin from CodeCanyon.</p>
    <p>To setup automatic updates please enter the "licence purchase code" below:</p>


    <form action="" method="post" >
        <input type="text" name="save_envato_licence" value="<?php echo htmlspecialchars($licence_code);?>"
               style="padding:5px; font-size: 16px; width: 400px;"> <br/>
        <input type="submit" class="button-primary" value="<?php _e('Save Licence Key') ?>" />
    </form>


    <p>Your unique code is in your "Licence Certificate" on the <strong>Downloads</strong> page in CodeCanyon.net (where you downloaded this plugin).</p>
    <p>The key looks something like this: 39d40592-12c0-1234-988b-123458cd736b</p>
    <p>Please <a href="http://dtbaker.net/admin/includes/plugin_envato/images/envato-license-code.gif" target="_blank">click here</a> for instructions on how to locate this code. If you have any questions or need support using this plugin please <a href="http://dtbaker.net/envato/support-ticket/" target="_blank">send us an email</a>.</p>


</div>