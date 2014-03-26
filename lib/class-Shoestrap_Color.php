<?php


if ( ! class_exists( 'Shoestrap_Color' ) ) {
	/**
	* Color Calculations class for Shoestrap
	*/
	class Shoestrap_Color {

		function __construct() {
		}

		public static function sanitize_hex( $color ) {
			// Remove any spaces and special characters before and after the string
			$color = trim( $color. ' \t\n\r\0\x0B' );

			// Remove any trailing '#' symbols from the color value
			$color = str_replace( '#', '', $color );

			// If there are more than 6 characters, only keep the first 6.
			if ( strlen( $color ) > 6 ) {
				$color = substr( $color, 0, 6 );
			}

			if ( strlen( $color ) == 6 ) {
				$hex = $color; // If string consists of 6 characters, then this is our color
			} else {
				// String is shorter than 6 characters.
				// We will have to do some calculations below to get the actual 6-digit hex value.

				// If the string is longer than 3 characters, only keep the first 3.
				if ( strlen( $color ) > 3 ) {
					$color = substr( $color, 0, 3 );
				}

				// If this is a 3-character string, format it to 6 characters.
				if ( strlen( $color ) == 3 ) {
					$red    = substr( $color, 0, 1 ) . substr( $color, 0, 1 );
					$green  = substr( $color, 1, 1 ) . substr( $color, 1, 1 );
					$blue   = substr( $color, 2, 1 ) . substr( $color, 2, 1 );

					$hex = $red . $green . $blue;
				}

				// If this is shorter than 3 characters, do some voodoo.
				if ( strlen( $color ) == 2 ) {
					$hex = $color . $color . $color;
				}

				if ( strlen( $color ) == 1 ) {
					$hex = $color . $color . $color . $color . $color . $color;
				}
			}

			return '#' . $hex;
		}

		/*
		 * Gets the rgb value of the $hex color.
		 * Returns an array.
		 */
		public static function get_rgb( $hex, $implode = false ) {
			// Remove any trailing '#' symbols from the color value
			$hex = str_replace( '#', '', self::sanitize_hex( $hex ) );

			if ( strlen( $hex ) == 3 ) {
				// If the color is entered using a short, 3-character format,
				// then find the rgb values from them
				$red    = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
				$green  = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
				$blue   = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			} else {
				// If the color is entered using a 6-character format,
				// then find the rgb values from them
				$red    = hexdec( substr( $hex, 0, 2 ) );
				$green  = hexdec( substr( $hex, 2, 2 ) );
				$blue   = hexdec( substr( $hex, 4, 2 ) );
			}

			// rgb is an array
			$rgb = array( $red, $green, $blue );
			if ( $implode ) {
				return implode( ',', $rgb );
			} else {
				return $rgb;
			}
		}

		/*
		 * Gets the rgba value of a color.
		 */
		public static function get_rgba( $hex = '#fff', $opacity = 100, $echo = false ) {
			$hex = self::sanitize_hex( $hex );
			// Make sure that opacity is properly formatted :
			// Set the opacity to 100 if a larger value has been entered by mistake.
			// If a negative value is used, then set to 0.
			// If an opacity value is entered in a decimal form (for example 0.25), then multiply by 100.
			if ( $opacity >= 100 ) {
				$opacity = 100;
			} elseif ( $opacity < 0 ) {
				$opacity = 0;
			} elseif ( $opacity < 1 && $opacity != 0 ) {
				$opacity = ( $opacity * 100 );
			} else {
				$opacity = $opacity;
			}

			// Divide the opacity by 100 to end-up with a CSS value for the opacity
			$opacity = ( $opacity / 100 );

			$color = 'rgba(' . self::get_rgb( $hex, true ) . ', ' . $opacity . ')';

			// Echo or Return the value
			if ( $echo == true ) {
				echo $color;
			} else {
				return $color;
			}

		}

		/*
		 * Gets the brightness of the $hex color.
		 * Returns a value between 0 and 255
		 */
		public static function get_brightness( $hex ) {
			$hex = self::sanitize_hex( $hex );
			// returns brightness value from 0 to 255
			// strip off any leading #
			$hex = str_replace( '#', '', $hex );

			$red    = hexdec( substr( $hex, 0, 2 ) );
			$green  = hexdec( substr( $hex, 2, 2 ) );
			$blue   = hexdec( substr( $hex, 4, 2 ) );

			return ( ( $red * 299 ) + ( $green * 587 ) + ( $blue * 114 ) ) / 1000;
		}

		/*
		 * Adjexts brightness of the $hex color.
		 * the $steps variable is a value between -255 (darken) and 255 (lighten)
		 */
		public static function adjust_brightness( $hex, $steps ) {
			$hex = self::sanitize_hex( $hex );
			// Steps should be between -255 and 255. Negative = darker, positive = lighter
			$steps = max( -255, min( 255, $steps ) );

			// Format the hex color string
			$hex = str_replace( '#', '', $hex );
			if ( strlen( $hex ) == 3 ) {
				$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
			}

			// Get decimal values
			$red    = hexdec( substr( $hex, 0, 2 ) );
			$green  = hexdec( substr( $hex, 2, 2 ) );
			$blue   = hexdec( substr( $hex, 4, 2 ) );

			// Adjust number of steps and keep it inside 0 to 255
			$red    = max( 0, min( 255, $red + $steps ) );
			$green  = max( 0, min( 255, $green + $steps ) );
			$blue   = max( 0, min( 255, $blue + $steps ) );

			$red_hex    = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
			$green_hex  = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
			$blue_hex   = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );

			return '#' . $red_hex . $green_hex . $blue_hex;
		}

		/*
		 * Mixes 2 hex colors.
		 * the "percentage" variable is the percent of the first color
		 * to be used it the mix. default is 50 (equal mix)
		 */
		public static function mix_colors( $hex1, $hex2, $percentage ) {
			$hex1 = self::sanitize_hex( $hex1 );
			$hex2 = self::sanitize_hex( $hex2 );

			// Format the hex color string
			$hex1 = str_replace( '#', '', $hex1 );
			if ( strlen( $hex1 ) == 3 ) {
				$hex1 = str_repeat( substr( $hex1, 0, 1 ), 2 ) . str_repeat( substr( $hex1, 1, 1 ), 2 ) . str_repeat( substr( $hex1, 2, 1 ), 2 );
			}

			$hex2 = str_replace( '#', '', $hex2 );
			if ( strlen( $hex2 ) == 3 ) {
				str_repeat( substr( $hex2, 0, 1 ), 2 ) . str_repeat( substr( $hex2, 1, 1 ), 2 ) . str_repeat( substr( $hex2, 2, 1 ), 2 );
			}

			// Get decimal values
			$red_1    = hexdec( substr( $hex1, 0, 2 ) );
			$green_1  = hexdec( substr( $hex1, 2, 2 ) );
			$blue_1   = hexdec( substr( $hex1, 4, 2 ) );
			$red_2    = hexdec( substr( $hex2, 0, 2 ) );
			$green_2  = hexdec( substr( $hex2, 2, 2 ) );
			$blue_2   = hexdec( substr( $hex2, 4, 2 ) );

			$red      = ( $percentage * $red_1 + ( 100 - $percentage ) * $red_2 ) / 100;
			$green    = ( $percentage * $green_1 + ( 100 - $percentage ) * $green_2 ) / 100;
			$blue     = ( $percentage * $blue_1 + ( 100 - $percentage ) * $blue_2 ) / 100;

			$red_hex    = str_pad( dechex( $red ), 2, '0', STR_PAD_LEFT );
			$green_hex  = str_pad( dechex( $green ), 2, '0', STR_PAD_LEFT );
			$blue_hex   = str_pad( dechex( $blue ), 2, '0', STR_PAD_LEFT );

			return '#' . $red_hex . $green_hex . $blue_hex;
		}

		/*
		 * Convert a hex color to HSV
		 */
		public static function hex_to_hsv( $hex ) {
			$hex = self::sanitize_hex( $hex );
			$hex = str_replace( '#', '', $hex );
			$rgb = self::get_rgb( $hex );
			$hsv = self::rgb_to_hsv( $rgb );

			return $hsv;
		}

		/*
		 * Convert an RGB array to HSV
		 */
		public static function rgb_to_hsv( $color = array() ) {
			$r = $color[0];
			$g = $color[1];
			$b = $color[2];

			$hsl = array();

			$var_r = ( $r / 255 );
			$var_g = ( $g / 255 );
			$var_b = ( $b / 255 );

			$var_min = min( $var_r, $var_g, $var_b);
			$var_max = max( $var_r, $var_g, $var_b);
			$del_max = $var_max - $var_min;

			$v = $var_max;

			if ( $del_max == 0 ) {
				$h = 0;
				$s = 0;
			} else {
				$s = $del_max / $var_max;

				$del_r = ( ( ( $var_max - $var_r ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
				$del_g = ( ( ( $var_max - $var_g ) / 6 ) + ( $del_max / 2 ) ) / $del_max;
				$del_b = ( ( ( $var_max - $var_b ) / 6 ) + ( $del_max / 2 ) ) / $del_max;

				if ( $var_r == $var_max ) {
					$h = $del_b - $del_g;
				} elseif ( $var_g == $var_max ) {
					$h = ( 1 / 3 ) + $del_r - $del_b;
				} elseif ( $var_b == $var_max ) {
					$h = ( 2 / 3 ) + $del_g - $del_r;
				}

				if ( $h<0 ) {
					$h++;
				}

				if ( $h>1 ) {
					$h--;
				}
			}

			$hsl['h'] = $h;
			$hsl['s'] = $s;
			$hsl['v'] = $v;

			return $hsl;
		}

		/*
		 * Get the brightest color from an array of colors.
		 * Return the key of the array if $context = 'key'
		 * Return the hex value of the color if $context = 'value'
		 */
		public static function brightest_color( $colors = array(), $context = 'key' ) {
			$brightest = false;

			foreach ( $colors as $color ) {
				$color      = self::sanitize_hex( $color );
				$hex        = str_replace( '#', '', $color );
				$brightness = self::get_brightness( $hex );

				if ( ! $brightest || self::get_brightness( $hex ) > self::get_brightness( $brightest ) ) {
					$brightest = $hex;
				}
			}

			if ( $context == 'key' ) {
				return array_search( $brightest, $colors );
			} elseif ( $context == 'value' ) {
				return $brightest;
			}
		}

		/*
		 * Get the most saturated color from an array of colors.
		 * Return the key of the array if $context = 'key'
		 * Return the hex value of the color if $context = 'value'
		 */
		public static function most_saturated_color( $colors = array(), $context = 'key' ) {
			$most_saturated = false;

			foreach ( $colors as $color ) {
				$color      = self::sanitize_hex( $color );
				$hex        = str_replace( '#', '', $color );
				$hsv        = self::hex_to_hsv( $hex );
				$saturation = $hsv['s'];

				if ( $most_saturated ) {
					$hsv_old = self::hex_to_hsv( $most_saturated );
				}

				if ( ! $most_saturated || $saturation > $hsv_old['s'] ) {
					$most_saturated = $hex;
				}
			}

			if ( $context == 'key' ) {
				return array_search( $most_saturated, $colors );
			} elseif ( $context == 'value' ) {
				return $most_saturated;
			}
		}

		/*
		 * Get the most intense color from an array of colors.
		 * Return the key of the array if $context = 'key'
		 * Return the hex value of the color if $context = 'value'
		 */
		public static function most_intense_color( $colors = array(), $context = 'key' ) {
			$most_intense = false;

			foreach ( $colors as $color ) {
				$color      = self::sanitize_hex( $color );
				$hex        = str_replace( '#', '', $color );
				$hsv        = self::hex_to_hsv( $hex );
				$saturation = $hsv['s'];

				if ( $most_intense ) {
					$hsv_old = self::hex_to_hsv( $most_intense );
				}

				if ( ! $most_intense || $saturation > $hsv_old['s'] ) {
					$most_intense = $hex;
				}
			}

			if ( $context == 'key' ) {
				return array_search( $most_intense, $colors );
			} elseif ( $context == 'value' ) {
				return $most_intense;
			}
		}

		/*
		 * Get the brightest color from an array of colors.
		 * Return the key of the array if $context = 'key'
		 * Return the hex value of the color if $context = 'value'
		 */
		public static function brightest_dull_color( $colors = array(), $context = 'key' ) {
			$brightest_dull = false;

			foreach ( $colors as $color ) {
				$color        = self::sanitize_hex( $color );
				$hex          = str_replace( '#', '', $color );
				$hsv          = self::hex_to_hsv( $hex );

				$brightness   = self::get_brightness( $hex );
				// Prevent "division by zero" messages.
				$hsv['s']     = ( $hsv['s'] == 0 ) ? 0.0001 : $hsv['s'];
				$dullness     = 1 / $hsv['s'];

				if ( $brightest_dull ) {
					$hsv_old      = self::hex_to_hsv( $brightest_dull );
					// Prevent "division by zero" messages.
					$hsv_old['s'] = ( $hsv_old['s'] == 0 ) ? 0.0001 : $hsv_old['s'];
					$dullness_old = 1 / $hsv_old['s'];
				}

				if ( ! $brightest_dull || self::get_brightness( $hex ) * $dullness > self::get_brightness( $brightest_dull ) * $dullness_old ) {
					$brightest_dull = $hex;
				}
			}

			if ( $context == 'key' ) {
				return array_search( $brightest_dull, $colors );
			} elseif ( $context == 'value' ) {
				return $brightest_dull;
			}
		}

		/*
		 * This is a very simple algorithm that works by summing up the differences between the three color components red, green and blue.
		 * A value higher than 500 is recommended for good readability.
		 */
		public static function color_difference( $color_1 = '#ffffff', $color_2 = '#000000' ) {
			$color_1 = self::sanitize_hex( $color_1 );
			$color_2 = self::sanitize_hex( $color_2 );

			$color_1_rgb = self::get_rgb( $color_1 );
			$color_2_rgb = self::get_rgb( $color_2 );

			$r1 = $color_1_rgb[0];
			$g1 = $color_1_rgb[1];
			$b1 = $color_1_rgb[2];

			$r2 = $color_2_rgb[0];
			$g2 = $color_2_rgb[1];
			$b2 = $color_2_rgb[2];

			$r_diff = max( $r1, $r2 ) - min( $r1, $r2 );
			$g_diff = max( $g1, $g2 ) - min( $g1, $g2 );
			$b_diff = max( $b1, $b2 ) - min( $b1, $b2 );

			$color_diff = $r_diff + $g_diff + $b_diff;

			return $color_diff;
		}

		/*
		 * This function tries to compare the brightness of the colors.
		 * A return value of more than 125 is recommended.
		 * Combining it with the color_difference function above might make sense.
		 */
		public static function brightness_difference( $color_1 = '#ffffff', $color_2 = '#000000' ) {
			$color_1 = self::sanitize_hex( $color_1 );
			$color_2 = self::sanitize_hex( $color_2 );

			$color_1_rgb = self::get_rgb( $color_1 );
			$color_2_rgb = self::get_rgb( $color_2 );

			$r1 = $color_1_rgb[0];
			$g1 = $color_1_rgb[1];
			$b1 = $color_1_rgb[2];

			$r2 = $color_2_rgb[0];
			$g2 = $color_2_rgb[1];
			$b2 = $color_2_rgb[2];

			$br_1 = ( 299 * $r1 + 587 * $g1 + 114 * $b1 ) / 1000;
			$br_2 = ( 299 * $r2 + 587 * $g2 + 114 * $b2 ) / 1000;

			return abs( $br_1 - $br_2 );
		}

		/*
		 * Uses the luminosity to calculate the difference between the given colors.
		 * The returned value should be bigger than 5 for best readability.
		 */
		public static function lumosity_difference( $color_1 = '#ffffff', $color_2 = '#000000' ) {
			$color_1 = self::sanitize_hex( $color_1 );
			$color_2 = self::sanitize_hex( $color_2 );

			$color_1_rgb = self::get_rgb( $color_1 );
			$color_2_rgb = self::get_rgb( $color_2 );

			$r1 = $color_1_rgb[0];
			$g1 = $color_1_rgb[1];
			$b1 = $color_1_rgb[2];

			$r2 = $color_2_rgb[0];
			$g2 = $color_2_rgb[1];
			$b2 = $color_2_rgb[2];

			$l1 = 0.2126 * pow( $r1 / 255, 2.2 ) + 0.7152 * pow( $g1 / 255, 2.2 ) + 0.0722 * pow( $b1 / 255, 2.2 );
			$l2 = 0.2126 * pow( $r2 / 255, 2.2 ) + 0.7152 * pow( $g2 / 255, 2.2 ) + 0.0722 * pow( $b2 / 255, 2.2 );

			$lum_diff = ( $l1 > $l2 ) ? ( $l1 + 0.05 ) / ( $l2 + 0.05 ) : ( $l2 + 0.05 ) / ( $l1 + 0.05 );

			return $lum_diff;

		}
	}
}