<!doctype html>
<html @php(language_attributes())>
  @include('partials.head')

  <body @php(body_class())>
    <div id="app" role="document">
      @php(do_action('get_header'))
      @include('partials.header')

      <div class="wrap container">
        <div class="content">
          <main class="main" role="main">
            @yield('content')
          </main>

          @hasSection('sidebar')
            <aside class="sidebar" role="complementary">
              @yield('sidebar')
            </aside>
          @endif
        </div>
      </div>

      @php(do_action('get_footer'))
      @include('partials.footer')
    </div>

    @php(wp_footer())
  </body>
</html>
