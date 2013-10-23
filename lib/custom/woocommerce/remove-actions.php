<?php
add_filter( 'woocommerce_enqueue_styles', '__return_false' );
remove_action( 'woocommerce_product_tabs', 'woocommerce_product_reviews_tab', 1);
remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_reviews_panel', 1);