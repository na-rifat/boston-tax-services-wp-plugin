<?php

namespace Boston;

defined( 'ABSPATH' ) or exit;

class Crud {
    function __construct() {

    }

    public static function db() {
        global $wpdb;
        return $wpdb;
    }

    public static function prefix() {
        return self::db()->prefix;
    }

    public static function charset_collate() {
        return self::db()->charset_collate;
    }

    public static function prepare() {
        return self::db()->prepare( func_get_args() );
    }

    public static function query( $args ) {
        return self::db()->query( $args );
    }

    public static function get_var( $args ) {
        return (int) self::db()->get_var( $args );
    }

    public static function get_results( $args ) {
        return self::db()->get_results( $args );
    }

    public static function insert( $table_name, $data ) {
        $table_name = self::prefix() . $table_name;
        self::db()->insert( $table_name, $data, self::data_format( $data ) );
    }

    public static function data_format( $data ) {
        $result = [];

        foreach ( $data as $key => $val ) {
            switch ( gettype( $val ) ) {
                case 'int':
                    $result[] = '%d';
                default:
                    $result[] = '%s';
                    break;
            }
        }

        return $result;
    }

    public static function table_name( $name ) {
        return self::prefix() . $name;
    }
}