<?php

/*
*  Meta box - options
*
*  This template file is used when editing a field group and creates the interface for editing options.
*
*  @type	template
*  @date	23/06/12
*/


// global
global $post;

	
// vars
$options = apply_filters('acf/field_group/get_options', array(), $post->ID);
	

?>
<table class="acf_input widefat" id="acf_options">
	<tr>
		<td class="label">
			<label for=""><?php _e("Order No.",'acf'); ?></label>
			<p class="description"><?php _e("Field groups are created in order <br />from lowest to highest",'acf'); ?></p>
		</td>
		<td>
			<?php 
			
			do_action('acf/create_field', array(
				'type'	=>	'number',
				'name'	=>	'menu_order',
				'value'	=>	$post->menu_order,
			));
			
			?>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for=""><?php _e("Position",'acf'); ?></label>
		</td>
		<td>
			<?php 
			
			do_action('acf/create_field', array(
				'type'	=>	'radio',
				'name'	=>	'options[position]',
				'value'	=>	$options['position'],
				'choices' => array(
					'acf_after_title'	=>	__("High (after title)",'acf'),
					'normal'			=>	__("Normal (after content)",'acf'),
					'side'				=>	__("Side",'acf'),
				),
				'default_value' => 'normal'
			));

			?>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for="post_type"><?php _e("Style",'acf'); ?></label>
		</td>
		<td>
			<?php 
			
			do_action('acf/create_field', array(
				'type'	=>	'radio',
				'name'	=>	'options[layout]',
				'value'	=>	$options['layout'],
				'choices' => array(
					'no_box'	=>	__("No Metabox",'acf'),
					'default'	=>	__("Standard Metabox",'acf'),
				)
			));
			
			?>
		</td>
	</tr>
	<tr>
		<td class="label">
			<label for="post_type"><?php _e("Hide on screen",'acf'); ?></label>
			<p class="description"><?php _e("<b>Select</b> items to <b>hide</b> them from the edit screen",'acf'); ?></p>
			<p class="description"><?php _e("If multiple field groups appear on an edit screen, the first field group's options will be used. (the one with the lowest order number)",'acf'); ?></p>
		</td>
		<td>
			<?php 
			
			do_action('acf/create_field', array(
				'type'	=>	'checkbox',
				'name'	=>	'options[hide_on_screen]',
				'value'	=>	$options['hide_on_screen'],
				'choices' => array(
					'the_content'		=>	__("Content Editor",'acf'),
					'excerpt'			=>	__("Excerpt"),
					'custom_fields'		=>	__("Custom Fields"),
					'discussion'		=>	__("Discussion"),
					'comments'			=>	__("Comments"),
					'revisions'			=>	__("Revisions"),
					'slug'				=>	__("Slug"),
					'author'			=>	__("Author"),
					'format'			=>	__("Format"),
					'featured_image'	=>	__("Featured Image"),
					'categories'		=>	__("Categories"),
					'tags'				=>	__("Tags"),
					'send-trackbacks'	=>	__("Send Trackbacks"),
				)
			));
			
			?>
		</td>
	</tr>
</table>