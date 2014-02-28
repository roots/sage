<?php

if ( !class_exists( 'SS_Framework_Bootstrap' ) ) {

	/**
	* The Bootstrap Framework module
	*/
	class SS_Framework_Bootstrap extends SS_Framework_Core {

		var $defines = array(
			// Layout
			'container'  => 'container',
			'row'        => 'row',
			'col-mobile' => 'col-xs',
			'col-tablet' => 'col-sm',
			'col-medium' => 'col-md',
			'col-large'  => 'col-lg',

			// Buttons
			'button'         => 'btn',
			'button-default' => 'btn-default',
			'button-primary' => 'btn-primary',
			'button-success' => 'btn-success',
			'button-info'    => 'btn-info',
			'button-warning' => 'btn-warning',
			'button-danger'  => 'btn-danger',
			'button-link'    => 'btn-link',

			'button-extra-small' => 'btn-xs',
			'button-small'       => 'btn-sm',
			'button-medium'      => null,
			'button-large'       => 'btn-lg',
			'button-extra-large' => 'btn-lg',

			'button-block'    => 'btn-block',
			'button-radius'   => null,
			'button-round'    => null,

			// Button-Groups
			'button-group'             => 'btn-group',
			'button-group-extra-small' => 'btn-group-xs',
			'button-group-small'       => 'btn-group-sm',
			'button-group-default'     => null,
			'button-group-large'       => 'btn-group-lg',
			'button-group-extra-large' => 'btn-group-lg',

			// Alerts
			'alert'         => 'alert',
			'alert-success' => 'alert-success',
			'alert-info'    => 'alert-info',
			'alert-warning' => 'alert-warning',
			'alert-danger'  => 'alert-danger',

			// Miscelaneous
			'clearfix' => '<div class="clearfix"></div>',
		);
		/**
		 * Class constructor
		 */
		function __construct() {
			global $ss_settings;

			if ( ! isset ( $ss_settings['color_brand_primary'] ) && ! empty( $ss_settings['color_brand_primary'] ) ) {
				add_filter( 'shoestrap_compiler', array( $this, 'styles_filter' ) );
			}

			if ( $ss_settings['navbar_social'] == 1 ) {
				if ( $ss_settings['navbar_social_style'] == 1 ) {
					add_action( 'shoestrap_inside_nav_end', array( $this, 'navbar_social_bar' ) );
				} else {
					add_action( 'shoestrap_inside_nav_end', array( $this, 'navbar_social_links' ) );
				}
			}
		}

		/**
		 * Makes a container
		 */
		function make_container() {

		}

		/**
		 * Column classes
		 */
		function column_classes( $sizes = array(), $return = 'array' ) {
			$classes = array();

			// Get the classes based on the $sizes array.
			foreach ( $sizes as $size => $columns ) {
				$classes[] = $this->defines['col-' . $size] . '-' . $columns;
			}

			if ( $return == 'array' ) {
				return $classes;
			} else {
				return implode( ' ', $classes );
			}

		}

		function button_group_classes( $size = 'medium', $type = null, $extra_classes = null ) {

			$classes = array();

			$classes[] = $this->defines['button-group'];

			// Get the proper class for button sizing from the framework definitions.
			if ( $size == 'extra-small' ) {
				$classes[] = $this->defines['button-group-extra-small'];
			} elseif ( $size == 'small' ) {
				$classes[] = $this->defines['button-group-small'];
			} elseif ( $size == 'medium' ) {
				$classes[] = $this->defines['button-group-medium'];
			} elseif ( $size == 'large' ) {
				$classes[] = $this->defines['button-group-large'];
			} elseif ( $size == 'extra-large' ) {
				$classes[] = $this->defines['button-group-extra-large'];
			}

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
			}

			if ( !is_null( $type ) ) {
				$types = explode( ' ', $type );

				foreach ( $types as $type ) {
					$classes[] = $type;
				}
			}
			$classes = implode( ' ', $classes );

			return $classes;
		}

		/**
		 * The framework's alert boxes.
		 */
		function alert( $type = 'info', $content = '', $id = null, $extra_classes = null, $dismiss = false ) {
			$classes = array();

			$classes[] = $this->defines['alert'];
			$classes[] = $this->defines['alert-' . $type];

			if ( true == $dismiss ) {
				$classes[] = 'alert-dismissable';

				$dismiss = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
			} else {
				$dismiss = null;
			}

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
			}

			// If an ID has been defined, format it properly.
			if ( !is_null( $id ) ) {
				$id = ' id=' . $id . '"';
			}

			$classes = implode( ' ', $classes );

			return '<div class="' . $classes . '"' . $id . '>' . $dismiss . $content . '</div>';
		}

		function make_panel( $extra_classes = null, $id = null  ) {

			$classes = array();

			if ( !is_null( $extra_classes ) ) {
				$extras = explode( ' ', $extra_classes );

				foreach ( $extras as $extra ) {
					$classes[] = $extra;
				}
				$classes = ' ' . implode( ' ', $classes );
			} else {
				$classes = null;
			}

			// If an ID has been defined, format it properly.
			if ( !is_null( $id ) ) {
				$id = ' id=' . $id . '"';
			}

			return '<div class="panel panel-default' . $classes . '"' . $id . '>';
		}

		/**
		 * Variables to use for the compiler.
		 * These override the default Bootstrap Variables.
		 */
		function styles() {
			global $ss_settings;

			/**
			 * BACKGROUND
			 */
			$bg      = $ss_settings['body_bg'];
			$bg      = isset( $bg['background-color'] ) ? $bg['background-color'] : '#ffffff';
			$body_bg = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $bg ) );

			// Calculate the gray shadows based on the body background.
			// We basically create 2 "presets": light and dark.
			if ( Shoestrap_Color::get_brightness( $body_bg ) > 80 ) {
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

			$bg_brightness = Shoestrap_Color::get_brightness( $body_bg );

			$table_bg_accent      = $bg_brightness > 50 ? 'darken(@body-bg, 2.5%)'    : 'lighten(@body-bg, 2.5%)';
			$table_bg_hover       = $bg_brightness > 50 ? 'darken(@body-bg, 4%)'      : 'lighten(@body-bg, 4%)';
			$table_border_color   = $bg_brightness > 50 ? 'darken(@body-bg, 13.35%)'  : 'lighten(@body-bg, 13.35%)';
			$input_border         = $bg_brightness > 50 ? 'darken(@body-bg, 20%)'     : 'lighten(@body-bg, 20%)';
			$dropdown_divider_top = $bg_brightness > 50 ? 'darken(@body-bg, 10.2%)'   : 'lighten(@body-bg, 10.2%)';

			$variables = '';

			// Calculate grays
			if ( isset( $body_bg ) && ! empty( $body_bg ) ) {
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
			}

			/**
			 * LAYOUT
			 */
			if ( isset( $ss_settings['screen_tablet'] ) && ! empty( $ss_settings['screen_tablet'] ) ) {
				$screen_sm = filter_var( $ss_settings['screen_tablet'], FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $ss_settings['screen_desktop'] ) && ! empty( $ss_settings['screen_desktop'] ) ) {
				$screen_md = filter_var( $ss_settings['screen_desktop'], FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $ss_settings['screen_large_desktop'] ) && ! empty( $ss_settings['screen_large_desktop'] ) ) {
				$screen_lg = filter_var( $ss_settings['screen_large_desktop'], FILTER_SANITIZE_NUMBER_INT );
			}

			if ( isset( $ss_settings['layout_gutter'] ) && ! empty( $ss_settings['layout_gutter'] ) ) {
				$gutter    = filter_var( $ss_settings['layout_gutter'], FILTER_SANITIZE_NUMBER_INT );
				$gutter    = ( $gutter < 2 ) ? 2 : $gutter;
			}

			if ( isset( $ss_settings['site_style'] ) && ! empty( $ss_settings['site_style'] ) ) {
				$site_style = $ss_settings['site_style'];
			}

			if ( isset( $site_style ) && ! empty( $site_style ) ) {
				$screen_xs = ( $site_style == 'static' ) ? '50px' : '480px';
				$screen_sm = ( $site_style == 'static' ) ? '50px' : $screen_sm;
				$screen_md = ( $site_style == 'static' ) ? '50px' : $screen_md;
			}

			if ( isset( $screen_sm ) && ! empty( $screen_sm ) ) {
				$variables .= '@screen-sm: ' . $screen_sm . 'px;';
			}

			if ( isset( $screen_md ) && ! empty( $screen_md ) ) {
				$variables .= '@screen-md: ' . $screen_md . 'px;';
			}

			if ( isset( $screen_lg ) && ! empty( $screen_lg ) ) {
				$variables .= '@screen-lg: ' . $screen_lg . 'px;';
			}

			if ( isset( $gutter ) && ! empty( $gutter ) ) {
				$variables .= '@grid-gutter-width: ' . $gutter . 'px;';
			}

			$variables .= '@jumbotron-padding: @grid-gutter-width;';

			if ( isset( $gutter ) && ! empty( $gutter ) ) {
				$variables .= '@modal-inner-padding: ' . round( $gutter * 20 / 30 ) . 'px;';
				$variables .= '@modal-title-padding: ' . round( $gutter * 15 / 30 ) . 'px;';

				$variables .= '@modal-lg: ' . round( $screen_md - ( 3 * $gutter ) ) . 'px;';
				$variables .= '@modal-md: ' . round( $screen_sm - ( 3 * $gutter ) ) . 'px;';
				$variables .= '@modal-sm: ' . round( $screen_xs - ( 3 * $gutter ) ) . 'px;';
			}

			$variables .= '@panel-body-padding: @modal-title-padding;';

			if ( isset( $gutter ) && ! empty( $gutter ) ) {
				$variables .= '@container-tablet:        ' . ( $screen_sm - ( $gutter / 2 ) ). 'px;';
				$variables .= '@container-desktop:       ' . ( $screen_md - ( $gutter / 2 ) ). 'px;';
				$variables .= '@container-large-desktop: ' . ( $screen_lg - $gutter ). 'px;';
			}

			if ( isset( $gutter ) && ! empty( $gutter ) && $site_style == 'static' ) {
				// disable responsiveness
				$variables .= '@screen-xs-max: 0 !important;
				.container { max-width: none !important; width: @container-large-desktop; }
				html { overflow-x: auto !important; }';
			}

			/**
			 * TYPOGRAPHY
			 */
			$font_base = shoestrap_process_font( $ss_settings['font_base'] );
			$font_h1   = shoestrap_process_font( $ss_settings['font_h1'] );
			$font_h2   = shoestrap_process_font( $ss_settings['font_h2'] );
			$font_h3   = shoestrap_process_font( $ss_settings['font_h3'] );
			$font_h4   = shoestrap_process_font( $ss_settings['font_h4'] );
			$font_h5   = shoestrap_process_font( $ss_settings['font_h5'] );
			$font_h6   = shoestrap_process_font( $ss_settings['font_h6'] );

			$text_color       = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_base['color'] ) );
			$sans_serif       = $font_base['font-family'];
			$font_size_base   = $font_base['font-size'];
			$font_weight_base = $font_base['font-weight'];

			$font_h1_size   = ( ( filter_var( $font_h1['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h2_size   = ( ( filter_var( $font_h2['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h3_size   = ( ( filter_var( $font_h3['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h4_size   = ( ( filter_var( $font_h4['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h5_size   = ( ( filter_var( $font_h5['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
			$font_h6_size   = ( ( filter_var( $font_h6['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );

			if ( $ss_settings['font_heading_custom'] != 1 ) {

				$font_h1_face = $font_h2_face = $font_h3_face = $font_h4_face = $font_h5_face = $font_h6_face = 'inherit';

				$font_h1_weight = $font_h2_weight = $font_h3_weight = $font_h5_weight = $font_h4_weight = $font_h6_weight = '500';

				$font_h1_style = $font_h2_style = $font_h3_style = $font_h4_style = $font_h5_style = $font_h6_style = 'inherit';

				$font_h1_color  = $font_h2_color  = $font_h3_color  = $font_h4_color  = $font_h5_color  = $font_h6_color  = 'inherit';

			} else {
				$font_h1_face   = $font_h1['font-family'];
				$font_h1_weight = $font_h1['font-weight'];
				$font_h1_style  = $font_h1['font-style'];
				$font_h1_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h1['color'] ) );

				$font_h2_face   = $font_h2['font-family'];
				$font_h2_weight = $font_h2['font-weight'];
				$font_h2_style  = $font_h2['font-style'];
				$font_h2_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h2['color'] ) );

				$font_h3_face   = $font_h3['font-family'];
				$font_h3_weight = $font_h3['font-weight'];
				$font_h3_style  = $font_h3['font-style'];
				$font_h3_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h3['color'] ) );

				$font_h4_face   = $font_h4['font-family'];
				$font_h4_weight = $font_h4['font-weight'];
				$font_h4_style  = $font_h4['font-style'];
				$font_h4_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h4['color'] ) );

				$font_h5_face   = $font_h5['font-family'];
				$font_h5_weight = $font_h5['font-weight'];
				$font_h5_style  = $font_h5['font-style'];
				$font_h5_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h5['color'] ) );

				$font_h6_face   = $font_h6['font-family'];
				$font_h6_weight = $font_h6['font-weight'];
				$font_h6_style  = $font_h6['font-style'];
				$font_h6_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_h6['color'] ) );
			}

			if ( isset( $text_color ) && ! empty( $text_color ) ) {
				$variables .= '@text-color:             ' . $text_color . ';';
			}

			if ( isset( $sans_serif ) && ! empty( $sans_serif ) ) {
				$variables .= '@font-family-sans-serif: ' . $sans_serif . ';';
			}

			if ( isset( $font_size_base ) && ! empty( $font_size_base ) ) {
				$variables .= '@font-size-base:         ' . $font_size_base . 'px;';
			}

			if ( isset( $font_h1_size ) && ! empty( $font_h1_size ) ) {
				$variables .= '@font-size-h1: floor((@font-size-base * ' . $font_h1_size . '));';
			}

			if ( isset( $font_h2_size ) && ! empty( $font_h2_size ) ) {
				$variables .= '@font-size-h2: floor((@font-size-base * ' . $font_h2_size . '));';
			}

			if ( isset( $font_h3_size ) && ! empty( $font_h3_size ) ) {
				$variables .= '@font-size-h3: ceil((@font-size-base * ' . $font_h3_size . '));';
			}

			if ( isset( $font_h4_size ) && ! empty( $font_h4_size ) ) {
				$variables .= '@font-size-h4: ceil((@font-size-base * ' . $font_h4_size . '));';
			}

			if ( isset( $font_h5_size ) && ! empty( $font_h5_size ) ) {
				$variables .= '@font-size-h5: ' . $font_h5_size . ';';
			}

			if ( isset( $font_h6_size ) && ! empty( $font_h6_size ) ) {
				$variables .= '@font-size-h6: ceil((@font-size-base * ' . $font_h6_size . '));';
			}

			$variables .= '@caret-width-base:  ceil(@font-size-small / 3 );';
			$variables .= '@caret-width-large: ceil(@caret-width-base * (5/4) );';

			$variables .= '@table-cell-padding:           ceil((@font-size-small * 2) / 3 );';
			$variables .= '@table-condensed-cell-padding: ceil(((@font-size-small / 3 ) * 5) / 4);';

			$variables .= '@carousel-control-font-size: ceil((@font-size-base * 1.43));';

			// Shoestrap-specific variables
			// --------------------------------------------------

			if ( isset( $font_weight_base ) && ! empty( $font_weight_base ) ) {
				$variables .= '@base-font-weight:        ' . $font_weight_base . ';';
			}

			// H1
			if ( isset( $font_h1_face ) && ! empty( $font_h1_face ) ) {
				$variables .= '@heading-h1-face:         ' . $font_h1_face . ';';
			}

			if ( isset( $font_h1_weight ) && ! empty( $font_h1_weight ) ) {
				$variables .= '@heading-h1-weight:       ' . $font_h1_weight . ';';
			}

			if ( isset( $font_h1_style ) && ! empty( $font_h1_style ) ) {
				$variables .= '@heading-h1-style:        ' . $font_h1_style . ';';
			}

			if ( isset( $font_h1_color ) && ! empty( $font_h1_color ) ) {
				$variables .= '@heading-h1-color:        ' . $font_h1_color . ';';
			}

			// H2
			if ( isset( $font_h2_face ) && ! empty( $font_h2_face ) ) {
				$variables .= '@heading-h2-face:         ' . $font_h2_face . ';';
			}

			if ( isset( $font_h2_weight ) && ! empty( $font_h2_weight ) ) {
				$variables .= '@heading-h2-weight:       ' . $font_h2_weight . ';';
			}

			if ( isset( $font_h2_style ) && ! empty( $font_h2_style ) ) {
				$variables .= '@heading-h2-style:        ' . $font_h2_style . ';';
			}

			if ( isset( $font_h2_color ) && ! empty( $font_h2_color ) ) {
				$variables .= '@heading-h2-color:        ' . $font_h2_color . ';';
			}

			// H3
			if ( isset( $font_h3_face ) && ! empty( $font_h3_face ) ) {
				$variables .= '@heading-h3-face:         ' . $font_h3_face . ';';
			}

			if ( isset( $font_h3_weight ) && ! empty( $font_h3_weight ) ) {
				$variables .= '@heading-h3-weight:       ' . $font_h3_weight . ';';
			}

			if ( isset( $font_h3_style ) && ! empty( $font_h3_style ) ) {
				$variables .= '@heading-h3-style:        ' . $font_h3_style . ';';
			}

			if ( isset( $font_h3_color ) && ! empty( $font_h3_color ) ) {
				$variables .= '@heading-h3-color:        ' . $font_h3_color . ';';
			}

			// H4
			if ( isset( $font_h4_face ) && ! empty( $font_h4_face ) ) {
				$variables .= '@heading-h4-face:         ' . $font_h4_face . ';';
			}

			if ( isset( $font_h4_weight ) && ! empty( $font_h4_weight ) ) {
				$variables .= '@heading-h4-weight:       ' . $font_h4_weight . ';';
			}

			if ( isset( $font_h4_style ) && ! empty( $font_h4_style ) ) {
				$variables .= '@heading-h4-style:        ' . $font_h4_style . ';';
			}

			if ( isset( $font_h4_color ) && ! empty( $font_h4_color ) ) {
				$variables .= '@heading-h4-color:        ' . $font_h4_color . ';';
			}

			// H5
			if ( isset( $font_h5_face ) && ! empty( $font_h5_face ) ) {
				$variables .= '@heading-h5-face:         ' . $font_h5_face . ';';
			}

			if ( isset( $font_h5_weight ) && ! empty( $font_h5_weight ) ) {
				$variables .= '@heading-h5-weight:       ' . $font_h5_weight . ';';
			}

			if ( isset( $font_h5_style ) && ! empty( $font_h5_style ) ) {
				$variables .= '@heading-h5-style:        ' . $font_h5_style . ';';
			}

			if ( isset( $font_h5_color ) && ! empty( $font_h5_color ) ) {
				$variables .= '@heading-h5-color:        ' . $font_h5_color . ';';
			}

			// H6
			if ( isset( $font_h6_face ) && ! empty( $font_h6_face ) ) {
				$variables .= '@heading-h6-face:         ' . $font_h6_face . ';';
			}

			if ( isset( $font_h6_weight ) && ! empty( $font_h6_weight ) ) {
				$variables .= '@heading-h6-weight:       ' . $font_h6_weight . ';';
			}

			if ( isset( $font_h6_style ) && ! empty( $font_h6_style ) ) {
				$variables .= '@heading-h6-style:        ' . $font_h6_style . ';';
			}

			if ( isset( $font_h6_color ) && ! empty( $font_h6_color ) ) {
				$variables .= '@heading-h6-color:        ' . $font_h6_color . ';';
			}


			/**
			 * BRANDING
			 */
			$brand_primary = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $ss_settings['color_brand_primary'] ) );
			$brand_success = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $ss_settings['color_brand_success'] ) );
			$brand_warning = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $ss_settings['color_brand_warning'] ) );
			$brand_danger  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $ss_settings['color_brand_danger'] ) );
			$brand_info    = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $ss_settings['color_brand_info'] ) );

			$link_hover_color = ( Shoestrap_Color::get_brightness( $brand_primary ) > 50 ) ? 'darken(@link-color, 15%)' : 'lighten(@link-color, 15%)';

			$brand_primary_brightness = Shoestrap_Color::get_brightness( $brand_primary );
			$brand_success_brightness = Shoestrap_Color::get_brightness( $brand_success );
			$brand_warning_brightness = Shoestrap_Color::get_brightness( $brand_warning );
			$brand_danger_brightness  = Shoestrap_Color::get_brightness( $brand_danger );
			$brand_info_brightness    = Shoestrap_Color::get_brightness( $brand_info );

			// Button text colors
			$btn_primary_color  = $brand_primary_brightness < 195 ? '#fff' : '333';
			$btn_success_color  = $brand_success_brightness < 195 ? '#fff' : '333';
			$btn_warning_color  = $brand_warning_brightness < 195 ? '#fff' : '333';
			$btn_danger_color   = $brand_danger_brightness  < 195 ? '#fff' : '333';
			$btn_info_color     = $brand_info_brightness    < 195 ? '#fff' : '333';

			// Button borders
			$btn_primary_border = $brand_primary_brightness < 195 ? 'darken(@btn-primary-bg, 5%)' : 'lighten(@btn-primary-bg, 5%)';
			$btn_success_border = $brand_success_brightness < 195 ? 'darken(@btn-success-bg, 5%)' : 'lighten(@btn-success-bg, 5%)';
			$btn_warning_border = $brand_warning_brightness < 195 ? 'darken(@btn-warning-bg, 5%)' : 'lighten(@btn-warning-bg, 5%)';
			$btn_danger_border  = $brand_danger_brightness  < 195 ? 'darken(@btn-danger-bg, 5%)'  : 'lighten(@btn-danger-bg, 5%)';
			$btn_info_border    = $brand_info_brightness    < 195 ? 'darken(@btn-info-bg, 5%)'    : 'lighten(@btn-info-bg, 5%)';

			$input_border_focus = ( Shoestrap_Color::get_brightness( $brand_primary ) < 195 ) ? 'lighten(@brand-primary, 10%);' : 'darken(@brand-primary, 10%);';
			$navbar_border      = ( Shoestrap_Color::get_brightness( $brand_primary ) < 50 ) ? 'lighten(@navbar-default-bg, 6.5%)' : 'darken(@navbar-default-bg, 6.5%)';

			// Branding colors
			if ( isset( $brand_primary ) && ! empty( $brand_primary ) ) {
				$variables .= '@brand-primary: ' . $brand_primary . ';';
			}

			if ( isset( $brand_success ) && ! empty( $brand_success ) ) {
				$variables .= '@brand-success: ' . $brand_success . ';';
			}

			if ( isset( $brand_info ) && ! empty( $brand_info ) ) {
				$variables .= '@brand-info:    ' . $brand_info . ';';
			}

			if ( isset( $brand_warning ) && ! empty( $brand_warning ) ) {
				$variables .= '@brand-warning: ' . $brand_warning . ';';
			}

			if ( isset( $brand_danger ) && ! empty( $brand_danger ) ) {
				$variables .= '@brand-danger:  ' . $brand_danger . ';';
			}

			// Link-hover
			if ( isset( $link_hover_color ) && ! empty( $link_hover_color ) ) {
				$variables .= '@link-hover-color: ' . $link_hover_color . ';';
			}

			$variables .= '@btn-default-color:  @gray-dark;';

			if ( isset( $btn_primary_color ) && ! empty( $btn_primary_color ) ) {
				$variables .= '@btn-primary-color:  ' . $btn_primary_color . ';';
			}

			if ( isset( $btn_primary_border ) && ! empty( $btn_primary_border ) ) {
				$variables .= '@btn-primary-border: ' . $btn_primary_border . ';';
			}

			if ( isset( $btn_success_color ) && ! empty( $btn_success_color ) ) {
				$variables .= '@btn-success-color:  ' . $btn_success_color . ';';
			}

			if ( isset( $btn_success_border ) && ! empty( $btn_success_border ) ) {
				$variables .= '@btn-success-border: ' . $btn_success_border . ';';
			}

			if ( isset( $btn_info_color ) && ! empty( $btn_info_color ) ) {
				$variables .= '@btn-info-color:     ' . $btn_info_color . ';';
			}

			if ( isset( $btn_info_border ) && ! empty( $btn_info_border ) ) {
				$variables .= '@btn-info-border:    ' . $btn_info_border . ';';
			}

			if ( isset( $btn_warning_color ) && ! empty( $btn_warning_color ) ) {
				$variables .= '@btn-warning-color:  ' . $btn_warning_color . ';';
			}

			if ( isset( $btn_warning_border ) && ! empty( $btn_warning_border ) ) {
				$variables .= '@btn-warning-border: ' . $btn_warning_border . ';';
			}

			if ( isset( $btn_danger_color ) && ! empty( $btn_danger_color ) ) {
				$variables .= '@btn-danger-color:   ' . $btn_danger_color . ';';
			}

			if ( isset( $btn_danger_border ) && ! empty( $btn_danger_border ) ) {
				$variables .= '@btn-danger-border:  ' . $btn_danger_border . ';';
			}

			if ( isset( $input_border_focus ) && ! empty( $input_border_focus ) ) {
				$variables .= '@input-border-focus: ' . $input_border_focus . ';';
			}

			$variables .= '@state-success-text: mix(@gray-darker, @brand-success, 20%);';
			$variables .= '@state-success-bg:   mix(@body-bg, @brand-success, 50%);';

			$variables .= '@state-info-text:    mix(@gray-darker, @brand-info, 20%);';
			$variables .= '@state-info-bg:      mix(@body-bg, @brand-info, 50%);';

			$variables .= '@state-warning-text: mix(@gray-darker, @brand-warning, 20%);';
			$variables .= '@state-warning-bg:   mix(@body-bg, @brand-warning, 50%);';

			$variables .= '@state-danger-text:  mix(@gray-darker, @brand-danger, 20%);';
			$variables .= '@state-danger-bg:    mix(@body-bg, @brand-danger, 50%);';

			/**
			 * JUMBOTRON
			 */
			$font_jumbotron         = shoestrap_process_font( $ss_settings['font_jumbotron'] );
			$jumbotron_bg           = $ss_settings['jumbo_bg'];
			$jumbotron_bg           = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $jumbotron_bg['background-color'] ) );
			$jumbotron_text_color   = '#' . str_replace( '#', '', $font_jumbotron['color'] );

			if ( $ss_settings['font_jumbotron_heading_custom'] == 1 ) {
				$font_jumbotron_headers = shoestrap_process_font( $ss_settings['font_jumbotron_headers'] );

				$font_jumbotron_headers_face   = $font_jumbotron_headers['font-family'];
				$font_jumbotron_headers_weight = $font_jumbotron_headers['font-weight'];
				$font_jumbotron_headers_style  = $font_jumbotron_headers['font-style'];
				$jumbotron_headers_text_color  = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $font_jumbotron_headers['color'] ) );

			} else {
				$font_jumbotron_headers_face   = $font_jumbotron['font-family'];
				$font_jumbotron_headers_weight = $font_jumbotron['font-weight'];
				$font_jumbotron_headers_style  = $font_jumbotron['font-style'];
				$jumbotron_headers_text_color  = $jumbotron_text_color;
			}

			if ( isset( $jumbotron_text_color ) && ! empty( $jumbotron_text_color ) ) {
				$variables .= '@jumbotron-color:         ' . $jumbotron_text_color . ';';
			}

			if ( isset( $jumbotron_bg ) && ! empty( $jumbotron_bg ) ) {
				$variables .= '@jumbotron-bg:            ' . $jumbotron_bg . ';';
			}

			if ( isset( $jumbotron_headers_text_color ) && ! empty( $jumbotron_headers_text_color ) ) {
				$variables .= '@jumbotron-heading-color: ' . $jumbotron_headers_text_color . ';';
			}

			if ( isset( $font_jumbotron ) && ! empty( $font_jumbotron ) ) {
				$variables .= '@jumbotron-font-size:     ' . $font_jumbotron['font-size'] . 'px;';
			}

			// Shoestrap-specific variables
			// --------------------------------------------------

			if ( isset( $font_jumbotron ) && ! empty( $font_jumbotron ) ) {
				$variables .= '@jumbotron-font-weight:       ' . $font_jumbotron['font-weight'] . ';';
				$variables .= '@jumbotron-font-style:        ' . $font_jumbotron['font-style'] . ';';
				$variables .= '@jumbotron-font-family:       ' . $font_jumbotron['font-family'] . ';';
			}

			if ( isset( $font_jumbotron_headers_weight ) && ! empty( $font_jumbotron_headers_weight ) ) {
				$variables .= '@jumbotron-headers-font-weight:       ' . $font_jumbotron_headers_weight . ';';
			}

			if ( isset( $font_jumbotron_headers_style ) && ! empty( $font_jumbotron_headers_style ) ) {
				$variables .= '@jumbotron-headers-font-style:        ' . $font_jumbotron_headers_style . ';';
			}

			if ( isset( $font_jumbotron_headers_face ) && ! empty( $font_jumbotron_headers_face ) ) {
				$variables .= '@jumbotron-headers-font-family:       ' . $font_jumbotron_headers_face . ';';
			}

			/**
			 * MENUS
			 */
			$font_brand        = shoestrap_process_font( $ss_settings['font_brand'] );

			$font_navbar       = shoestrap_process_font( $ss_settings['font_navbar'] );
			$navbar_bg         = '#' . str_replace( '#', '', Shoestrap_Color::sanitize_hex( $ss_settings['navbar_bg'] ) );
			$navbar_height     = filter_var( $ss_settings['navbar_height'], FILTER_SANITIZE_NUMBER_INT );
			$navbar_text_color = '#' . str_replace( '#', '', $font_navbar['color'] );
			$brand_text_color  = '#' . str_replace( '#', '', $font_brand['color'] );
			$navbar_border     = ( Shoestrap_Color::get_brightness( $navbar_bg ) < 50 ) ? 'lighten(@navbar-default-bg, 6.5%)' : 'darken(@navbar-default-bg, 6.5%)';
			$gfb = $ss_settings['grid_float_breakpoint'];

			if ( Shoestrap_Color::get_brightness( $navbar_bg ) < 165 ) {
				$navbar_link_hover_color    = 'darken(@navbar-default-color, 26.5%)';
				$navbar_link_active_bg      = 'darken(@navbar-default-bg, 6.5%)';
				$navbar_link_disabled_color = 'darken(@navbar-default-bg, 6.5%)';
				$navbar_brand_hover_color   = 'darken(@navbar-default-brand-color, 10%)';
			} else {
				$navbar_link_hover_color    = 'lighten(@navbar-default-color, 26.5%)';
				$navbar_link_active_bg      = 'lighten(@navbar-default-bg, 6.5%)';
				$navbar_link_disabled_color = 'lighten(@navbar-default-bg, 6.5%)';
				$navbar_brand_hover_color   = 'lighten(@navbar-default-brand-color, 10%)';
			}

			$grid_float_breakpoint = ( isset( $gfb ) )           ? $gfb             : '@screen-sm-min';
			$grid_float_breakpoint = ( $gfb == 'min' )           ? '10px'           : $grid_float_breakpoint;
			$grid_float_breakpoint = ( $gfb == 'screen_xs_min' ) ? '@screen-xs-min' : $grid_float_breakpoint;
			$grid_float_breakpoint = ( $gfb == 'screen_sm_min' ) ? '@screen-sm-min' : $grid_float_breakpoint;
			$grid_float_breakpoint = ( $gfb == 'screen_md_min' ) ? '@screen-md-min' : $grid_float_breakpoint;
			$grid_float_breakpoint = ( $gfb == 'screen_lg_min' ) ? '@screen-lg-min' : $grid_float_breakpoint;
			$grid_float_breakpoint = ( $gfb == 'max' )           ? '9999px'         : $grid_float_breakpoint;

			$grid_float_breakpoint = ( $gfb == 'screen-lg-min' ) ? '0 !important' : $grid_float_breakpoint;

			if ( isset( $navbar_height ) && ! empty( $navbar_height ) ) {
				$variables .= '@navbar-height:         ' . $navbar_height . 'px;';
			}

			if ( isset( $navbar_text_color ) && ! empty( $navbar_text_color ) ) {
				$variables .= '@navbar-default-color:  ' . $navbar_text_color . ';';
			}

			if ( isset( $navbar_bg ) && ! empty( $navbar_bg ) ) {
				$variables .= '@navbar-default-bg:     ' . $navbar_bg . ';';
			}

			if ( isset( $navbar_border ) && ! empty( $navbar_border ) ) {
				$variables .= '@navbar-default-border: ' . $navbar_border . ';';
			}

			$variables .= '@navbar-default-link-color:          @navbar-default-color;';
			if ( isset( $navbar_link_hover_color ) && ! empty( $navbar_link_hover_color ) ) {
				$variables .= '@navbar-default-link-hover-color:    ' . $navbar_link_hover_color . ';';
			}

			$variables .= '@navbar-default-link-active-color:   mix(@navbar-default-color, @navbar-default-link-hover-color, 50%);';

			if ( isset( $navbar_link_active_bg ) && ! empty( $navbar_link_active_bg ) ) {
				$variables .= '@navbar-default-link-active-bg:      ' . $navbar_link_active_bg . ';';
			}

			if ( isset( $navbar_link_disabled_color ) && ! empty( $navbar_link_disabled_color ) ) {
				$variables .= '@navbar-default-link-disabled-color: ' . $navbar_link_disabled_color . ';';
			}

			$variables .= '@navbar-default-brand-color:         @navbar-default-link-color;';
			if ( isset( $navbar_brand_hover_color ) && ! empty( $navbar_brand_hover_color ) ) {
				$variables .= '@navbar-default-brand-hover-color:   ' . $navbar_brand_hover_color . ';';
			}

			if ( isset( $navbar_border ) && ! empty( $navbar_border ) ) {
				$variables .= '@navbar-default-toggle-hover-bg:     ' . $navbar_border . ';';
			}

			if ( isset( $navbar_text_color ) && ! empty( $navbar_text_color ) ) {
				$variables .= '@navbar-default-toggle-icon-bar-bg:  ' . $navbar_text_color . ';';
			}

			if ( isset( $navbar_border ) && ! empty( $navbar_border ) ) {
				$variables .= '@navbar-default-toggle-border-color: ' . $navbar_border . ';';
			}

			// Shoestrap-specific variables
			// --------------------------------------------------

			if ( isset( $font_navbar ) && ! empty( $font_navbar ) ) {
				$variables .= '@navbar-font-size:        ' . $font_navbar['font-size'] . 'px;';
				$variables .= '@navbar-font-weight:      ' . $font_navbar['font-weight'] . ';';
				$variables .= '@navbar-font-style:       ' . $font_navbar['font-style'] . ';';
				$variables .= '@navbar-font-family:      ' . $font_navbar['font-family'] . ';';
			}

			if ( isset( $navbar_text_color ) && ! empty( $navbar_text_color ) ) {
				$variables .= '@navbar-font-color:       ' . $navbar_text_color . ';';
			}

			if ( isset( $font_brand ) && ! empty( $font_brand ) ) {
				$variables .= '@brand-font-size:         ' . $font_brand['font-size'] . 'px;';
				$variables .= '@brand-font-weight:       ' . $font_brand['font-weight'] . ';';
				$variables .= '@brand-font-style:        ' . $font_brand['font-style'] . ';';
				$variables .= '@brand-font-family:       ' . $font_brand['font-family'] . ';';
			}

			if ( isset( $brand_text_color ) && ! empty( $brand_text_color ) ) {
				$variables .= '@brand-font-color:        ' . $brand_text_color . ';';
			}

			if ( isset( $ss_settings['navbar_margin_top'] ) && ! empty( $ss_settings['navbar_margin_top'] ) ) {
				$variables .= '@navbar-margin-top:       ' . $ss_settings['navbar_margin_top'] . 'px;';
			}

			if ( isset( $grid_float_breakpoint ) && ! empty( $grid_float_breakpoint ) ) {
				$variables .= '@grid-float-breakpoint: ' . $grid_float_breakpoint . ';';
			}

			$variables .= '@import "' . dirname( __FILE__ ) . '/assets/less/blog.less";';
			$variables .= '@import "' . dirname( __FILE__ ) . '/assets/less/headers.less";';
			$variables .= '@import "' . dirname( __FILE__ ) . '/assets/less/layout.less";';
			$variables .= '@import "' . dirname( __FILE__ ) . '/assets/less/typography.less";';
			$variables .= '@import "' . dirname( __FILE__ ) . '/assets/less/social.less";';
			$variables .= '@import "' . dirname( __FILE__ ) . '/assets/less/menus.less";';
			$variables .= '@import "' . dirname( __FILE__ ) . '/assets/less/widgets.less";';

			return $variables;
		}

		/**
		 * Add styles to the compiler
		 */
		function styles_filter( $bootstrap ) {
			return $bootstrap . $this->styles();
		}

		/*
		 * This function can be used to compile a less file to css using the lessphp compiler
		 */
		function compiler() {
			global $ss_settings;

			if ( $ss_settings['minimize_css'] == 1 ) {
				$compress = true;
			} else {
				$compress = false;
			}

			$options = array( 'compress' => $compress );

			$bootstrap_location = dirname( __FILE__ ) . '/assets/less/';
			$webfont_location   = get_template_directory() . '/assets/fonts/';
			$bootstrap_uri      = '';
			$custom_less_file   = get_stylesheet_directory() . '/assets/less/custom.less';

			$css = '';
			try {

				$parser = new Less_Parser( $options );

				// The main app.less file
				$parser->parseFile( $bootstrap_location . 'app.less', $bootstrap_uri );

				// Include the Elusive Icons
				$parser->parseFile( $webfont_location . 'elusive-webfont.less', $bootstrap_uri );

				// Enable gradients
				if ( $ss_settings['gradients_toggle'] == 1 ) {
					$parser->parseFile( $bootstrap_location . 'gradients.less', $bootstrap_uri );
				}

				// The custom.less file
				if ( is_writable( $custom_less_file ) ) {
					$parser->parseFile( $bootstrap_location . 'custom.less', $bootstrap_uri );
				}

				// Parse any custom less added by the user
				$parser->parse( $ss_settings['user_less'] );

				// Get the extra variables & imports
				$extra_vars = do_action( 'ss_bootstrap_less_vars' );
				$parser->parse( $extra_vars );

				// Add a filter to the compiler
				$parser->parse( apply_filters( 'shoestrap_compiler', '' ) );

				$css = $parser->getCss();

			} catch( Exception $e ) {
				$error_message = $e->getMessage();
			}

			// Below is just an ugly hack
			$css = str_replace( '../', get_template_directory_uri() . '/assets/', $css );

			return apply_filters( 'shoestrap_compiler_output', $css );
		}

		/**
		 * The inline icon links for social networks.
		 */
		function navbar_social_bar() {
			global $ss_social;

			// Get all the social networks the user is using
			$networks = $ss_social->get_social_links();

			// The base class for icons that will be used
			$baseclass  = 'icon el-icon-';

			// Build the content
			$content = '';
			$content .= '<div id="navbar_social_bar">';

			// populate the networks
			foreach ( $networks as $network ) {
				if ( strlen( $network['url'] ) > 7 ) {
					// add the $show variable to check if the user has actually entered a url in any of the available networks
					$show     = true;
					$content .= '<a class="btn btn-link navbar-btn" href="' . $network['url'] . '" target="_blank" title="'. $network['icon'] .'">';
					$content .= '<i class="' . $baseclass . $network['icon'] . '"></i> ';
					$content .= '</a>';
				}
			}
			$content .= '</div>';

			echo ( $networks ) ? $content : '';
		}

		/**
		 * Build the social links for the navbar
		 */
		function navbar_social_links() {
			global $ss_social;

			// Get all the social networks the user is using
			$networks = $ss_social->get_social_links();

			// The base class for icons that will be used
			$baseclass  = 'el-icon-';

			// Build the content
			$content = '';
			$content .= '<ul class="nav navbar-nav pull-left">';
			$content .= '<li class="dropdown">';
			$content .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
			$content .= '<i class="' . $baseclass . 'network"></i>';
			$content .= '<b class="caret"></b>';
			$content .= '</a>';
			$content .= '<ul class="dropdown-menu dropdown-social">';

			// populate the networks
			foreach ( $networks as $network ) {
				if ( strlen( $network['url'] ) > 7 ) {
					// add the $show variable to check if the user has actually entered a url in any of the available networks
					$show     = true;
					$content .= '<li>';
					$content .= '<a href="' . $network['url'] . '" target="_blank">';
					$content .= '<i class="' . $baseclass . $network['icon'] . '"></i> ';
					$content .= $network['fullname'];
					$content .= '</a></li>';
				}
			}
			$content .= '</ul></li></ul>';

			if ( $networks ) {
				echo $content;
			}
		}

		function include_wrapper() {
			global $ss_layout;

			return $ss_layout->include_wrapper();
		}
	}
}