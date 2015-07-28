<?php

define('TEXTDOMAIN', 'BigJunior');
define('THEME_SLUG', 'BJ');

if(is_admin()) {
add_filter('filesystem_method', create_function('$a', 'return "direct";' ));
define( 'FS_CHMOD_DIR', 0751 );
}

/*
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
// === end wecross edit
*/
/**************************************************
	FOLDERS
**************************************************/

define('THEME_DIR',         get_template_directory());
define('THEME_LIB',			THEME_DIR . '/lib');
define('THEME_INCLUDES',    THEME_LIB . '/includes');
define('THEME_ADMIN',		THEME_LIB . '/admin');
define('THEME_LANGUAGES',	THEME_LIB . '/languages');
define('THEME_CACHE',	    THEME_DIR . '/cache');
define('THEME_ASSETS',   	THEME_DIR . '/assets');
define('THEME_PLUGINS',		THEME_DIR . '/plugins');
define('THEME_JS',			THEME_ASSETS . '/js');
define('THEME_CSS',			THEME_ASSETS . '/css');
define('THEME_IMAGES',		THEME_ASSETS . '/img');


/**************************************************
	FOLDER URI
**************************************************/

define('THEME_URI',		    	get_template_directory_uri());
define('THEME_LIB_URI',		    THEME_URI . '/lib');
define('THEME_ADMIN_URI',	    THEME_LIB_URI . '/admin');
define('THEME_LANGUAGES_URI',	THEME_LIB_URI . '/languages');
define('THEME_PLUGINS_URI',	    THEME_URI . '/plugins');
define('THEME_CACHE_URI',	    THEME_URI     . '/cache');
define('THEME_ASSETS_URI',	    THEME_URI     . '/assets');
define('THEME_JS_URI',			THEME_ASSETS_URI . '/js');
define('THEME_CSS_URI',			THEME_ASSETS_URI . '/css');
define('THEME_IMAGES_URI',		THEME_ASSETS_URI . '/img');

/**************************************************
	Text Domain
**************************************************/

load_theme_textdomain( TEXTDOMAIN, THEME_DIR . '/languages' );

/**************************************************
	Content Width
**************************************************/

if ( !isset( $content_width ) ) $content_width = 1170;

/**************************************************
	LIBRARIES
**************************************************/

require_once(THEME_LIB . '/framework.php');

function printpre($content){
	echo "<pre>";
	print_r($content);
	echo "</pre>";	
}

function my_login_changes() { ?>
    <style type="text/css">
        body.login {
            background: url('<?php echo get_bloginfo('template_url'); ?>/images/wecross_bg.jpg') no-repeat center center fixed !important; 
			  -webkit-background-size: cover !important;
			  -moz-background-size: cover !important;
			  -o-background-size: cover !important;
			  background-size: cover !important;
        }
        
        #login{
	        background-color: white;
	        background-color: rgba(255,255,255,0.7);
        }
        
        #login h1{
	        display: block;
	        background-image: url('<?php echo get_bloginfo('template_url'); ?>/images/menu-logo1.png'); 
	        background-position: center center;
	        background-repeat: no-repeat;
        }
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_changes' );

function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit) {
  array_pop($words);
  //add a ... at last article when more than limit word count
  echo implode(' ', $words)."..."; } else {
  //otherwise
  echo implode(' ', $words); }
}
