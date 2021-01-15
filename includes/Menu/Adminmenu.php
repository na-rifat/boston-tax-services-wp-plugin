<?php
namespace Boston\Menu;
/**
 * Handles admin menu
 */
class Adminmenu {
    public $user;
    function __construct($user) {
        $this->user = $user;
        add_action( 'admin_menu', [$this, 'register_menu'] );
    }

    /**
     * Registers the menu to admin panel
     *
     * @return void
     */
    public function register_menu() {
        $parent_slug = 'options-general.php';
        $capability  = 'manage_options';

        add_menu_page( 'Current year clients', 'Current year clients', $capability, 'current-year-clients', [$this->user, 'assigned_clients'], 'dashicons-groups', 2 );
        add_submenu_page( $parent_slug, 'Boston social login', 'Boston social login', $capability, 'boston-social-login', [$this, 'menu_page'] );
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