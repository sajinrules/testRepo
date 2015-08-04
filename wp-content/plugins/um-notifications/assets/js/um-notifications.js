function LoadNotifications()
{
	jQuery.ajax({
		url: um_scripts.ajaxurl,
		type: 'post',
		dataType: 'json',
		data: { action: 'um_notification_check_update' },
		success: function(data) {

			if ( data.refresh_count ) {
					new_title = document.title.replace(/\(.*?\)/g, '');
					new_title = data.refresh_count + ' ' + new_title;
					document.title = new_title;
					
					jQuery('.um-notification-b').animate({'bottom':'20px'}).addClass('has-new');
					animateBubble();

			} else {
					new_title = document.title.replace(/\(.*?\)/g, '');
					document.title = new_title;
			}
			
			// We have a new item
			if ( data.unread ) {
				
				var id_ = jQuery(data.unread).attr('data-notification_id');

				if (jQuery('.um-notification[data-notification_id='+id_+']').length == 0 ) {
					jQuery('.um-notification.none').remove();
					jQuery('.um-notification-ajax').prepend( data.unread );
					
					jQuery('.um-notification-b').animate({'bottom':'20px'}).addClass('has-new');
					animateBubble();
					
					if ( jQuery('.um-notification-b').attr('data-show-popup') == 1 ) {
					if ( jQuery('.um-notification-realtime').length ) {
						jQuery('.um-notification-realtime').html( data.unread );
					} else {
						jQuery('body').append('<div class="um-notification-realtime">' + data.unread + '</div>');
					}
					}

					jQuery('.um-notification-live-count').html( parseInt( jQuery('.um-notification-live-count').html() ) + 1 ).show();
			
				} else {

				}
				
			} else {
				
				// nothing new
				jQuery('.um-notification-live-count').html( 0 ).hide();
				
				jQuery('.um-notification-b').animate({'bottom':'-220px'}).removeClass('has-new');
				stopBubble();
				
			}
			
			if ( jQuery('.um-notification.unread').length == 0 ) { // there's really no new notifications
				jQuery('.um-notification-live-count').html(0).hide();
				new_title = document.title.replace(/\(.*?\)/g, '');
				document.title = new_title;
				jQuery('.um-notification-b').animate({'bottom':'-220px'}).removeClass('has-new');
				stopBubble();
			}
			
		}
	});
}

function animateBubble(){
	if ( jQuery('.um-notification-b').length ) {
		jQuery('.um-notification-b').addClass('hvr-pop');
	}
}

function stopBubble() {
	jQuery('.um-notification-b').removeClass('hvr-pop');
}

jQuery(document).ready(function() {
	
	jQuery(document).mouseup(function (e)
	{
		var container = jQuery(".um-notification-live-feed");

		if (!container.is(e.target) // if the target of the click isn't the container...
			&& container.has(e.target).length === 0) // ... nor a descendant of the container
		{
			container.hide();
			jQuery('.um-notification-b').removeClass('toggled');
		}
	});
	
	jQuery(document).on('click', '.um-notification-i-close',function(e){
		e.preventDefault();
		var container = jQuery(".um-notification-live-feed");
		container.hide();
		jQuery('.um-notification-b').removeClass('toggled');
		return false;
	});

	LoadNotifications();
	
	if ( jQuery('.um-notification-ajax').length ) {
		
		if ( jQuery('.um-notification.unread').length == 0 ) { // there's really no new notifications
			jQuery('.um-notification-live-count').html(0).hide();
			new_title = document.title.replace(/\(.*?\)/g, '');
			document.title = new_title;
		}

	}
	
	jQuery(document).on('click', '.um-notification-hide a',function(e){
		e.preventDefault();
		if ( jQuery(this).parents('.um-notification-realtime').length == 0 ) {
			
			var notification_id = jQuery(this).parents('.um-notification').attr('data-notification_id');
			jQuery(this).parents('.um-notification').fadeOut('fast');
			jQuery.ajax({
				url: um_scripts.ajaxurl,
				type: 'post',
				data: { action: 'um_notification_delete_log', notification_id: notification_id },
				success: function(data){

				}
			});
		
		}
		return false;
	});
	
	jQuery(document).on('click', '.um-notification-realtime .um-notification-hide a',function(e){
		e.preventDefault();
		jQuery(this).parents('.um-notification-realtime').fadeOut();
		return false;
	});
	
	jQuery(document).on('click', '.um-notification:not(.none)',function(e){
		var notification_uri = jQuery(this).attr('data-notification_uri');
		if ( notification_uri ) {
			window.location = notification_uri;
		}
	});
	
	jQuery(document).on('mouseenter', '.um-notification:not(.none)',function(e){
		if ( jQuery(this).hasClass('unread') ) { // only if unread
			
		var notification_id = jQuery(this).attr('data-notification_id');
		jQuery('*[data-notification_id='+notification_id+']').addClass('read').removeClass('unread');
		if ( jQuery(this).parents('.um-notification-realtime').length == 0 ) {
		jQuery('.um-notification-realtime').find('.um-notification[data-notification_id='+notification_id+']').parents('.um-notification-realtime').remove();
		}
		var notification = jQuery(this);
		notification.addClass('read').removeClass('unread');
		
		new_live_count = parseInt( jQuery('.um-notification-live-count').html() ) - 1;
		if ( new_live_count < 0 ) {
			new_live_count = 0;
		}
		
		jQuery('.um-notification-live-count').html( new_live_count );

		// Nothing more to see
		if ( new_live_count == 0 ) {
			jQuery('.um-notification-live-count').hide();
			jQuery('.um-notification-b').animate({'bottom':'-220px'}).removeClass('has-new');
			stopBubble();
		}
		
		if ( new_live_count == 0 ) {
			new_title = document.title.replace(/\(.*?\)/g, '');
			document.title = new_title;
		} else {
			new_title = document.title.replace(/\(.*?\)/g, '');
			new_title = '(' + new_live_count + ') ' + new_title;
			document.title = new_title;
		}
		
		jQuery.ajax({
			url: um_scripts.ajaxurl,
			type: 'post',
			data: { action: 'um_notification_mark_as_read', notification_id: notification_id },
			success: function(data){

			}
		});
		
		}
		
	});
	
	if ( jQuery('.um-notification-live-count').length && parseInt( jQuery('.um-notification-live-count').html() ) > 0 ) {
		jQuery('.um-notification-live-count').fadeIn();
	}
	
	if ( jQuery('.um-notification-b').length ) {
		
		if ( jQuery('.um-notification-b').hasClass('left') ) {
			jQuery('.um-notification-live-feed').css({
				bottom: '90px',
				left: '0'
			});
		} else {
			jQuery('.um-notification-live-feed').css({
				bottom: '90px',
				right: '0'
			});
		}
	
		jQuery(document).on('click', '.um-notification-b',function(e){
			e.preventDefault();
			if ( jQuery('.um-notification-live-feed').is(':hidden') ) {
				jQuery('.um-notification-live-feed').show().css({'height': jQuery(window).height() - 90, 'max-height': jQuery(window).height() - 90  });
				jQuery(this).addClass('toggled');
			} else {
				jQuery('.um-notification-live-feed').hide();
				jQuery(this).removeClass('toggled');
			}
			return false;
		});
		
	}
	
});