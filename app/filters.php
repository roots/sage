<?php

namespace App;

/**
 * Add <body> classes
 */
add_filter( 'body_class', function ( array $classes ) {
	/** Add page slug if it doesn't exist */
	if ( is_single() || is_page() && ! is_front_page() ) {
		if ( ! in_array( basename( get_permalink() ), $classes ) ) {
			$classes[] = basename( get_permalink() );
		}
	}

	/** Add class if sidebar is active */
	if ( display_sidebar() ) {
		$classes[] = 'sidebar-primary';
	}

	/** Clean up class names for custom templates */
	$classes = array_map( function ( $class ) {
		return preg_replace( [ '/-blade(-php)?$/', '/^page-template-views/' ], '', $class );
	}, $classes );

	return array_filter( $classes );
} );

/**
 * Add "… Continued" to the excerpt
 */
add_filter( 'excerpt_more', function () {
	return ' &hellip; <a href="' . get_permalink() . '">' . __( 'Continued', 'sage' ) . '</a>';
} );

/**
 * Template Hierarchy should search for .blade.php files
 */
collect( [
	'index',
	'404',
	'archive',
	'author',
	'category',
	'tag',
	'taxonomy',
	'date',
	'home',
	'frontpage',
	'page',
	'paged',
	'search',
	'single',
	'singular',
	'attachment'
] )->map( function ( $type ) {
	add_filter( "{$type}_template_hierarchy", __NAMESPACE__ . '\\filter_templates' );
} );

/**
 * Render page using Blade
 */
add_filter( 'template_include', function ( $template ) {
	$data = collect( get_body_class() )->reduce( function ( $data, $class ) use ( $template ) {
		return apply_filters( "sage/template/{$class}/data", $data, $template );
	}, [] );
	if ( $template ) {
	/*	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
			echo template( 'woocommerce', $data );
		} else {*/
			echo template( $template, $data );
		//}

		return get_stylesheet_directory() . '/index.php';
	}

	return $template;
}, PHP_INT_MAX );

/**
 * Tell WordPress how to find the compiled path of comments.blade.php
 */
add_filter( 'comments_template', function ( $comments_template ) {
	$comments_template = str_replace(
		[ get_stylesheet_directory(), get_template_directory() ],
		'',
		$comments_template
	);

	return template_path( locate_template( [
		"views/{$comments_template}",
		$comments_template
	] ) ?: $comments_template );
}, 100 );