<?php

namespace App\Composers;

use Roots\Acorn\View\Composer;

class Alert extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'components.alert'
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
        return [
            'type' => $data['type'] ?? 'primary',
        ];
    }
}
