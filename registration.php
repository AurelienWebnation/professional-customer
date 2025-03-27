<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action('elementor_pro/forms/new_record', function($record, $handler) {
    $form_name = $record->get_form_settings('form_name');
    if ('inscription_professionnel' !== $form_name) {
        return;
    }

    $raw_fields = $record->get('fields');
    $fields = [];
    foreach ($raw_fields as $id => $field) {
        $fields[$id] = $field['value'];
    }

    try {
        if (empty($fields['first_name'])) {
            throw new \Exception('Veuillez entrer un prénom valide.');
        }

        if (empty($fields['last_name'])) {
            throw new \Exception('Veuillez entrer un nom valide.');
        }

        if (empty($fields['email'])) {
            throw new \Exception('Veuillez entrer une adresse email valide.');
        }

        if (empty($fields['password'])) {
            throw new \Exception('Veuillez entrer un mot de passe.');
        }

        if (empty($fields['company_name'])) {
            throw new \Exception('Veuillez entrer le nom de votre société.');
        }

        $first_name = sanitize_text_field($fields['first_name']);
        $last_name = sanitize_text_field($fields['last_name']);
        $email = sanitize_email($fields['email']);
        $password = sanitize_text_field($fields['password']);
        $company_name = sanitize_text_field($fields['company_name']);
        $username = $email;

        if (!is_email($email)) {
            throw new \Exception('L’adresse email saisie n’est pas valide.');
        }

        if (email_exists($email)) {
            throw new \Exception('Merci d\'utiliser un autre E-mail.');
        }

        $new_user = wp_insert_user([
            'user_pass'  => $password,
            'user_login' => $username,
            'user_email' => $email,
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'role'       => 'customer' // ou autre rôle selon tes besoins
        ]);

        if (is_wp_error($new_user)) {
            throw new \Exception('Une erreur est survenue lors de la création du compte.');
        }

        update_user_meta($new_user, 'customer_type', 'professionnel');
        update_user_meta($new_user, 'company_name', $company_name);

        wp_new_user_notification($new_user, null, 'user');

        // Connexion automatique
		wp_set_current_user($new_user, $username);
		wp_set_auth_cookie($new_user);
		do_action('wp_login', $username, get_userdata($new_user));
    } catch (\Exception $e) {
        $handler->add_error_message($e->getMessage());
        $handler->is_success = false;
    }
}, 10, 2);