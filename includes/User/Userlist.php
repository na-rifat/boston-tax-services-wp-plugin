<?php

namespace Boston\User;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . '/wp-admin/includes/class-wp-list-table.php';
}

class Userlist extends \WP_List_Table {
    public $user;
    function __construct( $user ) {
        $this->user = $user;

        parent::__construct( array(
            'singular' => 'user',
            'plural'   => 'users',
            'ajax'     => false,
        ) );
    }

    /**
     * Get columns
     *
     * @return void
     */
    function get_columns() {
        return array(
            'cb'              => '<input type="checkbox" />',
            'client_name'     => __( 'Client name', 'boston-tax' ),
            'return_type'     => __( 'Return type', 'boston-tax' ),
            'tax_expert_name' => __( 'Assigned preparer', 'boston-tax' ),
            'amount_billed'   => __( 'Amount billed', 'boston-tax' ),
            'date_billed'     => __( 'Date billed', 'boston-tax' ),
            'amount_paid'     => __( 'Amount paid', 'boston-tax' ),
            'date_paid'       => __( 'Date paid', 'boston-tax' ),
            'note'            => __( 'Note', 'boston-tax' ),
            'filed'           => __( 'Filed', 'boston-tax' ),
            'action'          => __( 'Action', 'boston-tax' ),
        );
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'client_name'     => array( 'client_name', true ),
            'tax_expert_name' => array( 'assigned_preparer', true ),
            'filed'           => array( 'filed', true ),
            'return_type'     => array( 'filed', true ),
        );
        return $sortable_columns;
    }

    /**
     * Formats and sends default comments
     *
     * @param  [type] $item
     * @param  [type] $column_name
     * @return void
     */
    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            // case 'client_name':
            //     $url = admin_url( "admin.php?page=current-year-clients&action=manage&client_id={$item->client_id}" );
            //     return "<a href='{$url}'>{$item->$column_name}</a>";
            case 'action':
                $url = admin_url( "admin.php?page=current-year-clients&action=manage&client_id={$item->client_id}" );
                return "<a href='{$url}' class='button button-large'>Manage</a>";
                break;
            default:
                return isset( $item->$column_name ) ? $item->$column_name : '';
                break;
        }
    }

    /**
     * Formats name column
     *
     * @param  [type] $item
     * @return void
     */
    // protected function column_name( $item ) {
    //     $actions = array();

    //     $actions['edit'] = sprintf( '<a href="%s" title="%s">%s</a>', admin_url( 'admin.php?page=wedevs-academy&action=edit&id=' . $item->id ),
    //         __( 'Edit', 'wedevs-academy' ), __( 'Edit', 'wedevs-academy' ) );

    //     $actions['delete'] = sprintf( '<a href="#" class="submitdelete" data-id="%s">%s</a>', $item->id,
    //         __( 'Delete', 'wedevs-academy' )
    //     );
    //     return sprintf(
    //         '<a href="%1$s"><strong>%2$s</strong></a> %3$s',
    //         admin_url( 'admin.php?page=wedevs-academy&action=view&id=' . $item->id ),
    //         $item->name,
    //         $this->row_actions( $actions )
    //     );
    // }

    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="book_id[]" value="%d" />', $item->id
        );
    }

    public function prepare_items() {
        $column   = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $column, $hidden, $sortable );

        $per_page     = 40;
        $current_page = $this->get_pagenum();
        $offset       = ( $current_page - 1 ) * $per_page;

        $args = array(
            'number' => $per_page,
            'offset' => $offset,
        );

        if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
            $args['orderby'] = $_REQUEST['orderby'];
            $args['order']   = $_REQUEST['order'];
        }

        $this->items = $this->user->assgined_clients_data_list( $args );

        $this->set_pagination_args( array(
            'total_items' => $this->user->assigned_clients_count(),
            'per_page'    => $per_page,
        ) );
    }

    /**
     * Generates content for a single row of the table.
     *
     * @since 3.1.0
     *
     * @param object|array $item The current item
     */
    public function single_row( $item ) {
        $class = $this->user->status2class( $this->user->status( $item->client_id ) );
        echo "<tr class='{$class}'>";
        $this->single_row_columns( $item );
        echo '</tr>';
    }
}
