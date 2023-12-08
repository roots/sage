<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;

class Comments extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.comments',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'title' => $this->title(),
            'comments' => $this->comments(),
            'next' => $this->next(),
            'previous' => $this->previous(),
            'paginated' => $this->paginated(),
            'closed' => $this->closed(),
        ];
    }

    /**
     * The comment title.
     *
     * @return string
     */
    public function title()
    {
        /* translators: %1$s is replaced with the number of comments and %2$s with the post title */
        return sprintf(
            _nx('%1$s response to &ldquo;%2$s&rdquo;', '%1$s responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'sage'),
            get_comments_number() === 1 ? _x('One', 'comments title', 'sage') : number_format_i18n(get_comments_number()),
            '<span>'.get_the_title().'</span>'
        );
    }

    /**
     * Retrieve the comments.
     *
     * @return string
     */
    public function comments()
    {
        if (! have_comments()) {
            return;
        }

        return wp_list_comments([
            'style' => 'ol',
            'short_ping' => true,
        ]);
    }

    /**
     * The next comments link.
     *
     * @return string
     */
    public function next()
    {
        if (! get_next_comments_link()) {
            return;
        }

        return get_next_comments_link(
            __('Newer comments &rarr;', 'sage')
        );
    }

    /**
     * The previous comments link.
     *
     * @return string
     */
    public function previous()
    {
        if (! get_previous_comments_link()) {
            return;
        }

        return get_previous_comments_link(
            __('&larr; Older comments', 'sage')
        );
    }

    /**
     * Determine if the comments are paginated.
     *
     * @return bool
     */
    public function paginated()
    {
        return get_comment_pages_count() > 1 && get_option('page_comments');
    }

    /**
     * Determine if the comments are closed.
     *
     * @return bool
     */
    public function closed()
    {
        return ! comments_open() && get_comments_number() != '0' && post_type_supports(get_post_type(), 'comments');
    }
}
