<?php

namespace Boston;

defined( 'ABSPATH' ) or exit;

// use Boston\Crud as BostonCrud;
use Boston\Crud;

class Manager {
    function __construct() {

    }

    /**
     * Returns current number of clients of a tax expert
     *
     * @param  [type] $user_id
     * @return void
     */
    public static function client_count( $expert_id ) {
        $table_name = Crud::prefix() . 'boston_tax_session';
        return (int) Crud::db()->get_var(
            Crud::db()->prepare(
                "SELECT count(id) FROM $table_name WHERE expert_id=%d",
                $expert_id
            )
        );
    }

    /**
     * Return current page slug
     *
     * @return string
     */
    public static function current_page() {      
        global $post;
        return $post->post_name;
    }

    public static function current_parent() {
        global $post;
        return $post->post_parent;
    }

    /**
     * Navigate current request to a specific page
     *
     * @param  string  $slug
     * @param  integer $status
     * @return void
     */
    public static function go( $slug = '', $status = 301 ) {
        wp_redirect( BOSTON_SITE_URL . '/' . $slug, $status );
        exit;
    }

    /**
     * Navigate current request to homepage
     *
     * @return void
     */
    public static function go_home() {
        wp_redirect( BOSTON_SITE_URL, 301 );
    }

    public static function prev_year() {
        return mktime( 0, 0, 0, 12, 31, ( date( 'Y', time() ) - 1 ) );
    }
}