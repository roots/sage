{{--
@see 	    https://docs.woocommerce.com/document/template-structure/
@author 		WooThemes
@package 	WooCommerce/Templates
@version     3.0.0
--}}
@php
    do_action( 'woocommerce_before_single_product' );
    if ( post_password_required() ) {
        echo get_the_password_form();
        return;
    }
@endphp
<div id="product-@php the_ID() @endphp"
        @php post_class('sw-single-product row') @endphp>
    <div class="sw-single-product__images col-sm-6">
        @php do_action( 'woocommerce_before_single_product_summary' ) @endphp
    </div>
    <div class="sw-single-product__summary col-sm-6">
        @php do_action( 'woocommerce_single_product_summary' ) @endphp
    </div>
    <div class="sw-single-product__after-summary col-sm-12">
       @php do_action( 'woocommerce_after_single_product_summary' ) @endphp
    </div>
</div>
@php do_action( 'woocommerce_after_single_product' ) @endphp
