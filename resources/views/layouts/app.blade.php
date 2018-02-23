<!doctype html>
<html @php(language_attributes())>
@include('partials.head')
<body @php(body_class())>
@php(do_action('get_header'))
@include('partials.header')
<div class="wrap container" role="document">
    <div class="content">
        <main class="main">
            @if(function_exists('is_woocommerce') && is_woocommerce())
                <div class="woocommerce">
                    @yield('content')
                </div>
            @else
                @yield('content')
            @endif
        </main>
        @if (App\display_sidebar())
            <aside class="sidebar">
                @include('partials.sidebar')
            </aside>
        @endif
    </div>
</div>
@php(do_action('get_footer'))
@include('partials.footer')
@php(wp_footer())
</body>
</html>
