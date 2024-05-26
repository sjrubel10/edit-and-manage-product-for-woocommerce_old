<?php

namespace wooBEMP\Admin;

class Enque
{
    function __construct()
    {
        $this->init_hooks();
    }

    public function init_hooks()
    {
        add_action( 'admin_enqueue_scripts', [$this,'include_all_files'] );
    }

    public function include_css_files()
    {
        wp_enqueue_style('wooBENP_edit_product', WOOBEMP_ASSETS_LINK . 'css/editproduct.css', array(), WOOBEMP_VERSION );
        wp_enqueue_style('wooBENP_edit_product_filter', WOOBEMP_ASSETS_LINK . 'css/filter.css', array(), WOOBEMP_VERSION );
    }

    public function include_js_files()
    {
        wp_enqueue_script('wooBENP_edit_product',WOOBEMP_ASSETS_LINK . 'js/commonjsfunction.js' );
        wp_enqueue_script('wooBENP_edit_products',WOOBEMP_ASSETS_LINK . 'js/productsedit.js' );
    }

    public function include_all_files()
    {
        $this->include_css_files();
        $this->include_js_files();
    }

}