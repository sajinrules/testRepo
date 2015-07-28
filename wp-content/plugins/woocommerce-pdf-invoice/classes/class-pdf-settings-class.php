<?php

        class WC_pdf_admin_settings {

            public function __construct() {

                /**
                 * Add menu item to WooCommerce Menu
                 */
                add_action( 'admin_menu', array( $this,'admin_menu' ) );

                /**
                 * Register WooCommerce PDF Settings
                 */
                add_action( 'admin_init' , array( $this,'register_settings' ) );
				
				/**
				 * Add PDF page to list of pages that load WooCommerce scripts with this handy filter from WooCommerce
				 */
                add_filter( 'woocommerce_screen_ids' , array( $this, 'screen_id' ) );

                /**
                 * Update any past orders
                 */
                add_action( 'woocommerce_pdf_invoice_settings_action' , array( $this,'update_past_orders') );

            }

            /**
             * Add menu item to WooCommerce Menu
			 * 
			 * $parent_slug : woocommerce
			 * $page_title : PDF Invoice
			 * $menu_title : PDF Invoice
			 * $capability : manage_woocommerce
			 * $menu_slug : woocommerce_pdf
			 * $function : options_page
             */
            function admin_menu() {
                add_submenu_page('woocommerce', __( 'PDF Invoice', 'woocommerce-pdf-invoice' ), __( 'PDF Invoice', 'woocommerce-pdf-invoice' ), 'manage_woocommerce', 'woocommerce_pdf', array($this,'options_page') );
            }
			
            /**
             * Register WooCommerce PDF Settings
             */
            function register_settings() {
                register_setting( 'woocommerce_pdf_invoice_settings_group', 'woocommerce_pdf_invoice_settings' );
            }

			/**
			 * Add PDF settings page to list of pages that load WooCommerce scripts
			 */
            function screen_id( $woocommerce_screen_ids ) {
                global $woocommerce;

                $woocommerce_screen_ids[] = 'woocommerce_page_woocommerce_pdf';

                return $woocommerce_screen_ids;
			}

            /**
             * PDF options page
             */
            function options_page() {
               $woocommerce_pdf_invoice_options = get_option('woocommerce_pdf_invoice_settings');
               do_action( 'woocommerce_pdf_invoice_settings_action' );

                ob_start(); ?>

                <div class="wrap woocommerce">

                    <div id="icon-woocommerce" class="icon32 icon32-woocommerce-settings">
                    <br>
                    </div>

                    <h2><?php _e( 'WooCommerce PDF Invoice' , 'woocommerce-pdf-invoice' ) ?></h2>
                    <?php settings_errors(); ?>  
          
        			<?php  
					// Set the default tab and get the tab variable from the URL if available
						$active_tab = 'display_settings';
            			if( isset( $_GET[ 'tab' ] ) ) :  
                			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'display_settings';  
            			endif;  
        			?>
                    
                    <h2 class="nav-tab-wrapper">
						<a href="?page=woocommerce_pdf&tab=display_settings" class="nav-tab <?php echo $active_tab == 'display_settings' ? 'nav-tab-active' : ''; ?>"><?php _e( 'PDF Settings' , 'woocommerce-pdf-invoice' ) ?></a>
						<a href="?page=woocommerce_pdf&tab=display_debugging" class="nav-tab <?php echo $active_tab == 'display_debugging' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Debugging Information' , 'woocommerce-pdf-invoice' ) ?></a>
					</h2>
                    <?php
                    if( $active_tab == 'display_debugging' ) :
					
					require_once ( WP_PLUGIN_DIR . "/woocommerce-pdf-invoice/lib/pdf_debugging.php" );
                    
                    else :
                    // Settings
                    require_once ( WP_PLUGIN_DIR . "/woocommerce-pdf-invoice/lib/dompdf_config.inc.php" );
                    ?>
                    <p><?php _e( 'Configure the WooCommerce PDF settings here, refer to the <a href="'.PDFDOCSURL.'" target="_blank">WooCommerce PDF Invoice docs</a> for more information' , 'woocommerce-pdf-invoice' ) ?></p>
                    
                        <?php

                        // Set the temp directory
                        $pdftemp = DOMPDF_TEMP_DIR;

                        $upload_dir =  wp_upload_dir();
                        if ( file_exists( $upload_dir['basedir'] . '/woocommerce_pdf_invoice/index.html' ) ) {
                            $pdftemp = $upload_dir['basedir'] . '/woocommerce_pdf_invoice/';
                        }

                        if ( !is_writable( $pdftemp ) ) :
                        ?>
                            <div class="error">
                            <p>The temp directory needs to be writable, please contact your host</p>
                            </div>
                        <?php endif; ?>

                        <?php if ( !is_writable( DOMPDF_FONT_DIR ) || !is_writable( DOMPDF_FONT_DIR . 'dompdf_font_family_cache.dist.php' ) ) : ?>
                            <div class="error">
                            <p>Please make the DOMPDF font directory (<strong><?php echo str_replace( ABSPATH , '' , DOMPDF_FONT_DIR); ?></strong>) and <br >font cache file (<strong><?php echo str_replace( ABSPATH , '' , DOMPDF_FONT_DIR) . 'dompdf_font_family_cache.dist.php'; ?></strong>) are writable. Please use 777 for the file permissions</p>
                            </div>
                        <?php endif; ?>

                    <form method="post" action="options.php">
 
                    <?php settings_fields('woocommerce_pdf_invoice_settings_group'); ?>
 
                    <table class="form-table">
                        
                         <!-- Attach PDF to multiple emails -->
                        <?php $attach_multiple = array( 'new_order' => 'New Order', 'customer_invoice' => 'Invoice', 'customer_processing_order' => 'Processing', 'on-hold' => 'On-Hold Orders' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[attach_multiple]"><?php _e('Choose which additional emails to attach the PDF to.', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('By default the PDF Invoice extension will only atach to the Completed Order Email, if you want to attach it to other emails sent by WooCommerce then make selections here(ctrl-click or cmd-click for multiple selections)', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select multiple name="woocommerce_pdf_invoice_settings[attach_multiple][]" id="woocommerce_pdf_invoice_settings[attach_multiple]" style="width: 350px;" class="chosen_select">
                            	<?php foreach ( $attach_multiple as $key => $value ) : 
								
								// Backwards compatibility
								if ( $woocommerce_pdf_invoice_options['attach_neworder'] == 'true' && $key == 'new_order' && !isset($woocommerce_pdf_invoice_options['attach_multiple']) ) : ?>
                                	<option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
								
								<?php elseif ( isset($woocommerce_pdf_invoice_options['attach_multiple'] ) && in_array( $key, $woocommerce_pdf_invoice_options['attach_multiple'] ) ):?>
                                	<option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                                    
								<?php else : ?>
                                	<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                
                                <?php  endif; ?>
                                
								<?php endforeach; ?>
                            </select>
                            </td>
                        </tr>
                        
                        <!-- Create Invoice number etc if order is processing -->
                        <?php $create_array = array( 'completed' => 'Completed' , 'processing' => 'Processing' , 'pending' => 'Pending', 'on-hold' => 'On Hold' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[create_invoice]"><?php _e('When to create the invoice', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Do you want to create the invoice details when the order is paid for (processing) or wait until you have shipped (completed), from version 1.2.1 you can also create an invoice when the order status is On-Hold', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[create_invoice]" id="woocommerce_pdf_invoice_settings[create_invoice]" style="width: 350px;">
                            	<?php foreach ( $create_array as $key => $value ) : ?>
                                	<option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['create_invoice'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr> 
                         
                        <!-- Show invoice link on Thank You page -->
                        <?php $thanks_array = array( 'false' => 'No' , 'true' => 'Yes' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[link_thanks]"><?php _e('Show "Download Invoice" link on Thank You page?', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Add a link to download the invoice to the Thank You for your order page', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[link_thanks]" id="woocommerce_pdf_invoice_settings[link_thanks]" style="width: 350px;">
                            	<?php foreach ( $thanks_array as $key => $value ) : ?>
                                	<option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['link_thanks'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>                     
                        
                        <!-- Paper size -->
                        <?php $paper_array = array( 'a4' => 'A4', 'letter' => 'Letter' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[paper_size]"><?php _e('Paper Size', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Set the paper size of your PDF invoice, this is only really used if your customer prints it out.', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[paper_size]" id="woocommerce_pdf_invoice_settings[paper_size]" style="width: 350px;">
                            	<?php foreach ( $paper_array as $key => $value ) : ?>
                                	<option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['paper_size'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>
                        
                        <!-- Page Orientation -->
                        <?php $orientation_array = array( 'portrait' => 'Portrait', 'landscape' => 'Landscape' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[paper_orientation]"><?php _e('Paper Orientation', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Set the paper orientation of your PDF invoice, this is only really used if your customer prints it out.', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[paper_orientation]" id="woocommerce_pdf_invoice_settings[paper_orientation]" style="width: 350px;">
                            	<?php foreach ( $orientation_array as $key => $value ) : ?>
                                	<option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['paper_orientation'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>
                        
                        <!-- PDF Logo -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[logo_file]"><?php _e('PDF Logo', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php echo sprintf( __("Add a logo to your PDF, otherwise it will just use your WordPress title %s", 'woocommerce-pdf-invoice' ), get_bloginfo( 'name' ) ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[logo_file]" 
                                name="woocommerce_pdf_invoice_settings[logo_file]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['logo_file']) ? $woocommerce_pdf_invoice_options['logo_file'] : '' ); ?>"
                                placeholder="<?php _e('Copy the URL to your logo into here', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>
                            <?php echo ( isset($woocommerce_pdf_invoice_options['logo_file']) ? '<p><img src="'.$woocommerce_pdf_invoice_options['logo_file'].'" /></p>' : '' ); ?>
                            </td>
                        </tr>
                        
                        <!-- Company Name -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_company_name]"><?php _e('Company Name', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('The name of your company, this shows at the top of the invoice', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[pdf_company_name]" 
                                name="woocommerce_pdf_invoice_settings[pdf_company_name]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['pdf_company_name']) ? $woocommerce_pdf_invoice_options['pdf_company_name'] : '' ); ?>"
                                placeholder="<?php _e('Your company name', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>                     
                            </td>
                        </tr>
                        
                        <!-- Company Details -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_company_details]"><?php _e('Company Information', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="This is the address that your business operates from." src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <textarea id="woocommerce_pdf_invoice_settings[pdf_company_details]" 
                                name="woocommerce_pdf_invoice_settings[pdf_company_details]"
                                placeholder="<?php _e('Your company contact info', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"><?php echo ( isset($woocommerce_pdf_invoice_options['pdf_company_details']) ? $woocommerce_pdf_invoice_options['pdf_company_details'] : '' ); ?></textarea>                     
                            </td>
                        </tr>
                        
                        <!-- Registered Name -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_registered_name]"><?php _e('Registered Name', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="This sets the legal name of your company." src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[pdf_registered_name]" 
                                name="woocommerce_pdf_invoice_settings[pdf_registered_name]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['pdf_registered_name']) ? $woocommerce_pdf_invoice_options['pdf_registered_name'] : '' ); ?>"
                                placeholder="<?php _e('The legal name of your company', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>                     
                            </td>
                        </tr>
                        
                        <!-- Registered Address -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_registered_address]"><?php _e('Registered Office', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="This is the legal registered address of your company, it may be different to the address that your business operates from." src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <textarea id="woocommerce_pdf_invoice_settings[pdf_registered_address]" 
                                name="woocommerce_pdf_invoice_settings[pdf_registered_address]"
                                placeholder="<?php _e('The legal registered address of your company', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"><?php echo ( isset($woocommerce_pdf_invoice_options['pdf_registered_address']) ? $woocommerce_pdf_invoice_options['pdf_registered_address'] : '' ); ?></textarea>                     
                            </td>
                        </tr>
                        
                        <!-- Company Number -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_company_number]"><?php _e('Company Number', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="This is the government issued number for your business (in the UK it would be the number from Companies House)." src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[pdf_company_number]" 
                                name="woocommerce_pdf_invoice_settings[pdf_company_number]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['pdf_company_number']) ? $woocommerce_pdf_invoice_options['pdf_company_number'] : '' ); ?>"
                                placeholder="<?php _e('Government issued company ID', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>                     
                            </td>
                        </tr>
                        
                        <!-- Tax Number -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_tax_number]"><?php _e('Tax Number', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('If your buisness is registered for tax purposes your tax office may have issued you with a number (in the UK this would be your VAT number)', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[pdf_tax_number]" 
                                name="woocommerce_pdf_invoice_settings[pdf_tax_number]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['pdf_tax_number']) ? $woocommerce_pdf_invoice_options['pdf_tax_number'] : '' ); ?>"
                                placeholder="<?php _e('Govenment issued tax number if you have one', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>                     
                            </td>
                        </tr>

                        <!-- Invoice Sequential -->
                        <?php $sequential_array = array( 'true' => 'Yes', 'false' => 'No' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[sequential]"><?php _e('Use Sequential Invoice Numbering', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('By default WooCommerce uses the post->ID as the order number so there will be gaps in the order number sequence. By setting this to Yes invoice numbers will be sequential.', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[sequential]" id="woocommerce_pdf_invoice_settings[sequential]" style="width: 350px;">
                            	<?php foreach ( $sequential_array as $key => $value ) : ?>
                                	<option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['sequential'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>

                        <!-- Invoice Number Reset -->
                        <?php $reset_array = array( 'FALSE' => 'No', 'TRUE' => 'Yes' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[annual_restart]"><?php _e('Resest Invoice Numbering to 1 at the start of each year', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Will reset the invoice number to 1 for the first order of the year.', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[annual_restart]" id="woocommerce_pdf_invoice_settings[annual_restart]" style="width: 350px;">
                                <?php foreach ( $reset_array as $key => $value ) : ?>
                                    <option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['annual_restart'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <br /><?php _e('Use this option with caution, check with your local tax office if you are not sure if you need to use this.<br /><strong>You should include -{{year}} in the "Invoice number suffix" setting</strong>', 'woocommerce-pdf-invoice' ); ?>
                            </td>
                        </tr>
                        
                        <!-- Invoice Number Start -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[start_number]"><?php _e('Number of first invoice if not 1', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('What number would you like on the first invoice? Once you have issued an invoice changing this will make no difference', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[start_number]" 
                                name="woocommerce_pdf_invoice_settings[start_number]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['start_number']) ? $woocommerce_pdf_invoice_options['start_number'] : '' ); ?>"
                                placeholder="<?php _e('What number would you like on the first invoice?', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>
                            </td>
                        </tr>
                        
                        <!-- Invoice Number Prefix -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_prefix]"><?php _e('Invoice number prefix', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Use this field to add a prefix to your invoice numbers. If you want your invoice number to look like ABC-123 then add ABC- to this field', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[pdf_prefix]" 
                                name="woocommerce_pdf_invoice_settings[pdf_prefix]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['pdf_prefix']) ? $woocommerce_pdf_invoice_options['pdf_prefix'] : '' ); ?>"
                                placeholder="<?php _e('Add an invoice number prefix', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>                     
                            </td>
                        </tr>
                        
                        <!-- Invoice Number Sufix -->
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_sufix]"><?php _e('Invoice number suffix', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Use this field to add a prefix to your invoice numbers. If you want your invoice number to look like 123-ABC then add -ABC to this field', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[pdf_sufix]" 
                                name="woocommerce_pdf_invoice_settings[pdf_sufix]" 
                                type="text" 
                                value="<?php echo ( isset($woocommerce_pdf_invoice_options['pdf_sufix']) ? $woocommerce_pdf_invoice_options['pdf_sufix'] : '' ); ?>"
                                placeholder="<?php _e('Add an invoice number prefix suffix', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>                     
                            </td>
                        </tr>
                        
                        <!-- Invoice File Name Format -->
                        <?php if ( isset($woocommerce_pdf_invoice_options['pdf_filename']) && $woocommerce_pdf_invoice_options['pdf_filename'] != '' ) :
								$invoice_filename = $woocommerce_pdf_invoice_options['pdf_filename'];
							  else : 
							  	$invoice_filename = '{{company}}-{{invoicenumber}}';
							  endif; ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_filename]"><?php _e('Invoice file name format', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Set the file name format for your PDF files. Bear in mind that your customer should be able to identify your invoice easily. Please review the documentation for accepted variables. Default is {{company}}-{{invoicenumber}}', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                                <input id="woocommerce_pdf_invoice_settings[pdf_filename]" 
                                name="woocommerce_pdf_invoice_settings[pdf_filename]" 
                                type="text" 
                                value="<?php echo $invoice_filename; ?>"
                                placeholder="<?php _e('Invoice filename layout', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>                     
                            </td>
                        </tr>
                        
                        <!-- Invoice Date -->
                        <?php $date_array = array( 'order' => 'Order Date', 'completed' => 'Completed Date' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_date]"><?php _e('Which date should the invoice use', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Do you want the invoice date to be the date of order or the date the order is completed.', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[pdf_date]" id="woocommerce_pdf_invoice_settings[pdf_date]" style="width: 350px;">
                            	<?php foreach ( $date_array as $key => $value ) : ?>
                                	<option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['pdf_date'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>
                        
                        <!-- Invoice Date Format -->
                        <?php if ( isset($woocommerce_pdf_invoice_options['pdf_date_format']) && $woocommerce_pdf_invoice_options['pdf_date_format'] != '' ) :
								$invoice_date_format = $woocommerce_pdf_invoice_options['pdf_date_format'];
							  else : 
							  	$invoice_date_format = 'j F, Y';
							  endif; ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_date_format]"><?php _e('Invoice date format', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Set the invoice date format, see the docs for further information and examples.', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <input id="woocommerce_pdf_invoice_settings[pdf_date_format]" 
                                name="woocommerce_pdf_invoice_settings[pdf_date_format]" 
                                type="text" 
                                value="<?php echo $invoice_date_format; ?>"
                                placeholder="<?php _e('j F, Y', 'woocommerce-pdf-invoice' ); ?>" style="width: 350px;"/>
                                <p>Current Date Format : <?php 
								echo date( $invoice_date_format, strtotime( "now" ) ) ; ?></p>
                            </td>
                        </tr>
                        
                        <!-- Create Invoice for old orders -->
                        <?php $old_array = array( '0' => 'Invoice Old Orders', '1' => 'Yes' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_create_invoices_for_old]"><?php _e('Create invoices for past orders', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('If you have past orders that have been completed, do you want to create invoices for them?', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[pdf_create_invoices_for_old]" id="woocommerce_pdf_invoice_settings[pdf_create_invoices_for_old]" style="width: 350px;">
                            	<?php foreach ( $old_array as $key => $value ) : ?>
                                	<option value="<?php echo $key; ?>" ><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>
                        
                        <!-- Add PDF Terms Page -->
                        <?php $pages = get_pages(); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_termsid]"><?php _e('PDF Terms Page', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e('Set a terms page for your PDF invoices, if you set a terms page then an additional page will be added to the PDF. This terms pages uses a seperate template file so you can style the terms seperately', 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[pdf_termsid]" id="woocommerce_pdf_invoice_settings[pdf_termsid]" style="width: 350px;">
                            	<option value="0" <?php selected( $woocommerce_pdf_invoice_options['pdf_termsid'], 0 ); ?>><?php _e('Select PDF terms page if required', 'woocommerce-pdf-invoice' ) ?></option>
                            	<?php foreach ( $pages as $page ) : ?>
                                	<option value="<?php echo $page->ID; ?>" <?php selected( $woocommerce_pdf_invoice_options['pdf_termsid'], $page->ID ); ?>><?php echo $page->post_title; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>
                        
                        <!-- Invoice creation method -->
                        <?php $create_array = array( 'standard' => 'Standard', 'file' => 'File only' ); ?>
                        <tr valign="top">
                            <th scope="row" class="titledesc">
                                <label for="woocommerce_pdf_invoice_settings[pdf_creation]"><?php _e('PDF Creation Method', 'woocommerce-pdf-invoice' ); ?></label>
                                <img class="help_tip" data-tip="<?php _e("If you have problems with PDFs not creating change this option to 'File only'", 'woocommerce-pdf-invoice' ); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png' );?>" height="16" width="16" />                 
                            </th>
                            <td class="forminp forminp-number">
                            <select name="woocommerce_pdf_invoice_settings[pdf_termsid]" id="woocommerce_pdf_invoice_settings[pdf_creation]" style="width: 350px;">
                                <option value="0" <?php selected( $woocommerce_pdf_invoice_options['pdf_creation'], 0 ); ?>><?php _e('Select PDF creation method', 'woocommerce-pdf-invoice' ) ?></option>
                                <?php foreach ( $create_array as $key => $value ) : ?>
                                    <option value="<?php echo $key; ?>" <?php selected( $woocommerce_pdf_invoice_options['pdf_creation'], $key ); ?>><?php echo $value; ?></option>
                                <?php endforeach; ?>
                            </select>
                            </td>
                        </tr>

                        <?php do_action( 'woocommerce_pdf_invoice_additional_fields_admin' ); ?>
                        
                    </table>


                    <p class="submit">
                    <?php //backwards compatibility ?>
                    <input type="hidden" name="woocommerce_pdf_invoice_settings[attach_neworder]" value="false" />
                    <input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'woocommerce-pdf-invoice' ); ?>" />
                    </p>
 
                </form>
                
                </div>     

                <?php echo ob_get_clean(); 
				
				endif; // End settings tab.
            }

             /**
              * update_past_orders
              */
             function update_past_orders() {

                 $woocommerce_pdf_invoice_options = get_option('woocommerce_pdf_invoice_settings');
                 if ( $woocommerce_pdf_invoice_options['pdf_create_invoices_for_old'] == 1 ) :
                 
                    global $wpdb, $woocommerce, $the_order;
                 
                    $line_items = $wpdb->get_results( "
                                                        SELECT      *
                                                        FROM        {$wpdb->prefix}posts
                                                        WHERE       post_type = 'shop_order'
                                                        ORDER BY    id ASC
                                                    " );

                    $items = array();
            
                    foreach ( $line_items as $item ) {
                        $the_order = new WC_Order( $item->ID );
                
                        if ( sanitize_title( $the_order->status ) == 'completed' && get_post_meta($item->ID, '_invoice_number', TRUE) == '' ) :
                        
                            WC_pdf_functions::woocommerce_completed_order_create_invoice( $item->ID );

                        endif;
                    }
                 
                endif;               
             }

        }

    	$GLOBALS['WC_pdf_admin_settings'] = new WC_pdf_admin_settings();