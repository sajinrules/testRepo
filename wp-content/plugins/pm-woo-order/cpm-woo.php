<?php
/**
 * Plugin Name: WP Project Manager Pro - WooCommerce Order
 * Plugin URI: http://wedevs.com/plugin/wp-project-manager/
 * Description: Create projects when a new WooCommerce order has been made
 * Author: weDevs Team
 * Author URI: http://weDevs.com
 * Version: 0.1
 * License: GPL2
 */

/**
 * Copyright (c) 2014 weDevs Team (email: info@wedevs.com). All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * **********************************************************************
 */

if ( is_admin() ) {
    require_once dirname( __FILE__ ) . '/lib/wedevs-updater.php';

    new WeDevs_Plugin_Update_Checker( plugin_basename( __FILE__ ) );
}

/**
 * CPM_Woo_Order class
 *
 * @class CPM_Woo_Order The class that holds the entire CPM_Woo_Order plugin
 */
class CPM_Woo_Order {

    /**
     * Constructor for the CPM_Woo_Order class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     *
     * @uses add_action()
     */
    function __construct() {

        add_action( 'admin_menu', array($this, 'page_handelar') );
        add_action( 'wp_ajax_user_autocomplete', array($this, 'ajax_post') );
        add_action( 'wp_ajax_product_project_action', array($this, 'add_settings') );

        add_filter( 'cpm_settings_sections', array($this, 'add_settings_section'), 10, 1 );
        add_filter( 'cpm_settings_fields', array($this, 'add_settings_fields'), 10, 1 );

        add_action( 'woocommerce_checkout_order_processed', array($this, 'new_order'), 10, 2 );
        add_action( 'woocommerce_order_status_changed', array($this, 'new_order'), 10, 3 );
    }

    function add_settings_section( $sections ) {
        $sections[] = array(
            'id' => 'cpm_integration',
            'title' => 'Integration'
        );

        return $sections;
    }

    function add_settings_fields( $fields ) {

        $fields['cpm_integration'] = apply_filters( 'cpm_woo_settings_field_general', array(
            array(
                'name' => 'woo_duplicate',
                'label' => __( 'Project duplicate criteria', 'cpm' ),
                'type' => 'select',
                'default' => 'order',
                'options' => array(
                    'order' => __( 'After order', 'cpm' ),
                    'paid' => __( 'After paid', 'cpm' )
                )
            ),
        ) );

        return $fields;
    }

    function new_order( $order_id, $status, $slug = null ) {

        $duplicate_criteria = cpm_get_option( 'woo_duplicate' );

        if ( $duplicate_criteria == 'order' && current_filter() == 'woocommerce_order_status_changed' ) {
            return;
        } else if ( $duplicate_criteria == 'paid' && current_filter() == 'woocommerce_checkout_order_processed' ) {
            return;
        }

        if ( !is_array( $status ) && $slug == 'pending' ) {
            return;
        }

        $order = new WC_Order( $order_id );
        $items = $order->get_items();

        $settings = get_option( 'cpmwoo_settings' );
        $settings = is_array( $settings ) ? $settings : array();

        foreach ($items as $order_number => $order_info) {
            $product_id = $order_info['product_id'];

            foreach ($settings as $key => $settings_option) {

                if ( ( $product_id == $settings_option['product_id'] ) && ( $settings_option['type'] == 'duplicate' ) ) {

                    $this->duplicate_project( $settings_option['project_id'], $order_id );

                } else if ( $product_id == $settings_option['product_id'] && $settings_option['type'] == 'create' ) {

                    $this->create_project( $order_info, $order_id, $settings_option );

                }
            }
        }
    }

    function create_project( $order_info, $order_id, $settings_option ) {
        $posted = array(
            'project_name' => $order_id . '# - ' . $order_info['name'],
            'project_cat' => '-1',
            'project_description' => '',
            'role' => $settings_option['role'],
            'project_notify' => 'yes'
        );

        if ( WC()->session->order_awaiting_payment <= 0 || current_filter() == 'woocommerce_order_status_changed' ) {

            $id = CPM_Project::getInstance()->create( $project_id = 0, $posted );
        }
    }

    function duplicate_project( $project_id, $order_id ) {
        if ( WC()->session->order_awaiting_payment <= 0 || current_filter() == 'woocommerce_order_status_changed' ) {
            $id = CPM_Duplicate::getInstance()->create_duplicate( $project_id );
        }
    }

