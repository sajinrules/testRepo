<?php

function gatff_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	$gattf_license_key = trim(get_option('gattf_license_key'));
	$gattf_license_status = trim(get_option('gattf_license_key_status'));
	if (!$gattf_license_key || $gattf_license_status != 'valid'){
		$gattf_license_status = 'invalid';
		delete_option( 'gattf_license_key_status' );
	}
	
	$readOnlyStr = ''; 
	$gatff_plugin = false;
	$action = $_SERVER["REQUEST_URI"];
	if ( $gattf_license_status !== false && $gattf_license_status == 'valid' ) {
		$readOnlyStr = 'readonly';
		$gatff_plugin = true;
	}
	//get saves settings
	$saves_settings_option_name = '_gatff_saved_settings_V_2_4_';
	$saves_settings = get_option( $saves_settings_option_name, '' );
	?>
	<div class="wrap">
	<img src="<?PHP echo plugins_url(); ?>/ga-tracking-forms-pro/images/help-for-wordpress-small.png" align="left"/>
	<h2>Google Analytics For Forms - PRO</h2>
	<form action="<?php echo $action; ?>" method="POST" id="gatff_setting_form_id">
	<h3>Please activate your plugin!</h3>
	<p>In the field below please enter your license key to activate this plugin</p>
	<p>
	<input id="gattf_license_key_id" name="gattf_license_key" type="text" value="<?php echo $gattf_license_key; ?>" size="50" <?php echo $readOnlyStr; ?> />
	<?php
	if( $gattf_license_status !== false && $gattf_license_status == 'valid' ) {
		echo '<span style="color:green;">Active</span>';
		echo '<input type="submit" class="button-secondary" name="gattf_license_deactivate" value="Deactivate License" style="margin-left:20px;" />';
	}else{
		if ($gattf_license_key !== false && strlen($gattf_license_key) > 0) { 
			echo '<span style="color:red;">Inactive</span>'; 
		}
		echo '<input type="submit" class="button-secondary" name="gattf_license_activate" value="Activate License" style="margin-left:20px;" />';
	}
	wp_nonce_field( 'gattf_license_key_nonce', 'gattf_license_key_nonce' ); 
	?>
	</p>
	<?php if( $gatff_plugin ) { ?>
    <hr />
	<p>Choose how you would like this plugin to operate:</p>
	<h3 style="margin-top:40px;"><label><input type="checkbox" name="gatff_active_option_1" id="gatff_active_option_1_id" <?php if( $saves_settings && isset($saves_settings['option_1_setting']) && count($saves_settings['option_1_setting']) > 0 ){ echo 'checked="checked" '; } ?>/>Load on all Pages</label></h3>
    <p>Load the plugin on all pages of your site then configure one HTML Form id, choose this option when you have one form that is used on multiple pages of your site.</p>
    <div id="gatff_option_1_form_div" style="display:<?php if( $saves_settings && isset($saves_settings['option_1_setting']) && count($saves_settings['option_1_setting']) ){ echo 'block'; } else{ echo 'none'; }?>;">
        <p>
            <span style="width:200px; display:inline-block;"><label><input type="radio" name="gatff_option_1_form_type" id="gatff_option_1_form_type_html_id" value="html_form" <?php if( $saves_settings && ( !isset($saves_settings['option_1_setting']['form_type']) || $saves_settings['option_1_setting']['form_type'] != 'gravity_form' ) ){ echo 'checked="checked" '; } ?>/>Specify one HTML Form ID: </label></span>
            <input type="text" name="gatff_option_1_html_form_ID" id="gatff_option_1_html_form_ID_id" value="<?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'html_form' ){ echo $saves_settings['option_1_setting']['form_id']; } ?>" style="width:200px;" />
        </p>
        <?php
            //check if gravityforms activated
			$gravity_show = true;
            $plugins = get_option( 'active_plugins');
            if( !in_array('gravityforms/gravityforms.php',$plugins) ) { 
                $gravity_show = false;
            }else{
                $exist_forms_obj = gatff_get_gf_formids();
            }
        	if( $gravity_show && $exist_forms_obj){ 
		?>
        <p>
            <span style="width:200px; display:inline-block;"><label><input type="radio" name="gatff_option_1_form_type" id="gatff_option_1_form_type_gravity_form_id" value="gravity_form" <?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' ){ echo 'checked="checked" '; } ?>/>Gravity Form: </label></span>
            <select name="gatff_option_1_gravity_form_ID" id="gatff_option_1_gravity_form_ID_id" style="width:200px;" <?php if( $saves_settings && ( !isset($saves_settings['option_1_setting']['form_type']) || $saves_settings['option_1_setting']['form_type'] != 'gravity_form' ) ){ echo 'disabled="disabled" '; } ?>>
            	<option value="0">select...</option>';
				<?php
                if( count($exist_forms_obj) > 0 ){
                    foreach($exist_forms_obj as $u) {
                        if( isset($u->is_trash) && $u->is_trash == 1 ){
                            continue;
                        }
						$gform_id = 'gform_'. $u->id;
						if( $saves_settings && 
						    $saves_settings['option_1_setting']['form_type'] == 'gravity_form' && 
							$saves_settings['option_1_setting']['form_id'] == $gform_id )
						{
                        	echo '<option value="gform_' . $u->id . '" selected="selected">' . $u->id . ' ' . $u->title . '</option>';
						}else{
							echo '<option value="gform_' . $u->id . '">' . $u->id . ' ' . $u->title . '</option>';
						}
                    }
                }
                ?>
            </select>
            <span id="gatff_option_1_gf_fields_ajax_loader" style="display: none;"><img src="<?php echo plugin_dir_url("").'ga-tracking-forms-pro'; ?>/images/ajax-loader.gif" /></span>
        </p>
        <?php } ?>
        <h4>Configure the fields names that you'd like to use.</h4>
        <p>For example: if your field for the <i>source</i> looks like this &lt;input type=&#039;hidden&#039; name=&#039;foobar&#039;&gt; enter <strong>foobar</strong> for that field here.</p>
        <p>Tip: you should have already added these fields to your form, usually as hidden fields.</p>
        <?php
			$html_fields_display = 'inline-block';
			$garvity_fields_select_diabled = ' disabled="disabled" ';
			$gravity_fields_select_display = 'display:none;';
			if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' ){
				$garvity_fields_select_diabled = '';
				$gravity_fields_select_display = 'display:inline-block;';
				$html_fields_display = 'none';
			}
		?>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Source</span>
            <input type="text" name="gatff_option_1_field_source" id="gatff_option_1_field_source_id" value="<?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'html_form' ){ echo $saves_settings['option_1_setting']['fields_id']['source']; } ?>" placeholder="Source" style="width:200px;display:<?php echo $html_fields_display; ?>;" class="gatff-option-1-html-field-css" />
            <select name="gatff_option_1_gravity_form_fields_list_source" id="gatff_option_1_gravity_form_fields_list_source_id" style="width:200px;<?php echo $gravity_fields_select_display; ?>" <?php echo $garvity_fields_select_diabled; ?> class="gatff-option-1-gf-field-css">
            	<option value="0" >select...</option>
                <?php
					if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' && isset($saves_settings['option_1_setting']['form_id']) && $saves_settings['option_1_setting']['fields_id']['source'] ){
						gatff_list_gravity_form_4_option_1($saves_settings['option_1_setting']['form_id'], $saves_settings['option_1_setting']['fields_id']['source']); 
					}
				?>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Medium</span>
            <input type="text" name="gatff_option_1_field_medium" id="gatff_option_1_field_medium_id" value="<?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'html_form' ){ echo $saves_settings['option_1_setting']['fields_id']['medium']; } ?>" placeholder="Medium" style="width:200px;display:<?php echo $html_fields_display; ?>;" class="gatff-option-1-html-field-css" />
            <select name="gatff_option_1_gravity_form_fields_list_medium" id="gatff_option_1_gravity_form_fields_list_medium_id" style="width:200px;<?php echo $gravity_fields_select_display; ?>" <?php echo $garvity_fields_select_diabled; ?> class="gatff-option-1-gf-field-css">
            	<option value="0">select...</option>
                <?php
					if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' && isset($saves_settings['option_1_setting']['form_id']) && $saves_settings['option_1_setting']['fields_id']['medium'] ){
						gatff_list_gravity_form_4_option_1($saves_settings['option_1_setting']['form_id'], $saves_settings['option_1_setting']['fields_id']['medium']);
                    }
				?>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Term</span>
            <input type="text" name="gatff_option_1_field_term" id="gatff_option_1_field_term_id" value="<?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'html_form' ){ echo $saves_settings['option_1_setting']['fields_id']['term']; } ?>" placeholder="Term" style="width:200px;display:<?php echo $html_fields_display; ?>;" class="gatff-option-1-html-field-css" />
            <select name="gatff_option_1_gravity_form_fields_list_term" id="gatff_option_1_gravity_form_fields_list_term_id" style="width:200px;<?php echo $gravity_fields_select_display; ?>" <?php echo $garvity_fields_select_diabled; ?> class="gatff-option-1-gf-field-css">
            	<option value="0">select...</option>
                <?php
					if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' && isset($saves_settings['option_1_setting']['form_id']) && $saves_settings['option_1_setting']['fields_id']['term'] ){
                		gatff_list_gravity_form_4_option_1($saves_settings['option_1_setting']['form_id'], $saves_settings['option_1_setting']['fields_id']['term']);
					}
				?>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Content</span>
            <input type="text" name="gatff_option_1_field_content" id="gatff_option_1_field_content_id" value="<?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'html_form' ){ echo $saves_settings['option_1_setting']['fields_id']['content']; } ?>" placeholder="Content" style="width:200px;display:<?php echo $html_fields_display; ?>;" class="gatff-option-1-html-field-css" />
            <select name="gatff_option_1_gravity_form_fields_list_content" id="gatff_option_1_gravity_form_fields_list_content_id" style="width:200px;<?php echo $gravity_fields_select_display; ?>" <?php echo $garvity_fields_select_diabled; ?> class="gatff-option-1-gf-field-css">
            	<option value="0">select...</option>
                <?php
					if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' && isset($saves_settings['option_1_setting']['form_id']) && $saves_settings['option_1_setting']['fields_id']['content'] ){
                		gatff_list_gravity_form_4_option_1($saves_settings['option_1_setting']['form_id'], $saves_settings['option_1_setting']['fields_id']['content']);
					}
				?>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Campaign</span>
            <input type="text" name="gatff_option_1_field_campaign" id="gatff_option_1_field_campaign_id" value="<?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'html_form' ){ echo $saves_settings['option_1_setting']['fields_id']['campagin']; } ?>" placeholder="Campaign" style="width:200px;display:<?php echo $html_fields_display; ?>;" class="gatff-option-1-html-field-css" />
            <select name="gatff_option_1_gravity_form_fields_list_campaign" id="gatff_option_1_gravity_form_fields_list_campaign_id" style="width:200px;<?php echo $gravity_fields_select_display; ?>" <?php echo $garvity_fields_select_diabled; ?> class="gatff-option-1-gf-field-css">
            	<option value="0">select...</option>
                <?php
					if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' && isset($saves_settings['option_1_setting']['form_id']) && $saves_settings['option_1_setting']['fields_id']['campagin'] ){
                		gatff_list_gravity_form_4_option_1($saves_settings['option_1_setting']['form_id'], $saves_settings['option_1_setting']['fields_id']['campagin']);
					}
				?>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Segment</span>
            <input type="text" name="gatff_option_1_field_segment" id="gatff_option_1_field_segment_id" value="<?php if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'html_form' ){ echo $saves_settings['option_1_setting']['fields_id']['segment']; } ?>" placeholder="Segment" style="width:200px;display:<?php echo $html_fields_display; ?>;" class="gatff-option-1-html-field-css" />
            <select name="gatff_option_1_gravity_form_fields_list_segment" id="gatff_option_1_gravity_form_fields_list_segment_id" style="width:200px;<?php echo $gravity_fields_select_display; ?>" <?php echo $garvity_fields_select_diabled; ?> class="gatff-option-1-gf-field-css">
            	<option value="0">select...</option>
                <?php
					if( $saves_settings && $saves_settings['option_1_setting']['form_type'] == 'gravity_form' && isset($saves_settings['option_1_setting']['form_id']) && $saves_settings['option_1_setting']['fields_id']['segment'] ){
                		gatff_list_gravity_form_4_option_1($saves_settings['option_1_setting']['form_id'], $saves_settings['option_1_setting']['fields_id']['segment']);
					}
				?>
            </select>
        </p>
    </div>
    <hr />
    <h3 style="margin-top:40px;"><label><input type="checkbox" name="gatff_active_option_2" id="gatff_active_option_2_id" <?php if( $saves_settings && isset($saves_settings['option_2_setting']) && count($saves_settings['option_2_setting']) > 0 ){ echo 'checked="checked" '; } ?>/>Load on specified Pages</label></h3>
    <p>Specify individual page/post IDs and a corrosponding HTML Form id, choose this option to load the script only on specific pages and target a different HTML form id on each.</p>
    <div id="gatff_option_2_form_div" style="display:<?php if( $saves_settings && isset($saves_settings['option_2_setting']) && count($saves_settings['option_2_setting']) > 0 ){ echo 'block'; } else{ echo 'none'; }?>;"> 
    	<p>
            <span style="width:200px; display:inline-block;"><label>Specified Page/Post: </label></span>
            <?php
				$args = array(	'depth'     	=> 0,
								'child_of'  	=> 0,
								'selected'  	=> 0,
								'echo'      	=> false,
								'name'      	=> 'gatff_option_2_page_ID',
								'id'			=> 'gatff_option_2_page_ID_id',
								'sort_order'   	=> 'ASC',
								'sort_column'  	=> 'post_title',
								'post_type' 	=> 'page',
								'show_option_none' => "Plase select a page",
								'option_none_value'=> 0,
								'echo '			=> false
							 );
				$select_html = wp_dropdown_pages( $args );
				$select_html = str_replace('<select', '<select style="width:200px;"', $select_html);
				echo $select_html;
			?>
            <span style="display:inline-block; width:30px; text-align:center;">Or</span>
            <select name="gatff_option_2_post_ID" id="gatff_option_2_post_ID_id" style="width:200px;">
            	<option value="0">Please select a post</option>
				<?php
					$args = array(  'posts_per_page'   => -1,
									'offset'           => 0,
									'category'         => '',
									'orderby'          => 'title',
									'order'            => 'ASC',
									'include'          => '',
									'exclude'          => '',
									'meta_key'         => '',
									'meta_value'       => '',
									'post_type'        => 'post',
									'post_mime_type'   => '',
									'post_parent'      => '',
									'post_status'      => 'publish',
									'suppress_filters' => true
								 );
					$posts_got = get_posts($args);
					foreach( $posts_got as $post_obj ):
				 ?>
                 <option value="<?php echo $post_obj->ID; ?>"><?php echo $post_obj->post_title; ?></option>
                 <?php 
				 	endforeach; 
				?>
             </select>
        </p>
    	<p>
            <span style="width:200px; display:inline-block;"><label><input type="radio" name="gatff_option_2_form_type" id="gatff_option_2_form_type_html_id" value="html_form" checked="checked"/>Specify one HTML Form ID: </label></span>
            <input type="text" name="gatff_option_2_html_form_ID" id="gatff_option_2_html_form_ID_id" value="" style="width:200px;" />
        </p>
        <?php
            //check if gravityforms activated
			$gravity_show = true;
            $plugins = get_option( 'active_plugins');
            if( !in_array('gravityforms/gravityforms.php',$plugins) ) { 
                $gravity_show = false;
            }else{
                $exist_forms_obj = gatff_get_gf_formids();
            }
        	if( $gravity_show && $exist_forms_obj){ 
		?>
        <p>
            <span style="width:200px; display:inline-block;"><label><input type="radio" name="gatff_option_2_form_type" id="gatff_option_2_form_type_gravity_form_id" value="gravity_form" />Gravity Form: </label></span>
            <select name="gatff_option_2_gravity_form_ID" id="gatff_option_2_gravity_form_ID_id" style="width:200px;" disabled="disabled">
            	<option value="0" selected="selected">select...</option>';
				<?php
                if( count($exist_forms_obj) > 0 ){
                    foreach($exist_forms_obj as $u) {
                        if( isset($u->is_trash) && $u->is_trash == 1 ){
                            continue;
                        }
                        echo '<option value="gform_' . $u->id . '">' . $u->id . ' ' . $u->title . '</option>';
                    }
                }
                ?>
            </select>
            <span id="gatff_option_2_gf_fields_ajax_loader" style="display: none;"><img src="<?php echo plugin_dir_url("").'ga-tracking-forms-pro'; ?>/images/ajax-loader.gif" /></span>
        </p>
        <?php } ?>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Source</span>
            <input type="text" name="gatff_option_2_field_source" id="gatff_option_2_field_source_id" value="" placeholder="Source" style="width:200px;" class="gatff-option-2-html-field-css" />
            <select name="gatff_option_2_gravity_form_fields_list_source" id="gatff_option_2_gravity_form_fields_list_source_id" style="width:200px;display:none;" disabled="disabled" class="gatff-option-2-gf-field-css">
            	<option value="0" selected="selected">select...</option>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Medium</span>
            <input type="text" id="gatff_option_2_field_medium" value="" placeholder="Medium" style="width:200px;" class="gatff-option-2-html-field-css" />
            <select name="gatff_option_2_gravity_form_fields_list_medium" id="gatff_option_2_gravity_form_fields_list_medium_id" style="width:200px;display:none;" disabled="disabled" class="gatff-option-2-gf-field-css">
            	<option value="0" selected="selected">select...</option>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Term</span>
            <input type="text" id="gatff_option_2_field_term" value="" placeholder="Term" style="width:200px;" class="gatff-option-2-html-field-css" />
            <select name="gatff_option_2_gravity_form_fields_list_term" id="gatff_option_2_gravity_form_fields_list_term_id" style="width:200px;display:none;" disabled="disabled" class="gatff-option-2-gf-field-css">
            	<option value="0" selected="selected">select...</option>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Content</span>
            <input type="text" id="gatff_option_2_field_content" value="" placeholder="Content" style="width:200px;" class="gatff-option-2-html-field-css" />
            <select name="gatff_option_2_gravity_form_fields_list_content" id="gatff_option_2_gravity_form_fields_list_content_id" style="width:200px;display:none;" disabled="disabled" class="gatff-option-2-gf-field-css">
            	<option value="0" selected="selected">select...</option>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Campaign</span>
            <input type="text" id="gatff_option_2_field_campaign" value="" placeholder="Campaign" style="width:200px;" class="gatff-option-2-html-field-css" />
            <select name="gatff_option_2_gravity_form_fields_list_campaign" id="gatff_option_2_gravity_form_fields_list_campaign_id" style="width:200px;display:none;" disabled="disabled" class="gatff-option-2-gf-field-css">
            	<option value="0" selected="selected">select...</option>
            </select>
        </p>
        <p>
            <span style="width:200px; display:inline-block;">Field name for Segment</span>
            <input type="text" id="gatff_option_2_field_segment" value="" placeholder="Segment" style="width:200px;" class="gatff-option-2-html-field-css" />
            <select name="gatff_option_2_gravity_form_fields_list_segment" id="gatff_option_2_gravity_form_fields_list_segment_id" style="width:200px;display:none;" disabled="disabled" class="gatff-option-2-gf-field-css">
            	<option value="0" selected="selected">select...</option>
            </select>
        </p>
        <input type="button" id="gatff_option_2_add_configuration_id" class="button-primary" value="Add" />
        <p>
            Existing configuration for specified pages:
            <table class="widefat fixed" cellspacing="0" style="min-width:500px; width:80%;">
                <thead>
                    <th style="width:20%;">Page/Post ID</th>
                    <th style="width:20%;">Form ID</th>
                    <th style="width:40%;">Fields Name</th>
                    <th style="width:20%;"></th>
                </thead>
                <tbody id="gatff_existing_configuration_4_specified_page">
                	<?php
					if( $saves_settings && $saves_settings['option_2_setting'] && count($saves_settings['option_2_setting']) > 0 ){
						$insert_str = '';
						foreach($saves_settings['option_2_setting'] as $page_id => $page_forms){
							if( !is_array($page_forms) ){
								continue;
							}
							foreach( $page_forms as $form_id => $opton_2_setting ){
								if( !is_array($opton_2_setting) || !isset($opton_2_setting['form_id']) || !isset($opton_2_setting['fields_id']) || !is_array($opton_2_setting['fields_id']) || count($opton_2_setting['fields_id']) != 6 ){
									continue;
								}
								$insert_str.= $page_id.'#'.$opton_2_setting['form_id'].'#'.
											  $opton_2_setting['fields_id']['source'].'#'.
											  $opton_2_setting['fields_id']['medium'].'#'.
											  $opton_2_setting['fields_id']['term'].'#'.
											  $opton_2_setting['fields_id']['content'].'#'.
											  $opton_2_setting['fields_id']['campagin'].'#'.
											  $opton_2_setting['fields_id']['segment'].';';
	
								$fields_in_table_row_str = $opton_2_setting['fields_id']['source'].', '.
														   $opton_2_setting['fields_id']['medium'].', '.
														   $opton_2_setting['fields_id']['term'].', '.
														   $opton_2_setting['fields_id']['content'].', '.
														   $opton_2_setting['fields_id']['campagin'].', '.
														   $opton_2_setting['fields_id']['segment'];
			
								$table_row_tr_id = 'page_id_'.$page_id.'_form_id_'.$opton_2_setting['form_id'];
								$insert_str_hidden = '<input type="hidden" id="'.$table_row_tr_id.'_hidden_id" value="'.$insert_str.'" />'; //add this for delete
								$trash_icon_url = plugin_dir_url("").'ga-tracking-forms-pro/images/trash.gif';
								$trash_button = '<div id="trash_button_'.$table_row_tr_id.'" style="cursor:pointer;" class="gatff-option-2-trash-button"><img src="'.$trash_icon_url.'" width="16" height="16"></div>';
					?>
                    <tr id="<?php echo $table_row_tr_id; ?>" class="gattf-configuration-row">
                    	<td><?php echo $page_id; ?></td>
                    	<td><?php echo $opton_2_setting['form_id']; ?></td>
                        <td><?php echo $fields_in_table_row_str; ?></td>
                        <td><?php echo $insert_str_hidden.$trash_button; ?></td>
                    </tr>
                    <?php
							} //end foreach( $page_forms as $form_id => $filds_id ){
						} //end foreach
					}else{
					?>
                    <tr>
                        <td colspan="3">No item</td>
                    </tr>
                    <?php
					}
					?>
                </tbody>
            </table>
            <input type="hidden" name="gatff_option_2_exist_configuration" id="gatff_option_2_exist_configuration_id" value="<?php echo $insert_str; ?>"  />
            <input type="hidden" name="gatff_option_2_trash_icon_image_url" id="gatff_option_2_trash_icon_image_url_id" value="<?php echo plugin_dir_url("").'ga-tracking-forms-pro'; ?>/images/trash.gif"  />
        </p>
    </div>
    <input type="hidden" name="gatff_action" id="gatff_action_id" value="" />
    <input type="button" id="gatff_save_id" class="button-primary" value="Save all settings" />
	<?php } //end of if ($gatff_plugin) : ?>
    <hr />
	<h3 style="margin-top:40px;">Need help?</h3>
	<p>Plugin documentation is available at <a href="http://helpforwp.com/plugins/google-analytics-tracking-for-forms/google-analytics-tracking-for-forms-plugin-documentation/" target="_blank">HelpForWP.com</a> and <a href="http://helpforwp.com/forum/" target="_blank">support is available here</a>.</p>
	<br />
	<?php
	if( $gattf_license_status !== false && $gattf_license_status == 'valid' ) {
		global $_gattf_messager;
		
		$_gattf_messager->eddslum_plugin_option_page_update_center();
	}
	?>
	</div>
	<?php 
} //end of function gatff_options

