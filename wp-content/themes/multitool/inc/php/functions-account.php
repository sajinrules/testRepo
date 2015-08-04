<?php
	// php file for template functions which are needed for account related content

function get_userimg_url( $id_or_email, $args = null ) {
    $original_args = $args;
    $args = wp_parse_args( $args, array(
        'size'           => 250,
        'default'        => get_option( 'avatar_default', 'mystery' ),
        'force_default'  => false,
        'rating'         => get_option( 'avatar_rating' ),
        'scheme'         => null,
        'processed_args' => null, // if used, should be a reference
    ) );
    if ( is_numeric( $args['size'] ) ) {
        $args['size'] = absint( $args['size'] );
        if ( !$args['size'] ) {
            $args['size'] = 250;
        }
    } else {
        $args['size'] = 250;
    }
    if ( empty( $args['default'] ) ) {
        $args['default'] = 'mystery';
    }
    switch ( $args['default'] ) {
    case 'mm' :
    case 'mystery' :
    case 'mysteryman' :
        $args['default'] = 'mm';
        break;
    case 'gravatar_default' :
        $args['default'] = false;
        break;
    }
    $args['force_default'] = (bool) $args['force_default'];
    $args['rating'] = strtolower( $args['rating'] );
    $args['found_avatar'] = false;
    $url = apply_filters_ref_array( 'pre_get_avatar_url', array( null, $id_or_email, &$args, $original_args ) );
    if ( !is_null( $url ) ) {
        $return = apply_filters_ref_array( 'get_avatar_url', array( $url, $id_or_email, &$args, $original_args ) );
        $args['processed_args'] = $args;
        unset( $args['processed_args']['processed_args'] );
        return $return;
    }
    $email_hash = '';
    $user = $email = false;
    if ( is_numeric( $id_or_email ) ) {
        $user = get_user_by( 'id', absint( $id_or_email ) );
    } elseif ( is_string( $id_or_email ) ) {
        if ( strpos( $id_or_email, '@md5.gravatar.com' ) ) {
            // md5 hash
                list( $email_hash ) = explode( '@', $id_or_email );
        } else {
            // email address
            $email = $id_or_email;
        }
    } elseif ( is_object( $id_or_email ) ) {
        if ( isset( $id_or_email->comment_ID ) ) {
            // Comment Object
            // No avatar for pingbacks or trackbacks
            $allowed_comment_types = apply_filters( 'get_avatar_comment_types', array( 'comment' ) );
            if ( ! empty( $id_or_email->comment_type ) && ! in_array( $id_or_email->comment_type, (array) $allowed_comment_types ) ) {
                $args['processed_args'] = $args;
                unset( $args['processed_args']['processed_args'] );
                return false;
            }
            if ( ! empty( $id_or_email->user_id ) ) {
                $user = get_user_by( 'id', (int) $id_or_email->user_id );
            }
            if ( ( !$user || is_wp_error( $user ) ) && ! empty( $id_or_email->comment_author_email ) ) {
                $email = $id_or_email->comment_author_email;
            }
        } elseif ( ! empty( $id_or_email->user_login ) ) {
            // User Object
            $user = $id_or_email;
        } elseif ( ! empty( $id_or_email->post_author ) ) {
            // Post Object
            $user = get_user_by( 'id', (int) $id_or_email->post_author );
        }
    }
 
    if ( !$email_hash ) {
        if ( $user ) {
            $email = $user->user_email;
        }
        if ( $email ) {
            $email_hash = md5( strtolower( trim( $email ) ) );
        }
    }
    if ( $email_hash ) {
        $args['found_avatar'] = true;
    }
    $url_args = array(
        's' => $args['size'],
        'd' => $args['default'],
        'f' => $args['force_default'] ? 'y' : false,
        'r' => $args['rating'],
    );
    
    $url = sprintf( 'http://%d.gravatar.com/avatar/%s', hexdec( $email_hash[0] ) % 3, $email_hash );
    $url = add_query_arg(
        rawurlencode_deep( array_filter( $url_args ) ),
        set_url_scheme( $url, $args['scheme'] )
    );
    
    $return = apply_filters_ref_array( 'get_avatar_url', array( $url, $id_or_email, &$args, $original_args ) );
    $args['processed_args'] = $args;
    unset( $args['processed_args']['processed_args'] );
    return $return;
}

