<?php
namespace Boston\Shortcodes;
/**
 * Implements shrotcodes
 * Application for both frontend and admin side
 */
class Shortcode {
    /**
     * Builds this class
     */
    public $is5 = false;
    public $wizard;
    public $user;
    public $messages;
    public $file;

    public function __construct( $wizard, $user, $messages, $file ) {
        $this->wizard   = $wizard;
        $this->user     = $user;
        $this->messages = $messages;
        $this->file     = $file;

        add_shortcode( 'user-dashboard-menu', [$this->user, 'user_dashboard_menu'] );
        add_shortcode( 'boston-user-dashboard-contents', [$this->user, 'boston_user_dashboard_contents'] );

        add_shortcode( 'boston-messages', [$this->messages, 'messages'] );
        add_shortcode( 'copy-of-questionnaire', [$this->user, 'copy_of_questionnaire'] );

        add_shortcode( 'boston-user-wizard', [$this->wizard, 'output_wizard'] );
        add_shortcode( 'boston-docusign-wizard', [$this, 'docusign_wizard'] );
        add_shortcode( 'agreement-accept', [$this, 'agreement_accept'] );

        add_shortcode( 'boston-echo', [$this, 'boston_echo'] );
        add_shortcode( 'boston-load-page', [$this, 'boston_load_page'] );
        add_shortcode( 'social-login-buttons', [$this, 'social_login_buttons'] );

        add_shortcode( 'debug-test-function', [$this, 'debug_test_function'] );
        add_shortcode( 'boston-full-width-dashboard', [$this, 'boston_full_width_dashboard'] );

        add_shortcode( 'boston-client-irs-correspondence', [$this, 'client_irs_correspondence'] );

        add_shortcode( 'boston_test', [$this, 'mail_test'] );
    }

    public function mail_test() {
        return var_dump( mail( 'nuraalamrifat@gmail.com', 'Test', 'test' ) );
        // return var_dump( wp_mail( 'nuraalamrifat@gmail.com', 'Test', 'm' ) );
    }

    /**
     * Returns irs correspondence page for client
     *
     * @param  [type] $atts
     * @return void
     */
    public function client_irs_correspondence( $atts ) {
        return $this->file->client_irs_correspondence( $this->user->current_user_id );
    }

    /**
     * Returns boston full width modern desgined dashboard page
     *
     * @param  [type] $atts
     * @return void
     */
    public function boston_full_width_dashboard( $atts ) {
        ob_start();
        include __DIR__ . "/views/full_width.php";
        return ob_get_clean();
    }

    /**
     * Uses for debug purposes
     *
     * @return void
     */
    public function debug_test_function() {
        var_dump( $this->user->profile_info( 1 ) );

        return;
        global $super_admins;
        var_dump( $super_admins );return;
        return $this->user->account_type;
        // return $this->user->assign_to_current_session('', get_userdata(7));
        var_dump( $this->user->assgined_clients_data_list( [] ) );
        return;
        return $this->user->assigned_clients_count();
        return $this->user->current_year_tax_documents_count();
    }

    /**
     * Returns docusign template
     *
     * Functions for docusign agreement
     *
     * @return void
     */
    public function docusign_wizard() {

        return boston_template( __DIR__, 'docusign_agreement' );

    }

    /**
     * Echo any word or strings
     *
     * Use for test purposes
     *
     * @param  [type] $atts
     * @param  string $contents
     * @return void
     */
    public function boston_echo( $atts, $contents = '' ) {
        if ( ! empty( $atts ) && empty( $contents ) ) {
            foreach ( $atts as $key => $value ) {
                return $value;
            }
            return "\n<br>";
        } elseif ( ! empty( $contents ) ) {
            return $contents;
        } else {
            return "Hello world!\n<br>";
        }
    }

    /**
     * Templates user dashboard menu in frontend
     *
     * @param  [type] $atts
     * @return void
     */
    public function user_dashboard_menu( $atts ) {
        return boston_template( __DIR__, 'user_dashboard_menu', true );
    }

    /**
     * Outputs the dashboard tab contents
     *
     * @return void
     */
    public function boston_user_dashboard() {
        $tab = boston_what_tab();
        switch ( $tab ) {
            case 'current-year-taxes':
                return do_boston_shortcodes( 'current_year_taxes' );
                break;
            case 'prior-year-taxes':
                return do_boston_shortcodes( 'prior_year_taxes' );
                break;
            case 'messages':
                return do_boston_shortcodes( 'boston_user_messages' );
                break;
            case 'irs-correspondence':
                return do_boston_shortcodes( 'irs_correspondence' );
                break;
            case 'settings':
                return do_boston_shortcodes( 'boston_user_settings' );
                break;
            default:
                return do_boston_shortcodes( 'boston_welcome_page' );
                break;
        }
    }

    /**
     * Includes a page inside another page
     *
     * @param  [type] $atts
     * @return void
     */
    public function boston_load_page( $atts ) {
        wp_enqueue_style( 'boston-custom-style' );

        $atts = wp_parse_args( $atts, [
            'id'   => 0,
            'slug' => '',
        ] );

        if ( $atts['id'] !== 0 ) {
            $post    = get_post( $atts['id'] );
            $content = apply_filters( 'the_content', $post->post_content );
            return $content;
        } else if ( ! empty( $atts['slug'] ) ) {
            $id      = get_page_by_path( $atts['slug'] )->ID;
            $post    = get_post( $id );
            $content = apply_filters( 'the_content', $post->post_content );
            return $content;
        } else {
            echo 'No contents found for this page!';
        }
    }

    /**
     * Appears social login buttons in frontend
     *
     * @return void
     */
    public function social_login_buttons() {
        return do_action( 'boston_social_login_button' );
    }

    /**
     * Return agreement accept template
     *
     * @return void
     */
    public function agreement_accept() {
        return boston_template( __DIR__, 'agreement_accept' );
    }
}


