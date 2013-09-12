<?php

/*
 * The jumbotron core options for the Shoestrap theme
 */
if ( !function_exists( 'shoestrap_module_jumbotron_options' ) ) {
  function shoestrap_module_jumbotron_options($sections) {

    //Background Patterns Reader
    $bg_pattern_images_path = get_template_directory() . '/assets/img/patterns';
    $bg_pattern_images_url  = get_template_directory_uri() . '/assets/img/patterns/';
    $bg_pattern_images      = array();

    if ( is_dir( $bg_pattern_images_path ) ) {
      if ( $bg_pattern_images_dir = opendir( $bg_pattern_images_path ) ) {
        while ( ( $bg_pattern_images_file = readdir( $bg_pattern_images_dir ) ) !== false ) {
          if( stristr( $bg_pattern_images_file, '.png' ) !== false || stristr( $bg_pattern_images_file, '.jpg' ) !== false)
            $bg_pattern_images[] = $bg_pattern_images_url . $bg_pattern_images_file;
        }
      }
    }

		// Branding Options
    $section = array(
    		'title' => __('Jumbotron', 'shoestrap'),
    		'icon' => 'elusive icon-bullhorn icon-large'
    	);

    $url = admin_url( 'widgets.php' );
    $fields[] = array(
      'title'     => '',
      'subtitle'  => '',
      'id'        => 'help8',
      'default'       => '<h3 style=\'margin: 0 0 10px;\'>Jumbotron</h3>
                      <p>A \'Jumbotron\', also known as \'Hero\' area,
                      is an area in your site where you can display in a prominent position things that matter to you.
                      This can be a slideshow, some text or whatever else you wish.
                      This area is implemented as a widget area, so in order for something to be displayed
                      you will have to add a widget from <a href=\'$url\'>here</a>.</p>',
      'icon'      => true,
      'type'      => 'info'
    );

    $fields[] = array(
      'title'     => __('Jumbotron Background Color', 'shoestrap'),
      'subtitle'  => __('Select the background color for your Jumbotron area. Please note that this area will only be visible if you assign a widget to the \'Jumbotron\' Widget Area. Default: #EEEEEE.', 'shoestrap'),
      'id'        => 'jumbotron_bg',
      'default'       => '#EEEEEE',
      'compiler'      => true,
      'customizer'=> array(),
      'type'      => 'color'
    );


    $fields[] = array(
      'title'     => __('Background position', 'shoestrap'),
      'subtitle'  => __('Changes how the background image or pattern is displayed from scroll to fixed position. Default: Fixed.', 'shoestrap'),
      'id'        => 'jumbotron_background_fixed_toggle',
      'default'       => 1,
      'on'        => __('Fixed', 'shoestrap'),
      'off'       => __('Scroll', 'shoestrap'),
      'type'      => 'switch'
    );

    $fields[] = array(
      'title'     => __('Use a Background Image', 'shoestrap'),
      'subtitle'  => __('Enable this option to upload a custom background image for your site. This will override any patterns you may have selected. Default: OFF.', 'shoestrap'),
      'id'        => 'jumbotron_background_image_toggle',
      'default'       => 0,
      'type'      => 'switch'
    );

    $fields[] = array(
      'title'     => __('Upload a Custom Background Image', 'shoestrap'),
      'subtitle'  => __('Upload a Custom Background image using the media uploader, or define the URL directly.', 'shoestrap'),
      'id'        => 'jumbotron_background_image',
      'fold'      => 'jumbotron_background_image_toggle',
      'default'       => '',
      'type'      => 'media',
      'customizer'=> array(),
    );

    $fields[] = array(
      'title'     => __('Background Image Positioning', 'shoestrap'),
      'subtitle'  => __('Allows the user to modify how the background displays. By default it is full width and stretched to fill the page. Default: Full Width.', 'shoestrap'),
      'id'        => 'jumbotron_background_image_position_toggle',
      'default'       => 0,
      'fold'      => 'jumbotron_background_image_toggle',
      'on'        => __('Custom', 'shoestrap'),
      'off'       => __('Full Width', 'shoestrap'),
      'type'      => 'switch'
    );

    $fields[] = array(
      'title'     => __('Background Repeat', 'shoestrap'),
      'subtitle'  => __('Select how (or if) the selected background should be tiled. Default: Tile', 'shoestrap'),
      'id'        => 'jumbotron_background_repeat',
      'fold'      => 'jumbotron_background_image_position_toggle',
      'default'       => 'repeat',
      'type'      => 'select',
      'options'   => array(
        'no-repeat'  => __( 'No Repeat', 'shoestrap' ),
        'repeat'     => __( 'Tile', 'shoestrap' ),
        'repeat-x'   => __( 'Tile Horizontally', 'shoestrap' ),
        'repeat-y'   => __( 'Tile Vertically', 'shoestrap' ),
      ),
    );

    $fields[] = array(
      'title'     => __('Background Alignment', 'shoestrap'),
      'subtitle'  => __('Select how the selected background should be horizontally aligned. Default: Left', 'shoestrap'),
      'id'        => 'jumbotron_background_position_x',
      'fold'      => 'jumbotron_background_image_position_toggle',
      'default'       => 'repeat',
      'type'      => 'select',
      'options'   => array(
        'left'    => __( 'Left', 'shoestrap' ),
        'right'   => __( 'Right', 'shoestrap' ),
        'center'  => __( 'Center', 'shoestrap' ),
      ),
    );

    $fields[] = array(
      'title'     => __('Use a Background Pattern', 'shoestrap'),
      'subtitle'  => __('Select one of the already existing Background Patterns. Default: OFF.', 'shoestrap'),
      'id'        => 'jumbotron_background_pattern_toggle',
      'default'       => 0,
      'type'      => 'switch'
    );

    $fields[] = array(
      'title'     => __('Choose a Background Pattern', 'shoestrap'),
      'subtitle'  => __('Select a background pattern.', 'shoestrap'),
      'id'        => 'jumbotron_background_pattern',
      'fold'      => 'jumbotron_background_pattern_toggle',
      'default'       => '',
      'tiles'			=> true,
      'type'      => 'image_select',
      'options'   => $bg_pattern_images,
    );

    $fields[] = array(
      'title'     => __('Display Jumbotron only on the Frontpage.', 'shoestrap'),
      'subtitle'  => __('When Turned OFF, the Jumbotron area is displayed in all your pages. If you wish to completely disable the Jumbotron, then please remove the widgets assigned to its area and it will no longer be displayed. Default: ON', 'shoestrap'),
      'id'        => 'jumbotron_visibility',
      'customizer'=> array(),
      'default'       => 1,
      'type'      => 'switch'
    );

    $fields[] = array(
      'title'     => __('Full-Width', 'shoestrap'),
      'subtitle'  => __('When Turned ON, the Jumbotron is no longer restricted by the width of your page, taking over the full width of your screen. This option is useful when you have assigned a slider widget on the Jumbotron area and you want its width to be the maximum width of the screen. Default: OFF.', 'shoestrap'),
      'id'        => 'jumbotron_nocontainer',
      'customizer'=> array(),
      'default'       => 1,
      'type'      => 'switch'
    );

    $fields[] = array(
      'title'     => __('Use fittext script for the title.', 'shoestrap'),
      'subtitle'  => __('Use the fittext script to enlarge or scale-down the font-size of the widget title to fit the Jumbotron area. Default: OFF', 'shoestrap'),
      'id'        => 'jumbotron_title_fit',
      'customizer'=> array(),
      'default'       => 0,
      'type'      => 'switch',
      'fold'      => 'advanced_toggle'
    );

    $fields[] = array(
      'title'     => __('Center-align the content.', 'shoestrap'),
      'subtitle'  => __('Turn this on to center-align the contents of the Jumbotron area. Default: OFF', 'shoestrap'),
      'id'        => 'jumbotron_center',
      'customizer'=> array(),
      'default'       => 0,
      'type'      => 'switch',
      'fold'      => 'advanced_toggle'
    );

    $fields[] = array(
      'title'     => __('Jumbotron Font', 'shoestrap'),
      'subtitle'  => __('The font used in jumbotron.', 'shoestrap'),
      'id'        => 'font_jumbotron',
      'less'      => true,
      'default'       => array(
        'family'    => 'Arial, Helvetica, sans-serif',
        'size'    => '16px',
        'color'   => '#333333',
        'google'  => 'false',
        'color'   => '#333333',
      ),
      'preview'   => array(
        'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
        'size'    => '30px' //this is the text size from preview box
      ),
      'type'      => 'typography',
      'fold'      => 'advanced_toggle'
    );

    $fields[] = array(
      'title'     => __('Jumbotron Header Overrides', 'shoestrap'),
      'subtitle'  => __('By enabling this you can specify custom values for each <h*> tag. Default: Off', 'shoestrap'),
      'id'        => 'font_jumbotron_heading_custom',
      'default'       => 0,
      'compiler'      => true,
      'type'      => 'switch',
      'customizer'=> array(),
      'fold'      => 'advanced_toggle'
    );

    $fields[] = array(
      'title'     => __('Jumbotron Headers Font', 'shoestrap'),
      'subtitle'  => __('The main font for your site.', 'shoestrap'),
      'id'        => 'font_jumbotron_headers',
      'less'      => true,
      'default'       => array(
        'family'    => 'Arial, Helvetica, sans-serif',
        'color'   => '#333333',
        'google'  => 'false'
      ),
      'preview'   => array(
        'text'    => __( 'This is my preview text!', 'shoestrap' ), //this is the text from preview box
        'size'    => '30px' //this is the text size from preview box
      ),
      'type'      => 'typography',
      'fold'      => 'font_jumbotron_heading_custom',
    );

    $fields[] = array(
      'title'     => 'Jumbotron Bottom Border',
      'subtitle'  => __('Select the border options for your Jumbotron', 'shoestrap'),
      'id'        => 'jumbotron_border_bottom',
      'type'      => 'border',
      'default'       => array(
        'size'   => '0',
        'style'   => 'solid',
        'color'   => '#428bca',
      ),
      'fold'      => 'advanced_toggle'
    );

    $section['fields'] = $fields;

    do_action( 'shoestrap_module_jumbotron_options_modifier' );
    
    $sections[] = $section;
    return $sections;

  }
}
add_filter( 'redux-sections-'.REDUX_OPT_NAME, 'shoestrap_module_jumbotron_options', 70 ); 

include_once( dirname(__FILE__).'/functions.jumbotron.php' );