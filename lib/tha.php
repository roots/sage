<?php
/**
 * Theme Hook Alliance hook stub list with adoptation for roots
 *
 * @package 	themehookalliance
 * @version		1.0-draft-roots
 * @since		1.0-draft-roots
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, v2 (or newer)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

/**
 * Define the version of THA support.
 */
define( 'THA_HOOKS_VERSION', '1.0-draft-roots' );
add_theme_support( 'roots_tha_hooks', array( 'all' ) );
add_theme_support( 'tha_hooks', array( 'all' ) );

/**
 * Determines, whether the specific hook type is actually supported.
 *
 * Plugin developers should always check for the support of a <strong>specific</strong>
 * hook type before hooking a callback function to a hook of this type.
 *
 * Example:
 * <code>
 * 		if ( current_theme_supports( 'tha_hooks', 'header' ) )
 * 	  		add_action( 'tha_head_top', 'prefix_header_top' );
 * </code>
 *
 * @param bool $bool true
 * @param array $args The hook type being checked
 * @param array $registered All registered hook types
 *
 * @return bool
 */
function roots_current_theme_supports( $bool, $args, $registered ) {
    return in_array( $args[0], $registered[0] ) || in_array( 'all', $registered[0] );
}
add_filter( 'current_theme_supports-tha_hooks', 'tha_current_theme_supports', 10, 3 );

/**
 * HTML <html> hook
 * Special case, useful for <DOCTYPE>, etc.
 * $tha_supports[] = 'html;
 */
function roots_html_before() {
    do_action( 'roots_html_before' );
    do_action( 'tha_html_before' );
}
/**
 * HTML <body> hooks
 * $tha_supports[] = 'body';
 */
function roots_body_top() {
    do_action( 'roots_body_top' );
    do_action( 'tha_body_top' );
}

function roots_body_bottom() {
    do_action( 'roots_body_bottom' );
    do_action( 'tha_body_bottom' );
}

/**
 * HTML <head> hooks
 *
 * $tha_supports[] = 'head';
 */
function roots_head_top() {
    do_action( 'roots_head_top' );
    do_action( 'tha_head_top' );
}

function roots_head_bottom() {
    do_action( 'roots_head_bottom' );
    do_action( 'tha_head_bottom' );
}

/**
 * Semantic <header> hooks
 *
 * $tha_supports[] = 'header';
 */
function roots_header_before() {
    do_action( 'roots_header_before' );
    do_action( 'tha_header_before' );
}

function roots_header_after() {
    do_action( 'roots_header_after' );
    do_action( 'tha_header_after' );
}

function roots_header_top() {
    do_action( 'roots_header_top' );
    do_action( 'tha_header_top' );
}

function roots_header_bottom() {
    do_action( 'roots_header_bottom' );
    do_action( 'tha_header_bottom' );
}

/**
 * Semantic <content> hooks
 *
 * $tha_supports[] = 'content';
 */
function roots_content_before() {
    do_action( 'roots_content_before' );
    do_action( 'tha_content_before' );
}

function roots_content_after() {
    do_action( 'roots_content_after' );
    do_action( 'tha_content_after' );
}

function roots_content_top() {
    do_action( 'roots_content_top' );
    do_action( 'tha_content_top' );
}

function roots_content_bottom() {
    do_action( 'roots_content_bottom' );
    do_action( 'tha_content_bottom' );
}

/**
 * Semantic <entry> hooks
 *
 * $tha_supports[] = 'entry';
 */
function roots_entry_before() {
    do_action( 'roots_entry_before' );
    do_action( 'tha_entry_before' );
}

function roots_entry_after() {
    do_action( 'roots_entry_after' );
    do_action( 'tha_entry_after' );
}

function roots_entry_top() {
    do_action( 'roots_entry_top' );
    do_action( 'tha_entry_top' );
}

function roots_entry_bottom() {
    do_action( 'roots_entry_bottom' );
    do_action( 'tha_entry_bottom' );
}

/**
 * Comments block hooks
 *
 * $tha_supports[] = 'comments';
 */
function roots_comments_before() {
    do_action( 'roots_comments_before' );
    do_action( 'tha_comments_before' );
}

function roots_comments_after() {
    do_action( 'roots_comments_after' );
    do_action( 'tha_comments_after' );
}

/**
 * Semantic <sidebar> hooks
 *
 * $tha_supports[] = 'sidebar';
 */
function roots_sidebars_before() {
    do_action( 'roots_sidebars_before' );
    do_action( 'tha_sidebars_before' );
}

function roots_sidebars_after() {
    do_action( 'roots_sidebars_after' );
    do_action( 'tha_sidebars_after' );
}

function roots_sidebar_top() {
    do_action( 'roots_sidebar_top' );
    do_action( 'tha_sidebar_top' );
}

function roots_sidebar_bottom() {
    do_action( 'roots_sidebar_bottom' );
    do_action( 'tha_sidebar_bottom' );
}

/**
 * Semantic <footer> hooks
 *
 * $tha_supports[] = 'footer';
 */
function roots_footer_before() {
    do_action( 'roots_footer_before' );
    do_action( 'tha_footer_before' );
}

function roots_footer_after() {
    do_action( 'roots_footer_after' );
    do_action( 'tha_footer_after' );
}

function roots_footer_top() {
    do_action( 'roots_footer_top' );
    do_action( 'tha_footer_top' );
}

function roots_footer_bottom() {
    do_action( 'roots_footer_bottom' );
    do_action( 'tha_footer_bottom' );
}