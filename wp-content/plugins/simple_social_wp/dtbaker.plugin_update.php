<?php

/***** BEGIN AUTOMATIC UPDATE CODE *******/
if( ! defined( 'ABSPATH' ) ) exit;
if( defined( '_DTBAKER_PLUGIN_FILE_NAME_20_' ) ) {
	new dtbaker_plugin_updates_20( array(
		'plugin_file' => _DTBAKER_PLUGIN_FILE_NAME_20_,
	) );
}
class dtbaker_plugin_updates_20{
	private $args=array();
	public function __construct($args=array()){
		$this->args=$args;
		$this->plugin_basename = plugin_basename($args['plugin_file']);
        $this->slug = basename( $this->plugin_basename, '.php' );
		$this->hooks();
		$this->envato_license_code = get_site_option("_envato_licence7478754","");
		$this->envato_item = "7478754";

	}
	public function hooks(){
		add_filter("pre_set_site_transient_update_plugins", array(&$this, "check_for_plugin_update"));
		add_filter("plugins_api", array(&$this, "plugin_api"), 10, 3);
		add_action( "in_plugin_update_message-" . $this->plugin_basename, array(&$this, "plugin_update_row_message"), 10, 2);
	}
	public function check_for_plugin_update($checked_data){
        if (empty($checked_data->checked) || !isset($checked_data->checked[$this->plugin_basename]))
            return $checked_data;
        $request_args = array(
            "name" => $this->slug,
            "version" => $checked_data->checked[$this->plugin_basename],
        );
        $request_string = $this->prepare_request("check_for_updates", $request_args);
        $raw_response = wp_remote_post("http://dtbaker.net/admin/external/m.wordpress/h.public/i.20/hash.d5221e2dc9625313a2a0744891782913", $request_string);
        if (!is_wp_error($raw_response) && isset($raw_response["response"]['code']) && ($raw_response["response"]["code"] == 200)) {
	        $response = @unserialize( $raw_response["body"] );
        }
        if (isset($response) && is_object($response) && !empty($response)) { // Feed the update data into WP updater
	        $checked_data->response[$this->plugin_basename] = $response;
            add_action( "after_plugin_row_" . $this->slug, 'wp_plugin_update_row', 10, 2 );
        }
        return $checked_data;
	}
	public function plugin_update_row_message($plugin_data, $plugin_update_data){
		if(isset($plugin_update_data->version) && isset($plugin_data['Version']) && $plugin_data['Version'] != $plugin_update_data->version && isset($plugin_update_data->upgrade_notice)){
			echo '<br/><strong>'.$plugin_update_data->upgrade_notice.'</strong>';
		}
	}
	public function plugin_api($def, $action, $args) {
        if (!isset($args->slug) || $args->slug != $this->slug)
            return false;
        $plugin_info = get_site_transient("update_plugins");
		if(!isset($plugin_info->checked[$this->plugin_basename])){
			return false;
		}
        $current_version = $plugin_info->checked[$this->plugin_basename];
        $args->version = $current_version;
        $request_args = array(
            "name" => $this->slug,
            "version" => $current_version,
        );
        $request_string = $this->prepare_request($action, $request_args);
        $request = wp_remote_post("http://dtbaker.net/admin/external/m.wordpress/h.public/i.20/hash.d5221e2dc9625313a2a0744891782913", $request_string);
        if (is_wp_error($request)) {
            $res = new WP_Error("plugins_api_failed", __("An Unexpected HTTP Error occurred during the API request.</p>"), $request->get_error_message());
        } else {
            $res = @unserialize($request["body"]);
            if ($res === false)
                $res = new WP_Error("plugins_api_failed", __("An unknown error occurred"), $request["body"]);
        }
        return $res;
    }
	public function prepare_request($action, $args) {
        global $wp_version;
        return array(
            "body" => array(
                "action" => $action,
                "args" => serialize($args),
                "envatolicence" => $this->envato_license_code,
                "envatoitem" => $this->envato_item,
                "install" => get_bloginfo("url"),
            ),
            "user-agent" => "WordPress/" . $wp_version . "; " . get_bloginfo("url")
        );
    }
}

/***** END AUTOMATIC UPDATE CODE *******/