<?php

namespace Boston\User;

defined( 'ABSPATH' ) or exit;

use Boston\Crud;
use Boston\Manager;
use Boston\User\User;

class Client {
    function __construct() {
        add_action( 'template_redirect', [$this, 'wizard_complete'] );
    }

    public function wizard_complete() {
        if ( ! User::is_logged() || User::is( 'administrator' ) ) {
            return;
        }

        if ( ! User::meta( 'wizard_complete' ) && Manager::current_page() !== 'wizard' ) {
            wp_redirect( site_url( 'wizard' ), 301 );
        }
    }

    public static function have_expert() {
        
    }

    public static function have_session() {
        $table_name = Crud::table_name( 'boston_tax_session' );

        return Crud::get_var(
            Crud::prepare(
                "SELECT count(id) FROM $table_name
                WHERE client_id=%d AND cast(start_date as INT) > %d",
                User::id(),
                Manager::prev_year()
            )
        );
    }

    public static function wizard_forms() {
        $table_name = Crud::prefix() . 'db7_forms';

        return Crud::db()->get_results(
            Crud::db()->prepare(
                "SELECT * FROM $table_name WHERE user_id=%d",
                User::id()
            )
        );
    }

    public static function print_wizard_forms( $atts ) {

        $atts = wp_parse_args( $atts, [
            'form_id' => 0,
            'title'   => 'Wizard form',
        ] );

        if ( $atts['form_id'] == 0 ) {
            return 'Not a valid form!';
        }

        $table_name = Crud::table_name( 'db7_forms' );
        $form       = unserialize(
            Crud::get_results(
                Crud::prepare(
                    "SELECT * FROM $table_name WHERE user_id=%d AND form_post_id=%d LIMIT 1",
                    User::id(),
                    $atts['form_id']
                )
            )[0]->form_value
        );

        ob_start();

        include __DIR__ . "/views/copy_of_questionnaire.php";

        return ob_get_clean();
    }

    public static function assign( $expert_id, $client_id ) {

        $atts = [
            'client_id' => boston_var( 'client_id' ),
            'expert_id' => boston_var( 'expert_id' ),
            'nonce'     => boston_var( 'nonce' ),
        ];

        extract( $atts );

        if ( ! wp_verify_nonce( $nonce, 'assign_expert' ) ) {
            wp_send_json_error(
                [
                    'msg' => 'Invalid nonce!',
                ]
            );
            exit;
        }

        $data = [
            'client_id'   => $client_id,
            'expert_id'   => $expert_id,
            'assigned_at' => time(),
        ];

        $table_name = Crud::prefix() . 'boston_tax_session';

        $updated = Crud::db()->update(
            $table_name,
            $data,
            ['client_id' => $client_id],
            [
                '%d',
                '%d',
                '%s',
            ],
            [
                '%d',
            ]
        );

        if ( ! $updated ) {
            wp_send_json_error(

                [
                    'msg' => 'Error assigning client!',
                ]
            );
            exit;
        }

        wp_send_json_success(
            [
                'msg' => 'Client assigned successfully',
            ]
        );
        exit;
    }

    public function regsiter_to_session() {
        $user_id = User::id();

        if ( self::have_session( $user_id ) ) {
            return;
        }

        if ( User::type( $user_id ) != 'client' ) {
            return;
        }

        $user_info = User::profile_info( $user_id );

        $data = [
            'client_id'     => $user_id,
            'expert_id'     => 0,
            'client_name'   => $user_info['full_name'],
            'amount_billed' => 0,
            'amount_paid'   => 0,
            'date_billed'   => '',
            'date_paid'     => '',
            'filed'         => 'N',
            'appointed'     => 'N',
            'note'          => '',
            'start_date'    => time(),
            'return_type'   => '',
            'priority'      => 'Low',
            'assigned_at'   => '',
        ];

        return Crud::insert( 'boston_tax_session', $data );
    }

    public static function visual_status() {
        $stock = [
            'violet' => __( 'Engaged but no docs', 'boston-tax' ),
            'blue'   => __( 'On extension', 'boston-tax' ),
            'grey'   => __( 'Ready for review', 'boston-tax' ),
            'green'  => __( 'Filed', 'boston-tax' ),
            'orange' => __( 'Low priority' ),
            'yellow' => __( 'Medium priority' ),
            'red'    => __( 'High priority', 'boston-tax' ),
        ];

    }

}