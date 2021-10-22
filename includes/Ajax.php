<?php

namespace Boston;

/**
 * Handles ajax requests
 */
class Ajax {
    /**
     * @var mixed
     */
    public $current_user;
    public $messages;
    public $user;
    /**
     * Builds the class
     */
    function __construct() {
        boston_ajax( 'boston_options', [$this, 'save_options'] );
        boston_ajax( 'save_dashboard_shortcodes', [$this, 'save_dashboard_shortcodes'] );
        boston_ajax( 'user_agreement_accept', [$this, 'user_agreement_accept'] );
        boston_ajax( 'process_calendly', [$this, 'process_calendly'] );

        add_action( 'init', [$this, 'init'] );
    }

    /**
     * Process the calendly request
     *
     * @return void
     */
    public function process_calendly() {

        if ( $this->current_user == 0 ) {
            wp_send_json_error( [
                'message' => 'You\'re not logged in',
            ] );
            return;
        }

        if ( ! wp_verify_nonce( boston_var( 'nonce' ), 'process_calendly' ) ) {
            wp_send_json_error( [
                'message' => 'Invalid nonce!',
            ] );
            return;
        }

        $to_profile = $this->user->profile_info( $this->current_user );

        $expert_profile = $this->user->profile_info( $this->user->current_expert( $to_profile['id'] ) );

        $admin_email =get_option('admin_email');

        $data = [
            'to'              => $to_profile['full_name'],
            'to_id'           => $to_profile['id'],
            'from'            => '',
            'from_id'         => 0,
            'message_type'    => 'information',
            'message_content' => "You apppointed a meeting with {$expert_profile['full_name']} (Tax expert)",
        ];

        $result = $this->messages->send_message( $data );

        if ( is_wp_error( $result ) ) {
            wp_send_json_error(
                [
                    'message' => __( 'Unable to send an appointment message', 'boston-tax' ),
                ]
            );
            exit;
        } else {
            // send a mail to clients email
            $body = "Hello {$to_profile['first_name']}\nRecently you appointed a meeting with your tax expert {$expert_profile['first_name']}\nPlease upload your tax documents before scheduled meeting. \n\nFor any help kindly email us at {$admin_email}";
            wp_mail( $to_profile['email'], 'You appointed a meeting with your tax expert', $body);
            wp_send_json_success(
                [
                    'message' => __( 'Successfully sent appointment message', 'boston-tax' ),
                ]
            );
            exit;
        }

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
     * Saves options from social login page
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

    /**
     * Saves shortcodes and other options from user dashboardd settings page
     *
     * @return void
     */
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