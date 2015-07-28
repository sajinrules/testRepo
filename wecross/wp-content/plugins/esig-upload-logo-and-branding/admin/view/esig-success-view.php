<?php 
// To default a var, add it to an array
	$vars = array(
		'post_action' // will default $data['post_action']
	);
	$this->default_vals($data, $vars);

include($this->rootDir . DS . 'partials/_tab-nav.php'); ?>

<div class="esign-misc-tab">
 <a class="misc_link" href="admin.php?page=esign-misc-general"><?php _e('General Option','esig-ulab') ?></a>  <?php echo $data['customizztion_more_links']; ?>
</div>
	
<h3><?php _e('E-signature Success Page','esig-ulab') ?></h3>

 <?php echo $data['message']; ?>

 <div> <?php _e('This section lets you customise the WP E-Signature Success Page','esig-ulab') ?></div>

<form name="settings_form" class="settings-form" method="post" action="<?php echo $data['post_action']; ?>">	
<table class="form-table esig-settings-form">
	<tbody>
        <tr>
			<th><label for="success_paragraph_text"><?php _e('Success Paragraph Text','esig-ulab') ?></label></th>
				<td><span class="esig-description"> <?php _e('The text to appear in the header of signer success page.','esig-ulab') ?></span>
				<textarea id="esig_success_paragraph_text" name="esig_success_paragraph_text"  rows="5" cols="100%"><?php if(!empty($data['esig_success_page_paragraph_text'])){ echo htmlspecialchars(stripslashes($data['esig_success_page_paragraph_text']));} else { echo "Excellent work! You signed {document_title} like a boss."; }?></textarea></td>
		</tr>
    	<tr>
			<th><label for="esig_success_image" id="esig_success_image_label"><?php _e('Success Image','esig-ulab') ?></label></th>
			<td><input type="text" name="esig_branding_success_image" id="esig_branding_success_image" value="<?php echo $data['esig_success_page_image']; ?>" class="regular-text" /><br />
			<span class="description"><?php _e('Enter a URL to an image you want to show in the success page header . Upload your image using the','esig-ulab') ?> <a href="#" id="esig_success_image_upload"><?php _e('Media Uploader','esig-ulab') ?></a></span></td>
		</tr>
		<tr>
		    <th>&nbsp;</th>
			<td><label for="">
					<input name="esig_success_image_show" id="esig_success_image_show" type="checkbox" value="1" <?php echo $data['esig_success_page_image_disable']; ?>> 
                    <?php _e('Hide image on document signing page','esig-ulab') ?> </label>
					
			</td>
    	</tr>
		

	</tbody>
</table>

	<p> 
		<input type="submit" name="esig_success_submit"  class="button-appme button" value="<?php _e('Save Settings','esig-ulab') ?>" />
	</p>
</form>