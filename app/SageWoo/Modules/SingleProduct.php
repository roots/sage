<?php

namespace App\SageWoo\Modules;

use function App\template;
use function App\asset_path;

class SingleProduct extends Module {

	protected $config;

	protected function setDefaultConfig() {
		$this->config = [
			'radio_variations' => [],
		];

		return $this;
	}

	/**
	 * General constructor.
	 *
	 * @param array $user_config
	 */
	public function __construct( array $user_config ) {
		$this->setDefaultConfig()
		     ->setConfig( $user_config )
		     ->init();
	}

	protected function init() {
		if ( array_key_exists( 'radio_variations', $this->config ) && !empty($this->config['radio_variations']) ) {
			if (!(wc_get_product() && wc_get_product()->is_type('bundle'))) {
				$this->radioVariations( $this->config['radio_variations'] );
			}
		}

	}

	protected function radioVariations( array $attributes) {

		add_action('wp_enqueue_scripts', function () {
			wp_deregister_script( 'wc-add-to-cart-variation' );
			wp_register_script( 'wc-add-to-cart-variation', asset_path('scripts/add-to-cart-variation.js'), array( 'jquery', 'wp-util' ) );
		}, 99);

		add_filter( 'woocommerce_dropdown_variation_attribute_options_html',
			function ( $html ) use ( $attributes ) {
			$dom = new \DOMDocument();
			$dom->loadHTML( $html );
			$select   = $dom->getElementsByTagName( 'select' );
			$options  = $dom->getElementsByTagName( 'option' );
			$tax_name = '';
			$attrs    = [];
			if ( ! $select && ! $options ) {
				return '';
			}

			$tax_name = $select->item( 0 )->getAttribute( 'name' );
			$nice_name = str_replace('attribute_pa_', '', $tax_name);

			if (!in_array($nice_name, $attributes)) {
				return $html;
			}

			foreach ( $options as $option ) {
				$attrs[] = [
					'slug' => $option->getAttribute( 'value' ),
					'name' => $option->nodeValue,
					'default' => $option->getAttribute('selected') === 'selected' ? true : false
				];
			}
			$radio = template( 'woocommerce.sw-components.woo-radio', [ 'attrs' => $attrs, 'tax_name' => $tax_name ] );

			return $radio;
		} );


	}

	/**
	 * @param array $user_config
	 *
	 * @return $this
	 */
	protected function setConfig( array $user_config ) {
		$this->config = $this->parseConfig( $this->config, $user_config );

		return $this;
	}
}