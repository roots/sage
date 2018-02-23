{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.0.9
--}}

<div class="sw-shipping-fields">
    @if( true === WC()->cart->needs_shipping_address() )
		<div id="ship-to-different-address" class="form-check form-check-inline">
			<label class="form-check-label" for="ship_to_different_address">
				<span>{{ __( 'Ship to a different address?', 'woocommerce' ) }}</span>
			</label>
			<input id="ship-to-different-address-checkbox"
				   class="form-check-input"
				   @php(checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 )) type="checkbox"
				   name="ship_to_different_address" value="1"/>
		</div>

		<div class="shipping_address">

			@php(do_action( 'woocommerce_before_checkout_shipping_form', $checkout ))

			<div class="form-row mt-2">
				@php(\App\alterWooFields('shipping'))
			</div>

			@php(do_action( 'woocommerce_after_checkout_shipping_form', $checkout ))

		</div>
    @endif
</div>
<div class="sw-additional-fields mt-3">
	@php(do_action( 'woocommerce_before_order_notes', $checkout ))

	@if( apply_filters( 'woocommerce_enable_order_notes_field', 'yes' === get_option( 'woocommerce_enable_order_comments', 'yes' ) ) )
	@if(( ! WC()->cart->needs_shipping() || wc_ship_to_billing_address_only() ))

   <h3>{{ __( 'Additional information', 'woocommerce' ) }}</h3>

	@endif
		@php(\App\alterWooFields('order'))
	@endif

	@php(do_action( 'woocommerce_after_order_notes', $checkout ))
</div>
