<?php

/**
 * Block Styles
 * Register block styles for this theme
 */

register_block_style(
    'create-block/container',
    array(
      'name'         => 'container-full',
      'label'        => __('No Padding'),
    )
);

register_block_style(
    'create-block/container',
    array(
      'name'         => 'half-padding',
      'label'        => __('Half Padding'),
    )
);