function getAccountBlock(){
	global $ultimatemember;
	$wp_user = wp_get_current_user(); 
	um_fetch_user($wp_user->ID);
?>
	<div class="profile-sidebar">					
	<!-- PORTLET MAIN -->
	<div class="portlet light profile-sidebar-portlet">
		<!-- SIDEBAR USERPIC -->
		<div class="profile-userpic">
			<img src="<?php echo get_userimg_url($wp_user->ID); ?>" class="img-responsive" alt="">
		</div>
		<!-- END SIDEBAR USERPIC -->
		<!-- SIDEBAR USER TITLE -->
		<div class="profile-usertitle">
			<div class="profile-usertitle-name">
				 <?php echo um_user('display_name'); ?>
			</div>
			<div class="profile-usertitle-job">
				 <?php echo um_user('functie'); ?>
			</div>
		</div>
		<!-- END SIDEBAR USER TITLE -->
		<!-- SIDEBAR BUTTONS -->
		<div class="profile-userbuttons">
			<button type="button" class="btn btn-circle green-haze btn-sm">Follow</button>
			<button type="button" class="btn btn-circle btn-danger btn-sm">Message</button>
		</div>
		<!-- END SIDEBAR BUTTONS -->
		<!-- SIDEBAR MENU -->
		<div class="profile-usermenu">
			<?php do_action('um_account_display_tabs_hook', $args ); ?>
		</div>
		<!-- END MENU -->
	</div>
	<!-- END PORTLET MAIN -->
	<!-- PORTLET MAIN -->
	<div class="portlet light">
		<!-- STAT -->
		<div class="row list-separated profile-stat">
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title">
					 37
				</div>
				<div class="uppercase profile-stat-text">
					 Projects
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title">
					 51
				</div>
				<div class="uppercase profile-stat-text">
					 Tasks
				</div>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-6">
				<div class="uppercase profile-stat-title">
					 61
				</div>
				<div class="uppercase profile-stat-text">
					 Uploads
				</div>
			</div>
		</div>
		<!-- END STAT -->
		<div>
			<h4 class="profile-desc-title">About Marcus Doe</h4>
			<span class="profile-desc-text"> Lorem ipsum dolor sit amet diam nonummy nibh dolore. </span>
			<div class="margin-top-20 profile-desc-link">
				<i class="fa fa-globe"></i>
				<a href="http://www.keenthemes.com">www.keenthemes.com</a>
			</div>
			<div class="margin-top-20 profile-desc-link">
				<i class="fa fa-twitter"></i>
				<a href="http://www.twitter.com/keenthemes/">@keenthemes</a>
			</div>
			<div class="margin-top-20 profile-desc-link">
				<i class="fa fa-facebook"></i>
				<a href="http://www.facebook.com/keenthemes/">keenthemes</a>
			</div>
		</div>
	</div>
	<!-- END PORTLET MAIN -->
	</div>	
	<?php
}


// allow admins (not only superadmins) to edit users:
// http://thereforei.am/2011/03/15/how-to-allow-administrators-to-edit-users-in-a-wordpress-network/

function mc_admin_users_caps( $caps, $cap, $user_id, $args ){
 
    foreach( $caps as $key => $capability ){
 
        if( $capability != 'do_not_allow' )
            continue;
 
        switch( $cap ) {
            case 'edit_user':
            case 'edit_users':
                $caps[$key] = 'edit_users';
                break;
            case 'delete_user':
            case 'delete_users':
                $caps[$key] = 'delete_users';
                break;
            case 'create_users':
                $caps[$key] = $cap;
                break;
        }
    }
 
    return $caps;
}
add_filter( 'map_meta_cap', 'mc_admin_users_caps', 1, 4 );
remove_all_filters( 'enable_edit_any_user_configuration' );
add_filter( 'enable_edit_any_user_configuration', '__return_true');
 
/**
 * Checks that both the editing user and the user being edited are
 * members of the blog and prevents the super admin being edited.
 */
function mc_edit_permission_check() {
    global $current_user, $profileuser;
 
    $screen = get_current_screen();
 
    get_currentuserinfo();
 
    if( ! is_super_admin( $current_user->ID ) && in_array( $screen->base, array( 'user-edit', 'user-edit-network' ) ) ) { // editing a user profile
        if ( is_super_admin( $profileuser->ID ) ) { // trying to edit a superadmin while less than a superadmin
            wp_die( __( 'You do not have permission to edit this user.' ) );
        } elseif ( ! ( is_user_member_of_blog( $profileuser->ID, get_current_blog_id() ) && is_user_member_of_blog( $current_user->ID, get_current_blog_id() ) )) { // editing user and edited user aren't members of the same blog
            wp_die( __( 'You do not have permission to edit this user.' ) );
        }
    }
 
}
add_filter( 'admin_head', 'mc_edit_permission_check', 1, 4 );