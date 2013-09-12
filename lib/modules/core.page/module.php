<?php

/*
 * The page core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_page_options' ) ) {
  function shoestrap_module_page_options($sections) {

    // Page Options
    $section = array(
  		'title' => __( 'Page', 'shoestrap' ),
  		'icon' => 'elusive icon-file icon-large',
  	);

    $fields[] = array(
      'title'     => __( 'Custom Page Layout', 'shoestrap' ),
      'subtitle'  => __( 'Set a default layout for your blob/post pages. Default: OFF.', 'shoestrap' ),
      'id'        => 'page_layout_toggle',
      'default'       => 0,
      'type'      => 'switch',
      'customizer'=> array(),
    );

    $fields[] = array(
      'title'     => __( 'Page Layout', 'shoestrap' ),
      'subtitle'  => __( 'Override your default stylings. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
      'id'        => 'page_layout',
      'default'       => shoestrap_getVariable( 'layout', 1 ),
      'type'      => 'image_select',
      'fold'      => 'page_layout_toggle',
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

    $section['fields'] = $fields;

    do_action( 'shoestrap_module_page_options_modifier' );
    
    $sections[] = $section;
    
    return $sections;

  }
}
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_page_options', 76 ); 

/*
Disabled by roots by default. No real need, but the code here anyways
function shoestrap_core_page_comments_toggle() {
  if (is_page() && shoestrap_getVariable('page_comments_toggle')) {
    add_filter('get_comments_number', '__return_false', 10, 3);
  }
}
add_action( 'init','shoestrap_core_page_comments_toggle', 76 );
*/
