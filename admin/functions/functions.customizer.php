<?php
add_action( 'customize_controls_init', 'smof_customize_init' );
add_action( 'customize_preview_init', 'smof_preview_init' );
add_action( 'customize_register', 'smof_customize_register' );

$customizerView = true;

// Generates the CSS from less on save IF a less file has been changed
//if (get_theme_mod('less-dirty') == "true") {
  //add_action('customize_save', 'saveCSS', 100);
add_action('customize_save', 'regenCSS', 100);
function regenCSS( $wp_customize ) {
	checkCSSRegen();
	set_theme_mod('regen-css', time()+3);
}
function checkCSSRegen() {
	if (get_theme_mod('regen-css') != "" && get_theme_mod('regen-css') < time()) {
		shoestrap_makecss();
	  	remove_theme_mod('regen-css');
	}
}

$smof_details = array();




function smof_customize_init( $wp_customize ) {
	checkCSSRegen();
	// Get Javascript
	of_load_only();
	// Have to change the javascript for the customizer
	wp_dequeue_script('smof', ADMIN_DIR .'assets/js/smof.js');
	wp_enqueue_style('wp-pointer');
    wp_enqueue_script('wp-pointer');
	wp_enqueue_script('smofcustomizerjs', ADMIN_DIR .'assets/js/customizer.js');

	// Get styles
	of_style_only();
	wp_enqueue_style('smofcustomizer', ADMIN_DIR .'assets/css/customizer.css');
}



function smof_preview_init( $wp_customize ) {
	//echo "<pre>";
	//print_r(get_theme_mods());


	global $smof_data, $smof_details;
	wp_dequeue_style('shoestrap_css');
	wp_deregister_style('shoestrap_css');

	//print '<style type="text/less">'.shoestrap_complete_less().'</style>';
	print '<script type="text/javascript">
    less = {
        env: "development", // or "production"
        async: false,       // load imports async
        fileAsync: false,   // load imports async when in a page under
                            // a file protocol
        poll: 1000,         // when in watch mode, time in ms between polls
        functions: {},      // user functions, keyed by name
        dumpLineNumbers: "comments", // or "mediaQuery" or "all"
        relativeUrls: false,// whether to adjust urls to be relative
                            // if false, urls are already relative to the
                            // entry less file
        rootpath: "http://localhost/wordpress3/wp-content/themes/shoestrap/less/"// a path to add on to the start of every url
                            //resource
    };
</script><style type="text/less">'.shoestrap_complete_less_preview().'</style>';
	//print '<link rel="stylesheet/less" type="text/css" href="'.get_template_directory_uri() . '/assets/less/preview.less'.'">';
	wp_enqueue_script('less-js', ADMIN_DIR .'/assets/js/less-1.3.3.min.js');
	wp_enqueue_script('preview-js', ADMIN_DIR .'assets/js/preview.js');
	//$data['script'] = postMessageHandlersJS();
	wp_localize_script( 'preview-js', 'smofPost', array(
			'data'			=> $smof_data,
			'variables'		=> $smof_details
		)
	 );

}



function enqueue_less_styles($tag, $handle) {
    global $wp_styles;
    $match_pattern = '/\.less$/U';
    if ( preg_match( $match_pattern, $wp_styles->registered[$handle]->src ) ) {
        $handle = $wp_styles->registered[$handle]->handle;
        $media = $wp_styles->registered[$handle]->args;
        $href = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
        $rel = isset($wp_styles->registered[$handle]->extra['alt']) && $wp_styles->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
        $title = isset($wp_styles->registered[$handle]->extra['title']) ? "title='" . esc_attr( $wp_styles->registered[$handle]->extra['title'] ) . "'" : '';

        $tag = "<link rel='stylesheet/less' id='$handle' $title href='$href' type='text/less' media='$media' />";
    }
    return $tag;
}





function postMessageHandlersJS() {
	global $smof_data, $smof_details;
	$script = "";
	foreach ($smof_details as $option) {
		if ($option['less'] == true) {
			$script .="
	            wp.customize( option , function( value ) {
	                value.bind( function( to ) {
	                    console.log('Setting customize bind: '+option);
	                    var variable = '@'+option;
	//                    less.modifyVars({
	  //                      variable : '#5B83AD'
	    //                });
	                console.log(option);

	                });
	            });



			";



		}
	}
}


//do stuff here to find and replace the rel attribute
function less_loader($tag){
	echo "here!";
	return str_replace('rel="stylesheet"', 'rel="stylesheet/less"', $tag);
}



function smof_customize_register($wp_customize) {
	// Classes for all the custom controls
	include_once('functions.customcontrols.php');
	// Make the variables global
	global $smof_data, $of_options, $smof_details;
	$section = array();
	$section_set = true;
	//echo shoestrap_variables_less();

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
		if ($option['less'] == true) {
			//$customSetting['transport'] = 'postMessage';
		}
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
