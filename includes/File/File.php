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
     * @param [type] $file_id
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
        // $atts = [
        //     'nonce'=>boston_var('nonce'),
        //     'folder'=>boston_var('folder'),
        //     'tab'=>boston_var('tab'),
        //     'date'=>''
        // ];

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

        $info = $this->process_file( $file );
        print_r($info);
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

        $info = $this->process_file( $file );

        move_uploaded_file( $info['tmp_name'], $info['destination'] );

        $result = $this->insert_file_record( $info );

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
}
