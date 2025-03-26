<?php
/**
 * Plugin Name: Professional Customer
 * Description: Un plugin personnalisé pour gérer les clients professionnels.
 * Version: 1.0.0
 * Author: Aurélien Cabirol
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('PROF_CUSTOMER_PLUGIN_PATH', plugin_dir_path(__FILE__));

defined('PROF_CUSTOMER_URL') or define('PROF_CUSTOMER_URL', plugin_dir_url(__FILE__));

require_once PROF_CUSTOMER_PLUGIN_PATH . 'helpers.php';
require_once PROF_CUSTOMER_PLUGIN_PATH . 'registration.php';
require_once PROF_CUSTOMER_PLUGIN_PATH . 'ht-prices.php';
require_once PROF_CUSTOMER_PLUGIN_PATH . 'discount.php';
require_once PROF_CUSTOMER_PLUGIN_PATH . 'admin/edit.php';
require_once PROF_CUSTOMER_PLUGIN_PATH . 'admin/display.php';