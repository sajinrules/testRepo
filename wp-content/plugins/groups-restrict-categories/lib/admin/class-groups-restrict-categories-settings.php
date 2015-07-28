<?php
/**
 * class-groups-restrict-categories-settings.php
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
 * Admin section.
 */
class Groups_Restrict_Categories_Settings {

	/**
	 * Admin options setup.
	 */
	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'admin_menu' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );
		add_filter( 'plugin_action_links_'. plugin_basename( GRC_PLUGIN_FILE ), array( __CLASS__, 'admin_settings_link' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Admin options admin setup.
	 */
	public static function admin_init() {
		wp_register_style( 'groups_restrict_categories_admin', GRC_PLUGIN_URL . '/css/admin.css', array(), GRC_PLUGIN_VERSION );
	}

	/**
	 * Loads styles for the admin section.
	 */
	public static function admin_print_styles() {
		wp_enqueue_style( 'groups_restrict_categories_admin' );
	}

	/**
	 * Enqueues the select script on the user-edit and profile screens.
	 */
	public static function admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( isset( $screen->id ) ) {
			switch( $screen->id ) {
				case 'groups_page_groups-restrict-categories-settings' :
					require_once GROUPS_VIEWS_LIB . '/class-groups-uie.php';
					Groups_UIE::enqueue( 'select' );
					break;
			}
		}
	}

	/**
	 * Add a menu item to the Appearance menu.
	 */
	public static function admin_menu() {
		$page = add_submenu_page(
			'groups-admin',
			__( 'Groups Restrict Categories', GRC_PLUGIN_DOMAIN ),
			__( 'Restrict Categories', GRC_PLUGIN_DOMAIN ),
			GROUPS_ADMINISTER_OPTIONS,
			'groups-restrict-categories-settings',
			array( __CLASS__, 'settings' )
		);
		add_action( 'admin_print_styles-' . $page, array( __CLASS__, 'admin_print_styles' ) );
	}

	/**
	 * Settings screen.
	 */
	public static function settings() {
		if ( !current_user_can( GROUPS_ADMINISTER_OPTIONS ) ) {
			wp_die( __( 'Access denied.', GRC_PLUGIN_DOMAIN ) );
		}
		echo
			'<h2>' .
			__( 'Groups Restrict Categories - Settings', GRC_PLUGIN_DOMAIN ) .
			'</h2>';
		echo '<div class="groups-restrict-categories-settings">';
		include_once ( GRC_ADMIN_LIB . '/settings.php' );
		echo '</div>';
	}

	/**
	 * Adds plugin links.
	 *
	 * @param array $links
	 * @param array $links with additional links
	 */
	public static function admin_settings_link( $links ) {
		if ( current_user_can( GROUPS_ADMINISTER_OPTIONS ) ) {
			$links[] = '<a href="' . get_admin_url( null, 'admin.php?page=groups-restrict-categories-settings' ) . '">' . __( 'Settings', GRC_PLUGIN_DOMAIN ) . '</a>';
		}
		return $links;
	}

}
add_action( 'init', array( 'Groups_Restrict_Categories_Settings', 'init' ) );