function gatff_get_gf_formids() {
	global $wpdb;
	$table = $wpdb->prefix . 'rg_form';
	$gfids = $wpdb->get_results("SELECT * from $table");
	
	return $gfids;
}

function gatff_list_gravity_form_4_option_1( $formid, $field_id ){
	$formid = intval(str_replace('gform_', '', $formid));
	if( class_exists('GFAPI') ){
		$uns_gf = GFAPI::get_form( $formid );
	}else if( class_exists('GFFormsModel') ){
		$uns_gf = GFFormsModel::get_form_meta( $formid );
	}else{
		global $wpdb;
		$rg_form_meta_table = $wpdb->prefix . 'rg_form_meta';
		$f = $wpdb->get_results("SELECT * FROM {$rg_form_meta_table} WHERE form_id = " . $formid);
		$uns_gf = maybe_unserialize($f[0]->display_meta);
	}
	$form_id = $uns_gf['id'];
	foreach($uns_gf['fields'] as $field) {
		// check for displayOnly fields
		if(isset($field['displayOnly']) && $field['displayOnly'] == 1 || $field['type'] == 'fileupload') {
			continue;
		}
		if( isset($field['inputs']) && is_array($field['inputs']) && count($field['inputs']) > 0 && !isset($field['choices']) ) {
			foreach($field['inputs'] as $input) {
				$option_value = 'input_'.$formid.'_'.str_replace('.', '_', $input['id']);
				if( $field_id == $option_value ){
					$out .= '<option value="'.$option_value.'" selected="selected">'.$input['label'].'</option>';
				}else{
					$out .= '<option value="'.$option_value.'">'.$input['label'].'</option>';
				}
			}
		}else {
			$option_value = 'input_'.$formid.'_'.str_replace('.', '_', $field['id']);
			if( $field_id == $option_value ){
				$out .= '<option value="'.$option_value.'" selected="selected">'.$field['label'].'</option>';
			}else{
				$out .= '<option value="'.$option_value.'">'.$field['label'].'</option>';
			}
		}
	}
			
	echo $out;
}
?>
