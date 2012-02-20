<?php

function roots_scripts() {
  wp_register_script('roots_plugins', THEME_PATH . '/js/plugins.js', false, null, false);
  wp_register_script('roots_script', THEME_PATH . '/js/script.js', false, null, false);
  wp_enqueue_script('roots_plugins');
  wp_enqueue_script('roots_script');
}

add_action('wp_enqueue_scripts', 'roots_scripts');

if (!is_admin()) {
  add_action('wp_print_scripts', 'roots_print_scripts');
}

function roots_print_scripts() {
  global $wp_scripts;

  $wp_scripts->all_deps($wp_scripts->queue);
  $scripts = $locales = array();
  $queue = $wp_scripts->queue;
  $wp_scripts->all_deps($queue);

  foreach ($wp_scripts->to_do as $key => $handle) {
    $skip_scripts = array('jquery', 'roots_script', 'roots_plugins');

    $src = WP_BASE . leadingslashit($wp_scripts->registered[$handle]->src);
    $src = apply_filters('script_loader_src', $src);

    if ($locale = $wp_scripts->print_extra_script($handle, false)) {
      $locales[] = $locale;
    }

    $wp_scripts->done[] = $handle;

    if (!in_array($handle, $skip_scripts)) {
      $scripts[] = '<script src="' . $src . '"></script>';
    }
  }

  echo "\t" . implode("\n\t", $scripts) . "\n";
  if (!empty($locales)) {
    echo "\t<script>\n";
    foreach ($locales as $locale) {
      echo "\t\t{$locale}\n";
    }
    echo "\t</script>\n";
  }

  $template_uri = get_template_directory_uri();
  echo "\t<script src=\"$template_uri/js/plugins.js\"></script>\n";
  echo "\t<script src=\"$template_uri/js/script.js\"></script>\n";

  $wp_scripts->reset();
  return $wp_scripts->done;
}

?>
