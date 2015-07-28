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

 <a class="misc_link " href="admin.php?page=esign-misc-general"><?php _e('General Option', 'esig'); ?></a> 
 
 | <a class="misc_link <?php echo $data['link_active']; ?>" href="admin.php?page=esign-email-general"><?php _e('Advanced E-mail Settings', 'esig'); ?></a>  
 
 <?php echo $data['customizztion_more_links']; ?>

</div>	


<div class="esig-mail wrap" id="esig-mail">

			<div class="esig-mail-left">
			
			
			<div class="esig-updated" <?php if( empty( $data['message'] ) ) echo "style=\"display:none\""; ?>>
				<p><strong><?php echo $data['message']; ?></strong></p>
			</div>
			
			<div class="error" <?php if ( empty( $data['error'] ) ) echo "style=\"display:none\""; ?>>
				<p><strong><?php echo $data['error']; ?></strong></p>
			</div>
			
			<div class="esig-info-box">
				The E-Signature Advanced Email settings will only affect the emails that are sent from WP E-Signature and will NOT affect your overall WordPress site email settings.  Sending from an SMTP helps prevent your signer invite emails from getting lost in a spam folder.
			</div>
			
			
          <div class="esig-settings-wrap">
            <h3><?php _e('E-signature Advanced E-mail Settings','esig') ?></h3>
			
			
			<?php 
			$esig_options =(array_key_exists('esig_options', $data))?$data['esig_options']:null; 
			$email_class = new WP_E_Email();
			
			 ?>
			
			<form id="esig_settings_form" method="post" action="admin.php?page=esign-email-general">	
							
				<table class="form-table">
				
				<tr valign="top">
						<th scope="row"><?php _e( "Advanced E-mail Settings", 'esig' ); ?></th>
						<td>
							<input type="checkbox" name="esig_adv_mail_enable" value="yes" <?php if( 'yes' == $esig_options['enable'] ) echo 'checked' ; ?> />Enable<br />
							<span class="esig_info"><?php _e( "This checkbox will be used to enable E-signature mail settings", 'easy_wp_smtp' ); ?></span>
					</td>
				  </tr>
				
				
					<tr valign="top">
						<th scope="row"><?php _e( "From Email Address", 'esig' ); ?></th>
						<td>
							<input type="text" name="esig_from_email" class="regular-text" placeholder="e.g. john@gmail.com" value="<?php echo esc_attr( $esig_options['from_email_field'] ); ?>"/><br />
							<span class="esig_info"><?php _e( "This email address will be used in the 'From' field.", 'easy_wp_smtp' ); ?></span>
					</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "From Name", 'esig' ); ?></th>
						<td>
							<input type="text" placeholder="e.g. John Doe" name="esig_from_name" class="regular-text" value="<?php echo esc_attr($esig_options['from_name_field']); ?>"/><br />
							<span  class="esig_info"><?php _e( "This text will be used in the 'FROM' field for your eSignature emails", 'easy_wp_smtp' ); ?></span>
						</td>
					</tr>			
					<tr class="ad_opt esig_smtp_options">
						<th><?php _e( 'SMTP Host', 'esig' ); ?></th>
						<td>
							<input type='text' name='esig_smtp_host' class="regular-text" placeholder="smtp.gmail.com" value="<?php echo esc_attr($esig_options['smtp_settings']['host']); ?>" /><br />
							<span class="esig_info"><?php _e( "Your mail server", 'esig' ); ?></span>
						</td>
					</tr>
					<tr class="ad_opt esig_smtp_options">
						<th><?php _e( 'Type of Encription', 'esig' ); ?></th>
						<td>
							<label for="esig_smtp_type_encryption_1"><input type="radio" id="esig_smtp_type_encryption_1" name="esig_smtp_type_encryption" value='none' <?php if( 'none' == $esig_options['smtp_settings']['type_encryption'] ) echo 'checked="checked"'; ?> /> <?php _e( 'None', 'esig' ); ?></label>
							<label for="esig_smtp_type_encryption_2"><input type="radio" id="esig_smtp_type_encryption_2" name="esig_smtp_type_encryption" value='ssl' <?php if( 'ssl' == $esig_options['smtp_settings']['type_encryption']) echo 'checked="checked"'; ?> /> <?php _e( 'SSL', 'esig' ); ?></label>
							<label for="esig_smtp_type_encryption_3"><input type="radio" id="esig_smtp_type_encryption_3" name="esig_smtp_type_encryption" value='tls' <?php if( 'tls' == $esig_options['smtp_settings']['type_encryption'] ) echo 'checked="checked"'; ?> /> <?php _e( 'TLS', 'esig' ); ?></label><br />
							<span class="esig_info"><?php _e( "For most servers SSL is the recommended option", 'easy_wp_smtp' ); ?></span>
						</td>
					</tr>
					<tr class="ad_opt esig_smtp_options">
						<th><?php _e( 'SMTP Port', 'esig' ); ?></th>
						<td>
							<input type='text' name='esig_smtp_port' class="regular-text" placeholder="e.g. 465" value="<?php echo esc_attr($esig_options['smtp_settings']['port']); ?>" /><br />
							<span class="swpsmtp_info"><?php _e( "The port to your mail server", 'esig' ); ?></span>
						</td>
					</tr>
					<tr class="ad_opt esig_smtp_options">
						<th><?php _e( 'SMTP Authentication', 'easy_wp_smtp' ); ?></th>
						<td>
							<label for="esig_smtp_autentication"><input type="radio" id="esig_smtp_autentication" name="esig_smtp_autentication" value='no' <?php if( 'no' == $esig_options['smtp_settings']['authentication'] ) echo 'checked="checked"'; ?> /> <?php _e( 'No', 'esig' ); ?></label>
							<label for="esig_smtp_autentication"><input type="radio" id="esig_smtp_autentication" name="esig_smtp_autentication" value='yes' <?php if( 'yes' == $esig_options['smtp_settings']['authentication'] ) echo 'checked="checked"'; ?> /> <?php _e( 'Yes', 'esig' ); ?></label><br />
							<span class="esig_info"><?php _e( "This options should always be checked 'Yes'", 'esig' ); ?></span>
						</td>
					</tr>
					<tr class="ad_opt esig_smtp_options">
						<th><?php _e( 'SMTP username', 'esig' ); ?></th>
						<td>
							<input type='text' name='esig_smtp_username' class="regular-text" placeholder="e.g. john@gmail.com" value="<?php echo esc_attr($esig_options['smtp_settings']['username']); ?>" /><br />
							<span class="esig_info"><?php _e( "The username to login to your mail server", 'esig' ); ?></span>
						</td>
					</tr>
					<tr class="ad_opt esig_smtp_options">
						<th><?php _e( 'SMTP Password', 'esig' ); ?></th>
						<td>
							<input type='password' name='esig_smtp_password' class="regular-text" placeholder="e.g. Password" value='<?php echo $email_class->esig_mail_get_password(); ?>' /><br />
							<span class="esig_info"><?php _e( "The password to login to your mail server", 'esig' ); ?></span>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" id="esig-mail-settings-form-submit" class="button-primary" value="<?php _e( 'Save Changes', 'esig' ) ?>" />
					<input type="hidden" name="esig_mail_form_submit" value="submit" />
					<?php wp_nonce_field( "esig-mail-settings", 'esig_mail_nonce_name' ); ?>
				</p>				
			</form>
			</div>
			<div class="esig-updated" <?php if( empty( $data['result'] ) ) echo "style=\"display:none\""; ?>>
				<p><strong><?php echo $data['result']; ?></strong></p>
			</div>
			<div class="esig-settings-wrap">
            <h3><?php _e( 'Important: Test your SMTP settings below:', 'esig' ); ?></h3>
			<form id="esig_test_mail_form" method="post" action="admin.php?page=esign-email-general">					
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php _e( "To", 'esig' ); ?>:</th>
						<td>
							<input type="text" name="esig_to" class="regular-text"  placeholder="steve@gmail.com" value=""/><br />
							<span class="esig_info"><?php _e( "Enter the email address to recipient", 'esig' ); ?></span>
					</td>
					</tr>
					<tr valign="top">
						
							<input type="text" name="esig_mail_subject" class="regular-text" hidden value="Re: Testing E-Signature SMTP"/>
							
						
					</tr>
					<tr valign="top">
						<th scope="row"><?php _e( "Message", 'esig' ); ?>:</th>
						<td>
							<textarea name="esig_mail_message" id="esig_mail_message" rows="5" class="regular-text"></textarea><br />
							<span  class="esig_info"><?php _e( "Write your message", 'esig' ); ?></span>
						</td>
					</tr>				
				</table>
				<p class="submit">
					<input type="submit" id="settings-form-submit" class="button-primary" value="<?php _e( 'Send Test Email', 'esig' ) ?>" />
					<input type="hidden" name="esig_test_mail_submit" value="submit" />
					<?php wp_nonce_field('esig_test_mail', 'esig_mail_test_nonce_name'); ?>
				</p>				
			</form>
			
			</div>
  </div>  
          
          <div class="esig-mail-right esig-smtp-alert esig-top-box">
                Some SMTP servers only allow up to 2,000 emails to be sent per day.  If you expect to exceed this traffic (for all senders) you should use a free transactional email plugin like  <a href="https://wordpress.org/plugins/wpmandrill/?approveme.me">Mandrill WP</a>.
            </div>
            
  <div class="esig-mail-right">
		 		<div class="esig-mail-settings-wrap">
                    <h3> Gmail settings </h3>
                    SMTP Host: smtp.gmail.com<br>
                    Type of Encryption: SSL<br>
                    SMTP Port: 465<br>
                    SMTP Authentication: Yes
                </div>
	
                <hr>
				
                <div class="esig-mail-settings-wrap">
                    <h3> Yahoo settings </h3>
                    SMTP Host: smtp.mail.yahoo.com<br>
                    Type of Encryption: SSL<br>
                    SMTP Port: 465<br>
                    SMTP Authentication: Yes
               </div>
                <hr>
				
                <div class="esig-mail-settings-wrap">
                    <h3> Office 365 settings </h3>
                    SMTP Host: smtp.office365.com<br>
                    Type of Encryption: TLS<br>
                    SMTP Port: 587<br>
                    SMTP Authentication: Yes
               </div>
			    <hr>
				
                <div class="esig-mail-settings-wrap">
                    <h3> Hotmail settings </h3>
                    SMTP Host: smtp.live.com<br>
                    Type of Encryption: SSL<br>
                    SMTP Port: 465<br>
                    SMTP Authentication: Yes
               </div>
			</div>
            
            
</div><!--  #esig-mail .esig-mail -->
		
		
