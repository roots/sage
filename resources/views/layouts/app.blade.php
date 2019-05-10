<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')

  <body @php(body_class())>
    @php(wp_body_open())
    @php(do_action('get_header'))
    @include('partials.header')

    <div class="wrap container">
      <div class="content">
        <main class="main">
          @yield('content')
        </main>

        @hasSection('sidebar')
          <aside class="sidebar">
            @yield('sidebar')
          </aside>
        @endif
      </div>
    </div>

    @php(do_action('get_footer'))
    @include('partials.footer')

    @php(wp_footer())
  </body>
</html>
