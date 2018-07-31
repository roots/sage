<?php

namespace App\SageWoo;
use App\SageWoo\Modules\Misc;
use App\SageWoo\Modules\Actions;
use App\SageWoo\Modules\SingleProduct;
use function App\filter_templates;
use function App\locate_template;
use function App\template_path;

class Bootstrap {
	public $config;
	public $misc;
	public $actions;

	private static $instance;

	public static function get_instance()
	{
		if (null === self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->wooSupport();
		if (Config::configExists()) {
			$this->config = new Config();
			$this->initModules();
		}
	}

	private function initModules() {
		$modules = $this->config->enabledModules();
		$config = $this->config->getConfig();
		if (in_array('misc', $modules)) {
			$this->misc = new Misc($config['misc']);
		}
		if (in_array('actions', $modules)) {
			$this->actions = new Actions($config['actions']);
		}
		if (in_array('single_product', $modules)) {
			$this->actions = new SingleProduct($config['single_product']);
		}
	}


	private function wooSupport() {

		add_filter( 'woocommerce_template_loader_files', function ( $search_files, $default_file ) {
			return filter_templates( array_merge( $search_files, [ $default_file, 'woocommerce' ] ) );
		}, 100, 2 );

		add_filter( 'woocommerce_locate_template', function ( $template, $template_name, $template_path ) {
			$theme_template = locate_template( "{$template_path}{$template_name}" );

			return $theme_template ? template_path( $theme_template ) : $template;
		}, 100, 3 );

		add_filter( 'wc_get_template_part', function ( $template, $slug, $name ) {
			$theme_template = locate_template( [ "woocommerce/{$slug}-{$name}", "woocommerce/${name}" ] );
			return $theme_template ? template_path( $theme_template ) : $template;
		}, 100, 3 );
	}
}