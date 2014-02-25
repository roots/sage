<?php

if ( !class_exists( 'ShoestrapBackground' ) ) {

	/**
	* The "Background" module
	*/
	class ShoestrapBackground {
		
		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 60 );
			add_action( 'wp_enqueue_scripts', array( $this, 'css'              ), 101 );
			add_action( 'plugins_loaded',     array( $this, 'upgrade_options'  )      );
		}

		/*
		 * The background core options for the Shoestrap theme
		 */
		function options( $sections ) {
			global $redux, $ss_settings;

			// Blog Options
			$section = array(
				'title' => __( 'Background', 'shoestrap' ),
				'icon'  => 'el-icon-photo icon-large',
			);   

			$fields[] = array(
				'title'       => __( 'General Background Color', 'shoestrap' ),
				'desc'        => __( 'Select a background color for your site. Default: #ffffff.', 'shoestrap' ),
				'id'          => 'html_bg',
				'default'     => array(
					'background-color' => isset( $ss_settings['html_color_bg'] ) ? $ss_settings['html_color_bg'] : '#ffffff',
				),
				'transparent' => false,
				'type'        => 'background',
				'output'      => 'body'
			);

			$fields[] = array(
				'title'       => __( 'Content Background', 'shoestrap' ),
				'desc'        => __( 'Background for the content area. Colors also affect input areas and other colors.', 'shoestrap' ),
				'id'          => 'body_bg',
				'default'     => array(
					'background-color'    => isset( $ss_settings['color_body_bg'] ) ? $ss_settings['color_body_bg'] : '#ffffff',
					'background-repeat'   => isset( $ss_settings['background_repeat'] ) ? $ss_settings['background_repeat'] : NULL,
					'background-position' => isset( $ss_settings['background_position_x'] ) ? $ss_settings['background_position_x'] . ' center' : NULL,
					'background-image'    => isset( $ss_settings['background_image']['url'] ) ? $ss_settings['background_image']['url'] : NULL,
				),
				'compiler'    => true,
				'transparent' => false,
				'type'        => 'background',
				'output'      => '.wrap.main-section .content .bg'
			);

			$fields[] = array(
				'title'     => __( 'Content Background Color Opacity', 'shoestrap' ),
				'desc'      => __( 'Select the opacity of your background color for the main content area so that background images will show through. Default: 100 (fully opaque)', 'shoestrap' ),
				'id'        => 'body_bg_opacity',
				'default'   => 100,
				'min'       => 0,
				'step'      => 1,
				'max'       => 100,
				'type'      => 'slider',
			);

			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_background_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;

		}

		function css() {
			$content_opacity  = shoestrap_getVariable( 'body_bg_opacity' );
			$bg_color         = shoestrap_getVariable( 'body_bg' );
			$bg_color         = isset( $bg_color['background-color'] ) ? $bg_color['background-color'] : '#ffffff';

			// The Content background color
			$content_bg = $content_opacity < 100 ? 'background:' . Shoestrap_Color::get_rgba( $bg_color, $content_opacity ) . ';' : '';

			$style = $content_opacity < 100 ? '.wrap.main-section div.content .bg {' . $content_bg . '}' : '';

			wp_add_inline_style( 'shoestrap_css', $style );
		}
	}
}

$background = new ShoestrapBackground();