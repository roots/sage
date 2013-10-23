<?php
add_filter( 'image_size_names_choose', 'theme_custom_sizes' );

function theme_custom_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'mini' => __('Mini'),
        'x-small' => __('Xtra Small'),
        'small-tall' => __('Small + Tall'),
        'small' => __('Small'),
        'medium' => __('Medium'),
        'large' => __('Homepage Carousel'),
        'home-carousel' => __('Homepage Carousel'),
    ) );
}