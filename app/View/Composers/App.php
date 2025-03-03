<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class App extends Composer
{
    /**
     * List of views served by this composer.
     */
    protected static array $views = [
        '*',
    ];

    /**
     * Data to be passed to view before rendering.
     */
    public function with(): array
    {
        return [
            'siteName' => $this->siteName(),
        ];
    }

    /**
     * Returns the site name.
     */
    public function siteName(): string
    {
        return get_bloginfo('name', 'display');
    }
}
