<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')

  <body @php(body_class())>
    <div id="app">
      @php(wp_body_open())
      @php(do_action('get_header'))
      @include('partials.header')

      <div class="container">
        <main class="main">
          @yield('content')
        </main>

        @hasSection('sidebar')
          <aside class="sidebar">
            @yield('sidebar')
          </aside>
        @endif
      </div>

      @php(do_action('get_footer'))
      @include('partials.footer')
    </div>

    @php(wp_footer())
  </body>
</html>
