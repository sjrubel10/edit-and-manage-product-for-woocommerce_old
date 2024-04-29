<?php
namespace wooBEMP\Admin;
class Menu
{
    function  __construct() {
        if( is_admin() ) {
            add_action('admin_menu', [$this, 'my_plugin_menu']);
        }
    }

    public function woo_bulk_product_edit() {
        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ){
            include WOOBEMP_PATH."views/products.php";
        }else{
            echo esc_html__( "Please Active Woocommerce Plugin First", "edit-and-manage-product-for-woocommerce" );
        }
    }

    public function my_plugin_menu() {
        // Add a new top-level menu
        add_menu_page(
            'Woo Product Editor',
            'Woo Product Editor',
            'manage_options',
            'woo-edit-product',
            [$this, 'woo_bulk_product_edit']
        );
    }




}