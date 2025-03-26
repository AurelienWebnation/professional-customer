<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function display_ht_price($price_ttc, $display_ttc = false) {
    $price_ht = (float)$price_ttc / 1.2;

    if ($display_ttc) {
        return wc_price($price_ht) . ' ' . __('HT', 'textdomain') . ' (' . wc_price($price_ttc) . ' ' . __('TTC', 'textdomain') . ')';
    }

    return wc_price($price_ht) . ' HT';
}

// Page boutique et produit
add_filter('woocommerce_get_price_html', 'custom_price_display_based_on_customer', 10, 2);
function custom_price_display_based_on_customer($price, $product) {
    if (is_user_logged_in()) {
        if (is_professional()) {
            $price_ttc = $product->get_price();
            $price = display_ht_price($price_ttc);
        }
    }

    return $price;
}

// Page panier - prix unitaire
add_filter('woocommerce_cart_item_price', 'display_ht_and_ttc_in_cart_for_pro', 10, 3);
function display_ht_and_ttc_in_cart_for_pro($price, $cart_item, $cart_item_key) {
    if (is_user_logged_in()) {
        if (is_professional()) {
            $product = $cart_item['data'];
            $price_ttc = $product->get_price();

            $price = display_ht_price($price_ttc, true);
        }
    }
    return $price;
}

// Sous-total du panier
add_filter('woocommerce_cart_subtotal', 'display_ht_and_ttc_cart_subtotal_for_pro', 10, 3);
function display_ht_and_ttc_cart_subtotal_for_pro($subtotal, $cart, $with_tax) {
    if (is_user_logged_in()) {
        if (is_professional()) {
            $subtotal_ttc = 0;

            // Calculer le sous-total avec la réduction
            foreach (WC()->cart->get_cart() as $cart_item) {
                $product = $cart_item['data'];
                $quantity = $cart_item['quantity'];
                $price_ttc = $product->get_price() * $quantity;
                $subtotal_ttc += $price_ttc;
            }

            $subtotal = display_ht_price($subtotal_ttc, true);
        }
    }

    return $subtotal;
}

// Total de la commande
add_filter('woocommerce_cart_total', 'display_ht_and_ttc_cart_total_for_pro', 10, 1);
function display_ht_and_ttc_cart_total_for_pro($total) {
    if (is_user_logged_in()) {
        if (is_professional()) {
            $total_ttc = 0;

            // Calculer le total avec la réduction
            foreach (WC()->cart->get_cart() as $cart_item) {
                $product = $cart_item['data'];
                $quantity = $cart_item['quantity'];
                $price_ttc = $product->get_price() * $quantity;
                $total_ttc += $price_ttc;
            }

            $total = display_ht_price($total_ttc, true);
        }
    }

    return $total;
}

// 30% de réduction pour les professionnels appliqué au prix régulier
add_filter('woocommerce_product_get_regular_price', 'apply_pro_price_discount', 10, 2);
function apply_pro_price_discount($price, $product) {
    if (is_user_logged_in() && is_professional()) {
        $price = $price * 0.7;
    }

    return $price;
}