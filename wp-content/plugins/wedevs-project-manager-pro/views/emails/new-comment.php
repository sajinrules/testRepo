<?php

	$users = $this->prepare_contacts();

    if ( !$users ) {
        return;
    }

    cpm_get_email_header();
	$new      = CPM_URL . '/assets/images/new.png';
	$triangle = CPM_URL . '/assets/images/triangle.png';

	$msg_obj     = CPM_Message::getInstance();
	$parent_post = get_post( $data['comment_post_ID'] );
	$author      = wp_get_current_user();
	$comment_url = '';

    switch ($parent_post->post_type) {
        case 'message':
            $comment_url = cpm_url_single_message( $project_id, $data['comment_post_ID'] );
            break;

        case 'task_list':
            $comment_url = cpm_url_single_tasklist( $project_id, $parent_post->ID );
            break;

        case 'task':
            $comment_url = cpm_url_single_task( $project_id, $parent_post->post_parent, $parent_post->ID );
            break;
    }

    $template_vars = array(
        '%SITE%'         => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
        '%PROJECT_NAME%' => get_post_field( 'post_title', $project_id ),
        '%PROJECT_URL%'  => '<a style="text-decoration: none;" href="'.cpm_url_project_details( $project_id ).'">'.get_post_field( 'post_title', $project_id ).'</a>',
        '%AUTHOR%'       => $author->display_name,
        '%AUTHOR_EMAIL%' => $author->user_email,
        '%COMMENT_URL%'  => '<a style="text-decoration: none;" href="'.$comment_url .'/#cpm-comment-'.$comment_id.'">'.__( 'comment link', 'cpm' ).'</a>',
        '%COMMENT%'      => $data['comment_content'],
        '%IP%'           => get_ipaddress()
    );

    $subject = cpm_get_option( 'new_comment_sub' );
    $message = cpm_get_content( cpm_get_option( 'new_comment_body' ) );

    // subject
    foreach ($template_vars as $key => $value) {
        $subject = str_replace( $key, $value, $subject );
    }

    // message
    foreach ($template_vars as $key => $value) {
        $message = str_replace( $key, $value, $message );
    }
?>

  	<table width="600" style="margin-top: 50px; background: #fff;">
    	<tr>
    		<td>
    			<center><img src="<?php echo $new; ?>"/></center>
    		</td>
    	</tr> 
    	<tr>
    		<td>
    			<center><div style="font-size: 45px; padding-top: 38px;"><?php _e( 'A NEW COMMENT', 'cpm' );?></div></center>
    		</td>
    	</tr>
    </table>

    <table cellspacing="0" width="600" style="margin-top: 50px;">
		<!-- <tr>
			<td style="position: relative;"><img style="position: absolute; left: 48%; top: -8px;" src="<?php echo $triangle; ?>"/></td>
		</tr> -->
		<tr>
			<td style="background: #eee;  padding-top: 5px; padding-bottom: 5px;">
				<center>
					<table width="560" style="border-collapse:separate; border-spacing:0 20px;">
				
					        <tr>
					        	<td style="width: 560px; color: #717171; text-align: center; line-height: 30px;">
					        		<center><?php echo $message; ?></center>
					        	</td>
					        </tr>

					</table>
				</center>
			</td>
		</tr>
		<tr>
			<td><?php cpm_get_email_footer(); ?></td>
		</tr>
	</table>