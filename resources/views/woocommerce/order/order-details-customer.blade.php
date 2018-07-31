{{--
@see 	https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.3.0
--}}
@php
    $show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
@endphp

<section class="sw-myaccount__order__customer row">

    <div class="col">
        <h2 class="woocommerce-column__title"><?php _e( 'Billing address', 'woocommerce' ); ?></h2>

        <address>
          {!! wp_kses_post( $order->get_formatted_billing_address( __( 'N/A', 'woocommerce' ) ) ) !!}

            @if( $order->get_billing_phone())
                <p>{{  $order->get_billing_phone() }}</p>
            @endif

            @if( $order->get_billing_email() )
                <p>{{ $order->get_billing_email() }}</p>
            @endif
        </address>
    </div>
    @if( $show_shipping )
        <div class="col">
            <h2>{{ __( 'Shipping address', 'woocommerce' ) }}</h2>
            <address>
               {!! wp_kses_post( $order->get_formatted_shipping_address( __( 'N/A', 'woocommerce' ) ) )  !!}
            </address>
        </div>
    @endif

</section>
