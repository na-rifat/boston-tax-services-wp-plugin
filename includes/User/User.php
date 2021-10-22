<?php

namespace Boston\User;

defined( 'ABSPATH' ) or exit;

use Boston\Manager;
use Boston\User\Client;
use Boston\User\Expert;

class User {
    public $default_user_avatar_url;
    public $current_user;
    public $current_user_id;
    public $info_list;
    public $forms_table_suffix = 'db7_forms';
    public $form_list;
    public $dashboard_slug;
    public $client_dashboard_slug;
    public $tax_expert_dashboard_slug;
    public $account_type;
    public $db;
    public $prefix;
    public $previous_year;
    public $file;
    public $messages;

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

        global $wpdb;
        $this->db     = $wpdb;
        $this->prefix = $this->db->prefix;

        $this->previous_year = mktime( 0, 0, 0, 12, 31, ( date( 'Y', time() ) - 1 ) );

        remove_filter( 'get_avatar', 'um_get_avatar', 99999 );
        add_filter( 'get_avatar_data', [$this, 'avatar'], 99999, 5 );

        $this->default_user_avatar_url = imgfile( 'avatar.png' );
        $this->current_user            = wp_get_current_user();
        $this->current_user_id         = $this->current_user->ID;

        $this->dashboard_slug = get_option( 'dashboard_page_slug' );
        // $this->info_list      = self::profile_info( $this->current_user_id );
        // $this->account_type   = strtolower( $this->info_list['account_type'] );

        // $this->collect_forms_list();

        add_action( 'template_redirect', [$this, 'check_for_redirection'] );
        // add_action( 'wp_login', [$this, 'assign_to_current_session'], 10, 2 );
        // add_action( 'wp_login', [$this, 'add_to_expert_list'] );

