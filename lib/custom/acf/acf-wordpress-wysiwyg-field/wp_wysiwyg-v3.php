<?php

class acf_field_wp_wysiwyg extends acf_Field
{
	
	// vars
	var $settings, // will hold info such as dir / path
		$defaults; // will hold default field options
		
		
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
    	
    	// set name / title
    	$this->name = 'wp_wysiwyg';
		$this->title = __('WP WYSIWYG');
		$this->defaults = array(
			// add default here to merge into your field. 
			// This makes life easy when creating the field options as you don't need to use any if( isset('') ) logic. eg:
			'media_buttons' => 1,
			'teeny' => 0,
			'dfw' => 1,
			'default_value' => '',
		);
		
		
		// settings
		// settings
		$this->settings = array(
			'path' => '',
			'dir' => '',
			'version' => '1.0.0'
		);
		
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
	
	function create_options($key, $field)
	{
		// defaults?
		$field = array_merge($this->defaults, $field);
		
		
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
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- this function is called on edit screens to produce the html for this field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		// defaults?
		$field = array_merge($this->defaults, $field);
		
		$id = 'wysiwyg-' . $field['id'] . '-' . uniqid();
		
		$field['textarea_name'] = $field['name'];
		
		
		// create Field HTML
		wp_editor( $field['value'], $id, $field );
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
		$value = parent::get_value($post_id, $field);
		
		// format value
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

?>