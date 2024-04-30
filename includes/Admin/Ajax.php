<?php

namespace wooBEMP\Admin;

use wooBEMP\Classes\CommonFunctions;
use wooBEMP\Classes\Get_history;
use wooBEMP\Classes\ProductAttributeUpdate;
use wooBEMP\Classes\Get_products;
use wooBEMP\Classes\Bemp_config;

class Ajax {
    function  __construct() {
        add_action( 'wp_ajax_load_more_product_data', [$this, 'load_more_products'] );
        add_action( 'wp_ajax_update_product_attributes', [$this, 'wooBEMP_update_product_attributes'] );
        add_action( 'wp_ajax_show_hide_column_title_setting', [$this, 'wooBEMP_show_hide_column_title_setting'] );
        add_action( 'wp_ajax_search_product_data', [$this, 'wooBEMP_search_product_data'] );

        add_action('wp_ajax_store_html_content', [$this,'store_html_content']);
        add_action('wp_ajax_nopriv_store_html_content', [$this,'store_html_content']);
        add_action('wp_ajax_wooBEMP_update_per_batch_data', [$this,'wooBEMP_update_per_batch_data']);

        add_action('wp_ajax_wooBEMP_get_history_data', [$this,'wooBEMP_get_history_data']);
    }

    public function wooBEMP_update_per_batch_data(){
        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        if( wp_verify_nonce( $nonce, 'wooBEMPNonceCheck' ) ){
            if( isset( $_POST['per_batch_count'] ) ){
                $ber_batch_count = (int)sanitize_text_field( $_POST['per_batch_count'] );
                $success = update_option( 'wooBEMP_per_batch_count', $ber_batch_count );
                $result = array(
                    'type' => true,
                    'data' => $success
                );
            }else{
                $result = array(
                    'type' => false,
                    'data' => 'no data'
                );
            }
        }else{
            $result = array(
                'type' => false,
                'data' => 'no data'
            );
        }
        wp_send_json_success( $result );
    }

    public function wooBEMP_show_hide_column_title_setting() {
        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        if( wp_verify_nonce( $nonce, 'wooBEMPNonceCheck' ) ){

            $new = [];
            $config_info_instance = new Bemp_config();
            $current_title_attributes = $config_info_instance->wooBEMP_config();
            $changed_column_title = CommonFunctions::sanitize_array_values( $_POST['column_attributes'] );

            foreach ( $current_title_attributes as $key => $value){
                if( in_array( $key, $changed_column_title )){
                    $new[$key] = "block";
                }else{
                    $new[$key] = "none";
                }
            }

            update_option( 'wooBEMP_column_settings', maybe_serialize($new) );

            $result = array(
                'type' => true,
                'data' => array(),
                'data1' => ""
            );

        }else{
            $result = array(
                'type' => false,
                'data' => array(),
                'data1' => ""
            );
        }
        wp_send_json_success( $result );

    }

    public function wooBEMP_update_product_attributes() {
        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        $product_id = isset( $_POST['product_id'] ) ? sanitize_text_field( $_POST['product_id'] ) : '';
        $new_product_attribute_value = isset( $_POST['newProductAttributeValue'] ) ? sanitize_text_field( $_POST['newProductAttributeValue'] ) : '';
        $product_attribute_type = isset( $_POST['productAttributeType'] ) ? sanitize_text_field( $_POST['productAttributeType'] ) : '';

        if ( wp_verify_nonce( $nonce, 'wooBEMPNonceCheck' ) && $product_id !=='' && $product_attribute_type !=='' ) {
            $update = new ProductAttributeUpdate($product_id, $product_attribute_type, $new_product_attribute_value);
            $data = $update->updateProductAttributes();
            $result = array(
                'type' => 'success',
                'data' => array( $product_id, $new_product_attribute_value, $product_attribute_type ),
                'data1' => $data
            );
        } else {
            $result = array(
                'type' => false,
                'data' => array(),
                'data1' => ""
            );
        }

        wp_send_json_success( $result );
    }

