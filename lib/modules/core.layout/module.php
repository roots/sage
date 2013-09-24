<?php

/*
 * The layout core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_layout_options' ) ) :
function shoestrap_module_layout_options( $sections ) {

  // Layout Settings
  $section = array( 
    'title'       => __( 'Layout', 'shoestrap' ),
    'icon'        => 'elusive icon-screen icon-large',
    'description' => '<p>In this area you can select your site\'s layout, the width of your sidebars, as well as other, more advanced options.</p>',
  );

  $fields[] = array( 
    'title'     => __( 'Site Style', 'shoestrap' ),
    'desc'      => __( 'Select the default site layout. Default: Wide', 'shoestrap' ),
    'id'        => 'site_style',
    'default'   => 'wide',
    'type'      => 'select',
    'customizer'=> array(),
    'options'   => array( 
      'wide'    =>'Wide',
      'boxed'   =>'Boxed',
      'fluid'   =>'Fluid',
    )
  );

  $fields[] = array( 
    'title'     => __( 'Layout', 'shoestrap' ),
    'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
    'id'        => 'layout',
    'default'   => shoestrap_getVariable( 'layout', 1 ),
    'type'      => 'image_select',
    'customizer'=> array(),
    'options'   => array( 
      0         => REDUX_URL . 'assets/img/1c.png',
      1         => REDUX_URL . 'assets/img/2cr.png',
      2         => REDUX_URL . 'assets/img/2cl.png',
      3         => REDUX_URL . 'assets/img/3cl.png',
      4         => REDUX_URL . 'assets/img/3cr.png',
      5         => REDUX_URL . 'assets/img/3cm.png',
    )
  );

  $fields[] = array( 
    'title'     => __( 'Primary Sidebar Width', 'shoestrap' ),
    'desc'      => __( 'Select the width of the Primary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the primary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'shoestrap' ),
    'id'        => 'layout_primary_width',
    'type'      => 'button_set',
    'options'   => array(
      '1' => '1 Column',
      '2' => '2 Columns',
      '3' => '3 Columns',
      '4' => '4 Columns',
      '5' => '5 Columns'
    ),
    'default' => '4'
  );

  $fields[] = array( 
    'title'     => __( 'Secondary Sidebar Width', 'shoestrap' ),
    'desc'      => __( 'Select the width of the Secondary Sidebar. Please note that the values represent grid columns. The total width of the page is 12 columns, so selecting 4 here will make the secondary sidebar to have a width of 1/3 ( 4/12 ) of the total page width.', 'shoestrap' ),
    'id'        => 'layout_secondary_width',
    'type'      => 'button_set',
    'options'   => array(
      '1' => '1 Column',
      '2' => '2 Columns',
      '3' => '3 Columns',
      '4' => '4 Columns',
      '5' => '5 Columns'
    ),
    'default' => '3'
  );

  $fields[] = array( 
    'title'     => __( 'Show sidebars on the frontpage', 'shoestrap' ),
    'desc'      => __( 'OFF by default. If you want to display the sidebars in your frontpage, turn this ON.', 'shoestrap' ),
    'id'        => 'layout_sidebar_on_front',
    'customizer'=> array(),
    'default'   => 0,
    'type'      => 'switch'
  );

  $fields[] = array( 
    'title'     => __( 'Margin from top ( Works only in \'Boxed\' mode )', 'shoestrap' ),
    'desc'      => __( 'This will add a margin above the navbar. Useful if you\'ve enabled the \'Boxed\' mode above. Default: 0px', 'shoestrap' ),
    'id'        => 'navbar_margin_top',
    'fold'      => 'navbar_boxed',
    'default'   => 0,
    'min'       => 0,
    'step'      => 1,
    'max'       => 120,
    'compiler'  => true,
    'type'      => 'slider'
  );

  $fields[] = array( 
    'title'     => __( 'Widgets mode', 'shoestrap' ),
    'desc'      => __( 'How do you want your widgets to be displayed?', 'shoestrap' ),
    'id'        => 'widgets_mode',
    'default'   => 1,
    'fold'      => 'advanced_toggle',
    'off'       => __( 'Panel', 'shoestrap' ),
    'on'        => __( 'Well', 'shoestrap' ),
    'type'      => 'switch',
    'customizer'=> array(),
  );

  $fields[] = array( 
    'title'     => __( 'Show Breadcrumbs', 'shoestrap' ),
    'desc'      => __( 'Display Breadcrumbs. Default: OFF.', 'shoestrap' ),
    'id'        => 'breadcrumbs',
    'default'   => 0,
    'type'      => 'switch',
    'customizer'=> array(),
  );

  $fields[] = array( 
    'title'     => __( 'Body Top Margin', 'shoestrap' ),
    'desc'      => __( 'Select the top margin of body element in pixels. Default: 0px.', 'shoestrap' ),
    'id'        => 'body_margin_top',
    'default'   => 0,
    'min'       => 0,
    'max'       => 200,
    'type'      => 'slider',
    'fold'      => 'advanced_toggle'
  );

  $fields[] = array( 
    'title'     => __( 'Body Bottom Margin', 'shoestrap' ),
    'desc'      => __( 'Select the bottom margin of body element in pixels. Default: 0px.', 'shoestrap' ),
    'id'        => 'body_margin_bottom',
    'default'   => 0,
    'min'       => 0,
    'max'       => 200,
    'type'      => 'slider',
    'fold'      => 'advanced_toggle'
  );

  $fields[] = array( 
    'title'     => __( 'Custom Grid', 'shoestrap' ),
    'desc'      => '<strong>' . __( 'CAUTION:', 'shoestrap' ) . '</strong> ' . __( 'Only use this if you know what you are doing, as changing these values might break the way your site looks on some devices. The default settings should be fine for the vast majority of sites.', 'shoestrap' ),
    'id'        => 'custom_grid',
    'default'   => 0,
    'type'      => 'switch',
    'fold'      => 'advanced_toggle'
  );

  $fields[] = array( 
    'title'     => __( 'Small Screen / Tablet view', 'shoestrap' ),
    'desc'      => __( 'The width of Tablet screens. Default: 768px', 'shoestrap' ),
    'id'        => 'screen_tablet',
    'fold'      => 'custom_grid',
    'default'   => 768,
    'min'       => 620,
    'step'      => 2,
    'max'       => 2100,
    'advanced'  => true,
    'compiler'  => true,
    'type'      => 'slider'
  );

  $fields[] = array( 
    'title'     => __( 'Desktop Container Width', 'shoestrap' ),
    'desc'      => __( 'The width of normal screens. Default: 992px', 'shoestrap' ),
    'id'        => 'screen_desktop',
    'fold'      => 'custom_grid',
    'default'   => 992,
    'min'       => 620,
    'step'      => 2,
    'max'       => 2100,
    'advanced'  => true,
    'compiler'  => true,
    'type'      => 'slider',

  );

  $fields[] = array( 
    'title'     => __( 'Large Desktop Container Width', 'shoestrap' ),
    'desc'      => __( 'The width of Large Desktop screens. Default: 1200px', 'shoestrap' ),
    'id'        => 'screen_large_desktop',
    'fold'      => 'custom_grid',
    'default'   => 1200,
    'min'       => 620,
    'step'      => 2,
    'max'       => 2100,
    'advanced'  => true,
    'compiler'  => true,
    'type'      => 'slider'
  );

  $fields[] = array( 
    'title'     => __( 'Columns Gutter', 'shoestrap' ),
    'desc'      => __( 'The space between the columns in your grid. Default: 30px', 'shoestrap' ),
    'id'        => 'layout_gutter',
    'fold'      => 'custom_grid',
    'default'   => 30,
    'min'       => 0,
    'step'      => 2,
    'max'       => 100,
    'advanced'  => true,
    'compiler'  => true,
    'type'      => 'slider',
  );

  $section['fields'] = $fields;

  do_action( 'shoestrap_module_layout_options_modifier' );
  
  $sections[] = $section;
  return $sections;

}
endif;
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_layout_options', 55 ); 

include_once( dirname( __FILE__ ).'/functions.layout.php' );