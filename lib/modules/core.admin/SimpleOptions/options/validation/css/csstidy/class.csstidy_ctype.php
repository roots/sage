<?php

/**
 * CSSTidy - CSS Parser and Optimiser
 *
 * CSS ctype functions
 * Defines some functions that can be not defined.
 *
 * This file is part of CSSTidy.
 *
 * CSSTidy is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * CSSTidy is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with CSSTidy; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package csstidy
 * @author Nikolay Matsievsky (speed at webo dot name) 2009-2010
 * @version 1.0
 */
/* ctype_space  Check for whitespace character(s) */
if (!function_exists('ctype_space')) {

	function ctype_space($text) {
		return!preg_match("/[^\s\r\n\t\f]/", $text);
	}

}
/* ctype_alpha  Check for alphabetic character(s) */
if (!function_exists('ctype_alpha')) {

	function ctype_alpha($text) {
		return preg_match("/[a-zA-Z]/", $text);
	}

}
