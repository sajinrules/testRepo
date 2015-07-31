<?php
/**
 * @package We Cross Tool Engine connection Addon
 */
/*
Plugin Name: We Cross Tool Data connection 
Plugin URI: http://www.wecross.nl/
Description: Save page+link information for documentation
Version: 3.1.1
Author: We Cross
Author URI: http://www.wecross.nl/
License: GPLv2 or later
Text Domain: wecross
*/



function wecrossjira_init() {
  if (!is_admin()) {
    wp_enqueue_script('wecross-jirajs', plugins_url('/wecross-jira.js',__FILE__) );
  }
}
add_action('init', 'wecrossjira_init');




class E{
	
	private static $db_name = "";
	private static $server = "";
	private static $dbusername = "";
	private static $dbpassword = "";
	
	private static $connection;
	private static $db;

	
	public static function Connect() { 
		self::$connection = mysqli_connect(self::$server, self::$dbusername, self::$dbpassword, self::$db_name) or die(mysql_error()); 
	}
	
	public static function getConnection() { 
		return self::$connection; 
	}
	
	public static function Close() { 
		mysqli_close(self::$connection);
	}
	
	// perform a database query, returns the unique_id 
	public static function doSQL($sql)
	{	
		mysqli_query(self::$connection, $sql); 
		$last_id = mysqli_insert_id(self::$connection);
		return $last_id;
	}
	
	public static function getSQL($sql)
	{	
		// perform sql query
		$return = mysqli_query(self::$connection, $sql); 
		return $return;
	}
}