<?php
/**
 * URL rewriting and addition of HTML5 Boilerplate's .htaccess
 *
 * Rewrites currently do not happen for child themes (or network installs)
 * @todo https://github.com/retlehs/roots/issues/461
 *
 * Rewrite:
 *   /wp-content/themes/themename/css/ to /css/
 *   /wp-content/themes/themename/js/  to /js/
 *   /wp-content/themes/themename/img/ to /img/
 *   /wp-content/plugins/              to /plugins/
 *
 * If you aren't using Apache, alternate configuration settings can be found in the wiki.
 *
 * @link https://github.com/retlehs/roots/wiki/Nginx
 * @link https://github.com/retlehs/roots/wiki/Lighttpd
 */

if (stristr($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS/7.5') ) {


function roots_clean_urls( $content )
{
  if ( strpos( $content, FULL_RELATIVE_PLUGIN_PATH ) === 0 ) {
    return str_replace( FULL_RELATIVE_PLUGIN_PATH, WP_BASE . '/plugins', $content );
  } //strpos( $content, FULL_RELATIVE_PLUGIN_PATH ) === 0
  else {
    return str_replace( '/' . THEME_PATH, '', $content );
  }
}
function gizmo_iis7_delete_rewrite_rule()
{
  // If configuration file does not exist then rules also do not exist so there is nothing to delete
  global $wp_rewrite;
  $home_path = get_home_path();
  $filename  = $home_path . 'web.config';
  $prefix    = 'giz';
  if ( !file_exists( $filename ) )
    return true;
  if ( !class_exists( 'DOMDocument' ) )
    return false;
  $doc                     = new DOMDocument();
  $doc->preserveWhiteSpace = false;
  if ( $doc->load( $filename ) === false )
    return false;
  $xpath = new DOMXPath( $doc );
  $rules = $xpath->query( '/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'' . $prefix . '\')]' );
  for ( $i = 0; $i < $rules->length; $i++ ) {
    $child = $rules->item( $i );
    $child->parentNode->removeChild( $child );
  }
  /*$doc->formatOutput = true;*/
  saveDomDocument( $doc, $filename );
  return true;
}
function gizmo_iis7_rewrite_rule_exists( $filename, $nodename )
{
  if ( !file_exists( $filename ) )
    return false;
  if ( !class_exists( 'DOMDocument' ) )
    return false;
  $doc = new DOMDocument();
  if ( $doc->load( $filename ) === false )
    return false;
  $xpath = new DOMXPath( $doc );
  $rules = $xpath->query( '/configuration/system.webServer/rewrite/rules/rule[starts-with(@name,\'' . $nodename . '\')]' );
  if ( $rules->length == 0 )
    return false;
  else
    return true;
}
function roots_add_rewrites()
{
  global $wp_rewrite;
  $home_path              = get_home_path();
  $filename               = $home_path . 'web.config';
  $prefix                 = 'giz';
  $roots_new_non_wp_rules = array(
     'assets/css/(.*)' => THEME_PATH . '/assets/css/{R:1}',
    'assets/js/(.*)' => THEME_PATH . '/assets/js/{R:1}',
    'assets/img/(.*)' => THEME_PATH . '/assets/img/{R:1}',
    'plugins/(.*)' => RELATIVE_PLUGIN_PATH . '/{R:1}' 
  );
  $rule                   = "";
  foreach ( $roots_new_non_wp_rules as $k => $v ) {
    if ( gizmo_iis7_rewrite_rule_exists( $filename, $prefix . $k ) === false ) {
      $rule .= '<rule name="' . $prefix . $k . '"><match url="' . $k . '" /><action type="Rewrite" url="' . $v . '" /></rule>';
    } //gizmo_iis7_rewrite_rule_exists( $filename, $k ) === false
  } //$roots_new_non_wp_rules as $k => $v
  return iis7_add_rewrite_rule( $filename, $rule );
}
function giz_rewrites()
{
  global $wp_rewrite;
  if ( $wp_rewrite->using_permalinks() ) {
  } else {
    gizmo_iis7_delete_rewrite_rule();
  }
}
add_action( 'generate_rewrite_rules', 'roots_add_rewrites' );
add_action( 'admin_init', 'giz_rewrites' );
if ( !is_multisite() && !is_child_theme() && get_option( 'permalink_structure' ) ) {
  if ( !is_admin() && current_theme_supports( 'rewrite-urls' ) ) {
    $tags = array(
       'plugins_url',
      'bloginfo',
      'stylesheet_directory_uri',
      'template_directory_uri',
      'script_loader_src',
      'style_loader_src' 
    );
    add_filters( $tags, 'roots_clean_urls' );
  }
}
}