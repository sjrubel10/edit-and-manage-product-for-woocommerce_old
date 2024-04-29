<?php

namespace wooBEMP\Classes;

class Bemp_config
{

    public function wooBEMP_config(){

        $config = maybe_unserialize(get_option('wooBEMP_column_settings'));
        if( !$config ){
            $config = array(
                "productID" => 'block',
                "productTitle" => 'block',
                "productDesc" => 'block',
                "productShhortDesc" => 'block',
                "productType" => 'block',
                "productStatus" => 'block',
                "productPrice" => 'block',
                "productRegularprice" => 'block',
                "productSaleprice" => 'block',
                "productSku" => 'block',
                "productStockStatus" => 'block',
                "productStockManage" => 'block',
                "productStockQuantity" => 'block',
                "productCatalogVisibility" => 'none',
                "action" => 'block'
            );
        }

        return $config;
    }

    public function wooBEMP_product_info_table_colimn_idClass(){
        $productinfoIds = array(
            "productID" => esc_html__('wooBEMP_productID', 'edit-and-manage-product-for-woocommerce' ),
            "productTitle" => esc_html__('textareafield', 'edit-and-manage-product-for-woocommerce' ),
            "productDesc" => esc_html__('textareafielddescription', 'edit-and-manage-product-for-woocommerce' ),
            "productShhortDesc" => esc_html__('textareafieldshortdesc', 'edit-and-manage-product-for-woocommerce' ),
            "productType" => esc_html__('selectoOptionType', 'edit-and-manage-product-for-woocommerce' ),
            "productStatus" => esc_html__('selectoOptionStatus', 'edit-and-manage-product-for-woocommerce' ),
            "productPrice" => esc_html__('numberchange', 'edit-and-manage-product-for-woocommerce' ),
            "productRegularprice" => esc_html__('numberchange', 'edit-and-manage-product-for-woocommerce' ),
            "productSaleprice" => esc_html__('numberchange', 'edit-and-manage-product-for-woocommerce' ),
            "productSku" => esc_html__('textareafield', 'edit-and-manage-product-for-woocommerce' ),
            "productStockStatus" => esc_html__('selectoOptionStockStatus', 'edit-and-manage-product-for-woocommerce' ),
            "productStockManage" => esc_html__('selectoOptionStockManage', 'edit-and-manage-product-for-woocommerce' ),
            "productStockQuantity" => esc_html__('numberchange', 'edit-and-manage-product-for-woocommerce' ),
            "productCatalogVisibility" => esc_html__('selectoOptionCatalogVisibility', 'edit-and-manage-product-for-woocommerce' ),
            "action" => esc_html__( 'actionEditView', 'edit-and-manage-product-for-woocommerce' )
        );

        return $productinfoIds;
    }

 public function product_table_header(){
        $headers = array(
            "productID" => esc_html__('ID', 'edit-and-manage-product-for-woocommerce' ),
            "productTitle" => esc_html__('Title', 'edit-and-manage-product-for-woocommerce' ),
            "productDesc" => esc_html__('Description', 'edit-and-manage-product-for-woocommerce' ),
            "productShhortDesc" => esc_html__('Short Desc..', 'edit-and-manage-product-for-woocommerce' ),
            "productType" => esc_html__('Type', 'edit-and-manage-product-for-woocommerce' ),
            "productStatus" => esc_html__('Status', 'edit-and-manage-product-for-woocommerce' ),
            "productPrice" => esc_html__('Price', 'edit-and-manage-product-for-woocommerce' ),
            "productRegularprice" => esc_html__('Regular Price', 'edit-and-manage-product-for-woocommerce' ),
            "productSaleprice" => esc_html__('Sale Price', 'edit-and-manage-product-for-woocommerce' ),
            "productSku" => esc_html__('SKU', 'edit-and-manage-product-for-woocommerce' ),
            "productStockStatus" => esc_html__('Stock Status', 'edit-and-manage-product-for-woocommerce' ),
            "productStockManage" => esc_html__('Manage Stock', 'edit-and-manage-product-for-woocommerce' ),
            "productStockQuantity" => esc_html__('Stock Quantity', 'edit-and-manage-product-for-woocommerce' ),
            "productCatalogVisibility" => esc_html__('Catalog visibility', 'edit-and-manage-product-for-woocommerce' ),
            "action" => esc_html__('Action', 'edit-and-manage-product-for-woocommerce' )
        );

        return $headers;
    }

    public function table_header(){
        $config = $this->wooBEMP_config();
        $header = $this->product_table_header();
        $table_header = "";
        $td_name = esc_attr( 'wooBEMOtd' );
        foreach ( $config as $configkey => $is_visible ){
            if( $is_visible === 'block' ) {
                $table_header_array = esc_html( $header[$configkey] );
                $table_header .= "<td class='".$td_name."'> $table_header_array </td>";
                $array[] = $table_header_array;
            }
        }
        return $array;
    }

}