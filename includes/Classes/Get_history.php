<?php

namespace wooBEMP\Classes;

class Get_history
{
    private $data;

    public $loaded_ids;
    public $limit;
    public $order_by;
    public function __construct( $loaded_ids, $limit, $order_by )
    {
        $this->loaded_ids = $loaded_ids;
        $this->limit = $limit;
        $this->order_by = $order_by;
    }
    public function filter_history_data( $history_data ){
//        error_log( print_r( $history_data, true ) );
        if( count( $history_data )> 0 ){
//            $history_data= [];
            foreach ( $history_data as $key => $value ){

            }
        }else{
            $history_data = [];
        }

        return $history_data;
    }

    public function get_history_data( ){
        global $wpdb;
        $result = [];
        /*if( count( $this->loaded_ids )> 0 ){
            $loaded_ids_str = explode( $this->loaded_ids );
        }
        $table_name = $wpdb->prefix."wooBEMPhistory";
        $query ="SELECT * FROM `$table_name` WHERE 1 ORDER BY `ID` $this->order_by LIMIT $this->limit";
        $query = preg_replace('/\s+/', ' ', $query);
        $product_data = $wpdb->get_results($query,ARRAY_A);
        if( count( $product_data)>0 ){
            $get_data_ids = array_column( $product_data, 'ID');
        }else{
            $get_data_ids = [];
        }

//        $product_data = $this->filter_history_data( $product_data );

        $result = array(
            'history_data' => $product_data,
            'history_id' => $get_data_ids,
        );*/
        return $result;

    }

}