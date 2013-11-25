<?php

/*
 * The page core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_presets_options' ) ) :
function shoestrap_module_presets_options( $sections ) {
  // Page Options
  $section = array(
    'title' => __( 'Presets', 'shoestrap' ),
    'icon' => 'el-icon-file icon-large',
  );

  $fields[] = array(
    'id'      =>'presets',
    'type'    => 'image_select', 
    'presets' => true,
    'title'   => __('Preset', 'redux-framework'),
    'subtitle'=> __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework'),
    'default' => 0,
    'desc'    => __('This allows you to set a json string or array to override multiple preferences in your theme.', 'redux-framework'),
    'options' => array(
      'metro'     => array(
        'alt'     => 'Metro',
        'img'     => get_template_directory_uri() . '/lib/modules/core.presets/img/metro.png',
        'presets' =>array(
          'gradients_toggle'        => 0,
          'navbar_toggle'           => 'on',
          'color_brand_primary'     => '#52b9e9',
          'color_brand_secondary'   => '#52b9e9',
          'color_brand_success'     => '#43c83c',
          'color_brand_warning'     => '#f88529',
          'color_brand_danger'      => '#fa3031',
          'color_brand_info'        => '#932ab6',
          'site_style'              => 'wide',
          'layout_primary_width'    => '4',
          'layout_secondary_width'  => '3',
          'navbar_margin_top'       => 0,
          'widgets_mode'            => 1,
          'body_margin_top'         => 0,
          'body_margin_bottom'      => 0,
          'navbar_toggle'           => 1,
          'navbar_bg'               => '#222222',
          'navbar_style'            => 'metro',
          'navbar_brand'            => 1,
          'navbar_fixed'            => 0,
          'navbar_height'           => '70',
          'font_navbar'             => array(
            'font-family' => 'Open Sans',
            'font-size'   => 15,
            'color'       => '#ffffff',
            'google'      => 'true',
            'font-weight' => '300',
          ),
          'footer_background'       => '#222222',
          'footer_opacity'          => 0,
          'footer_color'            => '#52b9e9',
          'general_border_radius'   => 0,
          'padding_base'            => 10,
        )
      ),
      'red'       => array(
        'alt'     => 'Red',
        'img'     => get_template_directory_uri() . '/lib/modules/core.presets/img/red.png',
        'presets' =>array(
          'gradients_toggle'        => 0,
          'navbar_toggle'           => 'on',
          'color_brand_primary'     => '#ac2925',
          'color_brand_secondary'   => '#ac2925',
          'color_brand_success'     => '#5cb85c',
          'color_brand_warning'     => '#f0ad4e',
          'color_brand_danger'      => '#d9534f',
          'color_brand_info'        => '#5bc0de',
          'site_style'              => 'wide',
          'layout_primary_width'    => '4',
          'layout_secondary_width'  => '3',
          'navbar_margin_top'       => 0,
          'widgets_mode'            => 1,
          'body_margin_top'         => 0,
          'body_margin_bottom'      => 0,
          'navbar_toggle'           => 1,
          'navbar_bg'               => '#d9534f',
          'navbar_style'            => 'default',
          'navbar_brand'            => 1,
          'navbar_fixed'            => 0,
          'navbar_height'           => '58',
          'font_navbar'             => array(
            'font-family' => 'Open Sans',
            'font-size'   => 15,
            'color'       => '#ffffff',
            'google'      => 'true',
            'font-weight' => '300'
          ),
        )
      ),
      'blue'      => array(
        'alt'     => 'Blue',
        'img'     => get_template_directory_uri() . '/lib/modules/core.presets/img/blue.png',
        'presets' =>array(
          'gradients_toggle'        => 0,
          'navbar_toggle'           => 'on',
          'color_brand_primary'     => '#0088cc',
          'color_brand_secondary'   => '#0088cc',
          'color_brand_success'     => '#5cb85c',
          'color_brand_warning'     => '#f0ad4e',
          'color_brand_danger'      => '#d9534f',
          'color_brand_info'        => '#5bc0de',
          'site_style'              => 'wide',
          'layout_primary_width'    => '4',
          'layout_secondary_width'  => '3',
          'navbar_margin_top'       => 0,
          'widgets_mode'            => 1,
          'body_margin_top'         => 0,
          'body_margin_bottom'      => 0,
          'navbar_toggle'           => 1,
          'navbar_bg'               => '#93dbff',
          'navbar_style'            => 'default',
          'navbar_brand'            => 1,
          'navbar_fixed'            => 0,
          'navbar_height'           => '58',
          'font_navbar'             => array(
            'font-family' => 'Open Sans',
            'font-size'   => 16,
            'color'       => '#ffffff',
            'google'      => 'true',
            'font-weight' => '300'
          ),
        )
      ),
      'green'     => array(
        'alt'     => 'Green',
        'img'     => get_template_directory_uri() . '/lib/modules/core.presets/img/green.png',
        'presets' =>array(
          'gradients_toggle'        => 0,
          'navbar_toggle'           => 'on',
          'color_brand_primary'     => '#5cb85c',
          'color_brand_secondary'   => '#5cb85c',
          'color_brand_success'     => '#5cb85c',
          'color_brand_warning'     => '#f0ad4e',
          'color_brand_danger'      => '#d9534f',
          'color_brand_info'        => '#5bc0de',
          'site_style'              => 'wide',
          'layout_primary_width'    => '4',
          'layout_secondary_width'  => '3',
          'navbar_margin_top'       => 0,
          'widgets_mode'            => 1,
          'body_margin_top'         => 0,
          'body_margin_bottom'      => 0,
          'navbar_toggle'           => 1,
          'navbar_bg'               => '#5cb85c',
          'navbar_style'            => 'default',
          'navbar_brand'            => 1,
          'navbar_fixed'            => 0,
          'navbar_height'           => '58',
          'font_navbar'             => array(
            'font-family' => 'Open Sans',
            'font-size'   => 16,
            'color'       => '#ffffff',
            'google'      => 'true',
            'font-weight' => '300'
          ),
        )
      ),
      'orange'    => array(
        'alt'     => 'Orange',
        'img'     => get_template_directory_uri() . '/lib/modules/core.presets/img/orange.png',
        'presets' =>array(
          'gradients_toggle'        => 0,
          'navbar_toggle'           => 'on',
          'color_brand_primary'     => '#f0ad4e',
          'color_brand_secondary'   => '#f0ad4e',
          'color_brand_success'     => '#5cb85c',
          'color_brand_warning'     => '#f0ad4e',
          'color_brand_danger'      => '#d9534f',
          'color_brand_info'        => '#5bc0de',
          'site_style'              => 'wide',
          'layout_primary_width'    => '4',
          'layout_secondary_width'  => '3',
          'navbar_margin_top'       => 0,
          'widgets_mode'            => 1,
          'body_margin_top'         => 0,
          'body_margin_bottom'      => 0,
          'navbar_toggle'           => 1,
          'navbar_bg'               => '#f0ad4e',
          'navbar_style'            => 'default',
          'navbar_brand'            => 1,
          'navbar_fixed'            => 0,
          'navbar_height'           => '58',
          'font_navbar'             => array(
            'font-family' => 'Open Sans',
            'font-size'   => 16,
            'color'       => '#ffffff',
            'google'      => 'true',
            'font-weight' => '300'
          ),
        )
      ),
    ),
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_presets_options_modifier', $section );
  
  $sections[] = $section;
  
  return $sections;

}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_presets_options', 76 ); 