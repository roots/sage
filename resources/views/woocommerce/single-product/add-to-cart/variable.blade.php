@php
    /*
     * @see 	https://docs.woocommerce.com/document/template-structure/
     * @author  WooThemes
     * @package WooCommerce/Templates
     * @version 3.0.0
     */
    global $product;

    $attribute_keys = array_keys( $attributes );

    do_action( 'woocommerce_before_add_to_cart_form' );
@endphp

<form class="sw-variations variations_form cart" action="{{ esc_url( get_permalink() ) }}" method="post"
      enctype='multipart/form-data'
      data-product_id="{{ absint( $product->get_id()) }}"
      data-product_variations="{!! htmlspecialchars( wp_json_encode( $available_variations ) ) !!}">
    @php do_action( 'woocommerce_before_variations_form' ) @endphp
    @if( empty( $available_variations ) && false !== $available_variations )
        <p class="stock out-of-stock"><em>{{ __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) }}</em></p>
    @else
        <div class="sw-variations__groups variations">
            {{-- Blade is not used here because of the variables--}}
            <?php foreach($attributes as $attribute_name => $options) : ?>

                <div class="sw-variations__group form-group">
                    <label
                            for="{{ sanitize_title( $attribute_name ) }}">{{ wc_attribute_label( $attribute_name ) }}</label>
                    @php
                        $selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
                        wc_dropdown_variation_attribute_options( array( 'options'   => $options,
                                                                        'attribute' => $attribute_name,
                                                                        'product'   => $product,
                                                                        'selected'  => $selected
                        ) );
                        echo end( $attribute_keys ) === $attribute_name ? apply_filters( 'woocommerce_reset_variations_link', '<a class="mt-2 text-muted reset_variations" href="#"><small>' . esc_html__( 'Clear', 'woocommerce' ) . '</small></a>' ) : '';
                    @endphp
                </div>

            <?php endforeach; ?>
        </div>

        @php do_action( 'woocommerce_before_add_to_cart_button' ) @endphp

        <div class="sw-variations__add-to-cart mt-1 single_variation_wrap">
            @php
                /**
                 * woocommerce_before_single_variation Hook.
                 */
                do_action( 'woocommerce_before_single_variation' );

                /**
                 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
                 * @since 2.4.0
                 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
                 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
                 */
                do_action( 'woocommerce_single_variation' );

                /**
                 * woocommerce_after_single_variation Hook.
                 */
                do_action( 'woocommerce_after_single_variation' );
            @endphp
        </div>

        @php do_action( 'woocommerce_after_add_to_cart_button' ) @endphp
    @endif

    @php do_action( 'woocommerce_after_variations_form' ) @endphp
</form>

@php do_action( 'woocommerce_after_add_to_cart_form' ) @endphp

