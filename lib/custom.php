<?php
/**
 * Custom functions, all this is optional
 * Mosly cleaning up the admin interface
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
//    Change the IP address to your servers local IP!    
//
//////////////////////////////////////////////////////////////////////


// function livereload() {
//   wp_register_script('livereload', 'http://192.168.0.100:35729/livereload.js?snipver=1', array(), null, true);
//   wp_enqueue_script('livereload');
// }
// add_action('wp_enqueue_scripts', 'livereload');











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
//		Removes meta boxes from post
//
//////////////////////////////////////////////////////////////////////



function remove_meta_boxes() {
	remove_meta_box( 'pageparentdiv' , 'page', 'normal');
	remove_meta_box( 'tagsdiv-post_tag', 'post', 'normal');
	remove_meta_box( 'categorydiv', 'post', 'normal');
}
add_action('admin_menu', 'remove_meta_boxes');










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







