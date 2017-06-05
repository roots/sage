<?php

namespace App;

/**
* Define menus
*/
function primary_navigation()
{
    wp_nav_menu([
        'container' => false,
        'menu' => __('Primary Navigation', 'sage'),
        'menu_class' => 'menu horizontal',
        'theme_location' => 'primary_navigation',
        'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
        'link_before' => '<span class="link-wrap">',
        'link_after' => '</span>',
        'depth' => 5,
        'fallback_cb' => false,
        'walker' => new TopbarMenuWalker(),
    ]);
}

/**
* Menu walker to indent submenu ul elements for
* Foundation styles
* @link https://github.com/brettsmason
*/
class TopbarMenuWalker extends \Walker_Nav_Menu
{
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"vertical menu\">\n";
    }
}
