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
				'title'     => __( 'General Background Color', 'shoestrap' ),
				'desc'      => __( 'Select a background color for your site. Default: #ffffff.', 'shoestrap' ),
				'id'        => 'html_color_bg',
				'default'   => '#ffffff',
				'customizer'=> array(),
				'transparent'=> false,
				'type'      => 'color',
			);

			$fields[] = array(
				'title'     => __( 'Content Background Color', 'shoestrap' ),
				'desc'      => __( 'Select a background color for your site\'s content area. Default: #ffffff.', 'shoestrap' ),
				'id'        => 'color_body_bg',
				'default'   => '#ffffff',
				'compiler'  => true,
				'customizer'=> array(),
				'transparent'=> false,
				'type'      => 'color',
			);

			$fields[] = array(
				'title'     => __( 'Content Background Color Opacity', 'shoestrap' ),
				'desc'      => __( 'Select the opacity of your background color for the main content area so that background images and patterns will show through. Default: 100 (fully opaque)', 'shoestrap' ),
				'id'        => 'color_body_bg_opacity',
				'default'   => 100,
				'min'       => 0,
				'step'      => 1,
				'max'       => 100,
				'type'      => 'slider',
			);

			$fields[] = array(
				'title'     => 'Background Images',
				'id'        => 'help4',
				'desc'      => __( 'If you want a background image, you can select one here.
												You can either upload a custom image, or use one of our pre-defined image patterns.
												If you both upload a custom image and select a pattern, your custom image will override the selected pattern.
												Please note that the image only applies to the area on the right and left of the main content area,
												to ensure better content readability. You can also set the background position to be fixed or scroll!', 'shoestrap' ),
				'type'      => 'info'
			);

			$fields[] = array(
				'title'     => __( 'Use a Background Image', 'shoestrap' ),
				'desc'      => __( 'Enable this option to upload a custom background image for your site. This will override any patterns you may have selected. Default: OFF.', 'shoestrap' ),
				'id'        => 'background_image_toggle',
				'default'   => 0,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Upload a Custom Background Image', 'shoestrap' ),
				'desc'      => __( 'Upload a Custom Background image using the media uploader, or define the URL directly.', 'shoestrap' ),
				'id'        => 'background_image',
				'required'  => array('background_image_toggle','=',array('1')),
				'default'   => '',
				'type'      => 'media',
				'customizer'=> array(),
			);

			$fields[] = array(
				'title'     => __( 'Background position', 'shoestrap' ),
				'desc'      => __( 'Changes how the background image or pattern is displayed from scroll to fixed position. Default: Fixed.', 'shoestrap' ),
				'id'        => 'background_fixed_toggle',
				'default'   => 1,
				'on'        => __( 'Fixed', 'shoestrap' ),
				'off'       => __( 'Scroll', 'shoestrap' ),
				'type'      => 'switch',
				'required'  => array('background_image_toggle','=',array('1')),
			);

			$fields[] = array(
				'title'     => __( 'Background Image Positioning', 'shoestrap' ),
				'desc'      => __( 'Allows the user to modify how the background displays. By default it is full width and stretched to fill the page. Default: Full Width.', 'shoestrap' ),
				'id'        => 'background_image_position_toggle',
				'default'   => 0,
				'required'  => array('background_image_toggle','=',array('1')),
				'on'        => __( 'Custom', 'shoestrap' ),
				'off'       => __( 'Full Width', 'shoestrap' ),
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Background Repeat', 'shoestrap' ),
				'desc'      => __( 'Select how (or if) the selected background should be tiled. Default: Tile', 'shoestrap' ),
				'id'        => 'background_repeat',
				'required'  => array('background_image_position_toggle','=',array('1')),
				'default'   => 'repeat',
				'type'      => 'select',
				'options'   => array(
					'no-repeat'  => __( 'No Repeat', 'shoestrap' ),
					'repeat'     => __( 'Tile', 'shoestrap' ),
					'repeat-x'   => __( 'Tile Horizontally', 'shoestrap' ),
					'repeat-y'   => __( 'Tile Vertically', 'shoestrap' ),
				),
			);

			$fields[] = array(
				'title'     => __( 'Background Alignment', 'shoestrap' ),
				'desc'      => __( 'Select how the selected background should be horizontally aligned. Default: Left', 'shoestrap' ),
				'id'        => 'background_position_x',
				'required'  => array('background_image_position_toggle','=',array('1')),
				'default'   => 'repeat',
				'type'      => 'select',
				'options'   => array(
					'left'    => __( 'Left', 'shoestrap' ),
					'right'   => __( 'Right', 'shoestrap' ),
					'center'  => __( 'Center', 'shoestrap' ),
				),
			);

			$fields[] = array(
				'title'     => __( 'Use a Background Pattern', 'shoestrap' ),
				'desc'      => __( 'Select one of the already existing Background Patterns. Default: OFF.', 'shoestrap' ),
				'id'        => 'background_pattern_toggle',
				'default'   => 0,
				'type'      => 'switch'
			);

			$fields[] = array(
				'title'     => __( 'Choose a Background Pattern', 'shoestrap' ),
				'desc'      => __( 'Select a background pattern.', 'shoestrap' ),
				'id'        => 'background_pattern',
				'required'  => array('background_pattern_toggle','=',array('1')),
				'default'   => '',
				'tiles'     => true,
				'type'      => 'image_select',
				'options'   => $bg_pattern_images,
			);
			
			$section['fields'] = $fields;

			$section = apply_filters( 'shoestrap_module_background_options_modifier', $section );
			
			$sections[] = $section;
			return $sections;

		}

		function css() {

			$image_toggle     = shoestrap_getVariable( 'background_image_toggle' );
			$bg_img           = shoestrap_getVariable( 'background_image' );
			$pattern_toggle   = shoestrap_getVariable( 'background_pattern_toggle' );
			$bg_pattern       = shoestrap_getVariable( 'background_pattern' );
			$html_bg          = shoestrap_getVariable( 'html_color_bg' );
			$bg_color         = shoestrap_getVariable( 'color_body_bg' );
			$content_opacity  = shoestrap_getVariable( 'color_body_bg_opacity' );
			$repeat           = shoestrap_getVariable( 'background_repeat' );
			$position         = shoestrap_getVariable( 'background_position_x', 'left' );
			$fixed            = shoestrap_getVariable( 'background_image_position_toggle' );

			// Do not process if there is no need to.
			if ( $image_toggle == 0 && $pattern_toggle == 0 && $bg_color == $html_bg )
				return;

			$background = ( $pattern_toggle && !empty( $bg_pattern ) ) ? set_url_scheme( $bg_pattern ) : '';
			$background = ( $image_toggle && $bg_img != '' ) ? set_url_scheme( $bg_img['url'] ) : $background;

			// The Body background color
			$html_bg    = '#' . str_replace( '#', '', $html_bg ) . ';';

			// The Content background color
			$content_bg = '#' . str_replace( '#', '', $bg_color ) . ';';
			$content_bg .= ( $content_opacity != 100 ) ? 'background:' . ShoestrapColor::get_rgba( $content_bg, $content_opacity ) . ';' : '';

			$repeat  = ( !in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) ) ? 'repeat' : $repeat;
			$repeat .= ( $repeat == 'no-repeat' ) ? 'background-size: auto;' : '';

			$position = ( !in_array( $position, array( 'center', 'right', 'left' ) ) ) ? 'left' : $position;

			$style = '';

			if ( ( $image_toggle == 1 || $pattern_toggle == 1 ) && !empty( $background ) ) {

				$style .= 'body {';

				// Add the background image
				$style .= 'background-image: url( "' . $background . '" );';

				// Add the body background color
				$style .= ( $bg_color != $html_bg ) ? 'background-color: ' . $html_bg . ';' : '';

				// Apply fixed positioning for background when needed
				$style .= ( shoestrap_getVariable( 'background_fixed_toggle' ) == 1 ) ? 'background-attachment: fixed;' : '';

				if ( $image_toggle == 1 ) {
					// Background image positioning
					if ( $fixed == 0 ) {
						// cover
						$style .= 'background-size: cover;';
						$style .= '-webkit-background-size: cover;';
						$style .= '-moz-background-size: cover;';
						$style .= '-o-background-size: cover;';
						$style .= 'background-position: 50% 50%;';
					} else {
						$style .= ' background-repeat: ' . $repeat . ';';
						$style .= ' background-position: top ' . $position . ';';
					}
				}
				$style .= '}';
			} else {
				// Add the body background color
				$style .= ( $bg_color != $html_bg ) ? 'body { background-color: ' . $html_bg . '; }' : '';
			}

			$style .= ( $bg_color != $html_bg ) ? '.wrap.main-section .content .bg { background: ' . $content_bg . '; }' : '';

			wp_add_inline_style( 'shoestrap_css', $style );
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		public static function variables() {
			$bg      = shoestrap_getVariable( 'color_body_bg', true );
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