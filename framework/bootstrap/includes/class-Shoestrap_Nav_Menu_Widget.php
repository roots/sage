<?php


/**
 * Navigation Menu widget class
 *
 * @since 3.0.0
 */
class Shoestrap_Nav_Menu_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __('Use this widget to add one of your custom menus as a widget.', 'shoestrap') );
		parent::__construct( 'nav_menu', __('Custom Menu', 'shoestrap'), $widget_ops );
	}

	function widget($args, $instance) {
		global $ss_settings;
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;

		if ( ! $nav_menu ) {
			return;
		}

		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

		if ( $ss_settings['widgets_mode'] == 1 ) {
			echo '<section id="widget-menu-' . $instance["nav_menu"] . '" class="widget">';
		} else {
			echo $args['before_widget'];
		}

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}

		$menu_class = ( $ss_settings['inverse_navlist'] ) ? 'nav-list-inverse ' : '';

		$menu_class .= 'nav-list-' . $ss_settings['menus_class'];

		wp_nav_menu( array(
			'menu'        => $nav_menu,
			'depth'       => 2,
			'container'   => 'false',
			'menu_class'  => 'nav nav-list ' . $menu_class,
			'fallback_cb' => 'Shoestrap_Navlist_Walker::fallback',
			'walker'      => new Shoestrap_Navlist_Walker()
		) );
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		return $instance;
	}

	function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );

		// If no menus exists, direct the user to go and create some.
		if ( ! $menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.', 'shoestrap'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'shoestrap') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:', 'shoestrap'); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
		<?php
			foreach ( $menus as $menu ) {
				echo '<option value="' . $menu->term_id . '"'
					. selected( $nav_menu, $menu->term_id, false )
					. '>'. $menu->name . '</option>';
			}
		?>
			</select>
		</p>
		<?php
	}
}
