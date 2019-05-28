<!doctype html>
<html {!! get_language_attributes() !!}>
  @include('partials.head')

  <body @php(body_class())>
    @php(wp_body_open())
    @php(do_action('get_header'))
    @include('partials.header')

    <div class="container">
      <main>
        @yield('content')
      </main>

      @hasSection('sidebar')
        <aside>
          @yield('sidebar')
        </aside>
      @endif
    </div>

    @php(do_action('get_footer'))
    @include('partials.footer')

    @php(wp_footer())
  </body>
</html>