    public function store_html_content(){
        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        $result = array(
            'type' => false,
            'data' => array(),
            'data1' => ""
        );
        if( wp_verify_nonce( $nonce, 'wooBEMPNonceCheck' ) ) {
            $product_id = isset($_POST['product_id']) ? sanitize_text_field($_POST['product_id']) : '';
            $new_product_attribute_value = isset($_POST['newProductAttributeValue']) ? sanitize_text_field( $_POST['newProductAttributeValue'] ) : '';
            $new_product_attribute_value = sanitize_text_field(htmlspecialchars($new_product_attribute_value));
            $product_attribute_type = isset($_POST['productAttributeType']) ? sanitize_text_field($_POST['productAttributeType']) : '';

            $new_product_attribute_value = htmlspecialchars_decode($new_product_attribute_value);
            if ($product_id !== '' && $new_product_attribute_value !== '' && $product_attribute_type !== '') {
                $update = new ProductAttributeUpdate($product_id, $product_attribute_type, $new_product_attribute_value);
                $data = $update->updateProductAttributes();
                $result = array(
                    'type' => 'success',
                    'data' => array( $product_id, $new_product_attribute_value, $product_attribute_type ),
                    'data1' => $data
                );
            }
        }

        wp_send_json_success( $result );
    }

    public function load_more_products() {
        $get_product_data_instance = new Get_products();
        $config_info_instance = new Bemp_config();

        $wooBEMP_config = $config_info_instance->wooBEMP_config();

        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        if ( wp_verify_nonce( $nonce, 'wooBEMPNonceCheck' ) ) {
            $loaded_ids = explode(" ,", $_POST['alreadyLoadedIds'] );
            $products_data = $get_product_data_instance->get_search_data( $loaded_ids );
            $current_loaded_ids = array_column( $products_data, 'id');
            $loaded_current_ids = array_merge( $loaded_ids, $current_loaded_ids);
//            $loaded_current_str = serialize( $loaded_current_ids);
            $loaded_current_str = implode( " ,", $loaded_current_ids);


            $result = array(
                'type' => 'success',
                'product_data' => array( $products_data ),
                'loaded_product_ids' =>$loaded_current_str,
                'wooBEMP_config' => array( $wooBEMP_config )
            );
        }else {
            $result = array(
                'type' => false,
                'product_data' => array(),
                'wooBEMP_config' => array()
            );
        }

        wp_send_json_success($result);

    }

    public function wooBEMP_search_product_data(){

        $get_product_data_instance = new Get_products();
        $config_info_instance = new Bemp_config();

        $wooBEMP_config = $config_info_instance->wooBEMP_config();

        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

        if ( wp_verify_nonce( $nonce, 'wooBEMPNonceCheck' ) ) {

            if( isset( $_POST['alreadyLoadedIds'] )){
                $alreadyLoadedIds = sanitize_text_field( $_POST['alreadyLoadedIds'] );
                $alreadyLoadedIds = trim($alreadyLoadedIds, ',');
                $loaded_ids = explode(" ,", $alreadyLoadedIds );
            }else{
                $loaded_ids = [];
            }
            $decodedString = urldecode( sanitize_text_field( $_POST['data_search'] ) );
            $products_data = $get_product_data_instance->get_search_data( $loaded_ids, $decodedString );
            $current_loaded_ids = array_column( $products_data, 'id');
            $loaded_current_ids = array_merge( $loaded_ids, $current_loaded_ids);
//            $loaded_current_str = serialize( $loaded_current_ids);
            $loaded_current_str = implode( " ,", $loaded_current_ids);
            $result = array(
                'type' => 'success',
                'product_data' => array( $products_data ),
                'loaded_product_ids' =>$loaded_current_str,
                'wooBEMP_config' => array( $wooBEMP_config )
            );
        }else {
            $result = array(
                'type' => false,
                'product_data' => array(),
                'wooBEMP_config' => array()
            );
        }

        wp_send_json_success($result);

    }

    public function wooBEMP_get_history_data(){


        $nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        if ( wp_verify_nonce( $nonce, 'wooBEMPNonceCheck' ) ) {

            $limit = 30;
            $order_by = 'DESC';
            $loaded_ids=[];
            $get_history = new Get_history( $loaded_ids, $limit, $order_by );
            $result_data = $get_history->get_history_data( );
            $result = array(
                'type' => true,
                'data' => $result_data,
            );
        }else{
            $result = array(
                'type' => false,
                'data' => array(),
                'Message' => 'Error Loading'
            );
        }
        wp_send_json_success($result);
    }

}