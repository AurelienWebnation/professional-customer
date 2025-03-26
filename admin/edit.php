<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Ajouter un champ "Type de client" sur la page d'édition du profil utilisateur
add_action('edit_user_profile', 'customer_type_field');
function customer_type_field($user) {
    $customer_type = get_user_meta($user->ID, 'customer_type', true);
    ?>
    <h3>Informations client</h3>
    <table class="form-table">
        <tr>
            <th><label for="customer_type">Type de client</label></th>
            <td>
                <select name="customer_type" id="customer_type">
                    <option value="Particulier" <?php selected($customer_type, 'Particulier'); ?>>Particulier</option>
                    <option value="Professionnel" <?php selected($customer_type, 'Professionnel'); ?>>Professionnel</option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

// Sauvegarder la valeur du champ "Type de client"
add_action('edit_user_profile_update', 'save_customer_type_field');
function save_customer_type_field($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['customer_type'])) {
        update_user_meta($user_id, 'customer_type', sanitize_text_field($_POST['customer_type']));
    }
}
