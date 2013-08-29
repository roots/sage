#!/usr/bin/php
<?php

require_once dirname(__FILE__) . '/common.php';
require_once dirname(__FILE__) . '/../library/HTMLPurifier.auto.php';
assertCli();

/**
 * @file
 * Generates a schema cache file, saving it to
 * library/HTMLPurifier/ConfigSchema/schema.ser.
 *
 * This should be run when new configuration options are added to
 * HTML Purifier. A cached version is available via the repository
 * so this does not normally have to be regenerated.
 *
 * If you have a directory containing custom configuration schema files,
 * you can simple add a path to that directory as a parameter to
 * this, and they will get included.
 */

$target = dirname(__FILE__) . '/../library/HTMLPurifier/ConfigSchema/schema.ser';

$builder = new HTMLPurifier_ConfigSchema_InterchangeBuilder();
$interchange = new HTMLPurifier_ConfigSchema_Interchange();

$builder->buildDir($interchange);

$loader = dirname(__FILE__) . '/../config-schema.php';
if (file_exists($loader)) include $loader;
foreach ($_SERVER['argv'] as $i => $dir) {
    if ($i === 0) continue;
    $builder->buildDir($interchange, realpath($dir));
}

$interchange->validate();

$schema_builder = new HTMLPurifier_ConfigSchema_Builder_ConfigSchema();
$schema = $schema_builder->build($interchange);

echo "Saving schema... ";
file_put_contents($target, serialize($schema));
echo "done!\n";

// vim: et sw=4 sts=4
