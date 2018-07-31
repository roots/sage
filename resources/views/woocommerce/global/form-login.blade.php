@php
    /**
     * Login form
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @see 	    https://docs.woocommerce.com/document/template-structure/
     * @author 		WooThemes
     * @package 	WooCommerce/Templates
     * @version     3.3.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly.
    }

    if ( is_user_logged_in() ) {
        return;
    }
@endphp


<form class="woocommerce-form woocommerce-form-login login"
      method="post" {!! ( $hidden ) ? 'style="display:none;"' : '' !!}>

    @php do_action( 'woocommerce_login_form_start' ) @endphp
    <small>{!! ( $message ) ? wpautop( wptexturize( $message ) ) : '' !!}</small>
    <div class="form-group">
        <label for="username">{{ __( 'Username or email', 'woocommerce' ) }} <span class="required">*</span></label>
        <input type="text" class="form-control input-text" name="username" id="username"/>
    </div>
    <div class="form-group">
        <label for="password">{{ __( 'Password', 'woocommerce' ) }} <span class="required">*</span></label>
        <input class="input-text form-control" type="password" name="password" id="password"/>
    </div>

	@php do_action( 'woocommerce_login_form' ) @endphp
    <div class="form-group form-inline">
        {!! wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ) !!}

        <div class="input-group flex-column">
            <button type="submit" class="button" name="login"
                    value="@php(esc_attr_e( 'Login', 'woocommerce' ))">{{ __( 'Login', 'woocommerce' ) }}</button>
            <input type="hidden" name="redirect" value="{{ esc_url( $redirect ) }}"/>
            <small class="input-group form-check form-check-inline mt-2">
                <input class="form-check-input woocommerce-form__input woocommerce-form__input-checkbox"
                       name="rememberme"
                       type="checkbox"
                       id="rememberme" value="forever"/>
                <label class="woocommerce-form__label woocommerce-form__label-for-checkbox inline form-check-label">
					{{  __( 'Remember me', 'woocommerce' ) }}
                </label>
            </small>
        </div>


        <small class="lost_password text-muded ml-auto">
            <a href="{{ esc_url( wp_lostpassword_url() ) }}">{{  __( 'Lost your password?', 'woocommerce' ) }}</a>
        </small>
    </div>
    @php do_action( 'woocommerce_login_form_end' ) @endphp

</form>
