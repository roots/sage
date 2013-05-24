<?php
/**
 * SMOF Admin
 *
 * @package     WordPress
 * @subpackage  SMOF
 * @since       1.4.0
 * @author      Syamil MJ
 */


/**
 * Head Hook
 *
 * @since 1.0.0
 */
function of_head() { do_action( 'of_head' ); }

/**
 * Add default options upon activation else DB does not exist
 *
 * @since 1.0.0
 */
function of_option_setup()
{
	global $of_options, $options_machine;
	$options_machine = new Options_Machine($of_options);

	if (!of_get_options())
	{
		of_save_options($options_machine->Defaults);
	}
}

/**
 * Change activation message
 *
 * @since 1.0.0
 */
function optionsframework_admin_message() {

	//Tweaked the message on theme activate
	?>
    <script type="text/javascript">
    jQuery(function(){

        var message = '<p>This theme comes with an <a href="<?php echo admin_url('admin.php?page=optionsframework'); ?>">options panel</a> to configure settings. This theme also supports widgets, please visit the <a href="<?php echo admin_url('widgets.php'); ?>">widgets settings page</a> to configure them.</p>';
    	jQuery('.themes-php #message2').html(message);

    });
    </script>
    <?php

}

/**
 * Get header classes
 *
 * @since 1.0.0
 */
function of_get_header_classes_array()
{
	global $of_options;

	foreach ($of_options as $value)
	{
		if ($value['type'] == 'heading')
			$hooks[] = str_replace(' ','',strtolower($value['name']));
	}

	return $hooks;
}

/**
 * Get options from the database and process them with the load filter hook.
 *
 * @author Jonah Dahlquist
 * @since 1.4.0
 * @return array
 */
function of_get_options() {
	$data = get_theme_mods();
	$data = apply_filters('of_options_after_load', $data);
	return $data;
}

/**
 * Save options to the database after processing them
 *
 * @param $data Options array to save
 * @author Jonah Dahlquist
 * @since 1.4.0
 * @uses update_option()
 * @return void
 */
function of_save_options($data) {
  	global $of_options;
  	// Make the $of_options into a proper array
  	$smof_details = array();
	foreach($of_options as $option) {
		$smof_details[$option['id']] = $option;
	}

	$data = apply_filters('of_options_before_save', $data);
    $old = get_theme_mods();
	foreach ($data as $k=>$v) {
		set_theme_mod( $k, $v );
	}

	// Find if we changed a less variable, and if so, recompile the CSS.
	foreach ($smof_details as $key=>$option) {
		if ($option['less'] == true) {
		  if ($old[$option['id']] != $data[$option['id']]) {
		    shoestrap_makecss();
		    break;
		  }
		}
	}
}


/**
 * For use in themes
 *
 * @since forever
 */

$data = of_get_options();
$smof_data = of_get_options();
