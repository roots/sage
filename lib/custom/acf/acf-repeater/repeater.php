<?php

class acf_field_repeater extends acf_field
{

	var $settings;
	
	
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
		$this->name = 'repeater';
		$this->label = __("Repeater",'acf');
		$this->category = __("Layout",'acf');
		
		
		// do not delete!
    	parent::__construct();
    	

    	// settings
		$this->settings = array(
			'path' => apply_filters('acf/helpers/get_path', __FILE__),
			'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
			'version' => '1.0.1'
		);
		
		
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

	function input_admin_enqueue_scripts()
	{
		// register acf scripts
		wp_register_script( 'acf-input-repeater', $this->settings['dir'] . 'js/input.js', array('acf-input'), $this->settings['version'] );
		wp_register_style( 'acf-input-repeater', $this->settings['dir'] . 'css/input.css', array('acf-input'), $this->settings['version'] ); 
		
		
		// scripts
		wp_enqueue_script(array(
			'acf-input-repeater',	
		));

		// styles
		wp_enqueue_style(array(
			'acf-input-repeater',	
		));
		
	}
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add css + javascript to assist your create_field_options() action.
	*
	*  $info	http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*/

	function field_group_admin_enqueue_scripts()
	{
		wp_register_script( 'acf-field-group-repeater', $this->settings['dir'] . 'js/field-group.js', array('acf-field-group'), $this->settings['version']);
		
		// scripts
		wp_enqueue_script(array(
			'acf-field-group-repeater',	
		));
	}
	
	
	/*
	*  load_field()
	*
	*  This filter is appied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*
	*  @return	$field - the field array holding all the field options
	*/
	
