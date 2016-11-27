<?php

namespace Genero\Sage;

class OptionsPage extends AcfFieldLoader {
    private static $connections;
    protected static $group_key = 'group_5841faa52ddd4';

    public static function addAcfFieldgroup() {
        if (self::validateRequirements() && !acf_get_field_group(self::$group_key)) {
            require_once __DIR__ . '/OptionsPage/acf.php';
            parent::saveFieldgroupToDatabase(self::$group_key);
        }
    }

    public static function validateRequirements() {
        return true;
    }

}
