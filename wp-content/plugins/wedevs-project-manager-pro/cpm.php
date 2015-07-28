<?php
/**
 * Plugin Name: WP Project Manager PRO
 * Plugin URI: http://wedevs.com/plugin/wp-project-manager/
 * Description: A WordPress Project Management plugin. Simply it does everything and it was never been easier with WordPress.
 * Author: Tareq Hasan
 * Author URI: http://tareq.weDevs.com
 * Version: 1.1
 * License: GPL2
 */

/**
 * Copyright (c) 2013 Tareq Hasan (email: tareq@wedevs.com). All rights reserved.
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


/**
 * Project Manager bootstrap class
 *
 * @author Tareq Hasan
 */
class WeDevs_CPM {

    /**
     * @var The single instance of the class
     * @since 0.1
     */
    protected static $_instance = null;

    /**
     * @var CPM_Project $project
     */
    public $project;

    /**
     * @var CPM_Message $message
     */
    public $message;

    /**
     * @var CPM_Task $task
     */
    public $task;

    /**
     * @var CPM_Milestone $milestone
     */
    public $milestone;

    /**
     * @var CPM_Router $router
     */
    public $router;

    /**
     * @var CPM_Activity $activity
     */
    public $activity;

    /**
     * @var CPM_Ajax $ajax
     */
    public $ajax;

    /**
     * @var CPM_Notification $notification
     */
    public $notification;

    /**
     * CPM Constructor.
     */
    function __construct() {
        $this->init();

        add_action( 'admin_menu', array($this, 'admin_menu') );

        add_action( 'plugins_loaded', array( $this, 'cpm_content_filter' ) );
        add_action( 'plugins_loaded', array($this, 'load_textdomain') );
        add_action( 'plugins_loaded', array($this, 'plugins_loaded') );


        add_action( 'wp_enqueue_scripts', array( $this, 'admin_scripts' ) );

        register_activation_hook( __FILE__, array($this, 'install') );
        register_deactivation_hook( __FILE__, array($this, 'deactivate') );
    }

    /**
     * Main CPM Instance
     *
     * @since 1.1
     * @static
     * @see cpm()
     * @return CPMRP - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Add filters for text displays on Project Manager texts
     *
     * @since 0.4
     */
    function cpm_content_filter() {
        add_filter( 'cpm_get_content', 'wptexturize' );
        add_filter( 'cpm_get_content', 'convert_smilies' );
        add_filter( 'cpm_get_content', 'convert_chars' );
        add_filter( 'cpm_get_content', 'wpautop' );
        add_filter( 'cpm_get_content', 'shortcode_unautop' );
        add_filter( 'cpm_get_content', 'prepend_attachment' );
        add_filter( 'cpm_get_content', 'make_clickable' );
    }

    /**
     * Initial do
     *
     * @since 1.1
     * @return type
     */
    function init() {
        $this->define_constants();
        spl_autoload_register( array( __CLASS__, 'autoload' ) );
        $this->page()->cpm_function();

        $this->version    = CPM_VERSION;
        $this->db_version = CPM_DB_VERSION;

        $this->instantiate();
        $this->includes();
    }

    /**
     * Autoload class files on demand
     *
     * @param string $class requested class name
     */
    function autoload( $class ) {
        $name = explode( '_', $class );
        if ( isset( $name[1] ) ) {
            $class_name = strtolower( $name[1] );
            $filename = dirname( __FILE__ ) . '/class/' . $class_name . '.php';

            if ( file_exists( $filename ) ) {
                require_once $filename;
            }
        }
    }

    /**
     * Define cpmrp Constants
     *
     * @since 1.1
     * @return type
     */
    private function define_constants() {
        $this->define( 'CPM_VERSION', '1.1' );
        $this->define( 'CPM_DB_VERSION', '1.1' );
        $this->define( 'CPM_PATH', dirname( __FILE__ ) );
        $this->define( 'CPM_URL', plugins_url( '', __FILE__ ) );
        $this->define( 'CPM_PRO', true );
    }

