<?php

	/***
	***	@delete multiselect fields
	***/
	add_action('um_admin_before_saving_role_meta', 'um_followers_multi_choice_keys');
	function um_followers_multi_choice_keys( $post_id ){
		delete_post_meta( $post_id, '_um_can_follow_roles' );
	}

	/***
	***	@add options for followers
	***/
	add_action('um_admin_custom_role_metaboxes', 'um_followers_add_role_metabox');
	function um_followers_add_role_metabox( $action ){
		
		global $ultimatemember;
		
		$metabox = new UM_Admin_Metabox();
		$metabox->is_loaded = true;

		add_meta_box("um-admin-form-followers{" . um_followers_path . "}", __('Followers','um-followers'), array(&$metabox, 'load_metabox_role'), 'um_role', 'normal', 'low');
		
	}
	
	/***
	***	@When user is removed all their following data should be removed
	***/
	add_action('um_delete_user', 'um_followers_delete_user_data');
	function um_followers_delete_user_data( $user_id ) {
		global $wpdb;
		$wpdb->delete( $wpdb->prefix . "um_followers" , array( 'user_id1' => $user_id ) );
		$wpdb->delete( $wpdb->prefix . "um_followers" , array( 'user_id2' => $user_id ) );
	}