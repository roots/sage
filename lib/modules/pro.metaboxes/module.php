<?php

if ( !function_exists( 'shoestrap_pro_addMetaboxes' ) ) :

  function shoestrap_pro_addMetaboxes($ReduxFramework) {
  
    if ( !class_exists( 'Redux_Metaboxes' ) ) {
      require( dirname( __FILE__ ) . '/class.redux_metaboxes.php' );  
    }
    
    $boxSections[] = array(
      'title' => __('Home Settings2', 'redux-framework-demo'),
      'header' => __('Welcome to the Simple Options Framework Demo', 'redux-framework-demo'),
      'desc' => __('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'redux-framework-demo'),
      'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
      'fields' => array(
        array(
          'title'     => __( 'Layout', 'shoestrap' ),
          'desc'      => __( 'Select main content and sidebar arrangement. Choose between 1, 2 or 3 column layout.', 'shoestrap' ),
          'id'        => 'layout',
          'default'   => 1,
          'type'      => 'image_select',
          'customizer'=> array(),
          'options'   => array( 
            0         => ReduxFramework::$_url . '/assets/img/1c.png',
            1         => ReduxFramework::$_url . '/assets/img/2cr.png',
            2         => ReduxFramework::$_url . '/assets/img/2cl.png',
            3         => ReduxFramework::$_url . '/assets/img/3cl.png',
            4         => ReduxFramework::$_url . '/assets/img/3cr.png',
            5         => ReduxFramework::$_url . '/assets/img/3cm.png',
          )
        )
      )
    );
    $boxes[] = array(
      'id' => 'shoestrap-layout',
      //'title' => __('Cool Options', 'redux-sidebars'),
      'post_types' => array('page', 'post'),
      'position' => 'normal', // normal, advanced, side
      'priority' => 'high', // high, core, default, low
      'sections' => $boxSections
    );

    $metaboxes = new Redux_Metaboxes($ReduxFramework, $boxes);

  }
  add_action( 'redux/extensions/'.REDUX_OPT_NAME, 'shoestrap_pro_addMetaboxes'); 

//shoestrap_pro_addMetaboxes($shoestrap_ReduxFramework);

endif;

