{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.3.0
--}}
@php
    $page_title = ( 'billing' === $load_address ) ? __( 'Billing address', 'woocommerce' ) : __( 'Shipping address', 'woocommerce' );
    do_action( 'woocommerce_before_edit_account_address_form' )
@endphp

@if( ! $load_address )
    @php wc_get_template( 'myaccount/my-address.php' ) @endphp
@endif

<h3>{{ apply_filters( 'woocommerce_my_account_edit_address_title', $page_title, $load_address ) }}</h3>
<form method="post">

    <div class="sw-myaccount__edit-address">
        @php do_action( "woocommerce_before_edit_address_form_{$load_address}" ) @endphp

        <div class="form-row">
			@php \App\alterWooFields('myaccount-address', $address) @endphp
        </div>

        @php do_action( "woocommerce_after_edit_address_form_{$load_address}" ) @endphp

        <button type="submit" class="btn btn-primary" name="save_address"
                value="@php(esc_attr_e( 'Save address', 'woocommerce' ))">{{ __( 'Save address', 'woocommerce' ) }}</button>
        @php wp_nonce_field( 'woocommerce-edit_address' ) @endphp
        <input type="hidden" name="action" value="edit_address"/>
    </div>

</form>
@php  do_action( 'woocommerce_after_edit_account_address_form' ) @endphp