<?php
namespace Boston\Menu;
/**
 * Handles admin menu
 */
class Adminmenu {
    function __construct() {
        add_action( 'admin_menu', [$this, 'register_menu'] );
    }

    /**
     * Registers the menu to admin panel
     *
     * @return void
     */
    public function register_menu() {
        $parent_slug = 'boston';
        $capability  = 'manage_options';

        add_menu_page( 'Boston', 'Boston', $capability, 'boston', [$this, 'menu_page'], 'dashicons-admin-tools', 2 );
        add_submenu_page( $parent_slug, 'Boston', 'Boston', $capability, $parent_slug, [$this, 'menu_page'] );
        add_submenu_page( $parent_slug, 'User dashboard settings', 'User dashboard settings', $capability, 'boston-user-dashbaord-settings', [$this, 'user_dashboard_settings'] );
    }

    /**
     * Handles the  menu page
     *
     * @return void
     */
    public function menu_page() {
        boston_admin_template(__DIR__, 'boston_menu');
    }

    /**
     * Manages user dashboard settings page
     *
     * @return void
     */
    public function user_dashboard_settings() {
        boston_admin_template(__DIR__, 'user_dashboard_settings');
    }
}