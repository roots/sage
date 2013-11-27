<?php

/*
 * The updater core options for the Shoestrap theme
 * Simply adds the option in the Redux Framework
 */
if ( !function_exists( 'shoestrap_core_licencing_options' ) ) :
function shoestrap_core_licencing_options( $sections ) {
  // Licencing Options
  $section = array( 
    'title'     => __( 'Licencing', 'shoestrap' ),
    'icon'      => 'el-icon-repeat-alt icon-large',
  );
  $theme = wp_get_theme();
  $fields[] = array( 
    'title'            => __( 'Shoestrap Theme Licence', 'shoestrap' ),
    'id'              => 'shoestrap_license_key',
    'default'         => '',
    'type'            => 'edd_license',
    'mode'            => 'theme', // theme|plugin
    'path'            => '', // Path to the plugin/template main file
    'remote_api_url'  => 'http://shoestrap.org',    // our store URL that is running EDD
    'field_id'        => "shoestrap_license_key", // ID of the field used by EDD
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_licencing_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
add_filter( 'redux-sections-' . REDUX_OPT_NAME, 'shoestrap_core_licencing_options', 200 ); 
endif;
