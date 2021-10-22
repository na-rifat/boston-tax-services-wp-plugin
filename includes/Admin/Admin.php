<?php
namespace Boston\Admin;

use Boston\Menu\Adminmenu as Menu;

/**
 * Handles the admin functions
 */
class Admin {
    public $user;
    public $db;
    public $prefix;

    /**
     * Builds the class
     *
     * @param [type] $user
     */
    public function __construct( $user ) {
        global $wpdb;
        $this->user = $user;
        $menu       = new Menu( $this->user );

        $this->db     = $wpdb;
        $this->prefix = $this->db->prefix;

        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
        boston_ajax( 'get_tax_expert_assigner', [$this, 'get_tax_expert_assigner'] );
        boston_ajax( 'get_tax_expert_info', [$this, 'get_tax_expert_info'] );     
    }

    /**
     * Returns tax expert profile from ajax request
     *
     * @return
     */
    public function get_tax_expert_info() {
        $atts = [
            'id' => boston_var( 'expert_id' ),
        ];

        $profile = $this->expert_profile( $atts['id'] );

        ob_start();
        include __DIR__ . "/views/expert_profile.php";
        $element = ob_get_clean();

        wp_send_json_success(
            [
                'found'   => true,
                'message' => 'Profile found!',
                'profile' => $element,
            ]
        );
        exit;
    }

    /**
     * Loads admin assets
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_script( 'boston-admin-script' );
        wp_enqueue_style( 'boston-admin-style' );
    }

    /**
     * Returns template of tax expert assigner
     *
     * @return void
     */
    public function get_tax_expert_assigner() {

        $atts = [
            'client_id'          => boston_var( 'client_id' ),
            'client_profile'     => $this->user->profile_info( boston_var( 'client_id' ) ),
            'expert_id'      => boston_var( 'expert_id' ),
            'tax_expert_profile' => $this->user->profile_info( boston_var( 'expert_id' ) ),
            'tax_experts'        => $this->formatted_experts_list( $this->tax_experts() ),
        ];

        $atts['client_profile']['current_expert'] = $this->expert_profile( $this->user->current_expert( $atts['client_profile']['id'] ) );

        if ( empty( $atts['client_profile']['current_expert'] ) ) {
            $atts['client_profile']['current_expert'] = 'Not assigned';
        }

        ob_start();
        include __DIR__ . "/views/assign_expert.php";
        $assigner = ob_get_clean();

        wp_send_json_success(
            [
                'element' => $assigner,
            ]
        );
    }

    /**
     * Return list of tax experts
     *
     * @return void
     */
    public function tax_experts() {

        $experts = $this->db->get_results(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_tax_experts"
            )
        );
        $result = [];

        foreach ( $experts as $expert ) {
            $result[] = $this->expert_profile( $expert->user_id );
        }

        return $result;
    }

    /**
     * Returns profile informations of tax expert
     *
     * @param  [type] $user_id
     * @return void
     */
    public function expert_profile( $user_id ) {
        $profile = $this->user->profile_info( $user_id );

        if ( ! $profile ) {
            return false;
        }

        $profile['current_client_count'] = $this->current_client_count( $profile['id'] );

        return $profile;
    }

    /**
     * Returns current number of clients of a tax expert
     *
     * @param  [type] $user_id
     * @return void
     */
    public function current_client_count( $user_id ) {
        return (int) $this->db->get_var(
            "SELECT count(id) FROM {$this->prefix}boston_tax_session WHERE expert_id={$user_id}"
        );
    }

    /**
     *
     *
     * @param  [type] $arr
     * @return void
     */
    public function formatted_experts_list( $arr ) {
        $result = '';
        foreach ( $arr as $expert ) {
            $profile =
            $result .= "<option value='{$expert['id']}'>{$expert['id']}: {$expert['username']}</option>";
        }
        return $result;
    }
}