<?php 

add_action('roots_head', 'roots_1140_head');
add_action('roots_header_before', 'roots_1140_header_before');
add_action('roots_header_after', 'roots_1140_header_after');
add_action('roots_footer_before', 'roots_1140_footer_before');
add_action('roots_footer_after', 'roots_1140_footer_after');

function roots_1140_head() {
	$template_uri = get_template_directory_uri();
	if (get_option('roots_css_framework') === '1140') {
		echo "<script src=\"$template_uri/js/css3-mediaqueries.js\"></script>";
	}	
}

function roots_1140_header_before() {
	if (get_option('roots_css_framework') === '1140') {
		echo "<div class=\"row\">";
	}	
}

function roots_1140_header_after() {
	if (get_option('roots_css_framework') === '1140') {
		echo "</div><!-- /.row -->";
		echo "<div class=\"row\">";
	}	
}

function roots_1140_footer_before() {
	if (get_option('roots_css_framework') === '1140') {
		echo "</div><!-- /.row -->";
		echo "<div class=\"row\">";
	}	
}

function roots_1140_footer_after() {
	if (get_option('roots_css_framework') === '1140') {
		echo "</div><!-- /.row -->";
	}	
}

?>