{{--
@see 	    https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.1.0
--}}

@php
wc_print_notices();
@endphp
<div class="sw-cart__empty flex-column">

	@php(do_action( 'woocommerce_cart_is_empty' );)

	@if ( wc_get_page_id( 'shop' ) > 0 )
		<p class="return-to-shop">
			<a class="button wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
				<?php _e( 'Return to shop', 'woocommerce' ) ?>
			</a>
		</p>
	@endif
</div>
