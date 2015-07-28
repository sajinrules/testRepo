<?php
/**
 * class-groups-restrict-categories.php
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
 * Plugin core class.
 */
class Groups_Restrict_Categories {

	const OPTIONS       = 'grc_options';
	const INIT_PRIORITY = 999;

	const CACHE_GROUP         = 'groups';
	const RESTRICTED_TERM_IDS = 'restricted_term_ids';
	const CONTROLS_TERM       = 'controls_term';
	const USER_CAN_READ       = 'user_can_read';

	private static $taxonomies = array();

	/**
	 * Adds actions/filters.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'wp_init' ), apply_filters( 'groups_restrict_categories_init_priority', self::INIT_PRIORITY ) );

		// WooCommerce:

		// Don't allow to add products restricted by terms to the cart.
		// This is necessary to avoid products being added to the cart
		// through a direct link http://example.com/?post_type=product&add-to-cart=123
		// This filter must be added earlier than in our wp_init to take effect.
		add_filter( 'woocommerce_is_purchasable', array( __CLASS__, 'woocommerce_is_purchasable' ), 10, 2 );
	}

	/**
	 * Retrieves the post types option holding the write and read comments
	 * group requirements.
	 */
	public static function wp_init() {

		// Taxonomy filters:

		// Control access to taxonomy term admin pages and actions.
		// We can't use that (comes in too late and with different semantics and context).
		// See note on method below.
		//add_filter( 'get_term', array( __CLASS__, 'get_term' ), 10, 2 );
		// Instead, we deny access to the page:
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

		// Control terms requested through get_terms().
		add_filter( 'list_terms_exclusions', array( __CLASS__, 'list_terms_exclusions' ), 10, 3 );

		// Post filters:

		// Exclude posts related to restricted taxonomy terms.
		add_filter( 'posts_where', array( __CLASS__, 'posts_where' ), 10, 2 );

		// Page taxonomy access restrictions (for possible custom taxonomies).
		add_filter( 'get_pages', array( __CLASS__, 'get_pages' ) );

		// Post taxonomy access restrictions.
		if ( apply_filters( 'groups_restrict_categories_filter_the_posts', false ) ) {
			add_filter( 'the_posts', array( __CLASS__, 'the_posts' ), 1, 2 );
		}

		// Filter excerpts.
		add_filter( 'get_the_excerpt', array( __CLASS__, 'get_the_excerpt' ) );

		// Filter contents.
		add_filter( 'the_content', array( __CLASS__, 'the_content' ) );

		// Controls permission to edit or delete posts.
		add_filter( 'map_meta_cap', array( __CLASS__, 'map_meta_cap' ), 10, 4 );
	}

	/**
	 * Not used but left for reference.
	 * The filter doesn't work the way that we could use to
	 * restrict access to the term. For example, edit-tags.php would still
	 * show up although with empty fields.
	 * 
	 * Quoting from get_term() :
	 * "... 'get_term' hook ... Must return term object. ..."
	 * 
	 * @param object $term
	 * @param string $taxonomy
	 * @return object modified $term 
	 */
	// 	public static function get_term( $term, $taxonomy ) {
	// 		if ( isset( $term->term_id ) ) {
	// 			if ( !self::user_can_read_term(get_current_user_id(), $term->term_id ) ) {
	// 				$term = (object) array(
	// 					'term_id' => null,
	// 					'name' => null,
	// 					'taxonomy' => null,
	// 					'parent' => null,
	// 					'description' => null
	// 				);
	// 			}
	// 		}
	// 		return $term;
	// 	}

	/**
	 * Restricts access to taxonomy term pages and actions on the admin side,
	 * through edit-tags.php.
	 */
	public static function admin_init() {

		global $pagenow;

		$user_id = get_current_user_id();

		// admin override ?
		if ( $user_id ) {
			// if administrators can override access, don't filter
			if ( get_option( GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE, GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE_DEFAULT ) ) {
				if ( user_can( $user_id, 'administrator' ) ) {
					return;
				}
			}
		}

		switch( $pagenow ) {
			case 'edit-tags.php' :
				if ( isset( $_REQUEST['tag_ID'] ) ) {
					$term_id = (int) $_REQUEST['tag_ID'];
					if ( !self::user_can_read_term( get_current_user_id(), $term_id ) ) {
						wp_die( 'Access denied.', GRC_PLUGIN_DOMAIN );
					}
				}
				break;
		}
	}

