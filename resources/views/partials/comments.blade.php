@if (!$password_required)

<section id="comments" class="comments">
  @if ($has_comments)
    <h2>
      {!! $comments_title !!}
    </h2>

    <ol class="comment-list">
      {!! $comments_list !!}
    </ol>

    @if ($show_comments)
      <nav>
        <ul class="pager">
          @if ($previous_comments_link)
            <li class="previous">{!! $previous_comments_link !!}</li>
          @endif
          @if ($next_comments_link)
            <li class="next">{!! $next_comments_link !!}</li>
          @endif
        </ul>
      </nav>
    @endif
  @endif

  @if ($comments_closed)
    <div class="alert alert-warning">
      {{ __('Comments are closed.', 'sage') }}
    </div>
  @endif

  @php comment_form() @endphp
</section>

@endif
