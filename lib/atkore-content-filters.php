<?php 
function replace_content($content)
{
  // search and replace -- make sure you include all your commas
  $textSearch = array(
   		"Eastern Wire + Conduit™",
     	"Eastern Wire and Conduit",
     	"Eastern Wire & Conduit",
     	"Armor-Duct™",
  		"SS-40",
  		"SS40",
  		"SS-30",
  		"SS30",
  		"SS-20",
  		"SS20",
  		"SS-15",
  		"SS15",
  		"SS®",
  		"POLYKOTE",
  		"POLYKOTE®",
  		"Polykote®",
  		"®",
  		"™",
  		"Protect N Duct",
  		"Protect-n-Duct",
  		"Protect-N-Duct™",
  		"KORTECH",
  		"KORTECH®"
  	);
  $textReplace = array(
  		"Eastern Wire + Conduit",
  		"Eastern Wire + Conduit",
  		"Eastern Wire + Conduit",
  		"Armor-Duct",
  		"SS 40",
  		"SS 40",
  		"SS 30",
  		"SS 30",
  		"SS 20",
  		"SS 20",
  		"SS 15",
  		"SS 15",
  		"SS",
  		"Polykote",
  		"Polykote",
  		"Polykote",
  		"<sup>&reg;</sup>",
  		"<sup>&trade;</sup>",
  		"Protect-N-Duct",
  		"Protect-N-Duct",
  		"Protect-N-Duct",
  		"Kortech",
  		"Kortech"
  	);
  $content = str_replace($textSearch, $textReplace, $content);
  return $content;
}
add_filter('the_content','replace_content', 0);
add_filter('the_title','replace_content', 0);
add_filter('the_field','replace_content', 0);
add_filter('get_field','replace_content', 0);
//add_filter('wp_nav_menu','replace_content', 0);


function add_reg($content)
{
  // search and replace
  $textSearch = array(
  		"Eastern Wire + Conduit",
  		"Armor-Duct",
  		"SS 40",
  		"SS 30",
  		"SS 20",
  		"SS 15",
  		"Polykote",
  		"Protect-N-Duct"
  	);
  $textReplace = array(
  		"Eastern Wire + Conduit<sup>&trade;</sup>",
  		"Armor-Duct<sup>&trade;</sup>",
  		"SS 40<sup>&reg;</sup>",
  		"SS 30<sup>&reg;</sup>",
  		"SS 20<sup>&reg;</sup>",
  		"SS 15<sup>&reg;</sup>",
  		"Polykote<sup>&reg;</sup>",
  		"Protect-N-Duct<sup>&trade;</sup>",
  		"Kortech<sup>&reg;</sup>"
  	);
  $content = str_replace($textSearch, $textReplace, $content);
  return $content;
}
add_filter('the_title','add_reg', 1);
add_filter('the_title','add_reg', 1);
add_filter('the_field','add_reg', 1);
add_filter('get_field','add_reg', 1);
//add_filter('wp_nav_menu','replace_content', 0);



// Add specific CSS class by filter
function atkore_class_names($classes) {
  $domain = $_SERVER[ 'SERVER_NAME' ];
	// add 'class-name' to the $classes array
	$classes[] = $domain;
	// return the $classes array
	return $classes;
}
add_filter('body_class','atkore_class_names');

// Remove brand class to prevent bootstrap collisions 
function atkore_post_names($classes) {
	$classes = array_diff($classes, array('brand',));
	return $classes;
}
add_filter('post_class','atkore_post_names');

// Add brand specific CSS to post class
function add_brand_class( $classes )
{
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'post_class', 'add_brand_class' );
