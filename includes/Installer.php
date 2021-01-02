<?php

namespace Boston;

/**
 * Installer handler class
 */
class Installer {
    public $db;
    public $charset_collate;
    public $prefix;

    public function __construct() {

    }

    /**
     * Run the installer
     *
     * @return void
     */
    public function run() {
        $this->add_version();
        $this->create_tables();
    }

    public function init() {
        global $wpdb;
        $this->db              = $wpdb;
        $this->prefix          = $wpdb->prefix;
        $this->charset_collate = $wpdb->get_charset_collate();

        $this->run();
    }

    /**
     * Stores the current version
     *
     * @return void
     */
    public function add_version() {
        // $installed = get_option( 'wd_academy_installed' );
        // if ( $installed ) {
        //     update_option( 'wd_academy_installed', time() );
        // }
        // update_option( 'wd_academy_version', WD_ACADEMY_VERSION );
    }
    /**
     * Create necessery database tables
     *
     * @return void
     */
    public function create_tables() {
        $this->create_files_record_table();
        $this->create_messages_table();
    }

    /**
     * Creates a table in DB for store messages
     *
     * @return void
     */
    public function create_messages_table() {
        $schema = "CREATE TABLE IF NOT EXISTS `{$this->prefix}boston_messages` (
            `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
            `to_user_id` bigint NOT NULL,
            `from_user_id` bigint NOT NULL,
            `message_content` longtext NOT NULL,
            `read_at` longtext NOT NULL,
            `date` longtext NOT NULL,
            `status` varchar(500) NOT NULL,
            PRIMARY KEY (`id`)
           ) $this->charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

    /**
     * Creates a table in DB for file records
     *
     * @return void
     */
    public function create_files_record_table() {
        $schema = "CREATE TABLE IF NOT EXISTS `{$this->prefix}boston_user_file_records` (
            `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
            `real_name` varchar(400) NOT NULL,
            `type` varchar(200) NOT NULL,
            `size` int(255) NOT NULL,
            `ext` varchar(100) NOT NULL,
            `name` varchar(400) NOT NULL,
            `storage_name` varchar(400) NOT NULL,
            `destination` varchar(400) NOT NULL,
            `url` varchar(400) NOT NULL,
            `user` int(255) NOT NULL,
            `tab` varchar(100) NOT NULL,
            `folder` varchar(100) NOT NULL,
            `status` varchar(100) NOT NULL,
            `date` varchar(1000) NOT NULL,
            PRIMARY KEY (`id`)
           ) $this->charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

}