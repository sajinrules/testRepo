<div class="wrap">
	<h2>
		<?php _e('Simple Social Sent Items','simple_social_inbox');?>
		<a href="?page=simple_social_inbox_compose" class="add-new-h2"><?php _e('Compose','simple_social_inbox');?></a>
	</h2>
    <?php

    $myListTable = new SimpleSocialSentList();
    $myListTable->set_columns( array(
	    'social_column_time'    => __( 'Date/Time', 'simple_social_inbox' ),
	    'social_column_social' => __( 'Social Accounts', 'simple_social_inbox' ),
		'social_column_summary'    => __( 'Summary', 'simple_social_inbox' ),
		'social_column_links'    => __( 'Link Clicks', 'simple_social_inbox' ),
		//'social_column_stats'    => __( 'Stats', 'simple_social_inbox' ),
		//'social_column_action'    => __( 'Action', 'simple_social_inbox' ),
		'social_column_post'    => __( 'WP Post', 'simple_social_inbox' ),
	) );

	/* @var $message_manager ucm_facebook */
	/*foreach($this->message_managers as $message_id => $message_manager){
		$message_manager->load_all_messages($search, $order);
	}*/

    global $wpdb;
    $sql = "SELECT * FROM `"._SIMPLE_SOCIAL_DB_PREFIX."social_message` ORDER BY `social_message_id` DESC ";
    $messages = $wpdb->get_results($sql, ARRAY_A);


	$myListTable->set_message_managers($this->message_managers);
	$myListTable->set_data($messages);
	$myListTable->prepare_items();
    ?>
	<form method="post">
	    <input type="hidden" name="page" value="<?php echo htmlspecialchars($_REQUEST['page']); ?>" />
		<?php
	    $myListTable->display();
		?>
	</form>

	<script type="text/javascript">
	    jQuery(function () {
		    ucm.social.init();
		    <?php foreach($this->message_managers as $message_id => $message_manager){
				$message_manager->init_js();
			} ?>
	    });
	</script>


</div>