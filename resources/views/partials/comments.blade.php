@if (! post_password_required())
  <section id="comments" class="comments">
    @if (have_comments())
      <h2>
        {!! /* translators: %1$s is replaced with the number of comments and %2$s with the post title */ sprintf(_nx('%1$s response to &ldquo;%2$s&rdquo;', '%1$s responses to &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'sage'), get_comments_number() === 1 ? _x('One', 'comments title', 'sage') : number_format_i18n(get_comments_number()), '<span>' . get_the_title() . '</span>') !!}
      </h2>

      <ol class="comment-list">
        {!! wp_list_comments(['style' => 'ol', 'short_ping' => true]) !!}
      </ol>

      @if (get_comment_pages_count() > 1 && get_option('page_comments'))
        <nav>
          <ul class="pager">
            @if (get_previous_comments_link())
              <li class="previous">
                {!! get_previous_comments_link(__('&larr; Older comments', 'sage')) !!}
              </li>
            @endif

            @if (get_next_comments_link())
              <li class="next">
                {!! get_next_comments_link(__('Newer comments &rarr;', 'sage')) !!}
              </li>
            @endif
          </ul>
        </nav>
      @endif
    @endif

    @if (! comments_open() && get_comments_number() != '0' && post_type_supports(get_post_type(), 'comments'))
      <x-alert type="warning">
        {!! __('Comments are closed.', 'sage') !!}
      </x-alert>
    @endif

    @php(comment_form())
  </section>
@endif
