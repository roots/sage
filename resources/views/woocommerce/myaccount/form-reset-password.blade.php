{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.3.0
--}}
@php(wc_print_notices())


<div class="sw-reset-pass">

    <p>{{ apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ) }}</p>

<form class="form-row" method="post">
	<div class="col-sm-12 col-md-6">
		<label for="password_1">{{ __( 'New password', 'woocommerce' ) }}<sup>*</sup></label>
		<input type="password" class="form-control" name="password_1" id="password_1" />
	</div>
	<div class="col-sm-12 col-md-6">
		<label for="password_2">{{ __( 'Re-enter new password', 'woocommerce' ) }}<sup>*</sup></label>
		<input type="password" class="form-control" name="password_2" id="password_2" />
	</div>

	<input type="hidden" name="reset_key" value="@php(esc_attr( $args['key'] ))" />
	<input type="hidden" name="reset_login" value="@php(esc_attr( $args['login'] ))" />

	@php(do_action( 'woocommerce_resetpassword_form' ))

	<div class="col-12">
		<input type="hidden" name="wc_reset_password" value="true" />
		<button type="submit" class="btn btn-primary" value="@php(esc_attr_e( 'Save', 'woocommerce' ))">
            {{ __( 'Save', 'woocommerce' ) }}
        </button>
	</div>
</form>
	@php(wp_nonce_field( 'reset_password' ))
</div>
