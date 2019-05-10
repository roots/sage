<?php

namespace App\Composers;

use Roots\Acorn\View\Composer;

class Title extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.page-header',
        'partials.content',
        'partials.content-*'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @param  array $data
     * @param  \Illuminate\View\View $view
     * @return array
     */
    public function with($data, $view)
    {
        return ['title' => $this->title($view->getName())];
    }

    /**
     * Returns the post title.
     *
     * @param  \Illuminate\View\View $view
     * @return string
     */
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
