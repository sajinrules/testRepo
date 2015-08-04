<?php
/**
 * Logic related to displaying accounts page.
 *
 * @author   Actuality Extensions
 * @package  WooCommerce_Customer_Relationship_Manager
 * @since    1.0
 */

if ( !defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WC_Crm_Accounts Class
 */
class WC_Crm_Accounts {

	private static $saved_meta_boxes = false;

	/**
	 * Billing fields
	 *
	 * @var array
	 */
	protected static $billing_fields = array();

	/**
	 * Shipping fields
	 *
	 * @var array
	 */
	protected static $shipping_fields = array();

	protected static $general_fields = array();

	/**
	 * Init billing and shipping fields we display + save
	 */
	public static function init_fields() {

		self::$billing_fields = apply_filters( 'wc_crm_admin_accounts_billing_fields', array(
			'first_name' => array(
				'label' => __( 'First Name', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'last_name' => array(
				'label' => __( 'Last Name', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'company' => array(
				'label' => __( 'Company', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'address_1' => array(
				'label' => __( 'Address 1', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'address_2' => array(
				'label' => __( 'Address 2', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'city' => array(
				'label' => __( 'City', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'postcode' => array(
				'label' => __( 'Postcode', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'country' => array(
				'label'   => __( 'Country', 'wc_customer_relationship_manager' ),
				'show'    => false,
				'class'   => 'js_field-country select short',
				'type'    => 'select',
				'options' => array( '' => __( 'Select a country&hellip;', 'wc_customer_relationship_manager' ) ) + WC()->countries->get_allowed_countries()
			),
			'state' => array(
				'label' => __( 'State/County', 'wc_customer_relationship_manager' ),
				'class'   => 'js_field-state select short',
				'show'  => false
			),
		) );

		self::$shipping_fields = apply_filters( 'wc_crm_admin_accounts_shipping_fields', array(
			'first_name' => array(
				'label' => __( 'First Name', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'last_name' => array(
				'label' => __( 'Last Name', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'company' => array(
				'label' => __( 'Company', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'address_1' => array(
				'label' => __( 'Address 1', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'address_2' => array(
				'label' => __( 'Address 2', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'city' => array(
				'label' => __( 'City', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'postcode' => array(
				'label' => __( 'Postcode', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'country' => array(
				'label'   => __( 'Country', 'wc_customer_relationship_manager' ),
				'show'    => false,
				'type'    => 'select',
				'class'   => 'js_field-country select short',
				'options' => array( '' => __( 'Select a country&hellip;', 'wc_customer_relationship_manager' ) ) + WC()->countries->get_shipping_countries()
			),
			'state' => array(
				'label' => __( 'State/County', 'wc_customer_relationship_manager' ),
				'class'   => 'js_field-state select short',
				'show'  => false
			),
		) );
		
		self::$general_fields = apply_filters( 'wc_crm_admin_accounts_general_fields', array(
			'phone' => array(
				'label' => __( 'Phone', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'fax' => array(
				'label' => __( 'Fax', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'website' => array(
				'label' => __( 'Website', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'ticker_symbol' => array(
				'label' => __( 'Ticker symbol', 'wc_customer_relationship_manager' ),
				'show'  => false
			),
			'account_type' => array(
				'label' => __( 'Account Type', 'wc_customer_relationship_manager' ),
				'show'  => false,
				'type'    => 'select',
				'class'   => 'select form-field-wide',
				'options' => array( 
					'analyst' => __( 'Analyst', 'wc_customer_relationship_manager'),
					'competitor' => __( 'Competitor', 'wc_customer_relationship_manager'),
					'customer' => __( 'Customer', 'wc_customer_relationship_manager'),
					'distributor' => __( 'Distributor', 'wc_customer_relationship_manager'),
					'integrator' => __( 'Integrator', 'wc_customer_relationship_manager'),
					'investor' => __( 'Investor', 'wc_customer_relationship_manager'),
					'other' => __( 'Other', 'wc_customer_relationship_manager'),
					'partner' => __( 'Partner', 'wc_customer_relationship_manager'),
					'press' => __( 'Press', 'wc_customer_relationship_manager'),
					'prospect' => __( 'Prospect', 'wc_customer_relationship_manager'),
					'reseller' => __( 'Reseller', 'wc_customer_relationship_manager'),
					'supplier' => __( 'Supplier', 'wc_customer_relationship_manager'),
					'vendor' => __( 'Vendor', 'wc_customer_relationship_manager')
					 )
			),
			'ownership' => array(
				'label' => __( 'Ownership', 'wc_customer_relationship_manager' ),
				'show'  => false,
				'type'    => 'select',
				'class'   => 'select',
				'options' => array(
					'other'      => __( 'Other', 'wc_customer_relationship_manager'),
					'private'    => __( 'Private', 'wc_customer_relationship_manager' ),
					'public'     => __( 'Public', 'wc_customer_relationship_manager' ) ,
					'subsidiary' => __( 'Subsidiary', 'wc_customer_relationship_manager' ) ,
				)
			),
			'industry' => array(
				'label' => __( 'Industry', 'wc_customer_relationship_manager' ),
				'show'  => false,
				'type'    => 'select',
				'class'   => 'wc-enhanced-select',
				'custom_attributes' => array(
					'data-allow_clear' => true,
					'data-placeholder' => __( 'Select an Industry', 'wc_customer_relationship_manager' ),
					),
				'options' => array("" => "") + wc_crm_get_industries()
			),
			'employees' => array(
				'label' => __( 'Employees', 'wc_customer_relationship_manager' ),
				'show'  => false,
				'type'    => 'number',
				'custom_attributes' => array(
					'step' => 1,
					'min'  => 0,
					),
			),
			'annual_revenue' => array(
				'label' => __( 'Annual Revenue', 'wc_customer_relationship_manager' ),
				'show'  => false,
				'type'    => 'number',
				'custom_attributes' => array(
					'step' => '0.01',
					'min'  => 0,
					),
			),
			'sic_code' => array(
				'label' => __( 'SIC code', 'wc_customer_relationship_manager' ),
				'show'  => false,
				'type'    => 'number',
				'custom_attributes' => array(
					'step' => '0.01',
					'min'  => 0,
					),
			),
		) );
	}


	public function output($post)
	{
		$account_id = isset($_GET['post']) && !empty($_GET['post']) ? $_GET['post'] : 0;
		self::init_fields();
		wp_nonce_field( 'wc_crm_save_data', 'wc_crm_meta_nonce' );
		?>
		<style>
		#post-body-content, #titlediv, #major-publishing-actions, #minor-publishing-actions, #visibility, #submitdiv{display: none;}
		</style>
			<div class="panel-wrap woocommerce">
				<div id="order_data" class="panel">
					<h2><?php _e('Account Details', 'wc_customer_relationship_manager'); ?></h2>							
					<?php if($account_id){ ?>
					<p class="order_number">
							<?php echo __( 'Account number', 'wc_customer_relationship_manager' ) . ' #' . $account_id . ' '; ?>
					</p>
					<?php } else { ?>
					<p class="order_number"></p>
					<?php } ?>			
					<p class="order_number">
						<?php
						$account_owner = 0;
						if($account_id){
							$account_owner = get_post_meta($account_id,'_account_owner', true); ;
						}else{
							$current_user  = wp_get_current_user();
							$account_owner = $current_user->ID ;
						}
						$user_meta    = (object) get_user_meta( $account_owner );
						?>
						<p class="order_number">
						<?php _e('Account owner', 'wc_customer_relationship_manager'); ?>:					
						<a href="<?php echo admin_url('profile.php'); ?>" target="_blank">
							<?php echo $user_meta->first_name[0]; ?> <?php echo $user_meta->last_name[0]; ?>
						</a>
						</p>
						<input type="hidden" name="account_owner" id="account_owner" value="<?php echo $account_owner; ?>">
					</p>
					<div class="order_data_column_container">
						<div class="order_data_column account_data_column">
							<h4><?php _e( 'General Details', 'wc_customer_relationship_manager' ); ?></h4>
							<p class="form-field form-field-wide">
								<label for="post_title"><?php _e('Account Name', 'wc_customer_relationship_manager'); ?></label>
								<input type="text" value="<?php echo $post->post_title; ?>" name="post_title" id="post_title" required >
							</p>
							<?php
								if ( self::$general_fields ) {
									foreach ( self::$general_fields as $key => $field ) {
										if ( ! isset( $field['type'] ) ) {
											$field['type'] = 'text';
										}
										if ( ! isset( $field['id'] ) ){
											$field['id'] = '_' . $key;
										}

										switch ( $field['type'] ) {
											case 'select' :
												woocommerce_wp_select( $field );
											break;
											default :
												woocommerce_wp_text_input( $field );
											break;
										}
									}
								}
								?>
						</div>
						<div id="order_data_column_billing" class="order_data_column">
							<?php if($account_id) { ?>
								<h4>
									<?php _e('Billing Details', 'wc_customer_relationship_manager'); ?>
									<a class="edit_address" href="#"><img src="<?php echo WC()->plugin_url(); ?>/assets/images/icons/edit.png" alt="<?php _e( 'Edit', 'wc_customer_relationship_manager' ); ?>" width="14" /></a>
								</h4>
								<div class="address">
									<?php
										if ( self::getFormatedBillingAdress($account_id) ) {
											echo '<p><strong>' . __( 'Address', 'wc_customer_relationship_manager' ) . ':</strong>' . wp_kses( self::getFormatedBillingAdress($account_id), array( 'br' => array() ) ) . '</p>';
										} else {
											echo '<p class="none_set"><strong>' . __( 'Address', 'wc_customer_relationship_manager' ) . ':</strong> ' . __( 'No billing address set.', 'wc_customer_relationship_manager' ) . '</p>';
										}
									?>
								</div>
								<?php }else{ ?>
								<h4>
									<?php _e('Billing Details', 'wc_customer_relationship_manager'); ?>
								</h4>
							<?php } ?>
							<div class="edit_address" <?php echo $account_id == 0 ? 'style="display: block;"' : ''; ?> >
								<?php
								foreach ( self::$billing_fields as $key => $field ) {
									if ( ! isset( $field['type'] ) ) {
										$field['type'] = 'text';
									}
									if ( ! isset( $field['id'] ) ){
										$field['id'] = '_billing_' . $key;
									}
									switch ( $field['type'] ) {
										case 'select' :
											woocommerce_wp_select( $field );
										break;
										default :
											woocommerce_wp_text_input( $field );
										break;
									}
								}
								?>
							</div>
						</div>
						<div id="order_data_column_shipping" class="order_data_column">
							<?php if($account_id) { ?>
								<h4>
									<?php _e('Shipping Details', 'wc_customer_relationship_manager'); ?>
									<a class="edit_address" href="#"><img src="<?php echo WC()->plugin_url(); ?>/assets/images/icons/edit.png" alt="<?php _e( 'Edit', 'wc_customer_relationship_manager' ); ?>" width="14" /></a>
								</h4>
								<div class="address">
									<?php
										if ( self::getFormatedShippingAdress($account_id) ) {
											echo '<p><strong>' . __( 'Address', 'wc_customer_relationship_manager' ) . ':</strong>' . wp_kses( self::getFormatedShippingAdress($account_id), array( 'br' => array() ) ) . '</p>';
										} else {
											echo '<p class="none_set"><strong>' . __( 'Address', 'wc_customer_relationship_manager' ) . ':</strong> ' . __( 'No shipping address set.', 'wc_customer_relationship_manager' ) . '</p>';
										}
									?>
								</div>
								<?php }else{ ?>
								<h4>
									<?php _e('Shipping Details', 'wc_customer_relationship_manager'); ?>
								</h4>
							<?php } ?>
							<div class="edit_address" <?php echo $account_id == 0 ? 'style="display: block;"' : ''; ?> >
								<?php
								if ( self::$shipping_fields ) {
									foreach ( self::$shipping_fields as $key => $field ) {
										if ( ! isset( $field['type'] ) ) {
											$field['type'] = 'text';
										}
										if ( ! isset( $field['id'] ) ){
											$field['id'] = '_shipping_' . $key;
										}

										switch ( $field['type'] ) {
											case 'select' :
												woocommerce_wp_select( $field );
											break;
											default :
												woocommerce_wp_text_input( $field );
											break;
										}
									}
								}
								?>
							</div>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		<?php
	}

	public function output_actions($post)
	{
		?>
		<ul class="order_actions submitbox">
			<li id="actions" class="wide">
				<select id="wc_crm_account_action" name="wc_crm_account_action">
					<option value="">Actions</option>
				</select>
				<button class="button wc-reload wc_crm_new_action" title="<?php _e('Apply', 'wc_customer_relationship_manager'); ?>">
					<span>
						<?php _e('Apply', 'wc_customer_relationship_manager'); ?>
					</span>
				</button>
			</li>
			<li class="wide">
					<input type="submit" class="button save_customer button-primary wc_crm_new_action" style="float: right;" name="save" value="<?php _e('Save Account', 'wc_customer_relationship_manager'); ?>">
			</li>
		</ul>
		<?php
	}

	public function output_notes($post){
		global $post;

			$args = array(
				'post_id'   => $post->ID,
				'approve'   => 'approve',
				'type'      => 'order_note'
			);

			remove_filter( 'comments_clauses', array( 'WC_CRM_Post_Types', 'exclude_comments' ), 10, 1 );

			$notes = get_comments( $args );

			add_filter( 'comments_clauses', array( 'WC_CRM_Post_Types', 'exclude_comments' ), 10, 1 );

			echo '<ul class="order_notes">';

			if ( $notes ) {

				foreach( $notes as $note ) {

					$note_classes = get_comment_meta( $note->comment_ID, 'is_customer_note', true ) ? array( 'customer-note', 'note' ) : array( 'note' );

					?>
					<li rel="<?php echo absint( $note->comment_ID ) ; ?>" class="<?php echo implode( ' ', $note_classes ); ?>">
						<div class="note_content">
							<?php echo wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ); ?>
						</div>
						<p class="meta">
							<abbr class="exact-date" title="<?php echo $note->comment_date; ?>"><?php printf( __( 'added on %1$s at %2$s', 'woocommerce' ), date_i18n( wc_date_format(), strtotime( $note->comment_date ) ), date_i18n( wc_time_format(), strtotime( $note->comment_date ) ) ); ?></abbr>
							<?php if ( $note->comment_author !== __( 'WooCommerce', 'woocommerce' ) ) printf( ' ' . __( 'by %s', 'woocommerce' ), $note->comment_author ); ?>
							<a href="#" class="delete_note"><?php _e( 'Delete note', 'woocommerce' ); ?></a>
						</p>
					</li>
					<?php
				}

			} else {
				echo '<li>' . __( 'There are no notes yet.', 'woocommerce' ) . '</li>';
			}

			echo '</ul>';
			?>
			<div class="add_note">
				<h4><?php _e( 'Add note', 'woocommerce' ); ?> </h4>
				<p>
					<textarea type="text" name="order_note" id="add_order_note" class="input-text" cols="20" rows="5"></textarea>
				</p>
				<p>
					<select name="order_note_type" id="order_note_type" style="display: none;">
						<option value=""><?php _e( 'Private note', 'woocommerce' ); ?></option>
					</select>
					<a href="#" class="add_note button"><?php _e( 'Add', 'woocommerce' ); ?></a>
				</p>
			</div>
			<?php
		}

	public static function output_customers($post)
	{
			require_once( 'classes/wc_crm_customers_table.php');
			$wc_crm_order_list = new WC_Crm_Customers_Table();
			$wc_crm_order_list->prepare_items();
			$wc_crm_order_list->display();
			?>
			<hr>
			<a href="<?php echo admin_url('admin.php?page=wc_new_customer&account='.$post->ID);?>" class="button" id="asign_customer_to_account">
				<?php _e("Add Customer", "wc_customer_relationship_manager"); ?>
			</a>
			<div class="clear"></div>
			<?php
	}

	public static function getFormatedBillingAdress($id)
	{
			// Formatted Addresses
			$address = apply_filters( 'wc_crm_accounts_formatted_billing_address', array(
				'first_name'    => get_post_meta($id, '_billing_first_name', true),
				'last_name'     => get_post_meta($id, '_billing_last_name', true),
				'company'       => get_post_meta($id, '_billing_company', true),
				'address_1'     => get_post_meta($id, '_billing_address_1', true),
				'address_2'     => get_post_meta($id, '_billing_address_2', true),
				'city'          => get_post_meta($id, '_billing_city', true),
				'state'         => get_post_meta($id, '_billing_state', true),
				'postcode'      => get_post_meta($id, '_billing_postcode', true),
				'country'       => get_post_meta($id, '_billing_country', true)
			), $id );

			$formatted_billing_address = WC()->countries->get_formatted_address( $address );

		return $formatted_billing_address;
	}

	public static function getFormatedShippingAdress($id)
	{
		// Formatted Addresses
			$address = apply_filters( 'wc_crm_accounts_formatted_billing_address', array(
				'first_name'    => get_post_meta($id, '_shipping_first_name', true),
				'last_name'     => get_post_meta($id, '_shipping_last_name', true),
				'company'       => get_post_meta($id, '_shipping_company', true),
				'address_1'     => get_post_meta($id, '_shipping_address_1', true),
				'address_2'     => get_post_meta($id, '_shipping_address_2', true),
				'city'          => get_post_meta($id, '_shipping_city', true),
				'state'         => get_post_meta($id, '_shipping_state', true),
				'postcode'      => get_post_meta($id, '_shipping_postcode', true),
				'country'       => get_post_meta($id, '_shipping_country', true)
			), $id );

			$formatted_shipping_address = WC()->countries->get_formatted_address( $address );

		return $formatted_shipping_address;
	}

	public static function save_meta_boxes( $post_id, $post ) {
    // $post_id and $post are required
    if ( empty( $post_id ) || empty( $post ) || self::$saved_meta_boxes ) {
      return;
    }

    // Dont' save meta boxes for revisions or autosaves
    if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
      return;
    }

    // Check the nonce
    if ( empty( $_POST['wc_crm_meta_nonce'] ) || ! wp_verify_nonce( $_POST['wc_crm_meta_nonce'], 'wc_crm_save_data' ) ) {
      return;
    }

    // Check the post being saved == the $post_id to prevent triggering this call for other save_post events
    if ( empty( $_POST['post_ID'] ) || $_POST['post_ID'] != $post_id ) {
      return;
    }

    // Check user has permission to edit
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
      return;
    }

    self::$saved_meta_boxes = true;

    // Check the post type
    if ( $post->post_type == 'wc_crm_accounts' ) {
      do_action( 'wc_crm_process_accounts_meta', $post_id, $post );
    }
  }

  public static function save( $post_id, $post ) {
		global $wpdb;

		self::init_fields();
		update_post_meta( $post_id, '_account_owner', absint( $_POST['account_owner'] ) );

		if ( self::$general_fields ){
			foreach ( self::$general_fields as $key => $field ) {
				if ( ! isset( $field['id'] ) ){
					$field['id'] = '_' . $key;
				}
				update_post_meta( $post_id, $field['id'], wc_clean( $_POST[ $field['id'] ] ) );
			}
		}
		if ( self::$billing_fields ) {
			foreach ( self::$billing_fields as $key => $field ) {
				if ( ! isset( $field['id'] ) ){
					$field['id'] = '_billing_' . $key;
				}
				update_post_meta( $post_id, $field['id'], wc_clean( $_POST[ $field['id'] ] ) );
			}
		}

		if ( self::$shipping_fields ) {
			foreach ( self::$shipping_fields as $key => $field ) {
				if ( ! isset( $field['id'] ) ){
					$field['id'] = '_shipping_' . $key;
				}
				update_post_meta( $post_id, $field['id'], wc_clean( $_POST[ $field['id'] ] ) );
			}
		}

		  $acc = array(
		      'ID'           => $post_id,
		      'post_status'  => 'publish'
		  );

		// Update the post into the database
		  wp_update_post( $acc );

	}

	public static function remove_quick_edit($actions )
	{
		$screen = get_current_screen();
		if( $screen->id == 'edit-wc_crm_accounts'){
			unset($actions['inline hide-if-no-js']);
		}
		return $actions;
	}
}
