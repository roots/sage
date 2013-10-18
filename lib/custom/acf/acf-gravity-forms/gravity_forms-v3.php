<?php

class acf_field_gravity_forms extends acf_Field
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
  	$this->name = 'gravity_forms_field';
		$this->title = 'Gravity Forms';
		$this->defaults = array(
			'multiple' => '0',
			'allow_null' => '0'
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
		<label><?php _e("Allow Null?",'acf'); ?></label>
	</td>
	<td>
		<?php
		$this->parent->create_field(array(
			'type'  =>  'radio',
			'name'  =>  'fields['.$key.'][allow_null]',
			'value' =>  $field['allow_null'],
			'choices' =>  array(
				1 =>  __("Yes",'acf'),
				0 =>  __("No",'acf'),
			),
			'layout'  =>  'horizontal',
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Select multiple values?",'acf'); ?></label>
	</td>
	<td>
		<?php
		$this->parent->create_field(array(
			'type'  =>  'radio',
			'name'  =>  'fields['.$key.'][multiple]',
			'value' =>  $field['multiple'],
			'choices' =>  array(
				1 =>  __("Yes",'acf'),
				0 =>  __("No",'acf'),
			),
			'layout'  =>  'horizontal',
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
		// vars
		$field = array_merge($this->defaults, $field);
		$choices = array();
	    $forms = RGFormsModel::get_forms(1);


	    if($forms)
	    {
	    	foreach( $forms as $form )
	    	{
		    	$choices[ $form->id ] = ucfirst($form->title);
	    	}
	    }


		// override field settings and render
		$field['choices'] = $choices;
		$field['type'] = 'select';

		$this->parent->create_field($field);
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
	    if( !$value )
	    {
	    	return false;
	    }


	    if( $value == 'null' )
	    {
	    	return false;
	    }


	    // load form data
	    if( is_array($value) )
	    {
	    	foreach( $value as $k => $v )
	    	{
	        	$form = RGFormsModel::get_form($v);
	        	$value[ $k ] = $form;
	        }
	    }
	    else
	    {
	    	$value = RGFormsModel::get_form($value);
	    }


	    // return value
	    return $value;

	}

}

?>