<?php
// Start off with hard-coded theme support
add_theme_support('root-relative-urls');
add_theme_support('rewrite-urls');
add_theme_support('h5bp-htaccess');
add_theme_support('bootstrap-responsive');
add_theme_support('bootstrap-top-navbar');

// Set the post revisions to 5 unless previously set to avoid DB bloat
if (!defined('WP_POST_REVISIONS')) { define('WP_POST_REVISIONS', 5); }

// Set constants that no one should ever have to modify
define('WP_BASE',                   wp_base_dir());
define('THEME_NAME',                next(explode('/themes/', get_template_directory())));
define('RELATIVE_PLUGIN_PATH',      str_replace(site_url() . '/', '', plugins_url()));
define('FULL_RELATIVE_PLUGIN_PATH', WP_BASE . '/' . RELATIVE_PLUGIN_PATH);
define('RELATIVE_CONTENT_PATH',     str_replace(site_url() . '/', '', content_url()));
define('THEME_PATH',                RELATIVE_CONTENT_PATH . '/themes/' . THEME_NAME);

define( 'HOME_URL_IS_ROOT', str_replace('http://', '', home_url('/', 'http'))==$_SERVER["HTTP_HOST"] );


// Set up the config User Interface
function roots_config_ui() {
  // Let's make the config page visible to everyone who has the correct permissions
  add_theme_page('Configuration', 'Configuration', 'edit_theme_options', 'roots-config', 'roots_config_ui_html');

  // Let's check to see if we need to do anything else, and if we don't, we'll terminate this function.
  if (!isset($_GET['page']) || !isset($_REQUEST['action']) || !current_user_can('edit_theme_options')) return;

  if ($_GET['page'] == 'roots-config' && 'save' == $_REQUEST['action']) {
    // Since someone is trying to edit the config of the theme, let's update all of the config.

    $new_config = array(
      'wrap_classes'=>$_REQUEST['wrap_classes']?$_REQUEST['wrap_classes']:'container',
      'container_classes'=>$_REQUEST['container_classes']?$_REQUEST['container_classes']:'row',
      'content_width'=>$_REQUEST['content_width']?$_REQUEST['content_width']:40,
      'post_excerpt_length'=>$_REQUEST['post_excerpt_length']?$_REQUEST['post_excerpt_length']:40,
      'main_classes'=>$_REQUEST['main_classes']?$_REQUEST['main_classes']:'span8',
      'sidebar_classes'=>$_REQUEST['sidebar_classes']?$_REQUEST['sidebar_classes']:'span4',
      'fullwidth_classes'=>$_REQUEST['fullwidth_classes']?$_REQUEST['fullwidth_classes']:'span12',
      'google_analytics_id'=>$_REQUEST['google_analytics_id']?$_REQUEST['google_analytics_id']:null,
      'remove_admin_bar'=>$_REQUEST['remove_admin_bar']?$_REQUEST['remove_admin_bar']:'y',
      'brand_hover_glow'=>$_REQUEST['brand_hover_glow']?$_REQUEST['brand_hover_glow']:'n',
      'brand_hover_glow_nohover_color'=>$_REQUEST['brand_hover_glow_nohover_color']?str_replace('#', '', $_REQUEST['brand_hover_glow_nohover_color']):'f0f0f0',
      'brand_hover_glow_color'=>$_REQUEST['brand_hover_glow_color']?str_replace('#', '', $_REQUEST['brand_hover_glow_color']):'efefef',
      'brand_hover_glow_blur'=>$_REQUEST['brand_hover_glow_blur']?str_replace('px', '', $_REQUEST['brand_hover_glow_blur']):'30',
      'ios_scroll'=>$_REQUEST['ios_scroll']?$_REQUEST['ios_scroll']:'y'
    );

    update_option('roots_config', json_encode($new_config));

    // Now that they have been updated, we will notify them that the changes where successfully applied.
    if ( !defined( 'ROOTS_CONFIG_UPDATED' ) ) define( 'ROOTS_CONFIG_UPDATED', true );
  }
}

