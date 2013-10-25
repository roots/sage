<?php function remove_footer_admin () {
  echo 'Just Maintain';
}
add_filter('admin_footer_text', 'remove_footer_admin');

add_action('admin_head', 'my_custom_fonts');

function my_custom_fonts() {
  echo '<style>
    body, td, textarea, input, select {
      font-family: "Lucida Grande";
      font-size: 12px;
    } 
  </style>';
}

/*
 * Plugin Name: Mini Admin Bar
 * Plugin URI: http://www.netyou.co.il/
 * Description: Makes the admin bar a small button on the left and expands on hover.
 * Version: 1.0
 * Author: NetYou
 * Author URI: http://www.netyou.co.il/
 * License: MIT
 * Copyright: NetYou
*/
 
add_action('get_header', 'my_filter_head');
function my_filter_head() { remove_action('wp_head', '_admin_bar_bump_cb'); }
 
function my_admin_css() {
        if ( is_user_logged_in() ) {
        ?>
        <style type="text/css">
            #wpadminbar {
                width: 47px;
                min-width: auto;
                overflow: hidden;
                -webkit-transition: .4s width;
                -webkit-transition-delay: 1s;
                -moz-transition: .4s width;
                -moz-transition-delay: 1s;
                -o-transition: .4s width;
                -o-transition-delay: 1s;
                -ms-transition: .4s width;
                -ms-transition-delay: 1s;
                transition: .4s width;
                transition-delay: 1s;
            }
            
            #wpadminbar:hover {
                width: 100%;
                overflow: visible;
                -webkit-transition-delay: 0;
                -moz-transition-delay: 0;
                -o-transition-delay: 0;
                -ms-transition-delay: 0;
                transition-delay: 0;
            }
        </style>
        <?php }
}
add_action('wp_head', 'my_admin_css');

?>