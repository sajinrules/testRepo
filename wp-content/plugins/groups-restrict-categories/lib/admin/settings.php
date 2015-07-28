<?php
/**
 * settings.php
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
 * @package groups-restrict-categories 1.0.0
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !current_user_can( GROUPS_ADMINISTER_OPTIONS ) ) {
	wp_die( __( 'Access denied.', GRC_PLUGIN_DOMAIN ) );
}

$taxonomies = Groups_Restrict_Categories::get_taxonomies();

if ( isset( $_POST['action'] ) && ( $_POST['action'] == 'set' ) && wp_verify_nonce( $_POST['groups-restrict-categories-settings'], 'admin' ) ) {

	$controlled_taxonomies = array();
	foreach( $taxonomies as $taxonomy ) {
		if ( isset( $_POST['taxonomies'] ) && is_array( $_POST['taxonomies'] ) && in_array( $taxonomy->name, $_POST['taxonomies'] ) ) {
			$controlled_taxonomies[] = $taxonomy->name;
		}
	}
	Groups_Restrict_Categories::set_controlled_taxonomies( $controlled_taxonomies );

	echo
		'<p class="info">' .
		__( 'The settings have been saved.', GRC_PLUGIN_DOMAIN ) .
		'</p>';

}
?>
<style type="text/css">
.separator {
	height: 1px;
	margin: 0.6em 1em 0.6em 0;
}
</style>
<div class="settings">
<form name="settings" method="post" action="">
<div>
<?php
	$controlled_taxonomies = Groups_Restrict_Categories::get_controlled_taxonomies();
	echo '<h3>' . __( 'Taxonomies', GRC_PLUGIN_DOMAIN ) . '</h3>';
	echo '<p class="description">';
	echo __( 'Enable access restrictions for these taxonomies:', GRC_PLUGIN_DOMAIN );
	echo '</p>';
	echo '<ul>';
	foreach( $taxonomies as $taxonomy ) {
		echo '<li>';
		echo '<label>';
		printf( '<input type="checkbox" name="taxonomies[]" value="%s" %s />', esc_attr( $taxonomy->name ), in_array( $taxonomy->name, $controlled_taxonomies ) ? ' checked="checked" ' : '' );
		echo __( $taxonomy->labels->singular_name, GRC_PLUGIN_DOMAIN );
		echo '</label>';
		echo '</li>';
	}
	echo '</ul>';
	echo '<ul>';
	echo '<li>';
	echo __( 'Access restrictions must be set per taxonomy term.', GRC_PLUGIN_DOMAIN );
	echo ' ';
	echo __( 'For example, to restrict access to a category, edit the category and choose at least one capability to restrict access.', GRC_PLUGIN_DOMAIN );
	echo '</li>';
	echo '<li>';
	echo __( 'Access restrictions are voided immediately if these are disabled for a taxonomy here.', GRC_PLUGIN_DOMAIN );
	echo ' ';
	echo __( 'The restrictions will continue to take effect once the taxonomy is enabled.', GRC_PLUGIN_DOMAIN );
	echo '</li>';
	echo '</ul>';
?>

<div class="separator"></div>

<?php wp_nonce_field( 'admin', 'groups-restrict-categories-settings', true, true ); ?>

<div class="buttons">
<input class="save button" type="submit" name="submit" value="<?php echo __( 'Save', GRC_PLUGIN_DOMAIN ); ?>" />
<input type="hidden" name="action" value="set" />
</div>
</div>
</form>
</div>

