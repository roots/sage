<?php // https://github.com/retlehs/roots/wiki

if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));

require_once locate_template('/inc/roots-activation.php');  // activation
require_once locate_template('/inc/roots-options.php');     // theme options
require_once locate_template('/inc/roots-cleanup.php');     // cleanup
require_once locate_template('/inc/roots-scripts.php');     // modified scripts output
require_once locate_template('/inc/roots-htaccess.php');    // rewrites for assets, h5bp htaccess
require_once locate_template('/inc/roots-hooks.php');       // hooks
require_once locate_template('/inc/roots-actions.php');     // actions
require_once locate_template('/inc/roots-widgets.php');     // widgets
require_once locate_template('/inc/roots-custom.php');      // custom functions

$roots_options = roots_get_theme_options();

// set the maximum 'Large' image width to the maximum grid width
// http://wordpress.stackexchange.com/q/11766
if (!isset($content_width)) {
  global $roots_options;
  $roots_css_framework = $roots_options['css_framework'];
  switch ($roots_css_framework) {
    case 'blueprint':   $content_width = 950;   break;
    case '960gs_12':    $content_width = 940;   break;
    case '960gs_16':    $content_width = 940;   break;
    case '960gs_24':    $content_width = 940;   break;
    case '1140':        $content_width = 1140;  break;
    case 'adapt':       $content_width = 940;   break;
    case 'bootstrap':   $content_width = 940;   break;
    case 'foundation':  $content_width = 980;   break;
    default:            $content_width = 950;   break;
  }
}

function roots_setup() {
  load_theme_textdomain('roots', get_template_directory() . '/lang');

  // tell the TinyMCE editor to use editor-style.css
  // if you have issues with getting the editor to show your changes then
  // use this instead: add_editor_style('editor-style.css?' . time());
  add_editor_style('editor-style.css');

  // http://codex.wordpress.org/Post_Thumbnails
  add_theme_support('post-thumbnails');
  // set_post_thumbnail_size(150, 150, false);

  // http://codex.wordpress.org/Post_Formats
  // add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

  // http://codex.wordpress.org/Function_Reference/register_nav_menus
  register_nav_menus(array(
    'primary_navigation' => __('Primary Navigation', 'roots'),
    'utility_navigation' => __('Utility Navigation', 'roots')
  ));
}

add_action('after_setup_theme', 'roots_setup');

// http://codex.wordpress.org/Function_Reference/register_sidebar
function roots_register_sidebars() {
  $sidebars = array('Sidebar', 'Footer');

  foreach($sidebars as $sidebar) {
    register_sidebar(
      array(
        'id'=> 'roots-' . strtolower($sidebar),
        'name' => __($sidebar, 'roots'),
        'description' => __($sidebar, 'roots'),
        'before_widget' => '<article id="%1$s" class="widget %2$s"><div class="container">',
        'after_widget' => '</div></article>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
      )
    );
  }
}

add_action('widgets_init', 'roots_register_sidebars');

// return post entry meta information
function roots_entry_meta() {
  echo '<time class="updated" datetime="'. get_the_time('c') .'" pubdate>'. sprintf(__('Posted on %s at %s.', 'roots'), get_the_date(), get_the_time()) .'</time>';
  echo '<p class="byline author vcard">'. __('Written by', 'roots') .' <a href="'. get_author_posts_url(get_the_author_meta('id')) .'" rel="author" class="fn">'. get_the_author() .'</a></p>';
}

?>