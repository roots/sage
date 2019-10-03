<?php

namespace App\Composers\Components;

use Roots\Acorn\View\Composer;

class Alert extends Composer
{
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
        return $this->data->get('type', 'primary');
    }
}
