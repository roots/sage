<?php

namespace App;

add_action('init', function () {
    sage('blade')->compiler()->directive('dumpData', function () {
        return <<<'EOD'
<?php
$vars = get_defined_vars()['__data'];
unset($vars['__env'], $vars['app']);
dd($vars);
?>
EOD;
    });

    sage('blade')->compiler()->directive('theContent', function () {
        return '<?php the_content(); ?>';
    });

    sage('blade')->compiler()->directive('pageComponents', function () {
        return <<<'EOD'
<?php
if (! post_password_required()) {
    while(have_rows( 'page_components' )) {
        the_row();

        $class_name = \Illuminate\Support\Str::title( str_replace( '_', '', get_row_layout() ) );

        $full_class_name = '\\App\PageComponents\\' . $class_name;

        if (! class_exists($full_class_name)) {
            wp_die(
                "<h1>Class not found.</h1><p>Component class {$full_class_name} not found.</p>",
                'Class not found.'
            );
        }

        $container = App\sage();

        $component = $container->make($full_class_name);

        $data = $component->__getData();

        if ( ! $data ) {
            continue;
        }

        $component_path = sprintf( 'components.%1$s.%1$s', str_replace( '_', '-', get_row_layout() ) );

        echo App\template( $component_path, $data );
    }
}
?>
EOD;
    });
});
