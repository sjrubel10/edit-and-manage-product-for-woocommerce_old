<?php

namespace wooBEMP\Classes;

class Products
{
    /*protected function getVariableProductPrice( $variable, $type, $tax = false ) {
        $min_max_first_default = apply_filters( 'woo_feed_variable_product_price_range', 'min' );
        if ( isset( $this->config['variable_price'] ) ) {
            $min_max_first_default = $this->config['variable_price'];
        }

        $price = 0;
        if ( 'first' == $min_max_first_default ) {
            $children = $variable->get_visible_children();
            if ( isset( $children[0] ) && ! empty( $children[0] ) ) {
                $variation = wc_get_product( $children[0] );
                $price     = $this->get_price_by_product_type( $variation, $type, $tax );
            }
        } else {
            if ( 'regular_price' == $type ) {
                $price = $variable->get_variation_regular_price( $min_max_first_default );
            } elseif ( 'sale_price' == $type ) {
                $price = $variable->get_variation_sale_price( $min_max_first_default );
            } else {
                $price = $variable->get_variation_price( $min_max_first_default );
            }

            // Get WooCommerce Multi language Price by Currency.
            $price = apply_filters( 'woo_feed_wcml_price',
                $price, $variable->get_id(), $this->get_feed_currency(), '_' . $type
            );

            // Get Price with tax
            if ( true === $tax ) {
                $price = woo_feed_get_price_with_tax( $price, $variable );
            }
        }

        return $price > 0 ? $price : '';
    }

    protected function get_price_by_product_type( $product, $price_type, $tax = false ) {
        if ( $product->is_type( 'variable' ) ) {
            $price = $this->getVariableProductPrice( $product, $price_type, $tax );
        } elseif ( $product->is_type( 'grouped' ) ) {
            $price = $this->getGroupProductPrice( $product, $price_type, $tax );
        } elseif ( $product->is_type( 'bundle' ) ) {
            //TODO Diff taxation
            $price = $this->getBundleProductPrice( $product, $price_type, $tax );
        } elseif ( $product->is_type( 'composite' ) ) {
            //TODO Diff taxation
            $price = $this->getCompositeProductPrice( $product, $price_type, $tax );
        } elseif ( $product->is_type( 'bundled' ) ) {
            // iconic woocommerce product bundled plugin
            $price = $this->iconic_bundle_product_price( $product, $price_type, $tax );
        } else {
            $price = $this->get_price_by_price_type( $product, $price_type, $tax );
        }

        return $price > 0 ? $price : '';
    }

    protected function sale_price_sdate( $product ) {
        $startDate = $product->get_date_on_sale_from();
        if ( is_object( $startDate ) ) {
            $sale_price_sdate = $startDate->date_i18n();
        } else {
            $sale_price_sdate = '';
        }

        return apply_filters( 'woo_feed_filter_product_sale_price_sdate', $sale_price_sdate, $product, $this->config );
    }
    protected function sale_price_edate( $product ) {
        $endDate = $product->get_date_on_sale_to();
        if ( is_object( $endDate ) ) {
            $sale_price_edate = $endDate->date_i18n();
        } else {
            $sale_price_edate = "";
        }

        return apply_filters( 'woo_feed_filter_product_sale_price_edate', $sale_price_edate, $product, $this->config );
    }

    protected function get_feed_currency() {
        $currency = get_woocommerce_currency();
        if ( isset( $this->config['feedCurrency'] ) ) {
            $currency = $this->config['feedCurrency'];
        }

        return $currency;
    }

    protected function get_price_by_price_type( $product, $price_type, $tax ) {

        [0] => simple
        [1] => variable
        [2] => variation
        [3] => grouped
        [4] => external
        [5] => composite
        [6] => bundle
        [7] => bundled
        [8] => yith_bundle
        [9] => yith-composite
        [10] => subscription
        [11] => variable-subscription
        [12] => woosb

        if ( 'regular_price' === $price_type ) {
            $price = $product->get_regular_price();
        } elseif ( 'price' === $price_type ) {
            $price = $product->get_price();
        } else {
            $price = $product->get_sale_price();
        }

        // Get WooCommerce Multi language Price by Currency.
        $price = apply_filters( 'woo_feed_wcml_price',
            $price, $product->get_id(), $this->get_feed_currency(), '_' . $price_type
        );

        // Get Price with tax
        if ( true === $tax ) {
            $price = woo_feed_get_price_with_tax( $price, $product );
        }

        return $price;
    }*/
}