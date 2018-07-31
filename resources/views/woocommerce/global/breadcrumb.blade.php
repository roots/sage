{{--
@see 	    https://docs.woocommerce.com/document/template-structure/
@author 		WooThemes
@package 	WooCommerce/Templates
@version     2.3.0
@see         woocommerce_breadcrumb()
--}}

@if(!empty($breadcrumb))
    <nav class="sw-breadcrumbs" aria-label="breadcrumb">
        <ol class="breadcrumb">
			<?php foreach ( $breadcrumb as $key => $crumb ): ?>
            @if(! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1)
                <li class="breadcrumb-item">
                    <a href="{{ esc_url( $crumb[1] ) }}">{{ esc_html( $crumb[0] ) }}</a>
                </li>
            @else
                <li class="breadcrumb-item active" aria-current="page">
                    {{ esc_html( $crumb[0] ) }}
                </li>
            @endif
			<?php endforeach; ?>
        </ol>
    </nav>
@endif
