<form role="search" method="get" class="search-form" action="{{ $sf_action }}">
  <label>
    <span class="screen-reader-text">{{ $sf_screen_reader_text }}</span>
    <input type="search" class="search-field" placeholder="{!! $sf_placeholder !!}" value="{{ $sf_current_query }}" name="s">
  </label>
  <input type="submit" class="search-submit" value="{{ $sf_submit_text }}">
</form>
