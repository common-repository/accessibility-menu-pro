<?php

function ampro_get_populated_nav_menu_locations() {
  $locations = get_nav_menu_locations();
  // ampro_console_log("locations1:" . print_r($locations, true));
  $populatedMenus = [];

  foreach ($locations as $location_slug => $menu_id) {
      if ($menu_id > 0) { // Check if the menu ID is greater than 0 (meaning a menu is assigned)
          $menu = wp_get_nav_menu_object($menu_id);
          if ($menu) {
              $menu_items = wp_get_nav_menu_items($menu->term_id);
              if (!empty($menu_items)) {
                  // Directly use the location slug instead of the sanitized menu name
                  $populatedMenus[] = $location_slug;
              }
          }
      }
  }

  return $populatedMenus;
}

function ampro_get_formatted_menu_names_from_locations($menu_locations) {
  $formatted_names = [];

  foreach ($menu_locations as $location_slug) {
      // Get menu ID using the location slug
      $menu_id = get_nav_menu_locations()[$location_slug] ?? null;
      if ($menu_id) {
          // Get the menu object based on the menu ID
          $menu = wp_get_nav_menu_object($menu_id);
          if ($menu) {
              // Get the name of the menu, convert to lowercase, replace spaces and "-" with "_"
              $menu_name = strtolower($menu->name); // Convert name to lowercase
              $menu_name = str_replace(['-', ' '], '_', $menu_name); // Replace "-" and spaces with "_"
              $formatted_names[] = $menu_name;
          }
      }
  }

  return $formatted_names;
}

function ampro_clean_menu_names($menus) {
  // Check if the input is a string and handle it directly
  if (is_string($menus)) {
      return str_replace(['-', ' '], "_", strtolower($menus));
  }

  // Initialize the array to hold the cleaned menu names
  $cleaned_menus = [];

  // If the input is an array, process each element
  foreach ($menus as $menu) {
      $cleaned_menu = str_replace(['-', ' '], "_", strtolower($menu));
      $cleaned_menus[] = $cleaned_menu;
  }

  // Return the array of cleaned menu names
  return $cleaned_menus;
}