	function load_field( $field )
	{
		
		// apply_load field to all sub fields
		if( isset($field['sub_fields']) && is_array($field['sub_fields']) )
		{
			foreach( $field['sub_fields'] as $k => $sub_field )
			{
				// apply filters
				$sub_field = apply_filters('acf/load_field_defaults', $sub_field);
				
				
				// apply filters
				foreach( array('type', 'name', 'key') as $key )
				{
					// run filters
					$sub_field = apply_filters('acf/load_field/' . $key . '=' . $sub_field[ $key ], $sub_field); // new filter
				}

				
				// update sub field
				$field['sub_fields'][ $k ] = $sub_field;
			}
		}
		
		return $field;
		
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
		$defaults = array(
			'row_limit'		=>	0,
			'row_min'		=>	0,
			'layout' 		=> 'table',
			'sub_fields'	=>	array(),
			'button_label'	=>	__("Add Row",'acf'),
			'value'			=>	array(),
		);
		
		$field = array_merge($defaults, $field);
		
		
		// validate types
		$field['row_limit'] = (int) $field['row_limit'];
		$field['row_min'] = (int) $field['row_min'];
		
		
		// value may be false
		if( !is_array($field['value']) )
		{
			$field['value'] = array();
		}
		
		
		// row limit = 0?
		if( $field['row_limit'] < 1 )
		{
			$field['row_limit'] = 999;
		}
		
		

		// min rows
		if( $field['row_min'] > count($field['value']) )
		{
			for( $i = 0; $i < $field['row_min']; $i++ )
			{
				// already have a value? continue...
				if( isset($field['value'][$i]) )
				{
					continue;
				}
				
				// populate values
				$field['value'][$i] = array();
				
				foreach( $field['sub_fields'] as $sub_field)
				{
					$sub_value = isset($sub_field['default_value']) ? $sub_field['default_value'] : false;
					$field['value'][$i][ $sub_field['key'] ] = $sub_value;
				}
				
			}
		}

		
		// max rows
		if( $field['row_limit'] < count($field['value']) )
		{
			for( $i = 0; $i < count($field['value']); $i++ )
			{
				if( $i >= $field['row_limit'] )
				{
					unset( $field['value'][$i] );
				}
			}
		}

		
		// setup values for row clone
		$field['value']['acfcloneindex'] = array();
		foreach( $field['sub_fields'] as $sub_field)
		{
			$sub_value = isset($sub_field['default_value']) ? $sub_field['default_value'] : false;
			$field['value']['acfcloneindex'][ $sub_field['key'] ] = $sub_value;
		}

?>
<div class="repeater" data-min_rows="<?php echo $field['row_min']; ?>" data-max_rows="<?php echo $field['row_limit']; ?>">
	<table class="widefat acf-input-table <?php if( $field['layout'] == 'row' ): ?>row_layout<?php endif; ?>">
	<?php if( $field['layout'] == 'table' ): ?>
		<thead>
			<tr>
				<?php 
				
				// order th
				
				if( $field['row_limit'] > 1 ): ?>
					<th class="order"></th>
				<?php endif; ?>
				
				<?php foreach( $field['sub_fields'] as $sub_field): 
					
					// add width attr
					$attr = "";
					
					if( count($field['sub_fields']) > 1 && isset($sub_field['column_width']) && $sub_field['column_width'] )
					{
						$attr = 'width="' . $sub_field['column_width'] . '%"';
					}
					
					?>
					<th class="acf-th-<?php echo $sub_field['name']; ?>" <?php echo $attr; ?>>
						<span><?php echo $sub_field['label']; ?></span>
						<?php if( isset($sub_field['instructions']) ): ?>
							<span class="sub-field-instructions"><?php echo $sub_field['instructions']; ?></span>
						<?php endif; ?>
					</th><?php
				endforeach; ?>
							
				<?php
				
				// remove th
							
				if( $field['row_min'] < $field['row_limit'] ):  ?>
					<th class="remove"></th>
				<?php endif; ?>
			</tr>
		</thead>
	<?php endif; ?>
	<tbody>
	<?php if( $field['value'] ): foreach( $field['value'] as $i => $value ): ?>
		
		<tr class="<?php echo ( (string) $i == 'acfcloneindex') ? "row-clone" : "row"; ?>">
		
		<?php 
		
		// row number
		
		if( $field['row_limit'] > 1 ): ?>
			<td class="order"><?php echo $i+1; ?></td>
		<?php endif; ?>
		
		<?php
		
		// layout: Row
		
		if( $field['layout'] == 'row' ): ?>
			<td class="acf_input-wrap">
				<table class="widefat acf_input">
		<?php endif; ?>
		
		
		<?php
		
		// loop though sub fields
		
		foreach( $field['sub_fields'] as $sub_field ): ?>
		
			<?php
		
			// layout: Row
			
			if( $field['layout'] == 'row' ): ?>
				<tr>
					<td class="label">
						<label><?php echo $sub_field['label']; ?></label>
						<?php if( isset($sub_field['instructions']) ): ?>
							<span class="sub-field-instructions"><?php echo $sub_field['instructions']; ?></span>
						<?php endif; ?>
					</td>
			<?php endif; ?>
			
			<td class="sub_field field_type-<?php echo $sub_field['type']; ?> field_key-<?php echo $sub_field['key']; ?>" data-field_type="<?php echo $sub_field['type']; ?>" data-field_key="<?php echo $sub_field['key']; ?>" data-field_name="<?php echo $sub_field['name']; ?>">
				<?php
				
				// add value
				$sub_field['value'] = isset($value[$sub_field['key']]) ? $value[$sub_field['key']] : '';
					
				// add name
				$sub_field['name'] = $field['name'] . '[' . $i . '][' . $sub_field['key'] . ']';
				
				// clear ID (needed for sub fields to work!)
				unset( $sub_field['id'] );
				
				// create field
				do_action('acf/create_field', $sub_field);
				
				?>
			</td>
			
			<?php
		
			// layout: Row
			
			if( $field['layout'] == 'row' ): ?>
				</tr>				
			<?php endif; ?>
			
		<?php endforeach; ?>
			
		<?php
		
		// layout: Row
		
		if( $field['layout'] == 'row' ): ?>
				</table>
			</td>
		<?php endif; ?>
		
		<?php 
		
		// delete row
		
		if( $field['row_min'] < $field['row_limit'] ): ?>
			<td class="remove">
				<a class="acf-button-add add-row-before" href="javascript:;"></a>
				<a class="acf-button-remove" href="javascript:;"></a>
			</td>
		<?php endif; ?>
		
		</tr>
	<?php endforeach; endif; ?>
	</tbody>
	</table>
	<?php if( $field['row_min'] < $field['row_limit'] ): ?>

	<ul class="hl clearfix repeater-footer">
		<li class="right">
			<a href="javascript:;" class="add-row-end acf-button"><?php echo $field['button_label']; ?></a>
		</li>
	</ul>

	<?php endif; ?>	
</div>
		<?php
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
		// vars
		$defaults = array(
			'row_limit'		=>	'',
			'row_min'		=>	0,
			'layout' 		=> 'table',
			'sub_fields'	=>	array(),
			'button_label'	=>	__("Add Row",'acf'),
			'value'			=>	array(),
		);
		
		$field = array_merge($defaults, $field);
		$key = $field['name'];
		
		
		// validate types
		$field['row_min'] = (int) $field['row_min'];
		
		
		// add clone
		$field['sub_fields'][] = apply_filters('acf/load_field_defaults',  array(
			'key' => 'field_clone',
			'label' => __("New Field",'acf'),
			'name' => __("new_field",'acf'),
			'type' => 'text',
		));
		
		
		// get name of all fields for use in field type drop down
		$fields_names = apply_filters('acf/registered_fields', array());
		unset( $fields_names[ __("Layout",'acf') ]['tab'] );
		
		?>
<tr class="field_option field_option_<?php echo $this->name; ?> field_option_<?php echo $this->name; ?>_fields">
	<td class="label">
		<label><?php _e("Repeater Fields",'acf'); ?></label>
	</td>
	<td>
	<div class="repeater">
		<div class="fields_header">
			<table class="acf widefat">
				<thead>
					<tr>
						<th class="field_order"><?php _e('Field Order','acf'); ?></th>
						<th class="field_label"><?php _e('Field Label','acf'); ?></th>
						<th class="field_name"><?php _e('Field Name','acf'); ?></th>
						<th class="field_type"><?php _e('Field Type','acf'); ?></th>
					</tr>
				</thead>
			</table>
		</div>
		<div class="fields">

			<div class="no_fields_message" <?php if(count($field['sub_fields']) > 1){ echo 'style="display:none;"'; } ?>>
				<?php _e("No fields. Click the \"+ Add Sub Field button\" to create your first field.",'acf'); ?>
			</div>
	
			<?php foreach($field['sub_fields'] as $sub_field): 
				
				$fake_name =  $key . '][sub_fields][' . $sub_field['key'];
				
				?>
				<div class="field field_type-<?php echo $sub_field['type']; ?> field_key-<?php echo $sub_field['key']; ?> sub_field" data-id="<?php echo $sub_field['key']; ?>">
					<input type="hidden" class="input-field_key" name="fields[<?php echo $fake_name; ?>][key]" value="<?php echo $sub_field['key']; ?>" />
					<div class="field_meta">
					<table class="acf widefat">
						<tr>
							<td class="field_order"><span class="circle"><?php echo (int)$sub_field['order_no'] + 1; ?></span></td>
							<td class="field_label">
								<strong>
									<a class="acf_edit_field" title="<?php _e("Edit this Field",'acf'); ?>" href="javascript:;"><?php echo $sub_field['label']; ?></a>
								</strong>
								<div class="row_options">
									<span><a class="acf_edit_field" title="<?php _e("Edit this Field",'acf'); ?>" href="javascript:;"><?php _e("Edit",'acf'); ?></a> | </span>
									<span><a title="<?php _e("Read documentation for this field",'acf'); ?>" href="http://www.advancedcustomfields.com/docs/field-types/" target="_blank"><?php _e("Docs",'acf'); ?></a> | </span>
									<span><a class="acf_duplicate_field" title="<?php _e("Duplicate this Field",'acf'); ?>" href="javascript:;"><?php _e("Duplicate",'acf'); ?></a> | </span>
									<span><a class="acf_delete_field" title="<?php _e("Delete this Field",'acf'); ?>" href="javascript:;"><?php _e("Delete",'acf'); ?></a>
								</div>
							</td>
							<td class="field_name"><?php echo $sub_field['name']; ?></td>
							<td class="field_type"><?php echo $sub_field['type']; ?></td>
						</tr>
					</table>
					</div>
					
					<div class="field_form_mask">
					<div class="field_form">
						
						<table class="acf_input widefat">
							<tbody>
								<tr class="field_label">
									<td class="label">
										<label><span class="required">*</span><?php _e("Field Label",'acf'); ?></label>
										<p class="description"><?php _e("This is the name which will appear on the EDIT page",'acf'); ?></p>
									</td>
									<td>
										<?php 
										do_action('acf/create_field', array(
											'type'	=>	'text',
											'name'	=>	'fields[' . $fake_name . '][label]',
											'value'	=>	$sub_field['label'],
											'class'	=>	'label',
										));
										?>
									</td>
								</tr>
								<tr class="field_name">
									<td class="label">
										<label><span class="required">*</span><?php _e("Field Name",'acf'); ?></label>
										<p class="description"><?php _e("Single word, no spaces. Underscores and dashes allowed",'acf'); ?></p>
									</td>
									<td>
										<?php 
										do_action('acf/create_field', array(
											'type'	=>	'text',
											'name'	=>	'fields[' . $fake_name . '][name]',
											'value'	=>	$sub_field['name'],
											'class'	=>	'name',
										));
										?>
									</td>
								</tr>
								<tr class="field_type">
									<td class="label"><label><span class="required">*</span><?php _e("Field Type",'acf'); ?></label></td>
									<td>
										<?php 
										do_action('acf/create_field', array(
											'type'	=>	'select',
											'name'	=>	'fields[' . $fake_name . '][type]',
											'value'	=>	$sub_field['type'],
											'class'	=>	'type',
											'choices'	=>	$fields_names,
											'optgroup' 	=> 	true
										));
										?>
									</td>
								</tr>
								<tr class="field_instructions">
									<td class="label"><label><?php _e("Field Instructions",'acf'); ?></label></td>
									<td>
										<?php
										
										if( !isset($sub_field['instructions']) )
										{
											$sub_field['instructions'] = "";
										}
										
										do_action('acf/create_field', array(
											'type'	=>	'text',
											'name'	=>	'fields[' . $fake_name . '][instructions]',
											'value'	=>	$sub_field['instructions'],
											'class'	=>	'instructions',
										));
										?>
									</td>
								</tr>
								<tr class="field_column_width">
									<td class="label">
										<label><?php _e("Column Width",'acf'); ?></label>
										<p class="description"><?php _e("Leave blank for auto",'acf'); ?></p>
									</td>
									<td>
										<?php 
										
										if( !isset($sub_field['column_width']) )
										{
											$sub_field['column_width'] = "";
										}
										
										do_action('acf/create_field', array(
											'type'	=>	'number',
											'name'	=>	'fields[' . $fake_name . '][column_width]',
											'value'	=>	$sub_field['column_width'],
											'class'	=>	'column_width',
										));
										?> %
									</td>
								</tr>
								<?php 
								
								$sub_field['name'] = $fake_name;
								do_action('acf/create_field_options', $sub_field );
								
								?>
								<tr class="field_save">
									<td class="label">
										<!-- <label><?php _e("Save Field",'acf'); ?></label> -->
									</td>
									<td>
										<ul class="hl clearfix">
											<li>
												<a class="acf_edit_field acf-button grey" title="<?php _e("Close Field",'acf'); ?>" href="javascript:;"><?php _e("Close Sub Field",'acf'); ?></a>
											</li>
										</ul>
									</td>
								</tr>								
							</tbody>
						</table>
				
					</div><!-- End Form -->
					</div><!-- End Form Mask -->
				
				</div>
			<?php endforeach; ?>
		</div>
		<div class="table_footer">
			<div class="order_message"><?php _e('Drag and drop to reorder','acf'); ?></div>
			<a href="javascript:;" id="add_field" class="acf-button"><?php _e('+ Add Sub Field','acf'); ?></a>
		</div>
	</div>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Minimum Rows",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][row_min]',
			'value'	=>	$field['row_min'],
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Maximum Rows",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][row_limit]',
			'value'	=>	$field['row_limit'],
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?> field_option_<?php echo $this->name; ?>_layout">
	<td class="label">
		<label><?php _e("Layout",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'radio',
			'name'	=>	'fields['.$key.'][layout]',
			'value'	=>	$field['layout'],
			'layout'	=>	'horizontal',
			'choices'	=>	array(
				'table'	=>	__("Table (default)",'acf'),
				'row'	=>	__("Row",'acf')
			)
		));
		?>
	</td>
</tr>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Button Label",'acf'); ?></label>
	</td>
	<td>
		<?php 
		do_action('acf/create_field', array(
			'type'	=>	'text',
			'name'	=>	'fields['.$key.'][button_label]',
			'value'	=>	$field['button_label'],
		));
		?>
	</td>
</tr>
		<?php		
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
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the $post_id of which the value will be saved
	*
	*  @return	$value - the modified value
	*/
	
