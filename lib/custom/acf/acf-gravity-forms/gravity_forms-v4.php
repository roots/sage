<?php

class acf_field_gravity_forms extends acf_field
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
		$this->name = 'gravity_forms_field';
		$this->label = __('Gravity Forms');
		$this->category = __("Relational",'acf'); // Basic, Content, Choice, etc
		$this->defaults = array(
			'multiple' => 0,
			'allow_null' => 0
		);

		// do not delete!
    parent::__construct();
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
		<label><?php _e("Allow Null?",'acf'); ?></label>
	</td>
	<td>
		<?php
		do_action('acf/create_field', array(
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
		do_action('acf/create_field', array(
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

		do_action('acf/create_field', $field);
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

// create field
new acf_field_gravity_forms();

?>