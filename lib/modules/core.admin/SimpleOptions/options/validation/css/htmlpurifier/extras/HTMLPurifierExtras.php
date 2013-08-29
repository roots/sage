<?php

/**
 * Meta-class for HTML Purifier's extra class hierarchies, similar to
 * HTMLPurifier_Bootstrap.
 */
class HTMLPurifierExtras
{

    public static function autoload($class) {
        $path = HTMLPurifierExtras::getPath($class);
        if (!$path) return false;
        require $path;
        return true;
    }

    public static function getPath($class) {
        if (
            strncmp('FSTools', $class, 7) !== 0 &&
            strncmp('ConfigDoc', $class, 9) !== 0
        ) return false;
        // Custom implementations can go here
        // Standard implementation:
        return str_replace('_', '/', $class) . '.php';
    }

}

// vim: et sw=4 sts=4
