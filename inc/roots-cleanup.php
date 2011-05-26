<?php

$theme_name = next(explode('/themes/', get_stylesheet_directory()));
$theme_data = get_theme_data(ABSPATH . 'wp-content/themes/' . $theme_name . '/style.css');

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
if ((!defined('WP_ALLOW_MULTISITE') || (defined('WP_ALLOW_MULTISITE') && WP_ALLOW_MULTISITE !== true)) && $theme_data['Template'] === '') {
	add_action('generate_rewrite_rules', 'roots_add_rewrites');
	add_filter('plugins_url', 'roots_clean_plugins');
	add_filter('bloginfo', 'roots_clean_assets');
	add_filter('stylesheet_directory_uri', 'roots_clean_assets');
	add_filter('template_directory_uri', 'roots_clean_assets');
}

// redirect /?s to /search/
// http://txfx.net/wordpress-plugins/nice-search/
function roots_nice_search_redirect() {
	if (is_search() && strpos($_SERVER['REQUEST_URI'], '/wp-admin/') === false && strpos($_SERVER['REQUEST_URI'], '/search/') === false) {
		wp_redirect(home_url('/search/' . str_replace(array(' ', '%20'), array('+', '+'), urlencode(get_query_var( 's' )))), 301);
    exit();
	}
}
add_action('template_redirect', 'roots_nice_search_redirect');

function roots_search_query($escaped = true) {
	$query = apply_filters('roots_search_query', get_query_var('s'));
	if ($escaped) {
    	$query = esc_attr( $query );
	}
  	return urldecode($query);
}

add_filter('get_search_query', 'roots_search_query');

// root relative URLs for everything
// inspired by http://www.456bereastreet.com/archive/201010/how_to_make_wordpress_urls_root_relative/
// thanks to Scott Walkinshaw (scottwalkinshaw.com)
function roots_root_relative_url($input) {
	preg_match('/(https?:\/\/[^\/]+)/', $input, $matches);
	return str_replace(end($matches), '', $input);
}

add_filter('bloginfo_url', 'roots_root_relative_url');
add_filter('theme_root_uri', 'roots_root_relative_url');
add_filter('stylesheet_directory_uri', 'roots_root_relative_url');
add_filter('template_directory_uri', 'roots_root_relative_url');
add_filter('the_permalink', 'roots_root_relative_url');
add_filter('wp_list_pages', 'roots_root_relative_url');
add_filter('wp_list_categories', 'roots_root_relative_url');
add_filter('wp_nav_menu', 'roots_root_relative_url');
add_filter('wp_get_attachment_url', 'roots_root_relative_url');
add_filter('wp_get_attachment_link', 'roots_root_relative_url');
add_filter('the_content_more_link', 'roots_root_relative_url');
add_filter('the_tags', 'roots_root_relative_url');
add_filter('get_pagenum_link', 'roots_root_relative_url');
add_filter('get_comment_link', 'roots_root_relative_url');
add_filter('month_link', 'roots_root_relative_url');
add_filter('day_link', 'roots_root_relative_url');
add_filter('year_link', 'roots_root_relative_url');
add_filter('tag_link', 'roots_root_relative_url');

// Leaving plugins_url alone in admin to avoid potential issues (such as Gravity Forms)
if (!is_admin()) {
  add_filter('plugins_url', 'roots_root_relative_url');
}

// remove root relative URLs on any attachments in the feed
function roots_relative_feed_urls() {
	global $wp_query;
	if (is_feed()) {
		remove_filter('wp_get_attachment_url', 'roots_root_relative_url');
		remove_filter('wp_get_attachment_link', 'roots_root_relative_url');
	}
}

add_action('pre_get_posts', 'roots_relative_feed_urls' );

// remove dir and set lang="en" as default (rather than en-US)
function roots_language_attributes() {
	$attributes = array();
	$output = '';
	if (!defined('WP_LANG')) {
		$attributes[] = "lang=\"en\"";
	} else if ($lang = get_bloginfo('language')) {
		$attributes[] = "lang=\"$lang\"";
	}

	$output = implode(' ', $attributes);
	$output = apply_filters('roots_language_attributes', $output);
	echo $output;
}

