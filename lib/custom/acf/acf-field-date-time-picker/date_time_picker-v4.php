<?php

class acf_field_date_time_picker extends acf_field
{
	// vars
	var $settings   // will hold info such as dir / path
		, $defaults // will hold default field options
		, $domain   // holds the language domain
		, $lang;

	/*
	*  __construct
	*
	*  Set name / label needed for actions / filters
	*
	*  @since	3.6
	*  @date	23/01/13
	*/

	function __construct()
	{
		// vars
		$this->name = 'date_time_picker';
		$this->label = __('Date and Time Picker');
		$this->category = __("jQuery", $this->domain); // Basic, Content, Choice, etc
		$this->domain = 'acf-date_time_picker';
		$this->defaults = array(
			 'label'             => __( 'Choose Time', $this->domain )
			, 'time_format'       => 'h:mm tt'
			, 'show_date'         => 'true'
			, 'date_format'       => 'm/d/y'
			, 'show_week_number'  => 'false'
			, 'picker'            => 'slider'
			, 'save_as_timestamp' => 'true'
			, 'get_as_timestamp'  => 'false'
		);



		// do not delete!
    	parent::__construct();


    	// settings
		$this->settings = array(
			'path'      => apply_filters('acf/helpers/get_path', __FILE__)
			, 'dir'     => apply_filters('acf/helpers/get_dir', __FILE__)
			, 'version' => '2.0.9'
		);

	}


	/*
	*  create_options()
	*
	*  Create extra options for your field. This is rendered when editing a field.
	*  The value of $field['name'] can be used (like bellow) to save extra data to the $field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field	- an array holding all the field's data
	*/

