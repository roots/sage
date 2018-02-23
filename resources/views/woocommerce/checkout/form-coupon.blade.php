{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.3.0
--}}
@php
	if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    if ( ! wc_coupons_enabled() ) {
        return;
    }

    if ( empty( WC()->cart->applied_coupons ) ) {
        $info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' );
        wc_print_notice( $info_message, 'notice' );
    }
@endphp

<form class="checkout_coupon form-inline" method="post" style="display:none">

		<input type="text" name="coupon_code" class="form-control" placeholder="@php(esc_attr_e( 'Coupon code', 'woocommerce' ))" id="coupon_code" value="" />

		<button type="submit" class="button" name="apply_coupon" value="@php(esc_attr_e( 'Apply coupon', 'woocommerce' ))">{{ __( 'Apply coupon', 'woocommerce' ) }}</button>
</form>
