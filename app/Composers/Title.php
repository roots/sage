<?php

namespace App\Composers;

use Roots\Acorn\View\Composer;

class Title extends Composer
{
    protected static $views = [
        'partials.page-header',
        'partials.content',
        'partials.content-*'
    ];

    public function with($data, $view)
    {
        return ['title' => $this->title($view->getName())];
    }

    public function title($view)
    {
        if ($view !== 'partials.page-header') {
            return get_the_title();
        }
        if (is_home()) {
            if ($home = get_option('page_for_posts', true)) {
                return get_the_title($home);
            }
            return __('Latest Posts', 'sage');
        }
        if (is_archive()) {
            return get_the_archive_title();
        }
        if (is_search()) {
            return sprintf(__('Search Results for %s', 'sage'), get_search_query());
        }
        if (is_404()) {
            return __('Not Found', 'sage');
        }
        return get_the_title();
    }
}
