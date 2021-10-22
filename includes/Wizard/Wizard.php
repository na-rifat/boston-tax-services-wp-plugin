<?php

namespace Boston\Wizard;

/**
 * User wizard handler class
 */
class Wizard {
    public $current_user;
    public $step_prefix  = 'user_wizard_step_';
    public $con_meta_key = 'wizard_current_con';
    public $user;

    /**
     * Builds the class
     */
    public function __construct() {

        add_action( 'init', [$this, 'initialize'] );
        add_action( 'enqeueu_assets', [$this, 'enqeueu_assets'] );
        boston_ajax( 'update_wizard', [$this, 'update_wizard'] );

    }

    public function enqeueu_assets() {
        wp_enqueue_script( 'boston-file-script' );
        wp_enqueue_script( 'boston-frontend-script' );
    }

    /**
     * Initializes the class
     *
     * @return void
     */
    public function initialize() {
        $this->current_user = get_current_user_id();
        if ( ! is_admin() && is_user_logged_in() ) {
            add_action( 'wp', [$this, 'page_redirect'] );
        }

        add_action( 'wp', [$this, 'admin_redirect'] );
    }

    public function admin_redirect() {
        $roles = get_userdata( get_current_user_id() );

        if ( $roles != false ) {
            $roles = $roles->roles;
        }

        if ( is_page( 'dashboard' ) && in_array( 'administrator', $roles ) ) {
            wp_redirect( admin_url( '/admin.php?page=current-year-clients' ), 301 );
            return;
        }
    }

    /**
     * Redirect from a specific page
     *
     * @return void
     */
    public function page_redirect() {

        // if ( $this->user->account_type( $this->user->info_list['id'] ) != 'client' ) {
        //     return;
        // }

        // if ( is_page('dashboard') && get_user_meta( $this->current_user, 'wizard_current_con', true ) != '5' ) {
        //     // wp_redirect( site_url( "/" . get_option( 'wizard_page_slug' ) ), 301 );
        //     return;
        // }

        // if ( is_page( get_option( 'wizard_page_slug' ) ) && get_user_meta( get_current_user_id(), 'wizard_current_con', true ) == 5 ) {
        //     wp_redirect( site_url( '/' . get_option( 'dashboard_page_slug' ) ), 301 );
        //     return;
        // }

    }

    /**
     * Provides shortcodes based on condition
     *
     * @return void
     */
    public function output_wizard() {
        $current_con = get_user_meta( $this->current_user, 'wizard_current_con', true );
        $current_con = empty( $current_con ) ? 0 : $current_con;
        // $step        = "{$this->step_prefix}{$current_con}";

        /*
        #Steps
        1. Q&A (Contact form)
        2. Ask for agreement
        3. Engagement letter (Docusign)
        4. Additional information (Contact form)
         */
        $shortcodes = [
            '[contact-form-7 id="7" title="Q&A form"]',
        ];

        return do_shortcode( $shortcodes[$current_con] );
    }

    /**
     * Updates the wizard steps
     * @return void
     */
    public function update_step() {
        if ( ! empty( $_POST['update_boston_wizard_step'] ) && $_POST['update_boston_wizard_step'] == true ) {
            $prev_val = get_user_meta( $this->current_user, $this->con_meta_key, true );
            update_user_meta( $this->current_user, $this->con_meta_key, intval( $prev_val ) + 1 );
        }
    }

    /**
     * Checks and update the wizard
     */
    public function update_wizard() {
        if ( ! wp_verify_nonce( $_POST['nonce'], 'update_wizard' ) ) {
            echo "Wrong nonce!";
            return;
            exit;
        }

        $prev_val = get_user_meta( $this->current_user, $this->con_meta_key, true );
        update_user_meta( $this->current_user, $this->con_meta_key, intval( $prev_val ) + 1 );

        echo $this->output_wizard();
        exit;
    }
}