<?php

namespace App\SageWoo\Modules;

class Actions extends Module {

	const ARCHIVE_PRODUCT_HOOKS = [
		'woocommerce_before_main_content',
		'woocommerce_archive_description',
		'woocommerce_before_shop_loop',
		'woocommerce_shop_loop',
		'woocommerce_after_shop_loop',
		'woocommerce_no_products_found',
		'woocommerce_before_shop_loop_item',
		'woocommerce_before_shop_loop_item_title',
		'woocommerce_shop_loop_item_title',
		'woocommerce_after_shop_loop_item_title',
		'woocommerce_after_shop_loop_item',
	];

	const SINGLE_PRODUCT_HOOKS = [
		'woocommerce_before_main_content',
		'woocommerce_after_main_content',
		'woocommerce_before_single_product_summary',
		'woocommerce_single_product_summary',
		'woocommerce_after_single_product_summary',
	];

	private $config = [

	];

	protected function setDefaultConfig() {
		$this->config = [
			'remove'     => [],
			'add'        => [],
			'remove_all' => [
				'single_product'  => false,
				'archive_product' => false,
			]
		];

		return $this;
	}

	public function __construct( array $user_config ) {
		$this->setDefaultConfig()
		     ->setConfig( $user_config )
		     ->init();
	}

	protected function init() {
		if ( isset( $this->config['remove'] ) && ! empty( $this->config['remove'] ) ) {
			$this->actions( 'remove', $this->config['remove'] );
		}

		if ( isset( $this->config['add'] ) && ! empty( $this->config['add'] ) ) {
			$this->actions( 'add', $this->config['add'] );
		}
		if ( isset( $this->config['remove_all'] ) && ! empty( $this->config['remove_all'] ) ) {
			$remove_all = $this->config['remove_all'];
			foreach ( $remove_all as $action ) {
				$hooks = constant( 'self::' . strtoupper( $action ) . '_HOOKS' );
				$this->removeAllActions( $hooks );
			}
		}
	}

	/**
	 * @param string $job
	 * @param array  $data
	 */
	private function actions( string $job, array $data ) {
		$act = '';
		if ( $job === 'remove' ) {
			$act = 'remove_action';
		} elseif ( $job === 'add' ) {
			$act = 'add_action';
		}

		foreach ( $data as $hook => $actions ) :
			if ( $this->hookValidation( $hook ) === false ) {
				continue;
			}
			if ( is_array( $actions ) ):
				foreach ( $actions as $action => $order ):
					if ( $this->actionValidation( $action, $order ) ) {
						call_user_func_array( $act, [ $hook, $action, $order ] );
					}
					continue;
				endforeach;
			else:
				if ( $actions === 'all' && $job === 'remove' ) {
					remove_all_actions( $hook );
				} else {
					call_user_func_array( $act, [ $hook, $actions ] );
				}
			endif;
		endforeach;
	}

	/**
	 * @param array $hooks
	 */
	private function removeAllActions( array $hooks ) {
		foreach ( $hooks as $hook ) {
			if ( $this->hookValidation( $hook ) === false ) {
				continue;
			}
			remove_all_actions( $hook );
		}
	}

	/**
	 * @param string $hook
	 *
	 * @return bool
	 */
	public function hookValidation( string $hook ) {
		if ( has_action( $hook ) === false ) {
			trigger_error( 'Please check your hook name: ' . $hook, E_USER_WARNING );

			return false;
		}

		return true;
	}

	/**
	 * Just for the type hinting
	 *
	 * @param string $action
	 * @param int    $order
	 *
	 * @return bool
	 */
	public function actionValidation( string $action, int $order ) {
		return true;
	}

	protected function setConfig( array $user_config ) {
		$this->config = $this->parseConfig( $this->config, $user_config );

		return $this;
	}
}