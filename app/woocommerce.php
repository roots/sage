<?php

namespace App;

if (class_exists('woocommerce')  && !is_admin()) {
	add_action('woocommerce_init', ['App\SageWoo\Bootstrap', 'get_instance']);
}