	function update_value( $value, $post_id, $field )
	{
		$total = 0;
		
		if( $value )
		{
			// remove dummy field
			unset( $value['acfcloneindex'] );
			
			$i = -1;
			
			// loop through rows
			foreach( $value as $row )
			{	
				$i++;
				
				// increase total
				$total++;
					
				// loop through sub fields
				foreach( $field['sub_fields'] as $sub_field )
				{
					// get sub field data
					$v = isset( $row[$sub_field['key']] ) ? $row[$sub_field['key']] : false;
					
					// update full name
					$sub_field['name'] = $field['name'] . '_' . $i . '_' . $sub_field['name'];
					
					// save sub field value
					do_action('acf/update_value', $v, $post_id, $sub_field );
					
				}
			}
		}
		
		
		/*
		*  Remove Old Data
		*
		*  @credit: http://support.advancedcustomfields.com/discussion/1994/deleting-single-repeater-fields-does-not-remove-entry-from-database
		*/
		
		
		$old_total = (int) apply_filters('acf/load_value', 0, $post_id, $field);
		
		if( $old_total > $total )
		{
			for ( $j = $total; $j < $old_total; $j++ )
			{
				foreach( $field['sub_fields'] as $sub_field )
				{
					do_action('acf/delete_value', $post_id, $field['name'] . '_' . $j . '_' . $sub_field['name'] );
				}
			}
		}

		

		
		// update $value and return to allow for the normal save function to run
		$value = $total;
		
		
		return $value;
	}
	
	
	/*
	*  update_field()
	*
	*  This filter is appied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field - the field array holding all the field options
	*  @param	$post_id - the field group ID (post_type = acf)
	*
	*  @return	$field - the modified field
	*/

