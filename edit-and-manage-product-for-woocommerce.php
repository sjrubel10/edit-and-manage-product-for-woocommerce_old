<?php
/*
  Plugin Name: Edit and Manage Product for WooCommerce
  Plugin URI: https://wooBEMP-editor.com/
  Description:BEMP: Simplify WooCommerce product management. Bulk edit and manage with ease. Save time, streamline your store.
  Requires at least: WP 4.9
  Tested up to: WP 6.3
  Author: sjrubel10
  Author URI: https://profiles.wordpress.org/sjrubel10/
  Version: 1.0.0
  Requires PHP: 7.4
  Tags: woocommerce,woocommerce bulk edit,bulk edit,bulk,products editor, manage product
  Text Domain: edit-and-manage-product-for-woocommerce
  Domain Path: /languages
  WC requires at least: 3.6
  WC tested up to: 8.1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once __DIR__.'/vendor/autoload.php';
//***

final class WOOBEMP {

    const plugin_version = '1.0.0';

    private function __construct() {

        $this->define_constants();
        register_activation_hook( __FILE__, [$this, 'activate']);
//        register_deactivation_hook( __FILE__, [$this, 'deactivate'] );
        add_action( 'plugins_loaded', [ $this, 'wooBEMP_init_plugin' ] );

    }

    /**
     *Initializes a singleton instance
     * @return WOOBEMP
     **/
    public static function init() {
        static $instance = false;
        if( ! $instance ){
            $instance = new self();
        }
        return $instance;
    }

    public function wooBEMP_init_plugin() {

        if( is_admin() ) {
            new wooBEMP\Admin();
        }else {
            //echo
        }

    }

    public function define_constants() {
        define( 'WOOBEMP_PATH', plugin_dir_path(__FILE__ ) );
        define( 'WOOBEMP_LINK', plugin_dir_url(__FILE__ ) );
        define( 'WOOBEMP_ASSETS_LINK', WOOBEMP_LINK . 'assets/' );
        define( 'WOOBENP_API_LINK', WOOBEMP_LINK . 'api/' );
        define( 'WOOBENMP_DATA_PATH', WOOBEMP_PATH . 'data/' );
        define( 'WOOBEMP_PLUGIN_NAME', plugin_basename(__FILE__ ) );
        define( 'WOOBEMP_VERSION', self::plugin_version);
        define( 'WOOBE_MIN_WOOCOMMERCE_VERSION', '3.6' );
        define( 'WOOBEMP_admin_ulr', get_admin_url() );
    }

   /* private function create_history_table( $wpdb ){
        $table_name = $wpdb->prefix . "wooBEMPhistory";
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE `$table_name`
                ( 
                  `ID` int(11) NOT NULL AUTO_INCREMENT,
                  `product_title` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
                  `action` varchar(128) COLLATE utf8mb4_unicode_520_ci NOT NULL,
                  `product_id` int(11) NOT NULL,
                  `new_value` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
                  `old_value` varchar(128) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
                  `recorded` tinyint(1) NOT NULL DEFAULT '1',
                  `restore` tinyint(1) NOT NULL DEFAULT '0',
                  `update_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  PRIMARY KEY (`ID`)
                ) 
                $charset_collate";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }*/

//    public function deactivate() {
//        error_log( print_r( "Deactivation", true ) );
//    }
    public function activate() {
        update_option( 'woobemp_version', WOOBEMP_VERSION );
        global $wpdb;
        /*$table_name = $wpdb->prefix . "wooBEMPhistory";
        $query = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
        if ( ! $wpdb->get_var( $query ) == $table_name ) {
            $this->create_history_table( $wpdb );
        }*/

        $installed = get_option( 'woobemp_installed' );

        if( ! $installed ) {
            update_option( 'woobemp_installed', time() );
        }

    }

}

function init_woobenp() {
    return WOOBEMP::init();
}

//run plugin
init_woobenp();


// The function that displays the plugin page

?>
