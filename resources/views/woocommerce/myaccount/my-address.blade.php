{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 2.6.0
--}}

@php
	$customer_id = get_current_user_id();

    if ( ! wc_ship_to_billing_address_only() && wc_shipping_enabled() ) {
        $get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
            'billing' => __( 'Billing address', 'woocommerce' ),
            'shipping' => __( 'Shipping address', 'woocommerce' ),
        ), $customer_id );
    } else {
        $get_addresses = apply_filters( 'woocommerce_my_account_get_addresses', array(
            'billing' => __( 'Billing address', 'woocommerce' ),
        ), $customer_id );
    }

    $oldcol = 1;
    $col    = 1;
@endphp

<p>
	{{ apply_filters( 'woocommerce_my_account_my_address_description', __( 'The following addresses will be used on the checkout page by default.', 'woocommerce' ) ) }}
</p>
<div class="sw-myaccount__addresses row">
<?php foreach ( $get_addresses as $name => $title ) : ?>

	<div class="mb-4 col">
		<header>
			<h3>{{ $title }}</h3>
		</header>
		<address class="mb-2">
			@php
			$address = wc_get_account_formatted_address( $name );
			echo $address ? wp_kses_post( $address ) : esc_html_e( 'You have not set up this type of address yet.', 'woocommerce' );
		@endphp
		</address>
		<a href="{{ esc_url( wc_get_endpoint_url( 'edit-address', $name ) ) }}" class="btn btn-sm btn-primary edit">{{ __( 'Edit', 'woocommerce' ) }}</a>
	</div>
<?php endforeach; ?>
</div>

