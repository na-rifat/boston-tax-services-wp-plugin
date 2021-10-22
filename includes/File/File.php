<?php

namespace Boston\File;

/**
 * Handles the file system
 */
class File {
    public $current_user;
    public $upload_path;
    public $upload_url;
    public $files;
    public $db;
    public $prefix;
    public $user;

    /**
     * Builds the class
     */
    public function __construct() {
        add_action( 'init', [$this, 'init'] );
    }

    /**
     * Initializes the file handler class
     *
     * @return void
     */
    public function init() {
        global $wpdb;
        $this->current_user = get_current_user_id();
        $this->prepare_path();
        $this->db     = $wpdb;
        $this->prefix = $this->db->prefix;

        add_shortcode( 'boston-file-view', [$this, 'manager_view'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_assets'] );
        boston_ajax( 'upload_tax_file', [$this, 'upload_tax_file'] );
        boston_ajax( 'boston_get_file_list', [$this, 'boston_get_file_list'] );
        boston_ajax( 'upload_file_from_expert', [$this, 'upload_file_from_expert'] );
        boston_ajax( 'approve_file', [$this, 'approve_file'] );
        boston_ajax( 'upload_prepared', [$this, 'upload_prepared'] );
        boston_ajax( 'get_client_irs_correspondence', [$this, 'get_client_irs_correspondence'] );
        boston_ajax( 'upload_irs_document', [$this, 'upload_irs_document'] );
    }

    /**
     * Uploads IRS correspondence document from client
     *
     * @return void
     */
    function upload_irs_document() {
        $atts = [
            'nonce'  => boston_var( 'nonce' ),
            'folder' => 'irs-correspondence',
            'tab'    => 'irs-correspondence',
        ];

        if ( ! wp_verify_nonce( $atts['nonce'], 'upload_irs_document' ) ) {
            echo $this->client_irs_correspondence_files( $this->user->current_user_id );
            exit;
        }

        $p_info = $this->process_file( $_FILES['file'] );

        $p_info['folder'] = $atts['folder'];
        $p_info['tab']    = $atts['tab'];

        $p_info['uploaded_by'] = $this->user->current_user_id;

        move_uploaded_file( $p_info['tmp_name'], $p_info['destination'] );

        unset(
            $p_info['tmp_name']
        );

        $this->insert_file_record( $p_info );

        echo $this->client_irs_correspondence_files( $this->user->current_user_id );
        exit;
    }

    /**
     * Uploads preparedd file
     *
     * @return void
     */
    public function upload_prepared() {

        if ( ! wp_verify_nonce( boston_var( 'nonce' ), 'upload_prepared' ) ) {
            echo 1;
            return;
        }

        if ( empty( boston_var( 'file_id' ) ) ) {
            return;
        }

        $info = std2array( $this->file_info( boston_var( 'file_id' ) ) );

        if ( ! $this->user->have_access_of_client( $info['user'], $this->user->current_user_id ) ) {
            return;
        }
        $p_info                 = $this->process_file_for_update( $_FILES['file'] );
        $p_info['destination']  = "{$this->upload_path}{$info['user']}_{$p_info['name']}_{$p_info['date']}.{$p_info['ext']}";
        $p_info['storage_name'] = "{$info['user']}_{$p_info['name']}_{$p_info['date']}.{$p_info['ext']}";
        $p_info['url']          = "$this->upload_url{$p_info['storage_name']}";

        unlink( $info['destination'] );
        move_uploaded_file( $_FILES['file']['tmp_name'], $p_info['destination'] );

        // Update file info
        $result = $this->db->update(
            "{$this->prefix}boston_user_file_records",
            [
                'ext'          => $p_info['ext'],
                'destination'  => $p_info['destination'],
                'url'          => $p_info['url'],
                'date'         => $p_info['date'],
                'status'       => $p_info['status'],
                'type'         => $p_info['type'],
                'storage_name' => $p_info['storage_name'],
                'uploaded_by'  => $this->user->current_user_id,
            ],
            ['id' => $info['id']],
            [
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
            ],
            [
                '%d',
            ]
        );

        $files_list = $this->user->files_of_client( $info['user'] );
        echo $files_list;

        exit;
    }

    /**
     * Returns a file infromation by id
     *
     * @param  [type] $file_id
     * @return void
     */
    public function file_info( $file_id ) {
        return $this->db->get_row(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_user_file_records WHERE id={$file_id}"
            )
        );
    }

    /**
     * Updates status to approve of a specific file
     *
     * @return void
     */
    public function approve_file() {
        if ( ! wp_verify_nonce( boston_var( 'nonce' ), 'approve_file' ) ) {
            wp_send_json_error(
                [
                    'message' => __( 'Invalid nonce!' ),
                ]
            );
            exit;
        }

        $file_id = boston_var( 'file_id' );
        $status  = 'Approved';

        if ( $this->current_file_status( $file_id ) == 'Approved' ) {
            $status = 'Amend';
        }

        $success = $this->db->update(
            "{$this->prefix}boston_user_file_records",
            [
                'status' => $status,
            ],
            [
                'id'   => $file_id,
                'user' => $this->current_user,
            ],
            [
                '%s',
            ]
        );

        if ( $success ) {
            wp_send_json_success();
            exit;
        } else {
            wp_send_json_error(
                [
                    'Unable to update approve status!',
                ]
            );
            exit;
        }
    }

    /**
     * Returns current status of a file
     *
     * Approved|Amend
     *
     * @param  [type] $file_id
     * @return void
     */
    public function current_file_status( $file_id ) {
        return $this->db->get_row(
            $this->db->prepare(
                "SELECT status FROM {$this->prefix}boston_user_file_records WHERE id={$file_id}"
            )
        )->status;
    }

    /**
     * Uploads file from tax expert
     *
     * @return void
     */
    public function upload_file_from_expert() {
        if ( filesize( $_FILES['tax_file']['tmp_name'] ) != 0 ) {
            $file = $_FILES['tax_file'];
        } else {
            echo "Something went wrong with the file!";
            exit;
        }

        if ( empty( $_POST['tab'] ) || empty( $_POST['folder'] ) ) {
            echo "Tab number or folder is missing!";
            exit;
        }

        if ( ! wp_verify_nonce( boston_var( 'nonce' ), 'upload_file_from_expert' ) ) {
            echo "Invalid nonce!";
            exit;
        }

        $info                = $this->process_file( $file );
        $info['user']        = boston_var( 'client_id' );
        $info['uploaded_by'] = $this->user->current_user_id;

        move_uploaded_file( $info['tmp_name'], $info['destination'] );

        $this->insert_file_record( $info );

        $files_list = $this->user->files_of_client( $info['user'] );
        echo $files_list;

        exit;
    }

    /**
     * Deletes a file by file id
     *
     * @param  [type] $file_id
     * @return void
     */
    public function delete_file( $file_id ) {

    }

    /**
     * Returns a files path by it's id
     *
     * @param  [type] $file_id
     * @return void
     */
    public function file_path( $file_id ) {

    }

    /**
     * Replaces a file by another
     *
     * @param  [type] $file_id
     * @param  [type] $file
     * @return void
     */
    public function replace_file( $file_id, $file ) {

    }

    /**
     * Return the files list
     *
     * @return void
     */
    public function boston_get_file_list() {
        global $wpdb;

        $folder = boston_var( 'folder' );
        $tab    = boston_var( 'tab' );

        $prior_years = mktime( 0, 0, 0, 12, 31, ( date( 'Y', time() ) - 1 ) );

        if ( $tab == 'current-year-taxes' ) {
            $selector = '>';
        } else if ( $tab == 'prior-year-taxes' ) {
            $selector = '<';
        }

        $this->files = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}boston_user_file_records WHERE folder='{$folder}'
                AND user={$this->current_user}
                AND CAST(date as INT) {$selector} $prior_years
               "
            )
        );

        return include __DIR__ . "/views/file_list.php";
        exit;
    }

    /**
     * Fires the upload function from ajax
     *
     * @return void
     */
    public function upload_tax_file() {
        if ( filesize( $_FILES['tax_file']['tmp_name'] ) != 0 ) {
            $file = $_FILES['tax_file'];
        } else {
            echo "Something went wrong with the file!";
            exit;
        }

        if ( empty( $_POST['tab'] ) || empty( $_POST['folder'] ) ) {
            echo "Tab number or folder is missing!";
            exit;
        }

        if ( ! wp_verify_nonce( boston_var( 'filenonce' ), 'upload_tax_file' ) ) {
            echo "Invalid nonce!";
            exit;
        }

        $info                = $this->process_file( $file );
        $info['uploaded_by'] = $info['user'];

        move_uploaded_file( $info['tmp_name'], $info['destination'] );

        $result = $this->insert_file_record( $info );

        return $result;
    }

    /**
     * Uploads a file
     *
     * @param  [type] $atts
     * @return void
     */
    public function upload( $atts ) {

        $info = $this->process_file( $atts['file'] );

        move_uploaded_file( $info['tmp_name'], $info['destination'] );

        $result = $this->insert_file_record( $info );

    }

    /**
     * Enqueue assets
     *
     * @return void
     */
    public function enqueue_assets() {
        wp_enqueue_script( 'boston-file-script' );
    }

    /**
     * Views the folder template
     *
     * @param  [type] $atts
     * @return void
     */
    public function manager_view( $atts ) {
        switch ( $atts['view'] ) {
            case 'current-year-taxes':
                ob_start();
                include __DIR__ . "/views/file_manager.php";
                return ob_get_clean();
                break;
            case 'prior-year-taxes':
                return boston_template( __DIR__, 'file_manager' );
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Moves file to specific folder
     *
     * @return void
     */
    public function move_file_to_folder() {

    }

    /**
     * Processes the file and return the informations
     *
     * @return void
     */
    public function process_file( $file ) {
        $result    = [];
        $timestamp = time();

        $result['tmp_name']     = $file['tmp_name'];
        $result['real_name']    = $file['name'];
        $result['type']         = $file['type'];
        $result['size']         = $file['size'];
        $result['ext']          = strtolower( explode( '.', $file['name'] )[sizeof( explode( '.', $file['name'] ) ) - 1] );
        $result['name']         = explode( '.', $file['name'] )[0];
        $result['storage_name'] = "{$this->current_user}_{$result['name']}_{$timestamp}.{$result['ext']}";
        $result['destination']  = "{$this->upload_path}{$this->current_user}_{$result['name']}_{$timestamp}.{$result['ext']}";
        $result['url']          = "$this->upload_url{$result['storage_name']}";
        $result['user']         = $this->current_user;
        $result['tab']          = boston_var( 'tab' );
        $result['folder']       = boston_var( 'folder' );
        $result['date']         = floor( intval( boston_var( 'date' ) ) / 1000 );
        $result['status']       = 'Not approved';

        return $result;
    }

    /**
     * Processes file information before update
     *
     * @param  [type] $file
     * @return void
     */
    public function process_file_for_update( $file ) {
        $result    = [];
        $timestamp = time();

        $result['tmp_name']     = $file['tmp_name'];
        $result['real_name']    = $file['name'];
        $result['type']         = $file['type'];
        $result['size']         = $file['size'];
        $result['ext']          = strtolower( explode( '.', $file['name'] )[sizeof( explode( '.', $file['name'] ) ) - 1] );
        $result['name']         = explode( '.', $file['name'] )[0];
        $result['storage_name'] = "{$this->current_user}_{$result['name']}_{$timestamp}.{$result['ext']}";
        $result['destination']  = "{$this->upload_path}{$this->current_user}_{$result['name']}_{$timestamp}.{$result['ext']}";
        $result['url']          = "$this->upload_url{$result['storage_name']}";
        $result['date']         = $timestamp;
        $result['status']       = 'Not approved';

        return $result;
    }

    /**
     * Processes file information for expert
     *
     * @param  [type] $file
     * @return void
     */
    public function process_file_for_expert( $file ) {
        $result    = [];
        $timestamp = time();

        $result['tmp_name']     = $file['tmp_name'];
        $result['real_name']    = $file['name'];
        $result['type']         = $file['type'];
        $result['size']         = $file['size'];
        $result['ext']          = strtolower( explode( '.', $file['name'] )[sizeof( explode( '.', $file['name'] ) ) - 1] );
        $result['name']         = explode( '.', $file['name'] )[0];
        $result['storage_name'] = "{$this->current_user}_{$result['name']}_{$timestamp}.{$result['ext']}";
        $result['destination']  = "{$this->upload_path}{$this->current_user}_{$result['name']}_{$timestamp}.{$result['ext']}";
        $result['url']          = "$this->upload_url{$result['storage_name']}";
        $result['date']         = $timestamp;
        $result['status']       = 'Not approved';

        return $result;
    }

    /**
     * Pretty prints a uploaded by name
     *
     * @param  [type] $id
     * @return void
     */
    public function pretty_print_uploaded_by( $id ) {
        $info = $this->user->profile_info( $id );
        if ( in_array( 'administrator', $info['role'] ) ) {
            return 'Admin';
        }

        if ( $info['account_type'] == 'client' && $info['id'] == $this->user->current_user_id ) {
            return 'Me';
        }

        if ( $info['account_type'] == 'client' && $info['id'] != $this->user->current_user_id ) {
            return 'Client';
        }

        if ( $info['account_type'] == 'tax expert' ) {
            return 'Tax expert';
        }

        return 'Unknown or from system';
    }

    /**
     * Prepares the upload path
     *
     * @return void
     */
    public function prepare_path() {
        $path    = wp_upload_dir()['basedir'] . "/boston-user-tax-files/";
        $urlpath = wp_upload_dir()['baseurl'] . "/boston-user-tax-files/";
        if ( ! file_exists( $path ) ) {
            wp_mkdir_p( $path );
        }
        $this->upload_path = $path;
        $this->upload_url  = $urlpath;
    }

    /**
     * Insert the file record to the database
     *
     * @return void
     */
    public function insert_file_record( $atts ) {
        global $wpdb;

        $data = [
            'real_name'    => $atts['real_name'],
            'type'         => $atts['type'],
            'size'         => $atts['size'],
            'ext'          => $atts['ext'],
            'name'         => $atts['name'],
            'storage_name' => $atts['storage_name'],
            'destination'  => $atts['destination'],
            'url'          => $atts['url'],
            'user'         => $atts['user'],
            'tab'          => $atts['tab'],
            'folder'       => $atts['folder'],
            'date'         => $atts['date'],
            'status'       => $atts['status'],
            'uploaded_by'  => $atts['uploaded_by'],
        ];

        // return $data;
        $inserted = $wpdb->insert(
            "{$wpdb->prefix}boston_user_file_records",
            $data,
            [
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%d',
            ]
        );

        if ( ! $inserted ) {
            return new \WP_Error( 'failed-to-insert', __( 'Failed to insert data', 'boston-tax' ) );
        }
        return $wpdb->insert_id;
    }

    /**
     * Return an icon url for specific extension
     *
     * @param  [type] $ext
     * @return void
     */
    public function file_ext2ico( $ext ) {
        switch ( $ext ) {
            case 'png':
            case 'jpg':
            case 'jpeg':
            case 'pdf':
            case 'docx':
            case 'xml':
            case 'txt':
            case 'zip':
                return imgfile( $ext . ".png" );
                break;
            default:
                return imgfile( "file.png" );
                break;
        }
    }

    /**
     * Returns client IRS correspondence page
     *
     * @param  [type] $user_id
     * @return void
     */
    public function client_irs_correspondence( $user_id ) {
        ob_start();
        include __DIR__ . "/views/client_irs_correspondence.php";
        return ob_get_clean();
    }

    /**
     * Returns IRS correspondence files with pretty elements
     *
     * @param  [type] $user_id
     * @return void
     */
    public function client_irs_correspondence_files( $user_id ) {
        $correspondence = std2array( $this->client_irs_correspondence_files_list( $user_id ) );

        ob_start();
        include __DIR__ . "/views/client_files_of_irs_correspondence.php";
        return ob_get_clean();
    }

    /**
     * Returns std object list of correspondence files
     *
     * @param  [type] $user_id
     * @return void
     */
    public function client_irs_correspondence_files_list( $user_id ) {
        return $this->db->get_results(
            $this->db->prepare(
                "SELECT * FROM {$this->prefix}boston_user_file_records WHERE user={$user_id} AND folder='irs-correspondence'"
            )
        );
    }

    /**
     * Handles IRS correspondence file list request from AJAX
     *
     * @return void
     */
    public function get_client_irs_correspondence() {
        $files = $this->client_irs_correspondence_files( $this->user->current_user_id );

        wp_send_json_success(
            [
                'el' => $files,
            ]
        );
        exit;
    }
}
