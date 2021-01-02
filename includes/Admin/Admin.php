<?php
namespace Boston\Admin;

use Boston\Menu\Adminmenu as Menu;

/**
 * Handles the admin functions
 */
class Admin {
    public function __construct() {
        new Menu();
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * Loads admin assets
     *
     * @return void
     */
    public function enqueue_assets(){
        wp_enqueue_script( 'boston-admin-script' );
        wp_enqueue_style( 'boston-admin-style' );        
    }
}