	function create_options( $field )
	{
		$field = array_merge($this->defaults, $field);
		$key = $field['name'];
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_choice">
			<td class="label">
				<label for=""><?php _e( "Date and Time Picker?", $this->domain ); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
						'type'      => 'radio'
						, 'name'    => 'fields['.$key.'][show_date]'
						, 'value'   => $field['show_date']
						, 'layout'  => 'horizontal'
						, 'choices' => array(
								'true'    => __( 'Date and Time Picker', $this->domain )
								, 'false' => __( 'Time Picker', $this->domain )
						)
					) );
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_dateformat">
			<td class="label">
				<label><?php _e( "Date Format", $this->domain ); ?></label>
				<p class="description"><?php printf(__("eg. mm/dd/yy. read more about <a href=\"%s\" target=\"_blank\">formatting  date</a>", $this->domain ),"http://docs.jquery.com/UI/Datepicker/formatDate");?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
						'type'    => 'text'
						, 'name'  => 'fields[' . $key . '][date_format]'
						, 'value' => $field['date_format']
					) );
				/*
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][date_format]',
					'value'	=>	$field['date_format'],
					'choices' => array(
						  'm/d/y'    => 'm/d/y (5/27/13)'
						, 'mm/dd/yy' => 'mm/dd/yy (05/27/2013)'
						, 'yy/mm/dd' => 'yy/mm/dd (2013/05/27)'
						, 'yy-mm-dd' => 'yy-mm-dd (2013-05-27)'
						, 'dd.mm.yy' => 'dd.mm.yy (27.05.2013)'
						, 'dd-mm-yy' => 'dd-mm-yy (27-05-2013)'
						, 'yy-M-dd'  => 'yy-M-dd (2013-May-27)'
					)
				));
				*/
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_timeformat">
			<td class="label">
				<label><?php _e( "Time Format", $this->domain );?></label>
				<p class="description"><?php printf(__("eg. hh:mm. read more about <a href=\"%s\" target=\"_blank\">formatting  time</a>", $this->domain ),"http://trentrichardson.com/examples/timepicker/#tp-formatting");?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
						'type'    => 'text'
						, 'name'  => 'fields[' . $key . '][time_format]'
						, 'value' => $field['time_format']
					) );
				/*
				do_action('acf/create_field', array(
					'type'	=>	'select',
					'name'	=>	'fields['.$key.'][time_format]',
					'value'	=>	$field['time_format'],
					'choices' => array(
						'h:mm tt' => 'h:mm tt (9:59 am)'
						, 'hh:mm tt' => 'hh:mm tt (09:59 am)'
						, 'H:mm' => 'H:mm (9:59)'
						, 'HH:mm' => 'HH:mm (09:59)'
					)
				));
				*/
				?>
		   </td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_week_number">
			<td class="label">
				<label for=""><?php _e( "Display Week Number?", $this->domain ); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
						'type'      => 'radio'
						, 'name'    => 'fields['.$key.'][show_week_number]'
						, 'value'   => $field['show_week_number']
						, 'layout'  => 'horizontal'
						, 'choices' => array(
								'true'    => __( 'Yes', $this->domain )
								, 'false' => __( 'No', $this->domain )
						)
					) );
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_week_number">
			<td class="label">
				<label for=""><?php _e( "Time Picker style?", $this->domain ); ?></label>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
						'type'      => 'radio'
						, 'name'    => 'fields['.$key.'][picker]'
						, 'value'   => $field['picker']
						, 'layout'  => 'horizontal'
						, 'choices' => array(
								'slider'   => __( 'Slider', $this->domain )
								, 'select' => __( 'Dropdown', $this->domain )
						)
					) );
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_week_number">
			<td class="label">
				<label for=""><?php _e( "Save as timestamp?", $this->domain ); ?></label>
				<p class="description"><?php printf( __( "Most users should leave this untouched, only set it to \"No\" if you need a date and time format not supported by <a href=\"%s\" target=\"_blank\">strtotime</a>", $this->domain ), "http://php.net/manual/en/function.strtotime.php" );?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
						'type'      => 'radio'
						, 'name'    => 'fields['.$key.'][save_as_timestamp]'
						, 'value'   => $field['save_as_timestamp']
						, 'layout'  => 'horizontal'
						, 'choices' => array(
								'true'    => __( 'Yes', $this->domain )
								, 'false' => __( 'No', $this->domain )
						)
					) );
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_week_number">
			<td class="label">
				<label for=""><?php _e( "Get field as timestamp?", $this->domain ); ?></label>
				<p class="description"><?php printf( __( "Most users should leave this untouched, only set it to \"Yes\" if you need get the  date and time field as a timestamp using  <a href=\"%s\" target=\"_blank\">the_field()</a> or <a href=\"%s\" target=\"_blank\">get_field()</a> ", $this->domain ), "http://www.advancedcustomfields.com/resources/functions/the_field/", "http://www.advancedcustomfields.com/resources/functions/get_field/" );?></p>
			</td>
			<td>
				<?php
				do_action('acf/create_field', array(
						'type'      => 'radio'
						, 'name'    => 'fields['.$key.'][get_as_timestamp]'
						, 'value'   => $field['get_as_timestamp']
						, 'layout'  => 'horizontal'
						, 'choices' => array(
								'true'    => __( 'Yes', $this->domain )
								, 'false' => __( 'No', $this->domain )
						)
					) );
				?>
			</td>
		</tr>
		<?php
	}



	/*
	*  create_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field - an array holding all the field's data
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function create_field( $field ) {

		if ( $field['show_date'] !== 'true' ) {
			echo '<input type="text" value="' . $field['value'] . '" name="' . $field['name'] . '" class="ps_timepicker" value="" data-picker="' . $field['picker'] . '" data-time_format="' . $field['time_format'] . '"  title="' . $field['label'] . '" />';
		} else {
			echo '<input type="text" value="' . $field['value'] . '" name="' . $field['name'] . '" class="ps_timepicker" value="" data-picker="' . $field['picker'] . '" data-date_format="' . $field['date_format'] . '" data-time_format="' . $field['time_format'] . '" data-show_week_number="' . $field['show_week_number'] . '"  title="' . $field['label'] . '" />';
		}
	}

	//function load_field_defaults( $field ) { return $field; }

	function format_value($value, $post_id, $field)
	{
		$field = array_merge($this->defaults, $field);
		if ($value != '' && $field['save_as_timestamp'] == 'true' && $this->isValidTimeStamp($value)) {
			if ( $field['show_date'] == 'true') {
				 $value = date_i18n(sprintf("%s %s",$this->js_to_php_dateformat($field['date_format']),$this->js_to_php_timeformat($field['time_format'])), $value);
			} else {
				 $value = date_i18n(sprintf("%s",$this->js_to_php_timeformat($field['time_format'])), $value);
			}
		}
		return $value;
	}

	function format_value_for_api($value, $post_id, $field)
	{
		$field = array_merge($this->defaults, $field);
		if ($value != '' && $field['save_as_timestamp'] == 'true' && $field['get_as_timestamp'] != 'true' && $this->isValidTimeStamp($value)) {
			if ( $field['show_date'] == 'true') {
				 $value = date_i18n(sprintf("%s %s",$this->js_to_php_dateformat($field['date_format']),$this->js_to_php_timeformat($field['time_format'])), $value);
			} else {
				 $value = date_i18n(sprintf("%s",$this->js_to_php_timeformat($field['time_format'])), $value);
			}
		}
		return $value;
	}


	function js_to_php_dateformat($date_format) {
	    $chars = array(
	        // Day
	        'dd' => 'd', 'd' => 'j', 'DD' => 'l','D' => 'D', 'o' => 'z',
	        // Month
	        'mm' => 'm', 'm' => 'n', 'MM' => 'F', 'M' => 'M',
	        // Year
	        'yy' => 'Y', 'y' => 'y',
	    );

	    return strtr((string)$date_format, $chars);
	}


    function js_to_php_timeformat($time_format) {

	    $chars = array(
		    //hour
		    'HH' => 'H', 'H'  => 'G', 'hh' => 'h' , 'h'  => 'g',
		    //minute
		    'mm' => 'i', 'm'  => 'i',
		    //second
		    'ss' => 's', 's' => 's',
		    //am/pm
		    'TT' => 'A', 'T' => 'A', 'tt' => 'a', 't' => 'a'
	    );

	    return strtr((string)$time_format, $chars);
	}


	function isValidTimeStamp($timestamp) { //from http://stackoverflow.com/a/2524761/1434155
	    return ((string) (int) $timestamp === $timestamp)
	        && ($timestamp <= PHP_INT_MAX)
	        && ($timestamp >= ~PHP_INT_MAX);
	}
	/*
	*  update_value()
	*
	*  This filter is appied to the $value before it is updated in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value - the value which will be saved in the database
	*  @param	$post_id - the $post_id of which the value will be saved
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$value - the modified value
	*/

	function update_value( $value, $post_id, $field ) {
		$field = array_merge($this->defaults, $field);
		if ($value != '' && $field['save_as_timestamp'] == 'true') {
			$value = strtotime( $value );
		}
		return $value;
	}



	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add css + javascript to assist your create_field() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function input_admin_enqueue_scripts() {

		global $wp_locale;

		$has_locale = false;
		$js_locale = $this->get_js_locale(get_locale());

		wp_enqueue_script( 'jquery-ui-timepicker', $this->settings['dir'] . 'js/jquery-ui-timepicker-addon.js', array(
				'acf-datepicker',
				'jquery-ui-slider'
		), $this->settings['version'], true );

		if ( file_exists(  $this->settings['path'] . '/js/localization/jquery-ui-timepicker-' . $js_locale . '.js' ) ) {
			wp_enqueue_script( 'timepicker-localization', $this->settings['dir'] . 'js/localization/jquery-ui-timepicker-' . $js_locale . '.js', array(
				'jquery-ui-timepicker'
			), $this->settings['version'], true );
			wp_enqueue_script( 'timepicker', $this->settings['dir'] . 'js/timepicker.js', array(
				'timepicker-localization'
			), $this->settings['version'], true );
			$has_locale = true;
		} else {
			wp_enqueue_script( 'timepicker', $this->settings['dir'] . 'js/timepicker.js', array(
				'jquery-ui-timepicker'
			), $this->settings['version'], true );
		}

		if ( ! $has_locale && $js_locale != 'en' ) {
			$timepicker_locale = array(
				'closeText'      => __( 'Done', $this->domain )
				, 'currentText'  => __( 'Today', $this->domain )
				, 'prevText'     => __( 'Prev', $this->domain )
				, 'nextText'     => __( 'Next', $this->domain )
				, 'monthStatus'  => __( 'Show a different month', $this->domain )
				, 'weekHeader'   => __( 'Wk', $this->domain )
				, 'timeText'     => __( "Time", $this->domain )
				, 'hourText'     => __( "Hour", $this->domain )
				, 'minuteText'   => __( "Minute", $this->domain )
				, 'secondText'   => __( "Second", $this->domain )
				, 'millisecText' => __( "Millisecond", $this->domain )
				, 'timezoneText' => __( "Time Zone", $this->domain )
				, 'isRTL'        => $wp_locale->is_rtl()
			);
		}
		$timepicker_wp_locale = array(
			'monthNames'           => $this->strip_array_indices( $wp_locale->month )
			, 'monthNamesShort'    => $this->strip_array_indices( $wp_locale->month_abbrev )
			, 'dayNames'           => $this->strip_array_indices( $wp_locale->weekday )
			, 'dayNamesShort'      => $this->strip_array_indices( $wp_locale->weekday_abbrev )
			, 'dayNamesMin'        => $this->strip_array_indices( $wp_locale->weekday_initial )
			, 'showMonthAfterYear' => false
			, 'showWeek'           => false
			, 'firstDay'           => get_option( 'start_of_week' )
		);

		$l10n = ( isset( $timepicker_locale ) ) ? array_merge( $timepicker_wp_locale, $timepicker_locale ) : $timepicker_wp_locale;
		wp_localize_script( 'timepicker', 'timepicker_objectL10n', $l10n );

		wp_enqueue_style('jquery-style', $this->settings['dir'] . 'css/jquery-ui.css',array(
			'acf-datepicker'
		),$this->settings['version']);
		wp_enqueue_style('timepicker',  $this->settings['dir'] . 'css/jquery-ui-timepicker-addon.css',array(
			'jquery-style'
		),$this->settings['version']);
	}

	/**
	 * helper function, see: http://www.renegadetechconsulting.com/tutorials/jquery-datepicker-and-wordpress-i18n
	 * @param  array $ArrayToStrip
	 * @return array
	 */
	function strip_array_indices( $ArrayToStrip ) {
		foreach ( $ArrayToStrip as $objArrayItem ) {
			$NewArray[] =  $objArrayItem;
		}

		return $NewArray;
	}

	function get_js_locale($locale) {
		$dir_path = $this->settings['path'] . 'js/localization/';
		$exclude_list = array(".", "..");
		$languages = $this->ps_preg_filter("/jquery-ui-timepicker-(.*?)\.js/","$1",array_diff(scandir($dir_path), $exclude_list));

		$locale = strtolower(str_replace("_", "-", $locale));

		if (false !== strpos($locale,'-')) {
			$l = explode("-",$locale);
			$pattern = array('/' .  $locale . '/','/' . $l[0] . '/', '/' . $l[1]  . '/');
		} else {
			$pattern = array('/' . $locale . '/');
		}
		$res = $this->ps_preg_filter($pattern,"$0",$languages,-1,$count);

		return ($count) ? implode("", $res) : 'en';
	}


	function ps_preg_filter ($pattern, $replace, $subject,$limit = -1, &$count = 0) {
		if (function_exists('preg_filter'))
			return preg_filter($pattern, $replace, $subject,$limit,$count);
		else
			return  array_diff(preg_replace($pattern, $replace, $subject,$limit,$count), $subject);
	}


}


// create field
new acf_field_date_time_picker();

?>