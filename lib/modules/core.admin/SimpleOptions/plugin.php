<?php
/**
 * Plugin Name: Simple Options Framework
 * Plugin URI: https://github.com/SimpleRain/SimpleOptions
 * Description: A simple wordpress options framework for developers.
 * Version: 0.2.9
 * Author: SimpleRain
 * Author URI: http://simplerain.com
 *
 * The Simplefolio plugin was created to solve the problem of theme developers continuing 
 * to incorrectly add custom post types to handle portfolios within their themes.  This plugin allows 
 * any theme developer to build a "portfolio" theme without having to code the functionality.  This 
 * gives more time for design and makes users happy because their data isn't lost when they switch to 
 * a new theme.  Oh, and, this plugin lets creative folk put together a portfolio of their work on 
 * their site.
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package   SimpleOptions
 * @version   0.0.8
 * @since     0.0.8
 * @author    Dovy Paukstys <info@simplerain.com>
 * @copyright Copyright (c) 2013, SimpleRain
 * @link      http://simplerain.com/
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 */

	if(strpos(dirname(__FILE__),WP_PLUGIN_DIR) !== false && !class_exists('Simple_Options') && file_exists( dirname( __FILE__ ) . '/options/options.php' ) ){
		include_once( dirname( __FILE__ ) . '/options/options.php' );
	}

	if (file_exists( dirname( __FILE__ ) . '/options/SimpleUpdater.php') ) {
		include_once( dirname( __FILE__ ) . '/options/SimpleUpdater.php' );
	}	

	if (class_exists('Simple_Updater')) {
	  $Simple_Updater = new Simple_Updater( array( 
			'slug' 					=> __FILE__, 
			'force_update'	=> false,
		) );	
	}