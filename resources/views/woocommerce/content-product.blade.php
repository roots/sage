@php
    global $product
@endphp

@if(empty($product) || ! $product->is_visible())
    return
@endif
{{-- You should change this to your markup. I usually work with divs --}}
<div {{ post_class('sw-product-archive__product col-sm-12 col-md-6 col-lg-3') }}>
    @php(do_action( 'woocommerce_before_shop_loop_item' ))
    @php(do_action( 'woocommerce_before_shop_loop_item_title' ))
    @php(do_action( 'woocommerce_shop_loop_item_title' ))
    @php(do_action( 'woocommerce_after_shop_loop_item_title' ))
    @php(do_action( 'woocommerce_after_shop_loop_item' ))
</div>