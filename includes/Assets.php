<?php

namespace Boston;

/**
 * Registers essential assets
 */
class Assets {
    /**
     * Construct assets class
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', [$this, 'register'] );
        add_action( 'admin_enqueue_scripts', [$this, 'register'] );
    }

    /**
     * Return scripts from array
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'boston-frontend-script' => [
                'src'     => jsfile( 'frontend.js' ),
                'version' => jsversion( 'frontend.js' ),
                'deps'    => ['jquery'],
            ],
            'boston-admin-script'    => [
                'src'     => jsfile( 'admin.js' ),
                'version' => jsversion( 'admin.js' ),
                'deps'    => ['jquery'],
            ],
            'boston-file-script'     => [
                'src'     => jsfile( 'file.js' ),
                'version' => jsversion( 'file.js' ),
                'deps'    => ['jquery'],
            ],
        ];
    }

    /**
     * Return styles from array
     *
     * @return array
     */
    public function get_styles() {
        return [
            'boston-frontend-style' => [
                'src'     => cssfile( 'frontend.css' ),
                'version' => cssversion( 'frontend.css' ),
            ],
            'boston-admin-style'    => [
                'src'     => cssfile( 'admin.css' ),
                'version' => cssversion( 'admin.css' ),
            ],
            'boston-custom-style'   => [
                'src'     => cssfile( 'custom.css' ),
                'version' => cssversion( 'custom.css' ),
            ],
        ];
    }

    /**
     * Return localize variable from array
     *
     * @return array
     */
    public function get_localize() {
        global $post;
        return [
            'boston-admin-script'    => [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            ],
            'boston-frontend-script' => [
                'boston_sc'    => [
                    'facebook' => site_url( 'index.php?boston-connect=facebook' ),
                    'twitter'  => site_url( 'index.php?boston-connect=twitter' ),
                    'google'   => site_url( 'index.php?boston-connect=google' ),
                ],
                'ajaxurl'      => admin_url( 'admin-ajax.php' ),
                'siteurl'      => site_url(),
                'wizard_nonce' => wp_create_nonce( 'update_wizard' ),
            ],
            'boston-file-script'     => [
                'ajaxurl'          => admin_url( 'admin-ajax.php' ),
                'filenonce'        => wp_create_nonce( 'upload_tax_file' ),
                'wizard_nonce'     => wp_create_nonce( 'update_wizard' ),
                'wizard_form_id_1' => get_option( 'wizard_form_id_1' ),
                'wizard_form_id_2' => get_option( 'wizard_form_id_2' ),
                'wizard_form_id_3' => get_option( 'wizard_form_id_3' ),
                'wizard_page'      => get_option( 'wizard_page_slug' ),
                'current_page'     => $post->name,
                'messages_nonce'   => wp_create_nonce( 'get_messages' ),
            ],
        ];
    }

    /**
     * Registers scripts, styles and localize variables
     *
     * @return void
     */
    public function register() {
        $scripts = $this->get_scripts();

        foreach ( $scripts as $handle => $script ) {
            $deps = isset( $script['deps'] ) ? $script['deps'] : false;

            wp_register_script( $handle, $script['src'], $deps, true );

        }

        $styles = $this->get_styles();

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps );
        }

        $localize = $this->get_localize();

        foreach ( $localize as $handle => $vars ) {
            wp_localize_script( $handle, 'boston', $vars );
        }
    }
}