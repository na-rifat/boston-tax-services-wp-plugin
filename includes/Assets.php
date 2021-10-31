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
        add_action( 'wp_enqueue_scripts', [$this, 'load'] );
        add_action( 'admin_enqueue_scripts', [$this, 'load'] );
    }

    /**
     * Return scripts from array
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'boston-frontend-script'  => [
                'src'     => jsfile( 'frontend.js' ),
                'version' => jsversion( 'frontend.js' ),
                'deps'    => ['jquery'],
            ],
            'boston-admin-script'     => [
                'src'     => jsfile( 'admin.js' ),
                'version' => jsversion( 'admin.js' ),
                'deps'    => ['jquery'],
            ],
            'boston-file-script'      => [
                'src'     => jsfile( 'file.js' ),
                'version' => jsversion( 'file.js' ),
                'deps'    => ['jquery'],
            ],
            'boston-messages-script'  => [
                'src'     => jsfile( 'messages.js' ),
                'version' => jsversion( 'messages.js' ),
                'deps'    => ['jquery'],
            ],
            'boston-bootstrap-script' => [
                'src'     => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js',
                'version' => '3.3.5',
            ],
            // 'boston-rich-text-editor-script' => [
            //     'src'     => jsfile( 'rich_text.js' ),
            //     'version' => jsversion( 'rich_text.js' ),
            //     'deps'    => ['jquery'],
            // ],
            'boston-universal'        => [
                'src'     => jsfile( 'universal.js' ),
                'version' => jsversion( 'universal.js' ),
                'deps'    => ['jquery'],
            ],
            'calendly'                => [
                'src'     => 'https://calendly.com/assets/external/widget.js',
                'version' => '1.0',
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
            'boston-frontend-style'    => [
                'src'     => cssfile( 'frontend.css' ),
                'version' => cssversion( 'frontend.css' ),
            ],
            'boston-admin-style'       => [
                'src'     => cssfile( 'admin.css' ),
                'version' => cssversion( 'admin.css' ),
            ],
            'boston-custom-style'      => [
                'src'     => cssfile( 'custom.css' ),
                'version' => cssversion( 'custom.css' ),
            ],
            'boston-bootstrap-style'   => [
                'src'     => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css',
                'version' => '3.3.5',
            ],
            'boston-fontawesome-style' => [
                'src'     => 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css',
                'version' => '4.4.0',
            ],
            // 'boston-rich-text-editor-style' => [
            //     'src'     => cssfile( 'rich_text.css' ),
            //     'version' => cssversion( 'rich_text.css' ),
            // ],
            'boston-universal'         => [
                'src'     => cssfile( 'universal.css' ),
                'version' => cssversion( 'universal.css' ),
            ],
            'calendly'                 => [
                'src'     => 'https://calendly.com/assets/external/widget.css',
                'version' => '1.0',
            ],
            'dashboard-styles'         => [
                'src'     => cssfile( 'dashboard.css' ),
                'version' => cssversion( 'dashboard.css' ),
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
                'ajaxurl'                 => admin_url( 'admin-ajax.php' ),
                'send_message_nonce'      => wp_create_nonce( 'send_message_from_admin' ),
                'assign_tax_expert_nonce' => wp_create_nonce( 'assign_expert' ),
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
                'ajaxurl'                       => admin_url( 'admin-ajax.php' ),
                'filenonce'                     => wp_create_nonce( 'upload_tax_file' ),
                'wizard_nonce'                  => wp_create_nonce( 'update_wizard' ),
                'wizard_form_id_1'              => get_option( 'wizard_form_id_1' ),
                'wizard_form_id_2'              => get_option( 'wizard_form_id_2' ),
                'wizard_form_id_3'              => get_option( 'wizard_form_id_3' ),
                'wizard_page'                   => get_option( 'wizard_page_slug' ),
                'current_page'                  => basename( get_permalink(), '.php' ),
                'calendly_nonce'                => wp_create_nonce( 'process_calendly' ),
                'client_option_manager_nonce'   => wp_create_nonce( 'client_option_manager' ),
                'upload_file_from_expert_nonce' => wp_create_nonce( 'upload_file_from_expert' ),
                'boston_sc'                     => [
                    'facebook' => site_url( 'index.php?boston-connect=facebook' ),
                    'twitter'  => site_url( 'index.php?boston-connect=twitter' ),
                    'google'   => site_url( 'index.php?boston-connect=google' ),
                ],
                'mark_as_read_nonce'            => wp_create_nonce( 'mark_as_read' ),
                'approve_file_nonce'            => wp_create_nonce( 'approve_file' ),
                'upload_prepared_nonce'         => wp_create_nonce( 'upload_prepared' ),
                'upload_irs_document_nonce'     => wp_create_nonce( 'upload_irs_document' ),
            ],
            'boston-messages-script' => [
                'ajaxurl'        => admin_url( 'admin-ajax.php' ),
                'current_page'   => basename( get_permalink(), '.php' ),
                'messages_nonce' => wp_create_nonce( 'get_messages' ),
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

            wp_register_script( $handle, $script['src'], $deps, ! empty( $script['version'] ) ? $script['version'] : false, true );

        }

        $styles = $this->get_styles();

        foreach ( $styles as $handle => $style ) {
            $deps = isset( $style['deps'] ) ? $style['deps'] : false;

            wp_register_style( $handle, $style['src'], $deps, ! empty( $style['version'] ) ? $style['version'] : false );
        }

        $localize = $this->get_localize();

        foreach ( $localize as $handle => $vars ) {
            wp_localize_script( $handle, 'boston', $vars );
        }
    }

    /**
     * Loads the scripts to frontend
     *
     * @return void
     */
    public function load() {
        if ( ! empty( $_GET['tab'] ) && $_GET['tab'] == 'messages' ) {
            wp_enqueue_script( 'boston-messages-script' );
        }

        wp_enqueue_script( 'boston-universal' );
        wp_enqueue_style( 'boston-universal' );

        wp_enqueue_script( 'boston-messages-script' );

        if ( ! is_admin() ) {
            wp_enqueue_script( 'calendly' );
            wp_enqueue_style( 'calendly' );
        }

        wp_enqueue_style( 'dashboard-styles' );
        wp_enqueue_style( 'boston-fontawesome-style' );

        // wp_enqueue_style('boston-bootstrap-style');
        /**
         * Used for rich text editor,
         * disabled for incorrect output
         */
        // if ( is_admin() ) {
        //     wp_enqueue_script( 'boston-rich-text-editor-script' );
        //     wp_enqueue_script( 'boston-bootstrap-script' );
        //     wp_enqueue_style( 'boston-rich-text-editor-style' );
        //     wp_enqueue_style( 'boston-bootstrap-style' );
        // }
    }
}