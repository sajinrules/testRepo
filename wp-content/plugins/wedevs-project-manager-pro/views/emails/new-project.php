<?php

    if ( isset( $_POST['project_notify'] ) && $_POST['project_notify'] == 'yes' ) {
        $project_users = CPM_Project::getInstance()->get_users( $project_id );
        $users = array();

        if( is_array( $project_users ) && count($project_users) ) {
            foreach ($project_users as $user_id => $role_array ) {
                if( $this->filter_email( $user_id ) ) {
                   $users[$user_id] = sprintf( '%s', $role_array['email'] ); 
                   // $users[$user_id] = sprintf( '%s (%s)', $role_array['name'], $role_array['email'] ); 
                }
            }
        }
        //if any users left, get their mail addresses and send mail
        if ( $users ) {

        	cpm_get_email_header();
			$new      = CPM_URL . '/assets/images/new.png';
			$triangle = CPM_URL . '/assets/images/triangle.png';

            $template_vars = array(
                '%SITE%'            => wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
                '%PROJECT_NAME%'    => $data['post_title'],
                '%PROJECT_DETAILS%' => $data['post_content'],
                '%PROJECT_URL%'  => '<a style="text-decoration: none;" href="'.cpm_url_project_details( $project_id ).'">'.get_post_field( 'post_title', $project_id ).'</a>',
            );

            $subject = cpm_get_option( 'new_project_sub' );
            $message = cpm_get_content( cpm_get_option( 'new_project_body' ) );

            // subject
            foreach ($template_vars as $key => $value) {
                $subject = str_replace( $key, $value, $subject );
            }

            // message
            foreach ($template_vars as $key => $value) {
                $message = str_replace( $key, $value, $message );
            }

           
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
	    			<center><div style="font-size: 45px; padding-top: 38px;"><?php _e( 'A NEW PROJECT', 'cpm' );?></div></center>
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

        <?php
    }


?>