	/**
	 * Returns true if the user can access the term.
	 * 
	 * @param int $user_id
	 * @param int $term_id
	 * @return boolean
	 */
	public static function user_can_read_term( $user_id, $term_id ) {
		$restricted = false;
		if ( self::controls_term( $term_id ) ) {
			$groups_user = new Groups_User( $user_id );
			$read_caps = Groups_Restrict_Categories::get_term_read_capabilities( $term_id );
			if ( !empty( $read_caps ) ) {
				$restricted = true;
				foreach( $read_caps as $read_cap ) {
					if ( $groups_user->can( $read_cap ) ) {
						$restricted = false;
						break;
					}
				}
		}
		}
		return !$restricted;
	}

	/**
	 * Returns all term IDs that the user is not allowed to read.
	 * 
	 * @param int $user_id
	 * @return array of int with term IDs
	 */
	public static function get_user_restricted_term_ids( $user_id ) {
		$found = false;
		$restricted_term_ids = wp_cache_get( self::RESTRICTED_TERM_IDS . intval( $user_id ), self::CACHE_GROUP, false, $found );
		if ( $found === false ) {
			$restricted_term_ids = array();
			$taxonomies = self::get_controlled_taxonomies();
			// Temporarily disable the filter so that we can retrieve all terms
			// for the current user including those that are restricted.
			remove_filter( 'list_terms_exclusions', array(__CLASS__, 'list_terms_exclusions' ) );
			$term_ids = get_terms( $taxonomies, array( 'fields' => 'ids', 'hide_empty' => false ) );
			add_filter( 'list_terms_exclusions', array( __CLASS__, 'list_terms_exclusions' ), 10, 3 );
			if ( is_array( $term_ids ) ) {
				$groups_user = new Groups_User( $user_id );
				foreach( $term_ids as $term_id ) {
					$read_caps = Groups_Restrict_Categories::get_term_read_capabilities( $term_id );
					if ( !empty( $read_caps ) ) {
						$restricted = true;
						foreach( $read_caps as $read_cap ) {
							if ( $groups_user->can( $read_cap ) ) {
								$restricted = false;
								break;
							}
						}
						if ( $restricted ) {
							$restricted_term_ids[] = $term_id;
						}
					}
				}
			}
			$restricted_term_ids = array_map( 'intval', $restricted_term_ids );
			$cached = wp_cache_set( self::RESTRICTED_TERM_IDS . intval( $user_id ), $restricted_term_ids, self::CACHE_GROUP );
		}
		return $restricted_term_ids;
	}

	/**
	 * Filters out terms that are restricted.
	 * 
	 * @param string $exclusions
	 * @param array $args
	 * @param array $taxonomies
	 * @return string $exclusions with appended term ID restrictions
	 */
	public static function list_terms_exclusions( $exclusions, $args, $taxonomies ) {

		$user_id = get_current_user_id();

		// admin override ?
		if ( $user_id ) {
			// if administrators can override access, don't filter
			if ( get_option( GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE, GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE_DEFAULT ) ) {
				if ( user_can( $user_id, 'administrator' ) ) {
					return $exclusions;
				}
			}
		}

		$restricted_term_ids = self::get_user_restricted_term_ids( $user_id );
		if ( !empty( $restricted_term_ids ) ) {
			$restricted_term_ids = array_map( 'intval', $restricted_term_ids );
			$restricted_term_ids = implode( ',', $restricted_term_ids );
			$exclusions .= ' AND t.term_id NOT IN (' . $restricted_term_ids . ')';
		}

		return $exclusions;
	}