// Load configuration options (or create them if they don't exist)
function roots_config_load() {
  // Retrieve the config options from the DB
  $roots_config = get_option('roots_config', 'fail');

  if ($roots_config=='fail') {
    // There aren't any config options in the DB. First define the defaults in JSON:
    $roots_config = '{"wrap_classes":"container","container_classes":"row","content_width":40,"post_excerpt_length":40,"main_classes":"span8","sidebar_classes":"span4","fullwidth_classes":"span12","google_analytics_id":"","remove_admin_bar":"y","brand_hover_glow":"n","brand_hover_glow_nohover_color":"f0f0f0","brand_hover_glow_color":"efefef","brand_hover_glow_blur":"30","ios_scroll":"y"}';
    // And now update the config options in the DB
    update_option('roots_config', $roots_config);
  }
  else {
    $roots_config = json_decode($roots_config, true);
    if (count($roots_config)<14) {
      // 14 is the number of config options it should have. If it has less, then this is an older version of roots.
      $new_config = array(
        'wrap_classes'=>isset($roots_config['wrap_classes'])?$roots_config['wrap_classes']:'container',
        'container_classes'=>isset($roots_config['container_classes'])?$roots_config['container_classes']:'row',
        'content_width'=>isset($roots_config['content_width'])?$roots_config['content_width']:40,
        'post_excerpt_length'=>isset($roots_config['post_excerpt_length'])?$roots_config['post_excerpt_length']:40,
        'main_classes'=>isset($roots_config['main_classes'])?$roots_config['main_classes']:'span8',
        'sidebar_classes'=>isset($roots_config['sidebar_classes'])?$roots_config['sidebar_classes']:'span4',
        'fullwidth_classes'=>isset($roots_config['fullwidth_classes'])?$roots_config['fullwidth_classes']:'span12',
        'google_analytics_id'=>isset($roots_config['google_analytics_id'])?$roots_config['google_analytics_id']:null,
        'remove_admin_bar'=>isset($roots_config['remove_admin_bar'])?$roots_config['remove_admin_bar']:'y',
        'brand_hover_glow'=>isset($roots_config['brand_hover_glow'])?$roots_config['brand_hover_glow']:'n',
        'brand_hover_glow_nohover_color'=>isset($roots_config['brand_hover_glow_nohover_color'])?str_replace('#', '', $roots_config['brand_hover_glow_nohover_color']):'f0f0f0',
        'brand_hover_glow_color'=>isset($roots_config['brand_hover_glow_color'])?str_replace('#', '', $roots_config['brand_hover_glow_color']):'efefef',
        'brand_hover_glow_blur'=>isset($roots_config['brand_hover_glow_blur'])?str_replace('px', '', $roots_config['brand_hover_glow_blur']):'30',
        'ios_scroll'=>isset($roots_config['ios_scroll'])?$roots_config['ios_scroll']:'y'
      );
    }
  }
  return $roots_config;
}

$roots_config = roots_config_load();

define('WRAP_CLASSES',              $roots_config['wrap_classes']);
define('CONTAINER_CLASSES',         $roots_config['container_classes']);
// Set the content width based on the theme's design and stylesheet
if (!isset($content_width)) { $content_width = intval($roots_config['content_width']); }
define('POST_EXCERPT_LENGTH',       intval($roots_config['post_excerpt_length']));
define('MAIN_CLASSES',              $roots_config['main_classes']);
define('SIDEBAR_CLASSES',           $roots_config['sidebar_classes']);
define('FULLWIDTH_CLASSES',         $roots_config['fullwidth_classes']);
define('GOOGLE_ANALYTICS_ID',       $roots_config['google_analytics_id']);

if ($roots_config['remove_admin_bar']=='y') add_theme_support('header-remove-admin-bar');
if ($roots_config['brand_hover_glow']=='y') {
  define('BRAND_HOVER_GLOW_COLOR',    $roots_config['brand_hover_glow_color']);
  define('BRAND_HOVER_GLOW_BLUR',     $roots_config['brand_hover_glow_blur']);
  define('BRAND_HOVER_GLOW_NOHOVER_COLOR', $roots_config['brand_hover_glow_nohover_color']);
  add_theme_support('brand-hover-glow');
}
if ($roots_config['ios_scroll']=='y') add_theme_support('ios-scroll');


