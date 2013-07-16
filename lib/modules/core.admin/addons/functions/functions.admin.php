<?php
$smof_details = array();

// Enable less compiling on save
function shoestrap_of_save_options_after($data) {
	global $smof_details, $smof_data;
	$lessChanged = false;
	if (is_array($data['data'])) {
		foreach ( $smof_details as $key=>$option ) {
		  if ( isset($option['less']) ) {
		    if ( isset($smof_data[$key]) && $smof_data[$key] != $data['data'][$key] ) {
		      $lessChanged = true;
		    }
		  }
		  if (isset($option['urlrewrite'])) {
		  	set_theme_mod( 'urlrewrite', 1 );
		  }
		}		
	} else if (($data['data'] != $smof_data[$data['key']])) {
		if ($smof_data[$data['key']]['less'] == true) {
			$lessChanged = true;	
		}
		if ($smof_data[$data['key']]['urlrewrite'] == true) {
			set_theme_mod( 'urlrewrite', 1 );
		}
	}

  if ( $lessChanged == true ){
    shoestrap_makecss();
  }
}
add_action('of_save_options_after', 'shoestrap_of_save_options_after');


// Make sure $smof_details is set for much easier comparison
function shoepress_optionsframework_admin_init_before($data) {
	global $smof_details;

	if (empty($data['smof_data']['smof_init']) || empty($smof_details)) {
		foreach ($data['of_options'] as $option) {
			if (isset($option['id']) && $option['id'] != "")
				$smof_details[$option['id']] = $option;
		}
	}
}
add_action('optionsframework_admin_init_before', 'shoepress_optionsframework_admin_init_before');


?>