	function update_field( $field, $post_id )
	{
		// format sub_fields
		if( $field['sub_fields'] )
		{
			// remove dummy field
			unset( $field['sub_fields']['field_clone'] );
			
			
			// loop through and save fields
			$i = -1;
			$sub_fields = array();
			
			
			foreach( $field['sub_fields'] as $key => $f )
			{
				$i++;
				
				
				// order
				$f['order_no'] = $i;
				$f['key'] = $key;
				
				
				// save
				$f = apply_filters('acf/update_field/type=' . $f['type'], $f, $post_id ); // new filter
				
				
				// add
				$sub_fields[] = $f;
			}
			
			
			// update sub fields
			$field['sub_fields'] = $sub_fields;
			
		}
		
		
		// return updated repeater field
		return $field;
	}
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is passed to the create_field action
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
	
	function format_value( $value, $post_id, $field )
	{
		// vars
		$values = array();


		if( $value > 0 )
		{
			// loop through rows
			for($i = 0; $i < $value; $i++)
			{
				// loop through sub fields
				foreach( $field['sub_fields'] as $sub_field )
				{
					// update full name
					$key = $sub_field['key'];
					$sub_field['name'] = $field['name'] . '_' . $i . '_' . $sub_field['name'];
					
					$v = apply_filters('acf/load_value', false, $post_id, $sub_field);
					$v = apply_filters('acf/format_value', $v, $post_id, $sub_field);
					
					$values[ $i ][ $key ] = $v;
					
				}
			}
		}
		
		
		// return
		return $values;
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
		// vars
		$values = array();
		
		
		if( $value > 0 )
		{
			// loop through rows
			for($i = 0; $i < $value; $i++)
			{
				// loop through sub fields
				foreach( $field['sub_fields'] as $sub_field )
				{
					// update full name
					$key = $sub_field['name'];
					$sub_field['name'] = $field['name'] . '_' . $i . '_' . $sub_field['name'];
					
					$v = apply_filters('acf/load_value', false, $post_id, $sub_field);
					$v = apply_filters('acf/format_value_for_api', $v, $post_id, $sub_field);
					
					$values[ $i ][ $key ] = $v;
					
				}
			}
		}
		
		
		// return
		return $values;
	}
	
}

new acf_field_repeater();

?>
