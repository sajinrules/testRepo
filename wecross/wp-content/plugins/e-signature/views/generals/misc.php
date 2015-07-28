<?php 

if ( ! defined( 'ABSPATH' ) ) { 
	exit; // Exit if accessed directly
}

?>

<?php 
include($this->rootDir . DS . 'partials/_tab-nav.php'); 

// To default a var, add it to an array
	$vars = array(
		'other_form_element', // will default $data['other_form_element']
		'pdf_options', 
		'active_campaign_options'
	);
	$this->default_vals($data, $vars);
?>

<div class="esign-main-tab">

 <a class="misc_link <?php echo $data['link_active']; ?>" href="admin.php?page=esign-misc-general"><?php _e('General Option', 'esig'); ?></a> 
 
 | <a class="misc_link" href="admin.php?page=esign-email-general"><?php _e('Advanced E-mail Settings', 'esig'); ?></a>  
 
 <?php echo $data['customizztion_more_links']; ?>

</div>	
<h3>Misc Options</h3>

 <?php echo $data['message']; ?>
<form name="settings_form" class="settings-form" method="post" action="<?php echo $data['post_action']; ?>">	
<table class="form-table">
	<tbody>
		
		<tr>
			<td> 
				<?php echo $data['other_form_element']; ?> 
			</td>
		</tr>
		
		<tr>
			<td>
				<span id="advanced-settings">		
					<p class="esig-chosen-drop">
					<label><?php _e('Print Document', 'esig' ); ?> <span class="description"><?php _e('default settings:', 'esig' );?></span></label>

						<select name="esig_print_option" style="width:500px;" tabindex="9" data-placeholder="Choose a Option..." class="esig-select2">
									  
							<option value="1" <?php echo $data['selected1']; ?> ><?php _e('Only display \'Print Document\' button when document is signed by everyone', 'esig'); ?></option>
						 
							<option value="2" <?php echo $data['selected2']; ?> ><?php _e('Hide \'Print Document\' button always, no matter what.', 'esig' ); ?></option>
									
							<option value="3" <?php echo $data['selected3']; ?> ><?php _e('Display \'Print Document\' button always, no matter what.', 'esig' );?></option>
							
							<option value="4" <?php echo $data['selected4']; ?> ><?php _e('Only Display \'Print Document\' while document waiting for signature.', 'esig'); ?></option>
					 
						</select> 	
					</p>	
					<?php echo $data['pdf_options']; ?>
					<?php echo $data['active_campaign_options']; ?>
	   			 </span>
			</td>
		</tr>
	<?php if(esig_total_addons_installed()>0): ?>
		<tr>
    		<td><label for=""><input name="esign_auto_update" id="esign_auto_update" type="checkbox" value="1" <?php echo $data['auto_update_checked']; ?>> <?php _e('Keep my <a href="admin.php?page=esign-addons">E-Signature add-ons</a> up to date automatically, I don\'t want to think about it.', 'esig' ); ?></label>
        
			</td>
    	</tr>
        <?php endif ; ?>
        <tr>
    		<td><label for=""><input name="esign_auto_save_data" id="esign_auto_save_data" type="checkbox" value="1" <?php echo $data['preview_checked']; ?>> <?php _e('Check this box to enable auto save and preview document', 'esig' ); ?></label>
        
			</td>
    	</tr>
        
		<tr>
    		<td><label for=""><input name="esign_remove_all_data" id="esign_remove_data" type="checkbox" value="1" <?php echo $data['esign_remove_data']; ?>> <?php _e('<strong style="color:red;">Danger Zone</strong> - Check this box if you would like WP E-Signature to completely remove ALL of its data when the plugin is deleted.', 'esig' ); ?></label>
        <span class="description"><?php _e('If box is checked, when plugin is deleted all documents and signatures will be lost forever.', 'esig' );?></span>
			</td>
    	</tr>
        
	
	    <?php echo $data['misc_extra_content']; ?>
	</tbody>
</table>
		

	<p>
		<input type="submit" name="misc-submit" class="button-appme button" value="Save Settings" />
	</p>
</form>
