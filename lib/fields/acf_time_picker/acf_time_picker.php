<?php
/*
 *	Advanced Custom Fields - Date and Time Picker field
 *	Copyright (c) 2012 Per Soderlind - http://soderlind.no
 *
 *	Documentation: 
 *
 *  This is an add-on for the Advanced Custom Fields WordPress plugin that allows you to add a Time Picker field type.
 *
 *  Version: 1.2.0
 *
 *  Changlog:
 *     - 1.2.0 Updated jquery-ui-timepicker-addon.js to the latest version (1.0.0) and added localization.
 *     - 1.1.1 Fixed a small bug
 *     - 1.1  Change name to Date and Time Picker to reflect the new option to select between Date and Time picker or Time picker only.
 *     - 1.0: Initial version  
 *
 *  Latest version: http://soderlind.no/download/acf_time_picker.zip
 *
 *
 *  Installation:
 *     - Extract http://soderlind.no/download/acf_time_picker.zip in your theme folder (WordPress child theme is supported). 
 *       After you have extracted the file, you should have the following subdirectories in your theme folder:
 *   
 *       acf_time_picker      
 *             |____ css
 *             | |____ images
 *             |____ js
 *
 *
 *     - Add the following to your themes functions.php (functions.php in a WordPress child theme is supported):
 *       <?php
 *       if(function_exists('register_field')) {
 *	         register_field('acf_time_picker', dirname(__File__) . '/acf_time_picker/acf_time_picker.php');
 *       }
 *       ?>
 *
 *  Credit: 
 *     Advanced Custom Fields - Time Picker field uses the jQuery timepicker addon (http://trentrichardson.com/examples/timepicker/)
 *     By: Trent Richardson [http://trentrichardson.com]
 *     Version 0.9.9
 *     Last Modified: 02/05/2012
 * 
 *     Copyright 2012 Trent Richardson
 *     Dual licensed under the MIT and GPL licenses.
 *     http://trentrichardson.com/Impromptu/GPL-LICENSE.txt
 *     http://trentrichardson.com/Impromptu/MIT-LICENSE.txt
 */
 
 
class acf_time_picker extends acf_Field
{

	var $localizationDomain = 'acf_time_picker';

	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- This function is called when the field class is initalized on each page.
	*	- Here you can add filters / actions and setup any other functionality for your field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
		// do not delete!
    	parent::__construct($parent);

		$locale = get_locale();	
		load_textdomain($this->localizationDomain, sprintf("/%s/lang/%s-%s.mo",dirname( plugin_basename( __FILE__ ) ),$this->localizationDomain, $locale));
   	
