<?php
// @codingStandardsIgnoreFile
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5841f81994eeb',
	'title' => 'Hero banner',
	'fields' => array (
		array (
			'key' => 'field_5841f825dcb82',
			'label' => 'Default hero image',
			'name' => 'hero_default_image',
			'type' => 'image_crop',
			'instructions' => 'The default hero image to use when no other can be found',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'crop_type' => 'min',
			'target_size' => 'thumbnail',
			'width' => '',
			'height' => '',
			'preview_size' => 'medium',
			'force_crop' => 'no',
			'save_in_media_library' => 'no',
			'retina_mode' => 'no',
			'save_format' => 'url',
			'library' => 'all',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'acf-options',
			),
		),
	),
	'menu_order' => 80,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
