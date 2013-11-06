<?php

/*
 * The header core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_header_options' ) ) :
function shoestrap_module_header_options( $sections ) {

  // Branding Options
  $section = array( 
    'title' => __( 'Extra Header', 'shoestrap' ),
    'icon'  => 'el-icon-chevron-right icon-large'
  );

  $url = admin_url( 'widgets.php' );
  $fields[] = array( 
    'id'          => 'help9',
    'title'       => __( 'Extra Branding Area', 'shoestrap' ),
    'desc'        => __( "You can enable an extra branding/header area. In this header you can add your logo, and any other widgets you wish.
                      To add widgets on your header, visit <a href='$url'>this page</a> and add your widgets to the <strong>Header</strong> Widget Area.", 'shoestrap' ),
    'type'        => 'info',
    // 'required'    => array('advanced_toggle','=',array('1'))
  );

  $fields[] = array( 
    'title'       => __( 'Display the Header.', 'shoestrap' ),
    'desc'        => __( 'Turn this ON to display the header. Default: OFF', 'shoestrap' ),
    'id'          => 'header_toggle',
    'customizer'  => array(),
    'default'     => 0,
    'type'        => 'switch',
    // 'required'    => array('advanced_toggle','=',array('1'))
  );

  $fields[] = array( 
    'title'       => __( 'Display branding on your Header.', 'shoestrap' ),
    'desc'        => __( 'Turn this ON to display branding ( Sitename or Logo )on your Header. Default: ON', 'shoestrap' ),
    'id'          => 'header_branding',
    'customizer'  => array(),
    'default'     => 1,
    'type'        => 'switch',
    'required'    => array('header_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'       => __( 'Header Background Color', 'shoestrap' ),
    'desc'        => __( 'Select the background color for your header. Default: #EEEEEE.', 'shoestrap' ),
    'id'          => 'header_bg',
    'default'     => '#EEEEEE',
    'customizer'  => array(),
    'transparent' => false,    
    'type'        => 'color',
    'required'    => array('header_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'       => __( 'Header Background Opacity', 'shoestrap' ),
    'desc'        => __( 'Select the background opacity for your header. Default: 100%.', 'shoestrap' ),
    'id'          => 'header_bg_opacity',
    'default'     => 100,
    'min'         => 0,
    'step'        => 1,
    'max'         => 100,
    'compiler'    => true,
    'type'        => 'slider',
    'required'    => array('header_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'       => __( 'Header Text Color', 'shoestrap' ),
    'desc'        => __( 'Select the text color for your header. Default: #333333.', 'shoestrap' ),
    'id'          => 'header_color',
    'default'     => '#333333',
    'customizer'  => array(),
    'transparent' => false,    
    'type'        => 'color',
    'required'    => array('header_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'       => __( 'Header Top Margin', 'shoestrap' ),
    'desc'        => __( 'Select the top margin of header in pixels. Default: 0px.', 'shoestrap' ),
    'id'          => 'header_margin_top',
    'default'     => 0,
    'min'         => 0,
    'max'         => 200,
    'type'        => 'slider',
    'required'    => array('header_toggle','=',array('1')),
  );

  $fields[] = array( 
    'title'       => __( 'Header Bottom Margin', 'shoestrap' ),
    'desc'        => __( 'Select the bottom margin of header in pixels. Default: 0px.', 'shoestrap' ),
    'id'          => 'header_margin_bottom',
    'default'     => 0,
    'min'         => 0,
    'max'         => 200,
    'type'        => 'slider',
    'required'    => array('header_toggle','=',array('1')),
  );

  $section['fields'] = $fields;

  $section = apply_filters( 'shoestrap_module_header_options_modifier', $section );
  
  $sections[] = $section;
  return $sections;

}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_header_options', 67 );  

include_once( dirname( __FILE__ ) . '/functions.header.php' );