add_filter('language_attributes', 'roots_language_attributes');


// remove WordPress version from RSS feed
function roots_no_generator() { return ''; }
add_filter('the_generator', 'roots_no_generator');


// cleanup wp_head
function roots_head_cleanup() {
	// http://wpengineer.com/1438/wordpress-header/
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
	
	remove_action('wp_head', 'noindex', 1);	
	function roots_noindex() {
		if ('0' == get_option('blog_public'))
		echo "<meta name=\"robots\" content=\"noindex,nofollow\">\n";
	}	
	add_action('wp_head', 'roots_noindex');
	
	remove_action('wp_head', 'rel_canonical');	
	function roots_rel_canonical() {
		if (!is_singular())
			return;
		global $wp_the_query;
		if (!$id = $wp_the_query->get_queried_object_id())
			return;
		$link = get_permalink($id);
		echo "<link rel=\"canonical\" href=\"$link\">\n";
	}
	add_action('wp_head', 'roots_rel_canonical');	
	
	// stop Gravity Forms from outputting CSS since it's linked in header.php
	if (class_exists('RGForms')) {
		update_option('rg_gforms_disable_css', 1);
	}

	// deregister l10n.js (new since WordPress 3.1)
	// why you might want to keep it: http://wordpress.stackexchange.com/questions/5451/what-does-l10n-js-do-in-wordpress-3-1-and-how-do-i-remove-it/5484#5484
	if (!is_admin()) {
		wp_deregister_script('l10n');
	}	
	
	// don't load jQuery through WordPress since it's linked in header.php
	if (!is_admin()) {
		wp_deregister_script('jquery');
		wp_register_script('jquery', '', '', '', true);
	}	
	
	// remove CSS from recent comments widget
	function roots_recent_comments_style() {
		global $wp_widget_factory;
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
	
	add_action('wp_head', 'roots_recent_comments_style', 1);

	// remove CSS from gallery
	function roots_gallery_style($css) {
		return preg_replace("#<style type='text/css'>(.*?)</style>#s", '', $css);
	}
	
	add_filter('gallery_style', 'roots_gallery_style');
}

add_action('init', 'roots_head_cleanup');

// cleanup gallery_shortcode()
remove_shortcode('gallery');

function roots_gallery_shortcode($attr) {
	global $post, $wp_locale;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'icontag'    => 'figure',
		'captiontag' => 'figcaption',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
		$attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if ( apply_filters( 'use_default_gallery_style', true ) )
		$gallery_style = "";
	$size_class = sanitize_html_class( $size );
	$gallery_div = "<section id='$selector' class='clearfix gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		// make the gallery link to the file by default instead of the attachment
		// thanks to Matt Price (countingrows.com)
    $link = isset($attr['link']) && $attr['link'] === 'attachment' ? 
      wp_get_attachment_link($id, $size, true, false) : 
      wp_get_attachment_link($id, $size, false, false);
		$output .= "
			<{$icontag} class=\"gallery-item\">
				$link
			";
		if ( $captiontag && trim($attachment->post_excerpt) ) {
			$output .= "
				<{$captiontag} class=\"gallery-caption\">
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</{$icontag}>";
		if ( $columns > 0 && ++$i % $columns == 0 )
			$output .= '';
	}

	$output .= "</section>\n";

	return $output;
}

add_shortcode('gallery', 'roots_gallery_shortcode');


// http://www.deluxeblogtips.com/2011/01/remove-dashboard-widgets-in-wordpress.html
function roots_remove_dashboard_widgets() {
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	remove_meta_box('dashboard_primary', 'dashboard', 'normal');
	remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
}

add_action('admin_init', 'roots_remove_dashboard_widgets');

// excerpt cleanup
function roots_excerpt_length($length) {
	return 40;
}

function roots_continue_reading_link() {
	return ' <a href="' . get_permalink() . '">' . __( 'Continued', 'roots' ) . '</a>';
}

function roots_auto_excerpt_more($more) {
	return ' &hellip;' . roots_continue_reading_link();
}

add_filter('excerpt_length', 'roots_excerpt_length');
add_filter('excerpt_more', 'roots_auto_excerpt_more');

?>
