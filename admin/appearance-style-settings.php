<?php

if (!defined('ABSPATH')) {
    exit;
}

// Check user capabilities
if (!current_user_can('manage_options')) {
    return;
}

// No need to enqueue scripts/styles here as it's done in admin-navigation.php

$menus = array_reverse(wp_get_nav_menus());
$locations = array_reverse(get_nav_menu_locations());

$menu_location_pairs = [];
foreach ($menus as $menu) {
    $location_names = array_keys($locations, $menu->term_id);
    $location_descriptions = [];
    foreach ($location_names as $location_name) {
        $location_descriptions[] = $location_name;
    }
    $location_string = implode(', ', $location_descriptions);
    $menu_location_pairs[$menu->name] = $menu->name . (empty($location_string) ? '' : " (Location: $location_string)");
}

$settings_updated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($menus as $menu) {
        $menu_slug = sanitize_title($menu->name);
        $menu_name = ampro_clean_menu_names($menu_slug);

        if (isset($_POST['bame1-settings-nonce-' . $menu_slug]) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bame1-settings-nonce-' . $menu_slug])), 'bame1-settings-save')) {
            if (isset($_POST['ampro_breakpoint_width_' . $menu_name])) {
              update_option('ampro_breakpoint_width_' . $menu_name, intval($_POST['ampro_breakpoint_width_' . $menu_name]));
            }

            if (isset($_POST['ampro_submenu_element_width_' . $menu_name])) {
              update_option('ampro_submenu_element_width_' . $menu_name, intval($_POST['ampro_submenu_element_width_' . $menu_name]));
            }
            
            if (isset($_POST['ampro_parent_background_color_transparent_checkbox_' . $menu_name])) {
                update_option('ampro_parent_background_color_' . $menu_name, 'transparent');
            } else {
                update_option('ampro_parent_background_color_' . $menu_name, sanitize_hex_color($_POST['ampro_parent_background_color_' . $menu_name]));
            }

            update_option('ampro_parent_hover_background_color_' . $menu_name, sanitize_hex_color($_POST['ampro_parent_hover_background_color_' . $menu_name]));
            update_option('ampro_submenu_background_color_' . $menu_name, sanitize_hex_color($_POST['ampro_submenu_background_color_' . $menu_name]));
            update_option('ampro_submenu_hover_background_color_' . $menu_name, sanitize_hex_color($_POST['ampro_submenu_hover_background_color_' . $menu_name]));

            update_option('ampro_parent_text_color_' . $menu_name, sanitize_hex_color($_POST['ampro_parent_text_color_' . $menu_name]));
            update_option('ampro_parent_hover_text_color_' . $menu_name, sanitize_hex_color($_POST['ampro_parent_hover_text_color_' . $menu_name]));
            update_option('ampro_submenu_text_color_' . $menu_name, sanitize_hex_color($_POST['ampro_submenu_text_color_' . $menu_name]));
            update_option('ampro_submenu_hover_text_color_' . $menu_name, sanitize_hex_color($_POST['ampro_submenu_hover_text_color_' . $menu_name]));
            update_option('ampro_parent_font_size_' . $menu_name, intval($_POST['ampro_parent_font_size_' . $menu_name]));
            update_option('ampro_submenu_font_size_' . $menu_name, intval($_POST['ampro_submenu_font_size_' . $menu_name]));

            update_option('ampro_parent_font_' . $menu_name, sanitize_text_field($_POST['ampro_parent_font_' . $menu_name]));
            update_option('ampro_parent_font_style_' . $menu_name, sanitize_text_field($_POST['ampro_parent_font_style_' . $menu_name]));
            update_option('ampro_submenu_font_' . $menu_name, sanitize_text_field($_POST['ampro_submenu_font_' . $menu_name]));
            update_option('ampro_submenu_font_style_' . $menu_name, sanitize_text_field($_POST['ampro_submenu_font_style_' . $menu_name]));

            update_option('ampro_parent_text_transform_' . $menu_name, sanitize_text_field($_POST['ampro_parent_text_transform_' . $menu_name]));
            update_option('ampro_submenu_text_transform_' . $menu_name, sanitize_text_field($_POST['ampro_submenu_text_transform_' . $menu_name]));


            $parent_link_padding_top_option_name = "ampro_parent_link_padding_top_" . $menu_name;
            $parent_link_padding_right_option_name = "ampro_parent_link_padding_right_" . $menu_name;
            $parent_link_padding_bottom_option_name = "ampro_parent_link_padding_bottom_" . $menu_name;
            $parent_link_padding_left_option_name = "ampro_parent_link_padding_left_" . $menu_name;

            update_option($parent_link_padding_top_option_name, intval($_POST[$parent_link_padding_top_option_name]));
            update_option($parent_link_padding_right_option_name, intval($_POST[$parent_link_padding_right_option_name]));
            update_option($parent_link_padding_bottom_option_name, intval($_POST[$parent_link_padding_bottom_option_name]));
            update_option($parent_link_padding_left_option_name, intval($_POST[$parent_link_padding_left_option_name]));

            $submenu_link_padding_top_option_name = "ampro_submenu_link_padding_top_" . $menu_name;
            $submenu_link_padding_right_option_name = "ampro_submenu_link_padding_right_" . $menu_name;
            $submenu_link_padding_bottom_option_name = "ampro_submenu_link_padding_bottom_" . $menu_name;
            $submenu_link_padding_left_option_name = "ampro_submenu_link_padding_left_" . $menu_name;

            update_option($submenu_link_padding_top_option_name, intval($_POST[$submenu_link_padding_top_option_name]));
            update_option($submenu_link_padding_right_option_name, intval($_POST[$submenu_link_padding_right_option_name]));
            update_option($submenu_link_padding_bottom_option_name, intval($_POST[$submenu_link_padding_bottom_option_name]));
            update_option($submenu_link_padding_left_option_name, intval($_POST[$submenu_link_padding_left_option_name]));

            if (isset($_POST['ampro_justify_content_' . $menu_name])) {
              update_option('ampro_justify_content_' . $menu_name, sanitize_text_field($_POST['ampro_justify_content_' . $menu_name]));
            }
            
            update_option('ampro_submenu_mode_' . $menu_name, sanitize_text_field($_POST['ampro_submenu_mode_' . $menu_name]));

            if (isset($_POST['ampro_mobile_menu_button_background_color_' . $menu_name])) {
              update_option('ampro_mobile_menu_button_background_color_' . $menu_name, sanitize_hex_color($_POST['ampro_mobile_menu_button_background_color_' . $menu_name]));
            }
            if (isset($_POST['ampro_mobile_menu_overlay_background_color_' . $menu_name])) {
              update_option('ampro_mobile_menu_overlay_background_color_' . $menu_name, sanitize_hex_color($_POST['ampro_mobile_menu_overlay_background_color_' . $menu_name]));
            }
            if (isset($_POST['ampro_mobile_menu_button_font_' . $menu_name])) {
              update_option('ampro_mobile_menu_button_font_' . $menu_name, sanitize_text_field($_POST['ampro_mobile_menu_button_font_' . $menu_name]));
            }
            if (isset($_POST['ampro_mobile_menu_button_font_style_' . $menu_name])) {
              update_option('ampro_mobile_menu_button_font_style_' . $menu_name, sanitize_text_field($_POST['ampro_mobile_menu_button_font_style_' . $menu_name]));
            }
            if (isset($_POST['ampro_mobile_menu_button_font_size_' . $menu_name])) {
              update_option('ampro_mobile_menu_button_font_size_' . $menu_name, intval($_POST['ampro_mobile_menu_button_font_size_' . $menu_name]));
            }
            if (isset($_POST['ampro_mobile_menu_button_text_color_' . $menu_name])) {
              update_option('ampro_mobile_menu_button_text_color_' . $menu_name, sanitize_hex_color($_POST['ampro_mobile_menu_button_text_color_' . $menu_name]));
            }
                 
            $settings_updated = true;
        }
    }

    // Display the settings updated message if any settings were changed
    if ($settings_updated) {
        echo '<div class="updated" id="settings-updated"><p>Settings updated.</p></div>';
    }
}

