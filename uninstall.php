<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Include any necessary files
require_once plugin_dir_path(__FILE__) . 'utils.php';

// Delete plugin options
delete_option('ampro_primary');

$options = array(
    'breakpoint_width_', 'submenu_element_width_',
    'parent_background_color_', 'parent_hover_background_color_',
    'submenu_background_color_', 'submenu_hover_background_color_',
    'hover_mode_', 'parent_text_color_', 'parent_font_size_',
    'parent_font_', 'parent_font_style_', 'submenu_text_color_',
    'submenu_font_size_', 'submenu_font_', 'submenu_font_style_',
    'parent_link_padding_top_', 'parent_link_padding_right_',
    'parent_link_padding_bottom_', 'parent_link_padding_left_',
    'submenu_link_padding_top_', 'submenu_link_padding_right_',
    'submenu_link_padding_bottom_', 'submenu_link_padding_left_',
    'justify_content_', 'submenu_mode_', 'mobile_menu_button_background_color_',
    'mobile_menu_overlay_background_color_', 'mobile_menu_button_font_',
    'mobile_menu_button_font_style_', 'mobile_menu_button_font_size_',
    'mobile_menu_button_text_color_'
);

// Get all menu names to delete their specific options
$populated_menus = ampro_get_populated_nav_menu_locations();
$menu_names = ampro_get_formatted_menu_names_from_locations($populated_menus);

// Delete all options for each menu
foreach ($menu_names as $menu_name) {
    foreach ($options as $option) {
        delete_option($option . $menu_name);
    }
}
