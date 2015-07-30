<?php
	
	/***
	***	@Followers List
	***/
	add_action('um_profile_content_followers_default', 'um_profile_content_followers_default');
	function um_profile_content_followers_default( $args ) {
		echo do_shortcode('[ultimatemember_followers user_id='.um_profile_id().']');
	}
	
	/***
	***	@Following List
	***/
	add_action('um_profile_content_following_default', 'um_profile_content_following_default');
	function um_profile_content_following_default( $args ) {
		echo do_shortcode('[ultimatemember_following user_id='.um_profile_id().']');
	}

	/***
	***	@customize the nav bar
	***/
	add_action('um_profile_navbar', 'um_followers_add_profile_bar', 4 );
	function um_followers_add_profile_bar( $args ) {
		echo do_shortcode('[ultimatemember_followers_bar user_id='.um_profile_id().']');
	}