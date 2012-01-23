<?php

function roots_scripts() {
  $template_uri = get_template_directory_uri();
  wp_register_script('roots_plugins', ''.$template_uri.'/js/plugins.js', false, null, false);
  wp_register_script('roots_script', ''.$template_uri.'/js/script.js', false, null, false);
  wp_enqueue_script('roots_plugins');
  wp_enqueue_script('roots_script');

  if (roots_current_framework() === '1140') {
    wp_register_script('css3-mediaqueries', ''.$template_uri.'/js/libs/css3-mediaqueries.js', false, null, false);
    wp_enqueue_script('css3-mediaqueries');
  }

  if (roots_current_framework() === 'adapt') {
    wp_register_script('adapt', ''.$template_uri.'/js/libs/adapt.min.js', false, null, false);
    wp_enqueue_script('adapt');
  }

  if (roots_current_framework() === 'foundation') {
    wp_register_script('foundation-jquery-reveal', ''.$template_uri.'/js/foundation/jquery.reveal.js', false, null, false);
    wp_register_script('foundation-jquery-orbit', ''.$template_uri.'/js/foundation/jquery.orbit-1.3.0.js', false, null, false);
    wp_register_script('foundation-forms-jquery', ''.$template_uri.'/js/foundation/forms.jquery.js', false, null, false);
    wp_register_script('foundation-jquery-customforms', ''.$template_uri.'/js/foundation/jquery.customforms.js', false, null, false);
    wp_register_script('foundation-jquery-placeholder', ''.$template_uri.'/js/foundation/jquery.placeholder.min.js', false, null, false);
    wp_register_script('foundation-app', ''.$template_uri.'/js/foundation/app.js', false, null, false);
    wp_enqueue_script('foundation-jquery-reveal');
    wp_enqueue_script('foundation-jquery-orbit');
    wp_enqueue_script('foundation-forms-jquery');
    wp_enqueue_script('foundation-jquery-customforms');
    wp_enqueue_script('foundation-jquery-placeholder');
    wp_enqueue_script('foundation-app');
  }

  if (roots_current_framework() === 'bootstrap' || roots_current_framework() === 'bootstrap_less') {
    global $roots_options;
    $roots_bootstrap_js = $roots_options['bootstrap_javascript'];
    $roots_bootstrap_less_js = $roots_options['bootstrap_less_javascript'];
    $template_uri = get_template_directory_uri();
    if (roots_current_framework() === 'bootstrap_less') {
      wp_register_script('bootstrap-less', ''.$template_uri.'/js/bootstrap/less-1.1.3.min.js', false, null, false);
      wp_enqueue_script('bootstrap-less');
    }
    if ($roots_bootstrap_js === true || $roots_bootstrap_less_js === true) {
      $roots_options['bootstrap_less_javascript'] = false;
      $roots_options['bootstrap_javascript'] = false;

      wp_register_script('bootstrap-modal', ''.$template_uri.'/js/bootstrap/bootstrap-modal.js', false, null, false);
      wp_register_script('bootstrap-alerts', ''.$template_uri.'/js/bootstrap/bootstrap-alerts.js', false, null, false);
      wp_register_script('bootstrap-twipsy', ''.$template_uri.'/js/bootstrap/bootstrap-twipsy.js', false, null, false);
      wp_register_script('bootstrap-popover', ''.$template_uri.'/js/bootstrap/bootstrap-popover.js', false, null, false);
      wp_register_script('bootstrap-dropdown', ''.$template_uri.'/js/bootstrap/bootstrap-dropdown.js', false, null, false);
      wp_register_script('bootstrap-scrollspy', ''.$template_uri.'/js/bootstrap/bootstrap-scrollspy.js', false, null, false);
      wp_register_script('bootstrap-tabs', ''.$template_uri.'/js/bootstrap/bootstrap-tabs.js', false, null, false);
      wp_register_script('bootstrap-buttons', ''.$template_uri.'/js/bootstrap/bootstrap-buttons.js', false, null, false);
      wp_enqueue_script('bootstrap-modal');
      wp_enqueue_script('bootstrap-alerts');
      wp_enqueue_script('bootstrap-twipsy');
      wp_enqueue_script('bootstrap-popover');
      wp_enqueue_script('bootstrap-dropdown');
      wp_enqueue_script('bootstrap-scrollspy');
      wp_enqueue_script('bootstrap-tabs');
      wp_enqueue_script('bootstrap-buttons');
    }
  }
}

add_action('wp_enqueue_scripts', 'roots_scripts');

if (!is_admin()) {
  add_action('wp_print_scripts', 'roots_print_scripts');
}

function roots_print_scripts() {
  global $wp_scripts;
  $wp_scripts->all_deps($wp_scripts->queue);
  $scripts = array();

  foreach ($wp_scripts->queue as $key => $handle) {
    $skip_scripts = array('jquery', 'roots_script', 'roots_plugins');

    $src = $wp_scripts->registered[$handle]->src;
    unset($wp_scripts->queue[$key]);
    $wp_scripts->done[] = $handle;

    if (!in_array($handle, $skip_scripts)) {
      $scripts[] = '<script src="' . $src . '"></script>';
    }
  }

  echo "\t" . implode("\n\t", $scripts) . "\n";

  $template_uri = get_template_directory_uri();
  echo "\t<script defer src=\"$template_uri/js/plugins.js\"></script>\n";
  echo "\t<script defer src=\"$template_uri/js/script.js\"></script>\n";

  $wp_scripts->reset();
  return $wp_scripts->done;
}

?>