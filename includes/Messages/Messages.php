<?php

namespace Boston\Messages;

defined( 'ABSPATH' ) or exit;

use Boston\Crud;
use Boston\User\User;

class Messages {
    public $message_types = [
        'general'     => [

        ],
        'information' => [

        ],
        'alert'       => [

        ],
        'warning'     => [

        ],
    ];

    function __construct() {
        add_action( 'init', [$this, 'init'] );
    }

    /**
     * Initializes the class
     *
     * @return void
     */
    public function init() {
        $this->current_user_id = get_current_user_id();

        boston_ajax( 'get_messages', [$this, 'get_messages'] );
        boston_ajax( 'get_composer', [$this, 'get_composer'] );
        boston_ajax( 'send_message_from_ajax', [$this, 'send_message_from_ajax'] );
        boston_ajax( 'get_unread_count', [$this, 'get_unread_count'] );
        boston_ajax( 'mark_as_read', [$this, 'mark_as_read'] );
    }

    public function mark_as_read() {
        if ( ! wp_verify_nonce( boston_var( 'nonce' ), 'mark_as_read' ) ) {
            wp_send_json_error(
                [
                    'message' => __( 'Invalid nonce!', 'boston-tax' ),
                    'count'   => $this->get_unread_message_count( $this->current_user_id ),
                ]
            );
            exit;
        }

        $unread_id = unserialize( str_replace( "\\", "", boston_var( 'unread_id' ) ) );
        $unread_id = implode( ', ', $unread_id );

        if ( strlen( $unread_id ) == 0 ) {

            wp_send_json_success(
                [
                    'count' => $this->get_unread_message_count( $this->current_user_id ),
                ]
            );
            exit;
        }

        $success = Crud::query( "UPDATE {$this->prefix}boston_messages SET status='read' WHERE id IN({$unread_id})" );

        if ( $success ) {
            wp_send_json_success(
                [
                    'id'    => $unread_id,
                    'count' => $this->get_unread_message_count( $this->current_user_id ),
                ]
            );
            exit;
        } else {
            wp_send_json_error(
                [
                    'count' => $this->get_unread_message_count( $this->current_user_id ),
                ]
            );
            exit;
        }
    }

    /**
     * Sends unread message count from ajax requestF
     *
     * @return void
     */
    public function get_unread_count() {

        wp_send_json_success(
            [
                'count' => $this->get_unread_message_count( $this->current_user_id ),
            ]
        );
        exit;
    }

    /**
     * Send messages from ajax request
     *
     * @return void
     */
    public function send_message_from_ajax() {
        $data = [
            'to'              => boston_var( 'to' ),
            'to_id'           => boston_var( 'to_id' ),
            'from'            => boston_var( 'from' ),
            'from_id'         => boston_var( 'from_id' ),
            'message_type'    => boston_var( 'message_type' ),
            'message_content' => boston_var( 'body' ),
        ];

        $result = $this->send_message( $data );

        if ( ! is_wp_error( $result ) ) {
            wp_send_json_success(
                [
                    'message'   => 'Message sent succesfully to ' . $data['to'],
                    'insert_id' => $result,
                ]
            );
        } else {
            wp_send_json_error(
                [
                    'message' => 'Something wrong happen with server!',
                    'log'     => $result,
                ]
            );
        }
        exit;
    }

    /**
     * Registers message types
     *
     * @return void
     */
    public function register_message_types() {
        $types = [
            'general'     => [

            ],
            'information' => [

            ],
            'alert'       => [

            ],
            'warning'     => [

            ],
        ];

        return $types;
    }

    /**
     * Returns message type for select option
     *
     * @return void
     */
    public function message_types2options() {
        $result = '';
        foreach ( $this->message_types as $name => $params ) {
            $caption = ucwords( $name );
            $result .= "<option value='{$name}'>{$caption}</option>";
        }
        return $result;
    }

