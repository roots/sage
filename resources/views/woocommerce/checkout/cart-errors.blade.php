{{--
@see 	    https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 2.4.0
--}}
@php wc_print_notices() @endphp

<div class="alert alert-danger">{{ __( 'There are some issues with the items in your cart (shown above). Please go back to the cart page and resolve these issues before checking out.', 'woocommerce' ) }}</div>

<?php do_action( 'woocommerce_cart_has_errors' ); ?>

<p><a class="btn btn-large button wc-backward" href="{{ __esc_url( wc_get_page_permalink( 'cart' ) ) }}">{{ __( 'Return to cart', 'woocommerce' ) }}</a></p>
