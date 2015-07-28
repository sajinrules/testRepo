<?php
	cpm_get_email_header();
	$new      = CPM_URL . '/assets/images/new.png';
	$triangle = CPM_URL . '/assets/images/triangle.png';

    $template_vars = array(
        '%SITE%'         => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
        '%PROJECT_NAME%' => get_post_field( 'post_title', $project_id ),
        '%PROJECT_URL%'  => '<a style="text-decoration: none;" href="'.cpm_url_project_details( $project_id ).'">'.get_post_field( 'post_title', $project_id ).'</a>',
        '%AUTHOR%'       => $user->display_name,
        '%AUTHOR_EMAIL%' => $user->user_email,
        '%TASKLIST_URL%' => '<a style="text-decoration: none;" href="'.cpm_url_single_tasklist($project_id, $list_id).'">'.get_post_field( 'post_title', $list_id ).'</a>',
        '%TASK_URL%'     => '<a style="text-decoration: none;" href="'.cpm_url_single_task( $project_id, $list_id, $task_id ).'">'.get_post_field( 'post_title', $task_id ).'</a>',
        '%TASK%'         => $data['post_content'],
        '%IP%'           => get_ipaddress()
    );

    $subject = cpm_get_option( 'new_task_sub' );
    $message = cpm_get_content( cpm_get_option( 'new_task_body' ) );
  
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
    			<center><div style="font-size: 45px; padding-top: 38px;"><?php _e( 'A NEW TASK', 'cpm' );?></div></center>
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
 