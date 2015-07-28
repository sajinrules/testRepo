<?php

$current_account = isset($_REQUEST['social_twitter_id']) ? (int)$_REQUEST['social_twitter_id'] : false;
$ucm_twitter = new ucm_twitter();
if($current_account !== false){
	$ucm_twitter_account = new ucm_twitter_account($current_account);
	if(isset($_GET['do_twitter_refresh'])) {

		?>
		<div class="wrap">
			<h2>
				<?php _e( 'Twitter Account', 'simple_social_inbox' ); ?>
			</h2>
		Manually refreshing page data...
		<?php
		$ucm_twitter_account->import_data(true);
		$ucm_twitter_account->run_cron(true);
		?>
		</div>
		<?php

	}else if(isset($_GET['do_twitter_connect'])){

		include('twitter_connect.php');

	}else {
		?>
		<div class="wrap">
			<h2>
				<?php _e( 'Twitter Account', 'simple_social_inbox' ); ?>
			</h2>

			<form action="" method="post">
				<input type="hidden" name="_process" value="save_twitter">
				<input type="hidden" name="social_twitter_id"
				       value="<?php echo (int) $ucm_twitter_account->get( 'social_twitter_id' ); ?>">
				<?php wp_nonce_field( 'save-twitter' . (int) $ucm_twitter_account->get( 'social_twitter_id' ) ); ?>

				<?php 
				$fieldset_data = array(
				    'class' => 'tableclass tableclass_form tableclass_full',
				    'elements' => array(
				        array(
				            'title' => __('Account Name', 'simple_social_inbox'),
				            'field' => array(
				                'type' => 'text',
					            'name' => 'account_name',
					            'value' => $ucm_twitter_account->get('account_name'),
					            'help' => 'Choose a name for this account. This name will be shown here in the system.',
				            ),
				        ),
				    )
				);
				// check if this is active, if not prmopt the user to re-connect.
				if($ucm_twitter_account->is_active()){
					$fieldset_data['elements'][] = array(
						'title' => __('Last Checked', 'simple_social_inbox'),
				            'fields' => array(
				                ucm_print_date($ucm_twitter_account->get('last_checked'),true),
					            '(<a href="'.$ucm_twitter_account->link_refresh().'" target="_blank">'.__('Refresh', 'simple_social_inbox').'</a>)',
				            ),
				        );
					$fieldset_data['elements'][] = array(
						'title' => __('Twitter Name', 'simple_social_inbox'),
				            'fields' => array(
				                htmlspecialchars($ucm_twitter_account->get('twitter_name')),
				            ),
				        );
					$fieldset_data['elements'][] = array(
						'title' => __('Twitter ID', 'simple_social_inbox'),
				            'fields' => array(
				                htmlspecialchars($ucm_twitter_account->get('twitter_id')),
				            ),
				        );
					$fieldset_data['elements'][] = array(
						'title' => __('Import DM\'s', 'simple_social_inbox'),
				            'fields' => array(
				                array(
					                'type' => 'checkbox',
					                'value' => $ucm_twitter_account->get('import_dm'),
					                'name' => 'import_dm',
					                'help' => 'Enable this to import Direct Messages from this twitter account',
				                )
				            ),
				        );
					$fieldset_data['elements'][] = array(
						'title' => __('Import Mentions', 'simple_social_inbox'),
				            'fields' => array(
				                array(
					                'type' => 'checkbox',
					                'value' => $ucm_twitter_account->get('import_mentions'),
					                'name' => 'import_mentions',
					                'help' => 'Enable this to import any tweets that mention your name',
				                )
				            ),
				        );
					$fieldset_data['elements'][] = array(
						'title' => __('Import Tweets', 'simple_social_inbox'),
				            'fields' => array(
				                array(
					                'type' => 'checkbox',
					                'name' => 'import_tweets',
					                'value' => $ucm_twitter_account->get('import_tweets'),
					                'help' => 'Enable this to import any tweets that originated from this account',
				                )
				            ),
				        );
			
				}else{
			
				}
				echo module_form::generate_fieldset($fieldset_data);
				?>

				<p class="submit">
					<?php if ( $ucm_twitter_account->get( 'social_twitter_id' ) ) { ?>
						<input name="butt_save" type="submit" class="button-primary"
						       value="<?php echo esc_attr( __( 'Save', 'simple_social_inbox' ) ); ?>"/>
						<input name="butt_save_reconnect" type="submit" class="button"
						       value="<?php echo esc_attr( __( 'Re-Connect to Twitter', 'simple_social_inbox' ) ); ?>"/>
						<input name="butt_delete" type="submit" class="button"
						       value="<?php echo esc_attr( __( 'Delete', 'simple_social_inbox' ) ); ?>"
						       onclick="return confirm('<?php _e( 'Really delete this Twitter account and all associated data?', 'simple_social_inbox' ); ?>');"/>
					<?php } else { ?>
						<input name="butt_save_reconnect" type="submit" class="button-primary"
						       value="<?php echo esc_attr( __( 'Save and Connect to Twitter', 'simple_social_inbox' ) ); ?>"/>
					<?php } ?>
				</p>


			</form>
		</div>
	<?php
	}
}else{
	// show account overview:
	$myListTable = new Simple_Social_Account_Data_List_Table();
	$accounts = $ucm_twitter->get_accounts();
	foreach($accounts as $account_id => $account){
		$a = new ucm_twitter_account($account['social_twitter_id']);
		$accounts[$account_id]['edit_link'] = $a->link_edit();
		$accounts[$account_id]['title'] = $a->get('account_name');
		$accounts[$account_id]['last_checked'] = $a->get('last_checked') ? ucm_print_date( $a->get('last_checked') ) : 'N/A';
	}
	$myListTable->set_data($accounts);
	$myListTable->prepare_items();
	?>
	<div class="wrap">
		<h2>
			<?php _e('Twitter Accounts','simple_social_inbox');?>
			<a href="?page=<?php echo htmlspecialchars($_GET['page']);?>&social_twitter_id=new" class="add-new-h2"><?php _e('Add New','simple_social_inbox');?></a>
		</h2>
	    <?php
	    //$myListTable->search_box( 'search', 'search_id' );
	     $myListTable->display();
		?>
		<hr>
		<h2>
			<?php _e('Twitter App Settings','simple_social_inbox');?>
		</h2>
		<p>Please go to <a href="https://apps.twitter.com/" target="_blank">https://apps.twitter.com/</a> and sign in using your Twitter account. Then click the Create New App button. Enter a Name, Description, Website (and in the Callback URL just put your website address again). Once created, go to Permissions and choose "Read, write, and direct messages" then go to API Keys and copy your API Key and API Secret from here into the below form.</p>
		<form action="" method="post">
				<input type="hidden" name="_process" value="save_twitter_settings">
				<?php wp_nonce_field( 'save-twitter-settings' ); ?>

				<table class="form-table">
					<tbody>
					<tr>
						<th class="width1">
							<?php _e( 'App API Key', 'simple_social_inbox' ); ?>
						</th>
						<td class="">
							<input type="text" name="twitter_app_api_key" value="<?php echo esc_attr( $ucm_twitter->get('api_key') ); ?>">
						</td>
					</tr>
					<tr>
						<th class="width1">
							<?php _e( 'App API Secret', 'simple_social_inbox' ); ?>
						</th>
						<td class="">
							<input type="text" name="twitter_app_api_secret" value="<?php echo esc_attr( $ucm_twitter->get('api_secret') ); ?>">
						</td>
					</tr>
					</tbody>
				</table>

				<p class="submit">
					<input name="butt_save" type="submit" class="button-primary" value="<?php echo esc_attr( __( 'Save', 'simple_social_inbox' ) ); ?>"/>
				</p>


			</form>
	</div>
	<?php
}
