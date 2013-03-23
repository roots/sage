<?php
/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/bootstrap.css
 * 2. /theme/assets/css/bootstrap-responsive.css
 * 3. /theme/assets/css/app.css
  * 3. /theme/assets/css/app.css
 * 4. /child-theme/style.css (if a child theme is activated)
 *
 * Enqueue scripts in the following order:
 * 1. jquery-1.9.1.min.js via Google CDN
 * 2. /theme/assets/js/vendor/modernizr-2.6.2.min.js
 * 3. /theme/assets/js/plugins.js (in footer)
 * 4. /theme/assets/js/main.js    (in footer)
 */
function roots_scripts() {
  wp_enqueue_style('roots_bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.css', false, null);
  wp_enqueue_style('roots_bootstrap_responsive', get_template_directory_uri() . '/assets/css/bootstrap-responsive.css', array('roots_bootstrap'), null);
  wp_enqueue_style('roots_app', get_template_directory_uri() . '/assets/css/app.css', false, null);
  wp_enqueue_style('awesome_font', get_stylesheet_directory_uri() . '/assets/css/font-awesome.css', false, null);
  wp_enqueue_style('atkore', get_stylesheet_directory_uri() . '/assets/css/atkore.css', false, null);
  //wp_enqueue_style('atkore_less', get_stylesheet_directory_uri() . '/assets/css/atkore.less', false, '1.0');
  //add_filter('style_loader_tag', 'less_style_loader_tag_function');

  // Load style.css from child theme
  if (is_child_theme()) {
    wp_enqueue_style('roots_child', get_stylesheet_uri(), false, null);
  }

  // jQuery is loaded using the same method from HTML5 Boilerplate:
  // Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
  // It's kept in the header instead of footer to avoid conflicts with plugins.
  if (!is_admin()) {
    wp_deregister_script('jquery');
    wp_register_script('jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js', false, null, false);
  }

  if (is_single() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/vendor/modernizr-2.6.2.min.js', false, null, false);
  wp_register_script('less', get_stylesheet_directory_uri() . '/assets/js/vendor/less-1.4.0-beta.min.js', false, '1.4.0-beta', false);
  wp_register_script('sequence', get_stylesheet_directory_uri() . '/assets/js/vendor/sequence.jquery-min.js', false, '0.8.5-beta', false);
  wp_register_script('roots_plugins', get_template_directory_uri() . '/assets/js/plugins.js', false, '2.3.1', true);
  wp_register_script('roots_main', get_stylesheet_directory_uri() . '/assets/js/main-ck.js', false, null, true);
  wp_enqueue_script('jquery');
  wp_enqueue_script('modernizr');
  wp_enqueue_script('less');
  wp_enqueue_script('sequence');
  wp_enqueue_script('roots_plugins');
  wp_enqueue_script('roots_main');

}
add_action('wp_enqueue_scripts', 'roots_scripts', 100);
/*
// http://stackoverflow.com/questions/8082236/wp-enqueue-style-and-rel-other-than-stylesheet
function less_style_loader_tag_function($tag){
  //do stuff here to find and replace the rel attribute    
  return preg_replace("/='stylesheet' id='atkore_less-css'/", "='stylesheet/less' id='atkore_less-css'", $tag);
}
*/

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback($src, $handle) {
  static $add_jquery_fallback = false;

  if ($add_jquery_fallback) {
    echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/vendor/jquery-1.9.1.min.js"><\/script>\')</script>' . "\n";
    $add_jquery_fallback = false;
  }

  if ($handle === 'jquery') {
    $add_jquery_fallback = true;
  }

  return $src;
}
if (!is_admin()) {
  add_filter('script_loader_src', 'roots_jquery_local_fallback', 10, 2);
}

function roots_google_analytics() { ?>
<script>
  var _gaq=[['_setAccount','<?php echo GOOGLE_ANALYTICS_ID; ?>'],['_trackPageview']];
  (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
<?php }
if (GOOGLE_ANALYTICS_ID) {
  add_action('wp_footer', 'roots_google_analytics', 20);
}



