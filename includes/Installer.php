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
        $this->currently_assgined_records_table();
        $this->create_tax_expert_table();
        // $this->create_appointed_client_records_table();
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
            `message_type` varchar(500) NOT NULL,
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
            `date` varchar(500) NOT NULL,
            `uploaded_by` bigint NOT NULL,
            PRIMARY KEY (`id`)
           ) $this->charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

    /**
     * Creates table for appointed clients records
     *
     * @return void
     */
    public function create_appointed_client_records_table() {
        $schema = "CREATE TABLE IF NOT EXISTS `{$this->prefix}boston_appointed_client_records` (
            `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
            `client_id` bigint NOT NULL,
            `expert_id` bigint NOT NULL,
            `start_date` bigint NOT NULL,
            `session_status` varchar(100) NOT NULL,
            `date` varchar(1000) NOT NULL,
            PRIMARY KEY (`id`)
           ) $this->charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

    public function currently_assgined_records_table() {
        $schema = "CREATE TABLE IF NOT EXISTS `{$this->prefix}boston_tax_session` (
            `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
            `client_id` bigint NOT NULL,
            `expert_id` bigint NOT NULL,
            `client_name` longtext NOT NULL,
            `tax_expert_name` longtext NOT NULL,
            `amount_billed` bigint NOT NULL,
            `amount_paid` bigint NOT NULL,
            `date_billed` longtext NOT NULL,
            `date_paid` longtext NOT NULL,
            `filed` longtext NOT NULL,
            `appointed` longtext NOT NULL,
            `note` longtext NOT NULL,
            `start_date` longtext NOT NULL,
            `return_type` longtext NOT NULL,
            `priority` longtext NOT NULL,
            `assigned_at` longtext NOT NULL,
            PRIMARY KEY (`id`)
           ) $this->charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

    /**
     * Creates table for tax expert records
     *
     * @return void
     */
    public function create_tax_expert_table() {
        $schema = "CREATE TABLE IF NOT EXISTS `{$this->prefix}boston_tax_experts` (
            `id` int(255) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` bigint NOT NULL,
            `email` longtext NOT NULL,
            PRIMARY KEY (`id`)
           ) $this->charset_collate";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . '/wp-admin/includes/upgrade.php';
        }

        dbDelta( $schema );
    }

}