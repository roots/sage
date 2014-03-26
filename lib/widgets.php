<?php
/**
 * Register sidebars and widgets
 */
function shoestrap_widgets_init() {
	$class        = apply_filters( 'shoestrap_widgets_class', '' );
	$before_title = apply_filters( 'shoestrap_widgets_before_title', '<h3 class="widget-title">' );
	$after_title  = apply_filters( 'shoestrap_widgets_after_title', '</h3>' );

	// Sidebars
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'shoestrap' ),
		'id'            => 'sidebar-primary',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Secondary Sidebar', 'shoestrap' ),
		'id'            => 'sidebar-secondary',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));
}
add_action( 'widgets_init', 'shoestrap_widgets_init' );