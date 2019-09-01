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
     * @return array
     */
    public function with()
    {
        return [
            'type' => $this->type(),
        ];
    }

    /**
     * Returns the alert type.
     *
     * @return string
     */
    public function type()
    {
        return $this->view->getData()['type'] ?? 'primary';
    }
}
