<div class="um-message-conv">

	<?php
	
	$i = 0; if ( $conversations ) {
		 foreach( $conversations as $conversation ) {

		if ( $conversation->user_a == um_profile_id() ) {
			$user = $conversation->user_b;
		} else {
			$user = $conversation->user_a;
		}
	
		if ( !$ultimatemember->user->user_exists_by_id( $user ) ) continue;
		if ( $um_messaging->api->blocked_user( $user ) ) continue;
		if ( $um_messaging->api->hidden_conversation( $conversation->conversation_id ) ) continue;
		
		$i++;
		
		if ( $i == 1 && !isset( $current_conversation ) ) {
			$current_conversation = $conversation->conversation_id;
		}
		
		um_fetch_user( $user );
	
		$user_name = um_user('display_name');
		
		$is_unread = $um_messaging->api->unread_conversation( $conversation->conversation_id, um_profile_id() );
		
	?>
	
	<a href="<?php echo add_query_arg('conversation_id', $conversation->conversation_id ); ?>" class="um-message-conv-item <?php if ( $conversation->conversation_id == $current_conversation ) echo 'active '; ?>" data-message_to="<?php echo $user; ?>" data-trigger_modal="conversation" data-conversation_id="<?php echo $conversation->conversation_id; ?>">
	
		<span class="um-message-conv-name"><?php echo $user_name; ?></span>
		
		<span class="um-message-conv-pic"><?php echo get_avatar( $user, 40 ); ?></span>
		
		<?php if ( $is_unread ) { ?><span class="um-message-conv-new"><i class="um-faicon-circle"></i></span><?php } ?>
		
		<?php do_action('um_messaging_conversation_list_name'); ?>
		
	</a>
	
	<?php 
	
		} 
	
	} 
	
	?>
	
</div>

<div class="um-message-conv-view">

	<?php $i = 0; if ( $conversations ) { foreach( $conversations as $conversation ) { 
	
		if ( isset( $current_conversation ) && $current_conversation != $conversation->conversation_id )
			continue;

		if ( $conversation->user_a == um_profile_id() ) {
			$user = $conversation->user_b;
		} else {
			$user = $conversation->user_a;
		}
		
		if ( $um_messaging->api->blocked_user( $user ) ) continue;
		if ( $um_messaging->api->hidden_conversation( $conversation->conversation_id ) ) continue;
		
		$i++; if ( $i > 1 ) continue;
		
		um_fetch_user( $user );
		$user_name = um_user('display_name');
		
		$um_messaging->api->conversation_template( $user, $user_id );
		
		}
	}
	
	?>

</div><div class="um-clear"></div>

<?php if ( $i == 0 ) { ?>

<div class="um-message-noconv"><span><?php _e('You do not have any conversations yet.','um-messaging'); ?></span></div>

<?php } ?>