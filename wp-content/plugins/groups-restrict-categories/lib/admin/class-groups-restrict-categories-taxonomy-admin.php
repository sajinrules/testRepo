<?php
/**
 * class-groups-restrict-categories-taxonomy-admin.php
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
 * Taxonomy admin handlers. Adds access restriction sections to admin screens
 * for taxonomies.
*/
class Groups_Restrict_Categories_Taxonomy_Admin {

	const NONCE          = 'grc-box-nonce';
	const SET_CAPABILITY = 'set-capability';
	const READ_ACCESS    = 'read-access';
	const CAPABILITY     = 'grc-capability';

	/**
	 * Sets up the init action.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'wp_init' ), apply_filters( 'groups_restrict_categories_init_priority', Groups_Restrict_Categories::INIT_PRIORITY ) );
	}

	/**
	 * Registers our actions.
	 *
	 * We assume that anyone who can edit a taxonomy, should also be allowed
	 * to apply groups/capability restrictions. But only group admins are
	 * allowed to quick-create new group-capability pairs.
	 */
	public static function wp_init() {
		$taxonomies = Groups_Restrict_Categories::get_taxonomies( 'names' );
		foreach( $taxonomies as $taxonomy ) {
			// Render restriction options when adding a new taxonomy term.
			add_action( "${taxonomy}_add_form_fields", array( __CLASS__, 'taxonomy_add_form_fields' ) );

			// Render restriction options when editing a taxonomy term.
			add_action( "${taxonomy}_edit_form", array( __CLASS__, 'taxonomy_edit_form' ), 10, 2 );

			// Save restrictions for a new taxonomy term.
			add_action( "created_${taxonomy}", array( __CLASS__, 'created_taxonomy' ), 10, 2 );

			// Save restrictions for a taxonomy term.
			add_action( "edited_${taxonomy}", array( __CLASS__, 'edited_taxonomy' ), 10, 2 );

			// Remove restrictions when a taxonomy term is deleted.
			add_action( "delete_${taxonomy}", array( __CLASS__, 'delete_taxonomy' ), 10, 3 );

			// Remove deleted restrictions. 
			add_action( 'groups_deleted_capability', array( __CLASS__, 'groups_deleted_capability' ) );

			// @todo Later, if feasible: sortable access restriction column?
			// $_sortable = apply_filters( "manage_{$this->screen->id}_sortable_columns", $this->get_sortable_columns() );
			// add_filter( 'manage_edit-??????????_sortable_columns', array( __CLASS__, 'manage_edit_??????????_sortable_columns' ) );

			// Add the Access Restriction column in the taxonomy overview.
			add_filter( "manage_edit-${taxonomy}_columns", array( __CLASS__, 'manage_edit_taxonomy_columns' ) );

			// Render the access restriction capabilities.
			// return apply_filters( "manage_{$this->screen->taxonomy}_custom_column", '', $column_name, $tag->term_id );
			add_filter( "manage_${taxonomy}_custom_column", array( __CLASS__, 'manage_taxonomy_custom_column' ), 10, 3 );
		}
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue the select script where we need it.
	 * 
	 * @param string $hook
	 */
	public static function admin_enqueue_scripts( $hook ) {
		switch( $hook ) {
			case 'edit-tags.php' :
				Groups_UIE::enqueue( 'select' );
				break;
		}
	}

	/**
	 * Restrictions rendered before the "Add New (Taxonomy)" button.
	 * 
	 * @param string $taxonomy
	 */
	public static function taxonomy_add_form_fields( $taxonomy ) {
		self::panel( $taxonomy );
	}

	/**
	 * Hook in wp-admin/edit-tag-form.php - add restrictions.
	 * 
	 * @param string $tag
	 * @param string $taxonomy
	 */
	public static function taxonomy_edit_form( $tag, $taxonomy ) {
		self::panel( $tag, $taxonomy );
	}

	/**
	 * Renders our restriction panel.
	 */
	public static function panel( $tag = null ) {

		global $post, $wpdb;

		$output        = '';
		$term_id       = isset( $tag->term_id ) ? $tag->term_id : null;
		$taxonomy_name = isset( $tag->taxonomy ) ? $tag->taxonomy : $tag;
		$taxonomy      = get_taxonomy( $taxonomy_name );

		$singular_name = __( 'Taxonomy', GRC_PLUGIN_DOMAIN );
		if ( $taxonomy !== null ) {
			$labels = isset( $taxonomy->labels ) ? $taxonomy->labels : null;
			if ( $labels !== null ) {
				if ( isset( $labels->singular_name ) )  {
					$singular_name = __( $labels->singular_name );
				}
			}
		}

		$output .= wp_nonce_field( self::SET_CAPABILITY, self::NONCE, true, false );

		$output .= '<div class="form-field">';

		if ( Groups_Access_Meta_Boxes::user_can_restrict() ) {
			$user = new Groups_User( get_current_user_id() );

			// get access restrictions capabilities for $term_id
			if ( $term_id !== null ) {
				// existing term
				$read_caps = Groups_Restrict_Categories::get_term_read_capabilities( $term_id );
			} else {
				// new term
				$read_caps = array();
			}

			$valid_read_caps = Groups_Options::get_option( Groups_Post_Access::READ_POST_CAPABILITIES, array( Groups_Post_Access::READ_POST_CAPABILITY ) );
			$output .= '<div class="select-capability-container" style="width:95%">';
			$output .= '<label>';
			$output .= __( 'Enforce read access', GRC_PLUGIN_DOMAIN );
			$output .= sprintf(
				'<select class="select capability" name="%s" multiple="multiple" placeholder="%s" data-placeholder="%s" title="%s">',
				self::CAPABILITY . '[]',
				__( 'Type and choose &hellip;', GRC_PLUGIN_DOMAIN),
				__( 'Type and choose &hellip;', GRC_PLUGIN_DOMAIN),
				__( 'Choose one or more capabilities to restrict access. Groups that grant access through the capabilities are shown in parenthesis. If no capabilities are available yet, you can use the quick-create box to create a group and capability enabled for access restriction on the fly.', GRC_PLUGIN_DOMAIN )
			);
			$output .= '<option value=""></option>';
			foreach( $valid_read_caps as $valid_read_cap ) {
				if ( $capability = Groups_Capability::read_by_capability( $valid_read_cap ) ) {
					if ( $user->can( $capability->capability ) ) {
						$c = new Groups_Capability( $capability->capability_id );
						$groups = $c->groups;
						$group_names = array();
						if ( !empty( $groups ) ) {
							foreach( $groups as $group ) {
								$group_names[] = $group->name;
							}
						}
						if ( count( $group_names ) > 0 ) {
							$label_title = sprintf(
								_n(
									'Members of the %1$s group can access this %2$s through this capability.',
									'Members of the %1$s groups can access this %2$s through this capability.',
									count( $group_names ),
									GRC_PLUGIN_DOMAIN
								),
								wp_filter_nohtml_kses( implode( ',', $group_names ) ),
								$singular_name
							);
						} else {
							$label_title = __( 'No groups grant access through this capability. To grant access to group members using this capability, you should assign it to a group and enable the capability for access restriction.', GRC_PLUGIN_DOMAIN );
						}
						$output .= sprintf( '<option value="%s" %s>', esc_attr( $capability->capability_id ), !empty( $read_caps ) && in_array( $capability->capability, $read_caps ) ? ' selected="selected" ' : '' );
						$output .= wp_filter_nohtml_kses( $capability->capability );
						if ( count( $group_names ) > 0 ) {
							$output .= ' ';
							$output .= '(' . wp_filter_nohtml_kses( implode( ', ', $group_names ) ) . ')';
						}
						$output .= '</option>';
					}
				}
			}
			$output .= '</select>';
			$output .= '</label>';
			$output .= Groups_UIE::render_select( '.select.capability' );
			$output .= '</div>'; // .select-capability-container

			$output .= '<p class="description" style="width:92%;padding:0 8px;">';
			$output .= sprintf( __( "Only groups or users that have one of the selected capabilities are allowed to access this %s.", GRC_PLUGIN_DOMAIN ), $singular_name );
			$output .= ' ';
			$output .= __( 'Groups related to capabilities are shown in parenthesis.', GRC_PLUGIN_DOMAIN );
			$output .= '</p>';

		} else {
			$output .= '<p class="description" style="width:92%;padding:0 8px;">';
			$output .= __( 'You cannot set any access restrictions.', GRC_PLUGIN_DOMAIN );
			$style = 'cursor:help;vertical-align:middle;';
			if ( current_user_can( GROUPS_ADMINISTER_OPTIONS ) ) {
				$style = 'cursor:pointer;vertical-align:middle;';
				$output .= sprintf( '<a href="%s">', esc_url( admin_url( 'admin.php?page=groups-admin-options' ) ) );
			}
			$output .= sprintf( '<img style="%s" alt="?" title="%s" src="%s" />', $style, esc_attr( __( 'You must be in a group that has at least one capability enabled to enforce read access.', GRC_PLUGIN_DOMAIN ) ), esc_attr( GROUPS_PLUGIN_URL . 'images/help.png' ) );
			if ( current_user_can( GROUPS_ADMINISTER_OPTIONS ) ) {
				$output .= '</a>';
			}
			$output .= '</p>';
		}

		// quick-create
		if ( current_user_can( GROUPS_ADMINISTER_GROUPS ) ) {
			$style = 'cursor:help;vertical-align:middle;';
			$output .= '<div class="quick-create-group-capability" style="margin:4px 0;width:95%;">';
			$output .= '<label>';
			$output .= sprintf( '<input style="width:100%%;margin-right:-20px;" id="quick-group-capability" name="quick-group-capability" class="quick-group-capability" type="text" value="" placeholder="%s"/>', __( 'Quick-create group &amp; capability', GRC_PLUGIN_DOMAIN ) );
			$output .= sprintf(
				'<img id="quick-create-help-icon" style="%s" alt="?" title="%s" src="%s" />',
				$style,
				esc_attr( __( 'You can create a new group and capability here. The capability will be assigned to the group and enabled to enforce read access. Group names are case-sensitive, the name of the capability is the lower-case version of the name of the group. If the group already exists, a new capability is created and assigned to the existing group. If the capability already exists, it will be assigned to the group. If both already exist, the capability is enabled to enforce read access. In order to be able to use the capability, your user account will be assigned to the group.', GRC_PLUGIN_DOMAIN ) ),
				esc_attr( GROUPS_PLUGIN_URL . 'images/help.png' )
			);
			$output .= '</label>';
			$output .= '</div>';
			$output .= '<script type="text/javascript">';
			$output .= 'if (typeof jQuery !== "undefined"){';
			$output .= 'jQuery("#quick-create-help-icon").click(function(){';
			$output .= 'jQuery("#contextual-help-link").click();';
			$output .= '});';
			$output .= '}';
			$output .= '</script>';
		}

		$output .= '</div>'; // .form-field
		echo $output;

	}

	/**
	 * Save groups for a new taxonomy term.
	 * @param int $term_id
	 * @param int $tt_id
	 */
	public static function created_taxonomy( $term_id, $tt_id ) {
		self::edited_taxonomy( $term_id, $tt_id );
	}

	/**
	 * Save taxonomy term access restriction capabilities.
	 * @param int $term_id term ID
	 * @param int $tt_id taxonomy ID
	 */
	public static function edited_taxonomy( $term_id, $tt_id ) {

		// handle quick-create
		if ( current_user_can( GROUPS_ADMINISTER_GROUPS ) ) {
			if ( !empty( $_POST['quick-group-capability'] ) ) {
				$name = ucfirst( strtolower( trim( $_POST['quick-group-capability'] ) ) );
				if ( strlen( $name ) > 0 ) {
					// create or obtain the group
					if ( $group = Groups_Group::read_by_name( $name ) ) {
					} else {
						if ( $group_id = Groups_Group::create( compact( 'creator_id', 'datetime', 'name' ) ) ) {
							$group = Groups_Group::read( $group_id );
						}
					}
					// create or obtain the capability
					$name = strtolower( $name );
					if ( $capability = Groups_Capability::read_by_capability( $name ) ) {
					} else {
						if ( $capability_id = Groups_Capability::create( array( 'capability' => $name ) ) ) {
							$capability = Groups_Capability::read( $capability_id );
						}
					}
					if ( $group && $capability ) {
						// add the capability to the group
						if ( !Groups_Group_Capability::read( $group->group_id, $capability->capability_id ) ) {
							Groups_Group_Capability::create(
								array(
									'group_id' => $group->group_id,
									'capability_id' => $capability->capability_id
								)
							);
						}
						// enable the capability for access restriction
						$valid_read_caps = Groups_Options::get_option( Groups_Post_Access::READ_POST_CAPABILITIES, array( Groups_Post_Access::READ_POST_CAPABILITY ) );
						if ( !in_array( $capability->capability, $valid_read_caps ) ) {
							$valid_read_caps[] = $capability->capability;
						}
						Groups_Options::update_option( Groups_Post_Access::READ_POST_CAPABILITIES, $valid_read_caps );
						// add the current user to the group
						Groups_User_Group::create(
							array(
								'user_id' => get_current_user_id(),
								'group_id' => $group->group_id
							)
						);
						// put the capability ID in $_POST[self::CAPABILITY] so it is treated below
						if ( empty( $_POST[self::CAPABILITY] ) ) {
							$_POST[self::CAPABILITY] = array();
						}
						if ( !in_array( $capability->capability_id, $_POST[self::CAPABILITY] ) ) {
							$_POST[self::CAPABILITY][] = $capability->capability_id;
						}
					}
				}
			}
		}

		// assign capabilities
		$capabilities = array();
		if ( !empty( $_POST[self::CAPABILITY] ) && is_array( $_POST[self::CAPABILITY] ) ) {
			foreach( $_POST[self::CAPABILITY] as $capability_id ) {
				if ( $capability = Groups_Capability::read( $capability_id ) ) {
					$capabilities[] = $capability->capability;
				}
			}
		}
		Groups_Restrict_Categories::set_term_read_capabilities( $term_id, $capabilities );
	}

	/**
	 * Remove restrictions for a taxonomy term.
	 * @param int $term_id
	 * @param int $tt_id taxonomy
	 * @param object $deleted_term
	 */
	public static function delete_taxonomy( $term_id, $tt_id, $deleted_term ) {
		Groups_Restrict_Categories::delete_term_read_capabilities( $term_id );
	}

	/**
	 * Remove deleted restrictions related to a taxonomy term.
	 * @param int $group_id
	 */
	public static function groups_deleted_capability( $capability_id ) {
		// This is rather awkward ... as we can't retrieve the already
		// deleted capability by ID (it's just been deleted!) and the action
		// doesn't supply any other info than the ID, we have to scan
		// through all entries and remove capabilities that don't exist.
		$taxonomies = Groups_Restrict_Categories::get_taxonomies( 'names' );
		$term_ids = get_terms( $taxonomies, array( 'fields' => 'ids', 'hide_empty' => false ) );
		if ( is_array( $term_ids ) ) {
			foreach( $term_ids as $term_id ) {
				$read_caps = Groups_Restrict_Categories::get_term_read_capabilities( $term_id );
				if ( !empty( $read_caps ) ) {
					$new_read_caps = array();
					foreach( $read_caps as $read_cap ) {
						if ( $capability = Groups_Capability::read( $read_cap ) ) {
							$new_read_caps[] = $read_cap;
						}
					}
					Groups_Restrict_Categories::set_term_read_capabilities( $term_id, $new_read_caps );
				}
			}
		}
	}

	/**
	 * Not implemented - see init() above.
	 * @param array $columns
	 */
	public static function manage_edit_taxonomy_sortable_columns( $columns ) {
		return $columns;
	}

	/**
	 * Adds the Groups column.
	 * @param array $columns
	 * @return array
	 */
	public static function manage_edit_taxonomy_columns( $columns ) {
		$columns[self::CAPABILITY] = __( 'Access Restrictions', GRC_PLUGIN_DOMAIN );
		return $columns;
	}

	/**
	 * Render groups for a taxonomy term.
	 * @param string $content
	 * @param string $column_name
	 * @param int $term_id
	 * @return string
	 */
	public static function manage_taxonomy_custom_column( $content, $column_name, $term_id ) {
		if ( $column_name == self::CAPABILITY ) {
			$content .= self::get_capability_list_html( $term_id );
		}
		return $content;
	}

	/**
	 * Add a taxonomy column to the post type overview.
	 * @param array $posts_columns
	 * @return array
	 */
// 	public static function manage_??????????_posts_columns( $posts_columns ) {
// 		$posts_columns['??????????'] = __( '?????????? Taxonomy', GRC_PLUGIN_DOMAIN );
// 		return $posts_columns;
// 	}

	/**
	 * Render taxonomy terms in the post type overview.
	 * @param string $column_name
	 * @param int $post_id
	 */
// 	public static function manage_??????????_posts_custom_column( $column_name, $post_id ) {
// 		if ( $column_name == '??????????' ) {
// 			$terms = wp_get_post_terms( $post_id, '??????????' );
// 			foreach( $terms as $term ) {
// 				$args = array();
// 				$args['action'] = 'edit';
// 				$args['tag_ID'] = $term->term_id;
// 				$args['post_type'] = 'xxxxxxxxxx';
// 				$args['taxonomy'] = '??????????';
// 				echo sprintf( '<a href="%s">%s</a>',
// 					esc_url( add_query_arg( $args, 'edit-tags.php' ) ),
// 					esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, '??????????', 'display' ) )
// 				);
// 			}
// 		}
// 	}

	/**
	 * Render the list of access restriction capabilities for the given taxonomy term.
	 * @param int $term_id
	 * @return string HTML
	 */
	private static function get_capability_list_html( $term_id ) {
		$output = '';
		$read_caps = Groups_Restrict_Categories::get_term_read_capabilities( $term_id );
		$valid_read_caps = Groups_Options::get_option( Groups_Post_Access::READ_POST_CAPABILITIES, array( Groups_Post_Access::READ_POST_CAPABILITY ) );
		if ( !empty( $read_caps ) && ( count( $valid_read_caps ) > 0 ) ) {
			sort( $valid_read_caps );
			$output = '<ul>';
			foreach( $valid_read_caps as $valid_read_cap ) {
				if ( $capability = Groups_Capability::read_by_capability( $valid_read_cap ) ) {
					if ( in_array( $valid_read_cap, $read_caps ) ) {
						$output .= '<li>';
						$output .= wp_strip_all_tags( $capability->capability );
						$output .= '</li>';
					}
				}
			}
			$output .= '</ul>';
		} else {
			$output .= __( '', GRC_PLUGIN_DOMAIN );
		}
		return $output;
	}

}
Groups_Restrict_Categories_Taxonomy_Admin::init();
