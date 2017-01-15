<?php

namespace Roots\Sage;

use Composer\Script\Event;

class PostCreateProject
{
    public static function removeBootstrap(Event $event)
    {
        $io = $event->getIO();

        // @codingStandardsIgnoreStart
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
        // @codingStandardsIgnoreEnd
    }
}
