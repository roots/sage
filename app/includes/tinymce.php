<?php

namespace App;

/**
 * Add styles/classes to the "Styles" drop-down
 */
add_filter('mce_buttons_2', function ($buttons) {
    array_unshift($buttons, 'styleselect');
    return $buttons;
});

add_filter('tiny_mce_before_init', function ($settings) {
    $style_formats = [
        [
            'title' => esc_html__('Text Styles', 'sage'),
            'items' => [
                [
                    'title' => 'Preamble',
                    'selector' => 'p',
                    'classes' => 'preamble',
                ],
            ],
        ],
        [
            'title' => esc_html__('Buttons', 'sage'),
            'items' => [
                [
                    'title' => esc_html__('Default', 'sage'),
                    'selector' => 'a',
                    'attributes' => [ 'class' => 'btn' ],
                ],
            ],
        ],
    ];

    $heading = esc_html__('Heading', 'sage');

    $block_formats = [
        'p' => esc_html__('Paragraph', 'sage'),
        'h1' => sprintf('%s 1', $heading),
        'h2' => sprintf('%s 2', $heading),
        'h3' => sprintf('%s 3', $heading),
        'h4' => sprintf('%s 4', $heading),
    ];

    $block_formats = array_map(function ($label, $element) {
        return sprintf('%s=%s', $label, $element);
    }, array_values($block_formats), array_keys($block_formats));

    $settings['block_formats'] = implode(';', $block_formats);

    $settings['style_formats'] = json_encode($style_formats);

    $settings['body_class'] .= ' entry-content';

    return $settings;
});

/**
 * Remove tinymce buttons row 1
 */
add_filter('mce_buttons', function ($buttons) {
    $buttons = array_filter($buttons, function ($value) {
        $allowed_buttons = [
            'formatselect',
            'bold',
            'italic',
            'bullist',
            'numlist',
            'blockquote',
            'alignleft',
            'aligncenter',
            'alignright',
            'link',
            'unlink',
            'fullscreen',
            'wp_adv',
        ];

        return in_array($value, $allowed_buttons);
    });

    return $buttons;
});

/**
 * Remove tinymce buttons row 2
 */
add_filter('mce_buttons_2', function ($buttons) {
    $buttons = array_filter($buttons, function ($value) {
        $allowed_buttons = [
            'styleselect',
            'hr',
            'underline',
            'pastetext',
            'removeformat',
            'table',
        ];

        return in_array($value, $allowed_buttons);
    });

    return $buttons;
});
