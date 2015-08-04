<?php
if(!isset($social_facebook_id) || !isset($social_facebook_message_id)){
	exit;
} ?>

	<?php

if($social_facebook_id && $social_facebook_message_id){
	$facebook = new ucm_facebook_account($social_facebook_id);
    if($social_facebook_id && $facebook->get('social_facebook_id') == $social_facebook_id){
	    $facebook_message = new ucm_facebook_message( $facebook, false, $social_facebook_message_id );
	    if($social_facebook_message_id && $facebook_message->get('social_facebook_message_id') == $social_facebook_message_id && $facebook_message->get('social_facebook_id') == $social_facebook_id){

		    $comments         = $facebook_message->get_comments();
		    $facebook_message->mark_as_read();

		    ?>

			<form action="" method="post" id="facebook_edit_form">
				<div id="facebook_message_header">
					<div style="float:right; text-align: right; margin-top:-4px;">
						<small><?php echo ucm_print_date( $facebook_message->get('last_active'), true ); ?> </small><br/>
					    <?php if($facebook_message->get('status') == _SOCIAL_MESSAGE_STATUS_ANSWERED){  ?>
						    <a href="#" class="socialfacebook_message_action  btn btn-default btn-xs button"
						       data-action="set-unanswered" data-id="<?php echo (int)$facebook_message->get('social_facebook_message_id');?>"><?php _e( 'Inbox' ); ?></a>
					    <?php }else{ ?>
						    <a href="#" class="socialfacebook_message_action  btn btn-default btn-xs button"
						       data-action="set-answered" data-id="<?php echo (int)$facebook_message->get('social_facebook_message_id');?>"><?php _e( 'Archive' ); ?></a>
					    <?php } ?>
					</div>
					<!--<img src="<?php /*echo _BASE_HREF;*/?>includes/plugin_social_facebook/networks/facebook/facebook.png" class="facebook_icon">-->
					<img src="<?php echo plugins_url('networks/facebook/facebook.png', _DTBAKER_PLUGIN_FILE_NAME_20_);?>" class="facebook_icon">
						    <strong><?php _e('Account:');?></strong> <a href="<?php echo $facebook_message->get_link(); ?>"
					           target="_blank"><?php
						if($facebook_message->get('facebook_page_or_group')){
							echo htmlspecialchars( $facebook_message->get('facebook_page_or_group')->get( 'page_name' ) ?: $facebook_message->get('facebook_page_or_group')->get( 'group_name' ) );
						}else{
							echo 'Feed';
						}
						?></a> <br/>
						    <strong><?php _e('Type:');?></strong> <?php echo htmlspecialchars( $facebook_message->get_type_pretty() ); ?>
				</div>
				<div id="facebook_message_holder">
		    <?php
		    $facebook_message->full_message_output(true);
		    ?>
					</div>
		    </form>

	    <?php }
    }
}

