<?php
$fields = [
    [
        'key' => 'field_theme_settings_404_tab',
        'label' => esc_html__('404', 'sage'),
        'type' => 'tab',
    ],
    [
        'key' => 'field_theme_settings_404_title',
        'name' => 'settings_404_title',
        'label' => esc_html__('Heading', 'sage'),
        'type' => 'text',
        'required' => true,
    ],
    [
        'key' => 'field_theme_settings_404_content',
        'name' => 'settings_404_content',
        'label' => esc_html__('Content', 'sage'),
        'type' => 'wysiwyg',
        'delay' => true,
        'media_upload' => false,
        'required' => true,
    ],
];

acf_add_local_field_group([
    'title' => esc_html__('Theme Settings', 'sage'),
    'key' => 'group_theme_settings',
    'fields' => $fields,
    'style' => 'seamless',
    'location' => [
        [
            [
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'hembla_options',
            ],
        ],
    ],
]);
