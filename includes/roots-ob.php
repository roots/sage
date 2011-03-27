<?php

// http://www.dagondesign.com/articles/wordpress-hook-for-entire-page-using-output-buffering/

function roots_callback($buffer) {
	if (class_exists('All_in_One_SEO_Pack')) { 
		$temp = preg_replace('/<!-- All in One[^>]+?>\s+/', '', $buffer);
		return preg_replace('/<!-- \/all in one seo pack -->\n/', '', $temp);
	} else {
		return $buffer;
	}
}

function roots_buffer_start() {
	ob_start('roots_callback');
}

function roots_buffer_end() {
	ob_end_flush();
}

add_action('wp_head', 'roots_buffer_start', -999);
add_action('wp_footer', 'roots_buffer_end');

?>