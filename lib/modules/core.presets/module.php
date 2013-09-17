<?php

/*
 * The page core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_presets_options' ) ) :
function shoestrap_module_presets_options( $sections ) {
  // Page Options
  $section = array(
    'title' => __( 'Presets', 'shoestrap' ),
    'icon' => 'elusive icon-file icon-large',
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
      '1'         => array(
        'alt'     => 'Preset 1',
        'img'     => REDUX_URL . 'sample/presets/preset1.png',
        'presets' =>array(
          'color_body_bg' =>'#f7f7f7',
          'navbar_toggle' =>'on',
          'navbar_bg'     =>'#333333',
        )
      ),
      '2' => array(
        'alt' => 'Preset 1',
        'img' => REDUX_URL . 'sample/presets/preset2.png',
        'presets'=>array(
          'switch-on'=>1,
          'switch-off'=>1,
          'switch-custom'=>1
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