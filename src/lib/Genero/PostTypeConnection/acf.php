<?php

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_5840a5c05f9d2',
	'title' => 'Post Type Connection',
	'private' => true,
	'fields' => array (
		array (
			'key' => 'field_5840a5c2ec82c',
			'label' => 'Post type connection',
			'name' => 'post_type_connection',
			'type' => 'repeater',
			'instructions' => 'Create connections between post types and pages, thus making it possible to detect parent pages in the menu.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_5840a5eaec82d',
			'min' => '',
			'max' => '',
			'layout' => 'table',
			'button_label' => 'Add Connection',
			'sub_fields' => array (
				array (
					'key' => 'field_5840a5eaec82d',
					'label' => 'Post type',
					'name' => 'post_type',
					'type' => 'post_type_chooser',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => '',
					'allow_null' => 0,
					'multiple' => 0,
					'ui' => 1,
					'ajax' => 0,
				),
				array (
					'key' => 'field_5840a615ec82e',
					'label' => 'Parent page',
					'name' => 'parent_page',
					'type' => 'relationship',
					'instructions' => '',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => array (
						0 => 'page',
					),
					'taxonomy' => array (
					),
					'filters' => '',
					'elements' => '',
					'min' => '',
					'max' => '',
					'return_format' => 'id',
				),
			),
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
	'menu_order' => 100,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;
