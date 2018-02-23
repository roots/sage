<?php

namespace App\SageWoo\Modules;

abstract class Module {
	abstract protected function setDefaultConfig();

	abstract protected function setConfig(array $user_config );

	public function parseConfig( array $config, array $user_config ) {
		$arr = [];
		foreach ( $user_config as $setting => $value ) {
			if ( array_key_exists( $setting, $config ) ) {
				$arr[ $setting ] = $value;
			}
		}
		return $arr;
	}
}