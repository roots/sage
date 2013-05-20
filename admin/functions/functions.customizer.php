<?php
add_action( 'customize_controls_init', 'smof_customize_init' );
add_action( 'customize_register', 'smof_customize_register' );

$smof_details = array();

function smof_customize_init() {
	// Get Javascript
	of_load_only();
	// Won't work fully here.

	wp_dequeue_script('smof', ADMIN_DIR .'assets/js/smof.js', array( 'jquery' ));

	//wp_enqueue_script('smofcustomizerjs', ADMIN_DIR .'assets/js/customizer.js');
  	wp_register_script('smofcustomizerjs', ADMIN_DIR . 'assets/js/customizer.js', true, null, true);
  	wp_enqueue_script('smofcustomizerjs');


	// Get styles
	of_style_only();
	wp_enqueue_style('smofcustomizer', ADMIN_DIR .'assets/css/customizer.css');
	wp_enqueue_style('admin-style', ADMIN_DIR . 'assets/css/admin-style.css');
	wp_enqueue_style('color-picker', ADMIN_DIR . 'assets/css/colorpicker.css');
	wp_enqueue_style('jquery-ui-custom-admin', ADMIN_DIR .'assets/css/jquery-ui-custom.css');
}

function smof_customize_register($wp_customize) {
	// Definitions of the custom controls
	include_once('functions.customcontrols.php');
	// Make the variables global
	global $smof_data, $of_options, $smof_details;

	$section = "";
	$set_section = true;
	foreach($of_options as $option) {
		// Check if visible from the customizer

		//if ( !empty($option['customizer']) ) {
			// Check if visible from the customizer
			if ($option['type'] == "heading") {
				$section = $option;
				$section['id'] = strtolower(str_replace(" ", "", $option['name']));
				$set_section = true;
				// We've got the heading, move on!
				continue;
			}
			// Set the section if we have a variable that's using it!
			//if ($set_section) {
				$wp_customize->add_section($section['id'], array(
					'title' 		=> $section['name'],
					'priority'		=> $section['priority'],
					'description' 	=> $section['desc']
				) );
				$set_section = false;
			//}
			$smof_details[$option['id']] = $option;
			if( $option['type'] == 'text' || $option['type'] == 'textarea' ){

				$wp_customize->add_setting( $option['id'], array(
					'default'				=> $option['std'],
					'type'					=> 'theme_mod',
					'capabilities'	=> 'manage_theme_options'
				) );

				if ($option['type'] == 'text') {
					$wp_customize->add_control( new Customize_Text_Control( $wp_customize, $option['id'], array(
						'label'   => $option['name'],
						'section' => $section['id'],
						'settings' => $option['id'],
						'type'    => $option['type'],
					) ) );

				} else {
					$wp_customize->add_control( new Customize_Textarea_Control( $wp_customize, $option['id'], array(
						'label'   => $option['name'],
						'section' => $section['id'],
						'settings' => $option['id'],
						'desc' => $option['desc'],
						'priority' => $option['customizer']['priority']
					) ) );
				}


			}

			if( $option['type'] == 'color' ){

				$wp_customize->add_setting( $option['id'], array(
					'default'				=> $option['std'],
					'type'					=> 'theme_mod',
					'capabilities'	=> 'manage_theme_options'
				) );

				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );

			}

			if( $option['type'] == 'radio' || $option['type'] == 'select' ){

				$wp_customize->add_setting( $option['id'], array(
					'default'				=> $option['std'],
					'type'					=> 'theme_mod',
					'capabilities'	=> 'manage_theme_options'
				) );

				$wp_customize->add_control( $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'type'    => $option['type'],
					'choices' => $option['options'],
				) );
			}
			if( $option['type'] == 'checkbox' ){

				$wp_customize->add_setting( $option['id'], array(
					'default'				=> $option['std'],
					'type'					=> 'theme_mod',
					'capabilities'	=> 'manage_theme_options'
				) );

				$wp_customize->add_control( $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'type'    => $option['type'],
					'choices' => $option['options'],
				) );
			}

			if( $option['type'] == 'switch' ){

				$wp_customize->add_setting( $option['id'], array(
					'default'				=> $option['std'],
					'type'					=> 'theme_mod',
					'capabilities'	=> 'manage_theme_options'
				) );

				$wp_customize->add_control( new Customize_Switch_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );

			}
			if( $option['type'] == 'sliderui' ){

				$wp_customize->add_setting( $option['id'], array(
					'default'				=> $option['std'],
					'type'					=> 'theme_mod',
					'capabilities'	=> 'manage_theme_options'
				) );

				$wp_customize->add_control( new Customize_Slider_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );

			}
		//}
	}
}

