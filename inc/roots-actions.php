<?php 

add_action('roots_head', 'roots_1140_head');
add_action('roots_header_before', 'roots_1140_header_before');
add_action('roots_header_after', 'roots_1140_header_after');
add_action('roots_footer_before', 'roots_1140_footer_before');
add_action('roots_footer_after', 'roots_1140_footer_after');
add_action('roots_post_inside_before', 'roots_page_breadcrumb');

function roots_1140_head() {
	$options = roots_get_theme_options();
	$roots_css_framework = $options['css_grid_framework'];
	$template_uri = get_template_directory_uri();
	if ($roots_css_framework === '1140') {
		echo "<script src=\"$template_uri/js/css3-mediaqueries.js\"></script>";
	}	
}

function roots_1140_header_before() {
	$options = roots_get_theme_options();
	$roots_css_framework = $options['css_grid_framework'];
	if ($roots_css_framework === '1140') {
		echo "<div class=\"row\">";
	}	
}

function roots_1140_header_after() {
	$options = roots_get_theme_options();
	$roots_css_framework = $options['css_grid_framework'];
	if ($roots_css_framework === '1140') {
		echo "</div><!-- /.row -->";
		echo "<div class=\"row\">";
	}	
}

function roots_1140_footer_before() {
	$options = roots_get_theme_options();
	$roots_css_framework = $options['css_grid_framework'];
	if ($roots_css_framework === '1140') {
		echo "</div><!-- /.row -->";
		echo "<div class=\"row\">";
	}	
}

function roots_1140_footer_after() {
	$options = roots_get_theme_options();
	$roots_css_framework = $options['css_grid_framework'];
	if ($roots_css_framework === '1140') {
		echo "</div><!-- /.row -->";
	}	
}

function roots_page_breadcrumb() {
	global $post;
	if (function_exists('yoast_breadcrumb')) { 
		if (is_page() && $post->post_parent) { 
			yoast_breadcrumb('<p id="breadcrumbs">','</p>'); 
		} 
	}
	wp_reset_postdata();
}

?>