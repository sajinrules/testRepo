<?php
/*
 * Plugin Name: Simple Social Inbox
 * Version: 1.6
 * Plugin URI: http://simplesocialinbox.com
 * Description: Manage your Social accounts in an easy to use Inbox
 * Author: dtbaker
 * Author URI: http://dtbaker.net
 * Requires at least: 3.8
 * Tested up to: 4.0
 *
 * Version 1.1 - 2014-04-13 - initial release
 * Version 1.2 - 2014-04-15 - php5.3 __DIR__ fix
 * Version 1.3 - 2014-05-01 - cron bug fix, google+ initial version
 * Version 1.4 - 2014-05-05 - google+ improvements.
 * Version 1.5 - 2014-05-06 - google+ improvements, facebook bug fix.
 * Version 1.51 - 2014-05-28 - google+ fix for php open_basedir / safe mode hosting accounts
 * Version 1.52 - 2014-05-29 - headers_sent() bug fix
 * Version 1.53 - 2014-06-09 - https fix
 * Version 1.54 - 2014-07-11 - custom Facebook App settings
 * Version 1.55 - 2014-09-03 - stripping WP shortcodes from messages
 * Version 1.56 - 2014-11-04 - fix for Google+ page names
 * Version 1.561 - 2014-11-04 - fix for automatic updates
 * Version 1.562 - 2014-11-17 - Google+ GAPS cookie fix to help login
 * Version 1.6 - 2015-03-26 - LinkedIn Initial Support Added
 *
 * @package SimpleSocialInbox
 * @author dtbaker
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

defined('__DIR__') or define('__DIR__', dirname(__FILE__));

define('_SOCIAL_MESSAGE_STATUS_UNANSWERED',0);
define('_SOCIAL_MESSAGE_STATUS_ANSWERED',1);
define('_SOCIAL_MESSAGE_STATUS_PENDINGSEND',3);
define('_SOCIAL_MESSAGE_STATUS_SENDING',4);
define('_DTBAKER_PLUGIN_FILE_NAME_20_',__FILE__);

// Include plugin class files
require_once( 'classes/class-simple-social-inbox.php' );
require_once( 'classes/class-simple-social-inbox-table.php' );
//require_once( 'classes/class-simple-social-inbox-settings.php' );
require_once( 'classes/ucm.database.php' );
require_once( 'classes/ucm.form.php' );
require_once( 'vendor/autoload.php' );

// include the different network plugins:
require_once( 'networks/facebook/facebook.class.php' );
require_once( 'networks/twitter/twitter.class.php' );
require_once( 'networks/google/google.class.php' );
require_once( 'networks/linkedin/linkedin.class.php' );


require_once( 'dtbaker.plugin_update.php' );

// Instantiate necessary classes
global $plugin_obj;
$plugin_obj = SimpleSocialInbox::getInstance( _DTBAKER_PLUGIN_FILE_NAME_20_ );
//$plugin_settings_obj = new SimpleSocialInbox_Settings( __FILE__ );