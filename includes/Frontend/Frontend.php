<?php
namespace Boston\Frontend;

/**
 * Handles the frontend functions and elements
 */
class Frontend {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts'] );
        add_filter( 'wp_nav_menu_args', [$this, 'menu_controller'] );
    }

    /**
     * Loads the frontend scripts
     *
     * @return void
     */
    public function enqueue_scripts() {
        wp_enqueue_script( 'boston-frontend-script' );
        wp_enqueue_style( 'boston-frontend-style' );
    }

    public function menu_controller( $args = '' ) {
        if ( is_user_logged_in() ) {
            $args['menu'] = 'logged-in';
        } else {
            $args['menu'] = 'logged-out';
        }
        return $args;
    }
}
