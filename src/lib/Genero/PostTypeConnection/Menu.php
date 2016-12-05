<?php

namespace Genero\Sage\PostTypeConnection;

use TimberExtended;

/**
 * Extend the Timber Menu so that menu item ancestry take post type connections
 * into account.
 */
class Menu extends TimberExtended\Menu
{
    // @codingStandardsIgnoreLine
    protected function is_childpage($pid, $post = NULL) {
        $isChild = parent::is_childpage($pid, $post);
        // This has already been checked.
        if ($isChild || is_page()) {
            return true;
        }
        if (is_null($post)) {
            $post = get_post();
        }
        return \Genero\Sage\PostTypeConnection::isParent($pid, $post->post_type);
    }
}
