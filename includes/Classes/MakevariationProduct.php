<?php

namespace wooBEMP\Classes;

class MakevariationProduct
{
    /*public function make_variation_product(){
        // Replace 'your_product_id' with the actual product ID for which you want to add a variation.
        $product_id = 'your_product_id';

        // Replace 'attribute_name' with the attribute name you want to add.
        $attribute_name = 'color';

        // New attribute values to be added.
        $new_attribute_values = array('Red', 'Blue', 'Green');

        // Loop through the new attribute values and add them to the product variation.
        foreach ($new_attribute_values as $attribute_value) {
            // Prepare the variation data.
            $variation_data = array(
                'attributes' => array(
                    $attribute_name => $attribute_value,
                ),
                'regular_price' => '19.99',  // Set the regular price for the variation.
                'virtual' => false,         // Set whether the variation is virtual (digital) or physical.
                'downloadable' => false,    // Set whether the variation is downloadable or not.
                'stock_qty' => 100,         // Set the stock quantity for the variation.
                'stock_status' => 'instock', // Set the stock status. Possible values: 'instock', 'outofstock', 'onbackorder'.
            );

            // Create the variation.
            $variation_id = wp_insert_post(array(
                'post_title' => 'Variation - ' . $attribute_value,
                'post_name' => 'variation-' . sanitize_title($attribute_value),
                'post_parent' => $product_id,
                'post_type' => 'product_variation',
                'post_status' => 'publish',
                'guid' => home_url() . '?product_variation=' . $variation_id,
                'post_content' => '',
            ));

            // Update the variation data.
            update_post_meta($variation_id, '_price', $variation_data['regular_price']);
            update_post_meta($variation_id, '_virtual', $variation_data['virtual'] ? 'yes' : 'no');
            update_post_meta($variation_id, '_downloadable', $variation_data['downloadable'] ? 'yes' : 'no');
            update_post_meta($variation_id, '_stock', $variation_data['stock_qty']);
            update_post_meta($variation_id, '_stock_status', $variation_data['stock_status']);

            // Set attributes for the variation.
            foreach ($variation_data['attributes'] as $attribute => $value) {
                $attribute_term = get_term_by('name', $value, $attribute);
                if ($attribute_term) {
                    wp_set_post_terms($variation_id, $attribute_term->term_id, $attribute);
                }
            }
        }

    }*/
}