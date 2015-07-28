<?php

$current_account = isset($_REQUEST['social_facebook_id']) ? (int)$_REQUEST['social_facebook_id'] : false;
$ucm_facebook = new ucm_facebook();
if($current_account !== false){
	$ucm_facebook_account = new ucm_facebook_account($current_account);
	if(isset($_GET['manualrefresh'])){

		if(isset($_REQUEST['refresh_data'])){
			switch($_REQUEST['refresh_data']){
				case 'pages':
					echo "Refreshing available pages from Facebook API: ";
					$ucm_facebook_account->graph_load_available_pages(true);
					break;
				case 'groups':
					echo "Refreshing available groups from Facebook API: ";
					$ucm_facebook_account->graph_load_available_groups(true);
					break;
			}
		}
		if(isset($_REQUEST['facebook_feed'])){
			// update the users personal facebook feed.
			$ucm_facebook_account->load_latest_feed_data(true);
		}
		if(isset($_REQUEST['facebook_page_id'])) {
			$facebook_page_id = isset( $_REQUEST['facebook_page_id'] ) ? (int) $_REQUEST['facebook_page_id'] : 0;
			/* @var $pages ucm_facebook_page[] */
			$pages = $ucm_facebook_account->get( 'pages' );
			if ( ! $facebook_page_id || ! $pages || ! isset( $pages[ $facebook_page_id ] ) ) {
				die( 'No pages found to refresh' );
			}
			?>
			Manually refreshing page data... please wait...
			<?php
			$pages[ $facebook_page_id ]->graph_load_latest_page_data( true );
			$pages[ $facebook_page_id ]->run_cron( true );
		}
		if(isset($_REQUEST['facebook_group_id'])) {

			$facebook_group_id = isset( $_REQUEST['facebook_group_id'] ) ? (int) $_REQUEST['facebook_group_id'] : 0;
			/* @var $groups ucm_facebook_group[] */
			$groups = $ucm_facebook_account->get( 'groups' );
			if ( ! $facebook_group_id || ! $groups || ! isset( $groups[ $facebook_group_id ] ) ) {
				die( 'No groups found to refresh' );
			}
			?>
			Manually refreshing group data... please wait...
			<?php
			$groups[ $facebook_group_id ]->graph_load_latest_group_data( true );
			$groups[ $facebook_group_id ]->run_cron( true );
		}

	}else if(isset($_GET['fbconnect'])){
		// connect to Facebook.
		?>
		<div class="wrap">
			<h2>
				<?php _e( 'Facebook Account', 'simple_social_inbox' ); ?>
			</h2>
		<?php
		if($ucm_facebook_account->get('social_facebook_id') && $ucm_facebook_account->get('social_facebook_id') == $current_account) {
			// check for postback from our UCM facebook code handling script
			if ( isset( $_REQUEST['c'] ) ) {
				$response = isset( $_REQUEST['c'] ) ? @json_decode( $_REQUEST['c'], true ) : false;
				if ( ! $response ) {
					$response = @json_decode( stripslashes( $_REQUEST['c'] ), true );
				}
				//print_r($response);
				if ( ! $response || ! $response['code'] )
					die( 'Failed to get code from API, please press back and try again.' );
				$code = $response['code'];
				// https://graph.facebook.com/oauth/access_token?code=...&client_id=...&redirect_uri=...&machine_id= ...
				$url        = 'https://graph.facebook.com/oauth/access_token?code=' . urlencode( $code ) . '&redirect_uri=' . urlencode( 'http://simplesocialinbox.com/files/api/facebook/logindone.php' ) . '&client_id=589971684431717';
				$machine_id = isset( $response['machine_id'] ) ? $response['machine_id'] : false;
				if ( $machine_id ) {
					$url .= '&machine_id=' . $machine_id;
				}
				$ch = curl_init( $url );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				$data   = curl_exec( $ch );
				$result = @json_decode( $data, true );
				if ( ! $result || ! isset( $result['access_token'] ) ) {
					die( 'Failed to get client access token from API, please press back and try again: ' . $data );
				}
				$machine_id = isset( $result['machine_id'] ) ? $result['machine_id'] : $machine_id;
				// todo - save machine id along with access_token, use in future requests.
				$ucm_facebook_account->update( 'facebook_token', $result['access_token'] );
				$ucm_facebook_account->update( 'machine_id', $machine_id );
				// success!

				// now we load in a list of facebook pages to manage and redirect the user back to the 'edit' screen where they can continue managing the account.
				$ucm_facebook_account->graph_load_available_pages();
				$ucm_facebook_account->graph_load_available_groups();
				$url = $ucm_facebook_account->link_edit();
				?>
				<p>Congratulations! You have successfully linked your Facebook account with the Simple Social plugin. Please click the button below:</p>
				<p><a href="<?php echo $ucm_facebook_account->link_edit(); ?>" class="button">Click here to continue.</a></p>
			<?php
			} else if($ucm_facebook_account->get( 'facebook_app_id' ) && $ucm_facebook_account->get( 'facebook_app_secret' )) {
                // we connect using our own app id / secret

                if(isset($_REQUEST['login_completed']) && !empty($_REQUEST['login_completed'])){
                    // we have logged in, time to test it out!

                    require_once(dirname(_DTBAKER_PLUGIN_FILE_NAME_20_) .'/networks/facebook/facebook.php' );
                    ini_set('display_errors',true);
                    ini_set('error_reporting',E_ALL);
                    $settings = array(
                      'appId'  => $ucm_facebook_account->get( 'facebook_app_id' ),
                      'secret' => $ucm_facebook_account->get( 'facebook_app_secret' ),
                    );

                    $_SESSION['fb_'.$ucm_facebook_account->get( 'facebook_app_id' ).'_access_token'] = $_REQUEST['login_completed'];

                    $facebook = new Facebook($settings);
                    //$access_token = $facebook->getAccessToken();;
                    //echo 'Current access token is: '.$access_token.'<br>';

                    $user = $facebook->getUser();

                    if ($user) {
                      try {
                        $user_profile = $facebook->api('/me');
                      } catch (FacebookApiException $e) {
                        $user = null;
                      }
                    }

                    if ($user) {
                        $facebook->setExtendedAccessToken();
                        //echo 'extending access token...<br>';
                        $access_token = $facebook->getAccessToken();;
                        //echo 'Current access token is: '.$access_token.'<br>';
                        if($access_token){
                            $ucm_facebook_account->update( 'facebook_token', $access_token );
                            // success!

                            // now we load in a list of facebook pages to manage and redirect the user back to the 'edit' screen where they can continue managing the account.
                            $ucm_facebook_account->graph_load_available_pages();
                            $ucm_facebook_account->graph_load_available_groups();
                            $url = $ucm_facebook_account->link_edit();
                            ?>
                            <p>Congratulations! You have successfully linked your Facebook account with the Simple Social plugin through your own Facebook App. Please click the button below:</p>
                            <p><a href="<?php echo $ucm_facebook_account->link_edit(); ?>" class="button">Click here to continue.</a></p>
                            <?php

                       /* $url = 'https://graph.facebook.com/oauth/client_code?access_token='.$access_token.'&redirect_uri='.urlencode( admin_url($ucm_facebook_account->link_connect()) ).'&client_id='.$settings['appId'].'&client_secret='.$settings['secret'].'';
                        $response = wp_remote_get( $url );
                        if ( is_wp_error( $response ) || !$response['body'] ) {
                            $error_message = $response->get_error_message();
                            die("Something went wrong: $error_message");
                        }
                        $result = json_decode($response['body'],true);
                        if(isset($result['code']) && strlen($result['code'])){
                            // same code as above
                            $code = $result['code'];
                            // https://graph.facebook.com/oauth/access_token?code=...&client_id=...&redirect_uri=...&machine_id= ...
                            $url        = 'https://graph.facebook.com/oauth/access_token?code=' . urlencode( $code ) . '&redirect_uri=' . urlencode( admin_url($ucm_facebook_account->link_connect()) ) . '&client_id=' . $ucm_facebook_account->get( 'facebook_app_id' );
                            $machine_id = isset( $result['machine_id'] ) ? $result['machine_id'] : false;
                            if ( $machine_id ) {
                                $url .= '&machine_id=' . $machine_id;
                            }
                            $response = wp_remote_get( $url );
                            if ( is_wp_error( $response ) || !$response['body'] ) {
                                $error_message = $response->get_error_message();
                                die("Something went wrong with oauth/access_token: $error_message");
                            }
                            $result = @json_decode( $response['body'], true );
                            if ( ! $result || ! isset( $result['access_token'] ) ) {
                                die( 'Failed to get client access token from API, please press back and try again: ' . $data );
                            }
                            $machine_id = isset( $result['machine_id'] ) ? $result['machine_id'] : $machine_id;
                            // todo - save machine id along with access_token, use in future requests.
                            $ucm_facebook_account->update( 'facebook_token', $result['access_token'] );
                            $ucm_facebook_account->update( 'machine_id', $machine_id );
                            // success!

                            // now we load in a list of facebook pages to manage and redirect the user back to the 'edit' screen where they can continue managing the account.
                            $ucm_facebook_account->graph_load_available_pages();
                            $ucm_facebook_account->graph_load_available_groups();
                            $url = $ucm_facebook_account->link_edit();
                            ?>
                            <p>Congratulations! You have successfully linked your Facebook account with the Simple Social plugin through your own Facebook App. Please click the button below:</p>
                            <p><a href="<?php echo $ucm_facebook_account->link_edit(); ?>" class="button">Click here to continue.</a></p>
                            <?php  */
                        }else{
                            echo 'Error getting client code from API. Please press back and try again.';
                            print_r($data);
                        }

                      // yay!
                    } else {
                        echo "incorrect permissions from facebook, try again.";
                        exit;
                    }
                }else{
                ?>

                <div id="fb-root"></div>
                <script type="text/javascript">
                    var ucmfacebook = {
                        api_url: '',
                        loaded:  function(){

                            // first thing we do is check the database to see if we have a valid user token.
                            // we use this token along with our app id to see if the user has access via this accout.
                            // this all happens server side without hitting the FB javascript frontend.
                            jQuery('#facebook-login-button').click(function(){
                                ucmfacebook.login_clicked();
                            });


                        },
                        login_clicked: function(){
                            // only load the js client if we need it.
                            var args = {
                                appId: '<?php echo htmlspecialchars($ucm_facebook_account->get( 'facebook_app_id' ));?>', // SS fb app ID
                                status: true,
                                cookie: true
                            };
                            FB.init(args);
                              FB.Event.subscribe('auth.authResponseChange', function(response) {
                                if (response.status === 'connected') {
                                  // user has logged in
                                    // hide the login button and show the connected state.
                                    //alert('connected');
                                     ucmfacebook.loggedin(response);
                                    jQuery('.facebook-login-button').hide();
                                } else if (response.status === 'not_authorized') {
                                  // logged into facebook, but not authorized the app, prompt them to login
                                    //alert('Not auth');
                                  //FB.login();
                                } else {
                                    //alert('Not logged in');
                                  // not logged into facebook at the moment
                                  //FB.login();
                                }
                              });
                            ucmfacebook.do_initial_login();
                        },
                        do_initial_login: function(){
                            FB.login(
                                 function(response) {
                                     if(response.status === 'connected'){
                                         //alert('connected during login!');
                                         ucmfacebook.loggedin(response);
                                     }else if (response.session) {
                                         //alert('logged in');
                                         //alert('got session!');
                                     } else {
                                         console.debug(response);
                                         alert('Login failed. Please try again.');
                                     }
                                 },
                                 { scope: "public_profile, read_stream, read_page_mailboxes, manage_pages, publish_pages, publish_actions, user_location, user_groups, user_photos, user_friends, user_about_me, user_status, user_posts" } //, auth_type: 'reauthenticate'
                             );
                        },
                        loggedin: function(response){
                            console.log(response);
                            if(typeof response.authResponse != 'undefined' && typeof response.authResponse.accessToken != 'undefined'){
                                // valid token! push this to UCM server so we can get an extended access token for use on our client side.
                                /*amble.api('/facebook_token',{token: response.authResponse.accessToken}, function(data){
                                    amble.log('Result from facebook token: ');
                                    amble.log(data);
                                })*/
                                window.location='<?php echo $ucm_facebook_account->link_connect();?>&login_completed='+response.authResponse.accessToken;
                            }
                            FB.api('/me', function(response) {
                                console.log(response);
                            });
                        }
                    };
                    if(typeof FB == 'undefined'){
                        jQuery.getScript('//connect.facebook.net/en_US/all.js', ucmfacebook.loaded);
                    }else{
                        ucmfacebook.loaded();
                    }
                </script>
                <p>Please click the button below to connect your Facebook account:</p>
                <a href="#" id="facebook-login-button"><img src="<?php echo plugins_url('networks/facebook/connect.jpg',_DTBAKER_PLUGIN_FILE_NAME_20_);?>" width="90" height="25" title="Connect to Facebook" border="0"></a>

                <?php
                }
			} else {
                // no app / secret defined, use the default simple social API ones.
				?>
				<iframe
					src="//simplesocialinbox.com/files/api/facebook/login.php?return=<?php echo urlencode( get_admin_url( null, $ucm_facebook_account->link_connect() ) ); ?>"
					frameborder="0" style="width:100%; height:300px; background: transparent"
					ALLOWTRANSPARENCY="true"></iframe>
				<?php
			}
		}
		?> </div> <?php
	}else {
		?>
		<div class="wrap">
			<h2>
				<?php _e( 'Facebook Account', 'simple_social_inbox' ); ?>
			</h2>

			<form action="" method="post">
				<input type="hidden" name="_process" value="save_facebook">
				<input type="hidden" name="social_facebook_id"
				       value="<?php echo (int) $ucm_facebook_account->get( 'social_facebook_id' ); ?>">
				<?php wp_nonce_field( 'save-facebook' . (int) $ucm_facebook_account->get( 'social_facebook_id' ) ); ?>

                <p>Setup Instructions:</p>
                <ul>
                    <li>Go to <a href="https://developers.facebook.com/apps" target="_blank">https://developers.facebook.com/apps</a>  and click Create New App</li>
                    <li>Enter an App Name (e.g. MyBusinessName) and choose the category "Apps for Pages" then click "Create App" and then click "Skip Setup" (top right)</li>
                    <li>Copy the "App ID" and "App Secret" into the boxes below.</li>
                    <li>Click on the Facebook App "Settings" tab and add <?php echo $_SERVER['HTTP_HOST'];?> into the "App Domains" box and enter your email into the "Contact Email" box</li>
                    <li>Then click "Add Platform" and choose "Website" and enter http://<?php echo $_SERVER['HTTP_HOST'];?></li>
                    <li>Then click "Status &amp; Review" and change the app Live status from No to Yes (toggle button at top).</li>
                    <li>Ignore any errors about invalid permissions or submitting the app for review. If you are the Admin of the App and Admin of the Page it will be fine.</li>
                    <li>Please note: newly created accounts might have some issues accessing the API, especially Groups (Facebook is trying to cut down on SPAM). If you are an Administrator on the Facebook App (created above) then it shouldn't be a problem. </li>
                </ul>
				<table class="form-table">
					<tbody>
					<tr>
						<th class="width1">
							<?php _e( 'Account Name', 'simple_social_inbox' ); ?>
						</th>
						<td class="">
							<input type="text" name="facebook_name"
							       value="<?php echo esc_attr( $ucm_facebook_account->get( 'facebook_name' ) ); ?>">

						</td>
					</tr>
                    <tr>
                        <th class="width1">
                            <?php _e( 'Facebook App ID', 'simple_social_inbox' ); ?>
                        </th>
                        <td class="">
                            <input type="text" name="facebook_app_id"
							       value="<?php echo esc_attr( $ucm_facebook_account->get( 'facebook_app_id' ) ); ?>">
                        </td>
                    </tr>
                    <tr>
                        <th class="width1">
                            <?php _e( 'Facebook App Secret', 'simple_social_inbox' ); ?>
                        </th>
                        <td class="">
                            <input type="text" name="facebook_app_secret"
							       value="<?php echo esc_attr( $ucm_facebook_account->get( 'facebook_app_secret' ) ); ?>">
                        </td>
                    </tr>
					<?php if ( $ucm_facebook_account->get( 'social_facebook_id' ) ) { ?>
						<tr>
							<th class="width1">
								<?php _e( 'Last Checked', 'simple_social_inbox' ); ?>
							</th>
							<td class="">
								<?php echo $ucm_facebook_account->get( 'last_checked' ) ? ucm_print_date( $ucm_facebook_account->get( 'last_checked' ), true ) : __( 'N/A', 'simple_social_inbox' ); ?>
							</td>
						</tr>
						<tr>
							<th class="width1">
								<?php _e( 'Personal Account', 'simple_social_inbox' ); ?>
							</th>
							<td class="">
								<input type="checkbox" name="import_personal" value="1" <?php echo $ucm_facebook_account->get( 'import_personal' ) ? ' checked' : ''; ?>>
								Manage Personal Account Feed
								<?php
								if ( $ucm_facebook_account->get( 'import_personal' ) ) {
									echo ' (<a href="' . $ucm_facebook_account->link_refresh() . '" target="_blank">manually re-load feed data</a>)';
								} ?>
							</td>
						</tr>
						<tr>
							<th class="width1">
								<?php _e( 'Available Pages', 'simple_social_inbox' ); ?>
							</th>
							<td class="">
								<input type="hidden" name="save_facebook_pages" value="yep">
								<strong><?php _e( 'Choose which Facebook Pages you would like to manage:', 'simple_social_inbox' ); ?></strong><br>
								<?php
								$data = @json_decode( $ucm_facebook_account->get( 'facebook_data' ), true );
								if ( $data && isset( $data['pages'] ) && is_array( $data['pages'] ) && count( $data['pages'] ) > 0 ) {
									$fb_pages = $ucm_facebook_account->get('pages');
									foreach ( $data['pages'] as $page_id => $page_data ) {
										?>
										<div>
											<input type="checkbox" name="facebook_page[<?php echo $page_id; ?>]"
											       value="1" <?php echo $ucm_facebook_account->is_page_active( $page_id ) ? ' checked' : ''; ?>>
											<?php echo htmlspecialchars( $page_data['name'] ); ?>
											<?php
											if ( $ucm_facebook_account->is_page_active( $page_id ) ) {
												echo ' (<a href="' . $fb_pages[ $page_id ]->link_refresh() . '" target="_blank">manually re-load page data</a>)';
											} ?>
										</div>
									<?php
									}
								} else {
									_e( 'No Facebook Pages Found to Manage', 'simple_social_inbox' );
								}
								echo ' (<a href="' . $ucm_facebook_account->link_refresh_pages() . '" target="_blank">refresh available pages</a>)';

								?>
							</td>
						</tr>
						<tr>
							<th class="width1">
								<?php _e( 'Available Groups', 'simple_social_inbox' ); ?>
							</th>
							<td class="">
								<input type="hidden" name="save_facebook_groups" value="yep">
								<strong><?php _e( 'Choose which Facebook groups you would like to manage:', 'simple_social_inbox' ); ?></strong><br>
								<?php
								$data = @json_decode( $ucm_facebook_account->get( 'facebook_data' ), true );
								if ( $data && isset( $data['groups'] ) && is_array( $data['groups'] ) && count( $data['groups'] ) > 0 ) {
									$fb_groups = $ucm_facebook_account->get('groups');
									foreach ( $data['groups'] as $group_id => $group_data ) {
										?>
										<div>
											<input type="checkbox" name="facebook_group[<?php echo $group_id; ?>]"
											       value="1" <?php echo $ucm_facebook_account->is_group_active( $group_id ) ? ' checked' : ''; ?>>
											<?php echo htmlspecialchars( $group_data['name'] ); ?>
											<?php
											if ( $ucm_facebook_account->is_group_active( $group_id ) ) {
												echo ' (<a href="' . $fb_groups[ $group_id ]->link_refresh() . '" target="_blank">manually re-load group data</a>)';
											} ?>
										</div>
									<?php
									}
								} else {
									_e( 'No Facebook Groups Found to Manage', 'simple_social_inbox' );
								}
								echo ' (<a href="' . $ucm_facebook_account->link_refresh_groups() . '" target="_blank">refresh available groups</a>)';

								?>
							</td>
						</tr>
					<?php } ?>
					</tbody>
				</table>

				<p class="submit">
					<?php if ( $ucm_facebook_account->get( 'social_facebook_id' ) ) { ?>
						<input name="butt_save" type="submit" class="button-primary"
						       value="<?php echo esc_attr( __( 'Save', 'simple_social_inbox' ) ); ?>"/>
						<input name="butt_save_reconnect" type="submit" class="button"
						       value="<?php echo esc_attr( __( 'Re-Connect to Facebook', 'simple_social_inbox' ) ); ?>"/>
						<input name="butt_delete" type="submit" class="button"
						       value="<?php echo esc_attr( __( 'Delete', 'simple_social_inbox' ) ); ?>"
						       onclick="return confirm('<?php _e( 'Really delete this Facebook account and all associated data?', 'simple_social_inbox' ); ?>');"/>
					<?php } else { ?>
						<input name="butt_save_reconnect" type="submit" class="button-primary"
						       value="<?php echo esc_attr( __( 'Save and Connect to Facebook', 'simple_social_inbox' ) ); ?>"/>
					<?php } ?>
				</p>


			</form>
		</div>
	<?php
	}
}else{
	// show account overview:
	$myListTable = new Simple_Social_Account_Data_List_Table();
	$accounts = $ucm_facebook->get_accounts();
	foreach($accounts as $account_id => $account){
		$a = new ucm_facebook_account($account['social_facebook_id']);
		$accounts[$account_id]['edit_link'] = $a->link_edit();
		$accounts[$account_id]['title'] = $a->get('facebook_name');
		$accounts[$account_id]['last_checked'] = $a->get('last_checked') ? ucm_print_date( $a->get('last_checked') ) : 'N/A';
	}
	$myListTable->set_data($accounts);
	$myListTable->prepare_items();
	?>
	<div class="wrap">
		<h2>
			<?php _e('Facebook Accounts','simple_social_inbox');?>
			<a href="?page=<?php echo htmlspecialchars($_GET['page']);?>&social_facebook_id=new" class="add-new-h2"><?php _e('Add New','simple_social_inbox');?></a>
		</h2>
	    <?php
	    //$myListTable->search_box( 'search', 'search_id' );
	     $myListTable->display();
		?>
	</div>
	<?php
}
