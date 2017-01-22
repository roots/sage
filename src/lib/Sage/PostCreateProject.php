<?php

namespace Roots\Sage;

use Composer\Script\Event;

class PostCreateProject
{
    public static function updateHeaders(Event $event)
    {
        // @codingStandardsIgnoreStart
        $io = $event->getIO();

        if ($io->isInteractive()) {
            $io->write('<info>Define theme headers. Press enter key for default.</info>');

            $theme_headers_default = [
                'name'        => 'Sage Starter Theme',
                'uri'         => 'https://roots.io/sage/',
                'description' => 'Sage is a WordPress starter theme.',
                'version'     => '9.0.0-beta.2',
                'author'      => 'Roots',
                'author_uri'  => 'https://roots.io/'
            ];
            $theme_headers = [
              'name'        => $io->ask('<info>Theme Name [<comment>'.$theme_headers_default['name'].'</comment>]:</info> ', $theme_headers_default['name']),
              'uri'         => $io->ask('<info>Theme URI [<comment>'.$theme_headers_default['uri'].'</comment>]:</info> ', $theme_headers_default['uri']),
              'description' => $io->ask('<info>Theme Description [<comment>'.$theme_headers_default['description'].'</comment>]:</info> ', $theme_headers_default['description']),
              'version'     => $io->ask('<info>Theme Version [<comment>'.$theme_headers_default['version'].'</comment>]:</info> ', $theme_headers_default['version']),
              'author'      => $io->ask('<info>Theme Author [<comment>'.$theme_headers_default['author'].'</comment>]:</info> ', $theme_headers_default['author']),
              'author_uri'  => $io->ask('<info>Theme Author URI [<comment>'.$theme_headers_default['author_uri'].'</comment>]:</info> ', $theme_headers_default['author_uri'])
            ];

            file_put_contents('style.css', str_replace($theme_headers_default, $theme_headers, file_get_contents('style.css')));
        }
    }

    public static function selectFramework(Event $event)
    {
        $io = $event->getIO();
        $default_framework_pattern = '"bootstrap": ".*"';

        $files_to_clear = [
          'assets/styles/components/_comments.scss',
          'assets/styles/components/_forms.scss',
          'assets/styles/components/_wp-classes.scss',
          'assets/styles/layouts/_header.scss',
        ];


        if ($io->isInteractive()) {
            $frameworks = [
                'Bootstrap',
                'Foundation',
                'None'
            ];
            $framework = $io->select('<info>Select a CSS framework</info> <comment>(Default: Bootstrap)</comment>', $frameworks, 0);

            switch($framework) {
                case 0:
                    break;
                case 1:
                    file_put_contents('package.json', preg_replace("/{$default_framework_pattern}/", '"foundation-sites": "6.3.0"', file_get_contents('package.json')));
                    file_put_contents('assets/styles/main.scss', str_replace('@import "~bootstrap/scss/bootstrap";' . "\n", '@import "~foundation-sites/scss/foundation";' . "\n" . '@include foundation-everything;' . "\n", file_get_contents('assets/styles/main.scss')));
                    file_put_contents('assets/scripts/main.js', str_replace("import 'bootstrap';\n", "import 'foundation-sites/dist/js/foundation';\n", file_get_contents('assets/scripts/main.js')));
                    foreach($files_to_clear as $file) {
                        file_put_contents($file, '');
                    }
                    break;
                case 2:
                    file_put_contents('package.json', preg_replace("/\s+{$default_framework_pattern},/", '', file_get_contents('package.json')));
                    file_put_contents('assets/styles/main.scss', str_replace('@import "~bootstrap/scss/bootstrap";' . "\n", '', file_get_contents('assets/styles/main.scss')));
                    file_put_contents('assets/scripts/main.js', str_replace("import 'bootstrap';\n", '', file_get_contents('assets/scripts/main.js')));
                    foreach($files_to_clear as $file) {
                        file_put_contents($file, '');
                    }
                    break;
            }
        }
    }

    public static function addFontAwesome(Event $event)
    {
        $io = $event->getIO();

        if ($io->isInteractive()) {
            if ($io->askConfirmation('<info>Add Font Awesome?</info> [<comment>y,N</comment>]? ', false)) {
                $package = json_decode(file_get_contents('package.json'), true);
                $dependencies = $package['dependencies'];
                $dependencies = array_merge($dependencies, ['font-awesome' => '^4.7.0']);
                $package['dependencies'] = $dependencies;
                $package = str_replace('    ', '  ', json_encode($package, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
                file_put_contents('package.json', $package);
                
                $import_dep_str = '// Import npm dependencies' . "\n";
                file_put_contents('assets/styles/main.scss', str_replace($import_dep_str, $import_dep_str . '@import "~font-awesome/scss/font-awesome";' . "\n", file_get_contents('assets/styles/main.scss')));
                file_put_contents('assets/styles/common/_variables.scss', "\n" . '$fa-font-path:          \'~font-awesome/fonts\';' . "\n", FILE_APPEND);
            }
        }
    }
    // @codingStandardsIgnoreEnd
}
