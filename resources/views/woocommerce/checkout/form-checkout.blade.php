{{--
@see 	    https://docs.woocommerce.com/document/template-structure/
@author 		WooThemes
@package 	WooCommerce/Templates
@version     2.3.0
--}}

@php
    wc_print_notices();
    do_action( 'woocommerce_before_checkout_form', $checkout );
    // If checkout registration is disabled and not logged in, the user cannot checkout
    if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
        echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
        return;
}
@endphp

<form name="checkout" method="post" class="sw-checkout row checkout woocommerce-checkout"
      action="{{ esc_url( wc_get_checkout_url() ) }}" enctype="multipart/form-data">

    @if( $checkout->get_checkout_fields())
        @php do_action( 'woocommerce_checkout_before_customer_details' ) @endphp

        <div class="sw-checkout__customer-details col-xs-12 col-md-7" id="customer_details">
            @php do_action( 'woocommerce_checkout_billing' ) @endphp
            @php do_action( 'woocommerce_checkout_shipping' ) @endphp
        </div>
        @php do_action( 'woocommerce_checkout_after_customer_details' ) @endphp

    @endif
    @php do_action( 'woocommerce_checkout_before_order_review' ) @endphp
    <div class="sw-checkout__order-review col-xs-12 col-md-5" id="order_review">
        <h3 id="order_review_heading">{{ __( 'Your order', 'woocommerce' ) }}</h3>
        @php do_action( 'woocommerce_checkout_order_review' ) @endphp
    </div>
    @php do_action( 'woocommerce_checkout_after_order_review' ) @endphp
</form>

@php do_action( 'woocommerce_after_checkout_form', $checkout ) @endphp