<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function is_professional() {
	if (!is_user_logged_in()) {
		return false;
	}

	$user_id = get_current_user_id();
	$customer_type = get_user_meta($user_id, 'customer_type', true);

	return strtolower($customer_type) === 'professionnel';
}