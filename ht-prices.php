<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function display_ht_price($price_ttc, $display_ttc = false) {
    $discount_multiplier = get_pro_discount_multiplier();

    $price_ttc_with_discount = (float)$price_ttc * $discount_multiplier;
    $price_ht = $price_ttc_with_discount / 1.2;

    if ($display_ttc) {
        return wc_price($price_ht) . ' ' . __('HT', 'textdomain') . ' (' . wc_price($price_ttc_with_discount) . ' ' . __('TTC', 'textdomain') . ')';
    }

    return wc_price($price_ht) . ' HT';
}

// Page boutique et produit
add_filter('woocommerce_get_price_html', 'custom_price_display_based_on_customer', 10, 2);
function custom_price_display_based_on_customer($price, $product) {
    if (is_user_logged_in() && is_professional()) {
        $price_ttc = $product->get_price();
        $price = display_ht_price($price_ttc);
    }

    return $price;
}

// Page panier - prix unitaire
add_filter('woocommerce_cart_item_price', 'display_ht_and_ttc_in_cart_for_pro', 10, 3);
function display_ht_and_ttc_in_cart_for_pro($price, $cart_item, $cart_item_key) {
    if (is_user_logged_in() && is_professional()) {
        $product = $cart_item['data'];
        $price_ttc = $product->get_price();

        $price = display_ht_price($price_ttc, true);
    }
    return $price;
}

// Sous-total global du panier
add_filter('woocommerce_cart_subtotal', 'display_ht_and_ttc_cart_subtotal_for_pro', 10, 3);
function display_ht_and_ttc_cart_subtotal_for_pro($subtotal, $cart, $with_tax) {
    if (is_user_logged_in() && is_professional()) {
        $subtotal_ttc = 0;

        foreach (WC()->cart->get_cart() as $cart_item) {
            $product = $cart_item['data'];
            $quantity = $cart_item['quantity'];
            $price_ttc = $product->get_price() * $quantity;
            $subtotal_ttc += $price_ttc;
        }

        $subtotal = display_ht_price($subtotal_ttc, true);
    }

    return $subtotal;
}

// Sous-total par produit
add_filter('woocommerce_cart_product_subtotal', 'custom_cart_product_subtotal_for_pro', 10, 4);
function custom_cart_product_subtotal_for_pro($subtotal, $product, $quantity, $cart) {
    if (is_user_logged_in() && is_professional()) {
        $price_ttc = $product->get_price() * $quantity;

        $subtotal = display_ht_price($price_ttc, true);
    }

    return $subtotal;
}

// Total
add_filter('woocommerce_cart_total', 'display_ht_and_ttc_cart_total_for_pro', 10, 1);
function display_ht_and_ttc_cart_total_for_pro($total) {
    if (is_user_logged_in() && is_professional()) {
        $total_ttc = 0;

        foreach (WC()->cart->get_cart() as $cart_item) {
            $product = $cart_item['data'];
            $quantity = $cart_item['quantity'];
            $price_ttc = $product->get_price() * $quantity;
            $total_ttc += $price_ttc;
        }

        $total = display_ht_price($total_ttc, true);
    }

    return $total;
}
