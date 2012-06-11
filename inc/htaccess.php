<?php

// Using a server other than Apache? See:
// https://github.com/retlehs/roots/wiki/Nginx
// https://github.com/retlehs/roots/wiki/Lighttpd

if (stristr($_SERVER['SERVER_SOFTWARE'], 'apache') || stristr($_SERVER['SERVER_SOFTWARE'], 'litespeed') !== false) {

  function roots_htaccess_writable() {
    if (!is_writable(get_home_path() . '.htaccess')) {
      if (current_user_can('administrator')) {
        add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">.htaccess</a> file is writable ', 'roots'), admin_url('options-permalink.php')) . "</p></div>';"));
      }
    }
  }

  add_action('admin_init', 'roots_htaccess_writable');

  // Rewrites DO NOT happen for child themes
  // rewrite /wp-content/themes/roots/css/ to /css/
  // rewrite /wp-content/themes/roots/js/  to /js/
  // rewrite /wp-content/themes/roots/img/ to /js/
  // rewrite /wp-content/plugins/ to /plugins/

  function roots_add_rewrites($content) {
    global $wp_rewrite;
    $roots_new_non_wp_rules = array(
      'css/(.*)'      => THEME_PATH . '/css/$1',
      'js/(.*)'       => THEME_PATH . '/js/$1',
      'img/(.*)'      => THEME_PATH . '/img/$1',
      'plugins/(.*)'  => RELATIVE_PLUGIN_PATH . '/$1'
    );
    $wp_rewrite->non_wp_rules = array_merge($wp_rewrite->non_wp_rules, $roots_new_non_wp_rules);
    return $content;
  }

  function roots_clean_urls($content) {
    if (strpos($content, FULL_RELATIVE_PLUGIN_PATH) === 0) {
      return str_replace(FULL_RELATIVE_PLUGIN_PATH, WP_BASE . '/plugins', $content);
    } else {
      return str_replace('/' . THEME_PATH, '', $content);
    }
  }

  // only use clean urls if the theme isn't a child or an MU (Network) install
  if (!is_multisite() && !is_child_theme() && get_option('permalink_structure')) {
    if (current_theme_supports('rewrite-urls')) {
      add_action('generate_rewrite_rules', 'roots_add_rewrites');
    }

    if (current_theme_supports('h5bp-htaccess')) {
      add_action('generate_rewrite_rules', 'roots_add_h5bp_htaccess');
    }

    if (!is_admin() && current_theme_supports('rewrite-urls')) {
      $tags = array(
        'plugins_url',
        'bloginfo',
        'stylesheet_directory_uri',
        'template_directory_uri',
        'script_loader_src',
        'style_loader_src'
      );

      add_filters($tags, 'roots_clean_urls');
    }
  }

  // add the contents of h5bp-htaccess into the .htaccess file
  function roots_add_h5bp_htaccess($content) {
    global $wp_rewrite;
    $home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
    $htaccess_file = $home_path . '.htaccess';
    $mod_rewrite_enabled = function_exists('got_mod_rewrite') ? got_mod_rewrite() : false;

    if ((!file_exists($htaccess_file) && is_writable($home_path) && $wp_rewrite->using_mod_rewrite_permalinks()) || is_writable($htaccess_file)) {
      if ($mod_rewrite_enabled) {
        $h5bp_rules = extract_from_markers($htaccess_file, 'HTML5 Boilerplate');
          if ($h5bp_rules === array()) {
            $filename = dirname(__FILE__) . '/h5bp-htaccess';
            return insert_with_markers($htaccess_file, 'HTML5 Boilerplate', extract_from_markers($filename, 'HTML5 Boilerplate'));
          }
      }
    }

    return $content;
  }

}