    /**
     * Define constant if not already set
     *
     * @since 1.1
     *
     * @param  string $name
     * @param  string|bool $value
     * @return type
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
        }
    }

    /**
     * Instantiate all the required classes
     *
     * @since 0.1
     */
    function instantiate() {
        $this->project   = CPM_Project::getInstance();
        $this->message   = CPM_Message::getInstance();
        $this->task      = CPM_Task::getInstance();
        $this->milestone = CPM_Milestone::getInstance();

        $this->activity     = new CPM_Activity();
        $this->ajax         = new CPM_Ajax();
        $this->notification = new CPM_Notification();

        // instantiate admin settings only on admin page
        if ( is_admin() ) {
            $this->admin   = new CPM_Admin();
            $this->updates = new CPM_Updates();
            $this->upgrade = new CPM_Upgrade();
        }
    }

     /**
     * page router instanciate
     *
     * @since 1.1
     */
    function page() {
        $this->router = CPM_Router::instance();
        return $this->router;
    }

    /**
     * Include the required files
     *
     * @return void
     */
    function includes() {
        if ( is_admin() ) {
            $this->router->includes();
        }
    }

    /**
     * Runs the setup when the plugin is installed
     *
     * @since 0.3.1
     */
    function install() {
        CPM_Upgrade::getInstance()->plugin_upgrades();
        wp_schedule_event( time(), 'daily', 'cpm_daily_digest' );
    }

    /**
     * Deactivation actions
     *
     * @since 1.1
     *
     * @return void
     */
    public function deactivate() {
        wp_clear_scheduled_hook( 'cpm_daily_digest' );
    }

