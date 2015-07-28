<?php
load_theme_textdomain( 'multitool', get_bloginfo('template_url').'/languages' );
include( get_template_directory() . '/inc/php/functions-menu.php' );
include( get_template_directory() . '/inc/php/functions-account.php' );
include( get_template_directory() . '/inc/php/functions-tasks.php' );
include( get_template_directory() . '/inc/php/functions-campaigns.php' );
include( get_template_directory() . '/inc/php/functions-productions.php' );
include( get_template_directory() . '/inc/php/functions-artists.php' );
include( get_template_directory() . '/inc/php/functions-companies.php' );
include( get_template_directory() . '/inc/php/functions-knowledge.php' );

if (!is_ssl()) {
 $_SERVER['HTTPS'] = false;
}

add_action( 'init', 'my_deregister_heartbeat', 1 );
function my_deregister_heartbeat() {
	global $pagenow;
	if ( 'post.php' != $pagenow && 'post-new.php' != $pagenow  && 'edit.php' != $pagenow )
		wp_deregister_script('heartbeat');
}

function wptuts_heartbeat_settings( $settings ) {
    $settings['interval'] = 60; //Anything between 15-60
    return $settings;
}
add_filter( 'heartbeat_settings', 'wptuts_heartbeat_settings' );

function wptuts_respond_to_browser( $response, $data, $screen_id ) {

    // Slow the hearbeat
    $response['heartbeat_interval'] = 'slow';
    return $response;
}
add_filter( 'heartbeat_received', 'wptuts_respond_to_browser', 10, 3 ); // Logged in users
add_filter( 'heartbeat_nopriv_received', 'wptuts_respond_to_browser', 10, 3 ); // Logged out users

function sortarray($a,$subkey,$direction) {
	$b = array();
	$c = array();
	
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	if($direction != 'asc'){
		arsort($b);
	}
	else
	{
		asort($b);
	}
	
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}
	
function in_array_ci($needle, $haystack) 
{
	return in_array( strtolower($needle), array_map('strtolower', $haystack) );
}

function printpre($content){
	echo "<pre>";
	print_r($content);
	echo "</pre>";
}

function subval_sort($a,$subkey) {
	foreach($a as $k=>$v) {
		$b[$k] = strtolower($v[$subkey]);
	}
	asort($b);
	foreach($b as $key=>$val) {
		$c[] = $a[$key];
	}
	return $c;
}

function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

function loopAndFind($array, $index, $search){
     $returnArray = array();
     foreach($array as $k=>$v){
           if($v[$index] == $search){   
                $returnArray[] = $v;
           }
     }
     return $returnArray;
}



function is_valid_urlstring($url)
{
    if (!($url = @parse_url($url)))
    {
        return false;
    }

    $url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];
    $url['path'] = (!empty($url['path'])) ? $url['path'] : '/';
    $url['path'] .= (isset($url['query'])) ? "?$url[query]" : '';

    if (isset($url['host']) AND $url['host'] != @gethostbyname($url['host']))
    {
        if (PHP_VERSION >= 5)
        {
            $headers = @implode('', @get_headers("$url[scheme]://$url[host]:$url[port]$url[path]"));
        }
        else
        {
            if (!($fp = @fsockopen($url['host'], $url['port'], $errno, $errstr, 10)))
            {
                return false;
            }
            fputs($fp, "HEAD $url[path] HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
            $headers = fread($fp, 4096);
            fclose($fp);
        }
        return (bool)preg_match('#^HTTP/.*\s+[(200|301|302)]+\s#i', $headers);
    }
    return false;
}

function url_exists($url)
{
    $url = @parse_url($url);

    if (!$url)
    {
        return false;
    }

    $url = array_map('trim', $url);
    $url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];

    $path = (isset($url['path'])) ? $url['path'] : '/';
    $path .= (isset($url['query'])) ? "?$url[query]" : '';

    if (isset($url['host']) AND $url['host'] != gethostbyname($url['host']))
    {
        if (PHP_VERSION >= 5)
        {
            $headers = implode('', get_headers("$url[scheme]://$url[host]:$url[port]$path"));
        }
        else
        {
            $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);

            if (!$fp)
            {
                return false;
            }
            fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
            $headers = fread($fp, 4096);
            fclose($fp);
        }
        //echo " $headers ";
        return (bool)preg_match('#^HTTP/.*\s+[(200|301|302|304)]+\s#i', $headers);
    }
    return false;
}


