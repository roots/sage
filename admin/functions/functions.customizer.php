<?php
add_action( 'customize_controls_init', 'smof_customize_init' );
add_action( 'customize_preview_init', 'smof_preview_init' );
add_action( 'customize_register', 'smof_customize_register' );
/*
// Generates the CSS from less on save IF a less file has been changed
function shoestrapCSSRegen() {
    $nonce = $_POST['nonce'];

	header("Content-type: application/json");
    // check to see if the submitted nonce matches
    if ( ! wp_verify_nonce( $nonce, 'wp_ajax_shoestrap_makecss' ) ) {
        echo json_encode(
            array('response' => 'failed', 'message' => 'Verify nonce failed!')
        );
        exit;
    }
    // permission allow to Super Admin and Admin only
    if ( current_user_can( 'activate_plugins' ) ) {
        shoestrap_makecss();
        echo json_encode(
            array('response' => 'success', 'message' => 'CSS Regenerated')
        );
    } else {
        echo json_encode(
            array('response' => 'failed', 'message' => 'Insufficient permissions!')
        );
    }
    exit;
}
// Ajax call, remove when customize_save_after is in place!
add_action('wp_ajax_shoestrapCSSRegen', 'shoestrapCSSRegen');
*/

// Easiest way to do saving.
add_action('customize_save', 'shoestrap_preSave');
add_action('customize_save_after', 'shoestrap_generateCSS');

// Store the old SMOF values
function shoestrap_preSave() {
  set_theme_mod('shoestrap_customizer_preSave', get_theme_mods());
}

// Compare less values to see if we need to rebuild the CSS
function shoestrap_generateCSS() {
  global $smof_details;
  $old = get_theme_mod('shoestrap_customizer_preSave');
  remove_theme_mod('shoestrap_customizer_preSave'); // Cleanup
  $new = get_theme_mods();
  foreach ($smof_details as $key=>$option) {
    if ($option['less'] == true) {
      if ($old[$option['id']] != $new[$option['id']]) {
        shoestrap_makecss();
        break;
      }
    }
  }
}

$smof_details = array();

function smof_customize_init( $wp_customize ) {
	// Get Javascript
	of_load_only();
	// Have to change the javascript for the customizer
	wp_dequeue_script('smof', ADMIN_DIR .'assets/js/smof.js');
	wp_enqueue_style('wp-pointer');
    wp_enqueue_script('wp-pointer');
    // Remove when code is in place!
	wp_enqueue_script('smofcustomizerjs', ADMIN_DIR .'assets/js/customizer.js');
	/*
	wp_enqueue_script('smof-regenCSS', ADMIN_DIR .'assets/js/smof-regenCSS.js', array( 'jquery' ));
	wp_localize_script( 'smof-regenCSS', 'regenCSSAjax', array(
		'adminUrl'		=> 	admin_url(),
		'nonce'		=> 	js_escape( wp_create_nonce( 'wp_ajax_shoestrap_makecss' ) )
	));
	*/
	// Get styles
	of_style_only();
	wp_enqueue_style('smofcustomizer', ADMIN_DIR .'assets/css/customizer.css');
}



function smof_preview_init( $wp_customize ) {
	global $smof_data, $smof_details;
	wp_dequeue_style('shoestrap_css');
	wp_deregister_style('shoestrap_css');

	//print '<style type="text/less">'.shoestrap_complete_less().'</style>';
	file_put_contents(str_replace(".css", ".less", shoestrap_css()), shoestrap_complete_less(true));
	print '<link rel="stylesheet/less" type="text/less" href="'.str_replace(".css", ".less", shoestrap_css('url')).'">';

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
</script>';
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


function smof_customize_register($wp_customize) {
	// Classes for all the custom controls
	include_once('functions.customcontrols.php');
	// Make the variables global
	global $smof_data, $of_options, $smof_details;
	$section = array();
	$section_set = true;
	$order = array(
		'heading' => -100,
		'option'  => -100,
		);

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
			//echo $option['id']."-";
			//$customSetting['transport'] = 'postMessage';
		}
		if ($section_set == false && is_array($section)) {
			if (!isset($section['priority'])) {
				$section['priority'] = $order['heading'];
			}
			$wp_customize->add_section($section['id'], array(
				'title' 		=> $section['name'],
				'priority'		=> $section['priority'],
				'description' 	=> $section['desc']
			) );
			$section_set = true;
		}
		if ($option['type'] != 'heading') {
			if (!isset($option['priority'])) {
				$option['priority'] = $order['option'];
			}
		}
		switch( $option['type'] ) {
			case 'heading':
				// We don't want to put up the section unless it's used by something visible in the customizer
				$section = $option;
				$section['id'] = strtolower(str_replace(" ", "", $option['name']));
				$section_set = false;
				$order = array(
					'option'  => -100,
				);
				$order['heading']++;
				break;
			case 'text':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Text_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'select':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Select_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'textarea':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Textarea_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'radio':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Radio_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'checkbox':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Checkbox_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'multicheck':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Multicheck_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'upload':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Upload_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'media':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Media_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'color':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Color_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'typography':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Typography_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'border':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Border_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'images':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Images_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'info':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Info_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'image':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Image_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'slider':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Slider_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'sorter':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Sorter_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'titles':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Titles_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'select_google_font':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_SelectGoogleFont_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'sliderui':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Sliderui_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			case 'switch':
				$wp_customize->add_setting( $option['id'], $customSetting);
				$wp_customize->add_control( new Customize_SMOF_Switch_Control( $wp_customize, $option['id'], array(
					'label'   => $option['name'],
					'section' => $section['id'],
					'settings'=> $option['id'],
					'priority'=> $option['priority']
				) ) );
				break;
			default:

				break;
		}
		if ($option['type'] != 'heading') {
			$order['option']++;
		}
	}
}
