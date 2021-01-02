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
        $this->current_user = get_current_user_id();
        $this->prepare_path();

        add_shortcode( 'boston-file-view', [$this, 'manager_view'] );
        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_assets'] );
        boston_ajax( 'upload_tax_file', [$this, 'upload_tax_file'] );
        boston_ajax( 'boston_get_file_list', [$this, 'boston_get_file_list'] );
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

        $last_april = mktime( 0, 0, 0, 4, 1, ( date( 'Y', time() ) - 1 ) );

        if ( $tab == 'current-year-taxes' ) {
            $selector = '>';
        } else if ( $tab == 'prior-year-taxes' ) {
            $selector = '<';
        }

        $this->files = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}boston_user_file_records WHERE folder='{$folder}'
                AND user={$this->current_user}
                AND CAST(date as INT) {$selector} $last_april
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

        var_dump( $result );
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
                return boston_template( __DIR__, 'file_manager' );
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
