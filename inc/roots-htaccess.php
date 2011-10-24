<?php

// Using a server other than Apache? See:
// https://github.com/retlehs/roots/wiki/Nginx
// https://github.com/retlehs/roots/wiki/Lighttpd

if (stristr($_SERVER['SERVER_SOFTWARE'], 'apache') !== false) {
  function roots_htaccess_writable() {
    if (!is_writable(get_home_path() . '.htaccess')) {
      if (current_user_can('administrator')) {
        add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writable ', 'roots'), admin_url('options-permalink.php')) . "</p></div>';"));
      }
    };
  }

  add_action('admin_init', 'roots_htaccess_writable');

  // Rewrites DO NOT happen for child themes
  // rewrite /wp-content/themes/roots/css/ to /css/
  // rewrite /wp-content/themes/roots/js/  to /js/
  // rewrite /wp-content/themes/roots/img/ to /js/
  // rewrite /wp-content/plugins/ to /plugins/

  function roots_flush_rewrites() {
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
  }

  function roots_add_rewrites($content) {
    $theme_name = next(explode('/themes/', get_stylesheet_directory()));
    global $wp_rewrite;
    $roots_new_non_wp_rules = array(
      'css/(.*)'      => 'wp-content/themes/'. $theme_name . '/css/$1',
      'js/(.*)'       => 'wp-content/themes/'. $theme_name . '/js/$1',
      'img/(.*)'      => 'wp-content/themes/'. $theme_name . '/img/$1',
      'plugins/(.*)'  => 'wp-content/plugins/$1'
    );
    $wp_rewrite->non_wp_rules += $roots_new_non_wp_rules;
  }

  add_action('admin_init', 'roots_flush_rewrites');

  function roots_clean_assets($content) {
      $theme_name = next(explode('/themes/', $content));
      $current_path = '/wp-content/themes/' . $theme_name;
      $new_path = '';
      $content = str_replace($current_path, $new_path, $content);
      return $content;
  }

  function roots_clean_plugins($content) {
      $current_path = '/wp-content/plugins';
      $new_path = '/plugins';
      $content = str_replace($current_path, $new_path, $content);
      return $content;
  }

  // only use clean urls if the theme isn't a child or an MU (Network) install
  if (!is_multisite() && !is_child_theme()) {
    add_action('generate_rewrite_rules', 'roots_add_rewrites');
    if (!is_admin()) {
      add_filter('plugins_url', 'roots_clean_plugins');
      add_filter('bloginfo', 'roots_clean_assets');
      add_filter('stylesheet_directory_uri', 'roots_clean_assets');
      add_filter('template_directory_uri', 'roots_clean_assets');
      add_filter('script_loader_src', 'roots_clean_plugins');
      add_filter('style_loader_src', 'roots_clean_plugins');
    }
  }

  function roots_add_h5bp_htaccess($rules) {
    global $wp_filesystem;

    if (!defined('FS_METHOD')) define('FS_METHOD', 'direct');
    if (is_null($wp_filesystem)) WP_Filesystem(array(), ABSPATH);

    $filename = __DIR__ . '/h5bp-htaccess';

    return $rules . $wp_filesystem->get_contents($filename);
  }

  add_filter('mod_rewrite_rules', 'roots_add_h5bp_htaccess');
}

?>
