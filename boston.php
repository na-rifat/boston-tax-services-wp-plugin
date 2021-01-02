<?php
/**
 * Plugin Name:       Boston Tax Services
 * Plugin URI:        https://rafalotech.com/wordpress-plugins/boston-tax
 * Description:       Manages Boston Tax Services
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Rafalo Tech
 * Author URI:        https://rafalotech.com
 * Text Domain:       boston-tax
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */
namespace Boston;

use Boston\Shortcodes\Shortcode;
use Boston\Sociallogin\Sociallogin;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once __DIR__ . "/vendor/autoload.php";

/**
 * Creates the Boston Tax services plugin
 */
class BostonTax {
    const version = '1.0';

    function __construct() {
        $this->define_constants();
        add_action( 'plugins_loaded', [$this, 'initialize'] );
        register_activation_hook( __FILE__, [$this, 'activate'] );
    }

    /**
     * Prepares the plugin for first time use after activation
     *
     * @return void
     */
    public function activate() {
        $installer = new Installer();
        $installer->run();

    }

    /**
     * Creates main instance
     *
     * @return mixed
     */
    public static function init() {
        $instance = false;
        if ( ! $instance ) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * Initializes the classes
     *
     * @return void
     */
    public function initialize() {
        new Assets();
        $wizard = new Wizard\Wizard();
        $user   = new User\User();

        new Shortcode( $wizard, $user );
        new File\File();

        if ( ! is_admin() ) {

            new Frontend\Frontend();
            if ( ! is_user_logged_in() ) {
                 new Sociallogin();
            }
        } else {
            new Admin\Admin();
        }

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            new Ajax();
        }
    }

    /**
     *
     */

    /**
     * Defines important constants
     *
     * @return void
     */
    public function define_constants() {
        define( 'BOSTON_VERSION', self::version );
        define( 'BOSTON_PATH', __DIR__ );
        define( 'BOSTON_FILE', __FILE__ );
        define( 'BOSTON_PLUGIN_PATH', plugins_url( '', BOSTON_FILE ) );
        define( 'BOSTON_ASSETS', BOSTON_PLUGIN_PATH . '/assets' );
        define( 'BOSTON_JS', BOSTON_ASSETS . '/js' );
        define( 'BOSTON_CSS', BOSTON_ASSETS . '/css' );
        define( 'BOSTON_IMAGES', BOSTON_ASSETS . '/img' );
        define( 'BOSTON_FUNCTIONS', __DIR__ . '/includes/functions.php' );
    }
}

/**
 * Loads the plugins class
 *
 * @return void
 */
function load() {
    return BostonTax::init();
}
load();