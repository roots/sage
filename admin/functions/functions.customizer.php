<?php
add_action( 'customize_controls_init', 'smof_customize_init' );
add_action( 'customize_register', 'smof_customize_register' );

$smof_details = array();

function smof_customize_init() {
	// Get Javascript
	of_load_only();
	// Have to change the javascript for the customizer
	wp_dequeue_script('smof', ADMIN_DIR .'assets/js/smof.js', array( 'jquery' ));
	wp_enqueue_style('wp-pointer');
    wp_enqueue_script('wp-pointer');
	wp_enqueue_script('smofcustomizerjs', ADMIN_DIR .'assets/js/customizer.js');
  	//wp_register_script('smofcustomizerjs', ADMIN_DIR . 'assets/js/customizer.js', true, null, true);
  	//wp_enqueue_script('smofcustomizerjs');

	// Get styles
	of_style_only();
	wp_enqueue_style('admin-style', ADMIN_DIR . 'assets/css/admin-style.css');
	wp_enqueue_style('color-picker', ADMIN_DIR . 'assets/css/colorpicker.css');
	wp_enqueue_style('jquery-ui-custom-admin', ADMIN_DIR .'assets/css/jquery-ui-custom.css');
	wp_enqueue_style('smofcustomizer', ADMIN_DIR .'assets/css/customizer.css');




}



function smof_customize_register($wp_customize) {
	// Classes for all the custom controls
	include_once('functions.customcontrols.php');
	// Make the variables global
	global $smof_data, $of_options, $smof_details;
	$section = array();
	$section_set = true;

	foreach($of_options as $option) {
		$smof_details[$option['id']] = $option;

		// Skip it if we're not to display in the customizer
		if (isset($option['customizer']) && $option['type'] != "heading") {
			// TESTING, REMOVE COMMENT BEFORE RELEASE!
			//continue;
		}
		$customSetting = array(
				'type' 			=> 'theme_mod',
				'capabilities' 	=> 'manage_theme_options',
				'default'		=>	$option['std']
			);
		if ($section_set == false && is_array($section)) {
			$wp_customize->add_section($section['id'], array(
				'title' 		=> $section['name'],
				'priority'		=> $section['priority'],
				'description' 	=> $section['desc']
			) );
			$section_set = true;
		}
		switch( $option['type'] ) {
			case 'heading':
				// We don't want to put up the section unless it's used by something visible in the customizer
				$section = $option;
				$section['id'] = strtolower(str_replace(" ", "", $option['name']));
				$section_set = false;
				break;
			case 'text':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Text_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'select':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Select_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'textarea':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Textarea_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'radio':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Radio_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'checkbox':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Checkbox_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'multicheck':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Multicheck_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'upload':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Upload_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'media':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Media_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'color':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Color_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'typography':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Typography_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'border':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Border_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'images':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Images_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'info':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Info_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'image':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Image_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'slider':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Slider_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'sorter':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Sorter_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'titles':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Titles_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'select_google_font':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_SelectGoogleFont_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'sliderui':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Sliderui_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			case 'switch':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Switch_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
				) ) );
				break;
			default:

				break;
		}
	}
}
