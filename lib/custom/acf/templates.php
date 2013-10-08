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
	register_field_group(array (
		'id' => 'acf-template-tabs',
		'title' => 'Template - Tabs',
		'fields' => array (
			array (
				'key' => 'field_5214310b88219',
				'label' => 'Tabs',
				'name' => 'tabs',
				'type' => 'flexible_content',
				'layouts' => array (
					array (
						'label' => 'Tab Normal',
						'name' => 'tab_normal',
						'display' => 'row',
						'sub_fields' => array (
							array (
								'key' => 'field_52143a9905a81',
								'label' => 'Tab Label',
								'name' => 'tab_label',
								'type' => 'text',
								'column_width' => 10,
								'default_value' => '',
								'placeholder' => '',
								'prepend' => '',
								'append' => '',
								'formatting' => 'none',
								'maxlength' => '',
							),
							array (
								'key' => 'field_52143ab005a82',
								'label' => 'Tab Content',
								'name' => 'tab_content',
								'type' => 'wysiwyg',
								'column_width' => 90,
								'default_value' => '',
								'toolbar' => 'full',
								'media_upload' => 'yes',
							),
						),
					),
				),
				'button_label' => 'Add Tab',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'page',
					'operator' => '==',
					'value' => '6123',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
			array (
				array (
					'param' => 'page_template',
					'operator' => '==',
					'value' => 'template-tabs.php',
					'order_no' => 0,
					'group_no' => 1,
				),
			),
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'shopp_product',
					'order_no' => 0,
					'group_no' => 2,
				),
			),
		),
		'options' => array (
			'position' => 'normal',
			'layout' => 'default',
			'hide_on_screen' => array (
				0 => 'discussion',
				1 => 'comments',
				2 => 'revisions',
				3 => 'slug',
				4 => 'author',
				5 => 'format',
				6 => 'send-trackbacks',
			),
		),
		'menu_order' => 0,
	));
}
