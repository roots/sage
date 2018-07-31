{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 2.6.0
--}}
@php do_action( 'woocommerce_before_account_navigation' ) @endphp
<nav class="sw-myaccount__nav col-sm-12 col-lg-3">
	<ul class="nav flex-column">
		<?php foreach (wc_get_account_menu_items() as $endpoint => $label): ?>
			<li class="nav-item {{ wc_get_account_menu_item_classes( $endpoint ) }}">
				<a href="{{ esc_url( wc_get_account_endpoint_url( $endpoint ) ) }}">{{ esc_html( $label ) }}</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
@php do_action( 'woocommerce_after_account_navigation' ) @endphp
