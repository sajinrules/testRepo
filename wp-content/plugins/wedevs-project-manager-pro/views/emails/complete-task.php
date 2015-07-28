<?php
cpm_get_email_header();
$complete = CPM_URL . '/assets/images/complete.png';
$arrow    = CPM_URL . '/assets/images/arrow.png';
$triangle = CPM_URL . '/assets/images/triangle.png';
?>

<table cellspacing="0" width="600">
	<tr>
		<td><center><img style="padding-top: 50px; padding-bottom: 25px;" src="<?php echo $complete; ?>"></center></td>
	</tr>
	<tr>
		<td><center><div style="padding-bottom: 25px; font-size: 29px; color: #acacab;"><?php _e( 'A NEW TASK', 'cpm' ); ?></div></center></td>
	</tr>
	<tr>
		<td><center><div style="padding-bottom: 25px; font-size: 29px; font-weight: 800; color: #878786;"><?php _e( 'Has Been Completed', 'cpm' ); ?></div></center></td>
	</tr>
	<tr>
		<td><center><img style="padding-bottom: 25px;" src="<?php echo $arrow; ?>"></center></td>
	</tr>
	<tr>
		<td><center><div style="padding-bottom: 25px; font-size: 23px;"><?php _e( 'Completed Task', 'cpm' ); ?></div></center></td>
	</tr>

</table>



<?php

$project_users = CPM_Project::getInstance()->get_users( $project_id );
$users = array();

if( is_array( $project_users ) && count($project_users) ) {
    foreach ($project_users as $user_id => $role_array ) {
        if( $role_array['role'] == 'manager' ) {
            if( $this->filter_email( $user_id ) ) {
                // $users[$user_id] = sprintf( '%s (%s)', $role_array['name'], $role_array['email'] );
                $users[$user_id] = sprintf( '%s', $role_array['email'] );
            }
        }
    }
}
if ( $users ) {
    $template_vars = array(
        '%SITE%'         => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
        '%PROJECT_NAME%' => get_post_field( 'post_title', $project_id ),
        '%PROJECT_URL%'  => '<a style="text-decoration: none;" href="'.cpm_url_project_details( $project_id ).'">'.get_post_field( 'post_title', $project_id ).'</a>',
        '%TASKLIST_URL%' => '<a style="text-decoration: none;" href="'.cpm_url_single_tasklist($project_id, $list_id).'"">'.get_post_field( 'post_title', $list_id ) .'</a>',
        '%TASK_URL%'     => '<a style="text-decoration: none;" href="'.cpm_url_single_task( $project_id, $list_id, $task_id ).'">'.$data->post_content.'</a>',
        '%TASK%'         => $data->post_content,
        '%IP%'           => get_ipaddress()
    );

    $subject = cpm_get_option( 'complete_task_sub' );
    $message = cpm_get_content( cpm_get_option( 'completed_task_body' ) );
    
    // subject
    foreach ($template_vars as $key => $value) {
        $subject = str_replace( $key, $value, $subject );
    }

    foreach ($template_vars as $key => $value) {
		$message = str_replace( $key, $value, $message );
	}
}
?>
<table cellspacing="0" width="600">
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
