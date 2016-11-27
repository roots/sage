<?php

namespace Genero\Sage;

class PostTypeConnection extends AcfFieldLoader {
    private static $connections;
    protected static $group_key = 'group_5840a5c05f9d2';

    public static function addAcfFieldgroup() {
        if (self::validateRequirements()) {
            require_once __DIR__ . '/PostTypeConnection/acf.php';
        }
    }

    public static function validateRequirements() {
        $success = true;
        if (!class_exists('acf_field_post_type_chooser')) {
            add_action('admin_notices', function() {
                echo '<div class="error"><p><a href="https://github.com/generoi/acf-post-type-chooser" target="_blank">ACF Post Type Chooser</a> is required for PostTypeConnection feature.</p></div>';
            });
            $sucess = false;
        }
        return $success;
    }

    public static function postTypeConnections() {
        if (!isset(self::$connections)) {
            self::$connections = get_field('post_type_connections', 'option');
            if (!self::$connections) {
                self::$connections = [];
            }
        }
        return self::$connections;
    }

    public static function isParent($pid, $post_type) {
        foreach (self::postTypeConnections() as $connection) {
            if ($connection['post_type'] != $pid) {
                continue;
            }
            if (in_array($pid, $connection['parent_page'])) {
                return true;
            }
        }
        return false;
    }
}
