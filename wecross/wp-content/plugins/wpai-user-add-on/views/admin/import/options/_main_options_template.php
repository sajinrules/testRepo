<div class="wpallimport-collapsed">
	<div class="wpallimport-content-section">
		<div class="wpallimport-collapsed-header">
			<h3><?php _e('User\'s Data','pmxi_plugin');?></h3>	
		</div>
		<div class="wpallimport-collapsed-content" style="padding: 0;">
			<div class="wpallimport-collapsed-content-inner wpallimport-user-data">
				<table style="width:100%;">
					<tr>
						<td colspan="3" style="padding-top:20px;">
							<input type="hidden" name="pmui[import_users]" value="1"/>				
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>First Name</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('The user\'s first name. ', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[first_name]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['first_name']) ?>"/>				
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Last Name</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('The user\'s last name.', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[last_name]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['last_name']) ?>"/>				
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Role</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string with role slug used to set the user\'s role. Default role is subscriber.', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[role]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['role']) ?>"/>				
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Nickname</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('The user\'s nickname, defaults to the user\'s username. ', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[nickname]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['nickname']) ?>"/>				
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Description</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string containing content about the user.', 'pmxi_plugin'); ?>">?</a></p>
								<textarea name="pmui[description]" class="widefat" style="width:100%;margin-bottom:5px;"><?php if (!empty($post['pmui']['description'])) echo esc_html($post['pmui']['description']); ?></textarea>
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>* Login</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string that contains the user\'s username for logging in.', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[login]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['login']) ?>"/>				
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Password</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string that contains the plain text password for the user.', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[pass]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['pass']); ?>"/>				
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Nicename</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string that contains a URL-friendly name for the user. The default is the user\'s username.', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[nicename]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['nicename']); ?>"/>
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>* Email</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string containing the user\'s email address.', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[email]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['email']); ?>"/>
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Registered</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('The date the user registered. Format is Y-m-d H:i:s', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[registered]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['registered']); ?>"/>
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>Display Name</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string that will be shown on the site. Defaults to user\'s username. It is likely that you will want to change this, for both appearance and security through obscurity (that is if you dont use and delete the default admin user). ', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[display_name]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['display_name']); ?>"/>
							</div>
							<div class="input">
								<p style="margin-bottom:5px;"><?php _e('<b>URL</b>', 'pmxi_plugin');?><a class="wpallimport-help" href="#help" original-title="<?php _e('A string containing the user\'s URL for the user\'s web site. ', 'pmxi_plugin'); ?>">?</a></p>
								<input name="pmui[url]" type="text" class="widefat" style="width:100%;margin-bottom:5px;" value="<?php echo esc_attr($post['pmui']['url']); ?>"/>
							</div>					
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>