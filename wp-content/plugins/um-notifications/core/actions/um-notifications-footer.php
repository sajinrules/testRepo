<?php

	add_action('wp_footer', 'um_notification_show_feed', 99999999999);
	function um_notification_show_feed() {
		global $ultimatemember, $um_notifications;
		
		if ( !is_user_logged_in() ) return;
		
		$notifications = $um_notifications->api->get_notifications( 10 );
		if ( !$notifications ) {
			$template = 'no-notifications';
		} else {
			$template = 'notifications';
		}
		
		echo '<div class="um-notification-b '. um_get_option('notify_pos') . '" data-show-popup="' . um_get_option('realtime_notify_popup') . '">';
		echo '<i class="um-faicon-bell"></i>';
		echo '</div>';
		
		echo '<div class="um-notification-live-feed"><div class="um-notification-live-feed-inner">';
		
		$unread = (int)$um_notifications->api->get_notifications( 0, 'unread', true );
		echo '<span class="um-notification-live-count count-'. $unread . '">'. $unread .'</span>';
		
		include um_notifications_path . 'templates/'. $template . '.php';
		echo '</div></div>';
		
	}