    function add_settings() {
        check_ajax_referer( 'cpmw_nonce' );

        $data = array();
        foreach ($_POST['type'] as $key => $value) {
            $data[$key]['type'] = $_POST['type'][$key];
            $data[$key]['project_id'] = isset( $_POST['project_id'][$key] ) ? $_POST['project_id'][$key] : null;
            $data[$key]['product_id'] = $_POST['product_id'][$key];

            if ( $data[$key]['type'] == 'create' ) {
                $data[$key]['role'] = isset( $_POST['role'][$key] ) ? $_POST['role'][$key] : null;
            } else {
                $data[$key]['role'] = null;
            }
        }

        $update = update_option( 'cpmwoo_settings', $data );

        if ( $update ) {
            wp_send_json_success( __( 'Update Successfull', 'cpmw' ) );
        } else {
            wp_send_json_error( __( 'Unknown Error', 'cpmw' ) );
        }
    }

    function ajax_post() {
        $count = $_POST['count_row'];
        $users = get_users( array(
            'search' => '*' . $_POST['term'] . '*',
            'search_columns' => array('user_login', 'user_email', 'nicename'),
        ) );

        foreach ($users as $user) {
            $data[] = array(
                'label' => $user->display_name,
                '_user_meta' => $this->create_user_meta( $user->ID, $count ),
            );
        }

        if ( isset( $data ) && count( $data ) ) {
            $user_info = json_encode( $data );
        } else {
            $data[] = array(
                'label' => __( 'No user found !', 'cpm' ),
                'value' => 'cpm_create_user',
                '_user_meta' => '',
            );
            $user_info = json_encode( $data );
        }

        wp_send_json_success( $user_info );
    }

    function create_user_meta( $user_id, $count, $role_name = null ) {
        $user_info = get_user_by( 'id', $user_id );
        $role_name = ( $role_name == null ) ? 'co_worker' : $role_name;
        $name = str_replace( ' ', '_', $user_info->display_name );
        ob_start();
        ?>
        <tr>
            <td><?php printf( '%s', ucfirst( $user_info->display_name ) ); ?></td>
            <td>

                <input type="radio" <?php checked( 'manager', $role_name ); ?> id="<?php echo 'manager_' . $count . '_' . $user_id; ?>" name="role[<?php echo $count; ?>][<?php echo $user_id; ?>]" value="manager">
                <label for="<?php echo 'manager_' . $count . '_' . $user_id; ?>"><?php _e( 'Manager', 'cpm' ); ?></label>

            </td>
            <td>

                <input type="radio" <?php checked( 'co_worker', $role_name ); ?> id="<?php echo 'co_worker_' . $count . '_' . $user_id; ?>"  name="role[<?php echo $count; ?>][<?php echo $user_id; ?>]" value="co_worker">
                <label for="<?php echo 'co_worker_' . $count . '_' . $user_id; ?>"><?php _e( 'Co-worker', 'cpm' ); ?></label>
            </td>
            <td>

                <input type="radio" <?php checked( 'client', $role_name ); ?> id="<?php echo 'client_' . $count . '_' . $user_id; ?>" name="role[<?php echo $count; ?>][<?php echo $user_id; ?>]" value="client">
                <label for="<?php echo 'client_' . $count . '_' . $user_id; ?>"><?php _e( 'Client', 'cpm' ); ?></label>
            </td>
            <td><a hraf="#" class="cpmw-del-proj-role cpm-assign-del-user"><span><?php _e( 'Delete', 'cpm' ); ?></span></a></td>
        </tr>

        <?php
        return ob_get_clean();
    }

    function page_handelar() {
        $capability = 'read'; //minimum level: subscriber
        $hook_product_project = add_submenu_page( 'cpm_projects', __( 'Woo Project', 'cpmw' ), __( 'Woo Project', 'cpmw' ), $capability, 'cpm_product_project', array($this, 'woocommerce_project') );
        add_action( 'admin_print_styles-' . $hook_product_project, array($this, 'scripts') );
    }

    function scripts() {
        wp_enqueue_script( 'jquery-ui-autocomplete' );
        wp_enqueue_script( 'product_project', plugins_url( 'assets/js/scripts.js', __FILE__ ), array('jquery'), false, true );
        wp_localize_script( 'product_project', 'product', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'is_admin' => ( is_admin() ) ? 'yes' : 'no',
            '_wpnonce' => wp_create_nonce( 'cpmw_nonce' ),
        ) );

