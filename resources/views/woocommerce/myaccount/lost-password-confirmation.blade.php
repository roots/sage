{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 2.6.0
--}}
@php
wc_print_notices();
wc_print_notice( __( 'Password reset email has been sent.', 'woocommerce' ) );
@endphp

<div class="alert-warning alert" role="alert">
	{{ apply_filters( 'woocommerce_lost_password_message', __( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'woocommerce' ) ) }}
</div>
