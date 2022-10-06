<?php
namespace App;

collect([
  'styles',
  'templates',
])->each(function ($file) {
    $file = "app/Blocks/{$file}.php";

    if (! locate_template($file, true, true)) {
        wp_die(
            sprintf(__('Error locating <code>%s</code> for inclusion.', 'sage'), $file)
        );
    }
});