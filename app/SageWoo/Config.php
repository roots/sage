<?php

namespace App\SageWoo;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Config {
	private $config;

	/**
	 * @return mixed
	 */
	public function getConfig() {
		return $this->config;
	}

	const CONFIG_PATH = '/../config/sage-woo.yml';

	/**
	 * @return mixed
	 */
	public function enabledModules() {
		return array_keys( $this->config );
	}

	public function __construct() {
		$file = get_template_directory() . self::CONFIG_PATH;
		$this->loadConfig( $file );
	}

	public static function configExists() {
		$file = get_template_directory() . self::CONFIG_PATH;
		if ( file_exists( $file ) && filesize( $file ) > 0 ) {
			return true;
		}

		return false;
	}

	private function loadConfig( string $file ) {
		$yaml = new Parser();
		try {
			$this->config = $yaml->parse( file_get_contents( $file ) );
		} catch ( ParseException $e ) {
			throw $e;
		}
	}
}