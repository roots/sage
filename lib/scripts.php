<?php
/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/main.css
 *
 * Enqueue scripts in the following order:
 * 1. jquery-1.11.0.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr.min.js
 * 3. /theme/assets/js/main.js (in footer)
 */
function roots_scripts() {
  // The build task in Grunt renames production assets with a hash
  // Read the asset names from assets-manifest.json
  $get_assets = file_get_contents(get_template_directory_uri() . '/assets-manifest.json');
  $assets     = json_decode($get_assets, true);

  if (WP_ENV === 'development') {
    wp_enqueue_style('roots_main_dev', get_template_directory_uri() . '/assets/css/main.css', false, null);
  } else {
    wp_enqueue_style('roots_main',     get_template_directory_uri() . '/' . $assets['assets/css/main.min.css'], false, null);
  }

  // jQuery is loaded using the same method from HTML5 Boilerplate:
  // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
  // It's kept in the header instead of footer to avoid conflicts with plugins.
  if (!is_admin() && current_theme_supports('jquery-cdn')) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', array(), null, false);
    add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('modernizr',         get_template_directory_uri() . '/assets/js/vendor/modernizr.min.js', array(), null, false);
  wp_register_script('roots_scripts_dev', get_template_directory_uri() . '/assets/js/scripts.js', array(), null, true);
  wp_register_script('roots_scripts',     get_template_directory_uri() . '/' . $assets['assets/js/scripts.min.js'], array(), null, true);
  wp_enqueue_script('modernizr');
  wp_enqueue_script('jquery');
  if (WP_ENV === 'development') {
    wp_enqueue_script('roots_scripts_dev');
  } else {
    wp_enqueue_script('roots_scripts');
  }
}
add_action('wp_enqueue_scripts', 'roots_scripts', 100);

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle = null) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/vendor/jquery/jquery.min.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}
add_action('wp_head', 'roots_jquery_local_fallback');

function roots_google_analytics() { ?>
<script>
  (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
  function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
  e=o.createElement(i);r=o.getElementsByTagName(i)[0];
  e.src='//www.google-analytics.com/analytics.js';
  r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
  ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>');ga('send','pageview');
</script>

<?php }
if (GOOGLE_ANALYTICS_ID && !current_user_can('manage_options')) {
  add_action('wp_footer', 'roots_google_analytics', 20);
}
