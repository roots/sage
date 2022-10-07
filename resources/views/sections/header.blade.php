<header class="banner">
  <a class="brand" href="{{ home_url('/') }}">
    {!! $siteName !!}
  </a>

  @if (has_nav_menu('utility_navigation'))
    {!! wp_nav_menu($utilityMenu) !!}
  @endif

  @if (has_nav_menu('primary_navigation'))
    {!! wp_nav_menu($primaryMenu) !!}
  @endif
</header>
