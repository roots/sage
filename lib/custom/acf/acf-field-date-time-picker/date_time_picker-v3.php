<?php

class acf_field_date_time_picker extends acf_Field {

	// vars
	var $settings // will hold info such as dir / path
		, $defaults // will hold default field options
		, $domain; // holds the language domain


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

	function __construct( $parent ) {
		// do not delete!
		parent::__construct( $parent );

		// set name / title
		$this->name = 'date_time_picker';
		$this->title = __( 'Date and Time Picker' );
		$this->domain = 'acf-date_time_picker';
		$this->defaults = array(
			, 'label'              => __( 'Choose Time', $this->domain )
			, 'time_format'        => 'hh:mm'
			, 'show_date'          => 'true'
			, 'date_format'        => 'yy-mm-dd'
			, 'show_week_number'   => 'false'
			, 'picker'             => 'slider'
			, 'save_as_timestamp'  => 'true'
			, 'get_as_timestamp'   => 'false'
		);

		$this->settings = array(
			'path'      => $this->helpers_get_path( __FILE__ )
			, 'dir'     => $this->helpers_get_dir( __FILE__ )
			, 'version' => '2.0.9'
		);
	}


   	/*
    *  helpers_get_path
    *
    *  @description: calculates the path (works for plugin / theme folders)
    *  @since: 3.6
    *  @created: 30/01/13
    */

    function helpers_get_path( $file ) {
        return trailingslashit(dirname($file));
    }



    /*
    *  helpers_get_dir
    *
    *  @description: calculates the directory (works for plugin / theme folders)
    *  @since: 3.6
    *  @created: 30/01/13
    */

    function helpers_get_dir( $file ) {
        $dir = trailingslashit(dirname($file));
        $count = 0;


        // sanitize for Win32 installs
        $dir = str_replace('\\' ,'/', $dir);


        // if file is in plugins folder
        $wp_plugin_dir = str_replace('\\' ,'/', WP_PLUGIN_DIR);
        $dir = str_replace($wp_plugin_dir, WP_PLUGIN_URL, $dir, $count);


        if( $count < 1 )
        {
	        // if file is in wp-content folder
	        $wp_content_dir = str_replace('\\' ,'/', WP_CONTENT_DIR);
	        $dir = str_replace($wp_content_dir, WP_CONTENT_URL, $dir, $count);
        }


        if( $count < 1 )
        {
	        // if file is in ??? folder
	        $wp_dir = str_replace('\\' ,'/', ABSPATH);
	        $dir = str_replace($wp_dir, site_url('/'), $dir);
        }


        return $dir;
    }



	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*	- this function is called from core/field_meta_box.php to create extra options
	*	for your field
	*
	*	@params
	*	- $key (int) - the $_POST obejct key required to save the options to the field
	*	- $field (array) - the field object
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	*
	*-------------------------------------------------------------------------------------*/

