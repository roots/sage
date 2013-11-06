<?php

if ( !function_exists( 'shoestrap_module_typography_options' ) ) :
/*
 * The typography core options for the Shoestrap theme
 */
function shoestrap_module_typography_options( $sections ) {

  // Typography Options
  $section = array(
    'title'   => __( 'Typography', 'shoestrap' ),
    'icon'    => 'el-icon-font icon-large',
  );

  $fields[] = array( 
    'title'     => __( 'Base Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_base',
    'compiler'  => true,
    'units'     => 'px',
    'default'   => array( 
      'font-family' => 'Arial, Helvetica, sans-serif',
      'font-size'   => '14px',
      'google'      => 'false',
      'weight'      => 'inherit',
      'color'       => '#333333',
    ),
    'preview'   => array( 
      'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'font-size'   => '30px' //this is the text size from preview box
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
    // 'required'  => array('advanced_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'H1 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h1',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'font-family' => 'Arial, Helvetica, sans-serif',
      'font-size'   => '260%',
      'color'       => '#333333',
      'google'      => 'false'
    ),
    'preview'   => array( 
      'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'font-size'   => '30px' //this is the text size from preview box
    ),
    'type'      => 'typography',
    'required'  => array('font_heading_custom','=',array('1')),
  );

  $fields[] = array( 
    'id'        => 'font_h2',
    'title'     => __( 'H2 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'font-family' => 'Arial, Helvetica, sans-serif',
      'font-size'   => '215%',
      'color'       => '#333333',
      'google'      => 'false'
    ),
    'preview'   => array( 
      'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'font-size'   => '30px' //this is the text size from preview box
    ),
    'type'      => 'typography',
    'required'  => array('font_heading_custom','=',array('1')),    
  );

  $fields[] = array( 
    'id'        => 'font_h3',
    'title'     => __( 'H3 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'font-family' => 'Arial, Helvetica, sans-serif',
      'font-size'   => '170%',
      'color'       => '#333333',
      'google'      => 'false'
    ),
    'preview'   => array( 
      'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'font-size'   => '30px' //this is the text size from preview box
    ),
    'type'      => 'typography',
    'required'  => array('font_heading_custom','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'H4 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h4',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'font-family' => 'Arial, Helvetica, sans-serif',
      'font-size'   => '125%',
      'color'       => '#333333',
      'google'      => 'false'
    ),
    'preview'   => array( 
      'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'font-size'   => '30px' //this is the text size from preview box
    ),
    'type'      => 'typography',
    'required'  => array('font_heading_custom','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'H5 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h5',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'font-family' => 'Arial, Helvetica, sans-serif',
      'font-size'   => '100%',
      'color'       => '#333333',
      'google'      => 'false'
    ),
    'preview'       => array( 
      'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'font-size'   => '30px' //this is the text size from preview box
    ),
    'type'      => 'typography',
    'required'  => array('font_heading_custom','=',array('1')),
  );

  $fields[] = array( 
    'title'     => __( 'H6 Font', 'shoestrap' ),
    'desc'      => __( 'The main font for your site.', 'shoestrap' ),
    'id'        => 'font_h6',
    'compiler'  => true,
    'units'     => '%',
    'default'   => array( 
      'font-family' => 'Arial, Helvetica, sans-serif',
      'font-size'   => '85%',
      'color'       => '#333333',
      'google'      => 'false'
    ),
    'preview'   => array( 
      'text'        => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
      'font-size'   => '30px' //this is the text size from preview box
    ),
    'type'      => 'typography',
    'required'  => array('font_heading_custom','=',array('1')),
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_typography_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_typography_options', 80 ); 
endif;

include_once( dirname( __FILE__ ).'/functions.typography.php' );