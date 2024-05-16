<?php
/**
 * Created by PhpStorm.
 * User: Sj
 * Date: 6/10/2023
 * Time: 9:37 AM
 */

namespace wooBEMP\Classes;


class ProductAttributeUpdate
{
    protected $productID;
    protected $product;
    protected $attributeName;
    protected $productAttributeValue;

    protected $productAttrName = array(
                                    0 => "_visibility",
                                    1 => "_sku",
                                    2 => "_price",
                                    3 => "_regular_price",
                                    4 => "_sale_price",
                                    5 => "_sale_price_dates_from",
                                    6 => "_sale_price_dates_to",
                                    7 => "total_sales",
                                    8 => "_tax_status",
                                    9 => "_tax_class",
                                    10 => "_manage_stock",
                                    11 => "_stock",
                                    12 => "_stock_status",
                                    13 => "_backorders",
                                    14 => "_low_stock_amount",
                                    15 => "_sold_individually",
                                    16 => "_weight",
                                    17 => "_length",
                                    18 => "_width",
                                    19 => "_height",
                                    20 => "_upsell_ids",
                                    21 => "_crosssell_ids",
                                    22 => "_purchase_note",
                                    23 => "_default_attributes",
                                    24 => "_product_attributes",
                                    25 => "_virtual",
                                    26 => "_downloadable",
                                    27 => "_download_limit",
                                    28 =>" _download_expiry",
                                    29 => "_featured",
                                    30 => "_downloadable_files",
                                    31 => "_wc_rating_count",
                                    32 =>" _wc_average_rating",
                                    33 => "_wc_review_count",
                                    34 =>" _variation_description",
                                    35 => "_thumbnail_id",
                                    36 =>" _file_paths",
                                    37 => "_product_image_gallery",
                                    38 =>" _product_version",
                                    39 => "_wp_old_slug",
                                    40 =>" _edit_last",
                                    41 => "_edit_lock"
    );

    public function __construct( $productID, $attributeName, $productAttributeValue )
    {
        $this-> productID = $productID;
        $this-> attributeName = $attributeName;
        $this-> productAttributeValue = $productAttributeValue;

        $this->product = wc_get_product(  $this-> productID );
        if(!is_object($this->product)){
            die();
        }
    }

    /**
     * @return mixed
     */

    public function get_term_id_by_prodct_id( $taxonomy ) {
        $product_id = $this-> productID;
        $terms = get_the_terms( $product_id, $taxonomy );
        $term_id = array_shift( $terms )->term_id;
        return  $term_id;

    }

    public function get_taxonomy_id_from_product_id( string $taxonomy) {
        $product_id = $this-> productID;
        $terms = get_the_terms($product_id, $taxonomy);

        if (empty($terms)) {
            return null;
        }

        return $terms[0]->term_id;
    }

    public function update_product_title() {

        if ( empty ( $this-> productAttributeValue ) ) {
            return false;
        }

        // ensure title case of $new_title
        $new_title = mb_convert_case( $this-> productAttributeValue, MB_CASE_TITLE, "UTF-8" );

        // if $new_title is defined, but it matches the current title, return
        if ( $this->product->get_title() === $new_title ) {
            return false;
        }

        $post_update = array(
            'ID'         => $this-> productID,
            'post_title' => $new_title
        );

        $is_Set = wp_update_post( $post_update );
        return $is_Set;
    }

    public function update_product_description(){
        if ( empty ( $this-> productAttributeValue ) ) {
            return false;
        }
        $post_update = array(
            'ID'         => $this-> productID,
            'post_content' => $this-> productAttributeValue
        );

        $is_Set = wp_update_post( $post_update );

        return $is_Set;
    }

    private function insert_into_history_tavle( $produc_title, $produc_id, $attribute_type, $attribute_value_new, $attribute_value_old ){
        global $wpdb;
        $result = 1;
        /*$table_name = $wpdb->prefix."wooBEMPhistory";
        $query = " INSERT INTO `$table_name` ( `product_title`,`action`, `product_id`, `new_value`, `old_value`) VALUES ( %s, %s, %d, %s, %s )";
        $prepare_query = $wpdb->prepare( $query, $produc_title, $attribute_type, $produc_id, $attribute_value_new, $attribute_value_old);
        $result = $wpdb->query( $prepare_query);*/
        return $result;
    }

    public function update_product_short_description(){
        if ( empty ( $this-> productAttributeValue ) ) {
            return false;
        }
        $post_update = array(
            'ID'         => $this-> productID,
            'post_excerpt' => $this-> productAttributeValue
        );

        $is_Set = wp_update_post( $post_update );

        return $is_Set;
    }

