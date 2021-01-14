<form role="search" method="get" class="search-form" action="{{ home_url('/') }}">
  <label>
    <span class="sr-only">
      {{ _x('Search for:', 'label', 'sage') }}
    </span>

    <input
      type="search"
      class="px-3 py-1 border"
      placeholder="{!! esc_attr_x('Search &hellip;', 'placeholder', 'sage') !!}"
      value="{{ get_search_query() }}"
      name="s"
    >
  </label>

  <input
    type="submit"
    class="px-3 py-1 text-white bg-indigo-500 cursor-pointer"
    value="{{ esc_attr_x('Search', 'submit button', 'sage') }}"
  >
</form>
