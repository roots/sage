#!/console/art php
<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;

$console = new Application();

$console
    ->register('make:model')
    ->addArgument('name', InputArgument::REQUIRED, 'The view name')
    ->setCode(function ($input) {
        $model = __DIR__ . '\\..\\app\\Models\\' . $input->getArgument('name') . '.php';

        if (!file_exists($model)) {
            $content = "<?php \n\n use Carbon_Fields\Container; \n use Carbon_Fields\Field;";

            file_put_contents($model, $content);
        }
    });

$console->run();
