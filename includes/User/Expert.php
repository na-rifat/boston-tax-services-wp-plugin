<?php

namespace Boston\User;

defined( 'ABSPATH' ) or exit;

use Boston\Crud;
use Boston\User\User;

class Expert {
    function __construct() {
        add_action('wp_login', 'assign_to_experts');
    }

    public static function client_count( $expert_id ) {
        $table_name = Crud::prefix() . 'boston_tax_session';
        return (int) Crud::db()->get_var(
            Crud::db()->prepare(
                "SELECT count(id) FROM $table_name WHERE expert_id=%d",
                $expert_id
            )
        );
    }

    public static function list_client( $expert_id ) {

        $table_name = Crud::prefix() . 'boston_tax_session';
        $clients    = Crud::db()->get_results(
            Crud::db()->prepare(
                "SELECT * FROM $table_name WHERE expert_id=%d",
                $expert_id
            )
        );

        $clients = std2array( $clients );

        for ( $i = 0; $i < sizeof( $clients ); $i++ ) {
            $clients[$i]['profile'] = User::profile_info( $clients[$i]['client_id'] );
        }

        return $clients;
    }

    public function assign_to_experts(){
         
    }

}