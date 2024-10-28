<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!current_user_can('manage_options')) {
  return;
}

$settings_updated = false;
// Retrieve all registered menus
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

// Check if the form has been submitted and handle the general settings
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bame1-general-settings-nonce'])) {
  if (wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bame1-general-settings-nonce'])), 'bame1-general-settings-save')) {
    if (isset($_POST['ampro_primary'])) {
      // Update the option with the sanitized menu name
      update_option('ampro_primary', sanitize_text_field($_POST['ampro_primary']));
      $settings_updated = true;
    }
  }
}

if ($settings_updated) {
  echo '<div class="updated" id="settings-updated"><p>Settings updated.</p></div>';
}

// HTML form for general settings
?>
<div id="general-settings" class="wrap">
  <h2>General Settings</h2>

  <h3>Description</h3>
  <p>Accessibility Menu Pro replaces the standard menus in your WordPress theme with an American Disabilities Association (ADA) AA-compliant menu. All HTML semantics are compliant with accessibility standards, ensuring that your site is more accessible to users with disabilities.</p>
  <h3>Features</h3>
  <ul>
    <li> - Replaces default theme menus with ADA AA-compliant menus.</li>
    <li> - Ensures all HTML semantics meet accessibility standards.</li>
    <li> - Full compatibility and responsive on mobile and desktop devices.</li>
    <li> - Easy integration with existing WordPress themes.</li>
    <li> - Fully customizable to match the look and feel of your site.</li>
  </ul>
  
  <h3>Setup</h3>
  <ol>
    <li>If you haven't done so already, create your menu(s) using the default WordPress menu editor (Dashboard > Appearance > Menus or <?php echo '<a href="' . esc_url(admin_url('nav-menus.php')) . '">click here</a>'; ?>).</li>
    <li>Select your primary navigation menu below. The primary/main navigation menu usually appears at the top of your website on every page. This is the menu that will
    show when users tap the Menu button on mobile devices.</li>
    <li>Once saved, go to the <a href="?page=accessibility-menu-pro-settings&tab=appearance">Appearance & Style</a> tab to configure your menu(s).</li>
    <li>If this is a fresh install, you must "Save Changes" on the Appearance & Style tab to initialize menu correctly (even if you don't make any changes).</li>
  </ol>

  <form method="post" action="">
      <?php wp_nonce_field('bame1-general-settings-save', 'bame1-general-settings-nonce'); ?>

      <?php

      echo '<table class="form-table">';
      echo '<tr>';
      echo '<th scope="row"><label for="ampro_primary">Main Navigation Menu</label></th>';
      echo '<td>';

      // Add the null/empty/unset radio button
      echo '<label>';
      echo '<input type="radio" name="ampro_primary" value="" ' . checked('', get_option('ampro_primary'), false) . '>';
      echo '<i>None/unset</i>';
      echo '</label><br>';

      foreach ($menu_location_pairs as $menu_name => $menu_description) {
        echo '<label>';
        echo '<input type="radio" name="ampro_primary" value="' . esc_attr($menu_name) . '" ' . checked($menu_name, get_option('ampro_primary'), false) . '>';
        echo esc_html($menu_description);
        echo '</label><br>';
      }

      echo '</td>';
      echo '</tr>';
      echo '</table>';

      ?>

      <?php submit_button('Save Changes'); ?>
  </form>
</div>
