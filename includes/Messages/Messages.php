<?php

namespace Boston\Messages;

/**
 * Handles the messaging function
 */
class Messages {
    public $current_user_id;
    public $db;
    public $prefix;

    function __construct() {
        add_action( 'init', [$this, 'init'] );
    }

    /**
     * Initializes the class
     *
     * @return void
     */
    public function init() {
        global $wpdb;
        $this->db              = $wpdb;
        $this->prefix          = $this->db->prefix;
        $this->current_user_id = get_current_user_id();
        boston_ajax( 'get_messages', [$this, 'get_messages'] );
    }

    /**
     * Returns messages
     *
     * @return void
     */
    public function get_messages() {
        $list = $this->collect_messages_from_db();

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
    public function collect_messages_from_db() {
        $list = $this->db->get_results(
            $this->db->prepare(
                "SELECT * {$this->prefix}boston_messages WHERE to_user_id={$this->current_user_id}"
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
        $list = $this->db->get_results(
            $this->db->prepare(
                "SELECT * {$this->prefix}boston_messages WHERE to_user_id={$this->current_user_id} AND status='unread'"
            )
        );

        return $list;
    }

    /**
     * Gets unread messages count
     *
     * @return void
     */
    public function get_unread_message_count() {
        return (int) $this->db->get_var(
            "SELECT count(id) FROM {$this->prefix}bosotn_messages WHERE status='unread'"
        );
    }

    /**
     * Insert a message
     *
     * @return void
     */
    public function insert_message() {
        $info = [
            'to_user_id'      => boston_var( 'to_user_id' ),
            'from_user_id'    => boston_var( 'from_user_id' ),
            'message_content' => boston_var( 'message_content' ),
            'read_at'         => '',
            'date'            => boston_var( 'insert_time' ),
            'status'          => 'unread',
        ];

        $insert_id = $this->db->insert(
            "{$this->prefix}boston_messages",
            $info,
            [
                '%d',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
            ]
        );

        if ( ! $insert_id ) {
            return new \WP_Error( 'failed-to-insert', __( 'Failed to insert new message', 'boston' ) );
        }

        return $insert_id;
    }
}