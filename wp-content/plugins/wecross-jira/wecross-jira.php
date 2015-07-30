<?php
/**
 * @package We Cross Tool Jira Addon
 */
/*
Plugin Name: We Cross Jira Addon 
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


function getJiraLink(){
	global $user;
	if(!current_user_can( 'manage_options' )){
		return false;
	}
	$url = getStoredLink();
	?>
	<div class="page-toolbar">
		<!-- BEGIN THEME PANEL -->
		<div class="btn-group btn-theme-panel">
			<?php if($url) { ?>
			<a id="jiralink" href="<?php echo $url['url']; ?>" target='_blank'>
			<?php echo $url['nr']; ?>
			</a>
			<?php } else { ?>
			<a id="jiralink"  href="#" target='_blank'>
			Nog geen Jiralink opgeslagen
			</a>
			<?php } ?>
			<a href="javascript:;" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<i class="fa fa-lightbulb-o"></i>
			</a>
			<div class="dropdown-menu theme-panel pull-right dropdown-custom hold-on-click">
				<div class="row">
					<div class="col-md-12 col-sm-12 col-xs-12 seperator">
						<h3>Attach Jira/Confluence Page</h3>
						<div class="form-group form-md-line-input">
							<label class="col-md-2 control-label jira-input-label" for="form_control_1">URL</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="form_control_1" placeholder="fill in url to jirapage" value="<?php if($url) { echo $url['url']; }?>">
								<div class="form-control-focus">
								</div>
							</div>
						</div>
						<div class="form-group form-md-line-input">
							<label class="col-md-2 control-label jira-input-label" for="form_control_2">Label/ID/NR</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="form_control_2" placeholder="fill in label/id/nr" value="<?php if($url) { echo $url['nr']; }?>">
								<div class="form-control-focus">
								</div>
							</div>
						</div>
						
						<div class="form-actions noborder jira-submit">
						<div id='jira-ajax-response'></div><button id="jira-save-submit" type="button" class="btn blue">Save</button>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		<!-- END THEME PANEL -->
	</div>
	<?php
}

function saveJiraLink($inpurl, $inpnr, $curpage){
	
	$msg = "";
	DB::Connect();
	
	if($inpurl == '' && $inpnr == ''){
		$del = "DELETE FROM jiralinks WHERE wpurl = '$curpage'";
		DB::doSQL($del);
		$msg = "Link is verwijderd";
	}
	else
	{
		$inpurl = urldecode($inpurl);
		$sel = "SELECT * FROM jiralinks WHERE wpurl = '$curpage'";		
		//echo $sel;
		$res = DB::getSQL($sel);
		if(mysql_num_rows($res)>0){
			// found one, so update
			$upd = "UPDATE jiralinks SET jiraurl = '$inpurl', jiranummer = '$inpnr' WHERE wpurl = '$curpage'";
			DB::doSQL($upd);
			$msg = "Link is gewijzigd";
		}
		else
		{
			// not found, so insert
			$ins = "INSERT INTO jiralinks (wpurl, jiraurl, jiranummer) VALUES ('$curpage','$inpurl','$inpnr')";
			DB::doSQL($ins);
			$msg = "Link is ingevoerd";
		}
	}

	DB::Close();
	
	return $msg;
}


function deleteJiraLink($curpage){
	DB::Connect();
	$sql = "DELETE FROM jiralinks WHERE wpurl = '$curpage'";
	$res = DB::doSQL($sql);
	DB::Close();
}

function getCurrenPageJira(){
	$url = curPageURL();
	$url = str_ireplace('http://85.222.225.4', '', $url);
	$url = str_ireplace('https://85.222.225.4', '', $url);
	$url = str_ireplace('http://wecross.dev.wecross.nl', '', $url);
	$url = str_ireplace('https://wecross.dev.wecross.nl', '', $url);
	$url = str_ireplace('http://www.wecross.com', '', $url);
	$url = str_ireplace('https://www.wecross.com', '', $url);
	
	return $url;
}


function getStoredLink(){

	$url = getCurrenPageJira();
	
	DB::Connect();
	//$results = $wpdb->get_results( 'SELECT * FROM jiralinks WHERE wpurl = "'.$url.'"', OBJECT );
	$sql = "SELECT * FROM jiralinks WHERE wpurl = '".$url."'";
	$res = DB::getSQL($sql);
	
	while($row = mysqli_fetch_object($res)){
		$getjira = array('url' => $row->jiraurl, 'nr' => $row->jiranummer);
	}
	
	DB::Close();
	
	if(count($getjira)>0){
		return $getjira;
	}
	else
	{
		return false;
	}	
}

class DB{
	
	private static $db_name = "wecross";
	private static $server = "localhost";
	private static $dbusername = "wecross";
	private static $dbpassword = "DdE7AAmwwBLVFXPU";
	
	private static $connection;
	private static $db;
	
	
	/* disabled, used if DB is not static 
	public function DB()
	{
		self::$db_name = "modwisen";
		self::$server = "localhost";
		self::$dbusername = "modwisen";
		self::$dbpassword= "dQQvHbMS";
	}
	*/
	
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
	
