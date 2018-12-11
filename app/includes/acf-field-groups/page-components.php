<?php
$fields = [
    [
        'key' => 'field_page_components',
        'name' => 'page_components',
        'label' => esc_html__('Page Components', 'sage'),
        'button_label' => esc_html__('Add Component', 'sage'),
        'type' => 'flexible_content',
        'layouts' => [

        ],
    ],
];

acf_add_local_field_group([
    'key' => 'group_page_components',
    'title' => esc_html__('Page Components', 'sage'),
    'fields' => $fields,
    'location' => [
        [
            [
                'param' => 'post_type',
                'operator' => '==',
                'value' => 'page',
            ],
        ],
    ],
    'style' => 'seamless',
    'hide_on_screen' => [ 'the_content' ],
]);
