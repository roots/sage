<!doctype html>
<html {!! language_attributes() !!}>
  @include('partials.head')
  <body {!! body_class() !!}>
    <!--[if IE]>
      <div class="alert alert-warning">
        {!! _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'sage') !!}
      </div>
    <![endif]-->
    {!! do_action('get_header') !!}
    @include('partials.header')
    <div class="wrap container" role="document">
      <div class="content row">
        <main class="main">
          @yield('content')
        </main>
        {{--@if(App\display_sidebar())--}}
          {{--<aside class="sidebar">--}}
            {{--{!! App\template_part('partials/sidebar') !!}--}}
          {{--</aside>--}}
        {{--@endif--}}
      </div>
    </div>
    {!! do_action('get_footer') !!}
    @include('partials.footer')
    {!! wp_footer() !!}
  </body>
</html>