	/**
	 * Filters out posts that the user should not be able to access, based
	 * on taxonomy terms with access restrictions.
	 * 
	 * @param string $where current where conditions
	 * @param WP_Query $query current query
	 * @return string modified $where
	 */
	public static function posts_where( $where, &$query ) {

		global $wpdb;

		$user_id = get_current_user_id();

		// admin override ?
		if ( $user_id ) {
			// if administrators can override access, don't filter
			if ( get_option( GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE, GROUPS_ADMINISTRATOR_ACCESS_OVERRIDE_DEFAULT ) ) {
				if ( user_can( $user_id, 'administrator' ) ) {
					return $where;
				}
			}
		}

		$restricted_term_ids = self::get_user_restricted_term_ids( $user_id );

		// $restricted_term_ids are terms that the current user is not allowed
		// to access. Any post that belongs to one of those terms should be
		// filtered out.
		// The resulting query should result in getting all posts that are
		// not in ANY of the restricted categories (thus the UNION).
		if ( !empty( $restricted_term_ids ) ) {
			$where .= " AND {$wpdb->posts}.ID NOT IN ";
			$where .= " ( ";
			$union = array();
			foreach( $restricted_term_ids as $term_id ) {
				$union[] = sprintf( " SELECT object_id FROM $wpdb->term_relationships LEFT JOIN $wpdb->term_taxonomy ON {$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id WHERE term_id = %d ", intval( $term_id ) );
			}
			$where .= implode( ' UNION ALL ', $union );
			$where .= " ) ";
		}
		return $where;
	}

	/**
	 * Filter pages by their terms' access restrictions. Although pages don't
	 * have any terms related by default, this should be included if there
	 * are custom taxonomies related to pages.
	 * 
	 * @param array $pages
	 * @return array
	 */
	public static function get_pages( $pages ) {
		$result = array();
		$user_id = get_current_user_id();
		foreach ( $pages as $page ) {
			if ( self::user_can_read( $page->ID, $user_id ) ) {
				$result[] = $page;
			}
		}
		return $result;
	}

	/**
	 * Filter posts by their terms' access restrictions.
	 * 
	 * @param array $posts list of posts
	 * @param WP_Query $query
	 * @return array
	 */
	public static function the_posts( $posts, &$query ) {
		$result = array();
		$user_id = get_current_user_id();
		foreach ( $posts as $post ) {
			if ( self::user_can_read( $post->ID, $user_id ) ) {
				$result[] = $post;
			}
		}
		return $result;
	}

	/**
	 * Filter the excerpt by the post's related terms and their access 
	 * restrictions.
	 * 
	 * @param string $output
	 * @return string the original output if access is granted, otherwise ''
	 */
	public static function get_the_excerpt( $output ) {
		global $post;
		$result = '';
		// only try to restrict if we have the ID
		if ( isset( $post->ID ) ) {
			if ( self::user_can_read( $post->ID ) ) {
				$result = $output;
			}
		} else {
			$result = $output;
		}
		return $result;
	}

	/**
	 * Filters the content by its related terms and their access restrictions.
	 *
	 * @param string $output
	 * @return string the original output if access is granted, otherwise ''
	 */
	public static function the_content( $output ) {
		global $post;
		$result = '';
		// only try to restrict if we have the ID
		if ( isset( $post->ID ) ) {
			if ( self::user_can_read( $post->ID ) ) {
				$result = $output;
			}
		} else {
			$result = $output;
		}
		return $result;
	}

	/**
	 * Returns true if all related terms allow access to the user. A single
	 * related term that restricts access will result in false to be returned.
	 * 
	 * @param int $post_id
	 * @param int $user_id
	 * @return boolean
	 */
	public static function user_can_read( $post_id, $user_id = null ) {
		$result = true;
		if ( $user_id === null ) {
			$user_id = get_current_user_id();
		}
		$found = false;
		$maybe_result = wp_cache_get( self::USER_CAN_READ . '_' . $post_id . '_' . $user_id, self::CACHE_GROUP, false, $found );
		if ( $found === false ) {
			foreach( self::get_controlled_taxonomies() as $taxonomy ) {
				$terms = get_the_terms( $post_id, $taxonomy );
				if ( is_array( $terms ) ) {
					foreach( $terms as $term ) {
						if ( !self::user_can_read_term( $user_id, $term->term_id ) ) {
							$result = false;
							break;
						}
					}
					if ( !$result ) {
						break;
					}
				}
			}
			wp_cache_set( self::USER_CAN_READ . '_' . $post_id . '_' . $user_id, $result, self::CACHE_GROUP );
		} else {
			$result = $maybe_result;
		}
		return $result;
	}

