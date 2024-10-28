<?php

if (!defined('ABSPATH')) {
  exit;
}

function ampro_settings_page() {
    // Sanitize the 'tab' parameter from the URL
    $active_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'general';

    echo '<h1>Accessibility Menu Pro Settings</h1>';
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="?page=accessibility-menu-pro-settings&tab=general" class="nav-tab ' . ($active_tab == 'general' ? 'nav-tab-active' : '') . '">General</a>';
    echo '<a href="?page=accessibility-menu-pro-settings&tab=appearance" class="nav-tab ' . ($active_tab == 'appearance' ? 'nav-tab-active' : '') . '">Appearance & Style</a>';
    // echo '<a href="?page=accessibility-menu-pro-settings&tab=premium" class="nav-tab ' . ($active_tab == 'premium' ? 'nav-tab-active' : '') . '">Premium</a>';
    echo '</h2>';

    // Validate the tab value to ensure it matches one of the expected values
    switch ($active_tab) {
        case 'general':
            include(plugin_dir_path(__FILE__) . 'admin/general-settings.php');
            break;
        case 'appearance':
            include(plugin_dir_path(__FILE__) . 'admin/appearance-style-settings.php');
            break;
        case 'premium':
            include(plugin_dir_path(__FILE__) . 'admin/premium-settings.php');
            break;
        default:
            // Default to the 'general' tab if the tab value is not recognized
            include(plugin_dir_path(__FILE__) . 'admin/general-settings.php');
            break;
    }
}

// Hook the settings page into the admin menu
add_action('admin_menu', function() {
    add_options_page(
        'Accessibility Menu Pro Settings',
        'Accessibility Menu Pro',
        'manage_options',
        'accessibility-menu-pro-settings',
        'ampro_settings_page'
    );
});

// Enqueue the admin styles and scripts
function ampro_enqueue_admin_scripts($hook) {
    // Only load on specific admin pages
    if ($hook !== 'settings_page_accessibility-menu-pro-settings') {
        return;
    }

    wp_enqueue_style('accessibility-menu-pro-admin-styles', plugin_dir_url(__FILE__) . 'admin/admin-style.css', array(), "1.0.0");
    wp_enqueue_script('accessibility-menu-pro-admin-script', plugin_dir_url(__FILE__) . 'admin/admin-script.js', array('jquery'), "1.0.0", true);
}
add_action('admin_enqueue_scripts', 'ampro_enqueue_admin_scripts');

// for loading javascript from sub-pages
function accessibilityMenuProLoadCustomJs($custom_js) {
  wp_add_inline_script('accessibility-menu-pro-admin-script', $custom_js);
}
