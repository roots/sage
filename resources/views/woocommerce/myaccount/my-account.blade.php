{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 2.6.0
--}}
@php
wc_print_notices();
@endphp

<div class="sw-myaccount row">
	@php(do_action( 'woocommerce_account_navigation' ))
	<div class="sw-myaccount__content col-sm-12 col-lg">
		@php(do_action( 'woocommerce_account_content' ))
	</div>
</div>
