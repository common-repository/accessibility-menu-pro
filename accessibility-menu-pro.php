<?php
/**
 * Plugin Name: Accessibility Menu Pro
 * Description: Accessibility Menu Pro
 * Version: 1.3.0
 * Author: Qi Machine LLC
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
  exit;
}

require("admin-navigation.php");
require("utils.php");

register_uninstall_hook(__FILE__, 'ampro_uninstall');

function ampro_uninstall() {
  require plugin_dir_path(__FILE__) . 'uninstall.php';
}

function ampro_console_log($data) {
  // Encode the data to JSON format safely
  $json_data = wp_json_encode($data);
  
  // Add inline script to log data to the console
  $script = "console.log($json_data);";
  wp_add_inline_script('jquery', $script);
}

function ampro_add_assets() {
  $primary_menu_name = get_option('ampro_primary', 'error');
  $primary_menu_name = strtolower(str_replace(['-', ' '], '_', $primary_menu_name));

  // ampro_console_log("foo: " . print_r($primary_menu_name, true));

  $populated_menus = ampro_get_populated_nav_menu_locations();
  $menu_names = ampro_get_formatted_menu_names_from_locations($populated_menus);
  
  $menu_options = array();
  foreach ($menu_names as $menu_name) {
    $menu_options[$menu_name] = array(
      'breakpoint_width' => get_option("ampro_breakpoint_width_" . $menu_name, 1200),
      'submenu_element_width' => get_option("ampro_submenu_element_width_" . $menu_name, 200),

      'parent_background_color' => get_option("ampro_parent_background_color_" . $menu_name, "#ffffff"),
      'parent_hover_background_color' => get_option("ampro_parent_hover_background_color_" . $menu_name, "#DEDDFF"),
      'submenu_background_color' => get_option("ampro_submenu_background_color_" . $menu_name, "#DEDDFF"),
      'submenu_hover_background_color' => get_option("ampro_submenu_hover_background_color_" . $menu_name, "#B3DEFF"),

      'hover_mode' => get_option("hover_mode_" . $menu_name, false),
      'parent_text_color' => get_option("ampro_parent_text_color_" . $menu_name, "#000000"),
      'parent_hover_text_color' => get_option("ampro_parent_hover_text_color_" . $menu_name, "#000000"),
      'parent_font_size' => get_option("ampro_parent_font_size_" . $menu_name, '16'),
      'parent_font' => get_option("ampro_parent_font_" . $menu_name, 'Arial'),
      'parent_font_style' => get_option("ampro_parent_font_style_" . $menu_name, 'Regular'),
      'submenu_text_color' => get_option("ampro_submenu_text_color_" . $menu_name, '#000000'),
      'submenu_hover_text_color' => get_option("ampro_submenu_hover_text_color_" . $menu_name, '#000000'),
      'submenu_font_size' => get_option("ampro_submenu_font_size_" . $menu_name, '12'),
      'submenu_font' => get_option("ampro_submenu_font_" . $menu_name, 'Arial'),
      'submenu_font_style' => get_option("ampro_submenu_font_style_" . $menu_name, 'Regular'),

      'parent_text_transform' => get_option("ampro_parent_text_transform_" . $menu_name, 'none'),
      'submenu_text_transform' => get_option("ampro_submenu_text_transform_" . $menu_name, 'none'),

      'parent_link_padding_top' => get_option("ampro_parent_link_padding_top_" . $menu_name, 10),
      'parent_link_padding_right' => get_option("ampro_parent_link_padding_right_" . $menu_name, 10),
      'parent_link_padding_bottom' => get_option("ampro_parent_link_padding_bottom_" . $menu_name, 10),
      'parent_link_padding_left' => get_option("ampro_parent_link_padding_left_" . $menu_name, 10),

      'submenu_link_padding_top' => get_option("ampro_submenu_link_padding_top_" . $menu_name, 10),
      'submenu_link_padding_right' => get_option("ampro_submenu_link_padding_right_" . $menu_name, 10),
      'submenu_link_padding_bottom' => get_option("ampro_submenu_link_padding_bottom_" . $menu_name, 10),
      'submenu_link_padding_left' => get_option("ampro_submenu_link_padding_left_" . $menu_name, 10),

      'justify_content' => get_option("ampro_justify_content_" . $menu_name, 'center'),
      'submenu_mode' => get_option("ampro_submenu_mode_" . $menu_name, 'arrow'),

      'mobile_menu_button_background_color' => get_option("ampro_mobile_menu_button_background_color_" . $menu_name, '#ffffff'),
      'mobile_menu_overlay_background_color' => get_option("ampro_mobile_menu_overlay_background_color_" . $menu_name, '#008080'),
      'mobile_menu_button_font' => get_option("ampro_mobile_menu_button_font_" . $menu_name, 'Arial'),
      'mobile_menu_button_font_style' => get_option("ampro_mobile_menu_button_font_style_" . $menu_name, 'Regular'),
      'mobile_menu_button_font_size' => get_option("ampro_mobile_menu_button_font_size_" . $menu_name, 16),
      'mobile_menu_button_text_color' => get_option("ampro_mobile_menu_button_text_color_" . $menu_name, '#000000')
    );
  }

  $translation_array = array(
    'primary_menu_name' => $primary_menu_name,
    'base_class_names' => $menu_names,
    'menu_options' => $menu_options,
    'base_url' => site_url()
  );
  wp_enqueue_script('accessibility-menu-pro-script', plugin_dir_url(__FILE__) . 'accessibility-menu-pro-script.js', array(), "1.0.0", true);
  wp_localize_script('accessibility-menu-pro-script', 'tarray', $translation_array);
}
add_action('wp_enqueue_scripts', 'ampro_add_assets', 20);


function ampro_generate_nav_menu_html($menu_items, $parent_id = 0, $is_sub = false, $parent_name = '', $tier = 0, $base_class_name = "m1") {
  $primary_menu_name = get_option('ampro_primary', 'error');
  $primary_menu_name = strtolower(str_replace(['-', ' '], '_', $primary_menu_name));
  $is_primary_menu = $base_class_name == $primary_menu_name ? true : false;

  if ($parent_id === 0 && !$is_sub) {
    if ($is_primary_menu) {
      $html = '<div id="' . $base_class_name . '_accessibility_menu_pro_nav_wrapper" class="' . $base_class_name . '_accessibility_menu_pro_nav_wrapper_hidden_on_mobile"><nav id="' . $base_class_name . '_accessibility_menu_pro_nav"><div class="' . $base_class_name . '_accessibility_menu_pro_wrapper_inner">';
    } else {
      $html = '<div id="' . $base_class_name . '_accessibility_menu_pro_nav_wrapper"><nav id="' . $base_class_name . '_accessibility_menu_pro_nav"><div class="' . $base_class_name . '_accessibility_menu_pro_wrapper_inner">';
    }
  } else {
      $html = '';
  }

  $children = array_filter($menu_items, function($item) use ($parent_id) {
      return $item->menu_item_parent == $parent_id;
  });

  if (empty($children)) {
      if ($parent_id === 0 && !$is_sub) {
          $html .= '</div></nav></div>';
          // $html .= '</div>';
      }
      return $html;
  }

  if ($parent_id === 0 && !$is_sub) {
      $html .= '<ul role="menubar" id="' . $base_class_name . '_accessibility_menu_pro_ul">';

  } else {
      $sanitized_parent_name = strtolower(str_replace(' ', '', $parent_name));
      // The tier classes start being applied from the grandchildren level.
      $ul_class = $tier > 1 ? $base_class_name . "_accessibility_menu_pro_sub_menu sub_menu_tier_" . ($tier - 1) : $base_class_name . "_accessibility_menu_pro_sub_menu";

      $ul_attributes = " role=\"menu\" class=\"$sanitized_parent_name $ul_class\" aria-label=\"Show " . esc_attr($parent_name) . " submenu\"";
      $html .= "<ul$ul_attributes>";
  }

  foreach ($children as $item) {
      $has_sub_menu = !empty(array_filter($menu_items, function($sub_item) use ($item) {
          return $sub_item->menu_item_parent == $item->ID;
      }));

      $li_class = $base_class_name . "_accessibility_menu_pro_list_item" . ($has_sub_menu ? " " . $base_class_name . "_accessibility_menu_pro_has_sub_menu" : "");
      // Apply "parent" class only to top-level items that have submenus.
      $a_class = $parent_id === 0 ? ' class="parent"' : '';
      $a_attributes = ' role="menuitem" title="Link to ' . esc_attr($item->title) . '"' . $a_class;

      $html .= "<li role=\"none\" class=\"$li_class\"><a$a_attributes href=\"" . esc_url($item->url) . "\">" . esc_html($item->title) . "</a>";

      if ($has_sub_menu) {
          $html .= '<button class="' . $base_class_name . '_accessibility_menu_pro_dd_button" role="button">&#9662;</button>';
          // Adjust the tier right before making a recursive call
          $child_html = ampro_generate_nav_menu_html($menu_items, $item->ID, true, $item->title, $tier + 1, $base_class_name);
          if (!empty($child_html)) {
              $html .= $child_html;
          }
      }

      $html .= '</li>';
  }
  $html .= '</ul>';

  if ($parent_id === 0 && !$is_sub) {
      $html .= '</div></nav>';
  }

  return $html;
}

add_filter('wp_nav_menu', 'ampro_replace_menu_html', 10, 2);
function ampro_replace_menu_html($nav, $args) {
  // Get all locations with their assigned menu IDs
  $locations = get_nav_menu_locations();

  // Check if the current menu's theme location is populated
  if (!empty($args->theme_location) && isset($locations[$args->theme_location])) {
      $menu_id = $locations[$args->theme_location];
      $menu = wp_get_nav_menu_object($menu_id);

      if ($menu) {
          $menu_name = $menu->name;
          $base_class_name = strtolower(str_replace(['-', ' '], '_', $menu_name));
          $menu_items = wp_get_nav_menu_items($menu->term_id);

          // Check if menu items are available
          if (!empty($menu_items)) {
              return ampro_generate_nav_menu_html($menu_items, 0, false, '', 0, $base_class_name);  // Return custom nav HTML
          } else {
              ampro_console_log("No menu items found for menu: " . $menu_name);
          }
      } else {
          ampro_console_log("No menu found with ID: " . $menu_id);
      }
  }

  // Return default nav if no specific conditions are met
  return $nav;
}

function ampro_set_activation_message() {
  if (isset($_GET['plugin']) && $_GET['plugin'] == plugin_basename(__FILE__)) {
      set_transient('ampro_activation_notice', true, 5);
  }
}
add_action('activated_plugin', 'ampro_set_activation_message');

function ampro_show_activation_message() {
  if (get_transient('ampro_activation_notice')) {
      echo '<div class="updated notice is-dismissible"><p>';
      echo 'Plugin activated. <a href="' . esc_url(admin_url('options-general.php?page=accessibility-menu-pro-settings')) . '">Go to Settings</a> to customize your menu.';
      echo '</p></div>';
      delete_transient('ampro_activation_notice');
  }
}
add_action('admin_notices', 'ampro_show_activation_message');
