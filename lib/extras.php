<?php
/**
 * Clean up the_excerpt()
 */
function roots_excerpt_more($more) {
  return ' &hellip; <a href="' . get_permalink() . '">' . __('Continued', 'roots') . '</a>';
}
add_filter('excerpt_more', 'roots_excerpt_more');

/**
 * Manage output of wp_title()
 */
function roots_wp_title($title) {
  if (is_feed()) {
    return $title;
  }

  $title .= get_bloginfo('name');

  return $title;
}
add_filter('wp_title', 'roots_wp_title', 10);














/**
 * Custom functions, all this is optional
 * Mosly cleaning up the admin interface.
 * Comment out what you don't need, and uncomment what you want.
 */



//
//		Adds foundations flex video container around oembed embeds
//
//////////////////////////////////////////////////////////////////////


add_filter('embed_oembed_html', 'embed_oembed', 99, 4);
function embed_oembed($html, $url, $attr, $post_id) {
  return '<div class="flex-video">' . $html . '</div>';
}







//
//		Fixes overlapping adminbar for Foundations top-bar
//
//////////////////////////////////////////////////////////////////////


add_action('wp_head', 'admin_bar_fix', 5);
function admin_bar_fix() {
  if( is_admin_bar_showing() ) {
    $output  = '<style type="text/css">'."\n\t";
    $output .= '@media screen and (max-width: 600px) {#wpadminbar { position: fixed !important; } }'."\n";
    $output .= '</style>'."\n";
    echo $output;
  }
}







//
//		Adds Foundation classes to next/prev buttons
//
//////////////////////////////////////////////////////////////////////


add_filter('next_posts_link_attributes', 'posts_link_attributes');
add_filter('previous_posts_link_attributes', 'posts_link_attributes');

function posts_link_attributes() {
    return 'class="button tiny"';
}













//
//    Adds the livereload script. Primarily for testing other devices on same network as web server
//    Change the IP address to the IP of the computer thats running the "gulp" command (likely your dev computer)  
//
//////////////////////////////////////////////////////////////////////


function livereload() {
  wp_register_script('livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
  wp_enqueue_script('livereload');
}

// Runs the livereload function if domain contains .dev — edit to fit your own needs
// $host = $_SERVER['HTTP_HOST']; 
// if (strpos($host,'.dev') !== false) {
    add_action('wp_enqueue_scripts', 'livereload');
// }








//
//		Removes default dashboard widgets
//
//////////////////////////////////////////////////////////////////////



function remove_dashboard_widgets() {
    global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	update_user_meta( get_current_user_id(), 'show_welcome_panel', false );
	remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );







//
//		Remove meta boxes from post & pages. Uncomment if you want
//    cleaner post and pages(like the attributes, tags and category)
//
//////////////////////////////////////////////////////////////////////



// function remove_meta_boxes() {
// 	remove_meta_box( 'pageparentdiv' , 'page', 'normal'); // Removes attributes page
// 	remove_meta_box( 'tagsdiv-post_tag', 'post', 'normal'); // Removes tags for post
// 	remove_meta_box( 'categorydiv', 'post', 'normal'); // Removes category for posts
// }
// add_action('do_meta_boxes', 'remove_meta_boxes');










//
//		Removes comments menu
//
//////////////////////////////////////////////////////////////////////


// function remove_menus(){
//   remove_menu_page( 'edit-comments.php' );
// }
// add_action( 'admin_menu', 'remove_menus' );













//
//		Removes Types (custom post type generator) marketing
//
//////////////////////////////////////////////////////////////////////


// function adminstyle() {
//    echo '<style type="text/css">
//            #wpcf-marketing { display: none;}
//          </style>';
// }
// add_action('admin_head', 'adminstyle');







