<article class="{{ $class }}">
  <header>
    <h2 class="entry-title">
      <a href="{{ $permalink }}">
        {!! $title !!}
      </a>
    </h2>

    @include('partials/entry-meta')
  </header>

  <div class="entry-summary">
    @php(the_excerpt())
  </div>
</article>
