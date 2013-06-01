<?php
$smof_details = array();

// Enable less compiling on save
function shoestrap_of_save_options_after($data) {
	global $smof_details, $smof_data;
	$lessChanged = false;

	if (is_array($data['data'])) {
		foreach ( $smof_details as $key=>$option ) {
		  if ( $option['less'] == true ) {
		    if ( $smof_data[$key] != $data['data'][$key] ) {
		      $lessChanged = true;
		    }
		  }
		}		
	} else if (($data['data'] != $smof_data[$data['key']]) && $smof_data[$data['key']]['less'] == true) {
		$lessChanged = true;
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
			if ($option['id'] != "")
				$smof_details[$option['id']] = $option;
		}
	}
}
add_action('optionsframework_admin_init_before', 'shoepress_optionsframework_admin_init_before');