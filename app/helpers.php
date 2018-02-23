<?php

namespace App;

use Roots\Sage\Container;

/**
 * Get the sage container.
 *
 * @param string $abstract
 * @param array  $parameters
 * @param Container $container
 * @return Container|mixed
 */
function sage($abstract = null, $parameters = [], Container $container = null)
{
    $container = $container ?: Container::getInstance();
    if (!$abstract) {
        return $container;
    }
    return $container->bound($abstract)
        ? $container->makeWith($abstract, $parameters)
        : $container->makeWith("sage.{$abstract}", $parameters);
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed $default
 * @return mixed|\Roots\Sage\Config
 * @copyright Taylor Otwell
 * @link https://github.com/laravel/framework/blob/c0970285/src/Illuminate/Foundation/helpers.php#L254-L265
 */
function config($key = null, $default = null)
{
    if (is_null($key)) {
        return sage('config');
    }
    if (is_array($key)) {
        return sage('config')->set($key);
    }
    return sage('config')->get($key, $default);
}

/**
 * @param string $file
 * @param array $data
 * @return string
 */
function template($file, $data = [])
{
    if (remove_action('wp_head', 'wp_enqueue_scripts', 1)) {
        wp_enqueue_scripts();
    }

    return sage('blade')->render($file, $data);
}

/**
 * Retrieve path to a compiled blade view
 * @param $file
 * @param array $data
 * @return string
 */
function template_path($file, $data = [])
{
    return sage('blade')->compiledPath($file, $data);
}

/**
 * @param $asset
 * @return string
 */
function asset_path($asset)
{
    return sage('assets')->getUri($asset);
}

/**
 * @param string|string[] $templates Possible template files
 * @return array
 */
function filter_templates($templates)
{
    $paths = apply_filters('sage/filter_templates/paths', [
        'views',
        'resources/views',
	    'resources/views/woocommerce',
    ]);
    $paths_pattern = "#^(" . implode('|', $paths) . ")/#";

    return collect($templates)
        ->map(function ($template) use ($paths_pattern) {
            /** Remove .blade.php/.blade/.php from template names */
            $template = preg_replace('#\.(blade\.?)?(php)?$#', '', ltrim($template));

            /** Remove partial $paths from the beginning of template names */
            if (strpos($template, '/')) {
                $template = preg_replace($paths_pattern, '', $template);
            }

            return $template;
        })
        ->flatMap(function ($template) use ($paths) {
            return collect($paths)
                ->flatMap(function ($path) use ($template) {
                    return [
                        "{$path}/{$template}.blade.php",
                        "{$path}/{$template}.php",
                        "{$template}.blade.php",
                        "{$template}.php",
                    ];
                });
        })
        ->filter()
        ->unique()
        ->all();
}

/**
 * @param string|string[] $templates Relative path to possible template files
 * @return string Location of the template
 */
function locate_template($templates)
{
    return \locate_template(filter_templates($templates));
}

/**
 * Determine whether to show the sidebar
 * @return bool
 */
function display_sidebar()
{
    static $display;
    isset($display) || $display = apply_filters('sage/display_sidebar', false);
    return $display;
}

/**
 * Parses the woocommerce_form_field and replaces:
 * form-row with form-group,
 * form-row-first and form-row-last with col-md-6,
 * form-row-wide with col-sm-12
 * Allowed values are
 * 'billing',
 * 'account',
 * 'shipping',
 * 'order',
 * 'myaccount-address'
 * @param string $fields_type
 * @param array $address_fields
 */
function alterWooFields(string $fields_type, array $address_fields = []) {
	$checkout_types = [
		'billing',
		'account',
		'shipping',
		'order',
	];

	$isCheckout =  in_array($fields_type, $checkout_types);
	$isEditAccount = $fields_type === 'myaccount-address';
	$checkout = '';
	$fields = [];

	if ($isCheckout) {
		$checkout = \WC_Checkout::instance();
		$fields = $checkout->get_checkout_fields( $fields_type );
	}

	if ($isEditAccount) {
		if (empty($address_fields)) {
			trigger_error('Address fields must be provided');
			return;
		}
		$fields = $address_fields;
	}

	foreach ( $fields as $key => $field ) {
		$value = '';
		$field['return'] = true;

		if ($isCheckout) {
			if ( isset( $field['country_field'], $fields[ $field['country_field'] ] ) ) {
				$field['country'] = $checkout->get_value( $field['country_field'] );
			}
			$value = $checkout->get_value( $key );
		}

		if ($isEditAccount) {
			if ( isset( $field['country_field'], $address[ $field['country_field'] ] ) ) {
				$field['country'] = wc_get_post_data_by_key( $field['country_field'], $fields[ $field['country_field'] ]['value'] );
			}
			$value = wc_get_post_data_by_key( $key, $field['value'] );
		}

		$output = woocommerce_form_field( $key, $field, $value );

		$output = str_replace(
			['form-row ', 'form-row-first', 'form-row-last', 'form-row-wide'],
			['form-group sw-form ', 'col-sm-6 sw-form', 'col-sm-6 sw-form', 'col-sm-12 sw-form'], $output);
		echo $output;
	}
}

/**
 * @param $haystack
 * @param $needle
 *
 * @return bool
 * @link https://stackoverflow.com/a/10473026
 */
function startsWith($haystack, $needle) {
	// search backwards starting from haystack length characters from the end
	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

/**
 * @param $haystack
 * @param $needle
 *
 * @return bool
 * @link https://stackoverflow.com/a/10473026
 */
function endsWith($haystack, $needle) {
	// search forward starting from end minus needle length characters
	return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
}

