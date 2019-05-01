<form role="search" method="get" class="search-form" action="{{ home_url('/') }}">
  <label>
    <span class="screen-reader-text">{{ __('Search for:', 'sage') }}</span>
    <input type="search" class="search-field" placeholder="{!! __('Search &hellip;', 'sage') !!}" value="{{ get_search_query() }}" name="s">
  </label>

  <input type="submit" class="search-submit" value="{{ __('Search', 'sage') }}">
</form>
