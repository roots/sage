<?php


namespace App\Composers\Partials;


use Roots\Acorn\View\Composer;

class ContentPost extends Composer
{
    public function with()
    {
        $id = get_the_ID();

        return [
            'class'     => join(' ', get_post_class($id)),
            'permalink' => get_permalink($id),
            'title'     => $this->title($id),
        ];
    }

    public function title($post_id)
    {
        return sprintf(
            '%s - %s',
            get_the_title($post_id),
            join(' | ', wp_get_post_categories($post_id, ['fields' => 'names']))
        );
    }
}