if (empty(get_option('ampro_primary'))) {
    echo '<div class="notice notice-error is-dismissible">
            <p>
              <strong>Warning:</strong> No primary menu is set. The primary menu is usually at the top of your website and is displayed when the Menu button is tapped on mobile devices. Go to the <a href="?page=accessibility-menu-pro-settings&tab=general">General</a> tab to set.
            </p>
          </div>';
}

$active_menu_tab = isset($_POST['active_menu_tab']) ? sanitize_text_field(wp_unslash($_POST['active_menu_tab'])) : ampro_clean_menu_names(array_keys($menu_location_pairs)[0]);

?>
<div id="appearance-style-settings" class="wrap">
    <h2>Appearance & Style</h2>

    <?php
    echo '<div class="wrap">';
    echo '<div style="display: flex;">';
    echo '<div id="bame1-nav" style="width: 20%; padding-right: 20px;">';
    echo '<p>Choose the menu you would like to modify:</p>';
    echo '<ul>';

    foreach ($menu_location_pairs as $menu_name => $menu_description) {
        $a = $active_menu_tab === ampro_clean_menu_names($menu_name) ? "bame1-active" : "";
        echo '<li><a href="#' . esc_attr(ampro_clean_menu_names($menu_name)) . '" class="bame1-tab ' . esc_attr($a) . '">' . esc_html($menu_description) . '</a></li>';
    }

    echo '</ul>';
    echo '</div>';
    echo '<div id="bame1-content" style="flex-grow: 1;">';

    $primary_menu_name = get_option('ampro_primary');
    foreach ($menus as $menu) {
        $menu_slug = sanitize_title($menu->name);
        $menu_name = ampro_clean_menu_names($menu_slug);
        $menu_description = $menu_location_pairs[$menu->name];

        $a = $active_menu_tab === ampro_clean_menu_names($menu_name) ? 'block' : 'none';

        echo '<div id="' . esc_attr($menu_name) . '" class="tab-content" style="display: ' . esc_attr($a) . ';">';
        echo '<h3>Menu: ' . esc_html($menu_description) . '</h3>';
        echo '<form method="post" action="">';

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo wp_nonce_field('bame1-settings-save', 'bame1-settings-nonce-' . $menu_slug);

        echo '<input type="hidden" class="active_menu_tab_input" name="active_menu_tab" value="' . esc_attr($menu_name) . '">';
        echo '<table class="form-table">';

        echo '<tr><td><h4>General</h4></td></tr>';

        if ($menu_name == ampro_clean_menu_names($primary_menu_name)) {
            $justify_content_option_name = "ampro_justify_content_" . $menu_name;
            $justify_content = get_option($justify_content_option_name, "center");

            echo '<tr>';
            echo '<th scope="row"><label for="' . esc_attr($justify_content_option_name) . '">Alignment</label></th>';
            echo '<td>';
            echo '<select id="' . esc_attr($justify_content_option_name) . '" name="' . esc_attr($justify_content_option_name) . '">';
            echo '<option value="flex-start"' . selected($justify_content, 'flex-start', false) . '>flex-start</option>';
            echo '<option value="center"' . selected($justify_content, 'center', false) . '>center</option>';
            echo '<option value="flex-end"' . selected($justify_content, 'flex-end', false) . '>flex-end</option>';
            echo '</select>';
            echo '</td>';
            echo '</tr>';

            $submenu_mode_option_name = "ampro_submenu_mode_" . $menu_name;
            $submenu_mode = get_option($submenu_mode_option_name, "On hover");

            echo '<tr>';
            echo '<th scope="row"><label for="' . esc_attr($submenu_mode_option_name) . '">Submenu appears</label></th>';
            echo '<td>';
            echo '<select id="' . esc_attr($submenu_mode_option_name) . '" name="' . esc_attr($submenu_mode_option_name) . '">';
            echo '<option value="hover"' . selected($submenu_mode, 'hover', false) . '>On hover</option>';
            echo '<option value="arrow"' . selected($submenu_mode, 'arrow', false) . '>On arrow click</option>';
            echo '</select>';
            echo '</td>';
            echo '</tr>';

            $submenu_element_width_option_name = "ampro_submenu_element_width_" . $menu_name;
            $submenu_element_width = get_option($submenu_element_width_option_name, 200);
            echo '<tr>';
            echo '<th scope="row"><label for="' . esc_attr($submenu_element_width_option_name) . '">Submenu element width (px)</label></th>';
            echo '<td>';
            echo '<input size="6" type="number" id="' . esc_attr($submenu_element_width_option_name) . '" name="' . esc_attr($submenu_element_width_option_name) . '" value="' . esc_attr($submenu_element_width) . '" min="1" step="1"> px';
            echo '</td>';
            echo '</tr>';

        } else {
            $submenu_mode_option_name = "ampro_submenu_mode_" . $menu_name;
            $submenu_mode = get_option($submenu_mode_option_name);

            echo '<tr>';
            echo '<th scope="row"><label for="' . esc_attr($submenu_mode_option_name) . '">Submenu appears</label></th>';
            echo '<td>';
            echo '<select id="' . esc_attr($submenu_mode_option_name) . '" name="' . esc_attr($submenu_mode_option_name) . '">';
            echo '<option value="always"' . selected($submenu_mode, 'always', false) . '>Always</option>';
            echo '<option value="arrow"' . selected($submenu_mode, 'arrow', false) . '>On arrow click</option>';
            echo '</select>';
            echo '</td>';
            echo '</tr>';
        }

        echo '<tr><td><h4>Parent elements</h4></td></tr>';

        $parent_background_color_option_name = "ampro_parent_background_color_" . $menu_name;
        $parent_background_color_transparent_option_name = "ampro_parent_background_color_transparent_checkbox_" . $menu_name;
        $parent_background_color = get_option($parent_background_color_option_name, '#ffffff');
        $is_transparent = ($parent_background_color === "transparent");

        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($parent_background_color_option_name) . '">Parent Background Color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($parent_background_color_option_name) . '" name="' . esc_attr($parent_background_color_option_name) . '" value="' . esc_attr($parent_background_color) . '" ' . ($is_transparent ? 'disabled' : '') . '>';
        echo '<label style="margin-left:10px;"><input type="checkbox" id="' . esc_attr($parent_background_color_transparent_option_name) .'" name="' . esc_attr($parent_background_color_transparent_option_name) .'" value="1" ' . ($is_transparent ? 'checked' : '') . '> Transparent</label>';
        echo '</td>';
        echo '</tr>';
       
        $parent_hover_background_color_option_name = "ampro_parent_hover_background_color_" . $menu_name;
        $parent_hover_background_color = get_option($parent_hover_background_color_option_name, '#DEDDFF');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($parent_hover_background_color_option_name) . '">Parent Hover Background Color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($parent_hover_background_color_option_name) . '" name="' . esc_attr($parent_hover_background_color_option_name) . '" value="' . esc_attr($parent_hover_background_color) . '">';
        echo '</td>';
        echo '</tr>';

        $fonts = ampro_get_plugin_fonts();
        ampro_add_default_system_fonts($fonts);
        $parent_font_option_name = "ampro_parent_font_" . $menu_name;
        $parent_font = get_option($parent_font_option_name, 'Arial');
        $parent_font_style_option_name = "ampro_parent_font_style_" . $menu_name;
        $parent_font_style = get_option($parent_font_style_option_name, 'regular');

        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($parent_font_option_name) . '">Parent font</label></th>';
        echo '<td>';
        echo '<select name="' . esc_attr($parent_font_option_name) . '" id="' . esc_attr($parent_font_option_name) . '">';
        foreach ($fonts as $font_name => $styles) {
            echo '<option value="' . esc_attr($font_name) . '" ' . selected($parent_font, $font_name) . '>';
            echo esc_html($font_name);
            echo '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($parent_font_style_option_name) . '">Parent font style</label></th>';
        echo '<td>';
        echo '<select name="' . esc_attr($parent_font_style_option_name) . '" id="' . esc_attr($parent_font_style_option_name) . '">';
        if (isset($fonts[$parent_font])) {
            foreach ($fonts[$parent_font] as $style) {
                echo '<option value="' . esc_attr($style) . '" ' . selected($parent_font_style, $style) . '>';
                echo esc_html($style);
                echo '</option>';
            }
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';

        $font_size_option_name = "ampro_parent_font_size_" . $menu_name;
        $font_size = get_option($font_size_option_name, '16');  // Default font size is 16px
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($font_size_option_name) . '">Font size (px)</label></th>';
        echo '<td>';
        echo '<input size="6" type="number" id="' . esc_attr($font_size_option_name) . '" name="' . esc_attr($font_size_option_name) . '" value="' . esc_attr($font_size) . '" min="1" step="1"> px';
        echo '</td>';
        echo '</tr>';

        $parent_text_transform_option_name = "ampro_parent_text_transform_" . $menu_name;
        $parent_text_transform = get_option($parent_text_transform_option_name, "none");

        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($parent_text_transform_option_name) . '">Parent text transform</label></th>';
        echo '<td>';
        echo '<select id="' . esc_attr($parent_text_transform_option_name) . '" name="' . esc_attr($parent_text_transform_option_name) . '">';
        echo '<option value="none"' . selected($parent_text_transform, "none", false) . '>None</option>';
        echo '<option value="capitalize"' . selected($parent_text_transform, "capitalize", false) . '>Capitalize</option>';
        echo '<option value="uppercase"' . selected($parent_text_transform, "uppercase", false) . '>Uppercase</option>';
        echo '<option value="lowercase"' . selected($parent_text_transform, "lowercase", false) . '>Lowercase</option>';
        echo '<option value="initial"' . selected($parent_text_transform, "initial", false) . '>Initial</option>';
        echo '<option value="inherit"' . selected($parent_text_transform, "inherit", false) . '>Inherit</option>';
        echo '</select>';
        echo '</td>';
        echo '</tr>';

        $parent_text_color_option_name = "ampro_parent_text_color_" . $menu_name;
        $parent_text_color = get_option($parent_text_color_option_name, '#000000');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($parent_text_color_option_name) . '">Parent text color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($parent_text_color_option_name) . '" name="' . esc_attr($parent_text_color_option_name) . '" value="' . esc_attr($parent_text_color) . '">';
        echo '</td>';
        echo '</tr>';

        $parent_text_hover_color_option_name = "ampro_parent_hover_text_color_" . $menu_name;
        $parent_text_hover_color = get_option($parent_text_hover_color_option_name, '#000000');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($parent_text_hover_color_option_name) . '">Parent hover text color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($parent_text_hover_color_option_name) . '" name="' . esc_attr($parent_text_hover_color_option_name) . '" value="' . esc_attr($parent_text_hover_color) . '">';
        echo '</td>';
        echo '</tr>';

        $parent_link_padding_top_option_name = "ampro_parent_link_padding_top_" . $menu_name;
        $parent_link_padding_right_option_name = "ampro_parent_link_padding_right_" . $menu_name;
        $parent_link_padding_bottom_option_name = "ampro_parent_link_padding_bottom_" . $menu_name;
        $parent_link_padding_left_option_name = "ampro_parent_link_padding_left_" . $menu_name;
        
        // Default padding value
        $default_padding = '15';
        
        echo '<tr>';
        echo '<th scope="row">Parent link padding (px)</th>';
        echo '<td>';
        
        // Padding Top
        $parent_link_padding_top_value = get_option($parent_link_padding_top_option_name, $default_padding);
        echo '<label for="' . esc_attr($parent_link_padding_top_option_name) . '">Top: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($parent_link_padding_top_option_name) . '" name="' . esc_attr($parent_link_padding_top_option_name) . '" value="' . esc_attr($parent_link_padding_top_value) . '" min="0" step="1"> px ';
        
        // Padding Right
        $parent_link_padding_right_value = get_option($parent_link_padding_right_option_name, $default_padding);
        echo '<label for="' . esc_attr($parent_link_padding_right_option_name) . '">Right: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($parent_link_padding_right_option_name) . '" name="' . esc_attr($parent_link_padding_right_option_name) . '" value="' . esc_attr($parent_link_padding_right_value) . '" min="0" step="1"> px ';
        
        // Padding Bottom
        $parent_link_padding_bottom_value = get_option($parent_link_padding_bottom_option_name, $default_padding);
        echo '<label for="' . esc_attr($parent_link_padding_bottom_option_name) . '">Bottom: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($parent_link_padding_bottom_option_name) . '" name="' . esc_attr($parent_link_padding_bottom_option_name) . '" value="' . esc_attr($parent_link_padding_bottom_value) . '" min="0" step="1"> px ';
        
        // Padding Left
        $parent_link_padding_left_value = get_option($parent_link_padding_left_option_name, $default_padding);
        echo '<label for="' . esc_attr($parent_link_padding_left_option_name) . '">Left: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($parent_link_padding_left_option_name) . '" name="' . esc_attr($parent_link_padding_left_option_name) . '" value="' . esc_attr($parent_link_padding_left_value) . '" min="0" step="1"> px ';
        
        echo '</td>';
        echo '</tr>';

        echo '<tr><td><h4>Submenu elements</h4></td></tr>';

        $submenu_background_color_option_name = "ampro_submenu_background_color_" . $menu_name;
        $submenu_background_color = get_option($submenu_background_color_option_name, '#DEDDFF');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_background_color_option_name) . '">Submenu Background Color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($submenu_background_color_option_name) . '" name="' . esc_attr($submenu_background_color_option_name) . '" value="' . esc_attr($submenu_background_color) . '">';
        echo '</td>';
        echo '</tr>';

        $submenu_hover_background_color_option_name = "ampro_submenu_hover_background_color_" . $menu_name;
        $submenu_hover_background_color = get_option($submenu_hover_background_color_option_name, '#B3DEFF');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_hover_background_color_option_name) . '">Submenu Hover Background Color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($submenu_hover_background_color_option_name) . '" name="' . esc_attr($submenu_hover_background_color_option_name) . '" value="' . esc_attr($submenu_hover_background_color) . '">';
        echo '</td>';
        echo '</tr>';

        $submenu_font_option_name = "ampro_submenu_font_" . $menu_name;
        $submenu_font = get_option($submenu_font_option_name, 'Arial');
        $submenu_font_style_option_name = "ampro_submenu_font_style_" . $menu_name;
        $submenu_font_style = get_option($submenu_font_style_option_name, 'regular');

        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_font_option_name) . '">Submenu font</label></th>';
        echo '<td>';
        echo '<select name="' . esc_attr($submenu_font_option_name) . '" id="' . esc_attr($submenu_font_option_name) . '">';
        foreach ($fonts as $font_name => $styles) {
            echo '<option value="' . esc_attr($font_name) . '" ' . selected($submenu_font, $font_name) . '>';
            echo esc_html($font_name);
            echo '</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_font_style_option_name) . '">Submenu Font Style</label></th>';
        echo '<td>';
        echo '<select name="' . esc_attr($submenu_font_style_option_name) . '" id="' . esc_attr($submenu_font_style_option_name) . '">';
        if (isset($fonts[$submenu_font])) {
            foreach ($fonts[$submenu_font] as $style) {
                echo '<option value="' . esc_attr($style) . '" ' . selected($submenu_font_style, $style) . '>';
                echo esc_html($style);
                echo '</option>';
            }
        }
        echo '</select>';
        echo '</td>';
        echo '</tr>';

        $submenu_font_size_option_name = "ampro_submenu_font_size_" . $menu_name;
        $submenu_font_size = get_option($submenu_font_size_option_name, '12');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_font_size_option_name) . '">Submenu font size (px)</label></th>';
        echo '<td>';
        echo '<input size="6" type="number" id="' . esc_attr($submenu_font_size_option_name) . '" name="' . esc_attr($submenu_font_size_option_name) . '" value="' . esc_attr($submenu_font_size) . '" min="1" step="1"> px';
        echo '</td>';
        echo '</tr>';



        $submenu_text_transform_option_name = "ampro_submenu_text_transform_" . $menu_name;
        $submenu_text_transform = get_option($submenu_text_transform_option_name, "none");

        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_text_transform_option_name) . '">Submenu text transform</label></th>';
        echo '<td>';
        echo '<select id="' . esc_attr($submenu_text_transform_option_name) . '" name="' . esc_attr($submenu_text_transform_option_name) . '">';
        echo '<option value="none"' . selected($submenu_text_transform, "none", false) . '>None</option>';
        echo '<option value="capitalize"' . selected($submenu_text_transform, "capitalize", false) . '>Capitalize</option>';
        echo '<option value="uppercase"' . selected($submenu_text_transform, "uppercase", false) . '>Uppercase</option>';
        echo '<option value="lowercase"' . selected($submenu_text_transform, "lowercase", false) . '>Lowercase</option>';
        echo '<option value="initial"' . selected($submenu_text_transform, "initial", false) . '>Initial</option>';
        echo '<option value="inherit"' . selected($submenu_text_transform, "inherit", false) . '>Inherit</option>';
        echo '</select>';
        echo '</td>';
        echo '</tr>';





        $submenu_text_color_option_name = "ampro_submenu_text_color_" . $menu_name;
        $submenu_text_color = get_option($submenu_text_color_option_name, '#000000');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_text_color_option_name) . '">Submenu text color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($submenu_text_color_option_name) . '" name="' . esc_attr($submenu_text_color_option_name) . '" value="' . esc_attr($submenu_text_color) . '">';
        echo '</td>';
        echo '</tr>';

        $submenu_text_hover_color_option_name = "ampro_submenu_hover_text_color_" . $menu_name;
        $submenu_text_hover_color = get_option($submenu_text_hover_color_option_name, '#000000');
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($submenu_text_hover_color_option_name) . '">Submenu hover text color</label></th>';
        echo '<td>';
        echo '<input type="color" id="' . esc_attr($submenu_text_hover_color_option_name) . '" name="' . esc_attr($submenu_text_hover_color_option_name) . '" value="' . esc_attr($submenu_text_hover_color) . '">';
        echo '</td>';
        echo '</tr>';

        $submenu_link_padding_top_option_name = "ampro_submenu_link_padding_top_" . $menu_name;
        $submenu_link_padding_right_option_name = "ampro_submenu_link_padding_right_" . $menu_name;
        $submenu_link_padding_bottom_option_name = "ampro_submenu_link_padding_bottom_" . $menu_name;
        $submenu_link_padding_left_option_name = "ampro_submenu_link_padding_left_" . $menu_name;
        
        // Default padding value
        $default_padding = '15';
        
        echo '<tr>';
        echo '<th scope="row">Submenu link padding (px)</th>';
        echo '<td>';
        
        // Padding Top
        $submenu_link_padding_top_value = get_option($submenu_link_padding_top_option_name, $default_padding);
        echo '<label for="' . esc_attr($submenu_link_padding_top_option_name) . '">Top: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($submenu_link_padding_top_option_name) . '" name="' . esc_attr($submenu_link_padding_top_option_name) . '" value="' . esc_attr($submenu_link_padding_top_value) . '" min="0" step="1"> px ';
        
        // Padding Right
        $submenu_link_padding_right_value = get_option($submenu_link_padding_right_option_name, $default_padding);
        echo '<label for="' . esc_attr($submenu_link_padding_right_option_name) . '">Right: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($submenu_link_padding_right_option_name) . '" name="' . esc_attr($submenu_link_padding_right_option_name) . '" value="' . esc_attr($submenu_link_padding_right_value) . '" min="0" step="1"> px ';
        
        // Padding Bottom
        $submenu_link_padding_bottom_value = get_option($submenu_link_padding_bottom_option_name, $default_padding);
        echo '<label for="' . esc_attr($submenu_link_padding_bottom_option_name) . '">Bottom: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($submenu_link_padding_bottom_option_name) . '" name="' . esc_attr($submenu_link_padding_bottom_option_name) . '" value="' . esc_attr($submenu_link_padding_bottom_value) . '" min="0" step="1"> px ';
        
        // Padding Left
        $submenu_link_padding_left_value = get_option($submenu_link_padding_left_option_name, $default_padding);
        echo '<label for="' . esc_attr($submenu_link_padding_left_option_name) . '">Left: </label>';
        echo '<input size="4" type="number" id="' . esc_attr($submenu_link_padding_left_option_name) . '" name="' . esc_attr($submenu_link_padding_left_option_name) . '" value="' . esc_attr($submenu_link_padding_left_value) . '" min="0" step="1"> px ';
        
        echo '</td>';
        echo '</tr>';

        if ($menu_name == ampro_clean_menu_names($primary_menu_name)) {
          // Mobile menu
          echo '<tr><td><h4>Mobile Menu</h4></td></tr>';

          $breakpoint_width_option_name = "ampro_breakpoint_width_" . $menu_name;
          $breakpoint_width = get_option($breakpoint_width_option_name, 1200);
          echo '<tr>';
          echo '<th scope="row"><label for="' . esc_attr($breakpoint_width_option_name) . '">Breakpoint Width (px)</label></th>';
          echo '<td>';
          echo '<input size="10" type="text" id="' . esc_attr($breakpoint_width_option_name) . '" name="' . esc_attr($breakpoint_width_option_name) . '" value="' . esc_attr($breakpoint_width) . '" />';
          echo '</td>';
          echo '</tr>';

          $mobile_menu_overlay_background_color_option_name = "ampro_mobile_menu_overlay_background_color_" . $menu_name;
          $mobile_menu_overlay_background_color = get_option($mobile_menu_overlay_background_color_option_name, "#008080");
          echo '<tr>';
          echo '<th scope="row"><label for="' . esc_attr($mobile_menu_overlay_background_color_option_name) . '">Mobile Menu Overlay Background Color</label></th>';
          echo '<td>';
          echo '<input type="color" id="' . esc_attr($mobile_menu_overlay_background_color_option_name) . '" name="' . esc_attr($mobile_menu_overlay_background_color_option_name) . '" value="' . esc_attr($mobile_menu_overlay_background_color) . '">';
          echo '</td>';
          echo '</tr>';

          // mobile menu panel background color
          $mobile_menu_button_background_color_option_name = "ampro_mobile_menu_button_background_color_" . $menu_name;
          $mobile_menu_button_background_color = get_option($mobile_menu_button_background_color_option_name, '#ffffff');
          echo '<tr>';
          echo '<th scope="row"><label for="' . esc_attr($mobile_menu_button_background_color_option_name) . '">Mobile Menu Button Background Color</label></th>';
          echo '<td>';
          echo '<input type="color" id="' . esc_attr($mobile_menu_button_background_color_option_name) . '" name="' . esc_attr($mobile_menu_button_background_color_option_name) . '" value="' . esc_attr($mobile_menu_button_background_color) . '">';
          echo '</td>';
          echo '</tr>';

          $mobile_menu_button_font_option_name = 'ampro_mobile_menu_button_font_' . $menu_name;
          $mobile_menu_button_font = get_option($mobile_menu_button_font_option_name, 'Arial');
          $mobile_menu_button_font_style_option_name = 'ampro_mobile_menu_button_font_style_' . $menu_name;
          $mobile_menu_button_font_style = get_option($mobile_menu_button_font_style_option_name, 'regular');

          echo '<tr>';
          echo '<th scope="row"><label for="' . esc_attr($mobile_menu_button_font_option_name) . '">Mobile menu button font</label></th>';
          echo '<td>';
          echo '<select name="' . esc_attr($mobile_menu_button_font_option_name) . '" id="' . esc_attr($mobile_menu_button_font_option_name) . '">';
          foreach ($fonts as $font_name => $styles) {
              echo '<option value="' . esc_attr($font_name) . '" ' . selected($mobile_menu_button_font, $font_name) . '>';
              echo esc_html($font_name);
              echo '</option>';
          }
          echo '</select>';
          echo '</td>';
          echo '</tr>';

          echo '<tr>';
          echo '<th scope="row"><label for="' . esc_attr($mobile_menu_button_font_style_option_name) . '">Mobile Menu Font Style</label></th>';
          echo '<td>';
          echo '<select name="' . esc_attr($mobile_menu_button_font_style_option_name) . '" id="' . esc_attr($mobile_menu_button_font_style_option_name) . '">';
          if (isset($fonts[$mobile_menu_button_font])) {
              foreach ($fonts[$mobile_menu_button_font] as $style) {
                  echo '<option value="' . esc_attr($style) . '" ' . selected($mobile_menu_button_font_style, $style) . '>';
                  echo esc_html($style);
                  echo '</option>';
              }
          }
          echo '</select>';
          echo '</td>';
          echo '</tr>';

          $mobile_menu_button_font_size_option_name = "ampro_mobile_menu_button_font_size_" . $menu_name;
          $mobile_menu_button_font_size = get_option($mobile_menu_button_font_size_option_name, '12');
          echo '<tr>';
          echo '<th scope="row"><label for="' . esc_attr($mobile_menu_button_font_size_option_name) . '">Mobile menu button font size (px)</label></th>';
          echo '<td>';
          echo '<input size="6" type="number" id="' . esc_attr($mobile_menu_button_font_size_option_name) . '" name="' . esc_attr($mobile_menu_button_font_size_option_name) . '" value="' . esc_attr($mobile_menu_button_font_size) . '" min="1" step="1"> px';
          echo '</td>';
          echo '</tr>';

          $mobile_menu_button_text_color_option_name = "ampro_mobile_menu_button_text_color_" . $menu_name;
          $mobile_menu_button_text_color = get_option($mobile_menu_button_text_color_option_name, '#000000');
          echo '<tr>';
          echo '<th scope="row"><label for="' . esc_attr($mobile_menu_button_text_color_option_name) . '">Mobile menu button text color</label></th>';
          echo '<td>';
          echo '<input type="color" id="' . esc_attr($mobile_menu_button_text_color_option_name) . '" name="' . esc_attr($mobile_menu_button_text_color_option_name) . '" value="' . esc_attr($mobile_menu_button_text_color) . '">';
          echo '</td>';
          echo '</tr>';
        }

        // JavaScript for updating the font styles dropdown dynamically
        $ampro_custom_js = '
        document.addEventListener("DOMContentLoaded", function() {
            var colorInput = document.getElementById("' . esc_attr($parent_background_color_option_name) . '");
            var checkbox = document.getElementById("' . esc_attr($parent_background_color_transparent_option_name) . '");
            if (checkbox.checked) {
                colorInput.disabled = true;
                colorInput.value = "#ffffff";
            }

            checkbox.addEventListener("change", function() {
                if (checkbox.checked) {
                    colorInput.disabled = true;
                    colorInput.value = "#ffffff";
                } else {
                    colorInput.disabled = false;
                }
            });

            var parentFontSelect = document.getElementById("' . esc_attr($parent_font_option_name) . '");
            var styleSelect = document.getElementById("' . esc_attr($parent_font_style_option_name) . '");
            var fonts = ' . wp_json_encode($fonts) . ';
            var savedStyle = "' . esc_attr($parent_font_style) . '";  // Get the saved style option from PHP

            parentFontSelect.addEventListener("change", function() {
                var selectedFont = parentFontSelect.value;
                styleSelect.innerHTML = "";  // Clear existing options

                // Populate style dropdown with styles of the selected font
                if (fonts[selectedFont]) {
                    fonts[selectedFont].forEach(function(style) {
                        var option = document.createElement("option");
                        option.value = style;
                        option.textContent = style;
                        option.selected = (style === savedStyle);  // Set selected based on saved value
                        styleSelect.appendChild(option);
                    });
                }
            });

            // Initially populate the style dropdown and set the correct selected option
            parentFontSelect.dispatchEvent(new Event("change"));

            var submenuFontSelect = document.getElementById("' . esc_attr($submenu_font_option_name) . '");
            var submenuStyleSelect = document.getElementById("' . esc_attr($submenu_font_style_option_name) . '");
            var submenuFonts = ' . wp_json_encode($fonts) . ';
            var submenuSavedStyle = "' . esc_attr($submenu_font_style) . '";  // Get the saved style option from PHP

            submenuFontSelect.addEventListener("change", function() {
                var selectedFont = submenuFontSelect.value;
                submenuStyleSelect.innerHTML = "";  // Clear existing options

                // Populate style dropdown with styles of the selected font
                if (submenuFonts[selectedFont]) {
                    submenuFonts[selectedFont].forEach(function(style) {
                        var option = document.createElement("option");
                        option.value = style;
                        option.textContent = style;
                        option.selected = (style === submenuSavedStyle);  // Set selected based on saved value
                        submenuStyleSelect.appendChild(option);
                    });
                }
            });

            // Initially populate the style dropdown and set the correct selected option
            submenuFontSelect.dispatchEvent(new Event("change"));


            var mobileMenuButtonFontSelect = document.getElementById("' . esc_attr($mobile_menu_button_font_option_name) . '");
            var mobileMenuButtonStyleSelect = document.getElementById("' . esc_attr($mobile_menu_button_font_style_option_name) . '");
            var mobileMenuButtonFonts = ' . wp_json_encode($fonts) . ';
            var mobileMenuButtonSavedStyle = "' . esc_attr($mobile_menu_button_font_style) . '";  // Get the saved style option from PHP

            mobileMenuButtonFontSelect.addEventListener("change", function() {
                var selectedFont = mobileMenuButtonFontSelect.value;
                mobileMenuButtonStyleSelect.innerHTML = "";  // Clear existing options

                // Populate style dropdown with styles of the selected font
                if (mobileMenuButtonFonts[selectedFont]) {
                    mobileMenuButtonFonts[selectedFont].forEach(function(style) {
                        var option = document.createElement("option");
                        option.value = style;
                        option.textContent = style;
                        option.selected = (style === mobileMenuButtonSavedStyle);  // Set selected based on saved value
                        mobileMenuButtonStyleSelect.appendChild(option);
                    });
                }
            });

            // Initially populate the style dropdown and set the correct selected option
            mobileMenuButtonFontSelect.dispatchEvent(new Event("change"));
        });
        ';
        accessibilityMenuProLoadCustomJs($ampro_custom_js);
        
        echo '</table>';
        submit_button('Save Changes');
        echo '</form>';
        echo '</div>';        
      }

      echo '</div>';
      echo '</div>';
      echo '</div>';
    ?>
