<?php
/*
Plugin Name: WooCommerce Booking System
Plugin URI: https://example.com
Description: Advanced booking system for WooCommerce with employee selection, services and extra options.
Version: 1.0.0
Author: Your Name
Author URI: https://example.com
Text Domain: wc-booking
Domain Path: /languages
Requires at least: 5.6
Requires PHP: 7.2
WC requires at least: 5.0
WC tested up to: 7.0
*/

defined('ABSPATH') || exit;

// Define plugin constants
define('WC_BOOKING_PLUGIN_FILE', __FILE__);
define('WC_BOOKING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WC_BOOKING_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WC_BOOKING_VERSION', '1.0.0');

// Check if WooCommerce is active
if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    add_action('admin_notices', function() {
        echo '<div class="error"><p>';
        _e('WooCommerce Booking System requires WooCommerce to be installed and active!', 'wc-booking');
        echo '</p></div>';
    });
    return;
}

// Autoload classes
spl_autoload_register(function($class) {
    $prefix = 'WC_Booking_';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = WC_BOOKING_PLUGIN_DIR . 'includes/class-wc-booking-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Initialize the plugin
add_action('plugins_loaded', function() {
    // Load text domain
    load_plugin_textdomain('wc-booking', false, dirname(plugin_basename(__FILE__)) . '/languages');
    
    // Initialize components
    WC_Booking_Post_Types::init();
    WC_Booking_Admin::init();
    WC_Booking_Frontend::init();
    WC_Booking_Cart::init();
    WC_Booking_Order::init();
    WC_Booking_Employee::init();
    WC_Booking_Service::init();
    WC_Booking_Extra_Options::init();
    
    // Add settings link
    add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
        $settings_link = '<a href="' . admin_url('admin.php?page=wc-settings&tab=booking') . '">' . __('Settings', 'wc-booking') . '</a>';
        array_unshift($links, $settings_link);
        return $links;
    });
});

// Activation and deactivation hooks
register_activation_hook(__FILE__, ['WC_Booking_Post_Types', 'register_post_types']);
register_activation_hook(__FILE__, ['WC_Booking_Post_Types', 'register_taxonomies']);
register_activation_hook(__FILE__, 'wc_booking_flush_rewrite_rules');

function wc_booking_flush_rewrite_rules() {
    WC_Booking_Post_Types::register_post_types();
    WC_Booking_Post_Types::register_taxonomies();
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'wc_booking_deactivate');

function wc_booking_deactivate() {
    flush_rewrite_rules();
}
