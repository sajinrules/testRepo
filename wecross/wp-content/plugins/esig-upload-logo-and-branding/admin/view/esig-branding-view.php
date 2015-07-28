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
	
<h3><?php _e('E-signature Branding','esig-ulab') ?></h3>

 <?php echo $data['message']; ?>

 <div> <?php _e('This section lets you customise the WP E-Signature','esig-ulab') ?></div>

<form name="settings_form" class="settings-form" method="post" action="<?php echo $data['post_action']; ?>">	
<table class="form-table esig-settings-form">
	<tbody>
    	<tr>
			<th><label for="header_image" id="header_image_label"><?php _e('Header Image','esig-ulab') ?></label></th>
			<td> <a href="#" id="esig_logo_upload" class="button insert-media add_media"><?php _e('Upload Your Logo','esig-ulab') ?></a><br />
            
            <p>or</p>
            <span class="description"><?php _e('Enter a URL to an image you want to use instead','esig-ulab') ?></span>
            <input type="text" name="esig_branding_header_image" id="esig_branding_header_image" value="<?php echo $data['esig_branding_header_image']; ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
		    <th>&nbsp;</th>
			<td><label for="">
					<input name="esig_document_head_img" id="esig_document_head_img" type="checkbox" value="1" <?php echo $data['esig_document_head_img']; ?>> 
                    <?php _e('Display header image on document signing page','esig-ulab') ?> </label>
					
			</td>
    	</tr>
        <tr>
		    <th>&nbsp;</th>
			<td>
            <?php
                 $alignment=$data['esig_head_img_alignment'];
            ?>
            <input type="radio" name="esig_document_head_img_alignment" value="left" <?php if($alignment=='left'){ echo 'checked';} ?>> Align Left
            <input type="radio" name="esig_document_head_img_alignment" value="center" <?php if($alignment=='center'){ echo 'checked';} ?>> Align Center
            <input type="radio" name="esig_document_head_img_alignment" value="right" <?php if($alignment=='right'){ echo 'checked';} ?>> Align Right
			</td>
    	</tr>
		<tr>
			<th><label for="logo_tagline" id="logo_tagline_label"><?php _e('Logo Tagline','esig-ulab') ?></label></th>
			<td><input type="text" name="esig_branding_logo_tagline" id="esig_branding_logo_tagline" value="<?php echo $data['esig_branding_logo_tagline']; ?>" class="regular-text" />
			<span class="description"><?php _e('Enter the tagline text that will appear beneath your logo in the signer invite emails','esig-ulab') ?></span></td>
		</tr>

		<tr>
			<th><label for="footer_text_headline" id="footer_text_headline_label"><?php _e('Footer Text Headline','esig-ulab') ?></label></th>
			<td><input type="text" <?php echo $data['esig_extra_attr']; ?> name="esig_branding_footer_text_headline" id="esig_branding_footer_text_headline" size="30" value="<?php echo $data['esig_branding_footer_text_headline']; ?>"  class="regular-text" />
			
        	<span class="description"><?php _e('Enter the headline text that will appear above the footer text in the signer invite emails.','esig-ulab') ?></span></td>
		</tr>

		<tr>
			<th><label for="email_footer_text"><?php _e('E-mail Footer Text','esig-ulab') ?></label></th>
				<td><span class="esig-description"> <?php _e('The text to appear in the footer of signer invite emails.','esig-ulab') ?></span>
				<textarea <?php echo $data['esig_extra_attr']; ?> id="esig_branding_footer_text" name="esig_branding_email_footer_text"  rows="5" cols="100%"><?php echo $data['esig_branding_email_footer_text']; ?></textarea></td>
		</tr>
	
		<tr>
		    <th>&nbsp;</th>
			<td><label for="">
					<input name="esig_brandhing_disable" id="esig_brandhing_disable" type="checkbox" value="1" <?php echo $data['esig_brandhing_disable']; ?>> <?php _e('Disable footer text','esig-ulab') ?></label>
					<br>
					<span class="description"><?php _e('If the box is checked, the footer text and header will not displayed.','esig-ulab') ?></span>
			</td>
    	</tr>
        
        <tr>
		   <th><label for="esig_cover_text"><?php _e('Document Cover Page','esig-ulab') ?></label></th>
			<td><label for="">
					<input name="esig_cover_page" id="esig_cover_page" type="checkbox" value="1" <?php echo $data['esig_cover_page']; ?>> <?php _e('Create a cover page with my logo and document info','esig-ulab') ?></label>
					
			</td>
    	</tr>
        
         <tr>
		    <th>E-mail Sender :</th>
			<td>
            <?php
                 $sender_type=$data['esig_email_invitation_sender_checked'];
            ?>
            <input type="radio" name="esig_email_invitation_sender_checked" value="owner" <?php if($sender_type=='owner'){ echo 'checked';} if(empty($sender_type)){ echo 'checked' ;} ?>> Super admin first name last name
            <input type="radio" name="esig_email_invitation_sender_checked" value="company" <?php if($sender_type=='company'){ echo 'checked';} ?>>Organization's Name
            
			</td>
    	</tr>
        
         <tr>
		    <th>&nbsp;</th>
			<td><label for="">
					<input name="esig_button_background" id="esig_button_background" type="textbox" value="<?php if(array_key_exists('esig_branding_back_color', $data)){ echo $data['esig_branding_back_color'];} else { echo '#0083c5'; } ?>" class="esig-color-picker" /> </label>
					
			</td>
    	</tr>
		
	</tbody>
</table>

	<p> 
		<input type="submit" name="branding_submit"  class="button-appme button" value="<?php _e('Save Settings','esig-ulab') ?>" />
	</p>
</form>