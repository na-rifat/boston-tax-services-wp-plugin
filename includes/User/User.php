<?php

namespace Boston\User;

/**
 * Handles user functions
 */
class User {
    public $default_user_avatar_url;
    public $current_user;
    public $current_user_id;
    public $info_list;
    public $forms_table_suffix = 'db7_forms';
    public $form_list;

    /**
     * Builds the class
     */
    public function __construct() {
        $this->init();
    }

    /**
     * Initializes the class
     *
     * @return void
     */
    public function init() {
        remove_filter( 'get_avatar', 'um_get_avatar', 99999 );
        add_filter( 'get_avatar_data', [$this, 'avatar'], 99999, 5 );

        $this->default_user_avatar_url = imgfile( 'avatar.png' );
        $this->current_user            = wp_get_current_user();
        $this->current_user_id         = $this->current_user->ID;

        $this->info_list = $this->profile_info();
        $this->collect_forms_list();
    }

    /**
     * Outputs an item from the user info
     *
     * @param  mixed  $key
     * @return void
     */
    public function info( $key ) {
        echo $this->info_list[$key];
        return;
    }

    /**
     * Gets the modified avatar
     *
     * @param  int    $user_id
     * @return void
     */
    public function avatar( $args, $id_or_email ) {
        $avatar_url = get_user_meta( $id_or_email, 'avatar', true );

        $args['url'] = ! empty( $avatar_url ) ? $avatar_url : $this->default_user_avatar_url;

        return $args;
    }

    public function profile() {
        ob_start();
        include __DIR__ . "/views/user_profile.php";
        return ob_get_clean();
    }

    public function profile_info() {

        if ( ! is_user_logged_in() ) {
            return;
        }

        $info = [];

        $info['avatar']       = get_avatar( $this->current_user_id );
        $info['first_name']   = $this->current_user->first_name;
        $info['last_name']    = $this->current_user->last_name;
        $info['full_name']    = "{$info['first_name']} {$info['last_name']}";
        $info['nick_name']    = $this->current_user->nice_name;
        $info['nice_name']    = $this->current_user->nice_name;
        $info['email']        = $this->current_user->user_email;
        $info['user_slug']    = $this->current_user->user_login;
        $info['role_select']  = um_user( 'role_select' );
        $info['account_type'] = $info['role_select'];
        $info['id']           = $this->current_user_id;
        $info['joined']       = $this->current_user->user_registered;

        return $info;
    }

    /**
     * return an value
     *
     * @param  [type] $key
     * @return void
     */
    public function val( $key ) {
        if ( ! empty( $this->info_list[$key] ) ) {
            echo " value='{$this->info_list[$key]}'";
        }
    }

    /**
     * Undocumented function
     *
     * @param  [type] $key
     * @return void
     */
    public function text( $key ) {
        if ( ! empty( $this->info_list[$key] ) ) {
            echo $this->info_list[$key];
        } else {
            echo 'Not found!';
        }
    }

    /**
     * Returns a forms list
     *
     * @return void
     */
    public function collect_forms_list() {
        global $wpdb;
        $prefix = $wpdb->prefix;

        $this->form_list = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$prefix}{$this->forms_table_suffix} WHERE user_id={$this->current_user_id}"
            )
        );

    }

    /**
     * Gets a single form
     *
     * @param  [type] $id
     * @return void
     */
    public function get_single_form( $id ) {
        global $wpdb;
        $prefix = $wpdb->prefix;

        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$prefix}{$this->forms_table_suffix} WHERE user_id={$this->current_user_id} AND form_post_id={$id} LIMIT 1"
            )
        );

        return $result;
    }

    /**
     * Returns template of questionnaire
     *
     * @return void
     */
    public function copy_of_questionnaire( $atts ) {
        global $wpdb;

        $atts = wp_parse_args( $atts, [
            'form_id' => 0,
            'title'   => 'Wizard form',
        ] );

        if ( $atts['form_id'] == 0 ) {
            return 'Not a valid form!';
        }

        $form = $this->get_single_form( $atts['form_id'] );

        $result    = $form[0];
        $form_data = unserialize( $result->form_value );

        ob_start();

        include __DIR__ . "/views/copy_of_questionnaire.php";

        return ob_get_clean();
    }
}