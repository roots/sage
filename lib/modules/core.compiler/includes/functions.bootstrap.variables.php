<?php


if ( !function_exists( 'shoestrap_variables' ) ) :
/*
 * The content below is a copy of bootstrap's variables.less file.
 *
 * Some options are user-configurable and stored as theme mods.
 * We try to minimize the options and simplify the user environment.
 * In order to do that, we 'll have to provide a minimum amount of options
 * and calculate the rest based on the user's selections.
 *
 */
function shoestrap_variables() {

  $site_style = shoestrap_getVariable( 'site_style' );

  $brand_primary    = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_primary', true ) ) );
  $brand_success    = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_success', true ) ) );
  $brand_warning    = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_warning', true ) ) );
  $brand_danger     = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_danger', true ) ) );
  $brand_info       = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'color_brand_info', true ) ) );

  $font_base              = shoestrap_process_font( shoestrap_getVariable( 'font_base', true ) );
  $font_navbar            = shoestrap_process_font( shoestrap_getVariable( 'font_navbar', true ) );
  $font_brand             = shoestrap_process_font( shoestrap_getVariable( 'font_brand', true ) );
  $font_jumbotron         = shoestrap_process_font( shoestrap_getVariable( 'font_jumbotron', true ) );
  $font_heading           = shoestrap_process_font( shoestrap_getVariable( 'font_heading', true ) );  

  $font_h1 = shoestrap_process_font( shoestrap_getVariable( 'font_h1', true ) );
  $font_h2 = shoestrap_process_font( shoestrap_getVariable( 'font_h2', true ) );
  $font_h3 = shoestrap_process_font( shoestrap_getVariable( 'font_h3', true ) );
  $font_h4 = shoestrap_process_font( shoestrap_getVariable( 'font_h4', true ) );
  $font_h5 = shoestrap_process_font( shoestrap_getVariable( 'font_h5', true ) );
  $font_h6 = shoestrap_process_font( shoestrap_getVariable( 'font_h6', true ) );

  $font_h1_face   = $font_h1['font-family'];
  $font_h1_size   = ( ( filter_var( $font_h1['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
  $font_h1_weight = $font_h1['font-weight'];
  $font_h1_style  = $font_h1['font-style'];
  $font_h1_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_h1['color'] ) );

  $font_h2_face   = $font_h2['font-family'];
  $font_h2_size   = ( ( filter_var( $font_h2['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
  $font_h2_weight = $font_h2['font-weight'];
  $font_h2_style  = $font_h2['font-style'];
  $font_h2_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_h2['color'] ) );

  $font_h3_face   = $font_h3['font-family'];
  $font_h3_size   = ( ( filter_var( $font_h3['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
  $font_h3_weight = $font_h3['font-weight'];
  $font_h3_style  = $font_h3['font-style'];
  $font_h3_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_h3['color'] ) );

  $font_h4_face   = $font_h4['font-family'];
  $font_h4_size   = ( ( filter_var( $font_h4['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
  $font_h4_weight = $font_h4['font-weight'];
  $font_h4_style  = $font_h4['font-style'];
  $font_h4_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_h4['color'] ) );

  $font_h5_face   = $font_h5['font-family'];
  $font_h5_size   = ( ( filter_var( $font_h5['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
  $font_h5_weight = $font_h5['font-weight'];
  $font_h5_style  = $font_h5['font-style'];
  $font_h5_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_h5['color'] ) );

  $font_h6_face   = $font_h6['font-family'];
  $font_h6_size   = ( ( filter_var( $font_h6['font-size'], FILTER_SANITIZE_NUMBER_INT ) ) / 100 );
  $font_h6_weight = $font_h6['font-weight'];
  $font_h6_style  = $font_h6['font-style'];
  $font_h6_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_h6['color'] ) );

  if ( shoestrap_getVariable( 'font_heading_custom', true ) != 1 ) {

    $font_h1_face   = '@font-family-base';
    $font_h1_weight = '@headings-font-weight';
    $font_h1_style  = 'inherit';
    $font_h1_color  = 'inherit';

    $font_h2_face   = '@font-family-base';
    $font_h2_weight = '@headings-font-weight';
    $font_h2_style  = 'inherit';
    $font_h2_color  = 'inherit';

    $font_h3_face   = '@font-family-base';
    $font_h3_weight = '@headings-font-weight';
    $font_h3_style  = 'inherit';
    $font_h3_color  = 'inherit';

    $font_h4_face   = '@font-family-base';
    $font_h4_weight = '@headings-font-weight';
    $font_h4_style  = 'inherit';
    $font_h4_color  = 'inherit';

    $font_h5_face   = '@font-family-base';
    $font_h5_weight = '@headings-font-weight';
    $font_h5_style  = 'inherit';
    $font_h5_color  = 'inherit';

    $font_h6_face   = '@font-family-base';
    $font_h6_weight = '@headings-font-weight';
    $font_h6_style  = 'inherit';
    $font_h6_color  = 'inherit';

  }

  $text_color       = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_base['color'] ) );
  $font_size_base   = $font_base['font-size'];
  $font_style_base  = $font_base['font-style'];
  $font_weight_base = $font_base['font-weight'];
  $sans_serif       = $font_base['font-family'];

  $border_radius    = filter_var( shoestrap_getVariable( 'general_border_radius', true ), FILTER_SANITIZE_NUMBER_INT );
  $border_radius    = ( strlen( $border_radius ) < 1 ) ? 0 : $border_radius;

  $padding_base     = intval( shoestrap_getVariable( 'padding_base', true ) );
  $navbar_bg        = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'navbar_bg', true ) ) );
  $jumbotron_bg     = '#' . str_replace( '#', '', shoestrap_sanitize_hex( shoestrap_getVariable( 'jumbotron_bg', true ) ) );

  $screen_sm = filter_var( shoestrap_getVariable( 'screen_tablet', true ), FILTER_SANITIZE_NUMBER_INT );
  $screen_md = filter_var( shoestrap_getVariable( 'screen_desktop', true ), FILTER_SANITIZE_NUMBER_INT );
  $screen_lg = filter_var( shoestrap_getVariable( 'screen_large_desktop', true ), FILTER_SANITIZE_NUMBER_INT );
  $gutter    = filter_var( shoestrap_getVariable( 'layout_gutter', true ), FILTER_SANITIZE_NUMBER_INT );
  $gutter    = ( $gutter < 2 ) ? 2 : $gutter;

  $screen_xs = ( $site_style == 'static' ) ? '50px' : '480px';
  $screen_sm = ( $site_style == 'static' ) ? '50px' : $screen_sm;
  $screen_md = ( $site_style == 'static' ) ? '50px' : $screen_md;

  $gfb = shoestrap_getVariable( 'grid_float_breakpoint' );
  $grid_float_breakpoint = ( isset( $gfb ) )           ? $gfb             : '@screen-sm-min';
  $grid_float_breakpoint = ( $gfb == 'min' )           ? '10px'           : $grid_float_breakpoint;
  $grid_float_breakpoint = ( $gfb == 'screen_xs_min' ) ? '@screen-xs-min' : $grid_float_breakpoint;
  $grid_float_breakpoint = ( $gfb == 'screen_sm_min' ) ? '@screen-sm-min' : $grid_float_breakpoint;
  $grid_float_breakpoint = ( $gfb == 'screen_md_min' ) ? '@screen-md-min' : $grid_float_breakpoint;
  $grid_float_breakpoint = ( $gfb == 'screen_lg_min' ) ? '@screen-lg-min' : $grid_float_breakpoint;
  $grid_float_breakpoint = ( $gfb == 'max' )           ? '9999px'         : $grid_float_breakpoint;

  $grid_float_breakpoint = ( $gfb == 'screen-lg-min' ) ? '0 !important' : $grid_float_breakpoint;

  $navbar_height    = filter_var( shoestrap_getVariable( 'navbar_height', true ), FILTER_SANITIZE_NUMBER_INT );
  $navbar_text_color       = '#' . str_replace( '#', '', $font_navbar['color'] );

  $brand_text_color       = '#' . str_replace( '#', '', $font_brand['color'] );
  $jumbotron_text_color   = '#' . str_replace( '#', '', $font_jumbotron['color'] );

  if ( shoestrap_getVariable( 'font_jumbotron_heading_custom', true ) == 1 ) {

    $font_jumbotron_headers = shoestrap_process_font( shoestrap_getVariable( 'font_jumbotron_headers', true ) );

    $font_jumbotron_headers_face   = $font_jumbotron_headers['font-family'];
    $font_jumbotron_headers_weight = $font_jumbotron_headers['font-weight'];
    $font_jumbotron_headers_style  = $font_jumbotron_headers['font-style'];
    $jumbotron_headers_text_color  = '#' . str_replace( '#', '', shoestrap_sanitize_hex( $font_jumbotron_headers['color'] ) );

  } else {

    $font_jumbotron_headers_face   = $font_jumbotron['font-family'];
    $font_jumbotron_headers_weight = $font_jumbotron['font-weight'];
    $font_jumbotron_headers_style  = $font_jumbotron['font-style'];
    $jumbotron_headers_text_color  = $jumbotron_text_color;
  }

  $link_hover_color = ( shoestrap_get_brightness( $brand_primary ) > 50 ) ? 'darken(@link-color, 15%)' : 'lighten(@link-color, 15%)';

  if ( shoestrap_get_brightness( $brand_primary ) > 50 ) {
    $table_bg_accent      = 'darken(@body-bg, 2.5%)';
    $table_bg_hover       = 'darken(@body-bg, 4%)';
    $table_border_color   = 'darken(@body-bg, 13.35%)';
    $input_border         = 'darken(@body-bg, 20%)';
    $dropdown_divider_top = 'darken(@body-bg, 10.2%)';
  } else {
    $table_bg_accent      = 'lighten(@body-bg, 2.5%)';
    $table_bg_hover       = 'lighten(@body-bg, 4%)';
    $table_border_color   = 'lighten(@body-bg, 13.35%)';
    $input_border         = 'lighten(@body-bg, 20%)';
    $dropdown_divider_top = 'lighten(@body-bg, 10.2%)';
  }

  if ( shoestrap_get_brightness( $navbar_bg ) < 165 ) {
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

  if ( shoestrap_get_brightness( $brand_primary ) < 165 ) {
    $btn_primary_color  = '#fff';
    $btn_primary_border = 'darken(@btn-primary-bg, 5%)';
  } else {
    $btn_primary_color  = '#333';
    $btn_primary_border = 'lighten(@btn-primary-bg, 5%)';
  }

  if ( shoestrap_get_brightness( $brand_success ) < 165 ) {
    $btn_success_color  = '#fff';
    $btn_success_border = 'darken(@btn-success-bg, 5%)';
  } else {
    $btn_success_color  = '#333';
    $btn_success_border = 'lighten(@btn-success-bg, 5%)';
  }

  if ( shoestrap_get_brightness( $brand_warning ) < 165 ) {
    $btn_warning_color  = '#fff';
    $btn_warning_border = 'darken(@btn-warning-bg, 5%)';
  } else {
    $btn_warning_color  = '#333';
    $btn_warning_border = 'lighten(@btn-warning-bg, 5%)';
  }

  if ( shoestrap_get_brightness( $brand_danger ) < 165 ) {
    $btn_danger_color  = '#fff';
    $btn_danger_border = 'darken(@btn-danger-bg, 5%)';
  } else {
    $btn_danger_color  = '#333';
    $btn_danger_border = 'lighten(@btn-danger-bg, 5%)';
  }

  if ( shoestrap_get_brightness( $brand_info ) < 165 ) {
    $btn_info_color  = '#fff';
    $btn_info_border = 'darken(@btn-info-bg, 5%)';
  } else {
    $btn_info_color  = '#333';
    $btn_info_border = 'lighten(@btn-info-bg, 5%)';
  }

  $input_border_focus = ( shoestrap_get_brightness( $brand_primary ) < 165 ) ? 'lighten(@brand-primary, 10%);' : 'darken(@brand-primary, 10%);';
  $navbar_border      = ( shoestrap_get_brightness( $brand_primary ) < 50 ) ? 'lighten(@navbar-default-bg, 6.5%)' : 'darken(@navbar-default-bg, 6.5%)';

$variables = '
@428bca: ' . $brand_primary . ';
@5cb85c: ' . $brand_success . ';
@f0ad4e: ' . $brand_warning . ';
@d9534f: ' . $brand_danger . ';
@5bc0de: ' . $brand_info . ';



@brand-primary:         @428bca;
@brand-success:         @5cb85c;
@brand-info:            @5bc0de;
@brand-warning:         @f0ad4e;
@brand-danger:          @d9534f;

//== Scaffolding
//
// ## Settings for some of the most global styles.

//** Global text color on `<body>`.
@text-color:            ' . $text_color . ';

//** Global textual link color.
@link-color:            @brand-primary;
//** Link hover color set via `darken()` function.
@link-hover-color:      ' . $link_hover_color . ';

//== Typography
//
//## Font, line-height, and color for body text, headings, and more.

@font-family-sans-serif:  ' . $sans_serif . ';
@font-family-serif:       Georgia, "Times New Roman", Times, serif;
//** Default monospace fonts for `<code>`, `<kbd>`, and `<pre>`.
@font-family-monospace:   Monaco, Menlo, Consolas, "Courier New", monospace;
@font-family-base:        @font-family-sans-serif;

@font-size-base:          ' . $font_size_base . 'px;
@font-size-large:         ceil((@font-size-base * 1.25)); // ~18px
@font-size-small:         ceil((@font-size-base * 0.85)); // ~12px

@font-size-h1:            floor((@font-size-base * ' . $font_h1_size . ')); // ~36px
@font-size-h2:            floor((@font-size-base * ' . $font_h2_size . ')); // ~30px
@font-size-h3:            ceil((@font-size-base * ' . $font_h3_size . ')); // ~24px
@font-size-h4:            ceil((@font-size-base * ' . $font_h4_size . ')); // ~18px
@font-size-h5:            ' . $font_h5_size . ';
@font-size-h6:            ceil((@font-size-base * ' . $font_h6_size . ')); // ~12px

//** Unit-less `line-height` for use in components like buttons.
@line-height-base:        1.428571429; // 20/14
@line-height-computed:    floor((@font-size-base * @line-height-base)); // ~20px

//** By default, this inherits from the `<body>`.
@headings-font-family:    inherit;
@headings-font-weight:    500;
@headings-line-height:    1.1;
@headings-color:          inherit;

//-- Iconography
//
//## Specify custom locations of the include Glyphicons icon font. Useful for those including Bootstrap via Bower.
 
@icon-font-path:          "../fonts/";
@icon-font-name:          "glyphicons-halflings-regular";
@icon-font-svg-id:        "glyphicons_halflingsregular";

//== Components
//
//## Define common padding and border radius sizes and more. Values based on 14px text and 1.428 line-height (~20px to start).

@padding-base-vertical:          ' . round( $padding_base * 1.33 ) . 'px;
@padding-base-horizontal:        ' . round( $padding_base * 1.5 ) . 'px;

@padding-large-vertical:         ' . round( $padding_base * 1.25 ) . 'px;
@padding-large-horizontal:       ' . ( $padding_base * 2 ) . 'px;

@padding-small-vertical:         ' . round( $padding_base * 0.625 ) . 'px;
@padding-small-horizontal:       @padding-large-vertical;

@padding-xs-vertical:            ' . round( $padding_base * 0.125 ) . 'px;
@padding-xs-horizontal:          @padding-small-vertical;

@line-height-large:              1.33;
@line-height-small:              1.5;

@border-radius-base:             ' . $border_radius . 'px;
@border-radius-large:            ceil(@border-radius-base * 1.5);
@border-radius-small:            floor(@border-radius-base * 0.75);

//** Global color for active items (e.g., navs or dropdowns).
//** Global background color for active items (e.g., navs or dropdowns).
@component-active-bg:       @brand-primary;

//** Width of the `border` for generating carets that indicator dropdowns.
@caret-width-base:               ceil(@font-size-small / 3 ); // ~4px
//** Carets increase slightly in size for larger components.
@caret-width-large:              ceil(@caret-width-base * (5/4) ); // ~5px

//== Tables
//
//## Customizes the `.table` component with basic values, each used across all table variations.

//** Padding for `<th>`s and `<td>`s.
@table-cell-padding:                 ceil((@font-size-small * 2) / 3 ); // ~8px;
//** Padding for cells in `.table-condensed`.
@table-condensed-cell-padding:       ceil(((@font-size-small / 3 ) * 5) / 4); // ~5px

//** Default background color used for all tables.
@table-bg:                           transparent;
//** Background color used for `.table-striped`.
@table-bg-accent:                    ' . $table_bg_accent . ';
//** Background color used for `.table-hover`.
@table-bg-hover:                     ' . $table_bg_hover . ';
@table-bg-active:                    @table-bg-hover;

//** Border color for table and cell borders.
@table-border-color:                 ' . $table_border_color . '; // table and cell border


//== Buttons
//
//## For each of Bootstraps buttons, define text, background and border color.

@btn-font-weight:                normal;

@btn-default-color:              @gray-dark;

@btn-primary-color:              ' . $btn_primary_color . ';
@btn-primary-bg:                 @brand-primary;
@btn-primary-border:             ' . $btn_primary_border . ';

@btn-success-color:              ' . $btn_success_color . ';
@btn-success-bg:                 @brand-success;
@btn-success-border:             ' . $btn_success_border . ';

@btn-info-color:                 ' . $btn_info_color . ';
@btn-info-bg:                    @brand-info;
@btn-info-border:                ' . $btn_info_border . ';

@btn-warning-color:              ' . $btn_warning_color . ';
@btn-warning-bg:                 @brand-warning;
@btn-warning-border:             ' . $btn_warning_border . ';

@btn-danger-color:               ' . $btn_danger_color . ';
@btn-danger-bg:                  @brand-danger;
@btn-danger-border:              ' . $btn_danger_border . ';

@btn-link-disabled-color:        @gray-light;


//== Forms
//
//##

//** `<input disabled>` background color
@input-bg-disabled:              @gray-lighter;

@input-color:                    @gray;
@input-border-radius:            @border-radius-base;
@input-border-focus:             ' . $input_border_focus . ';

//** Placeholder text color
@input-color-placeholder:        @gray-light;

//** Default `.form-control` height
@input-height-base:              (@line-height-computed + (@padding-base-vertical * 2) + 2);
//** Large `.form-control` height
@input-height-large:             (floor(@font-size-large * @line-height-large) + (@padding-large-vertical * 2) + 2);
//** Small `.form-control` height
@input-height-small:             (floor(@font-size-small * @line-height-small) + (@padding-small-vertical * 2) + 2);

@legend-color:                   @gray-dark;
@legend-border-color:            @gray-lighter;

//** Background color for textual input addons
@input-group-addon-bg:           @gray-lighter;
//** Border color for textual input addons
@input-group-addon-border-color: @input-border;


//== Dropdowns
//
//## Dropdown menu container and contents.

//** Dropdown menu `border-color`.
@dropdown-border:                rgba(0,0,0,.15);
//** Dropdown menu `border-color` **for IE8**.
@dropdown-fallback-border:       @input-border;
//** Divider color for between dropdown items.
@dropdown-divider-bg:            @legend-border-color;

//** Dropdown link text color.
@dropdown-link-color:            @gray-dark;
//** Hover color for dropdown links.
@dropdown-link-hover-color:      darken(@gray-dark, 5%);
//** Hover background for dropdown links.
@dropdown-link-hover-bg:         @table-bg-hover;

//** Active dropdown menu item text color.
@dropdown-link-active-color:     @component-active-color;
//** Active dropdown menu item background color.
@dropdown-link-active-bg:        @component-active-bg;

//** Disabled dropdown menu item background color.
@dropdown-link-disabled-color:   @gray-light;

//** Text color for headers within dropdown menus.
@dropdown-header-color:          @gray-light;


//-- Z-index master list
//
// Warning: Avoid customizing these values. They are used for a birds eye view
// of components dependent on the z-axis and are designed to all work together.
//
// Note: These variables are not generated into the Customizer.

@zindex-navbar:            1000;
@zindex-dropdown:          1000;
@zindex-popover:           1010;
@zindex-tooltip:           1030;
@zindex-navbar-fixed:      1030;
@zindex-modal-background:  1040;
@zindex-modal:             1050;

//== Media queries breakpoints
//
//## Define the breakpoints at which your layout will change, adapting to different screen sizes.

// Extra small screen / phone
// Note: Deprecated @screen-xs and @screen-phone as of v3.0.1
@screen-xs:                  480px;
@screen-xs-min:              @screen-xs;
@screen-phone:               @screen-xs-min;

// Small screen / tablet
// Note: Deprecated @screen-sm and @screen-tablet as of v3.0.1
@screen-sm:                  ' . $screen_sm . 'px;
@screen-sm-min:              @screen-sm;
@screen-tablet:              @screen-sm-min;

// Medium screen / desktop
// Note: Deprecated @screen-md and @screen-desktop as of v3.0.1
@screen-md:                  ' . $screen_md . 'px;
@screen-md-min:              @screen-md;
@screen-desktop:             @screen-md-min;

// Large screen / wide desktop
// Note: Deprecated @screen-lg and @screen-lg-desktop as of v3.0.1
@screen-lg:                  ' . $screen_lg . 'px;
@screen-lg-min:              @screen-lg;
@screen-lg-desktop:          @screen-lg-min;

// So media queries dont overlap when required, provide a maximum
@screen-xs-max:              (@screen-sm-min - 1);
@screen-sm-max:              (@screen-md-min - 1);
@screen-md-max:              (@screen-lg-min - 1);


//== Grid system
//
//## Define your custom responsive grid.

//** Number of columns in the grid.
@grid-columns:              12;
//** Padding between columns. Gets divided in half for the left and right.
@grid-gutter-width:         ' . $gutter . 'px;

// Navbar collapse

//** Point at which the navbar becomes uncollapsed.
@grid-float-breakpoint:     ' . $grid_float_breakpoint . ';
//** Point at which the navbar begins collapsing.
@grid-float-breakpoint-max: (@grid-float-breakpoint - 1);


//== Navbar
//
//##

// Basics of a navbar
@navbar-height:                    ' . $navbar_height . 'px;
@navbar-margin-bottom:             @line-height-computed;
@navbar-border-radius:             @border-radius-base;
@navbar-padding-horizontal:        floor((@grid-gutter-width / 2));
@navbar-padding-vertical:          ((@navbar-height - @line-height-computed) / 2);
@navbar-collapse-max-height:       340px;

@navbar-default-color:             ' . $navbar_text_color . ';
@navbar-default-bg:                ' . $navbar_bg . ';
@navbar-default-border:            ' . $navbar_border . ';

// Navbar links
@navbar-default-link-color:                @navbar-default-color;
@navbar-default-link-hover-color:          ' . $navbar_link_hover_color . ';
@navbar-default-link-hover-bg:             transparent;
@navbar-default-link-active-color:         mix(@navbar-default-color, @navbar-default-link-hover-color, 50%);
@navbar-default-link-active-bg:            ' . $navbar_link_active_bg . ';
@navbar-default-link-disabled-color:       ' . $navbar_link_disabled_color . ';
@navbar-default-link-disabled-bg:          transparent;

// Navbar brand label
@navbar-default-brand-color:               @navbar-default-link-color;
@navbar-default-brand-hover-color:         ' . $navbar_brand_hover_color . ';
@navbar-default-brand-hover-bg:            transparent;

// Navbar toggle
@navbar-default-toggle-hover-bg:           ' . $navbar_border . ';
@navbar-default-toggle-icon-bar-bg:        ' . $navbar_text_color . ';
@navbar-default-toggle-border-color:       ' . $navbar_border . ';


// Inverted navbar
// Reset inverted navbar basics
@navbar-inverse-color:                      @gray-light;
@navbar-inverse-bg:                         #222;
@navbar-inverse-border:                     darken(@navbar-inverse-bg, 10%);

// Inverted navbar links
@navbar-inverse-link-color:                 @gray-light;
@navbar-inverse-link-hover-color:           #fff;
@navbar-inverse-link-hover-bg:              transparent;
@navbar-inverse-link-active-color:          @navbar-inverse-link-hover-color;
@navbar-inverse-link-active-bg:             darken(@navbar-inverse-bg, 10%);
@navbar-inverse-link-disabled-color:        #444;
@navbar-inverse-link-disabled-bg:           transparent;

// Inverted navbar brand label
@navbar-inverse-brand-color:                @navbar-inverse-link-color;
@navbar-inverse-brand-hover-color:          #fff;
@navbar-inverse-brand-hover-bg:             transparent;

// Inverted navbar toggle
@navbar-inverse-toggle-hover-bg:            #333;
@navbar-inverse-toggle-icon-bar-bg:         #fff;
@navbar-inverse-toggle-border-color:        #333;


//== Navs
//
//##

//=== Shared nav styles
@nav-link-padding:                          10px 15px;
@nav-link-hover-bg:                         @gray-lighter;

@nav-disabled-link-color:                   @gray-light;
@nav-disabled-link-hover-color:             @gray-light;


//== Tabs
@nav-tabs-border-color:                     @table-border-color;

@nav-tabs-link-hover-border-color:          @gray-lighter;

@nav-tabs-active-link-hover-color:          @gray;
@nav-tabs-active-link-hover-border-color:   @table-border-color;

@nav-tabs-justified-link-border-color:            @table-border-color;

//== Pills
@nav-pills-border-radius:                   @border-radius-base;
@nav-pills-active-link-hover-bg:            @component-active-bg;
@nav-pills-active-link-hover-color:         @component-active-color;


//== Pagination
//
//##

@pagination-color:                     @link-color;
@pagination-border:                    ' . $table_border_color . ';

@pagination-hover-color:               @link-hover-color;
@pagination-hover-bg:                  @gray-lighter;
@pagination-hover-border:              @table-border-color;

@pagination-active-bg:                 @brand-primary;
@pagination-active-border:             @brand-primary;

@pagination-disabled-color:            @gray-light;
@pagination-disabled-border:           @table-border-color;

//== Pager
//
//##

@pager-bg:                             @pagination-bg;
@pager-border:                         @pagination-border;
@pager-border-radius:                  @navbar-padding-horizontal;

@pager-hover-bg:                       @pagination-hover-bg;

@pager-active-bg:                      @pagination-active-bg;
@pager-active-color:                   @pagination-active-color;

@pager-disabled-color:                 @pagination-disabled-color;


//== Jumbotron
//
//##

@jumbotron-padding:              (@border-radius-large * 5);
@jumbotron-color:                ' . $jumbotron_text_color . ';
@jumbotron-bg:                   ' . $jumbotron_bg . ';
@jumbotron-heading-color:        ' . $jumbotron_headers_text_color . ';
@jumbotron-font-size:            ' . $font_jumbotron['font-size'] . 'px;


//== Form states and alerts
//
//## Define colors for form feedback states and, by default, alerts.

@state-success-text:             #3c763d;
@state-success-bg:               #dff0d8;
@state-success-border:           darken(spin(@state-success-bg, -10), 5%);

@state-info-text:                #31708f;
@state-info-bg:                  #d9edf7;
@state-info-border:              darken(spin(@state-info-bg, -10), 7%);

@state-warning-text:             #8a6d3b;
@state-warning-bg:               #fcf8e3;
@state-warning-border:           darken(spin(@state-warning-bg, -10), 5%);

@state-danger-text:              #a94442;
@state-danger-bg:                #f2dede;
@state-danger-border:            darken(spin(@state-danger-bg, -10), 5%);


//== Tooltips
//
//##

//** Tooltip max width
@tooltip-max-width:           200px;
//** Tooltip background color
@tooltip-bg:                  darken(@gray-darker, 15%);
@tooltip-opacity:             .9;

//** Tooltip arrow width
@tooltip-arrow-width:         @padding-small-vertical;
//** Tooltip arrow color
@tooltip-arrow-color:         @tooltip-bg;


//== Popovers
//
//##

//** Popover maximum width
@popover-max-width:                   276px;
//** Popover border color
@popover-border-color:                rgba(0,0,0,.2);

//** Popover title background color
@popover-title-bg:                    darken(@popover-bg, 3%);

//** Popover arrow width
@popover-arrow-width:                 (@tooltip-arrow-width * 2);

//** Popover outer arrow width
@popover-arrow-outer-width:           (@popover-arrow-width + 1);
//** Popover outer arrow color
@popover-arrow-outer-color:           rgba(0,0,0,.25);
//** Popover outer arrow fallback color
@popover-arrow-outer-fallback-color:  @gray-light;


//== Labels
//
//##

//** Default label background color
@label-default-bg:            @gray-light;
//** Primary label background color
@label-primary-bg:            @brand-primary;
//** Success label background color
@label-success-bg:            @brand-success;
//** Info label background color
@label-info-bg:               @brand-info;
//** Warning label background color
@label-warning-bg:            @brand-warning;
//** Danger label background color
@label-danger-bg:             @brand-danger;


//== Modals
//
//##

//** Padding applied to the modal body
@modal-inner-padding:         @line-height-computed;

//** Padding applied to the modal title
@modal-title-padding:         ceil(@modal-inner-padding * (4/3)); // ~15px
//** Modal title line-height
@modal-title-line-height:     @line-height-base;

//** Modal content border color
@modal-content-border-color:                   rgba(0,0,0,.2);
//** Modal content border color **for IE8**
@modal-content-fallback-border-color:          @gray-light;

//** Modal backdrop background color
@modal-backdrop-bg:           darken(@gray-darker, 15%);
//** Modal backdrop opacity
@modal-backdrop-opacity:      .5;
//** Modal header border color
@modal-header-border-color:   lighten(@gray-lighter, 12%);
//** Modal footer border color
@modal-footer-border-color:   @modal-header-border-color;

@modal-lg:                    900px;
@modal-md:                    600px;
@modal-sm:                    300px;


//== Alerts
//
//## Define alert colors, border radius, and padding.
@alert-padding:               15px;
@alert-border-radius:         @border-radius-base;
@alert-link-font-weight:      bold;

@alert-success-bg:            @state-success-bg;
@alert-success-text:          @state-success-text;
@alert-success-border:        @state-success-border;

@alert-info-bg:               @state-info-bg;
@alert-info-text:             @state-info-text;
@alert-info-border:           @state-info-border;

@alert-warning-bg:            @state-warning-bg;
@alert-warning-text:          @state-warning-text;
@alert-warning-border:        @state-warning-border;

@alert-danger-bg:             @state-danger-bg;
@alert-danger-text:           @state-danger-text;
@alert-danger-border:         @state-danger-border;


//== Progress bars
//
//##

//** Background color of the whole progress component
@progress-bg:                 ' . $table_bg_hover . ';
//** Progress bar text color

//** Default progress bar color
@progress-bar-bg:             @brand-primary;
//** Success progress bar color
@progress-bar-success-bg:     @brand-success;
//** Warning progress bar color
@progress-bar-warning-bg:     @brand-warning;
//** Danger progress bar color
@progress-bar-danger-bg:      @brand-danger;
//** Info progress bar color
@progress-bar-info-bg:        @brand-info;


//== List group
//
//##

//** `.list-group-item` border color
@list-group-border:           ' . $table_border_color . ';
//** List group border radius
@list-group-border-radius:    @border-radius-base;

//** Background color of single list elements on hover
@list-group-hover-bg:         ' . $table_bg_hover . ';
//** Text color of active list elements
@list-group-active-color:     @component-active-color;
//** Background color of active list elements
@list-group-active-bg:        @component-active-bg;
//** Border color of active list elements
@list-group-active-border:    @list-group-active-bg;
@list-group-active-text-color:  lighten(@list-group-active-bg, 40%);

@list-group-link-color:          @gray;
@list-group-link-heading-color:  @gray-dark;


//== Panels
//
//##
@panel-body-padding:          floor((@grid-gutter-width / 2));
@panel-border-radius:         @border-radius-base;

//** Border color for elements within panels
@panel-inner-border:          @list-group-border;
@panel-footer-bg:             @list-group-hover-bg;

@panel-default-text:          @gray-dark;
@panel-default-border:        @table-border-color;
@panel-default-heading-bg:    @panel-footer-bg;

@panel-primary-border:        @brand-primary;
@panel-primary-heading-bg:    @brand-primary;

@panel-success-text:          @state-success-text;
@panel-success-border:        @state-success-border;
@panel-success-heading-bg:    @state-success-bg;

@panel-info-text:             @state-info-text;
@panel-info-border:           @state-info-border;
@panel-info-heading-bg:       @state-info-bg;

@panel-warning-text:          @state-warning-text;
@panel-warning-border:        @state-warning-border;
@panel-warning-heading-bg:    @state-warning-bg;

@panel-danger-text:           @state-danger-text;
@panel-danger-border:         @state-danger-border;
@panel-danger-heading-bg:     @state-danger-bg;


//== Thumbnails
//
//##
//** Padding around the thumbnail image
@thumbnail-padding:           ceil(@table-cell-padding / 2 );
//** Thumbnail border color
@thumbnail-border:            @list-group-border;
//** Thumbnail border radius
@thumbnail-border-radius:     @border-radius-base;

//** Custom text color for thumbnail captions
@thumbnail-caption-color:     @text-color;
//** Padding around the thumbnail caption
@thumbnail-caption-padding:   @table-cell-padding;


//== Wells
//
//##
@well-bg:                     @table-bg-hover;
@well-border:                 darken(@well-bg, 7%);


//== Badges
//
//##

//** Badge background color in active nav link
@badge-bg:                    @gray-light;

//** Badge text color in active nav link
@badge-active-color:          @link-color;

@badge-font-weight:           bold;
@badge-line-height:           1;
@badge-border-radius:         10px;


//== Breadcrumbs
//
//##

@breadcrumb-padding-vertical:   @table-cell-padding;
@breadcrumb-padding-horizontal: @modal-title-padding;
//** Breadcrumb background color
@breadcrumb-bg:                 @table-bg-hover;
//** Text color of current page in the breadcrumb
@breadcrumb-active-color:       @gray-light;
//** Textual separator for between breadcrumb elements
@breadcrumb-separator:          "/";


//== Carousel
//
//##

@carousel-text-shadow:                        0 1px 2px rgba(0,0,0,.6);

@carousel-control-width:                      15%;
@carousel-control-opacity:                    .5;
@carousel-control-font-size:                  @line-height-computed;



//== Close
//
//##

@close-font-weight:           bold;
@close-color:                 darken(@gray-darker, 15%);


// Code
// ------------------------
@code-color:                  #c7254e;
@code-bg:                     #f9f2f4;

@kbd-color:                   #fff;
@kbd-bg:                      #333;

@pre-bg:                      #f5f5f5;
@pre-color:                   @gray-dark;
@pre-border-color:            #ccc;
@pre-scrollable-max-height:   340px;


//== Type
//
//##

//** Text muted color
@text-muted:                  @gray-light;
//** Abbreviations and acronyms border color
@abbr-border-color:           @gray-light;
//** Headings small color
@headings-small-color:        @gray-light;
//** Blockquote small color
@blockquote-small-color:      @gray-light;
//** Blockquote border color
@blockquote-border-color:     @gray-lighter;
//** Page header border color
@page-header-border-color:    @gray-lighter;

//== Miscellaneous
//
//##

//** Horizontal line color.
@hr-border:                   @gray-lighter;

//** Horizontal offset for forms and lists.
@component-offset-horizontal: 180px;


//== Container sizes
//
//## Define the maximum width of `.container` for different screen sizes.

// Small screen / tablet
@container-tablet:           ' . ( $screen_sm - ( $gutter / 2 ) ). 'px;
//** For `@screen-sm-min` and up.
@container-sm:               @container-tablet;

// Medium screen / desktop
@container-desktop:          ' . ( $screen_md - ( $gutter / 2 ) ). 'px;
//** For `@screen-md-min` and up.
@container-md:               @container-desktop;

// Large screen / wide desktop
@container-large-desktop:    ' . ( $screen_lg - $gutter ). 'px;
//** For `@screen-lg-min` and up.
@container-lg:                 @container-large-desktop;


// Shoestrap-specific variables
// --------------------------------------------------

@navbar-font-size:        ' . $font_navbar['font-size'] . 'px;
@navbar-font-weight:      ' . $font_navbar['font-weight'] . ';
@navbar-font-style:       ' . $font_navbar['font-style'] . ';
@navbar-font-family:      ' . $font_navbar['font-family'] . ';
@navbar-font-color:       ' . $navbar_text_color . ';

@brand-font-size:         ' . $font_brand['font-size'] . 'px;
@brand-font-weight:       ' . $font_brand['font-weight'] . ';
@brand-font-style:        ' . $font_brand['font-style'] . ';
@brand-font-family:       ' . $font_brand['font-family'] . ';
@brand-font-color:        ' . $brand_text_color . ';

@jumbotron-font-weight:       ' . $font_jumbotron['font-weight'] . ';
@jumbotron-font-style:        ' . $font_jumbotron['font-style'] . ';
@jumbotron-font-family:       ' . $font_jumbotron['font-family'] . ';

@jumbotron-headers-font-weight:       ' . $font_jumbotron_headers_weight . ';
@jumbotron-headers-font-style:        ' . $font_jumbotron_headers_style . ';
@jumbotron-headers-font-family:       ' . $font_jumbotron_headers_face . ';

// H1
@heading-h1-face:         ' . $font_h1_face . ';
@heading-h1-weight:       ' . $font_h1_weight . ';
@heading-h1-style:        ' . $font_h1_style . ';
@heading-h1-color:        ' . $font_h1_color . ';

// H2
@heading-h2-face:         ' . $font_h2_face . ';
@heading-h2-weight:       ' . $font_h2_weight . ';
@heading-h2-style:        ' . $font_h2_style . ';
@heading-h2-color:        ' . $font_h2_color . ';

// H3
@heading-h3-face:         ' . $font_h3_face . ';
@heading-h3-weight:       ' . $font_h3_weight . ';
@heading-h3-style:        ' . $font_h3_style . ';
@heading-h3-color:        ' . $font_h3_color . ';

// H4
@heading-h4-face:         ' . $font_h4_face . ';
@heading-h4-weight:       ' . $font_h4_weight . ';
@heading-h4-style:        ' . $font_h4_style . ';
@heading-h4-color:        ' . $font_h4_color . ';

// H5
@heading-h5-face:         ' . $font_h5_face . ';
@heading-h5-weight:       ' . $font_h5_weight . ';
@heading-h5-style:        ' . $font_h5_style . ';
@heading-h5-color:        ' . $font_h5_color . ';

// H6
@heading-h6-face:         ' . $font_h6_face . ';
@heading-h6-weight:       ' . $font_h6_weight . ';
@heading-h6-style:        ' . $font_h6_style . ';
@heading-h6-color:        ' . $font_h6_color . ';

@navbar-margin-top:       ' . shoestrap_getVariable( 'navbar_margin_top' ) . 'px;

';

if ( $site_style == 'static' ):
// disable responsiveness
  $variables .= '
    @screen-xs-max: 0 !important; 
    .container { max-width: none !important; width: @container-large-desktop; }
    html { overflow-x: auto !important; }
  ';
endif;

  return $variables;
}
endif;