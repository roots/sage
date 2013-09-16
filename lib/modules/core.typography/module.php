<?php

if ( !function_exists( 'shoestrap_module_typography_options' ) ) :
/*
 * The typography core options for the Shoestrap theme
 */
function shoestrap_module_typography_options( $sections ) {

  // Typography Options
  $section = array( 
  		'title'   => __( 'Typography', 'shoestrap' ),
  		'icon'    => 'elusive icon-font icon-large',
  	 );

  $fields[] = array( 
    'title'     => __( 'Base Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_base',
    'compiler'  => true,
    'units'     => 'px',
    'default'   => array( 
      'family'  => 'Arial, Helvetica, sans-serif',
      'size'    => 14,
      'google'  => 'false',
      'weight'  => 'inherit',
      'color'   => '#333333',
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'size'    => 30 //this is the text size from preview box
    ),
    'type'      => 'typography',
  );

  $fields[] = array( 
    'title'     => __( 'Header Overrides', 'shoestrap' ),
    'desc'      => __( 'By enabling this you can specify custom values for each <h*> tag. Default: Off', 'shoestrap' ),
    'id'        => 'font_heading_custom',
    'default'   => 0,
    'compiler'  => true,
    'type'      => 'switch',
    'customizer'=> array(),
    // 'fold'      => 'advanced_toggle'
  );

  $fields[] = array( 
    'title'     => __( 'H1 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h1',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'family'  => 'Arial, Helvetica, sans-serif',
      'size'    => 260,
      'color'   => '#333333',
      'google'  => 'false'
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'size'    => 30 //this is the text size from preview box
    ),
    'type'      => 'typography',
    // 'fold'      => 'font_heading_custom',
  );

  $fields[] = array( 
    'id'		    => 'font_h2',
    'title'     => __( 'H2 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'family'  => 'Arial, Helvetica, sans-serif',
      'size'    => 215,
      'color'   => '#333333',
      'google'  => 'false'
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'size'    => 30 //this is the text size from preview box
    ),
    'type'      => 'typography',
    // 'fold'      => 'font_heading_custom',
  );

  $fields[] = array( 
    'id'		    => 'font_h3',
    'title'     => __( 'H3 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'family'  => 'Arial, Helvetica, sans-serif',
      'size'    => 170,
      'color'   => '#333333',
      'google'  => 'false'
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'size'    => 30 //this is the text size from preview box
    ),
    'type'      => 'typography',
    // 'fold'      => 'font_heading_custom',
  );

  $fields[] = array( 
    'title'     => __( 'H4 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h4',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'family'  => 'Arial, Helvetica, sans-serif',
      'size'    => 125,
      'color'   => '#333333',
      'google'  => 'false'
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'size'    => 30 //this is the text size from preview box
    ),
    'type'      => 'typography',
    // 'fold'      => 'font_heading_custom',
  );

  $fields[] = array( 
    'title'     => __( 'H5 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h5',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'family'  => 'Arial, Helvetica, sans-serif',
      'size'    => 100,
      'color'   => '#333333',
      'google'  => 'false'
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'size'    => 30 //this is the text size from preview box
    ),
    'type'      => 'typography',
    // 'fold'      => 'font_heading_custom',
  );

  $fields[] = array( 
    'title'     => __( 'H6 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h6',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'family'  => 'Arial, Helvetica, sans-serif',
      'size'    => 85,
      'color'   => '#333333',
      'google'  => 'false'
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'size'    => 30 //this is the text size from preview box
    ),
    'type'      => 'typography',
    // 'fold'      => 'font_heading_custom',
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_typography_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_typography_options', 80 ); 
endif;

include_once( dirname( __FILE__ ).'/functions.typography.php' );