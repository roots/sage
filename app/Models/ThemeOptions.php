<?php

namespace App\Models;

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action('carbon_fields_register_fields', function () {
    Container::make('theme_options', 'Theme options')
        ->add_tab(__('General'), [
            Field::make('text', 'site_name', 'Site name'),
            ]
        );
});
