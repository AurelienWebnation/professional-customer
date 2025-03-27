<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Page Comptes Wordpress
add_filter('manage_users_columns', function($columns) {
    $columns['customer_type'] = 'Type de client'; // Nom de la colonne
    return $columns;
});

add_action('manage_users_custom_column', function($value, $column_name, $user_id) {
    if ($column_name === 'customer_type') {
        $customer_type = get_user_meta($user_id, 'customer_type', true);

        return $customer_type ? esc_html($customer_type) : 'Particulier';
    }
    return $value;
}, 10, 3);

// Page Commandes WooCommerce
add_filter('manage_edit-shop_order_columns', function($columns) {
    $columns['customer_type'] = 'Type de client';
    return $columns;
}, 20);

add_action('manage_shop_order_posts_custom_column', function($column, $post_id) {
    if ($column === 'customer_type') {
        $user_id = get_post_meta($post_id, '_customer_user', true);

        if ($user_id) {
            $customer_type = get_user_meta($user_id, 'customer_type', true);

            echo $customer_type ? esc_html($customer_type) : 'Particulier';
        } else {
            echo '-';
        }
    }
}, 10, 2);