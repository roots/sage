{{--
@see     https://docs.woocommerce.com/document/template-structure/
@author  WooThemes
@package WooCommerce/Templates
@version 3.3.0
--}}
@php
    wc_print_notices();
    $reg_enabled = get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes';
    do_action( 'woocommerce_before_customer_login_form' );
@endphp

<div class="sw-login row">

    <div class="sw-login__login col" id="customer-login">
        <h2>{{__( 'Login', 'woocommerce' )}}</h2>
        <form class="sw-login__form login" method="post">
            @php(do_action( 'woocommerce_login_form_start' ))
            <div class="form-group">
                <label for="username">{{ __( 'Username or email', 'woocommerce' ) }} <sup>*</sup></label>
                <input type="text" class="form-control" name="username" id="username"
                       value="{{ ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''}}"/>
            </div>
            <div class="form-group">
                <label for="password">{{ __( 'Password', 'woocommerce' ) }} <sup>*</sup></label>
                <input class="form-control" type="password" name="password" id="password"/>
            </div>

            @php(do_action( 'woocommerce_login_form' ))

            <div class="form-group form-inline">
                {!! wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ) !!}
                <div class="input-group flex-column">
                    <button type="submit" class="button" name="login"
                            value="@php(esc_attr_e( 'Login', 'woocommerce' ))">
                        {{ __( 'Login', 'woocommerce' ) }}
                    </button>
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
                @php(do_action( 'woocommerce_login_form_end' ))
            </div>
        </form>
    </div>
    @if($reg_enabled)
        <div class="sw-login__register col">
            <h2>{{ __( 'Register', 'woocommerce' ) }}</h2>

            <form method="post" class="register">

                @php(do_action( 'woocommerce_register_form_start' ))

                @if('no' === get_option( 'woocommerce_registration_generate_username' ) ))
                <div class="form-group">
                    <label for="reg_username">{{ __( 'Username', 'woocommerce' ) }}<span
                                class="required">*</span></label>
                    <input type="text" class="form-control" name="username"
                           id="reg_username"
                           value="{{ ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : '' }}"/>
                </div>
                @endif

                <div class="form-group">
                    <label for="reg_email">{{ __( 'Email address', 'woocommerce' ) }}<sup>*</sup></label>
                    <input type="email" class="form-control" name="email"
                           id="reg_email"
                           value="{{ ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : '' }}"/>
                </div>

                @if( 'no' === get_option( 'woocommerce_registration_generate_password' ) )
                    <div class="form-group">
                        <label for="reg_password">{{ __( 'Password', 'woocommerce' ) }}<sup>*</sup></label>
                        <input type="password" class="form-control"
                               name="password" id="reg_password"/>
                    </div>
                @endif

                @php(do_action( 'woocommerce_register_form' ))
                <div class="form-group">
                    @php(wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ))
                    <button type="submit" class="btn btn-primary" name="register"
                            value="@php(esc_attr_e( 'Register', 'woocommerce' ))">{{ __( 'Register', 'woocommerce' ) }}</button>
                </div>
                @php(do_action( 'woocommerce_register_form_end' ))
            </form>
        </div>
    @endif
</div>
@php(do_action( 'woocommerce_after_customer_login_form' ))
