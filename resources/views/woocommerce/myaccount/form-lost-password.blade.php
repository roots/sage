{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.3.0
--}}
@php wc_print_notices() @endphp


<div class="sw-lost-pass">
    <p>{{ apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ) }}</p>

    <form method="post" class="form-inline">
        <label class="sr-only" for="user_login">
            {{ __( 'Username or email', 'woocommerce' ) }}
        </label>
        <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login"
               id="user_login"/>


        @php do_action( 'woocommerce_lostpassword_form' ) @endphp

        <input type="hidden" name="wc_reset_password" value="true"/>
        <button type="submit" class="btn btn-primary"
                value="@php(esc_attr_e( 'Reset password', 'woocommerce' ))">{{ __( 'Reset password', 'woocommerce' ) }}</button>


        @php wp_nonce_field( 'lost_password' ) @endphp

    </form>
</div>