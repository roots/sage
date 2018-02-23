<?php

namespace App\SageWoo\Modules;

class Misc extends Module {
	const WOO_SCRIPTS = [
		'wc_price_slider',
		'wc-single-product',
		'wc-add-to-cart',
		'wc-cart-fragments',
		'wc-checkout',
		'wc-add-to-cart-variation',
		'wc-single-product',
		'wc-cart',
		'wc-chosen',
		'woocommerce',
		'prettyPhoto',
		'prettyPhoto-init',
		'jquery-blockui',
		'jquery-placeholder',
		'fancybox',
		'jqueryui',
		'selectWoo'
	];

	protected $config = [];

	/**
	 * @return $this
	 */
	protected function setDefaultConfig() {
		$this->config = [
			'remove_woo_styles'  => false,
			'remove_woo_scripts' => '',
			'analytics'          => '',
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
		if ( array_key_exists( 'remove_woo_styles', $this->config ) ) {
			$this->removeWooStyles( $this->config['remove_woo_styles'] );
		}

		if ( array_key_exists( 'remove_woo_scripts', $this->config ) ) {
			$this->removeWooScripts( $this->config['remove_woo_scripts'] );
		}

		if (array_key_exists('analytics', $this->config)) {
			$this->setAnalytics($this->config['analytics']);
		}
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

	protected function setAnalytics( string $id ) {
		add_action( 'wp_footer', function () use ( $id ) {
			?>
			<script>
              window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
              ga('create','<?= $id ?>','auto');ga('send','pageview')
			</script>
			<?php
		}, 999.7 );

		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_script('google-analytics', 'https://www.google-analytics.com/analytics.js', [], false, true);
		});

		add_filter('script_loader_tag', function($tag, $handle) {
			if ($handle === 'google-analytics') {
				return str_replace('src', 'async defer src', $tag);
			}
			return $tag;
		}, 10, 2);
	}

	/**
	 * @param bool $val
	 */
	protected function removeWooStyles( bool $val ) {
		if ( $val ) {
			add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		}
	}

	/**
	 * @param $scripts
	 */
	protected function removeWooScripts( $scripts ) {
		add_action( 'wp_enqueue_scripts', function () use ( $scripts ) {
			$all        = self::WOO_SCRIPTS;
			$removables = [];
			if ( is_string( $scripts ) && $scripts === 'all' ) {
				$removables = $all;
			}
			if ( is_array( $scripts ) && ! empty( $scripts ) ) {
				foreach ( $scripts as $script ) {
					if ( in_array( $script, $all ) ) {
						array_push( $removables, $script );
					}
				}
			}
			foreach ( $removables as $removable ) {
				if ( $removable === 'selectWoo' ) {
					wp_dequeue_style( $removable );
					wp_deregister_style( $removable );

					wp_dequeue_script( $removable );
					wp_deregister_script( $removable );
				} else {
					wp_dequeue_script( $removable );
				}
			}
		}, 99 );
	}
}