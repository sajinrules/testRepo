<?php
	
	/***
	***	@creates options in Role page
	***/
	add_action('um_admin_custom_role_metaboxes', 'um_messaging_add_role_metabox');
	function um_messaging_add_role_metabox( $action ){
		global $ultimatemember;
		
		$metabox = new UM_Admin_Metabox();
		$metabox->is_loaded = true;

		add_meta_box("um-admin-form-messaging{" . um_messaging_path . "}", __('Private Messages','um-messaging'), array(&$metabox, 'load_metabox_role'), 'um_role', 'normal', 'low');
		
	}