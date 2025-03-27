<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('admin_menu', 'reduction_pro_add_admin_menu');
function reduction_pro_add_admin_menu() {
    add_menu_page(
        'Réduction Pro',                       // Titre de la page
        'Réduction Pro',                       // Titre du menu
        'manage_options',                      // Capability
        'reduction_pro',                       // Slug
        'reduction_pro_options_page',          // Callback
        'dashicons-money-alt',                 // Icône (voir note ci-dessous)
        56                                     // Position dans le menu
    );
}

add_action('admin_init', 'reduction_pro_settings_init');
function reduction_pro_settings_init() {
    register_setting('reductionProSettings', 'reduction_pro_percentage');

    add_settings_section(
        'reduction_pro_section',
        'Paramètres de la réduction Pro',
        null,
        'reduction_pro'
    );

    add_settings_field(
        'reduction_pro_percentage_field',
        'Pourcentage de réduction',
        'reduction_pro_percentage_render',
        'reduction_pro',
        'reduction_pro_section'
    );
}

function reduction_pro_percentage_render() {
    $value = esc_attr(get_option('reduction_pro_percentage', 30));
    echo "<input type='number' name='reduction_pro_percentage' value='{$value}' min='0' max='100' /> %";
}

function reduction_pro_options_page() {
    echo '<div class="wrap">';
    echo '<h1>Réduction pour les pros</h1>';
    echo '<form action="options.php" method="post">';

    settings_fields('reductionProSettings');
    do_settings_sections('reduction_pro');
    submit_button();

    echo '</form>';
    echo '</div>';
}


