<?php

/**
 * Register sidebars and widgets
 */
function shoestrap_static_left_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'In-Navbar Widget Area', 'shoestrap' ),
		'id'            => 'navbar',
		'description'   => __( 'This widget area will show up in your NavBars. This is most useful when using a static-left navbar.', 'shoestrap' ),
		'before_widget' => '<div class="container">',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>',
	));
}

add_action( 'widgets_init', 'shoestrap_static_left_widgets_init', 40 );



function shoestrap_navbar_sidebar() {
	dynamic_sidebar( 'navbar' );
}
add_action( 'shoestrap_post_main_nav', 'shoestrap_navbar_sidebar' );