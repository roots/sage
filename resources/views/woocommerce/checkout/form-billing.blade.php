{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.0.9

 @global WC_Checkout $checkout
--}}
<div class="sw-billing-fields">
    @if( wc_ship_to_billing_address_only() && WC()->cart->needs_shipping() )
        <h3 class="sw-billing-fields__heading">{{ __( 'Billing &amp; Shipping', 'woocommerce' ) }}</h3>
    @else
        <h3 class="sw-billing-fields__heading">{{ __( 'Billing details', 'woocommerce' ) }}</h3>
    @endif


    <div class="form-row sw-billing-fields__wrapper woocommerce-billing-fields__field-wrapper">
        @php( \App\alterWooFields('billing'))
    </div>

    @php(do_action( 'woocommerce_after_checkout_billing_form', $checkout ))
</div>

@if(! is_user_logged_in() && $checkout->is_registration_enabled() )
    <div class="sw-account-fields">
        @if(! $checkout->is_registration_required())

            <p class="form-check-inline">
                <label class="form-check-label">
                    <span>{{ __( 'Create an account?', 'woocommerce' ) }}</span>
                </label>
		<input class="form-check-input"
                       id="createaccount"
                       @php( checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ))
                       type="checkbox" name="createaccount" value="1"/>

            </p>
        @endif

        @php(do_action( 'woocommerce_before_checkout_registration_form', $checkout ))

        @if($checkout->get_checkout_fields( 'account' ))
                <div class="create-account">
                    @php(\App\alterWooFields('account'))
                </div>
        @endif

        @php(do_action( 'woocommerce_after_checkout_registration_form', $checkout ))
    </div>
@endif
