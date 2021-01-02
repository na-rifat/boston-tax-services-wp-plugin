<?php

namespace Boston;

/**
 * Handles ajax requests
 */
class Ajax {
    public $current_user;
    /**
     * Builds the class
     */
    function __construct() {
        boston_ajax( 'boston_options', [$this, 'save_options'] );
        boston_ajax( 'save_dashboard_shortcodes', [$this, 'save_dashboard_shortcodes'] );
        boston_ajax( 'user_agreement_accept', [$this, 'user_agreement_accept'] );
        add_action( 'init', [$this, 'init'] );
    }

    /**
     * Initializes the class
     *
     * @return void
     */
    public function init() {
        $this->current_user = get_current_user_id();
    }
    /**
     * Handles the agreement accept function
     *
     * @return void
     */
    public function user_agreement_accept() {
        if ( ! wp_verify_nonce( boston_var( '_wpnonce' ), 'user_agreement_accept' ) ) {
            wp_send_json_error( [
                'message' => 'Wrong data!',
            ] );
            return;
        }

        $agree = boston_var( 'agree' );

        switch ( $agree ) {
            case 'yes':
                update_user_meta( $this->current_user, 'havenewslatter', 'yes' );
                do_action( 'boston_wizard_update' );
                wp_send_json_success( [
                    'next' => true,
                ] );
                exit;
                break;
            case 'no':
                update_user_meta( $this->current_user, 'agreement_accept_step', 'complete' );
                do_action( 'boston_wizard_update' );
                wp_send_json_success( [
                    'next'      => false,
                    'user'      => $this->current_user,
                    'new_nonce' => wp_create_nonce( 'user_agreement_accept' ),
                ] );
                exit;
                break;
            case 'delete':
                wp_delete_user( $this->current_user );
                wp_send_json_success( [
                    'deleted' => true,
                ] );
                exit;
                break;
            default:
                wp_send_json_error();
                exit;
                break;
        }
    }

    /**
     * Saves options
     *
     * @return void
     */
    public function save_options() {

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'boston_options_nonce' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid nonce!',
            ] );
            return;
        }

        $social_login_information = [
            'facebook_app_id'     => boston_var( 'facebook_app_id' ),
            'facebook_app_secret' => boston_var( 'facebook_app_secret' ),
            'twitter_app_id'      => boston_var( 'twitter_app_id' ),
            'twtiter_app_secret'  => boston_var( 'twitter_app_secret' ),
            'google_app_id'       => boston_var( 'google_app_id' ),
            'google_app_secret'   => boston_var( 'google_app_secret' ),
        ];

        foreach ( $social_login_information as $key => $value ) {
            update_option( $key, $value );
        }

        wp_send_json_success( [
            'message' => 'Settings saved succesfully',
        ] );
        exit;
    }

    public function save_dashboard_shortcodes() {

        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'save_dashboard_shortcodes' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid nonce!',
            ] );
            return;
        }

        $shortcodes = [
            'current_year_taxes'               => boston_var( 'current_year_taxes' ),
            'prior_year_taxes'                 => boston_var( 'prior_year_taxes' ),
            'boston_user_messages'             => boston_var( 'boston_user_messages' ),
            'irs_correspondence'               => boston_var( 'irs_correspondence' ),
            'boston_user_settings'             => boston_var( 'boston_user_settings' ),
            'boston_welcome_page'              => boston_var( 'boston_welcome_page' ),
            'user_wizard_step_1'               => boston_var( 'user_wizard_step_1' ),
            'user_wizard_step_2'               => boston_var( 'user_wizard_step_2' ),
            'user_wizard_step_3'               => boston_var( 'user_wizard_step_3' ),
            'user_wizard_step_4'               => boston_var( 'user_wizard_step_4' ),
            'user_wizard_step_5'               => boston_var( 'user_wizard_step_5' ),
            'wizard_page_slug'                 => boston_var( 'wizard_page_slug' ),
            'dashboard_page_slug'              => boston_var( 'dashboard_page_slug' ),
            'wizard_form_id_1'                 => boston_var( 'wizard_form_id_1' ),
            'wizard_form_id_2'                 => boston_var( 'wizard_form_id_2' ),
            'wizard_form_id_3'                 => boston_var( 'wizard_form_id_3' ),
            'restricted_plages_for_all'        => boston_var( 'restricted_plages_for_all' ),
            'restricted_plages_for_logged_out' => boston_var( 'restricted_plages_for_logged_out' ),
            'tax_expert_dashboard_page_slug'   => boston_var( 'tax_expert_dashboard_page_slug' ),
            'client_dashboard_page_slug'       => boston_var( 'client_dashboard_page_slug' ),
        ];

        foreach ( $shortcodes as $key => $value ) {
            update_option( $key, $value );
        }

        wp_send_json_success( [
            'message' => 'Shortcodes saved successfully',
        ] );
        exit;
    }

}