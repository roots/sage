<header class="banner">
  <a class="brand" href="{{ home_url('/') }}">
    {{ $siteName }}
  </a>

  <nav class="nav-primary">
    @if (has_nav_menu('primary_navigation'))
      {!! wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
    @endif
  </nav>
</header>
