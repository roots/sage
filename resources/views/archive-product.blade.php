@extends('layouts.app')

@section('content')
    @php(do_action('woocommerce_before_main_content'))
    @php(do_action('woocommerce_archive_description'))
    @if(have_posts())
        @php(do_action('woocommerce_before_shop_loop'))
        @if(wc_get_loop_prop('total'))
            <div class="sw-product-archive row">
                @while(have_posts()) @php(the_post())
                @php(do_action('woocommerce_shop_loop'))
                @include('woocommerce.content-product')
                @endwhile
            </div>
        @endif
        @php(do_action( 'woocommerce_after_shop_loop' ))
    @else
        @php(do_action( 'woocommerce_no_products_found' ))
    @endif
    @php(do_action( 'woocommerce_after_main_content' ))
@endsection