<?php

namespace wooBEMP\Classes;

use WP_Query;

class Get_products
{
    private $is_call;

    public function __construct()
    {
           $this->is_call = 0;
    }

    public function condition_wise_query_post( $field, $condition, $value, $is_post = false ){
        $field = trim( sanitize_text_field( $field ) );
        $condition =  trim( sanitize_text_field( $condition ) );
        $value =  trim( sanitize_text_field( $value ) );

        if( !is_numeric( $value ) && $condition !== "LIKE" ){
            if( $condition === "Include" || $condition === "Exclude" ){
                $segments = array_map('trim', explode(',', $value));
                $value = implode(',', $segments);
                $value = trim( $value, ',');
                $elements = explode(',', $value);
                $value ="'".implode("','", $elements)."'";
            }else{
                $value = "'".$value."'";
            }
        }else{
            $value = "$value";
        }
        if( $condition === "EQUAL" ){
            if( $is_post ){
                $query = "p.".$field. " = $value AND ";
            }else{
                if( $field === "product_cat" ||  $field === "product_type" ){
                    $query = " ( tt.taxonomy = '$field' AND t.slug = $value ) AND ";
                }else {
                    $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value = $value ) AND ";
                }
            }
        }else if( $condition === "NOT EQUAL" ){
            if( $is_post ){
                $query = "p.".$field. " <> $value AND ";
            }else{
                if( $field === "product_cat" ||  $field === "product_type" ){
                    $query = " tt.taxonomy = '$field' AND t.slug <> $value AND ";
                }else {
                    $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value <> $value ) AND ";
                }
            }
        }else if( $condition === "LIKE" ){
            if( $is_post ){
                $query ="p.".$field." LIKE '%".$value."%' AND ";
            }else{
                if( $field === "product_cat" ||  $field === "product_type"  ){
                    $query = " tt.taxonomy = '$field' AND t.slug LIKE '%".$value."%' AND ";
                }else{
                    $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value LIKE '%".$value."%' ) AND ";
                }

            }
        }else if( $condition === "NOT LIKE" ){
            if( $is_post ){
                $query ="p.".$field." NOT LIKE '%".$value."%' AND ";
            }else{
                if( $field === "product_cat" ||  $field === "product_type"  ){
                    $query = " tt.taxonomy = '$field' AND t.slug NOT LIKE '%".$value."%' AND ";
                }else {
                    $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value NOT LIKE '%" . $value . "%' ) AND ";
                }
            }
        }else if( $condition === "BETWEEN" ){
            if( $is_post ){
                $query ="p.".$field." LIKE '%".$value."%' AND ";
            }else{
                if (preg_match('/(\d+)\s*,\s*(\d+)/', $value, $matches)) {
                    $number1 = (int)intval($matches[1]);
                    $number2 = (int)intval($matches[2]);
                }
                $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value BETWEEN $number1 AND $number2 ) AND ";
            }
        }else if( $condition === "Include" ){
            if( $is_post ){
                $query = "p.".$field." IN ( $value ) AND ";
            }else{
                $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value IN ( $value ) ) AND ";
            }

        }else if( $condition === "Exclude" ){
            if( $is_post ){
                $query = "p.".$field." NOT IN ( $value ) AND ";
            }else{
                $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value NOT IN ( $value ) ) AND ";
            }

        }else if( $condition === "GREATER THAN" ){
            if( $is_post ){
                $query = "p.".$field." > $value AND ";
            }else{
                $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value > $value ) AND ";
            }

        }else if( $condition === "LESS THAN" ){
            if( $is_post ){
                $query = "p.".$field." < $value AND ";
            }else{
                $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value < $value ) AND ";
            }
        }else if( $condition === "EMPTY" ){
            if( $is_post ){
                $query = "p.".$field." = '' AND ";
            }else{
//                $query = "( pm$field.meta_key = '$field' AND pm$field.meta_value = '' ) AND ";
//                $query = "( pm$field.meta_value IS NULL OR  pm$field.meta_value = '' ) AND ";
                $query = " (pm$field.meta_key is null ) AND";
//                $needtoChange = "SELECT DISTINCT * FROM wp_posts p LEFT JOIN wp_postmeta m2 ON m2.post_id = p.ID AND m2.meta_key = '_regular_price' WHERE m2.meta_key is null  AND p.post_type = 'product' AND p.post_status = 'publish'";
            }
        }else{
            $query = "";
        }

        return  $query;
    }

    public function inner_join( $meta_table, $column, $join_type, $table_prefix ){
        if( $column === "product_cat" || $column === "product_type" ) {
            $join = $this->make_join_with_term_table( $table_prefix, $this->is_call );
            $this->is_call ++;
        } else {
            if( $join_type === 'LEFT' ){
                $aa = "AND pm$column.meta_key = '$column'";
            }else{
                $aa = "";
            }
            $join = " $join_type JOIN `$meta_table` AS pm$column ON p.ID = pm$column.post_id $aa ";
        }

        return $join;
    }

    public function make_join_with_term_table( $table_prefix, $is_call ){
        if( $is_call === 0){
            $join = " INNER JOIN ".$table_prefix."term_relationships AS tr ON p.ID = tr.object_id
                      INNER JOIN ".$table_prefix."term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                      INNER JOIN ".$table_prefix."terms AS t ON tt.term_id = t.term_id ";
        }else{
            $join = "";
        }

        return $join;
    }

    public function get_search_data( $alreadyLoadedIds = [], $decodedString = [] ){
        global $wpdb ;

        $post_table = $wpdb->prefix."posts";
        $postmeta_table = $wpdb->prefix."postmeta";
        $limit = get_option('wooBEMP_per_batch_count');
        if( !$limit ){
            $limit = 10;
        }
        $meta_query = '';
        $filter_query_post_meta_join = '';
        if( !empty( $decodedString ) ) {
//            $urlEncodedString = $search_option;
            $decodedString = urldecode( $decodedString );
            $parsedArray = array();
            parse_str($decodedString, $parsedArray);
            $filter_option = array_values($parsedArray);

            $post_Table_column = array('post_title','post_content', 'ID', 'post_status', 'post_date');
            $filter_query_post = '';
            $filter_query_post_meta = '';
            $filter_query_post_meta_join = '';

            foreach ($filter_option as $key => $searchOptions) {
                if ($searchOptions[0] === "select" || $searchOptions[1] === "SELECT ANY" || $searchOptions[2] === "") {
                    $filter_query_post .= '';
                    $filter_query_post_meta .= '';
                    $filter_query_post_meta_join .= '';
                } else {
                    $searchKey = trim($searchOptions[0]);
                    if (in_array($searchKey, $post_Table_column)) {
                        $filter_query_post .= $this->condition_wise_query_post($searchOptions[0], $searchOptions[1], $searchOptions[2], true);
                    } else {
                        if(  $searchOptions[1] === "EMPTY" ){
                            $filter_query_post_meta_join .= $this->inner_join( $postmeta_table, $searchOptions[0], 'LEFT', $wpdb->prefix );
                        }else{
                            $filter_query_post_meta_join .= $this->inner_join( $postmeta_table, $searchOptions[0], 'INNER', $wpdb->prefix );
                        }

                        $filter_query_post_meta .= $this->condition_wise_query_post($searchOptions[0], $searchOptions[1], $searchOptions[2]);
                    }
                }
            }

            if ($filter_query_post !== "") {
                $lastAndPos = strrpos($filter_query_post, "AND");
                if ($lastAndPos !== false) {
                    // Remove the last "AND" from the string
                    $filter_query_post = substr($filter_query_post, 0, $lastAndPos) . substr($filter_query_post, $lastAndPos + strlen("AND"));
                }
                $post_query = "p.post_type = 'product' AND ". $filter_query_post;
            } else {
                $post_query = "";
            }

            if ($filter_query_post_meta !== "") {
                $lastAndPos = strrpos( $filter_query_post_meta, "AND" );
                if ($lastAndPos !== false) {
                    $filter_query_post_meta = substr( $filter_query_post_meta, 0, $lastAndPos ) . substr( $filter_query_post_meta, $lastAndPos + strlen("AND" ) );
                }
                $meta_query = $filter_query_post_meta ;
            } else {
                $filter_query_post_meta_join = '';
                $meta_query = "";
            }
        }else{
            $post_query = " p.post_type = 'product' AND p.post_status = 'publish' ";
        }

        if( $post_query === "" ){
            $post_query = " p.post_type = 'product' AND p.post_status = 'publish' ";
        }

        if( count( $alreadyLoadedIds ) > 0 ){

            $alreadyLoadedIds = array_filter( $alreadyLoadedIds );
            $alreadyLoadedIds_str = implode(", ",$alreadyLoadedIds );
            if( $alreadyLoadedIds_str !== "" ) {
                $already_loaded_remove_query = " p.ID NOT IN ( $alreadyLoadedIds_str ) AND ";
            }else{
                $already_loaded_remove_query = "";
            }
        }else{
            $already_loaded_remove_query = "";
        }

        if( $post_query !=="" && $meta_query !=="" ){
            $need_and = " AND ";
        }else{
            $need_and = "";
        }


        if( 1===2 ){
            /*$query ="SELECT `ID` FROM `$post_table` AS p $filter_query_post_meta_join WHERE $already_loaded_remove_query $post_query $need_and $meta_query GROUP BY p.ID ORDER BY p.ID DESC LIMIT %d";
            $query = preg_replace('/\s+/', ' ', $query );
            $query = $wpdb->prepare( $query, $limit );
            $product_data = $wpdb->get_results( $query,ARRAY_A );

            $get_data_ids = array_column( $product_data, 'ID');

            if( count( $product_data )> 0 ){
                $product_data = $this->get_product_by_ids( $get_data_ids );
            }*/
            $product_data = array();

        } else{
            $product_ids = array();
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $limit, // Get all products
                'post__not_in'   => $alreadyLoadedIds,
            );
            $products = new WP_Query( $args );
            if ( $products->have_posts() ) {
                while ( $products->have_posts() ) {
                    $products->the_post();
                    $product_ids[] = get_the_ID();
                }
                wp_reset_postdata(); // Reset post data
            }
            if( count( $product_ids )> 0 ){
                $product_data = $this->get_product_by_ids( $product_ids );
            }
        }

        return $product_data;
    }

    public function get_product_by_ids( $get_data_ids ){
        $products_array = array(); // Array to store products
        foreach ($get_data_ids as $product_id) {
            $product = wc_get_product( $product_id );
            if ( $product ) {
                // Array to store product data
                $product_array = array(
                    'name' => $product->get_name(),
                    'id' => $product->get_id(),
                    'description' => $product->get_description(),
                    'short_description' => $product->get_short_description(),
                    'status' => $product->get_status(),
                    'type' => $product->get_type(),
                    'link' => $product->get_permalink(),
                    'availability' => 'Music',
                    'price' => $product->get_price(),
                    'sale_price' => $product->get_sale_price(),
                    'regular_price' => $product->get_regular_price(),
                    'sku' => $product->get_sku(),
                    'brand' => '',
                    'canonical_link' => '',
                    'stock_status' => $product->get_stock_status(),
                    'manage_stock' => $product->get_manage_stock(),
                    'stock_quantity' => $product->get_stock_quantity(),
                    'is_commentable' => '',
                    'is_parent' => $product->get_parent_id(),
                    'catalog_visibility' => $product->get_catalog_visibility(),
                    'edit_link' => '',
                    // Add more product data as needed
                );

                // Get product meta data
                $meta_data = get_post_meta( $product_id );
                foreach ( $meta_data as $key => $value ) {
                    $product_array[$key] = $value[0]; // Assuming single value for each meta key
                }

                if( isset( $product_array['_thumbnail_id'])){
                    $product_array['image_link'] = wp_get_attachment_url( $product_array['_thumbnail_id'] );
//                    $product_array['image_link'] = wp_get_attachment_url( isset( $product_array['_thumbnail_id'] ) ? $product_array['_thumbnail_id'] : "" );;
                }

                $visibility = $product->get_catalog_visibility();
//                $product_array['catalog_visibility'] = $visibility;

                $terms = wp_get_post_terms($product_id, 'product_cat'); // Change 'product_cat' to the desired taxonomy if needed
                $product_array['product_category'] = wp_list_pluck($terms, 'name');

                // Add product array to the products array
                $products_array[] = $product_array;
            }
        }

        return $products_array;
    }

}