<?php

namespace Genero\Sage;

class Hero extends AcfFieldLoader {
    public static $group_key = 'group_5841c75e2a9b5';

    public static function addAcfFieldgroup() {
        if (self::validateRequirements()) {
            // Only save it if it doesn't exist, otherwise let themers edit it.
            if (!acf_get_field_group(self::$group_key)) {
                require_once __DIR__ . '/Hero/acf.php';
                parent::saveFieldgroupToDatabase(self::$group_key);
            }
            // Add options.
            require_once __DIR__ . '/Hero/options.php';
        }
    }

    public static function validateRequirements() {
        $success = true;
        if (!class_exists('acf_field_image_crop')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p><a href="https://wordpress.org/plugins/acf-image-crop-add-on/" target="_blank">ACF Image Crop</a> is required for the Hero feature.</p></div>';
            });
            $sucess = false;
        }
        return $success;
    }
}
