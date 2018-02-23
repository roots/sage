<?php

namespace App;

if (class_exists('woocommerce')) {
	add_action('woocommerce_init', ['App\SageWoo\Bootstrap', 'get_instance']);
}