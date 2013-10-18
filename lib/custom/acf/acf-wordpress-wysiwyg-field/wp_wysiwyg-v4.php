<?php

class acf_field_wp_wysiwyg extends acf_field
{
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
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
		$this->name = 'wp_wysiwyg';
		$this->label = __('WP WYSIWYG');
		$this->category = __("Content",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			// add default here to merge into your field. 
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			'media_buttons' => 1,
			'teeny' => 0,
			'dfw' => 1,
			'default_value' => '',
		);
		
		
		// do not delete!
    	parent::__construct();
    	
    	
    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.0'
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
		// defaults?
		$field = array_merge($this->defaults, $field);
		
		// key is needed in the field names to correctly save the data
		$key = $field['name'];
		
		
		// Create Field Options HTML
		
?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Default Value",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'textarea',
			'name'	=>	'fields['.$key.'][default_value]',
			'value'	=>	$field['default_value'],
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Teeny Mode",'acf'); ?></label>
		<p><?php _e("Whether to output the minimal editor configuration used in PressThis",'acf'); ?></p>
	</td>
	<td>
		<?php
		
		do_action('acf/create_field', array(
			'type'	=>	'radio',
			'name'	=>	'fields['.$key.'][teeny]',
			'value'	=>	$field['teeny'],
			'layout'	=>	'horizontal',
			'choices' => array(
				1	=>	__("Yes",'acf'),
				0	=>	__("No",'acf'),
			)
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Show Media Upload Buttons?",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'radio',
			'name'	=>	'fields['.$key.'][media_buttons]',
			'value'	=>	$field['media_buttons'],
			'layout'	=>	'horizontal',
			'choices' => array(
				1	=>	__("Yes",'acf'),
				0	=>	__("No",'acf'),
			)
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Distraction Free Writing",'acf'); ?></label>
		<p><?php _e("Whether to replace the default fullscreen editor with DFW",'acf'); ?></p>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'radio',
			'name'	=>	'fields['.$key.'][dfw]',
			'value'	=>	$field['dfw'],
			'layout'	=>	'horizontal',
			'choices' => array(
				1	=>	__("Yes",'acf'),
				0	=>	__("No",'acf'),
			)
		));
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
	
	function create_field( $field )
	{
		// defaults?
		$field = array_merge($this->defaults, $field);
		
		$id = 'wysiwyg-' . $field['id'] . '-' . uniqid();
		
		$field['textarea_name'] = $field['name'];
		
		
		// create Field HTML
		wp_editor( $field['value'], $id, $field );
	}
	
	
	/*
	*  format_value_for_api()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed back to the api functions such as the_field
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value	- the value which was loaded from the database
	*  @param	$post_id - the $post_id from which the value was loaded
	*  @param	$field	- the field array holding all the field options
	*
	*  @return	$value	- the modified value
	*/
	
	function format_value_for_api( $value, $post_id, $field )
	{
		// wp_embed convert urls to videos
		if(	isset($GLOBALS['wp_embed']) )
		{
			$embed = $GLOBALS['wp_embed'];
            $value = $embed->run_shortcode( $value );
            $value = $embed->autoembed( $value );
		}
		
		
		// auto p
		$value = wpautop( $value );
		
		
		// run all normal shortcodes
		$value = do_shortcode( $value );
		
	
		return $value;
	}
	
	
}


// create field
new acf_field_wp_wysiwyg();

?>