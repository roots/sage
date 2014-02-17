<?php

if ( !class_exists( 'ShoestrapBackground' ) ) {

	/**
	* The "Background" module
	*/
	class ShoestrapBackground {
		
		function __construct() {
			add_filter( 'redux/options/' . SHOESTRAP_OPT_NAME . '/sections', array( $this, 'options' ), 60 );
			add_action( 'wp_enqueue_scripts', array( $this, 'css'              ), 101 );
			add_filter( 'shoestrap_compiler', array( $this, 'variables_filter' )      );
			add_action( 'plugins_loaded',     array( $this, 'upgrade_options'  )      );
		}

		/**
		 * Migrate some deprecated options to their new versions.
		 */
		function upgrade_options( $context ) {
			// Get an array of all the options
			$settings = get_option( SHOESTRAP_OPT_NAME );

			if ( ( $settings['color_body_bg'] && !$settings['body_bg'] ) || ( $settings['html_color_bg'] && !$settings['html_bg'] ) ) {

				$html_color_bg                    = $settings['html_color_bg'];
				$color_body_bg                    = $settings['color_body_bg'];
				$color_body_bg_opacity            = $settings['color_body_bg_opacity'];
				$background_image_toggle          = $settings['background_image_toggle'];
				$background_image                 = $settings['background_image'];
				$background_fixed_toggle          = $settings['background_fixed_toggle'];
				$background_image_position_toggle = $settings['background_image_position_toggle'];
				$background_repeat                = $settings['background_repeat'];
				$background_position_x            = $settings['background_position_x'];
				$background_pattern_toggle        = $settings['background_pattern_toggle'];
				$background_pattern               = $settings['background_pattern'];

				if ( $background_pattern_toggle  == 1 && $background_image_toggle != 1 ) {
					$background_image  = $background_pattern;
					$background_repeat = 'repeat';
				}

				$body_bg = array(
					'background-color'    => $color_body_bg,
				);

				$html_bg = array(
					'background-color'    => $html_color_bg,
					'background-repeat'   => $background_repeat,
					'background-position' => $background_position_x . ' center',
					'background-image'    => $background_image
				);

				$settings['html_bg'] = $html_bg;
				$settings['body_bg'] = $body_bg;

				update_option( SHOESTRAP_OPT_NAME, $settings );
			}
		}

		/*
		 * The background core options for the Shoestrap theme
		 */
		function options( $sections ) {
			global $redux;

			//Background Patterns Reader
			$bg_pattern_images_path = SHOESTRAP_MODULES_PATH . '/background/assets/patterns';
			$bg_pattern_images_url  = SHOESTRAP_MODULES_URL . '/background/assets/patterns/';

			$bg_pattern_images      = array();

			if ( is_dir( $bg_pattern_images_path ) ) {
				if ( $bg_pattern_images_dir = opendir( $bg_pattern_images_path ) ) {
					$bg_pattern_images = array();

					while ( ( $bg_pattern_images_file = readdir( $bg_pattern_images_dir ) ) !== false ) {
						if( stristr( $bg_pattern_images_file, '.png' ) !== false || stristr( $bg_pattern_images_file, '.jpg' ) !== false )
							array_push( $bg_pattern_images, $bg_pattern_images_url . $bg_pattern_images_file );
					}
				}
			}

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
					'background-color' => '#ffffff'
				),
				'customizer'  => array(),
				'transparent' => false,
				'type'        => 'background',
				'output'      => 'body'
			);

			$fields[] = array(
				'title'       => __( 'Content Background', 'shoestrap' ),
				'desc'        => __( 'Background for the content area. Colors also affect input areas and other colors.', 'shoestrap' ),
				'id'          => 'body_bg',
				'default'     => array(
					'background-color' => '#ffffff'
				),
				'compiler'    => true,
				'transparent' => false,
				'customizer'  => array(),
				'type'        => 'background',
				'output'      => '.wrap.main-section .content .bg'
			);

			$fields[] = array(
				'title'     => __( 'Content Background Color Opacity', 'shoestrap' ),
				'desc'      => __( 'Select the opacity of your background color for the main content area so that background images and patterns will show through. Default: 100 (fully opaque)', 'shoestrap' ),
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
			$content_bg = $content_opacity < 100 ? 'background:' . ShoestrapColor::get_rgba( $bg_color, $content_opacity ) . ';' : '';

			$style = $content_opacity < 100 ? '.wrap.main-section div.content .bg {' . $content_bg . '}' : '';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		public static function variables() {
			$bg      = shoestrap_getVariable( 'body_bg', true );
			$bg      = isset( $bg_color['background-color'] ) ? $bg_color['background-color'] : '#ffffff';
			$body_bg = '#' . str_replace( '#', '', ShoestrapColor::sanitize_hex( $bg ) );

			// Calculate the gray shadows based on the body background.
			// We basically create 2 "presets": light and dark.
			if ( ShoestrapColor::get_brightness( $body_bg ) > 80 ) {
				$gray_darker  = 'lighten(#000, 13.5%)';
				$gray_dark    = 'lighten(#000, 20%)';
				$gray         = 'lighten(#000, 33.5%)';
				$gray_light   = 'lighten(#000, 60%)';
				$gray_lighter = 'lighten(#000, 93.5%)';
			} else {
				$gray_darker  = 'darken(#fff, 13.5%)';
				$gray_dark    = 'darken(#fff, 20%)';
				$gray         = 'darken(#fff, 33.5%)';
				$gray_light   = 'darken(#fff, 60%)';
				$gray_lighter = 'darken(#fff, 93.5%)';
			}

			$bg_brightness = ShoestrapColor::get_brightness( $body_bg );

			$table_bg_accent      = $bg_brightness > 50 ? 'darken(@body-bg, 2.5%)'    : 'lighten(@body-bg, 2.5%)';
			$table_bg_hover       = $bg_brightness > 50 ? 'darken(@body-bg, 4%)'      : 'lighten(@body-bg, 4%)';
			$table_border_color   = $bg_brightness > 50 ? 'darken(@body-bg, 13.35%)'  : 'lighten(@body-bg, 13.35%)';
			$input_border         = $bg_brightness > 50 ? 'darken(@body-bg, 20%)'     : 'lighten(@body-bg, 20%)';
			$dropdown_divider_top = $bg_brightness > 50 ? 'darken(@body-bg, 10.2%)'   : 'lighten(@body-bg, 10.2%)';

			$variables = '';

			// Calculate grays
			$variables .= '@gray-darker:            ' . $gray_darker . ';';
			$variables .= '@gray-dark:              ' . $gray_dark . ';';
			$variables .= '@gray:                   ' . $gray . ';';
			$variables .= '@gray-light:             ' . $gray_light . ';';
			$variables .= '@gray-lighter:           ' . $gray_lighter . ';';

			// The below are declared as #fff in the default variables.
			$variables .= '@body-bg:                     ' . $body_bg . ';';
			$variables .= '@component-active-color:          @body-bg;';
			$variables .= '@btn-default-bg:                  @body-bg;';
			$variables .= '@dropdown-bg:                     @body-bg;';
			$variables .= '@pagination-bg:                   @body-bg;';
			$variables .= '@progress-bar-color:              @body-bg;';
			$variables .= '@list-group-bg:                   @body-bg;';
			$variables .= '@panel-bg:                        @body-bg;';
			$variables .= '@panel-primary-text:              @body-bg;';
			$variables .= '@pagination-active-color:         @body-bg;';
			$variables .= '@pagination-disabled-bg:          @body-bg;';
			$variables .= '@tooltip-color:                   @body-bg;';
			$variables .= '@popover-bg:                      @body-bg;';
			$variables .= '@popover-arrow-color:             @body-bg;';
			$variables .= '@label-color:                     @body-bg;';
			$variables .= '@label-link-hover-color:          @body-bg;';
			$variables .= '@modal-content-bg:                @body-bg;';
			$variables .= '@badge-color:                     @body-bg;';
			$variables .= '@badge-link-hover-color:          @body-bg;';
			$variables .= '@badge-active-bg:                 @body-bg;';
			$variables .= '@carousel-control-color:          @body-bg;';
			$variables .= '@carousel-indicator-active-bg:    @body-bg;';
			$variables .= '@carousel-indicator-border-color: @body-bg;';
			$variables .= '@carousel-caption-color:          @body-bg;';
			$variables .= '@close-text-shadow:       0 1px 0 @body-bg;';
			$variables .= '@input-bg:                        @body-bg;';
			$variables .= '@nav-open-link-hover-color:       @body-bg;';

			// These are #ccc
			// We re-calculate the color based on the gray values above.
			$variables .= '@btn-default-border:            mix(@gray-light, @gray-lighter);';
			$variables .= '@input-border:                  mix(@gray-light, @gray-lighter);';
			$variables .= '@popover-fallback-border-color: mix(@gray-light, @gray-lighter);';
			$variables .= '@breadcrumb-color:              mix(@gray-light, @gray-lighter);';
			$variables .= '@dropdown-fallback-border:      mix(@gray-light, @gray-lighter);';

			$variables .= '@table-bg-accent:    ' . $table_bg_accent . ';';
			$variables .= '@table-bg-hover:     ' . $table_bg_hover . ';';
			$variables .= '@table-border-color: ' . $table_border_color . ';';

			$variables .= '@legend-border-color: @gray-lighter;';
			$variables .= '@dropdown-divider-bg: @gray-lighter;';

			$variables .= '@dropdown-link-hover-bg: @table-bg-hover;';
			$variables .= '@dropdown-caret-color:   @gray-darker;';

			$variables .= '@nav-tabs-border-color:                   @table-border-color;';
			$variables .= '@nav-tabs-active-link-hover-border-color: @table-border-color;';
			$variables .= '@nav-tabs-justified-link-border-color:    @table-border-color;';

			$variables .= '@pagination-border:          @table-border-color;';
			$variables .= '@pagination-hover-border:    @table-border-color;';
			$variables .= '@pagination-disabled-border: @table-border-color;';

			$variables .= '@tooltip-bg: darken(@gray-darker, 15%);';

			$variables .= '@popover-arrow-outer-fallback-color: @gray-light;';

			$variables .= '@modal-content-fallback-border-color: @gray-light;';
			$variables .= '@modal-backdrop-bg:                   darken(@gray-darker, 15%);';
			$variables .= '@modal-header-border-color:           lighten(@gray-lighter, 12%);';

			$variables .= '@progress-bg: ' . $table_bg_hover . ';';

			$variables .= '@list-group-border:   ' . $table_border_color . ';';
			$variables .= '@list-group-hover-bg: ' . $table_bg_hover . ';';

			$variables .= '@list-group-link-color:         @gray;';
			$variables .= '@list-group-link-heading-color: @gray-dark;';

			$variables .= '@panel-inner-border:       @list-group-border;';
			$variables .= '@panel-footer-bg:          @list-group-hover-bg;';
			$variables .= '@panel-default-border:     @table-border-color;';
			$variables .= '@panel-default-heading-bg: @panel-footer-bg;';

			$variables .= '@thumbnail-border: @list-group-border;';

			$variables .= '@well-bg: @table-bg-hover;';

			$variables .= '@breadcrumb-bg: @table-bg-hover;';

			$variables .= '@close-color: darken(@gray-darker, 15%);';

			return $variables;
		}

		/**
		 * Add the variables to the compiler
		 */
		function variables_filter( $variables ) {
			return $variables . self::variables();
		}
	}
}

$background = new ShoestrapBackground();