    	// set name / title
    	$this->name = 'time_picker'; // variable name (no spaces / special characters / etc)
		$this->title = __("Date and Time Picker",$this->localizationDomain); // field label (Displayed in edit screens)
   	}

	
	/*--------------------------------------------------------------------------------------
	*
	* Builds the field options
	* 
	* @see acf_Field::create_options()
	* @param string $key
	* @param array $field
	*
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		$field['timepicker_show_date_format'] = isset($field['timepicker_show_date_format']) ? $field['timepicker_show_date_format'] : 'false';
		$field['timepicker_date_format'] = isset($field['timepicker_date_format']) ? $field['timepicker_date_format'] : 'mm/dd/yy';
		$field['timepicker_time_format'] = isset($field['timepicker_time_format']) ? $field['timepicker_time_format'] : 'hh:mm';
		$field['timepicker_show_week_number'] = isset($field['timepicker_show_week_number']) ? $field['timepicker_show_week_number'] : 'false';
		
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_choice">
			<td class="label">
				<label for=""><?php _e("Date and Time Picker?",$this->localizationDomain); ?></label>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][timepicker_show_date_format]',
					'value'	=>	$field['timepicker_show_date_format'],
					'layout' => 'horizontal', 
					'choices' => array(
						'true' => __('Date and Time Picker',$this->localizationDomain), 
						'false' => __('Time Picker',$this->localizationDomain)
					)
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_dateformat">
			<td class="label">
				<label><?php _e("Date format",$this->localizationDomain); ?></label>
				<p class="description"><?php _e("eg. mm/dd/yy. read more about",$this->localizationDomain); ?> <a href="http://docs.jquery.com/UI/Datepicker/formatDate">formatDate</a></p>
			</td>
			<td>
				<?php
					$this->parent->create_field(array(
							'type' => 'text',
							'name' => 'fields[' . $key . '][timepicker_date_format]',
							'value' => $field['timepicker_date_format']
					));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_timeformat">
			<td class="label">
				<label><?php _e("Time Format", $this->localizationDomain);?></label>
				<p class="description"><a href="javascript:" id="timepicker_help_select">See Documentation</a></p>
			</td>
			<td>
				<?php
					$this->parent->create_field(array(
							'type' => 'text',
							'name' => 'fields[' . $key . '][timepicker_time_format]',
							'value' => $field['timepicker_time_format']
					));
				?>
				<div id="timepicker_help_text" style="display:none;">
					<p>Time format (default is "hh:mm")</p>
					<table>
					<tr><td>h</td><td>Hour with no leading 0</td></tr>
					<tr><td>hh</td><td>Hour with leading 0</td></tr>
					<tr><td>m</td><td> Minute with no leading 0</td></tr>
					<tr><td>mm</td><td>Minute with leading 0</td></tr>
					<tr><td>s</td><td>Second with no leading 0</td></tr>
					<tr><td>ss</td><td>Second with leading 0</td></tr>
					<tr><td>l</td><td>Milliseconds always with leading 0</td></tr>
					<tr><td>t</td><td>a or p for AM/PM</td></tr>
					<tr><td>T</td><td>A or P for AM/PM</td></tr>
					<tr><td>tt</td><td>am or pm for AM/PM</td></tr>
					<tr><td>TT</td><td>AM or PM for AM/PM</td></tr>
					</table>
					<p>tip, for duration, don't use AM/PM</p>
				</div>
				<script type="text/javascript">
					jQuery(function() {
						jQuery("body").on("click", "#timepicker_help_select", function(event){
    						event.preventDefault();
        					jQuery('#timepicker_help_text').show(); 
							return false;
						});						
					});
				</script>
		   </td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_week_number">
			<td class="label">
				<label for=""><?php _e("Display Week Number?",$this->localizationDomain); ?></label>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'radio',
					'name'	=>	'fields['.$key.'][timepicker_show_week_number]',
					'value'	=>	$field['timepicker_show_week_number'],
					'layout' => 'horizontal', 
					'choices' => array(
						'true' => __('Yes',$this->localizationDomain), 
						'false' => __('No',$this->localizationDomain)
					)
				));
				?>
			</td>
		</tr>
		<?php		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	pre_save_field
	*	- this function is called when saving your acf object. Here you can manipulate the
	*	field object and it's options before it gets saved to the database.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function pre_save_field($field)
	{
		// do stuff with field (mostly format options data)
		
		return parent::pre_save_field($field);
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	* Creates the time picker field for inside post metaboxes
	* 
	* @see acf_Field::create_field()
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		$field['value'] = isset($field['value']) ? $field['value'] : '';
        $title = (isset($field['label'])) ? (empty($field['label']) ? '' : $field['label']) : __('Choose Time',$this->localizationDomain);
        $time_format = (isset($field['timepicker_time_format'])) ? (empty($field['timepicker_time_format']) ? 'hh:mm' : $field['timepicker_time_format']) : 'hh:mm'; 

        if ($field['timepicker_show_date_format'] != 'true') {
	        echo '<input type="text" name="' . $field['name'] . '" class="time_picker" value="' . $field['value'] . '" data-time_format="' . $time_format . '"  title="' . $title . '" />';
        } else {
        	$date_format = (isset($field['timepicker_date_format'])) ? (empty($field['timepicker_date_format']) ? 'mm/dd/yy' : $field['timepicker_date_format']) : 'mm/dd/yy';
	        $show_week_number = (isset($field['timepicker_show_week_number'])) ? (empty($field['timepicker_show_week_number']) ? 'false' : $field['timepicker_show_week_number']) : 'false';
	        echo '<input type="text" name="' . $field['name'] . '" class="time_picker" value="' . $field['value'] . '" data-date_format="' . $date_format . '" data-time_format="' . $time_format . '" data-show_week_number="' . $show_week_number . '"  title="' . $title . '" />';    
        }
 	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	* admin_print_scripts / admin_print_styles
	* These functions are called in the admin_print_scripts / admin_print_styles where 
	* your field is created. Use this function to register css and javascript to assist 
	* your create_field() function.
	*
	* @see acf_Field::admin_print_scripts()
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_print_scripts()
	{
	
		global $wp_locale;
	
		wp_enqueue_script('jquery-ui-timepicker', get_stylesheet_directory_uri() . '/acf_time_picker/js/jquery-ui-timepicker-addon.js',array(
			'jquery-ui-datepicker',
			'jquery-ui-slider'
		),'1.0.0', true ); 	
		wp_enqueue_script('timepicker', get_stylesheet_directory_uri() . '/acf_time_picker/js/timepicker.js',array(
			'jquery-ui-timepicker'
		),'1.2.0',true);
		
		//localize our js, from http://www.renegadetechconsulting.com/tutorials/jquery-datepicker-and-wordpress-i18n (google cache: http://webcache.googleusercontent.com/search?q=cache:LG5-wdUYzZUJ:www.renegadetechconsulting.com/tutorials/jquery-datepicker-and-wordpress-i18n&hl=en&prmd=imvns&strip=1)
		$timepickerArgs = array(
			'closeText'				=> __('Done',$this->localizationDomain),
			'currentText'       	=> __('Today',$this->localizationDomain),
			'prevText'				=> __('Prev',$this->localizationDomain),
			'nextText'				=> __('Next',$this->localizationDomain),
			'monthNames'        	=> $this->strip_array_indices( $wp_locale->month ),
			'monthNamesShort'   	=> $this->strip_array_indices( $wp_locale->month_abbrev ),
			'monthStatus'       	=> __( 'Show a different month', $this->localizationDomain ),
			'showMonthAfterYear' 	=> false,
			'dayNames'				=> $this->strip_array_indices( $wp_locale->weekday ),
			'dayNamesShort'     	=> $this->strip_array_indices( $wp_locale->weekday_abbrev ),
			'dayNamesMin'       	=> $this->strip_array_indices( $wp_locale->weekday_initial ),
			'showWeek'				=> false,
			'weekHeader'			=> __('Wk',$this->localizationDomain),
			'firstDay'				=> get_option( 'start_of_week' ),
			'isRTL'					=> $wp_locale->is_rtl(),
			'timeText'   			=> __("Time",$this->localizationDomain),
			'hourText'   			=> __("Hour",$this->localizationDomain),
			'minuteText' 			=> __("Minute",$this->localizationDomain),
			'secondText' 			=> __("Second",$this->localizationDomain),
			'millisecText' 			=> __("Millisecond",$this->localizationDomain),
			'timezoneText' 			=> __("Time Zone",$this->localizationDomain),
			'locale'      			=> ( '' == get_locale() ) ? 'en' : strtolower( substr(get_locale(), 0, 2) ) // only ISO 639-1  (code from class-wp-editor.php)
		);

		// Pass the array to the enqueued JS
		wp_localize_script( 'timepicker', 'timepicker_objectL10n', $timepickerArgs );
		 	
		
	}
	
	function strip_array_indices( $ArrayToStrip ) {
		foreach( $ArrayToStrip as $objArrayItem) {
			$NewArray[] =  $objArrayItem;
		}
	
		return( $NewArray );
	}
	
	
	function admin_print_styles()
	{
		wp_enqueue_style('jquery-style', get_stylesheet_directory_uri() . '/acf_time_picker/css/jquery-ui.css'); 
		wp_enqueue_style('timepicker',  get_stylesheet_directory_uri() . '/acf_time_picker/css/jquery-ui-timepicker-addon.css',array(
			'jquery-style'
		),'1.0.0');
	}

	
	/*--------------------------------------------------------------------------------------
	*
	*	update_value
	*	- this function is called when saving a post object that your field is assigned to.
	*	the function will pass through the 3 parameters for you to use.
	*
	*	@params
	*	- $post_id (int) - usefull if you need to save extra data or manipulate the current
	*	post object
	*	- $field (array) - usefull if you need to manipulate the $value based on a field option
	*	- $value (mixed) - the new value of your field.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function update_value($post_id, $field, $value)
	{
		// do stuff with value

		parent::update_value($post_id, $field, $value);
	}
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*	- called from the edit page to get the value of your field. This function is useful
	*	if your field needs to collect extra data for your create_field() function.
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		// get value
		$value = parent::get_value($post_id, $field);
		
		// format value
		
		// return value
		return $value;		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*	- called from your template file when using the API functions (get_field, etc). 
	*	This function is useful if your field needs to format the returned value
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		// get value
		$value = $this->get_value($post_id, $field);
		
		// format value
		
		// return value
		return $value;

	}
	
}

?>