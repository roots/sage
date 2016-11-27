<?php

namespace Genero\Sage\PostTypeConnection;

use TimberExtended;

/**
 * Extend the Timber Menu so that menu item ancestry take post type connections
 * into account.
 */
class Menu extends TimberExtended\Menu
{
    protected function is_childpage($pid, $post = NULL) {
        $is_child = parent::is_childpage($pid, $post);
        // This has already been checked.
        if ($is_child || is_page()) {
            return true;
        }
        if (is_null($post)) {
            $post = get_post();
        }
        return \Genero\Sage\PostTypeConnection::isParent($pid, $post->post_type);
    }
}
