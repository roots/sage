<?php


namespace App\Composers;


use Roots\Acorn\View\Composer;

class TemplateCustom extends Composer
{
    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'title' => $this->title(),
        ];
    }

    public function title()
    {
        return 'Custom Template';
    }
}
