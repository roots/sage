<?php
// @codingStandardsIgnoreFile
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5841faa52ddd4',
	'title' => 'Theme options',
	'fields' => array (
		array (
			'key' => 'field_5841faafd0c8a',
			'label' => 'Footer copyright',
			'name' => 'footer_copyright',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Mitattavia tuloksia ja ainutlaatuisia tarinoita. Digitaalinen mainostoimisto.',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
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
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
