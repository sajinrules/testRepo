<?php
/**
 * class-groups-restrict-categories-controller.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is provided subject to the license granted.
 * Unauthorized use and distribution is prohibited.
 * See COPYRIGHT.txt and LICENSE.txt
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * This header and all notices must be kept intact.
 * 
 * @author Karim Rahimpur
 * @package groups-restrict-categories
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin controller.
 */
class Groups_Restrict_Categories_Controller {

	public static $admin_messages = array();

	/**
	 * Boots the plugin.
	 */
	public static function boot() {
		add_action( 'admin_notices', array( __CLASS__, 'admin_notices' ) );
		load_plugin_textdomain( GRC_PLUGIN_DOMAIN, null, 'groups-restrict-categories/languages' );
		if ( self::check_dependencies() ) {
			self::version_check();
			require_once( GRC_CORE_LIB . '/class-groups-restrict-categories.php' );
			if ( is_admin() ) {
				require_once( GRC_ADMIN_LIB . '/class-groups-restrict-categories-taxonomy-admin.php' );
				require_once( GRC_ADMIN_LIB . '/class-groups-restrict-categories-settings.php' );
			}
		}
	}

	/**
	 * Checks if Groups is activated.
	 * @return true if Groups is there, false otherwise
	 */
	public static function check_dependencies() {
		$active_plugins = get_option( 'active_plugins', array() );
		if ( is_multisite() ) {
			$active_sitewide_plugins = get_site_option( 'active_sitewide_plugins', array() );
			$active_sitewide_plugins = array_keys( $active_sitewide_plugins );
			$active_plugins = array_merge( $active_plugins, $active_sitewide_plugins );
		}
		$groups_is_active = in_array( 'groups/groups.php', $active_plugins );
		if ( !$groups_is_active ) {
			self::$admin_messages[] =
				'<div class="error">' .
				__( '<strong>Groups Restrict Categories</strong> requires the <a href="http://www.itthinx.com/plugins/groups/">Groups</a> plugin. Please install and activate it.', GRC_PLUGIN_DOMAIN ) .
				'</div>';
		}
		return $groups_is_active;
	}

	/**
	 * Prints admin notices.
	 */
	public static function admin_notices() {
		if ( !empty( self::$admin_messages ) ) {
			foreach ( self::$admin_messages as $msg ) {
				echo $msg;
			}
		}
	}

	/**
	 * Checks the current version and triggers an update if needed.
	 */
	public static function version_check() {
		$previous_version = get_option( 'grc_plugin_version', null );
		$current_version  = GRC_PLUGIN_VERSION;
		if ( version_compare( $previous_version, $current_version ) < 0 ) {
			if ( self::update( $previous_version ) ) {
				update_option( 'grc_plugin_version', $current_version );
			} else {
				self::$admin_messages[] = '<div class="error">Updating internal data for Groups Restrict Categories <em>failed</em>.</div>';
			}
		}
	}

	/**
	 * Update internal data.
	 */
	public static function update( $previous_version ) {
		global $wpdb;
		$result = false;
		if ( version_compare( $previous_version, '1.3.0' ) < 0 ) {
			self::$admin_messages[] = '<div class="updated">Groups Restrict Categories is updating its internal data.</div>';
			$index = get_option( 'grc_term_read_capabilities', null );
			if ( $index === null ) {
				$index = array();
				add_option( 'grc_term_read_capabilities', array(), '', 'no' );
			}
			$terms = $wpdb->get_results( "SELECT term_id FROM $wpdb->terms" );
			foreach( $terms as $term ) {
				$term_id = $term->term_id;
				$capabilities = get_option( "grc_read_${term_id}", null );
				if ( $capabilities !== null ) {
					$index[$term_id] = $capabilities;
				}
			}
			update_option( 'grc_term_read_capabilities', $index );
			unset( $index );
			$result = true;
			foreach( $terms as $term ) {
				$term_id = $term->term_id;
				delete_option( "grc_read_${term_id}" );
			}
			unset( $terms );
		} else {
			$result = true;
		}
		return $result;
	}
}
Groups_Restrict_Categories_Controller::boot();