	/**
	 * Controls permission to edit or delete posts based on the access
	 * restrictions of its related terms.
	 * 
	 * If this were not handled, a user could access for example the post edit
	 * screen and modify it even though the post were restricted by term.
	 * 
	 * @param array $caps
	 * @param string $cap
	 * @param int $user_id
	 * @param array $args
	 * @return array
	 */
	public static function map_meta_cap( $caps, $cap, $user_id, $args ) {
		if ( isset( $args[0] ) ) {
			if ( strpos( $cap, 'edit_' ) === 0 || strpos( $cap, 'delete_' ) === 0 ) {
				if ( $post_type = get_post_type( $args[0] ) ) {

					$edit_post_type   = 'edit_' . $post_type;
					$delete_post_type = 'delete_' . $post_type;
					if ( $post_type_object = get_post_type_object( $post_type ) ) {
						if ( !isset( $post_type_object->capabilities ) ) {
							$post_type_object->capabilities = array();
						}
						$caps_object = get_post_type_capabilities( $post_type_object );
						if ( isset( $caps_object->edit_post ) ) {
							$edit_post_type = $caps_object->edit_post;
						}
						if ( isset( $caps_object->delete_post ) ) {
							$delete_post_type = $caps_object->delete_post;
						}
					}

					if ( $cap === $edit_post_type || $cap === $delete_post_type ) {
						$post_id = null;
						if ( is_numeric( $args[0] ) ) {
							$post_id = $args[0];
						} else if ( $args[0] instanceof WP_Post ) {
							$post_id = $post->ID;
						}
						if ( $post_id ) {
							if ( !self::user_can_read( $post_id, $user_id ) ) {
								$caps[] = 'do_not_allow';
							}
						}
					}
				}
			}
		}
		return $caps;
	}

	/**
	 * WooCommerce purchasable filter.
	 * 
	 * @param int $product_id
	 * @return product ID or null if not allowed
	 */
	public static function woocommerce_is_purchasable( $purchasable, $product ) {
		if ( $purchasable && !self::user_can_read( $product->id ) ) {
			$purchasable = false;
		}
		return $purchasable;
	}

	/**
	 * Returns taxonomy objects handled by this extension.
	 * The groups_restrict_categories_get_taxonomies_args filter can be used
	 * to modify the query which restricts the taxonomies that are handled
	 * to those which fulfill public and show_ui are true.
	 * 
	 * @param string $output 'objects' or 'names'
	 * @return array of object or string
	 */
	public static function get_taxonomies( $output = 'objects' ) {
		return get_taxonomies(
			apply_filters(
				'groups_restrict_categories_get_taxonomies_args',
				array(
					'public' => true,
					'show_ui' => true
				)
			),
			$output
		);
	}

	/**
	 * Returns true if the term is of a taxonomy that has
	 * access restrictions enabled.
	 * 
	 * @param int $term_id
	 * @return boolean
	 */
	public static function controls_term( $term_id ) {

		global $wpdb;

		$controls_term = wp_cache_get( self::CONTROLS_TERM . intval( $term_id ), self::CACHE_GROUP );
		if ( $controls_term === false ) {
			$taxonomy = $wpdb->get_var( $wpdb->prepare(
				"SELECT taxonomy FROM $wpdb->term_taxonomy WHERE term_id = %d",
				intval( $term_id )
			) );
			$result = self::controls_taxonomy( $taxonomy );
			$cached = wp_cache_set( self::CONTROLS_TERM . intval( $term_id ), $result ? 'yes' : 'no', self::CACHE_GROUP );
		} else {
			$result = ( $controls_term === 'yes' );
		}
		return $result;
	}

