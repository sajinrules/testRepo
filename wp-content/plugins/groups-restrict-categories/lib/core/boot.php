<?php
/**
 * boot.php
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

define( 'GRC_ADMIN_LIB', GRC_PLUGIN_DIR . '/lib/admin' );
define( 'GRC_UTY_LIB', GRC_PLUGIN_DIR . '/lib/uty' );
define( 'GRC_VIEWS_LIB', GRC_PLUGIN_DIR . '/lib/views' );

require_once( GRC_CORE_LIB . '/class-groups-restrict-categories-controller.php' );
