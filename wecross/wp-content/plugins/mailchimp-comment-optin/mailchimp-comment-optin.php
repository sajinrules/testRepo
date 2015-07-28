<?php
/*
Plugin Name: MailChimp Comment Optin
Plugin URI: http://wordpress.org/extend/plugins/mailchimp-comment-optin/
Description: This plugin adds a checkbox to your comment form to allow users to optin to one of your MailChimp lists.
Author: Thomas Griffin
Author URI: http://thomasgriffinmedia.com/
Version: 1.2.1
License: GNU General Public License v3.0
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

/*  Copyright 2012  Thomas Griffin  (email : thomas@thomasgriffinmedia.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
*/

if ( ! class_exists( 'MCAPI' ) )
	require_once( plugin_dir_path( __FILE__ ) . 'lib/classes/MCAPI.class.php' );
	
if ( ! class_exists( 'TGM_MailChimp_Comment_Optin' ) ) {
	/**
 	 * Comment form optin class for MailChimp.
 	 *
 	 * Creates a way for users to add a checkbox to their comment forms so
 	 * users can optin to email lists.
 	 *
 	 * @since 1.0.0
 	 *
 	 * @package TGM-MailChimp_Comment_Optin
 	 * @author Thomas Griffin <thomas@thomasgriffinmedia.com>
 	 */
	class TGM_MailChimp_Comment_Optin {
		
		/**
	 	 * The name of the plugin options group.
	 	 *
	 	 * @since 1.0.0
	 	 *
	 	 * @var string
	 	 */
		public $option = 'tgm_mailchimp_comment_settings';
	
		/**
		 * Adds a reference of this object to $instance and hooks in the 
		 * interactions to init.
		 *
		 * Sets the default options for the class.
		 *
		 * @since 1.0.0
		 *
		 * @global array $tgm_mc_options Array of plugin options
		 */
		public function __construct() {
			
			/** Register and update our options */
			global $tgm_mc_options;
			
			$tgm_mc_options = get_option( $this->option );
			if ( false === $tgm_mc_options )
				$tgm_mc_options = $this->default_options();
			
			update_option( $this->option, $tgm_mc_options );
			
			/** Start the class once the rest of WordPress has loaded */
			add_action( 'init', array( &$this, 'init' ), 11 );
		
		}
		
		/**
		 * Initialize the interactions between this class and WordPress.
		 *
		 * @since 1.0.0
		 */
		public function init() {
		
			/** Admin facing actions */
			add_action( 'admin_init', array( &$this, 'handle_api_request' ), 5 );
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'get_ui' ) );
			
			/** Non-admin facing actions */
			add_action( 'comment_form', array( &$this, 'comment_form' ) );
			add_action( 'comment_post', array( &$this, 'populate_list' ) );
			add_filter( 'preprocess_comment', array( &$this, 'save_checkbox_state' ), 1 );
		
		}
		
		/**
		 * Set default options for the class.
		 *
		 * @since 1.0.0
		 */
		public function default_options() {
		
			$defaults = array(
				'apikey' 				=> '',
				'username' 				=> '',
				'user_id' 				=> '',
				'lists' 				=> array(),
				'current_list_id' 		=> '',
				'current_list_web_id' 	=> '',
				'current_list_name' 	=> '',
				'count' 				=> '',
				'show' 					=> 1,
				'check_text' 			=> 'Subscribe me to your mailing list',
				'subscribed_text' 		=> 'You are currently subscribed to our mailing list',
				'pending_text' 			=> 'Your subscription to our mailing list is pending - please check your email to confirm your subscription',
				'admin_text' 			=> 'You are the administrator - no need to subscribe you to the mailing list',
				'clear' 				=> 1,
				'check' 				=> 1
			);
			
			return $defaults;
		
		}
		
		/**
		 * Handles pinging of MailChimp API to get user credentials
		 * for logging in and using their lists. Also handles the 
		 * logout process.
		 *
		 * Stores the data received from the MailChimp API to our option.
		 *
		 * @since 1.0.0
		 *
		 * @global array $tgm_mc_options Array of plugin options
		 * @return null Redirect if successful
		 */
		public function handle_api_request() {
		
			global $tgm_mc_options;
			
			/** Handle the API key check and request */
			if ( isset( $_POST[sanitize_key( 'tgm_mc_action' )] ) && 'get_mc_api_key' == $_POST[sanitize_key( 'tgm_mc_action' )] ) {
				check_admin_referer( 'tgm_mc_api_ping' );
				
				/** Ping the MailChimp API to make sure this API Key is valid */
				$api_key = strip_tags( stripslashes( $_POST[sanitize_key( 'tgm_mc_get_api_key' )] ) );
				$api = new MCAPI( $api_key );
				$api->ping();
				
				/** Get necessary data and store it into our options field */
				if ( ! empty( $api->errorCode ) ) {
					/** Looks like there was an error */
					$message = sprintf( __( 'Sorry, but MailChimp was unable to verify your API key. MailChimp gave this response: <p><em>%s</em></p> Please try entering your API key again.', 'tgm-mc-optin' ), $api->errorMessage );
					add_settings_error( 'tgm-mc-optin', 'apikey-fail', $message, 'error' );
				}
				else {
					/** We have successfully connected to the MailChimp API, so we know this API key is good */
					$tgm_mc_options['apikey'] = $api_key;
					
					/** Get an array of account data to store */
					$creds = $api->getAccountDetails();

					$tgm_mc_options['username'] = $creds['username'];
					$tgm_mc_options['user_id'] = $creds['user_id'];
					
					/** Support up to 100 lists (but most users won't have nearly that many */
					$lists = $api->lists( array(), 0, 100 );
					
					/** Store the total number of lists for updating purposes */
					$tgm_mc_options['count'] = $lists['total'];
					
					/** Grab all lists, loop through them and store the necessary data */
					$lists = $lists['data'];
					$i = 0;	
					foreach ( $lists as $list ) {
						$tgm_mc_options['lists'][$i]['id'] = $list['id'];
						$tgm_mc_options['lists'][$i]['web_id'] = $list['web_id'];
						$tgm_mc_options['lists'][$i]['name'] = $list['name'];
						$i++;
					}
					
					/** Save all of our new data */
					update_option( $this->option, $tgm_mc_options );
					
					wp_redirect( add_query_arg( array( 'page' => 'tgm-mailchimp-comment-settings' ), admin_url( 'options-general.php' ) ) );
					exit;
				}
			}
			
			/** Handle the logout request */
			if ( isset( $_GET[sanitize_key( 'tgm_mc_action' )] ) && 'logout' == $_GET[sanitize_key( 'tgm_mc_action' )] ) {
				check_admin_referer( 'tgm_mc_logout', 'tgm_mc_logout_nonce' );
				
				/** Empty out the options and set them back to default */
				update_option( $this->option, $this->default_options() );
				
				wp_redirect( add_query_arg( array( 'page' => 'tgm-mailchimp-comment-settings' ), admin_url( 'options-general.php' ) ) );
				exit;
			}
		
		}
		
		/**
		 * Register our plugin option group, name and sanitization method.
		 *
		 * @since 1.0.0
		 */
		public function admin_init() {
			
			register_setting( $this->option, $this->option, array( &$this, 'sanitize_options' ) );
			require plugin_dir_path( __FILE__ ) . 'lib/utils.php';
		
		}
		
		/**
		 * Creates the plugin settings page.
		 *
		 * @since 1.0.0
		 */
		public function admin_menu() {
		
			add_options_page( __( 'MailChimp Comment Optin Settings', 'tgm-mc-optin' ), __( 'MC Comment Optin', 'tgm-mc-optin' ), 'manage_options', 'tgm-mailchimp-comment-settings', array( &$this, 'settings_page' ) );
		
		}
		
		/**
		 * Outputs the plugin settings page.
		 *
		 * @since 1.0.0
		 */
		public function settings_page() {
		
			global $tgm_mc_options;
			
			?>
			<div class="tgm-mc-settings wrap">
				<?php screen_icon( 'options-general' ); ?>
				<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
				
				<div class="tgm-mc-content">
					<?php if ( empty( $tgm_mc_options['apikey'] ) ) { ?>
						<form id="tgm-mc-login" action="" method="post">
							<input type="hidden" name="tgm_mc_action" value="get_mc_api_key" />
							<?php $this->get_api_creds(); ?>
							<?php wp_nonce_field( 'tgm_mc_api_ping' ); ?>
						</form>
					<?php } else { ?>
						<form id="tgm-mc-options" action="options.php" method="post">
							<?php settings_fields( $this->option ); ?>
							<?php $this->get_form_options(); ?>
						</form>
					<?php } ?>
				</div>
			</div>
			<?php
		
		}
		
		/**
		 * If the users API key is not set, we output this form in order to get it
		 * and move forward.
		 *
		 * @since 1.0.0
		 *
		 * @global array $tgm_mc_options Array of plugin options
		 */
		public function get_api_creds() {
		
			global $tgm_mc_options;
			
			?>
			<div class="tgm-mc-header">
				<div class="content">
					<p><?php _e( 'You must enter a valid MailChimp API key before you can proceed.', 'tgm-mc-optin' ); ?></p>
					<p><a href="http://admin.mailchimp.com/account/api-key-popup" class="button-secondary get-auth" target="_tab"><?php _e( 'Click Here to Get an API Key', 'tgm-mc-optin' ); ?></a></p>
				</div>
			</div>
			<div class="tgm-mc-table">
				<div class="content">
					<table class="form-table">
						<tbody>
							<tr valign="middle">
								<th scope="row">
									<label><?php echo esc_attr__( 'MailChimp API Key', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-apikey" type="text" name="tgm_mc_get_api_key" size="50" value="" />
								</td>
							</tr>
						</tbody>
					</table>
					<?php 
						submit_button(
							__( 'Login to Your MailChimp Account', 'tgm-mc-optin' ), // Button text
							'secondary', // Button class
							'tgm_mc_login', // Input name
							true // Wrap in <p> tags
						);
					?>
				</div>
			</div>
			<?php
		
		}
		
		/**
		 * If the users API key is set, we can output all of our options
		 * for the plugin to be customized.
		 *
		 * @since 1.0.0
		 *
		 * @global array $tgm_mc_options Array of plugin options
		 */
		public function get_form_options() {
		
			global $tgm_mc_options;
			
			/** Quick check to make sure that our API key is set */
			if ( empty( $tgm_mc_options['apikey'] ) )
				return;
				
			?>
			<div class="tgm-mc-header">
				<div class="content">
					<?php printf( __( '<p>%1$s <strong>%2$s</strong> <a href="%3$s" class="tgm-mc-logout button-secondary">Logout</a></p>', 'tgm-mc-optin' ), 'You are currently logged in as: ', $tgm_mc_options['username'], add_query_arg( array( 'page' => 'tgm-mailchimp-comment-settings', 'tgm_mc_action' => 'logout', 'tgm_mc_logout_nonce' => wp_create_nonce( 'tgm_mc_logout' ) ), admin_url( 'options-general.php' ) ) ); ?>
				</div>
			</div>
			<div class="tgm-mc-table">
				<div class="content">
					<table class="form-table">
						<tbody>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[current_list_name]"><?php _e( 'Select the list that commenters should subscribe to', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<?php
										$values = array();
										
										foreach ( $tgm_mc_options['lists'] as $lists )
											$values[] = implode( ',', $lists );
											
										echo '<select id="tgm-mc-lists" name="' . $this->option . '[current_list_name]">';
											echo '<option value=",,,"></option>';
											foreach ( $values as $set ) {
												$data = explode( ',', $set );
												$selected = ( $data[2] == $tgm_mc_options['current_list_name'] ) ? 'selected="selected"' : '';
												echo '<option value="' . $set . '"' . $selected . '>' . $data[2] . '</option>';
											}
										echo '</select>';
									?>
								</td>
							</tr>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[show]"><?php _e( 'Display the checkbox after the comment form?', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-show" type="checkbox" name="<?php echo $this->option; ?>[show]" value="<?php echo $tgm_mc_options['show']; ?>" <?php checked( $tgm_mc_options['show'], 1 ); ?> />
								</td>
							</tr>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[check_text]"><?php _e( 'Customize the checkbox message', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-check-text" type="text" name="<?php echo $this->option; ?>[check_text]" size="55" value="<?php echo $tgm_mc_options['check_text']; ?>" />
								</td>
							</tr>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[subscribed_text]"><?php _e( 'Customize the already subscribed message', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-subscribed-text" type="text" name="<?php echo $this->option; ?>[subscribed_text]" size="55" value="<?php echo $tgm_mc_options['subscribed_text']; ?>" />
								</td>
							</tr>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[pending_text]"><?php _e( 'Customize the pending subscriber message', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-pending-text" type="text" name="<?php echo $this->option; ?>[pending_text]" size="55" value="<?php echo $tgm_mc_options['pending_text']; ?>" />
								</td>
							</tr>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[admin_text]"><?php _e( 'Customize the admin logged in message', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-admin-text" type="text" name="<?php echo $this->option; ?>[admin_text]" size="55" value="<?php echo $tgm_mc_options['admin_text']; ?>" />
								</td>
							</tr>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[clear]"><?php _e( 'Add a CSS \'clear\' to the checkbox?', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-clear" type="checkbox" name="<?php echo $this->option; ?>[clear]" value="<?php echo $tgm_mc_options['clear']; ?>" <?php checked( $tgm_mc_options['clear'], 1 ); ?> />
									<span class="description"><?php _e( 'Uncheck if this causes layout issues', 'tgm-mc-optin' ); ?></span>
								</td>
							</tr>
							<tr valign="middle">
								<th scope="row">
									<label for="<?php echo $this->option; ?>[check]"><?php _e( 'Check for pending or active subscribers?', 'tgm-mc-optin' ); ?></label>
								</th>
								<td>
									<input id="tgm-mc-check" type="checkbox" name="<?php echo $this->option; ?>[check]" value="<?php echo $tgm_mc_options['check']; ?>" <?php checked( $tgm_mc_options['check'], 1 ); ?> />
									<span class="description"><?php _e( 'When checked, this plugin queries your active list for the email address submitted to return the appropriate string and data. Large email lists may consider unchecking this option if performance issues arise.', 'tgm-mc-optin' ); ?></span>
								</td>
							</tr>
						</tbody>
					</table>
					<?php 
						submit_button(
							__( 'Save Changes', 'tgm-mc-optin' ), // Button text
							'secondary', // Button class
							'tgm_mc_save_options', // Input name
							true // Wrap in <p> tags
						);
					?>
				</div>
			</div>
			<?php
		
		}
		
		/**
		 * Enqueue styles for the plugin page.
		 *
		 * @since 1.0.0
		 *
		 * @global object $current_screen Data associated with the current page
		 */
		public function get_ui() {
		
			global $current_screen;
			
			if ( 'settings_page_tgm-mailchimp-comment-settings' == $current_screen->id ) {
				wp_register_style( 'tgm-mc-style', plugin_dir_url( __FILE__ ) . 'lib/css/admin.css', array(), '1.0.0' );
				wp_enqueue_style( 'tgm-mc-style' );
			}
		
		}
		
		/**
		 * Sanitizes $_POST inputs from the user before being stored 
		 * in the database.
		 *
		 * @since 1.0.0
		 *
		 * @global array $tgm_mc_options Array of plugin options
		 * @param array $input The array of $_POST inputs
		 * @return array $tgm_mc_options Amended array of plugin options
		 */
		public function sanitize_options( $input ) {
			
			global $tgm_mc_options;
			
			$current_list_data = explode( ',', $input['current_list_name'] );
			$tgm_mc_options['current_list_id'] = esc_attr( $current_list_data[0] );
			$tgm_mc_options['current_list_web_id'] = esc_attr( $current_list_data[1] );
			$tgm_mc_options['current_list_name'] = esc_attr( $current_list_data[2] );
			
			$tgm_mc_options['show'] = isset( $input['show'] ) ? (int) 1 : (int) 0;
			
			$tgm_mc_options['check_text'] = esc_attr( strip_tags( $input['check_text'] ) );
			$tgm_mc_options['subscribed_text'] = esc_attr( strip_tags( $input['subscribed_text'] ) );
			$tgm_mc_options['pending_text'] = esc_attr( strip_tags( $input['pending_text'] ) );
			$tgm_mc_options['admin_text'] = esc_attr( strip_tags( $input['admin_text'] ) );
			
			$tgm_mc_options['clear'] = isset( $input['clear'] ) ? (int) 1 : (int) 0;
			$tgm_mc_options['check'] = isset( $input['check'] ) ? (int) 1 : (int) 0;
			
			return $tgm_mc_options;
		
		}
		
		/**
		 * Outputs the checkbox area below the comment form that allows
		 * commenters to subscribe to the email list.
		 *
		 * @since 1.0.0
		 *
		 * @global array $tgm_mc_options Array of plugin options
		 * @return null Return early if in the admin or the email list hasn't been set
		 */
		public function comment_form() {
		
			global $tgm_mc_options;
			
			/** Don't do anything if we are in the admin */
			if ( is_admin() )
				return;
			
			/** Don't do anything if the user has turned off the feature */
			if ( ! $tgm_mc_options['show'] )
				return;
			
			/** Don't do anything unless the user has already logged in and selected a list */
			if ( empty( $tgm_mc_options['current_list_id'] ) )
				return;
			
			$clear = $tgm_mc_options['clear'] ? 'style="clear: both;"' : '';
			$checked_status = ( ! empty( $_COOKIE['tgm_mc_checkbox_' . COOKIEHASH] ) && 'checked' == $_COOKIE['tgm_mc_checkbox_' . COOKIEHASH] ) ? true : false;
			$checked = $checked_status ? 'checked="checked"' : '';
			$status = $this->get_viewer_status();
			
			if ( 'admin' == $status ) {
				echo '<p class="tgm-mc-subscribe" ' . $clear . '>' . $tgm_mc_options['admin_text'] . '</p>';
			}
			elseif ( 'subscribed' == $status ) {
				echo '<p class="tgm-mc-subscribe" ' . $clear . '>' . $tgm_mc_options['subscribed_text'] . '</p>';
			}
			elseif ( 'pending' == $status ) {
				echo '<p class="tgm-mc-subscribe" ' . $clear . '>' . $tgm_mc_options['pending_text'] . '</p>';
			}
			else {
				echo '<p class="tgm-mc-subscribe" ' . $clear . '>';
					echo '<input type="checkbox" name="tgm_mc_get_subscribed" id="tgm-mc-get-subscribed" value="subscribe" style="width: auto;" ' . $checked . ' />';
					echo '<label for="tgm_mc_get_subscribed"> ' . $tgm_mc_options['check_text'] . '</label>';
				echo '</p>';
			}	
		
		}
		
		/**
		 * Gets and returns the current viewer's name.
		 *
		 * @since 1.0.0
		 *
		 * @return string|boolean Commenter name on success, false on failure
		 */
		public function get_viewer_name() {
			
			global $tgm_mc_comment_data;
			
			/** Grab the current user's info if available */
			get_currentuserinfo();
			
			/** Get the commenter email from cookies if available */
			if ( ! empty( $tgm_mc_comment_data['comment_author_name'] ) )
				$commenter_name = $tgm_mc_comment_data['comment_author_name'];
			elseif ( ! empty( $_COOKIE[sanitize_key( 'comment_author_' . COOKIEHASH )] ) )
				$commenter_name = trim( $_COOKIE[sanitize_key( 'comment_author_' . COOKIEHASH )] );
			
			if ( empty( $commenter_name ) )
				return false;
			
			return $commenter_name;
		
		}
		
		/**
		 * Gets and returns the current viewer's email.
		 *
		 * This is a shortened version of the get_viewer_status() method.
		 *
		 * @since 1.0.0
		 *
		 * @global int $post The current post ID
		 * @global string $user_email The email of the current user if logged in
		 * @global array $tgm_mc_options Array of plugin options
		 * @return string|boolean Email string on success, false on failure
		 */
		public function get_viewer_email() {
		
			global $post, $user_email, $tgm_mc_options, $tgm_mc_comment_data;
			
			/** Grab the current user's info if available */
			get_currentuserinfo();
			
			/** Get the commenter email from cookies if available */
			if ( ! empty( $tgm_mc_comment_data['comment_author_email'] ) )
				$commenter_email = $tgm_mc_comment_data['comment_author_email'];
			elseif ( ! empty( $_COOKIE[sanitize_key( 'comment_author_email_' . COOKIEHASH )] ) )
				$commenter_email = trim( $_COOKIE[sanitize_key( 'comment_author_email_' . COOKIEHASH )] );
	
			if ( is_email( $user_email ) )
				$email = strtolower( $user_email );
			elseif ( is_email( $commenter_email ) )
				$email = strtolower( $commenter_email );
			else
				return false;
			
			return $email;
		
		}
		
		/**
		 * Determines the current state of a commenter. Sets whether or not he/she is
		 * subscribed or not, an admin or the author of the post.
		 *
		 * Modified from the 'Subscribe To Comments' plugin by Mark Jaquith.
		 *
		 * @since 1.0.0
		 *
		 * @global int $post The current post ID
		 * @global string $user_email The email of the current user if logged in
		 * @global array $tgm_mc_options Array of plugin options
		 * @return string|boolean Admin or email string on success, false on failure
		 */
		public function get_viewer_status() {
		
			global $post, $user_email, $tgm_mc_options;
			
			/** Grab the current user's info if available */
			get_currentuserinfo();
			
			/** Get the commenter email from cookies if available */
			$commenter_email = ! empty( $_COOKIE[sanitize_key( 'comment_author_email_' . COOKIEHASH )] ) ? trim( $_COOKIE[sanitize_key( 'comment_author_email_' . COOKIEHASH )] ) : '';
			$loggedin = false;
			
			if ( is_email( $user_email ) ) {
				$email = strtolower( $user_email );
				$loggedin = true;
			}
			elseif ( is_email( $commenter_email ) ) {
				$email = strtolower( $commenter_email );
			}
			else {
				return false;
			}
				
			$author = get_userdata( $post->post_author );
			
			if ( $email == strtolower( $author->user_email ) && $loggedin )
				return 'admin';
			
			/** Return early if the user has selected to skip checking for previous subscribers */
			if ( ! $tgm_mc_options['check'] )
				return false;
				
			/** Connect to MailChimp and see if the email is already stored for the list */	
			$api = new MCAPI( $tgm_mc_options['apikey'] );
				
			$check_for_email = $api->listMemberInfo( $tgm_mc_options['current_list_id'], array( $email ) );
				
			/** If there are any errors, we know they are not on the list */
			if ( 0 !== $check_for_email['errors'] )
				return false;
			elseif ( 'pending' == $check_for_email['data'][0]['status'] )
				return 'pending';
			else
				return 'subscribed';
				
			return false;
		
		}
		
		/**
		 * Sends the email and (optionally) first name of the commenter to the 
		 * current MailChimp list.
		 *
		 * @since 1.0.0
		 *
		 * @global array $tgm_mc_options Array of plugin options
		 * @global array $tgm_mc_comment_data Array of submitted comment data
		 */
		public function populate_list() {
		
			global $tgm_mc_options, $tgm_mc_comment_data;
			
			/** Only go forward if the checkbox has been selected and the user isn't subscribed */
			if ( ! empty( $tgm_mc_comment_data['tgm_mc_subscribe'] ) && 'yes' == $tgm_mc_comment_data['tgm_mc_subscribe'] || ! empty( $_COOKIE['tgm_mc_checkbox_' . COOKIEHASH] ) && 'checked' == $_COOKIE['tgm_mc_checkbox_' . COOKIEHASH] ) {
				$name = $this->get_viewer_name();
				$email = $this->get_viewer_email();
			
				$api = new MCAPI( $tgm_mc_options['apikey'] );
				$merge_vars = array( 'FNAME' => $name );
				
				$api->listSubscribe( $tgm_mc_options['current_list_id'], $email, $merge_vars );
			}
		
		}
		
		/**
		 * Sets a cookie to determine the current state of the checkbox for the 
		 * email list.
		 *
		 * Also ammends the commentdata to set the checked state as an argument.
		 *
		 * @since 1.0.0
		 * 
		 * @global array $tgm_comment_data Matches submitted comment data
		 * @param array $commentdata Submitted comment data from the user
		 * @return array $commentdata Amended comment data
		 */
		public function save_checkbox_state( $commentdata ) {
		
			global $tgm_mc_comment_data;
			
			/** Set the global variable equal to the current comment information */
			$tgm_mc_comment_data = (array) $commentdata;
			
			/** If our checkbox has been checked, set a cookie with the value of 'checked', else 'unchecked' */
			if ( isset( $_POST[sanitize_key( 'tgm_mc_get_subscribed' )] ) ) {
				$tgm_mc_comment_data['tgm_mc_subscribe'] = 'yes';
				setcookie( 'tgm_mc_checkbox_' . COOKIEHASH, 'checked', time() + 30000000, COOKIEPATH );
			}
			else {
				$tgm_mc_comment_data['tgm_mc_subscribe'] = 'no';
				setcookie( 'tgm_mc_checkbox_' . COOKIEHASH, 'unchecked', time() + 30000000, COOKIEPATH );
			}

			/** Return our amended array of args */
			return $tgm_mc_comment_data;
		
		}
	
	}
}

/** Instantiate the class */
$tgm_mc_comment_optin = new TGM_MailChimp_Comment_Optin;