if($social_facebook_id && !(int)$social_facebook_message_id){
	$facebook = new ucm_facebook_account($social_facebook_id);
    if($social_facebook_id && $facebook->get('social_facebook_id') == $social_facebook_id){

	    /* @var $pages ucm_facebook_page[] */
	    $pages = $facebook->get('pages');
	    //print_r($pages);
	    ?>
	    <form action="" method="post" enctype="multipart/form-data">
		    <input type="hidden" name="_process" value="send_facebook_message">
			<?php wp_nonce_field( 'send-facebook' . (int) $facebook->get( 'social_facebook_id' ) ); ?>
		    <?php
		    $fieldset_data = array(
			    'heading' => array(
				    'type' => 'h3',
				    'title' => 'Compose Message',
				),
			    'class' => 'tableclass tableclass_form tableclass_full',
			    'elements' => array(
			       'facebook_page' => array(
			            'title' => __('Facebook Page', 'simple_social_inbox'),
			            'fields' => array(),
			        ),
				    'message' => array(
					    'title' => __('Message', 'simple_social_inbox'),
					    'field' => array(
						    'type' => 'textarea',
						    'name' => 'message',
						    'id' => 'facebook_compose_message',
						    'value' => '',
					    ),
				    ),
				    'type' => array(
					    'title' => __('Type', 'simple_social_inbox'),
					    'fields' => array(
						    '<input type="radio" name="post_type" id="post_type_wall" value="wall" checked> ',
						    '<label for="post_type_wall">',
						    __('Wall Post', 'simple_social_inbox'),
						    '</label>',
						    '<input type="radio" name="post_type" id="post_type_link" value="link"> ',
						    '<label for="post_type_link">',
						    __('Link Post', 'simple_social_inbox'),
						    '</label>',
						    '<input type="radio" name="post_type" id="post_type_picture" value="picture"> ',
						    '<label for="post_type_picture">',
						    __('Picture Post', 'simple_social_inbox'),
						    '</label>',
					    ),
				    ),
				    'link' => array(
					    'title' => __('Link', 'simple_social_inbox'),
					    'fields' => array(
						    array(
							    'type' => 'text',
							    'name' => 'link',
							    'id' => 'message_link_url',
							    'value' => '',
						    ),
						    '<div id="facebook_link_loading_message"></div>',
						    '<span class="facebook-type-link facebook-type-option"></span>', // flag for our JS hide/show hack
					    ),
				    ),
				    'link_picture' => array(
					    'title' => __('Link Picture', 'simple_social_inbox'),
					    'fields' => array(
						    array(
							    'type' => 'text',
							    'name' => 'link_picture',
							    'value' => '',
						    ),
						    ('Full URL (eg: http://) to the picture to use for this link preview'),
						    '<span class="facebook-type-link facebook-type-option"></span>', // flag for our JS hide/show hack
					    ),
				    ),
				    'link_name' => array(
					    'title' => __('Link Title', 'simple_social_inbox'),
					    'fields' => array(
						    array(
							    'type' => 'text',
							    'name' => 'link_name',
							    'value' => '',
						    ),
						    ('Title to use instead of the automatically generated one from the Link page'),
						    '<span class="facebook-type-link facebook-type-option"></span>', // flag for our JS hide/show hack
					    ),
				    ),
				    'link_caption' => array(
					    'title' => __('Link Caption', 'simple_social_inbox'),
					    'fields' => array(
						    array(
							    'type' => 'text',
							    'name' => 'link_caption',
							    'value' => '',
						    ),
						    ('Caption to use instead of the automatically generated one from the Link page'),
						    '<span class="facebook-type-link facebook-type-option"></span>', // flag for our JS hide/show hack
					    ),
				    ),
				    'link_description' => array(
					    'title' => __('Link Description', 'simple_social_inbox'),
					    'fields' => array(
						    array(
							    'type' => 'text',
							    'name' => 'link_description',
							    'value' => '',
						    ),
						    ('Description to use instead of the automatically generated one from the Link page'),
						    '<span class="facebook-type-link facebook-type-option"></span>', // flag for our JS hide/show hack
					    ),
				    ),
				    /*'track' => array(
					    'title' => __('Track clicks', 'simple_social_inbox'),
					    'field' => array(
						    'type' => 'check',
						    'name' => 'track_links',
						    'value' => '1',
						    'help' => 'If this is selected, the links will be automatically shortened so we can track how many clicks are received.',
						    'checked' => false,
					    ),
				    ),*/
				    'picture' => array(
					    'title' => __('Picture', 'simple_social_inbox'),
					    'fields' => array(
						    '<input type="file" name="picture" value="">',
						    '<span class="facebook-type-picture facebook-type-option"></span>', // flag for our JS hide/show hack
					    ),
				    ),
				    'schedule' => array(
					    'title' => __('Schedule', 'simple_social_inbox'),
					    'fields' => array(
						    array(
							    'type' => 'date',
							    'name' => 'schedule_date',
							    'value' => '',
						    ),
						    array(
							    'type' => 'time',
							    'name' => 'schedule_time',
							    'value' => '',
						    ),
						    ' ',
						    sprintf(__('Currently: %s','simple_social_inbox'),date('c')),
						    ' (Leave blank to send now, or pick a date in the future.)',
					    ),
				    ),
				    'debug' => array(
					    'title' => __('Debug', 'simple_social_inbox'),
					    'field' => array(
						    'type' => 'check',
						    'name' => 'debug',
						    'value' => '1',
						    'checked' => false,
						    'help' => 'Show debug output while posting the message',
					    ),
				    ),
			    )
			);
		    foreach($pages as $facebook_page_id => $page){
			    $fieldset_data['elements']['facebook_page']['fields'][] =
				    '<div id="facebook_compose_page_select">' .
				    '<input type="checkbox" name="compose_page_id['.$facebook_page_id.']" value="1" checked> ' .
				    '<img src="//graph.facebook.com/'.$facebook_page_id.'/picture"> ' .
				    htmlspecialchars($page->get('page_name')) .
				    '</div>'
			    ;
		    }
			echo module_form::generate_fieldset($fieldset_data);


		    ?>
	    </form>

	    <script type="text/javascript">
		    function change_post_type(){
			    var currenttype = jQuery('[name=post_type]:checked').val();
			    jQuery('.facebook-type-option').each(function(){
				    jQuery(this).parents('tr').first().hide();
			    });
			    jQuery('.facebook-type-'+currenttype).each(function(){
				    jQuery(this).parents('tr').first().show();
			    });

		    }
		    jQuery(function(){
			    jQuery('[name=post_type]').change(change_post_type);
			    jQuery('#message_link_url').change(function(){
				    jQuery('#facebook_link_loading_message').html('<?php _e('Loading URL information...');?>');
				    jQuery.ajax({
					    url: '<?php echo '';?>',
					    data: {_process:'ajax_facebook_url_info', url: jQuery(this).val()},
					    dataType: 'json',
					    success: function(res){
						    jQuery('.facebook-type-link').each(function(){
							    var elm = jQuery(this).parent().find('input');
							    if(res && typeof res[elm.attr('name')] != 'undefined'){
								    elm.val(res[elm.attr('name')]);
							    }
						    });
					    },
					    complete: function(){
						    jQuery('#facebook_link_loading_message').html('');
					    }
				    });
			    });
			    change_post_type();
		    });
	    </script>

	    <?php
    }
}
?>