	/**
	 * Returns true if access restrictions are enabled for the taxonomy.
	 * 
	 * @param string $taxonomy taxonomy name
	 * @return boolean
	 */
	public static function controls_taxonomy( $taxonomy ) {
		return in_array( $taxonomy, self::get_controlled_taxonomies() );
	}

	/**
	 * Returns an array of taxonomy names for which access restrictions are
	 * enabled.
	 * 
	 * @return array of string
	 */
	public static function get_controlled_taxonomies() {
		$options = self::get_options();
		return isset( $options['taxonomies'] ) ? $options['taxonomies'] : self::get_taxonomies( 'names' );
	}

	/**
	 * Determines taxonomies for which access restrictions are enabled.
	 *  
	 * @param array $taxonomies taxonomy names
	 */
	public static function set_controlled_taxonomies( $taxonomies ) {
		if ( is_array( $taxonomies ) ) {
			$_taxonomies = array();
			foreach( $taxonomies as $taxonomy ) {
				if ( taxonomy_exists( $taxonomy ) ) {
					$_taxonomies[] = $taxonomy;
				}
			}
			$options = self::get_options();
			$options['taxonomies'] = $_taxonomies;
			self::set_options( $options );
		}
	}

	/**
	 * Get plugin options.
	 * @return array
	 */
	public static function get_options() {
		$data = get_option( self::OPTIONS, null );
		if ( $data === null ) {
			if ( add_option( self::OPTIONS, array(), '', 'no' ) ) {
				$data = get_option( self::OPTIONS, null );
			}
		}
		return $data;
	}

	/**
	 * Set plugin options.
	 * @param array $data
	 */
	public static function set_options( $data ) {
		$current_data = get_option( self::OPTIONS, null );
		if ( $current_data === null ) {
			add_option( self::OPTIONS, $data, '', 'no' );
		} else {
			update_option( self::OPTIONS, $data );
		}
	}

	/**
	 * Set the read capabilities for the term.
	 * 
	 * @param int $term_id
	 * @param array $capabilities
	 */
	public static function set_term_read_capabilities( $term_id, $capabilities ) {
		$term_id = intval( $term_id );
		// term_exists(...) is very expensive, we only use it here and
		// skip checks when reading or deleting with get_term_read_capabilities(...) and
		// delete_term_read_capabilities(...)
		if ( is_array( $capabilities ) && term_exists( $term_id ) ) {
			$version = get_option( 'grc_plugin_version', null );
			if ( version_compare( $version, '1.3.0' ) < 0 ) {
				delete_option( "grc_read_${term_id}" );
				if ( count( $capabilities ) > 0 ) {
					add_option( "grc_read_${term_id}", $capabilities, '', 'no' );
				}
			} else {
				$index = get_option( 'grc_term_read_capabilities', null );
				if ( $index === null ) {
					$index = array();
					add_option( 'grc_term_read_capabilities', array(), '', 'no' );
				}
				if ( count( $capabilities ) > 0 ) {
					$index[$term_id] = $capabilities;
				} else {
					unset( $index[$term_id] );
				}
				update_option( 'grc_term_read_capabilities', $index );
			}
		}
	}

	/**
	 * Returns an array of read capabilities for the term.
	 * 
	 * @param int $term_id
	 * @return array of string with read capabilities for the term, null if the term does not exist
	 */
	public static function get_term_read_capabilities( $term_id ) {
		$result = null;
		$term_id = intval( $term_id );
		$version = get_option( 'grc_plugin_version', null );
		if ( version_compare( $version, '1.3.0' ) < 0 ) {
			$result = get_option( "grc_read_${term_id}", array() );
		} else {
			$index = get_option( 'grc_term_read_capabilities', array() );
			if ( key_exists( $term_id, $index ) ) {
				$result = $index[$term_id];
			}
		}
		return $result;
	}

	/**
	 * Delete the read capabilities for the term.
	 * 
	 * @param int $term_id
	 */
	public static function delete_term_read_capabilities( $term_id ) {
		$term_id = intval( $term_id );
		delete_option( "grc_read_${term_id}" );
	}
}
Groups_Restrict_Categories::init();