        wp_enqueue_style( 'cpmw_style', plugins_url( 'assets/css/style.css', __FILE__ ), false, false, 'all' );
    }

    function settings_field( $key, $field, $projects, $products ) {
        $field['role'] = ( isset( $field['role'] ) && is_array( $field['role'] ) ) ? $field['role'] : array();
        $field['type'] = isset( $field['type'] ) ? $field['type'] : 'duplicate';
        $class = ( $field['type'] == 'duplicate' ) ? 'none' : '';
        ob_start();
        ?>
            <li class="cpmw-clone-area">
                <div class="cpmw-delete-li"><span><?php _e( 'Delete', 'cpmw' ); ?></span></div>
                <div class="cpmw-type-wrap">
                    <label for=""><?php _e( 'Action', 'cpmw' ); ?></label>
                    <select class="cpmw-type" name="type[<?php echo $key; ?>]">
                        <option value="duplicate" <?php selected( $field['type'], 'duplicate' ); ?>><?php _e( 'Duplicate', 'cpmw' ); ?></option>
                        <option value="create" <?php selected( $field['type'], 'create' ); ?> ><?php _e( 'Create', 'cpmw' ); ?></option>
                    </select>
                </div>

                <div class="cpmw-product-wrap">
                    <label for=""><?php _e( 'Product', 'cpmw' ); ?></label>
                    <select name="product_id[<?php echo $key; ?>]">
                        <?php
                        echo $this->get_product_option( $field['product_id'], $products );
                        ?>
                    </select>
                </div>

                <div class="cpmw-project-fields-wrap">
                    <?php if ( $field['type'] == 'duplicate' ) { ?>
                        <div class="cpmw-project-fields">
                            <label for=""><?php _e( 'Project', 'cpmw' ); ?></label>

                            <select name="project_id[<?php echo $key; ?>]">
                                <?php echo $this->get_project_option( $field['project_id'], $projects ); ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>

                <div class="cpmw-clear"></div>
                <div class="cpmw-role-wrap" style="display: <?php echo $class; ?>">

                    <div class="cpm-form-item cpmw-project-role">

                        <table>
                            <?php
                            foreach ($field['role'] as $user_id => $role_name) {

                                echo $this->create_user_meta( $user_id, $key, $role_name );
                            }
                            ?>

                        </table>
                    </div>

                    <label for=""><?php _e( 'Co-workers', 'cpmw' ); ?></label>
                    <input class="cpmw-project-coworker"  type="text" name="" placeholder="<?php esc_attr_e( 'Add co-workers...', 'cpm' ); ?>" size="45">

                </div>
            </li>

        <?php
        return ob_get_clean();
    }

    function woocommerce_project( $settings ) {
        $project_obj = CPM_Project::getInstance();
        $projects = $project_obj->get_projects();

        $args = array('post_type' => 'product', 'numberposts' => -1);
        $products = get_posts( $args );
        $option_value = get_option( 'cpmwoo_settings' );
        echo '<div class="wrap">';
        require_once dirname( __FILE__ ) . '/views/index.php';
        echo '</div>';
    }

    function get_project_option( $project_id = null, $projects ) {

        ob_start();
        ?>

        <option value="-1"><?php _e( '- Select -', 'cpmw' ); ?></option>
        <?php
        if ( $projects ) {
            foreach ($projects as $project) {
                ?>
                <option <?php selected( $project_id, $project->ID ); ?> value="<?php echo $project->ID; ?>"><?php echo $project->post_title; ?></option>
                <?php
            }
        }

        return ob_get_clean();
    }

    function get_product_option( $product_id = null, $products ) {

        ob_start();
        ?>

        <option value="-1"><?php _e( '- Select -', 'cpmw' ); ?></option> <?php
        if ( $products ) {
            foreach ($products as $product) {
                ?>
                <option <?php selected( $product_id, $product->ID ); ?> value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                <?php
            }
        }

        return ob_get_clean();
    }

}

/**
 * Initialize the plugin
 *
 * @return void
 */
function cpm_woo_order_init() {
    new CPM_Woo_Order();
}

add_action( 'plugins_loaded', 'cpm_woo_order_init' );