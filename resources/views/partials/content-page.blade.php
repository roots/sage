<section class="wp-editor">
  <div class="entry-content">
    @php the_content() @endphp
  </div>
  {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
</section>