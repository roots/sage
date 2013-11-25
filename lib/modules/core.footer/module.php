<?php

/*
 * The footer core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_footer_options' ) ) :
function shoestrap_module_footer_options( $sections ) {

  // Branding Options
  $section = array(
    'title' => __( 'Footer', 'shoestrap' ),
    'icon' => 'el-icon-caret-down icon-large'
  );

  $fields[] = array( 
    'title'       => __( 'Footer Background Color', 'shoestrap' ),
    'desc'        => __( 'Select the background color for your footer. Default: #282a2b.', 'shoestrap' ),
    'id'          => 'footer_background',
    'default'     => '#282a2b',
    'customizer'  => array(),
    'transparent' => false,    
    'type'        => 'color'
  );
  
  $fields[] = array( 
    'title'       => __( 'Footer Background Opacity', 'shoestrap' ),
    'desc'        => __( 'Select the opacity level for the footer bar. Default: 100%.', 'shoestrap' ),
    'id'          => 'footer_opacity',
    'default'     => 100,
    'min'         => 0,
    'max'         => 100,
    'type'        => 'slider',
    'required'    => array('retina_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'       => __( 'Footer Text Color', 'shoestrap' ),
    'desc'        => __( 'Select the text color for your footer. Default: #8C8989.', 'shoestrap' ),
    'id'          => 'footer_color',
    'default'     => '#8C8989',
    'customizer'  => array(),
    'transparent' => false,    
    'type'        => 'color'
  );

  $fields[] = array( 
    'title'       => __( 'Footer Text', 'shoestrap' ),
    'desc'        => __( 'The text that will be displayed in your footer. You can use [year] and [sitename] and they will be replaced appropriately. Default: &copy; [year] [sitename]', 'shoestrap' ),
    'id'          => 'footer_text',
    'default'     => '&copy; [year] [sitename]',
    'customizer'  => array(),
    'type'        => 'textarea'
  );

  $fields[] = array( 
    'title'       => 'Footer Border',
    'desc'        => 'Select the border options for your Footer',
    'id'          => 'footer_border',
    'type'        => 'border',
    'all'         => false, 
    'left'        => false, 
    'bottom'      => false, 
    'right'       => false,
    'default'     => array(
      'border-top'      => '0',
      'border-bottom'   => '0',
      'border-style'    => 'solid',
      'border-color'    => '#4B4C4D',
    ),
    // 'required'    => array('advanced_toggle','=',array('1'))
  );

  $fields[] = array( 
    'title'       => __( 'Footer Top Margin', 'shoestrap' ),
    'desc'        => __( 'Select the top margin of footer in pixels. Default: 0px.', 'shoestrap' ),
    'id'          => 'footer_top_margin',
    'default'     => 0,
    'min'         => 0,
    'max'         => 200,
    'type'        => 'slider',
    // 'required'    => array('advanced_toggle','=',array('1'))
  );

  $fields[] = array( 
    'title'       => __( 'Show social icons in footer', 'shoestrap' ),
    'desc'        => __( 'Show social icons in the footer. Default: On.', 'shoestrap' ),
    'id'          => 'footer_social_toggle',
    'default'     => 0,
    'customizer'  => array(),
    'type'        => 'switch',
    // 'required'    => array('advanced_toggle','=',array('1'))
  );

  $fields[] = array( 
    'title'       => __( 'Footer social links column width', 'shoestrap' ),
    'desc'        => __( 'You can customize the width of the footer social links area. The footer text width will be adjusted accordingly. Default: 5.', 'shoestrap' ),
    'id'          => 'footer_social_width',
    'required'    => array('footer_social_toggle','=',array('1')),
    'default'     => 6,
    'min'         => 3,
    'step'        => 1,
    'max'         => 10,
    'customizer'  => array(),
    'type'        => 'slider',
  );    

  $fields[] = array( 
    'title'       => __( 'Footer social icons open new window', 'shoestrap' ),
    'desc'        => __( 'Social icons in footer will open a new window. Default: On.', 'shoestrap' ),
    'id'          => 'footer_social_new_window_toggle',
    'required'    => array('footer_social_toggle','=',array('1')),
    'default'     => 1,
    'customizer'  => array(),
    'type'        => 'switch',
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_footer_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;
}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_footer_options', 90 );   

include_once( dirname( __FILE__ ) . '/functions.footer.php' );