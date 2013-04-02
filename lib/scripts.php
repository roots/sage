<?php
/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue stylesheets in the following order:
 * 1. /theme/assets/css/bootstrap.css
 * 2. /theme/assets/css/bootstrap-responsive.css
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
  $domain = $_SERVER[ 'SERVER_NAME' ];
  // Core Roots styles 
  wp_enqueue_style('bootstrap', get_stylesheet_directory_uri() . '/assets/css/bootstrap.css', false, null);
  wp_enqueue_style('roots_bootstrap_responsive', get_template_directory_uri() . '/assets/css/bootstrap-responsive.css', array('bootstrap'), null);
  wp_enqueue_style('roots_app', get_template_directory_uri() . '/assets/css/app.css', false, null);

  // Load style.css from child theme
  if (is_child_theme()) {
    wp_enqueue_style('roots_child', get_stylesheet_uri(), false, null);
  }
  
  // Additionals
  wp_enqueue_style('tablecloth', get_stylesheet_directory_uri() . '/assets/css/tablecloth.css', false, null);
  wp_enqueue_style('awesome_font', get_stylesheet_directory_uri() . '/assets/css/font-awesome.css', false, null);
  wp_enqueue_style('bootstrap_progressbar', get_stylesheet_directory_uri() . '/assets/css/bootstrap-progressbar.min.css', false, null);
  wp_enqueue_style('atkore_progressbar', get_stylesheet_directory_uri() . '/assets/css/atkore-progressbar.css', false, null);


  if ($domain == 'atkore.local' || $domain == 'dev.atkore.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'www.atkore.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'atkore.com') {
    wp_enqueue_style('atkore', get_stylesheet_directory_uri() . '/assets/css/atkore.css', false, null);
  }
  if ($domain == 'atcfence.local' || $domain == 'dev.atcfence.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'www.atcfence.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'atcfence.com') {
    wp_enqueue_style('atcfence', get_stylesheet_directory_uri() . '/assets/css/atcfence.css', false, null);
  }
  if ($domain == 'easternwire.local' || $domain == 'dev.easternwire.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'www.easternwire.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'easternwire.com') {
    wp_enqueue_style('easternwire', get_stylesheet_directory_uri() . '/assets/css/easternwire.css', false, null);
  }
  if ($domain == 'kaftech.local' || $domain == 'dev.kaftech.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'www.kaftech.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'kaf-tech.com') {
    wp_enqueue_style('kaftech', get_stylesheet_directory_uri() . '/assets/css/kaftech.css', false, null);
  }
  if ($domain == 'alliedtube-sprinkler.local' || $domain == 'dev.alliedtube-sprinkler.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'www.alliedtube-sprinkler.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'alliedtube-sprinkler.com') {
    wp_enqueue_style('alliedtube_sprinkler', get_stylesheet_directory_uri() . '/assets/css/alliedtube-sprinkler.css', false, null);
  }
  if ($domain == 'unistrutfallprotection.local' || $domain == 'dev.unistrutfallprotection.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'www.unistrutfallprotection.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'unistrutfallprotection.com') {
    wp_enqueue_style('unistrutfallprotection', get_stylesheet_directory_uri() . '/assets/css/unistrutfallprotection.css', false, null);
  }
  if ($domain == 'afcweb.local' || $domain == 'dev.afcweb.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'www.afcweb.com.php53-2.ord1-1.websitetestlink.com' || $domain == 'afcweb.com') {
    wp_enqueue_style('unistrutfallprotection', get_stylesheet_directory_uri() . '/assets/css/afcweb.css', false, null);
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

  if (is_front_page()){
    wp_register_script('sequence', get_stylesheet_directory_uri() . '/assets/js/vendor/sequence.jquery-min.js', false, null, false);
    wp_register_script('atkore_sequence', get_stylesheet_directory_uri() . '/assets/js/atkore-sequence.min.js', false, null, false);
  }

  wp_register_script('roots_plugins', get_template_directory_uri() . '/assets/js/plugins.js', false, '2.3.1', true);
  wp_register_script('roots_main', get_stylesheet_directory_uri() . '/assets/js/main.min.js', false, null, true);
  wp_register_script('bootstrap_progressbar', get_stylesheet_directory_uri() . '/assets/js/vendor/bootstrap-progressbar.min.js', false, null, true);
  wp_register_script('css3mediaqueries', get_stylesheet_directory_uri() . '/assets/js/vendor/css3-mediaqueries.js', false, null, true);
  wp_register_script('metadata', get_stylesheet_directory_uri() . '/assets/js/vendor/jquery.metadata.js', false, null, true);
  wp_register_script('tablesorter', get_stylesheet_directory_uri() . '/assets/js/vendor/jquery.tablesorter.min.js', false, null, true);
  wp_register_script('tablecloth', get_stylesheet_directory_uri() . '/assets/js/vendor/jquery.tablecloth.js', false, null, true);
  wp_register_script('atkore_tabs', get_stylesheet_directory_uri() . '/assets/js/atkore-tabs.min.js', false, null, true);
  wp_register_script('atkore_popovers', get_stylesheet_directory_uri() . '/assets/js/atkore-popovers.min.js', false, null, true);
  wp_register_script('atkore_progressbar', get_stylesheet_directory_uri() . '/assets/js/atkore-progressbar.min.js', false, null, true);
  wp_register_script('atkore_tablecloth', get_stylesheet_directory_uri() . '/assets/js/atkore-tablecloth.min.js', false, null, true);


  wp_enqueue_script('jquery');
  wp_enqueue_script('modernizr');

  if (is_front_page()){
    wp_enqueue_script('sequence');
    wp_enqueue_script('atkore_sequence');
  }

  wp_enqueue_script('roots_plugins');
  wp_enqueue_script('roots_main');
  wp_enqueue_script('bootstrap_progressbar');
  wp_enqueue_script('css3mediaqueries');
  wp_enqueue_script('metadata');
  wp_enqueue_script('tablesorter');
  wp_enqueue_script('tablecloth');

  wp_enqueue_script('atkore_tabs');
  wp_enqueue_script('atkore_popovers');
  wp_enqueue_script('atkore_progressbar');
  wp_enqueue_script('atkore_tablecloth');

}
add_action('wp_enqueue_scripts', 'roots_scripts', 100);

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



