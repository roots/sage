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
    protected static $views = ['components.alert'];

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
            'color' => $this->getColorClassNames(key_exists('type', $data) ? $data['type'] : null),
        ];
    }

    /**
     * Get the alert color class names based on its type
     *
     * @param string $type
     * @return string
     */
    public function getColorClassNames($type = '')
    {
        switch ($type) {
            case 'success':
                return 'bg-green-200 text-green-800 border-green-500';
            case 'warning':
                return 'bg-yellow-200 text-yellow-800 border-yellow-500';
            case 'error':
                return 'bg-red-200 text-red-700 border-red-500';
            default:
                return 'bg-indigo-200 text-indigo-800 border-indigo-500';
        }
    }
}
