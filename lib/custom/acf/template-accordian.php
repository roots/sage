<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf-template-accordion',
		'title' => 'Template - Accordion',
		'fields' => array (
			array (
				'key' => 'field_5215bca260248',
				'label' => 'Panels',
				'name' => 'panels',
				'type' => 'flexible_content',
				'layouts' => array (
					array (
						'label' => 'Panel Default',
						'name' => 'panel_default',
						'display' => 'row',
						'sub_fields' => array (
							array (
								'key' => 'field_5215bcc060249',
								'label' => 'Panel Title',
								'name' => 'panel_title',
								'type' => 'text',
								'column_width' => '',
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'formatting' => 'none',
								'maxlength' => '',
							),
							array (
								'key' => 'field_5215bcc96024a',
								'label' => 'Panel Body',
								'name' => 'panel_body',
								'type' => 'wysiwyg',
								'column_width' => '',
								'default_value' => '',
								'toolbar' => 'full',
								'media_upload' => 'yes',
							),
						),
					),
				),
				'button_label' => 'Add Panel',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'template-accordion.php',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'acf_after_title',
			'layout' => 'no_box',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
