<article @php(post_class('h-entry'))>
  <header>
    <h1 class="p-name">
      {!! $title !!}
    </h1>

    @include('partials.entry-meta')
  </header>

  <div class="e-content">
    @php(the_content())
  </div>

  @php($pagination_links = wp_link_pages([
    'echo' => 0,
    'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'),
    'after' => '</p></nav>'
  ]))

  @if ($pagination_links)
    <footer>
      {!! $pagination_links !!}
    </footer>
  @endif

  @php(comments_template())
</article>