function url_getheaders($url)
{
    $url = @parse_url($url);

    if (!$url)
    {
        return false;
    }

    $url = array_map('trim', $url);
    $url['port'] = (!isset($url['port'])) ? 80 : (int)$url['port'];

    $path = (isset($url['path'])) ? $url['path'] : '/';
    $path .= (isset($url['query'])) ? "?$url[query]" : '';

    if (isset($url['host']) AND $url['host'] != gethostbyname($url['host']))
    {
        if (PHP_VERSION >= 5)
        {
            $headers = implode('', get_headers("$url[scheme]://$url[host]:$url[port]$path"));
        }
        else
        {
            $fp = fsockopen($url['host'], $url['port'], $errno, $errstr, 30);

            if (!$fp)
            {
                return false;
            }
            fputs($fp, "HEAD $path HTTP/1.1\r\nHost: $url[host]\r\n\r\n");
            $headers = fread($fp, 4096);
            fclose($fp);
        }
        //echo $headers;
        echo (bool)preg_match('#^HTTP/.*\s+[(200|301|302|304)]+\s#i', $headers);
    }
    //return false;
}


function curPageURL() {
	$pageURL = 'http';
	if (@$_SERVER["HTTPS"] == "on")
	{
		$pageURL .= "s";
	}
	
	$pageURL .= "://";
	
	if ($_SERVER["SERVER_PORT"] != "80") 
	{
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	}
	else 
	{
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	
	return $pageURL;
}

function curPageName() {
	return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
}

/* registering top menu locations */

function register_my_menus() {
  register_nav_menu('top-menu-actions',__( 'Acties Menu' ));
  register_nav_menu('top-menu-profile',__( 'Profiel Menu' ));
  register_nav_menu('left-top',__( 'Top Menu - links' ));
  register_nav_menu('left-menu-main',__( 'Linker Hoofd Menu' ));
}
add_action( 'init', 'register_my_menus' );

class mainmenu_left_walker extends Walker_Nav_Menu
{
    function end_el(&$output, $item, $depth=0, $args=array()) 
    { 
	   // create title span for items on mainmenuleft
	   $output = str_ireplace("<span>", "<span class='title'>", $output);
    }
}

add_action( 'widgets_init', 'wecross_admin_widgets_init' );
function wecross_admin_widgets_init() {
    register_sidebar( array(
        'name' => __( 'Dashboard Widgets', 'wecross_admin' ),
        'id' => 'dashboard-main',
        'description' => __( 'Widgets in this area will be shown on all posts and pages.', 'wecross_admin' ),
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li>',
	'before_title'  => '<h2 class="widgettitle">',
	'after_title'   => '</h2>',
    ) );
}

/**
 * Function added by Jp on 10/07/2015
 * for disabling the admin bar from the front end top header 
 **/
 
 // Disable Admin Bar for everyone
if (!function_exists('df_disable_admin_bar')) {

	function df_disable_admin_bar() {
		
		// for the admin page
		remove_action('admin_footer', 'wp_admin_bar_render', 1000);
		// for the front-end
		remove_action('wp_footer', 'wp_admin_bar_render', 1000);
	  	
		// css override for the admin page
		function remove_admin_bar_style_backend() { 
			echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
		}	  
		add_filter('admin_head','remove_admin_bar_style_backend');
		
		// css override for the frontend
		function remove_admin_bar_style_frontend() {
			echo '<style type="text/css" media="screen">
			html { margin-top: 0px !important; }
			* html body { margin-top: 0px !important; }
			</style>';
		}
		add_filter('wp_head','remove_admin_bar_style_frontend', 99);
  	}
}
add_action('init','df_disable_admin_bar');
 
add_action('um_pre_profile_shortcode', 'um_pre_profile_shortcode_plug');
function um_pre_profile_shortcode_plug($args){
	 
		global $ultimatemember;
		extract( $args );
		//$host  = $_SERVER['HTTP_HOST'];
		//$uri= $_SERVER['REQUEST_URI'];
		//$url="http://$host$uri";
		 
		
		if ( $mode == 'profile' && $ultimatemember->fields->editing == false ) {
			$ultimatemember->fields->viewing = 1;
			
			if ( um_get_requested_user() ) {
				if ( !um_can_view_profile( um_get_requested_user() ) ) um_redirect_home();
				if ( !um_current_user_can('edit', um_get_requested_user() ) ) $ultimatemember->user->cannot_edit = 1;
				um_fetch_user( um_get_requested_user() );
			} else {
				//header("Location: http://$host.$uri");
				//exit;
				wp_redirect(get_option('siteurl').'/account-2/?profiletab=main&um_action=edit');
				echo '<span class="alert alert-success">Profile updated successfully</span>';
				if ( !is_user_logged_in() ) um_redirect_home();
				if ( !um_user('can_edit_profile') ) $ultimatemember->user->cannot_edit = 1;
			}
			
		}

		if ( $mode == 'profile' && $ultimatemember->fields->editing == true ) {
			$ultimatemember->fields->editing = 1;
		
			if ( um_get_requested_user() ) {
				if ( !um_current_user_can('edit', um_get_requested_user() ) ) um_redirect_home();
				um_fetch_user( um_get_requested_user() );
			}
			
		}
		
	}

	/**
	 * Functionality for user profile avatar editting
	 *
	 **/
	add_action('um_pre_header_editprofile', 'um_add_edit_icon_avatar' );
	function um_add_edit_icon_avatar( $args ) {
		
		global $ultimatemember;
		$output = '';
		
		if ( !is_user_logged_in() ) return; // not allowed for guests
		
		if ( isset( $ultimatemember->user->cannot_edit ) && $ultimatemember->user->cannot_edit == 1 ) return; // do not proceed if user cannot edit
		
		if ( $ultimatemember->fields->editing == true ) {
		
		?>
			
		<div class="um-profile-edit um-profile-headericon">
		
			<a href="#" class="um-profile-edit-a um-profile-save"><i class="um-faicon-check"></i></a>
		
		</div>
		
		<?php } else { ?>
		
		<div class="um-profile-edit um-profile-headericon">
		
			<a href="#" class="um-profile-edit-a"><i class="um-faicon-cog"></i></a>
		
			<?php
			
			$items = array(
				'editprofile' => '<a href="'.um_edit_profile_url().'" class="real_url">'.__('Edit Profile','ultimatemember').'</a>',
				'myaccount' => '<a href="'.um_get_core_page('account').'" class="real_url">'.__('My Account','ultimatemember').'</a>',
				'logout' => '<a href="'.um_get_core_page('logout').'" class="real_url">'.__('Logout','ultimatemember').'</a>',
				'cancel' => '<a href="#" class="um-dropdown-hide">'.__('Cancel','ultimatemember').'</a>',
			);
			
			$cancel = $items['cancel'];
				
			if ( !um_is_myprofile() ) {
				
				$actions = $ultimatemember->user->get_admin_actions();
				
				unset( $items['myaccount'] );
				unset( $items['logout'] );
				unset( $items['cancel'] );
				
				if ( is_array( $actions ) ) {
				$items = array_merge( $items, $actions );
				}
				
				$items = apply_filters('um_profile_edit_menu_items', $items, um_profile_id() );
				
				$items['cancel'] = $cancel;

			} else {
			
				$items = apply_filters('um_myprofile_edit_menu_items', $items );
				
			}
			
			echo $ultimatemember->menu->new_ui( $args['header_menu'], 'div.um-profile-edit', 'click', $items );
			
			?>
		
		</div>
		
		<?php
		}
		
	}
	
	/**
	 * 
	 *
	 **/
	
	
/*
add_action('um_is_core_uri', 'templateRedirect');
function templateRedirect()
{
     wp_redirect( 'http://www.example.com', 301 ); exit;
}*/
 
// add our function to template_redirect hook