    /**
     * Returns the composer template
     *
     * @return void
     */
    public function get_composer() {
        $atts = [
            'to'      => boston_var( 'to' ),
            'to_id'   => boston_var( 'to_id' ),
            'from'    => boston_var( 'from' ),
            'from_id' => boston_var( 'from_id' ),
        ];

        if ( $atts['form_id'] == 'admin' ) {
            $atts['from_id'] == $this->user->admin_id();
            $atts['from'] = $this->user->profile_info( $atts['from_id'] )['full_name'];
        }

        if ( empty( $atts['to_id'] ) ) {
            echo 'No valid information found!';
            exit;
        }

        echo include __DIR__ . "/views/composer.php";
        exit;
    }

    /**
     * Returns messages
     *
     * @return void
     */
    public function get_messages() {
        $list = $this->collect_messages_from_db( $this->current_user_id );

        ob_start();
        include __DIR__ . "/views/messages.php";
        echo ob_get_clean();

        exit;
    }

    /**
     * Returns the messages template
     *
     * @return void
     */
    public function messages() {
        ob_start();
        include __DIR__ . "/views/messages_frame.php";
        return ob_get_clean();
    }

    /**
     * Collects messages from db
     *
     * @return void
     */
    public function collect_messages_from_db( $user_id ) {
        $list = Crud::get_results(
            Crud::prepare(
                "SELECT * FROM {$this->prefix}boston_messages WHERE to_user_id={$user_id} ORDER BY id DESC"
            )
        );

        return $list;
    }

    /**
     * Collects unread messages
     *
     * @return void
     */
    public function get_unread_messages() {
        $table_name = Crud::prefix() . 'boston_messages';
        return Crud::get_results(
            Crud::prepare( "SELECT * $table_name WHERE to_user_id=%d AND status='unread'", User::id() )
        );
    }

    /**
     * Gets unread messages count
     *
     * @return void
     */
    public function get_unread_message_count( $user_id ) {
        return Crud::get_var( "SELECT count(id) FROM {$this->prefix}boston_messages WHERE status='unread' AND to_user_id={$user_id}" );
    }

    /**
     * Insert a message
     *
     * @return void
     */
    public function send_message( $data ) {
        $current = time();

        $data = $this->process_data_before_send( $data );

        $defaults = [
            'to_user_id'      => 0,
            'from_user_id'    => 0,
            'message_content' => 'Empty message!',
            'read_at'         => '',
            'date'            => $current,
            'status'          => 'unread',
            'message_type'    => 'general',
        ];

        $data = wp_parse_args( $data, $defaults );

        /**
         * Check for validation
         */

        if ( $data['to_user_id'] == 0 ) {
            return new \WP_Error( 'insert-failed', __( 'Recipient not valid!', 'boston-tax' ) );
        }

        $insert_id = $this->db->insert(
            "{$this->prefix}boston_messages",
            $data,
            [
                '%d',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        );

        if ( ! $insert_id ) {
            return new \WP_Error( 'failed-to-insert', __( 'Failed to insert new message', 'boston-tax' ) );
        }

        return $insert_id;
    }

    /**
     * Processes the data before send
     *
     * @param  [type] $data
     * @return void
     */
    public function process_data_before_send( $data ) {
        $data['to_user_id']   = $data['to_id'];
        $data['from_user_id'] = $data['from_id'];

        unset(
            $data['to'],
            $data['to_id'],
            $data['from'],
            $data['from_id']
        );

        return $data;
    }

    /**
     * Loads the composer
     *
     * @return void
     */
    public function composer( $atts ) {
        return include __DIR__ . "/views/composer.php";
    }

    /**
     * Returns url of message types icon
     *
     * @param  [type] $message_type
     * @return void
     */
    public function message_icon( $message_type ) {
        return imgfile( "/message-icons/message-{$message_type}.png" );
    }

    /**
     * Returns a pretty name for from cell
     *
     * @param  [type] $id
     * @return void
     */
    public function pretty_from( $id ) {
        if ( $id == 0 ) {
            return 'BTS system';
        }

        $user = get_userdata( $id );

        if ( in_array( 'administrator', $user->roles ) ) {
            return 'Admin';
        } else if ( $this->user->account_type( $id )['account_type'] == 'tax expert' ) {
            return "{$user->first_name} {$user->last_name} {Your tax expert}";
        } else {
            return "{$user->first_name} {$user->last_name}";
        }
    }
}