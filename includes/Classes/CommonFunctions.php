<?php

namespace wooBEMP\Classes;

use DateTime;

class CommonFunctions
{
    public function __construct()
    {

    }

    public function var_test_die( $data ){
        var_dump( $data );
        die();
    }

    public function error_log_print( $data ) {
        error_log( print_r( $data, true ) );
    }

    public static function get_time_ago( $time ){

        $require_time_str= strtotime( $time );
        $time_str=$require_time_str;
        $time_difference = time() - $time_str;

        if( $time_difference < 1 ) { return 'less than 1 second ago'; }
        $condition = array(
            12 * 30 * 24 * 60 * 60 =>  'year',
            30 * 24 * 60 * 60       =>  'month',
            24 * 60 * 60            =>  'day',
            60 * 60                 =>  'hour',
            60                      =>  'minute',
            1                       =>  'second'
        );
        foreach( $condition as $secs => $str ) {
            $d = $time_difference / $secs;
            if( $d >= 1 )
            {
                $t = round( $d );
                return  $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
            }
        }
    }

    /*public static function get_sql_table_information( $tablename ){
        global $wpdb;
        $table_name = $wpdb->prefix.$tablename; // Replace 'your_table_name' with the actual table name.
// Query to retrieve the CREATE TABLE statement.
        $query = "SHOW CREATE TABLE $table_name";
        $result = $wpdb->get_row($query);
        error_log( print_r( $result, true ) );
        return $result;
    }*/

    public static function get_all_product_categories(){
        $categories_data = get_categories(
            array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            )
        );
        if( is_array( $categories_data ) && count( $categories_data )> 0 ){
            foreach ( $categories_data as $category ) {
                $category = (array) $category;
                $categories[ $category['slug']] =  $category['name'];
            }
        }else{
            $categories = [];
        }

        return $categories;
    }

    public static function sanitize_array_values( $data ) {
        // Ensure $data is an array
        if ( !is_array($data) ) {
            return $data;
        }
        foreach ($data as $key => $value) {
            // If the value is an array, recursively sanitize it
            if (is_array($value)) {
                $data[$key] = self::sanitize_array_values($value);
            } else {
                // Sanitize the individual value
                $data[$key] = sanitize_text_field($value);
            }
        }

        return $data;
    }

}