function roots_config_ui_html() {
  $roots_config = roots_config_load();
  wp_enqueue_style('roots-config-css', '/css/config.css');
  wp_enqueue_script('roots-config-js', '/js/config.js')?>
<form action="themes.php" method="get" style="margin-top: 15px" id="configform">
  <input type="hidden" id="page" name="page" value="roots-config"><input type="hidden" id="action" name="action" value="save">

  <?php echo defined('ROOTS_CONFIG_UPDATED')?'<p style="color:green;font-weight:bold">Changes successfully applied!</p>':'';?>

  <div style="margin-top:15px"><label for="remove_admin_bar">Remove Admin Bar</label><select id="remove_admin_bar" name="remove_admin_bar"><option value="y"<?php echo $roots_config['remove_admin_bar']=='y'?'Selected':''?>>Yes</option><option value="n"<?php echo $roots_config['remove_admin_bar']=='n'?'Selected':''?>>No</option></select></div>

  <div style="margin-bottom:0"><label for="brand_hover_glow"><abbr title="If this is set to Yes, then your brand will be displayed in pure white and will glow whenever someone hovers over it. You can modify the amout of blur the glow has by editing /templates/header-brand-hover-glow.php">Enable Brand Glow?</abbr></label><select id="brand_hover_glow" name="brand_hover_glow"><option value="y"<?php echo $roots_config['brand_hover_glow']=='y'?'Selected':''?>>Yes</option><option value="n"<?php echo $roots_config['brand_hover_glow']=='n'?'Selected':''?>>No</option></select></div>
  <div id="brand_hover_glow_options" class="advanced" style="display:<?php echo $roots_config['brand_hover_glow']=='n'?'none':'block'?>"><label for="brand_hover_glow_nohover_color">Color of brand</label><input type="text" id="brand_hover_glow_nohover_color" name="brand_hover_glow_nohover_color" value="<?php echo $roots_config['brand_hover_glow_nohover_color'];?>"><br><label for="brand_hover_glow_color">Color of glow</label><input type="text" id="brand_hover_glow_color" name="brand_hover_glow_color" value="<?php echo $roots_config['brand_hover_glow_color'];?>"><br><label for="brand_hover_glow_blur">Amount of blur applied</label><input type="text" id="brand_hover_glow_blur" name="brand_hover_glow_blur" value="<?php echo $roots_config['brand_hover_glow_blur'];?>"></div>

  <div style="margin-top:15px"><label for="ios_scroll">Hide address bar on iOS</label><select id="ios_scroll" name="ios_scroll"><option value="y"<?php echo $roots_config['ios_scroll']=='y'?'Selected':''?>>Yes</option><option value="n"<?php echo $roots_config['ios_scroll']=='n'?'Selected':''?>>No</option></select></div>
  <div><label for="post_excerpt_length">Post Excerpt Length</label><input type="text" id="post_excerpt_length" name="post_excerpt_length" value="<?php echo $roots_config['post_excerpt_length']?>"></div>
  <div><label for="google_analytics_id"><abbr title="It's something like UA-XXXXXXXX-X">Google Analytics ID</abbr></label><input type="text" id="google_analytics_id" name="google_analytics_id" value="<?php echo $roots_config['google_analytics_id']?>" placeholder="UA-XXXXXXXX-X"></div>
  <div><label for="wrap_classes">Wrap Classes</label><input type="text" id="wrap_classes" name="wrap_classes" value="<?php echo $roots_config['wrap_classes']?>"></div>
  <div><label for="container_classes">Container Classes</label><input type="text" id="container_classes" name="container_classes" value="<?php echo $roots_config['container_classes']?>"></div>
  <div><label for="main_classes">Main Classes</label><input type="text" id="main_classes" name="main_classes" value="<?php echo $roots_config['main_classes']?>"></div>
  <div><label for="sidebar_classes">Sidebar Classes</label><input type="text" id="sidebar_classes" name="sidebar_classes" value="<?php echo $roots_config['sidebar_classes']?>"></div>
  <div><label for="fullwidth_classes">Full-Width Classes</label><input type="text" id="fullwidth_classes" name="fullwidth_classes" value="<?php echo $roots_config['fullwidth_classes']?>"></div>
  <div><label for="content_width">Content Width</label><input type="text" id="content_width" name="content_width" value="<?php echo $roots_config['content_width']?>"></div>

  <span id="submit"><input class="button-primary" value="Update configuration &raquo;" type="submit"></span>
</form>
<?php
  flush();
}
// Display a link in the admin section to the config UI
add_action('admin_menu', 'roots_config_ui');