    /**
     * Load plugin textdomain
     *
     * @since 0.3
     */
    function load_textdomain() {
        load_plugin_textdomain( 'cpm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Run actions on `plugins_loaded` hook
     *
     * @since 1.1
     *
     * @return void
     */
    public function plugins_loaded() {

        if( cpm_get_option( 'daily_digest' ) == 'off' ) {
            return;
        }

        if ( get_user_meta( get_current_user_id(), '_user_daily_digets_status', true ) == 'off' ) {
            return;
        }

        new CPM_Digest();
    }

    /**
     * Load all the plugin scripts and styles only for the
     * project area
     *
     * @since 0.1
     */
    static function admin_scripts() {
        $upload_size = intval( cpm_get_option( 'upload_limit') ) * 1024 * 1024;

        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-autocomplete');
        wp_enqueue_script( 'jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-prettyPhoto', plugins_url( 'assets/js/jquery.prettyPhoto.js', __FILE__ ), array( 'jquery' ), false, true );
        wp_enqueue_script( 'jquery-chosen', plugins_url( 'assets/js/chosen.jquery.min.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'validate', plugins_url( 'assets/js/jquery.validate.min.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'plupload-handlers' );

        wp_enqueue_script( 'cpm_admin', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery', 'jquery-prettyPhoto' ), false, true );
        wp_enqueue_script( 'cpm_task', plugins_url( 'assets/js/task.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_script( 'cpm_uploader', plugins_url( 'assets/js/upload.js', __FILE__ ), array('jquery', 'plupload-handlers'), false, true );

        wp_localize_script( 'cpm_admin', 'CPM_Vars', array(
            'ajaxurl'  => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'cpm_nonce' ),
            'is_admin' => is_admin() ? 'yes' : 'no',
            'plupload' => array(
                'browse_button'       => 'cpm-upload-pickfiles',
                'container'           => 'cpm-upload-container',
                'max_file_size'       => $upload_size . 'b',
                'url'                 => admin_url( 'admin-ajax.php' ) . '?action=cpm_ajax_upload&nonce=' . wp_create_nonce( 'cpm_ajax_upload' ),
                'flash_swf_url'       => includes_url( 'js/plupload/plupload.flash.swf' ),
                'silverlight_xap_url' => includes_url( 'js/plupload/plupload.silverlight.xap' ),
                'filters'             => array(array('title' => __( 'Allowed Files' ), 'extensions' => '*')),
                'resize'              => array('width' => (int) get_option( 'large_size_w' ), 'height' => (int) get_option( 'large_size_h' ), 'quality' => 100)
            )
        ) );

        wp_enqueue_style( 'cpm_admin', plugins_url( 'assets/css/admin.css', __FILE__ ) );
        wp_enqueue_style( 'cpm_prettyPhoto', plugins_url( 'assets/css/prettyPhoto.css', __FILE__ ) );
        wp_enqueue_style( 'jquery-ui', plugins_url( 'assets/css/jquery-ui-1.9.1.custom.css', __FILE__ ) );
        wp_enqueue_style( 'jquery-chosen', plugins_url( 'assets/css/chosen.css', __FILE__ ) );

    }

    /**
     * Load my task scripts
     *
     * @return void
     */
    static function my_task_scripts() {
        self::admin_scripts();
        wp_enqueue_script( 'cpm_mytask', plugins_url( 'assets/js/mytask.js', __FILE__ ), array('jquery', 'cpm_task'), false, true );
    }

    /**
     * Load calendar scripts
     *
     * @return void
     */
    static function calender_scripts() {
        self::admin_scripts();

        wp_enqueue_script( 'fullcalendar', plugins_url( 'assets/js/fullcalendar.min.js', __FILE__ ), array('jquery'), false, true );
        wp_enqueue_style( 'fullcalendar', plugins_url( 'assets/css/fullcalendar.css', __FILE__ ) );
    }

    /**
     * Register the plugin menu
     *
     * @since 0.1
     */
    function admin_menu() {
        $capability = 'read'; //minimum level: subscriber

        $count_task = CPM_Task::getInstance()->mytask_count();
        $current_task = isset( $count_task['current_task'] ) ? $count_task['current_task'] : 0;
        $outstanding = isset( $count_task['outstanding'] ) ? $count_task['outstanding'] : 0;
        $active_task =  $current_task + $outstanding;

        $mytask_text = __( 'My Tasks', 'cpm' );
        if ( $active_task ) {
            $mytask_text = sprintf( __( 'My Tasks %s', 'cpm' ), '<span class="awaiting-mod count-1"><span class="pending-count">' . $active_task . '</span></span>');
        }

        $hook = add_menu_page( __( 'Project Manager', 'cpm' ), __( 'Project Manager', 'cpm' ), $capability, 'cpm_projects', array($this, 'admin_page_handler'), 'dashicons-networking', 3 );
        add_submenu_page( 'cpm_projects', __( 'Projects', 'cpm' ), __( 'Projects', 'cpm' ), $capability, 'cpm_projects', array($this, 'admin_page_handler') );
        $hook_my_task  = add_submenu_page( 'cpm_projects', __( 'My Tasks', 'cpm' ), $mytask_text, $capability, 'cpm_task', array($this, 'my_task') );
        $hook_calender = add_submenu_page( 'cpm_projects', __( 'Calendar', 'cpm' ), __( 'Calendar', 'cpm' ), $capability, 'cpm_calendar', array($this, 'admin_page_handler') );

        if ( current_user_can( 'manage_options' ) ) {
            add_submenu_page( 'cpm_projects', __( 'Categories', 'cpm' ), __( 'Categories', 'cpm' ), $capability, 'edit-tags.php?taxonomy=project_category' );
        }
        add_submenu_page( 'cpm_projects', __( 'Add-ons', 'cpm' ), __( 'Add-ons', 'cpm' ), 'manage_options', 'cpm_addons', array($this, 'admin_page_addons') );
        add_action( 'admin_print_styles-' . $hook, array($this, 'admin_scripts') );
        add_action( 'admin_print_styles-' . $hook_my_task, array($this, 'my_task_scripts') );
        add_action( 'admin_print_styles-' . $hook_calender, array($this, 'calender_scripts') );
    }

    /**
     * Render my tasks page
     *
     * @since 0.5
     * @return void
     */
    function my_task() {
        $this->router->my_task();
    }

    /**
     * Main function that renders the admin area for all the project
     * related markup.
     *
     * @since 0.1
     */
    function admin_page_handler() {
        $this->router->output();
    }

    /**
     * Shows the add-ons page on admin
     *
     * @return void
     */
    function admin_page_addons() {
        $this->router->admin_page_addons();
    }
}

/**
 * Returns the main instance.
 *
 * @since  1.1
 * @return WeDevs_CPM
 */
function cpm() {
    return WeDevs_CPM::instance();
}

//cpm instance.
cpm();
