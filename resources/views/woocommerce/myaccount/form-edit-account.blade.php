{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.3.0
--}}
@php do_action( 'woocommerce_before_edit_account_form' ) @endphp

<form class="sw-myaccount__edit-account form-row" action="" method="post">

	@php do_action( 'woocommerce_edit_account_form_start' ) @endphp
	<div class="form-group col-6">
		<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?> <sup>*</sup></label>
		<input type="text" class="form-control" name="account_first_name" id="account_first_name" value="<?php echo esc_attr( $user->first_name ); ?>" />
	</div>
	<div class="form-group col-6">
		<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?> <sup>*</sup></label>
		<input type="text" class="form-control" name="account_last_name" id="account_last_name" value="<?php echo esc_attr( $user->last_name ); ?>" />
	</div>
	<div class="form-group col-12">
		<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?> <sup>*</sup></label>
		<input type="email" class="form-control" name="account_email" id="account_email" value="<?php echo esc_attr( $user->user_email ); ?>" />
	</div>

	<fieldset class="col-12">
		<legend>{{ __( 'Password change', 'woocommerce' ) }}</legend>
		<div class="form-group">
			<label for="password_current">{{ __( 'Current password (leave blank to leave unchanged)', 'woocommerce' ) }}</label>
			<input type="password" class="form-control" name="password_current" id="password_current" />
		</div>
		<div class="form-group">
			<label for="password_1">{{ __( 'New password (leave blank to leave unchanged)', 'woocommerce' ) }}</label>
			<input type="password" class="form-control" name="password_1" id="password_1" />
		</div>
		<p class="form-group">
			<label for="password_2">{{ __( 'Confirm new password', 'woocommerce' ) }}</label>
			<input type="password" class="form-control" name="password_2" id="password_2" />
		</p>
	</fieldset>
	@php do_action( 'woocommerce_edit_account_form' ) @endphp
	<div class="col-12">
		@php wp_nonce_field( 'save_account_details' ) @endphp
		<button type="submit" class="btn btn-primary" name="save_account_details" value="@php(esc_attr_e( 'Save changes', 'woocommerce' ))">{{ __( 'Save changes', 'woocommerce' ) }}</button>
		<input type="hidden" name="action" value="save_account_details" />
	</div>
	@php  do_action( 'woocommerce_edit_account_form_end' ) @endphp
</form>

@php do_action( 'woocommerce_after_edit_account_form' ) @endphp
