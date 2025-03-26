<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Applique réellement le prix discount pour les professionnels
add_action('woocommerce_cart_calculate_fees', 'apply_professional_discount', 20, 1);
function apply_professional_discount($cart) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return; // Ne pas exécuter dans l'admin
    }

    if (is_user_logged_in() && function_exists('is_professional') && is_professional()) {
        $subtotal_ttc = $cart->subtotal;
        $discount_ttc = $subtotal_ttc * 0.3;
        $discount_ht = $discount_ttc / 1.2;

        $cart->add_fee(__('Réduction Professionnelle', 'woocommerce'), -$discount_ht, false);
    }
}