        // boston_ajax( 'assign_expert', [$this, 'assign_expert'] );
        boston_ajax( 'client_option_manager', [$this, 'client_option_manager'] );
        // add_action( 'wp_login', [$this, 'delete_from_previous_year'], 10, 2 );
    }

    /**
     * Client manager options loader for tax expert
     *
     * @return void
     */
    public function client_option_manager() {
        if ( ! wp_verify_nonce( boston_var( 'nonce' ), 'client_option_manager' ) ) {
            wp_send_json_error(
                [
                    'message' => __( 'Invalid nonce!', 'boston-tax' ),
                ]
            );
            exit;
        }

        $client_id = boston_var( 'client_id' );

        $atts = [
            'profile' => self::profile_info( $client_id ),
            'ai'      => std2array( $this->assigned_information( $client_id ) ),
        ];

        ob_start();
        include __DIR__ . "/views/client_option_manager.php";
        $element = ob_get_clean();

        wp_send_json_success(
            [
                'element' => $element,
            ]
        );
        exit;
    }

    /**
     * Assign expert to an user
     *
     * @return void
     */
    // public function assign_expert() {
    //     $atts = [
    //         'client_id' => boston_var( 'client_id' ),
    //         'expert_id' => boston_var( 'expert_id' ),
    //         'nonce'     => boston_var( 'nonce' ),
    //     ];

    //     if ( ! wp_verify_nonce( $atts['nonce'], 'assign_expert' ) ) {
    //         wp_send_json_error(
    //             [
    //                 'message' => 'Invalid nonce!',
    //             ]
    //         );
    //         exit;
    //     }

    //     $client_profile = self::profile_info( $atts['client_id'] );
    //     $expert_profile = self::profile_info( $atts['expert_id'] );

    //     if ( ! $expert_profile ) {
    //         wp_send_json_error(
    //             [
    //                 'message' => 'Expert not exists!',
    //                 'atts'    => $atts,
    //             ]
    //         );
    //         exit;
    //     }

    //     $data = [
    //         'client_id'       => $atts['client_id'],
    //         'expert_id'       => $atts['expert_id'],
    //         'tax_expert_name' => $expert_profile['full_name'],
    //         'assigned_at'     => time(),
    //     ];

    //     $updated = $this->db->update(
    //         "{$this->prefix}boston_tax_session",
    //         $data,
    //         ['client_id' => $atts['client_id']],
    //         [
    //             '%d',
    //             '%d',
    //             '%s',
    //             '%s',
    //         ],
    //         [
    //             '%d',
    //         ]
    //     );

    //     if ( $updated ) {
    //         wp_send_json_success(
    //             [
    //                 'message' => __( 'Tax expert assigned succesfully for ' . $client_profile['full_name'] . '.', 'boston-tax' ),
    //             ]
    //         );
    //         exit;
    //     } else {
    //         wp_send_json_error(
    //             [
    //                 'message' => __( 'Failed to assign an expert', 'boston-tax' ),
    //             ]
    //         );
    //         exit;
    //     }

    // }

    /**
     * Add to tax experts list
     *
     * @return void
     */
    // public function add_to_expert_list( $id ) {

    //     $id = $this->name2id( $id );

    //     if ( $this->account_type( $id ) != 'tax expert' ) {
    //         return;
    //     }

    //     if ( $this->have_in_expert_list( $id ) ) {
    //         return;
    //     }

    //     $profile = self::profile_info( $id );

    //     $data = [
    //         'user_id' => $profile['id'],
    //         'email'   => $profile['email'],
    //     ];

    //     $data = wp_parse_args( $data, [
    //         'user_id' => 0,
    //         'email'   => '',
    //     ] );

    //     $insert_id = $this->db->insert(
    //         "{$this->prefix}boston_tax_experts",
    //         $data,
    //         [
    //             '%d',
    //             '%s',
    //         ]
    //     );

    //     if ( ! $insert_id ) {
    //         return new \WP_Error( 'insert-to-failed', __( 'Insert to failed in tax expert list', 'boston-tax' ) );
    //     }

    //     return $insert_id;
    // }

    /**
     * Check from the database the expert listed in the DB
     *
     * @param  [type] $id
     * @return void
     */
    // public function have_in_expert_list( $id ) {
    //     return $this->db->get_var(
    //         "SELECT count(id) FROM {$this->prefix}boston_tax_experts WHERE user_id={$id}"
    //     );
    // }

    /**
     * Prepares user essential meta data
     *
     * @return void
     */
    // public function assign_to_current_session( $user_name, $user ) {
    //     $user_id = $user->ID;

    //     if ( $this->does_have_current_assigned_session( $user_id ) ) {
    //         return;
    //     }

    //     if ( $this->account_type( $user_id ) != 'client' ) {
    //         return;
    //     }

    //     $user_info = self::profile_info( $user_id );

    //     $data = [
    //         'client_id'       => $user_id,
    //         'expert_id'       => 0,
    //         'client_name'     => $user_info['full_name'],
    //         'tax_expert_name' => '',
    //         'amount_billed'   => 0,
    //         'amount_paid'     => 0,
    //         'date_billed'     => '',
    //         'date_paid'       => '',
    //         'filed'           => 'N',
    //         'appointed'       => 'N',
    //         'note'            => '',
    //         'start_date'      => time(),
    //         'return_type'     => '',
    //         'priority'        => 'Low',
    //         'assigned_at'     => '',
    //     ];

    //     return $this->update_assigned_session( $data, $user_id, false );
    // }

    /**
     * Updates the assigned session of user
     *
     * If no records found, then inserts
     *
     * @return void
     */
    // public function update_assigned_session( $data, $user_id, $does_have = true ) {

    //     if ( $does_have && $this->does_have_current_assigned_session( $user_id ) ) {
    //         // update
    //         $this->db->update(
    //             "{$this->prefix}boston_tax_session",
    //             $data,
    //             ['client_id' => $user_id],
    //             [
    //                 '%d',
    //                 '%d',
    //                 '%s',
    //                 '%s',
    //                 '%d',
    //                 '%d',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //             ]
    //         );
    //     } else {
    //         // insert
    //         $this->db->insert(
    //             "{$this->prefix}boston_tax_session",
    //             $data,
    //             [
    //                 '%d',
    //                 '%d',
    //                 '%s',
    //                 '%s',
    //                 '%d',
    //                 '%d',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //                 '%s',
    //             ]
    //         );
    //     }
    // }

    // public function does_have_current_assigned_session( $user_id ) {
    //     $have = (int) $this->db->get_var(
    //         "SELECT count(id) FROM {$this->prefix}boston_tax_session
    //         WHERE client_id={$user_id} AND cast(start_date as INT) > {$this->previous_year}"
    //     );
    //     return $have;
    // }

    /**
     * Checks for redirection via filtering
     *
     * @return void
     */
    public function check_for_redirection() {

        if ( is_admin() ) {
            return;
        }

        $dashboard_slug                  = $this->dashboard_slug;
        $restricted_pages_for_all        = explode( ', ', get_option( 'restricted_plages_for_all' ) );
        $restricted_pages_for_logged_out = explode( ', ', get_option( 'restricted_plages_for_logged_out' ) );

        if ( is_page( $restricted_pages_for_all ) ) {
            if ( is_user_logged_in() ) {
                wp_redirect( site_url( "/{$dashboard_slug}" ), 301 );
                return;
            } else {
                wp_redirect( site_url(), 301 );
                return;
            }
        }

        if ( is_page( $restricted_pages_for_logged_out ) && ! is_user_logged_in() ) {
            wp_redirect( site_url(), 301 );
            return;
        }
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

    public static function profile_info( $user_id = 0 ) {
        $info = [];

        if ( $user_id == 0 ) {
            return;
        }

        $user = get_userdata( $user_id );

        if ( ! $user ) {
            return false;
        }

        $info['avatar']       = get_avatar( $user->ID );
        $info['first_name']   = $user->first_name;
        $info['last_name']    = $user->last_name;
        $info['full_name']    = "{$info['first_name']} {$info['last_name']}";
        $info['nick_name']    = $user->nice_name;
        $info['nice_name']    = $user->nice_name;
        $info['email']        = $user->user_email;
        $info['user_slug']    = $user->user_login;
        $info['role_select']  = self::account_type( $user->ID );
        $info['account_type'] = $info['role_select'];
        $info['id']           = $user->ID;
        $info['ID']           = $user->ID;
        $info['joined']       = $user->user_registered;
        $info['role']         = $user->roles;
        $info['username']     = $user->user_login;

        return $info;
    }

    /**
     * Returns a forms list
     *
     * @return void
     */
    // public function collect_forms_list() {
    //     $table_name = Crud::prefix() . 'db7_forms';

    //     $this->form_list = Crud::db()->get_results(
    //         Crud::db()->prepare(
    //             "SELECT * FROM $table_name WHERE user_id=%d",
    //             self::id()
    //         )
    //     );

    // }

    /**
     * Gets a single form
     *
     * @ret urn void
     * @param [type] $id
     */
    // public function get_single_form( $id ) {
    //     global $wpdb;
    //     $prefix = $wpdb->prefix;

    //     $result = $wpdb->get_results(
    //         $wpdb->prepare(
    //             "SELECT * FROM {$prefix}{$this->forms_table_suffix} WHERE user_id={$this->current_user_id} AND form_post_id={$id} LIMIT 1"
    //         )
    //     );

    //     return $result;
    // }

    /**
     * Returns template of questionnaire
     *
     * @return void
     */
    // public function copy_of_questionnaire( $atts ) {
    //     global $wpdb;

    //     $atts = wp_parse_args( $atts, [
    //         'form_id' => 0,
    //         'title'   => 'Wizard form',
    //     ] );

    //     if ( $atts['form_id'] == 0 ) {
    //         return 'Not a valid form!';
    //     }

    //     $form = $this->get_single_form( $atts['form_id'] );

    //     $result    = $form[0];
    //     $form_data = unserialize( $result->form_value );

    //     ob_start();

    //     include __DIR__ . "/views/copy_of_questionnaire.php";

    //     return ob_get_clean();
    // }

    /**
     * Templates user dashboard menu in frontend
     *
     * @param  [type] $atts
     * @return void
     */
    public function user_dashboard_menu( $atts ) {
        $current_user_type = $this->account_type( $this->current_user_id );

        if ( $current_user_type == 'client' ) {
            $profile = $this->client_short_profile( $this->current_user_id );
        } elseif ( $current_user_type == 'tax expert' ) {
            $profile = $this->expert_short_profile( $this->current_user_id );
        }

        ob_start();
        switch ( $this->account_type ) {
            case 'client':
                include __DIR__ . "/views/client_menu.php";
                break;
            case 'tax expert':
                include __DIR__ . "/views/tax_expert_menu.php";
                break;
            default:
                return 'No menu found!';
                break;
        }
        return ob_get_clean();
    }

    /**
     * Outputs the dashboard tab contents
     *
     * @return void
     */
    public function boston_user_dashboard_contents() {
        switch ( $this->account_type ) {
            case 'client':
                return $this->client_dashboard_contents();
                break;
            case 'tax expert':
                return $this->tax_expert_dashboard_contents();
                break;
            default:
                // print_r( self::profile_info( get_current_user_id() ) );
                return 'No dashboard contents found!';
                break;
        }
    }

    /**
     * Returns dashboard contents for client
     *
     * @return void
     */
    public function client_dashboard_contents() {
        $tab = boston_what_tab();

        if ( $this->account_type != 'client' ) {
            return "You're not a client!";
        }

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
     * Return dashboard contents for tax experts
     *
     * @return void
     */
    public function tax_expert_dashboard_contents() {
        $tab = boston_what_tab();

        if ( $this->account_type != 'tax expert' ) {
            return "You're not a tax expert!";
        }

        switch ( $tab ) {
            case 'clients':
                $clients = Expert::list_client( self::id() );

                include __DIR__ . "/views/clients_of_expert.php";
                break;
            case 'invoice':

                break;
            case 'settings':
                return do_shortcode( '[boston-load-page slug=tax-expert-settings]' );
                break;
            default:
                $clients = Expert::list_client( self::id() );

                include __DIR__ . "/views/clients_of_expert.php";
                break;
        }
    }

    /**
     * Returns list of clients of specific tax expert
     *
     * @param  [type] $user_id
     * @return void
     */
    // public function clients_of_expert( $user_id ) {
    //     $clients = $this->db->get_results(
    //         $this->db->prepare(
    //             "SELECT * FROM {$this->prefix}boston_tax_session WHERE expert_id={$user_id}"
    //         )
    //     );

    //     $clients = std2array( $clients );

    //     for ( $i = 0; $i < sizeof( $clients ); $i++ ) {
    //         $clients[$i]['profile'] = self::profile_info( $clients[$i]['client_id'] );
    //     }

    //     return $clients;
    // }

    /**
     * Converts status code to status text
     *
     * @param  [type] $status
     * @return void
     */
    public function status2text( $status ) {
        $result = [
            'violet' => __( 'Engaged but no docs', 'boston-tax' ),
            'blue'   => __( 'On extension', 'boston-tax' ),
            'grey'   => __( 'Ready for review', 'boston-tax' ),
            'green'  => __( 'Filed', 'boston-tax' ),
            'orange' => __( 'Low priority' ),
            'yellow' => __( 'Medium priority' ),
            'red'    => __( 'High priority', 'boston-tax' ),
        ];

        return $result[$status];
    }

    /**
     * Gets the current status of the user
     *
     * @return void
     */
    public function status( $user_id ) {
        $status    = 'violet';
        $doc_count = $this->current_year_tax_documents_count( $user_id );

        // Status check for engaged but no docs
        if ( $doc_count == 0 ) {
            return $status;
        }

        $status      = 'blue';
        $have_expert = Client::have_expert( $user_id );
        // Status check for on extension
        if ( ! $have_expert ) {
            return $status;
        }

        $status   = 'grey';
        $priority = $this->get_priority( $user_id );

        // Status check for ready for review
        if (  ( ! $have_expert ) && $priority == 'low' ) {
            return $status;
        }

        $status = 'green';
        $filed  = $this->does_filed( $user_id );
        // Status check for Filed
        if ( $have_expert && $priority == 'low' && ( ! $filed ) ) {
            return $status;
        }

        $status = 'orange';

        // Status check for priority low
        if ( $have_expert && $priority = 'low' && $filed ) {
            return $status;
        }

        $status = 'yellow';

        // Status check for priority medium
        if ( $have_expert && $priority = 'medium' && $filed ) {
            return $status;
        }

        $status = 'red';

        // Status check for priority high
        if ( $have_expert && $priority = 'high' && $filed ) {
            return $status;
        }

        return $status;
    }

    public function status2class( $status ) {
        return "client-status-{$status}";
    }

    /**
     * Check if the client filed documents
     *
     * @param  [type] $user_id
     * @return void
     */
    public function does_filed( $user_id ) {
        $result = $this->db->get_row(
            $this->db->prepare(
                "SELECT filed FROM {$this->prefix}boston_tax_session WHERE client_id={$user_id}"
            )
        );

        if ( $result == 'n' ) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Counts number of current users current year tax document files
     *
     * @return void
     */
    public function current_year_tax_documents_count( $user_id ) {
        return (int) $this->db->get_var(
            "SELECT count(id) FROM {$this->db->prefix}boston_user_file_records WHERE user={$user_id} AND CAST(date as INT) > {$this->previous_year}"
        );
    }

    /**
     * Counts number of current users prior year tax document files
     *
     * @return void
     */
    public function prior_year_tax_documents_count( $user_id ) {
        return (int) $this->db->get_var(
            "SELECT count(id) FROM {$this->db->prefix}boston_user_file_records WHERE user={$user_id} AND CAST(date as INT) < {$this->previous_year}"
        );
    }

    /**
     * Returns the account type of user based on user id
     *
     * @param  [type] $user_id
     * @return void
     */
    public static function account_type( $user_id ) {
        return strtolower( UM()->roles()->get_role_name( UM()->roles()->get_editable_priority_user_role( $user_id ) ) );
    }

    /**
     * Returns a list of assigned clients
     *
     * @return void
     */
    public function assgined_clients_data_list( $args ) {
        $defaults = [
            'number'  => 40,
            'offset'  => 0,
            'orderby' => 'id',
            'order'   => 'ASC',
        ];

        $args = wp_parse_args( $args, $defaults );

        $clients = $this->db->get_results(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_tax_session
                ORDER BY {$args['orderby']} {$args['order']}
                LIMIT %d, %d", $args['offset'], $args['number']
            )
        );

        return $clients;
    }

    /**
     * Formats the assign client to add status class
     *
     * @return void
     */
    public function assigned_clients_data_list_formatted( $args ) {
        $list = $this->assgined_clients_data_list( $args );

    }

    /**
     * Counts number of assigned clients
     *
     * @return void
     */
    public function assigned_clients_count() {
        return (int) $this->db->get_var(
            "SELECT count(id) FROM {$this->prefix}boston_tax_session"
        );
    }

    /**
     * Returns a frontend list of assigned clients
     *
     * @return void
     */
    public function assigned_clients() {

        $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

        switch ( $action ) {
            case 'manage':
                $user_id      = isset( $_REQUEST['client_id'] ) ? $_REQUEST['client_id'] : 0;
                $profile_info = self::profile_info( $user_id );

                $assigned_info = json_decode( json_encode( $this->assigned_information( $user_id ) ), true );

                include __DIR__ . "/views/manage_user.php";
                break;

            default:
                $list = new Userlist( $this );
                include __DIR__ . "/views/assigned_clients.php";
                break;
        }
    }

    /**
     * Checks if a client have assigned to an expert
     *
     * @param  [type] $user_id
     * @return void
     */
    // public function have_expert( $user_id ) {
    //     if ( ! $this->does_have_current_assigned_session( $user_id ) ) {
    //         return false;
    //     }

    //     $have = $this->db->get_row(
    //         $this->db->prepare(
    //             "SELECT expert_id FROM {$this->prefix}boston_tax_session WHERE client_id={$user_id}"
    //         )
    //     )->expert_id;

    //     return $have;
    // }

    /**
     * Sets the priority of the specific user
     *
     * @param  [type] $user_id
     * @return void
     */
    public function set_priority( $user_id, $priority ) {
        $this->db->update(
            "{$this->prefix}boston_tax_session",
            [
                'priority' => $priority,
            ],
            ['client_id' => $user_id],
            [
                '%s',
            ]
        );
    }

    /**
     * Gets the priority of specific user
     *
     * @param  [type] $user_id
     * @return void
     */
    public function get_priority( $user_id ) {
        return $this->db->get_row(
            $this->db->prepare(
                "SELECT priority FROM {$this->prefix}boston_tax_session WHERE client_id={$user_id}"
            )
        );
    }

    public function delete_from_previous_year( $user_name, $user ) {
        $user_id = $user->ID;
        $this->db->query(
            "DELETE FROM {$this->prefix}boston_tax_session WHERE client_id={$user_id} AND CAST(start_date as INT) < {$this->previous_year}"
        );
    }

    /**
     * Gets assigned information of specific user
     *
     * @param  [type] $user_id
     * @return void
     */
    public function assigned_information( $user_id ) {
        return $this->db->get_row(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_tax_session WHERE client_id={$user_id} AND cast(start_date as INT) > {$this->previous_year}"
            )
        );
    }

    /**
     * Convert user login name to id
     *
     * @param  [type] $name
     * @return void
     */
    public function name2id( $name ) {
        return get_user_by( 'login', $name )->ID;
    }

    /**
     * Converts user id to login name
     *
     * @param  [type] $id
     * @return void
     */
    public function id2name( $id ) {
        return get_user_by( 'ID', $id )->login;
    }

    /**
     * Returns first admins id
     *
     * @return void
     */
    public function admin_id() {
        $admins = get_super_admins();
        return $this->name2id( $admins[0] );
    }

    /**
     * Returns id of current tax expert of specific user
     *
     * @param  [type] $user_id
     * @return void
     */
    public function current_expert( $user_id ) {
        if ( $user_id == 0 ) {
            return;
        }

        $expert = $this->db->get_row(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_tax_session WHERE client_id={$user_id}"
            )
        );

        if ( empty( $expert ) ) {
            return false;
        }

        return $expert->expert_id;
    }

    /**
     * Pretty prints assgined time of a client
     *
     * @param  int    $time
     * @return void
     */
    public function pretty_print_assign_time( $time ) {
        $result = date( 'D, M d, Y', $time );
        return __( $result, 'boston-tax' );
    }

    /**
     * Returns a list of client files
     *
     * @param  [type] $client_id
     * @return void
     */
    public function files_of_client( $client_id ) {
        if ( ! $this->have_access_of_client( $client_id, $this->current_user_id ) ) {
            return [];
        }

        $files = $this->db->get_results(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_user_file_records WHERE user={$client_id}"
            )
        );

        $files = std2array( $files );
        $irs   = std2array( $this->file->client_irs_correspondence_files_list( $client_id ) );

        ob_start();
        include __DIR__ . "/views/manage_client_files.php";
        return ob_get_clean();
    }

    /**
     * Checks if a specific expert have access of a client
     *
     * @param  [type] $client_id
     * @param  [type] $expert_id
     * @return void
     */
    public function have_access_of_client( $client_id, $expert_id ) {
        if ( $client_id == 0 || $expert_id == 0 ) {
            return false;
        }

        $result = $this->db->get_row(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_tax_session WHERE client_id={$client_id}"
            )
        );

        if ( $result->client_id == $client_id && $result->expert_id == $expert_id ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns a short profile of client by ID
     * Specially for top of the menu
     *
     * @param  [type] $client_id
     * @return void
     */
    public function client_short_profile( $client_id ) {
        $profile                       = self::profile_info( $client_id );
        $profile['current_status']     = $this->status2text( $this->status( $profile['id'] ) );
        $profile['current_tax_expert'] = self::profile_info( $this->current_expert( $client_id ) );

        ob_start();
        include __DIR__ . "/views/short_profile//client_profile.php";
        return ob_get_clean();
    }

    /**
     * Returns a short profile tax expert by ID
     *  Specially for top of the menu
     *
     * @return void
     */
    public function expert_short_profile( $expert_id ) {
        $profile                      = self::profile_info( $expert_id );
        $profile['number_of_clients'] = $this->how_much_client_have( $profile['id'] );

        ob_start();
        include __DIR__ . "/views/short_profile/expert_profile.php";
        return ob_get_clean();
    }

    /**
     * Pretty prints tax expert name
     *
     * @param  [type] $profile
     * @return void
     */
    public function expert_name( $profile ) {
        if ( $profile ) {
            return $profile['first_name'];
        } else {
            return 'Not assigned';
        }
    }

    /**
     * Returns current number of clients of a tax expert
     *
     * @param  [type] $user_id
     * @return void
     */
    public function how_much_client_have( $expert_id ) {
        return (int) $this->db->get_var(
            "SELECT count(id) FROM {$this->prefix}boston_tax_session WHERE expert_id={$expert_id}"
        );
    }

    public function files_of_folder( $files, $folder ) {
        ob_start();
        include __DIR__ . "/views/files_of_folder.php";
        return ob_get_clean();
    }

    public static function is_logged() {
        return is_user_logged_in();
    }

    public static function get() {
        return get_userdata( get_current_user_id() );
    }

    public static function id() {
        if ( ! self::is_logged() ) {
            return;
        }

        return self::get()->ID;
    }

    public static function email() {
        return self::get()->user_email;
    }

    public static function first_name() {
        return self::get()->first_name;
    }

    public static function last_name() {
        return self::get()->last_name;
    }

    public static function name() {
        return self::first_name() . ' ' . self::last_name();
    }

    public static function password() {
        return self::get()->password;
    }

    public static function current_user_id() {
        return self::get()->ID;
    }

    public static function current_user_first_name() {
        return self::get()->first_name;
    }

    public static function current_user_last_name() {
        return self::get()->last_name;
    }

    public static function current_user_email() {
        return self::get()->user_email;
    }

    public static function current_user_login() {
        return self::get()->user_login;
    }

    public static function current_user_roles() {
        return self::get()->roles;
    }

    public static function meta( $key, $single = true ) {
        return get_user_meta( self::id(), $key, $single );
    }

    public static function set_meta( $key, $val ) {
        return self::get()->set_meta( $key, $val );
    }

    public static function type( $user_id = false ) {
        $user_id = $user_id == false ? self::id() : $user_id;

        $type = strtolower( UM()->roles()->get_role_name( UM()->roles()->get_editable_priority_user_role( $user_id ) ) );

        return $type == 'tax expert' ? 'expert' : $type;
    }

    /**
     * Logout current user
     *
     * @return void
     */
    public static function logout() {
        wp_logout();

        Manager::go_home();
    }

    public static function is( $role_name ) {
        if ( in_array( $role_name, self::get()->roles ) ) {
            return true;
        }

        return false;
    }

}
