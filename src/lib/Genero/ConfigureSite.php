<?php

namespace Genero\Sage;

class ConfigureSite
{
    private static $menus = [
        [
            'name' => 'Primary menu',
            'location' => 'primary_navigation',
        ],
    ];

    public static function menus() {
        foreach (self::$menus as $menu) {
            if (wp_get_nav_menu_object($menu_name)) {
                continue;
            }
            $menu_id = wp_create_nav_menu($menu['name']);
            if (isset($menu['location']) && !has_nav_menu($menu['location'])) {
                $locations = get_theme_mod('nav_menu_locations');
                $locations[$menu['location']] = $menu_id;
                set_theme_mod('nav_menu_locations', $locations);
            }
        }
    }
}
