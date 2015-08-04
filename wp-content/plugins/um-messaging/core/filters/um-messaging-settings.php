<?php

	/***
	***	@extend settings
	***/
	add_filter("redux/options/um_options/sections", 'um_messaging_config', 10 );
	function um_messaging_config($sections){
		global $um_bbpress;
		
		$fields[] = array(
			'id'       		=> 'pm_char_limit',
			'type'     		=> 'text',
			'title'   		=> __( 'Message character limit','um-messaging' ),
			'default'		=> 200,
			'validate'		=> 'numeric'
		);
		
		$fields[] = array(
			'id'       		=> 'pm_block_users',
			'type'     		=> 'text',
			'title'   		=> __( 'Block users from sending messages','um-messaging' ),
			'desc' 	   		=> __('A comma seperated list of user IDs that cannot send messages on the site.','um-messaging'),
		);
		
        $fields[] = array(
			'id'       		=> 'pm_active_color',
            'type'     		=> 'color',
			'default'		=> um_get_metadefault('active_color'),
            'title'    		=> __( 'Primary color','ultimatemember' ),
            'validate' 		=> 'color',
			'transparent'	=> false,
        );
		
		$fields[] = array(
			'id'       		=> 'new_message_on',
			'type'     		=> 'switch',
			'title'    		=> __( 'New Message Notification','um-messaging' ),
			'default'  		=> 1,
			'desc' 	   		=> __('Send a notification to user when he receives a new private message','um-messaging'),
		);
				
		$fields[] = array(
			'id'       		=> 'new_message_sub',
			'type'     		=> 'text',
			'title'   		=> __( 'New Message Notification','um-messaging' ),
			'subtitle' 		=> __( 'Subject Line','um-messaging' ),
			'default'  		=> '{sender} has messaged you on {site_name}!',
			'required' 		=> array( 'new_message_on', '=', 1 ),
			'desc' 	   		=> __('This is the subject line of the e-mail','um-messaging'),
		);

		$fields[] = array(
			'id'       		=> 'new_message',
			'type'     		=> 'textarea',
			'title'    		=> __( 'New Message Notification','um-messaging' ),
			'subtitle' 		=> __( 'Message Body','um-messaging' ),
			'required' 		=> array( 'new_message_on', '=', 1 ),
			'default'  		=> 'Hi {recipient},' . "\r\n\r\n" .
								'{sender} has just started a conversation with you you on {site_name}.' . "\r\n\r\n" .
								'To view your new message(s) click the following link:'  . "\r\n" .
								'{message_history}'  . "\r\n\r\n" .
								'This is an automated notification from {site_name}. You do not need to reply.',
		);
		
		$sections[] = array(

			'subsection' => true,
			'title'      => __( 'Messaging','um-messaging'),
			'fields'     => $fields

		);

		return $sections;
		
	}
	
	/***
	***	@Adds a notification type
	***/
	add_filter('um_notifications_core_log_types', 'um_messaging_add_notification_type', 100 );
	function um_messaging_add_notification_type( $array ) {
		
		$array['new_pm'] = array(
			'title' => __('User get a new private message','um-messaging'),
			'template' => '<strong>{member}</strong> has just sent you a private message.',
			'account_desc' => __('When someone sends a private message to me','um-messaging'),
		);
		
		return $array;
	}