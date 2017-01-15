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

    public static function removeBootstrap(Event $event)
    {
        $io = $event->getIO();

        if ($io->isInteractive()) {
            if ($io->askConfirmation('<info>Remove Bootstrap?</info> [<comment>y,N</comment>]? ', false)) {
                file_put_contents('package.json', str_replace('    "bootstrap": "^4.0.0-alpha.6",' . "\n", '', file_get_contents('package.json')));
                file_put_contents('assets/styles/main.scss', str_replace('// Import npm dependencies' . "\n", '', file_get_contents('assets/styles/main.scss')));
                file_put_contents('assets/styles/main.scss', str_replace('@import "~bootstrap/scss/bootstrap";' . "\n", '', file_get_contents('assets/styles/main.scss')));
                file_put_contents('assets/scripts/main.js', str_replace('import \'bootstrap/dist/js/bootstrap\';' . "\n", '', file_get_contents('assets/scripts/main.js')));
                file_put_contents('assets/styles/components/_comments.scss', '');
                file_put_contents('assets/styles/components/_forms.scss', '');
                file_put_contents('assets/styles/components/_wp-classes.scss', '');
                file_put_contents('assets/styles/layouts/_header.scss', '');
            }
        }
    }
    // @codingStandardsIgnoreEnd
}
