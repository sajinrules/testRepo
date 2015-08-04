<?php

	/***
	***	@extend settings
	***/
	add_filter("redux/options/um_options/sections", 'um_mailchimp_config', 10 );
	function um_mailchimp_config($sections){
		global $um_mailchimp;
		
		$sections[] = array(

			'subsection' => true,
			'title'      => __( 'MailChimp','um-mailchimp'),
			'fields'     => array(

				array(
						'id'       		=> 'mailchimp_api',
						'type'     		=> 'text',
						'title'   		=> __( 'MailChimp API Key','um-mailchimp' ),
						'desc' 	   		=> __('The MailChimp API Key is required and enables you access and integration with your lists.','um-mailchimp'),
				),

				array(
						'id'       		=> 'mailchimp_real_status',
						'type'     		=> 'switch',
						'title'   		=> __( 'Enable Real-time Subscription Status','um-mailchimp' ),
						'default'		=> 0,
						'desc' 	   		=> __('Careful as this option will contact the MailChimp API when you request a status of user subscription to a specific list.','um-mailchimp'),
				),
				
			)

		);

		$um_mailchimp->tab_id = count($sections) - 1;
		
		return $sections;
		
	}