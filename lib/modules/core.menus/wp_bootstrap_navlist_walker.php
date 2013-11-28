<?php

/*
 * Class Name: wp_bootstrap_navlist_walker
 * GitHub URI: https://github.com/twittem/wp-bootstrap-navlist-walker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 3 navigation style in a custom theme using the WordPress built in menu manager.
 * Version: 1.0 RC1
 * Author: Edward McIntyre - @twittem
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

class wp_bootstrap_navlist_walker extends Walker_Nav_Menu {

	/**
	 * @see Walker::start_lvl()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<div id=\"nav-sublist\"><ul role=\"menu\" class=\"nav nav-sublist\">\n";
	}

	/**
	 * Ends the list of after the elements are added.
	 *
	 * @see Walker::end_lvl()
	 *
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu()
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= 0 == $depth ? "$indent</ul>\n" : "$indent</div></ul>\n";
	}

	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		// Check if the link it disables Disabled, Active or regular menu item.
		if ( stristr( $item->attr_title, 'disabled' ) )
			$output .= $indent . '<li role="presentation" class="disabled"><a name="' . esc_attr( $item->title ) . '">' . esc_attr( $item->title ) . '</a>';
		else
			$output .= $item->current ? $indent . '<li class="active">' : $indent . '<li>';

		$atts = array();
		$atts['title']  = ! empty( $item->title )	? $item->title	: '';
		$atts['target'] = ! empty( $item->target )	? $item->target	: '';
		$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';
		$atts['href'] 	= ! empty( $item->url ) 	? $item->url : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/*
		 * el-icons
		 * ===========
		 * We check to see there is a value in the attr_title property. If the attr_title
		 * property is NOT null or divider we apply it as the class name for the el-icon.
		 */
		if ( ! empty( $item->attr_title ) )
			$item_output .= '<a'. $attributes .'><span class="el-icon ' . esc_attr( $item->attr_title ) . '"></span>&nbsp;';
		else
			$item_output .= '<a'. $attributes .'>';

		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * @see Walker::start_el()
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
        if ( ! $element )
            return;
        
        //If parent is not current item, don't output children
        if( ! $element->current )
        	parent::unset_children( $element, $children_elements );

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

	/**
	 * Menu Fallback
	 * =============
	 * If this function is assigned to the wp_nav_menu's fallback_cb variable
	 * and a manu has not been assigned to the theme location in the WordPress
	 * menu manager the function with display nothing to a non-logged in user,
	 * and will add a link to the WordPress menu manager if logged in as an admin.
	 *
	 * @param array $args passed from the wp_nav_menu function.
	 *
	 */
	public static function fallback( $args ) {
		if ( current_user_can( 'manage_options' ) ) {

			extract( $args );

			$output = null;

			if ( $container ) {
				$output = '<' . $container;

				if ( $container_id )
					$output .= ' id="' . $container_id . '"';

				if ( $container_class )
					$output .= ' class="' . $container_class . '"';

				$output .= '>';
			}

			$output .= '<ul';

			if ( $menu_id )
				$output .= ' id="' . $menu_id . '"';

			if ( $menu_class )
				$output .= ' class="' . $menu_class . '"';

			$output .= '>';
			$output .= '<li><a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a></li>';
			$output .= '</ul>';

			if ( $container )
				$output .= '</' . $container . '>';

			echo $output;
		}
	}
}