<?php
if(function_exists("register_field_group"))
{
	register_field_group(array (
		'id' => 'acf_product-file-downloads',
		'title' => 'Product File Downloads',
		'fields' => array (
			array (
				'key' => 'field_52368f0b7673b',
				'label' => '2D CAD Files',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_521893bc19173',
				'label' => 'DXF',
				'name' => 'dxf',
				'type' => 'file',
				'save_format' => 'object',
				'library' => 'all',
			),
			array (
				'key' => 'field_521893d019174',
				'label' => 'DWG',
				'name' => 'dwg',
				'type' => 'file',
				'save_format' => 'object',
				'library' => 'all',
			),
			array (
				'key' => 'field_52368f237673c',
				'label' => '3D CAD Files',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_52368eaf76739',
				'label' => 'SAT',
				'name' => 'sat',
				'type' => 'file',
				'save_format' => 'object',
				'library' => 'all',
			),
			array (
				'key' => 'field_52368eec7673a',
				'label' => 'STP',
				'name' => 'stp',
				'type' => 'file',
				'save_format' => 'object',
				'library' => 'all',
			),
			array (
				'key' => 'field_52368f4a7673d',
				'label' => 'Submittal Sheets',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_52368dd89e16f',
				'label' => 'Submittal Sheet',
				'name' => 'submittal_sheet',
				'type' => 'file',
				'save_format' => 'object',
				'library' => 'all',
			),
			array (
				'key' => 'field_52368f6e7673e',
				'label' => 'Additional Downloads',
				'name' => '',
				'type' => 'tab',
			),
			array (
				'key' => 'field_5218941519176',
				'label' => 'File Downloads',
				'name' => 'file_downloads',
				'type' => 'repeater',
				'sub_fields' => array (
					array (
						'key' => 'field_5218947e19178',
						'label' => 'Download Label',
						'name' => 'download_label',
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
						'key' => 'field_5218946b19177',
						'label' => 'Download File',
						'name' => 'download_file',
						'type' => 'file',
						'column_width' => '',
						'save_format' => 'object',
						'library' => 'all',
					),
				),
				'row_min' => 0,
				'row_limit' => '',
				'layout' => 'table',
				'button_label' => 'Add File',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'product',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options' => array (
			'position' => 'side',
			'layout' => 'default',
			'hide_on_screen' => array (
			),
		),
		'menu_order' => 0,
	));
}
