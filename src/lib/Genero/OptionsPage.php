<?php

namespace Genero\Sage;

class OptionsPage extends AcfFieldLoader
{
    protected static $groupKey = 'group_5841faa52ddd4';

    public static function addAcfFieldgroup()
    {
        if (self::validateRequirements() && !acf_get_field_group(self::$groupKey)) {
            require_once __DIR__ . '/OptionsPage/acf.php';
            parent::saveFieldgroupToDatabase(self::$groupKey);
        }
    }

    public static function validateRequirements()
    {
        return true;
    }
}
