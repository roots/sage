@extends('layouts.app')

@section('content')
    @php do_action('woocommerce_before_main_content') @endphp
    @php do_action('woocommerce_archive_description') @endphp
    @if(have_posts())
        @php do_action('woocommerce_before_shop_loop') @endphp
        @if(wc_get_loop_prop('total'))
            <div class="sw-product-archive row">
                @while(have_posts()) @php the_post() @endphp
                @php do_action('woocommerce_shop_loop') @endphp
                @include('woocommerce.content-product')
                @endwhile
            </div>
        @endif
        @php do_action( 'woocommerce_after_shop_loop' ) @endphp
    @else
        @php do_action( 'woocommerce_no_products_found' ) @endphp
    @endif
    @php do_action( 'woocommerce_after_main_content' ) @endphp
@endsection