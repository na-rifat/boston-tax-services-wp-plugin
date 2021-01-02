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
    public function __construct( $wizard, $user ) {
        $this->wizard = $wizard;
        $this->user   = $user;
        add_shortcode( 'user-dashboard-menu', [$this, 'user_dashboard_menu'] );
        add_shortcode( 'boston-user-dashboard', [$this, 'boston_user_dashboard'] );
        add_shortcode( 'boston-echo', [$this, 'boston_echo'] );
        add_shortcode( 'boston-load-page', [$this, 'boston_load_page'] );
        add_shortcode( 'social-login-buttons', [$this, 'social_login_buttons'] );
        add_shortcode( 'boston-user-wizard', [$this->wizard, 'output_wizard'] );
        add_shortcode( 'agreement-accept', [$this, 'agreement_accept'] );
        add_shortcode( 'boston-docusign-wizard', [$this, 'docusign_wizard'] );
        add_shortcode( 'dashboard-user-profile', [$this->user, 'profile'] );
        add_shortcode( 'copy-of-questionnaire', [$this->user, 'copy_of_questionnaire'] );
        add_shortcode( 'boston-messages', [$this, 'messages'] );
    }

    /**
     * Returns the messages template
     *
     * @return void
     */
    public function messages() {
        ob_start();
        include __DIR__ . "/views/messages.php";
        return ob_get_clean();
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
        exit;
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