    public function updateProductAttributes() {
        $is_Set ="";
        if( $this-> attributeName === "wooBEMP_productTitle" ) {
            $old_title = $this->product->get_title();
            $is_Set = $this -> update_product_title();

            $this->insert_into_history_tavle( $this-> productAttributeValue, $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_title );
        }  else if(  $this-> attributeName === "wooBEMP_productDesc" ){
            $old_desc = $this->product->get_description();
            $is_Set = $this -> update_product_description();

            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_desc );
        }   else if(  $this-> attributeName === "wooBEMP_productShhortDesc" ){
            $old_srt_desc = $this->product->get_short_description();
            $is_Set = $this -> update_product_short_description();

            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_srt_desc );

        } else if (  $this-> attributeName === "wooBEMP_productType" ){
            $product = wc_get_product( $this-> productID );
            $productId = $product->get_id();
            $old_type = $product->get_type();
            wp_remove_object_terms( $productId, $product->get_type(), 'product_type', true );
            $is_Set = wp_set_object_terms( $productId, $this-> productAttributeValue, 'product_type', true );

            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_type );

        } else if (  $this-> attributeName === "wooBEMP_productStatus" ){
            $post_status_update = array();

            $old_status = $this->product->get_status();
            $post_status_update['ID'] = $this-> productID;
            $post_status_update['post_status'] = $this-> productAttributeValue;
            $is_Set = wp_update_post( $post_status_update );

            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_status );

        } else if (  $this-> attributeName === "wooBEMP_productPrice" ){
            $is_exist = metadata_exists(  'post', $this-> productID, '_price' );

            $old_price = $this->product->get_price();
            if( $is_exist )  {
                $is_Set = update_post_meta( $this-> productID, '_price', $this-> productAttributeValue );
            }else {
                $is_Set = add_post_meta( $this-> productID, '_price', $this-> productAttributeValue );
            }

            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_price );

        } else if (  $this-> attributeName === "wooBEMP_productRegularprice" ){
            $is_exist = metadata_exists(  'post', $this-> productID, '_regular_price' );
            $old_regular_price = $this->product->get_regular_price();

            if( $is_exist )  {
                $is_Set = update_post_meta( $this-> productID, '_regular_price', $this-> productAttributeValue );
                update_post_meta( $this-> productID, '_price', $this-> productAttributeValue );
            } else {
                $is_Set = add_post_meta( $this-> productID, '_regular_price', $this-> productAttributeValue );
                add_post_meta( $this-> productID, '_price', $this-> productAttributeValue );
            }

            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_regular_price );

        } else if (  $this-> attributeName === "wooBEMP_productSaleprice" ){
            $is_exist = metadata_exists(  'post', $this-> productID, '_sale_price' );
            $old_sale_price = $this->product->get_sale_price();

            if( $is_exist )  {
                $is_Set = update_post_meta( $this-> productID, '_sale_price', $this-> productAttributeValue );
            }else {
                $is_Set = add_post_meta( $this-> productID, '_sale_price', $this-> productAttributeValue );
            }

            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_sale_price );

        } else if (  $this-> attributeName === "wooBEMP_productSku" ){
            $is_exist = metadata_exists(  'post', $this-> productID, '_sku' );
            $old_sale_sku = $this->product->get_sku();
            if( $is_exist )  {
                $is_Set = update_post_meta( $this-> productID, '_sku', $this-> productAttributeValue );
            }else {
                $is_Set = add_post_meta( $this-> productID, '_sku', $this-> productAttributeValue );
            }
            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_sale_sku );

            //Do Something
            //@Todo
        } else if (  $this-> attributeName === "wooBEMP_productStockStatus" ){
//            $is_Set = update_post_meta( $this-> productID, '_stock_status', $this-> productAttributeValue );
            $is_exist = metadata_exists(  'post', $this-> productID, '_stock_status' );
            $old_stock_status = $this->product->get_stock_status();
            if( $is_exist )  {
                $is_Set = update_post_meta( $this-> productID, '_stock_status', $this-> productAttributeValue );
            }else {
                $is_Set = add_post_meta( $this-> productID, '_stock_status', $this-> productAttributeValue );
            }
            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_stock_status );

        } else if (  $this-> attributeName === "wooBEMP_productStockManage" ){
            $old_manage_stock = $this->product->get_manage_stock();
            $is_Set = update_post_meta( $this-> productID, '_manage_stock', $this-> productAttributeValue );
            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_manage_stock );

        } else if (  $this-> attributeName === "wooBEMP_productStockQuantity" ){
            $old_stock_quantity = $this->product->get_stock_quantity();
            $is_Set = update_post_meta( $this-> productID, '_stock', $this-> productAttributeValue );
            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_stock_quantity );

        } else if (  $this-> attributeName === "wooBEMP_productCatalogVisibility" ){
            $old_catalog_visibility = $this->product->get_catalog_visibility();

            wp_remove_object_terms( $this->productID, $this->product->get_catalog_visibility(), 'product_visibility' );
            $is_Set = wp_set_object_terms( $this->productID, $this->productAttributeValue, 'product_visibility', true );
            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_catalog_visibility );

        } else if (  $this-> attributeName === "wooBEMP_productTrash" ){

            $old_value = $this->product->get_status();
            $is_Set = wp_trash_post( $this->productID );
            $this->insert_into_history_tavle( $this->product->get_title(), $this-> productID, $this-> attributeName, $this-> productAttributeValue, $old_value );
        } else if (  $this-> attributeName === "wooBEMP_productPermanentTrash" ){
            $is_Set = wp_delete_post( $this->productID, true );
        } else {
            $is_Set ="";
        }
        return  $is_Set;
    }

}