	function create_options( $key, $field ) {


		$field = array_merge( $this->defaults, $field );
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_choice">
			<td class="label">
				<label for=""><?php _e( "Date and Time Picker?", $this->domain ); ?></label>
			</td>
			<td>
			<?php
			$this->parent->create_field( array(
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
				<p class="description"><?php _e( "eg. mm/dd/yy. read more about", $this->domain ); ?> <a href="http://docs.jquery.com/UI/Datepicker/formatDate">formatDate</a></p>
			</td>
			<td>
			<?php
			$this->parent->create_field( array(
				'type'    => 'text'
				, 'name'  => 'fields[' . $key . '][date_format]'
				, 'value' => $field['date_format']
			) );
			?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_timeformat">
			<td class="label">
				<label><?php _e( "Time Format", $this->domain );?></label>
				<p class="description"><?php printf( __( "eg. hh:mm. read more about <a href=\"%s\" target=\"_blank\">formatting  time</a>", $this->domain ), "http://trentrichardson.com/examples/timepicker/#tp-formatting" );?></p>
			</td>
			<td>
			<?php
			$this->parent->create_field( array(
				'type'    => 'text'
				, 'name'  => 'fields[' . $key . '][time_format]'
				, 'value' => $field['time_format']
			) );
			?>
		   </td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?> timepicker_week_number">
			<td class="label">
				<label for=""><?php _e( "Display Week Number?", $this->domain ); ?></label>
			</td>
			<td>
			<?php
			$this->parent->create_field( array(
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
				<label for=""><?php _e( "Time Picker Style?", $this->domain ); ?></label>
			</td>
			<td>
			<?php
			$this->parent->create_field( array(
				'type'       => 'radio'
				, 'name'     => 'fields['.$key.'][picker]'
				, 'value'    => $field['picker']
				, 'layout'   => 'horizontal'
				, 'choices'  => array(
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
			$this->parent->create_field( array(
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
				<label for=""><?php _e( "Get field as a timestamp?", $this->domain ); ?></label>
				<p class="description"><?php printf( __( "Most users should leave this untouched, only set it to \"Yes\" if you need get the  date and time field as a timestamp using  <a href=\"%s\" target=\"_blank\">the_field()</a> or <a href=\"%s\" target=\"_blank\">get_field()</a> ", $this->domain ), "http://www.advancedcustomfields.com/resources/functions/the_field/", "http://www.advancedcustomfields.com/resources/functions/get_field/" );?></p>
			</td>
			<td>
				<?php
				$this->parent->create_field( array(
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


	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- this function is called on edit screens to produce the html for this field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	*
	*-------------------------------------------------------------------------------------*/

	function create_field( $field ) {
		$field = array_merge( $this->defaults, $field );

		if ( $field['show_date'] != 'true' ) {
			echo '<input type="text" value="' . $field['value'] . '" name="' . $field['name'] . '" class="ps_timepicker" value="" data-picker="' . $field['picker'] . '" data-time_format="' . $field['time_format'] . '"  title="' . $field['label'] . '" />';
		} else {
			echo '<input type="text" value="' . $field['value'] . '" name="' . $field['name'] . '" class="ps_timepicker" value="" data-picker="' . $field['picker'] . '" data-date_format="' . $field['date_format'] . '" data-time_format="' . $field['time_format'] . '" data-show_week_number="' . $field['show_week_number'] . '"  title="' . $field['label'] . '" />';
		}
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

	function update_value($post_id, $field, $value) {
		$field = array_merge($this->defaults, $field);
		if ($value != '' && $field['save_as_timestamp'] == 'true') {
			if ( $field['show_date'] == 'true') {
				 $date = DateTime::createFromFormat(sprintf("%s %s",$this->js_to_php_dateformat($field['date_format']),$this->js_to_php_timeformat($field['time_format'])), $value);
			} else {
				 $date = DateTime::createFromFormat(sprintf("%s",$this->js_to_php_timeformat($field['time_format'])), $value);
			}
			$value =  $date->getTimestamp();
		}

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

	function get_value($post_id, $field){
		$field = array_merge($this->defaults, $field);
		$value = parent::get_value($post_id, $field);

		if ($value != '' && $field['save_as_timestamp'] == 'true' && $this->isValidTimeStamp($value)) {
			if ( $field['show_date'] == 'true') {
				 $value = date(sprintf("%s %s",$this->js_to_php_dateformat($field['date_format']),$this->js_to_php_timeformat($field['time_format'])), $value);
			} else {
				 $value = date(sprintf("%s",$this->js_to_php_timeformat($field['time_format'])), $value);
			}
		}

		return $value;
	}

	function get_value_for_api($post_id, $field){
		$field = array_merge($this->defaults, $field);
		$value = parent::get_value($post_id, $field);

		if ($value != '' && $field['save_as_timestamp'] == 'true' && $field['get_as_timestamp'] != 'true' && $this->isValidTimeStamp($value)) {
			if ( $field['show_date'] == 'true') {
				 $value = date(sprintf("%s %s",$this->js_to_php_dateformat($field['date_format']),$this->js_to_php_timeformat($field['time_format'])), $value);
			} else {
				 $value = date(sprintf("%s",$this->js_to_php_timeformat($field['time_format'])), $value);
			}
		}

		return $value;
	}

	function js_to_php_dateformat($date_format) {
	    $chars = array(
	        // Day
	        'dd' => 'd', 'd' => 'j', 'DD' => 'l', 'D' => 'D', 'o' => 'z',
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

	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*	- this function is called in the admin_print_scripts / admin_print_styles where
	*	your field is created. Use this function to register css and javascript to assist
	*	your create_field() function.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	*
	*-------------------------------------------------------------------------------------*/

	function admin_print_scripts() {
		global $wp_locale;

		$has_locale = false;
		$js_locale = $this->get_js_locale(get_locale());
		wp_enqueue_script( 'jquery-ui-timepicker', $this->settings['dir'] . 'js/jquery-ui-timepicker-addon.js', array(
				'acf-datepicker',
				'jquery-ui-slider'
		), $this->settings['version'], true );

		if ( file_exists( dirname( __FILE__ ) . '/js/localization/jquery-ui-timepicker-' . $js_locale . '.js' ) ) {
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


	function admin_print_styles() {
		wp_enqueue_style( 'jquery-style', $this->settings['dir'] . 'css/jquery-ui.css' );
		wp_enqueue_style( 'timepicker',  $this->settings['dir'] . 'css/jquery-ui-timepicker-addon.css', array(
				'jquery-style'
			), $this->settings['version'] );
	}
}