</div>

<?php

function ampro_get_plugin_fonts($dir = null) {
  // Set the initial path to the fonts directory, moving up one level from the plugin directory
  $base_path = dirname(plugin_dir_path(__FILE__)) . '/fonts';
  $font_path = $dir ? $base_path . '/' . $dir : $base_path;

  // Check if the path exists and is a directory
  if (!is_dir($font_path)) {
      ampro_console_log('Font directory not found: ' . $font_path);
      return array();
  }

  $font_files = scandir($font_path);
  $fonts = array();

  foreach ($font_files as $file) {
      if ($file === "." || $file === "..") {
          continue;
      }
      // Build the path for the file or directory
      $full_path = $font_path . '/' . $file;

      // Check if it's a directory and recurse
      if (is_dir($full_path)) {
          $sub_fonts = ampro_get_plugin_fonts($dir ? $dir . '/' . $file : $file);
          $fonts = array_merge($fonts, $sub_fonts);
      } else {
          // Process the file if it's a valid font file
          $file_info = pathinfo($file);
          if (isset($file_info['extension']) && in_array($file_info['extension'], ['woff', 'woff2'])) {
              // Split at "-v" to separate the font family name from the rest
              $name_parts = explode('-v', $file_info['filename'], 2);
              if (count($name_parts) > 1) {
                  $font_name = trim($name_parts[0]); // This will contain the entire name part before "-v"
                  $version_style = 'v' . $name_parts[1]; // Restores the initial 'v' and contains the version and style

                  // Check if the version_style contains 'italic', and split it accordingly
                  if (preg_match('/italic/', $version_style)) {
                      $font_style = preg_replace('/\.woff2$/', '', $version_style); // Removes file extension if present
                  } else {
                      $font_style = preg_replace('/\.woff2$/', '', $version_style); // Same here
                  }

                  if (!isset($fonts[$font_name])) {
                      $fonts[$font_name] = [];
                  }
                  $fonts[$font_name][] = $font_style;
              }
          }
      }
  }

  return $fonts;
}

function ampro_add_default_system_fonts(&$fonts) {
  // URL to the default fonts JSON file
  $json_file_url = plugins_url('/../fonts/default-fonts.json', __FILE__);

  // Perform the HTTP request using wp_remote_get()
  $response = wp_remote_get($json_file_url);

  // Check if the request was successful
  if (is_wp_error($response)) {
      return;
  }

  // Get the response body
  $json_content = wp_remote_retrieve_body($response);

  // Decode the JSON content into an associative array
  $default_fonts = json_decode($json_content, true);

  // Ensure the JSON content was decoded properly
  if (!is_array($default_fonts)) {
      return;
  }

  // Add each default font and its styles to the $fonts array
  foreach ($default_fonts as $font_name => $styles) {
      if (!isset($fonts[$font_name])) {
          $fonts[$font_name] = [];
      }
      foreach ($styles as $style) {
          if (!in_array($style, $fonts[$font_name])) {
              $fonts[$font_name][] = $style;
          }
      }
  }
}

?>
