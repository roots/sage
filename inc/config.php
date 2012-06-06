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

// Set up the config User Interface
function roots_config_ui() {
  // Let's make the config page visible to everyone who has the correct permissions
  add_theme_page('Configuration', 'Configuration', 'edit_theme_options', 'roots-config', 'roots_config_ui_html');

  // Let's check to see if we need to do anything else, and if we don't, we'll terminate this function.
  if (!isset($_GET['page']) || !isset($_REQUEST['action']) || !current_user_can('edit_theme_options')) return false;

  if ($_GET['page'] == 'roots-config' && 'save' == $_REQUEST['action']) {
    // Since someone is trying to edit the config of the theme, let's update all of the config.

    $new_config = array(
      'wrap_classes'=> $_REQUEST[ 'wrap_classes' ]?$_REQUEST[ 'wrap_classes' ]:'container',
      'container_classes'=> $_REQUEST[ 'container_classes' ]?$_REQUEST[ 'container_classes' ]:'row',
      'content_width'=> $_REQUEST[ 'content_width' ]?$_REQUEST[ 'content_width' ]:40,
      'post_excerpt_length'=> $_REQUEST[ 'post_excerpt_length' ]?$_REQUEST[ 'post_excerpt_length' ]:40,
      'main_classes'=> $_REQUEST[ 'main_classes' ]?$_REQUEST[ 'main_classes' ]:'span8',
      'sidebar_classes'=> $_REQUEST[ 'sidebar_classes' ]?$_REQUEST[ 'sidebar_classes' ]:'span4',
      'fullwidth_classes'=> $_REQUEST[ 'fullwidth_classes' ]?$_REQUEST[ 'fullwidth_classes' ]:'span12',
      'google_analytics_id'=> $_REQUEST[ 'google_analytics_id' ]?$_REQUEST[ 'google_analytics_id' ]:null,
    );

    update_option('roots_config', json_encode($new_config));

    // Now that they have been updated, we will go back to the home page
    wp_redirect(home_url());
    exit;
  }
}

// Load configuration options (or create them if they don't exist)
function roots_config_load() {
  // Retrieve the config options from the DB
  $roots_config = get_option('roots_config', 'fail');

  if ($roots_config=='fail') {
    // There aren't any config options in the DB. First define the defaults in JSON:
    $roots_config = '{"wrap_classes":"container","container_classes":"row","content_width":40,"post_excerpt_length":40,"main_classes":"span8","sidebar_classes":"span4","fullwidth_classes":"span12","google_analytics_id":""}';

    // And now update the config options in the DB
    update_option('roots_config', $roots_config);

  }

  $roots_config = json_decode($roots_config, true);
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


function roots_config_ui_html() {
  $roots_config = roots_config_load();?>
<form action="themes.php" method="get" style="margin-top: 20px">

  <input type="hidden" id="page" name="page" value="roots-config">
  <input type="hidden" id="action" name="action" value="save">

  <div style="margin-bottom: 15px">
    <label for="post_excerpt_length" style="width:170px;display:inline-block">Post Excerpt Length</label>
    <input type="text" id="post_excerpt_length" name="post_excerpt_length" value="<?php echo $roots_config['post_excerpt_length']?>">
  </div>

  <div style="margin-bottom: 15px">
    <label for="google_analytics_id" style="width:170px;display:inline-block">Google Analytics ID</label>
    <input type="text" id="google_analytics_id" name="google_analytics_id" value="<?php echo $roots_config['google_analytics_id']?>">
  </div>

  <div style="margin-bottom: 15px">
    <label for="wrap_classes" style="width:170px;display:inline-block">Wrap Classes</label>
    <input type="text" id="wrap_classes" name="wrap_classes" value="<?php echo $roots_config['wrap_classes']?>">
  </div>

  <div style="margin-bottom: 15px">
    <label for="container_classes" style="width:170px;display:inline-block">Container Classes</label>
    <input type="text" id="container_classes" name="container_classes" value="<?php echo $roots_config['container_classes']?>">
  </div>

  <div style="margin-bottom: 15px">
    <label for="main_classes" style="width:170px;display:inline-block">Main Classes</label>
    <input type="text" id="main_classes" name="main_classes" value="<?php echo $roots_config['main_classes']?>">
  </div>

  <div style="margin-bottom: 15px">
    <label for="sidebar_classes" style="width:170px;display:inline-block">Sidebar Classes</label>
    <input type="text" id="sidebar_classes" name="sidebar_classes" value="<?php echo $roots_config['sidebar_classes']?>">
  </div>

  <div style="margin-bottom: 15px">
    <label for="fullwidth_classes" style="width:170px;display:inline-block">Full-Width Classes</label>
    <input type="text" id="fullwidth_classes" name="fullwidth_classes" value="<?php echo $roots_config['fullwidth_classes']?>">
  </div>

  <div style="margin-bottom: 15px">
    <label for="content_width" style="width:170px;display:inline-block">Content Width</label>
    <input type="text" id="content_width" name="content_width" value="<?php echo $roots_config['content_width']?>">
  </div>

  <span id="submit">
   <input class="button-primary" value="Update configuration &raquo;" type="submit">
  </span>
</form>
<?php
}
// Display a link in the admin section to the config UI
add_action('admin_menu', 'roots_config_ui');