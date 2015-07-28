<?php
/**
 * groups-restrict-categories.php
 * 
 * Copyright (c) 2014-2015 "kento" Karim Rahimpur www.itthinx.com
 * 
 * =============================================================================
 * 
 *                             LICENSE RESTRICTIONS
 * 
 *           This plugin is provided subject to the license granted.
 *              Unauthorized use and distribution is prohibited.
 *                     See COPYRIGHT.txt and LICENSE.txt.
 * 
 * Files licensed under the GNU General Public License state so explicitly in
 * their header or where implied. Other files are not licensed under the GPL
 * and the license obtained applies.
 * 
 * =============================================================================
 * 
 * You MUST be granted a license by the copyright holder for those parts that
 * are not provided under the GPLv3 license.
 * 
 * If you have not been granted a license DO NOT USE this plugin until you have
 * BEEN GRANTED A LICENSE.
 * 
 * Use of this plugin without a granted license constitutes an act of COPYRIGHT
 * INFRINGEMENT and LICENSE VIOLATION and may result in legal action taken
 * against the offending party.
 * 
 * Being granted a license is GOOD because you will get support and contribute
 * to the development of useful free and premium themes and plugins that you
 * will be able to enjoy.
 * 
 * Thank you!
 * 
 * Visit www.itthinx.com for more information.
 * 
 * =============================================================================
 * 
 * This code is released under the GNU General Public License.
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
 *
 * Plugin Name: Groups Restrict Categories
 * Plugin URI: http://www.itthinx.com/plugins/groups-restrict-categories
 * Description: Restrict access to categories and taxonomy terms in general based on <a href="http://www.itthinx.com/plugins/groups/">Groups</a>.
 * Author: itthinx
 * Author URI: http://www.itthinx.com
 * Version: 1.3.3
 */
define( 'GRC_PLUGIN_VERSION', '1.3.3' );
if ( !function_exists( 'itthinx_plugins' ) ) {
	require_once 'itthinx/itthinx.php';
}
itthinx_plugins( __FILE__ );
define( 'GRC_PLUGIN_DOMAIN', 'groups-restrict-categories' );
define( 'GRC_PLUGIN_FILE', __FILE__ );
define( 'GRC_PLUGIN_URL', plugins_url( 'groups-restrict-categories' ) );
define( 'GRC_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'GRC_CORE_LIB', GRC_PLUGIN_DIR . '/lib/core' );
require_once( GRC_CORE_LIB . '/boot.php' );
