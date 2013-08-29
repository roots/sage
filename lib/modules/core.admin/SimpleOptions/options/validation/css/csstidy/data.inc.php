<?php

/**
 * Various CSS Data for CSSTidy
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
 * @author Florian Schmitz (floele at gmail dot com) 2005
 * @author Nikolay Matsievsky (speed at webo dot name) 2010
 */

define('AT_START',    1);
define('AT_END',      2);
define('SEL_START',   3);
define('SEL_END',     4);
define('PROPERTY',    5);
define('VALUE',       6);
define('COMMENT',     7);
define('DEFAULT_AT', 41);

/**
 * All whitespace allowed in CSS
 *
 * @global array $GLOBALS['csstidy']['whitespace']
 * @version 1.0
 */
$GLOBALS['csstidy']['whitespace'] = array(' ',"\n","\t","\r","\x0B");

/**
 * All CSS tokens used by csstidy
 *
 * @global string $GLOBALS['csstidy']['tokens']
 * @version 1.0
 */
$GLOBALS['csstidy']['tokens'] = '/@}{;:=\'"(,\\!$%&)*+.<>?[]^`|~';

/**
 * All CSS units (CSS 3 units included)
 *
 * @see compress_numbers()
 * @global array $GLOBALS['csstidy']['units']
 * @version 1.0
 */
$GLOBALS['csstidy']['units'] = array('in','cm','mm','pt','pc','px','rem','em','%','ex','gd','vw','vh','vm','deg','grad','rad','ms','s','khz','hz');

/**
 * Available at-rules
 *
 * @global array $GLOBALS['csstidy']['at_rules']
 * @version 1.1
 */
$GLOBALS['csstidy']['at_rules'] = array('page' => 'is','font-face' => 'atis','charset' => 'iv', 'import' => 'iv','namespace' => 'iv','media' => 'at','keyframes' => 'at','-moz-keyframes' => 'at','-o-keyframes' => 'at','-webkit-keyframes' => 'at','-ms-keyframes' => 'at');

 /**
 * Properties that need a value with unit
 *
 * @todo CSS3 properties
 * @see compress_numbers();
 * @global array $GLOBALS['csstidy']['unit_values']
 * @version 1.2
 */
$GLOBALS['csstidy']['unit_values'] = array ('background', 'background-position', 'background-size', 'border', 'border-top', 'border-right', 'border-bottom', 'border-left', 'border-width',
											'border-top-width', 'border-right-width', 'border-left-width', 'border-bottom-width', 'bottom', 'border-spacing', 'column-gap', 'column-width',
											'font-size', 'height', 'left', 'margin', 'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'max-height',
											'max-width', 'min-height', 'min-width', 'outline', 'outline-width', 'padding', 'padding-top', 'padding-right',
											'padding-bottom', 'padding-left', 'perspective', 'right', 'top', 'text-indent', 'letter-spacing', 'word-spacing', 'width');

/**
 * Properties that allow <color> as value
 *
 * @todo CSS3 properties
 * @see compress_numbers();
 * @global array $GLOBALS['csstidy']['color_values']
 * @version 1.0
 */
$GLOBALS['csstidy']['color_values'] = array();
$GLOBALS['csstidy']['color_values'][] = 'background-color';
$GLOBALS['csstidy']['color_values'][] = 'border-color';
$GLOBALS['csstidy']['color_values'][] = 'border-top-color';
$GLOBALS['csstidy']['color_values'][] = 'border-right-color';
$GLOBALS['csstidy']['color_values'][] = 'border-bottom-color';
$GLOBALS['csstidy']['color_values'][] = 'border-left-color';
$GLOBALS['csstidy']['color_values'][] = 'color';
$GLOBALS['csstidy']['color_values'][] = 'outline-color';
$GLOBALS['csstidy']['color_values'][] = 'column-rule-color';

/**
 * Default values for the background properties
 *
 * @todo Possibly property names will change during CSS3 development
 * @global array $GLOBALS['csstidy']['background_prop_default']
 * @see dissolve_short_bg()
 * @see merge_bg()
 * @version 1.0
 */
$GLOBALS['csstidy']['background_prop_default'] = array();
$GLOBALS['csstidy']['background_prop_default']['background-image'] = 'none';
$GLOBALS['csstidy']['background_prop_default']['background-size'] = 'auto';
$GLOBALS['csstidy']['background_prop_default']['background-repeat'] = 'repeat';
$GLOBALS['csstidy']['background_prop_default']['background-position'] = '0 0';
$GLOBALS['csstidy']['background_prop_default']['background-attachment'] = 'scroll';
$GLOBALS['csstidy']['background_prop_default']['background-clip'] = 'border';
$GLOBALS['csstidy']['background_prop_default']['background-origin'] = 'padding';
$GLOBALS['csstidy']['background_prop_default']['background-color'] = 'transparent';

/**
 * Default values for the font properties
 *
 * @global array $GLOBALS['csstidy']['font_prop_default']
 * @see merge_fonts()
 * @version 1.3
 */
$GLOBALS['csstidy']['font_prop_default'] = array();
$GLOBALS['csstidy']['font_prop_default']['font-style'] = 'normal';
$GLOBALS['csstidy']['font_prop_default']['font-variant'] = 'normal';
$GLOBALS['csstidy']['font_prop_default']['font-weight'] = 'normal';
$GLOBALS['csstidy']['font_prop_default']['font-size'] = '';
$GLOBALS['csstidy']['font_prop_default']['line-height'] = '';
$GLOBALS['csstidy']['font_prop_default']['font-family'] = '';

/**
 * A list of non-W3C color names which get replaced by their hex-codes
 *
 * @global array $GLOBALS['csstidy']['replace_colors']
 * @see cut_color()
 * @version 1.0
 */
$GLOBALS['csstidy']['replace_colors'] = array();
$GLOBALS['csstidy']['replace_colors']['aliceblue'] = '#f0f8ff';
$GLOBALS['csstidy']['replace_colors']['antiquewhite'] = '#faebd7';
$GLOBALS['csstidy']['replace_colors']['aquamarine'] = '#7fffd4';
$GLOBALS['csstidy']['replace_colors']['azure'] = '#f0ffff';
$GLOBALS['csstidy']['replace_colors']['beige'] = '#f5f5dc';
$GLOBALS['csstidy']['replace_colors']['bisque'] = '#ffe4c4';
$GLOBALS['csstidy']['replace_colors']['blanchedalmond'] = '#ffebcd';
$GLOBALS['csstidy']['replace_colors']['blueviolet'] = '#8a2be2';
$GLOBALS['csstidy']['replace_colors']['brown'] = '#a52a2a';
$GLOBALS['csstidy']['replace_colors']['burlywood'] = '#deb887';
$GLOBALS['csstidy']['replace_colors']['cadetblue'] = '#5f9ea0';
$GLOBALS['csstidy']['replace_colors']['chartreuse'] = '#7fff00';
$GLOBALS['csstidy']['replace_colors']['chocolate'] = '#d2691e';
$GLOBALS['csstidy']['replace_colors']['coral'] = '#ff7f50';
$GLOBALS['csstidy']['replace_colors']['cornflowerblue'] = '#6495ed';
$GLOBALS['csstidy']['replace_colors']['cornsilk'] = '#fff8dc';
$GLOBALS['csstidy']['replace_colors']['crimson'] = '#dc143c';
$GLOBALS['csstidy']['replace_colors']['cyan'] = '#00ffff';
$GLOBALS['csstidy']['replace_colors']['darkblue'] = '#00008b';
$GLOBALS['csstidy']['replace_colors']['darkcyan'] = '#008b8b';
$GLOBALS['csstidy']['replace_colors']['darkgoldenrod'] = '#b8860b';
$GLOBALS['csstidy']['replace_colors']['darkgray'] = '#a9a9a9';
$GLOBALS['csstidy']['replace_colors']['darkgreen'] = '#006400';
$GLOBALS['csstidy']['replace_colors']['darkkhaki'] = '#bdb76b';
$GLOBALS['csstidy']['replace_colors']['darkmagenta'] = '#8b008b';
$GLOBALS['csstidy']['replace_colors']['darkolivegreen'] = '#556b2f';
$GLOBALS['csstidy']['replace_colors']['darkorange'] = '#ff8c00';
$GLOBALS['csstidy']['replace_colors']['darkorchid'] = '#9932cc';
$GLOBALS['csstidy']['replace_colors']['darkred'] = '#8b0000';
$GLOBALS['csstidy']['replace_colors']['darksalmon'] = '#e9967a';
$GLOBALS['csstidy']['replace_colors']['darkseagreen'] = '#8fbc8f';
$GLOBALS['csstidy']['replace_colors']['darkslateblue'] = '#483d8b';
$GLOBALS['csstidy']['replace_colors']['darkslategray'] = '#2f4f4f';
$GLOBALS['csstidy']['replace_colors']['darkturquoise'] = '#00ced1';
$GLOBALS['csstidy']['replace_colors']['darkviolet'] = '#9400d3';
$GLOBALS['csstidy']['replace_colors']['deeppink'] = '#ff1493';
$GLOBALS['csstidy']['replace_colors']['deepskyblue'] = '#00bfff';
$GLOBALS['csstidy']['replace_colors']['dimgray'] = '#696969';
$GLOBALS['csstidy']['replace_colors']['dodgerblue'] = '#1e90ff';
$GLOBALS['csstidy']['replace_colors']['feldspar'] = '#d19275';
$GLOBALS['csstidy']['replace_colors']['firebrick'] = '#b22222';
$GLOBALS['csstidy']['replace_colors']['floralwhite'] = '#fffaf0';
$GLOBALS['csstidy']['replace_colors']['forestgreen'] = '#228b22';
$GLOBALS['csstidy']['replace_colors']['gainsboro'] = '#dcdcdc';
$GLOBALS['csstidy']['replace_colors']['ghostwhite'] = '#f8f8ff';
$GLOBALS['csstidy']['replace_colors']['gold'] = '#ffd700';
$GLOBALS['csstidy']['replace_colors']['goldenrod'] = '#daa520';
$GLOBALS['csstidy']['replace_colors']['greenyellow'] = '#adff2f';
$GLOBALS['csstidy']['replace_colors']['honeydew'] = '#f0fff0';
$GLOBALS['csstidy']['replace_colors']['hotpink'] = '#ff69b4';
$GLOBALS['csstidy']['replace_colors']['indianred'] = '#cd5c5c';
$GLOBALS['csstidy']['replace_colors']['indigo'] = '#4b0082';
$GLOBALS['csstidy']['replace_colors']['ivory'] = '#fffff0';
$GLOBALS['csstidy']['replace_colors']['khaki'] = '#f0e68c';
$GLOBALS['csstidy']['replace_colors']['lavender'] = '#e6e6fa';
$GLOBALS['csstidy']['replace_colors']['lavenderblush'] = '#fff0f5';
$GLOBALS['csstidy']['replace_colors']['lawngreen'] = '#7cfc00';
$GLOBALS['csstidy']['replace_colors']['lemonchiffon'] = '#fffacd';
$GLOBALS['csstidy']['replace_colors']['lightblue'] = '#add8e6';
$GLOBALS['csstidy']['replace_colors']['lightcoral'] = '#f08080';
$GLOBALS['csstidy']['replace_colors']['lightcyan'] = '#e0ffff';
$GLOBALS['csstidy']['replace_colors']['lightgoldenrodyellow'] = '#fafad2';
$GLOBALS['csstidy']['replace_colors']['lightgrey'] = '#d3d3d3';
$GLOBALS['csstidy']['replace_colors']['lightgreen'] = '#90ee90';
$GLOBALS['csstidy']['replace_colors']['lightpink'] = '#ffb6c1';
$GLOBALS['csstidy']['replace_colors']['lightsalmon'] = '#ffa07a';
$GLOBALS['csstidy']['replace_colors']['lightseagreen'] = '#20b2aa';
$GLOBALS['csstidy']['replace_colors']['lightskyblue'] = '#87cefa';
$GLOBALS['csstidy']['replace_colors']['lightslateblue'] = '#8470ff';
$GLOBALS['csstidy']['replace_colors']['lightslategray'] = '#778899';
$GLOBALS['csstidy']['replace_colors']['lightsteelblue'] = '#b0c4de';
$GLOBALS['csstidy']['replace_colors']['lightyellow'] = '#ffffe0';
$GLOBALS['csstidy']['replace_colors']['limegreen'] = '#32cd32';
$GLOBALS['csstidy']['replace_colors']['linen'] = '#faf0e6';
$GLOBALS['csstidy']['replace_colors']['magenta'] = '#ff00ff';
$GLOBALS['csstidy']['replace_colors']['mediumaquamarine'] = '#66cdaa';
$GLOBALS['csstidy']['replace_colors']['mediumblue'] = '#0000cd';
$GLOBALS['csstidy']['replace_colors']['mediumorchid'] = '#ba55d3';
$GLOBALS['csstidy']['replace_colors']['mediumpurple'] = '#9370d8';
$GLOBALS['csstidy']['replace_colors']['mediumseagreen'] = '#3cb371';
$GLOBALS['csstidy']['replace_colors']['mediumslateblue'] = '#7b68ee';
$GLOBALS['csstidy']['replace_colors']['mediumspringgreen'] = '#00fa9a';
$GLOBALS['csstidy']['replace_colors']['mediumturquoise'] = '#48d1cc';
$GLOBALS['csstidy']['replace_colors']['mediumvioletred'] = '#c71585';
$GLOBALS['csstidy']['replace_colors']['midnightblue'] = '#191970';
$GLOBALS['csstidy']['replace_colors']['mintcream'] = '#f5fffa';
$GLOBALS['csstidy']['replace_colors']['mistyrose'] = '#ffe4e1';
$GLOBALS['csstidy']['replace_colors']['moccasin'] = '#ffe4b5';
$GLOBALS['csstidy']['replace_colors']['navajowhite'] = '#ffdead';
$GLOBALS['csstidy']['replace_colors']['oldlace'] = '#fdf5e6';
$GLOBALS['csstidy']['replace_colors']['olivedrab'] = '#6b8e23';
$GLOBALS['csstidy']['replace_colors']['orangered'] = '#ff4500';
$GLOBALS['csstidy']['replace_colors']['orchid'] = '#da70d6';
$GLOBALS['csstidy']['replace_colors']['palegoldenrod'] = '#eee8aa';
$GLOBALS['csstidy']['replace_colors']['palegreen'] = '#98fb98';
$GLOBALS['csstidy']['replace_colors']['paleturquoise'] = '#afeeee';
$GLOBALS['csstidy']['replace_colors']['palevioletred'] = '#d87093';
$GLOBALS['csstidy']['replace_colors']['papayawhip'] = '#ffefd5';
$GLOBALS['csstidy']['replace_colors']['peachpuff'] = '#ffdab9';
$GLOBALS['csstidy']['replace_colors']['peru'] = '#cd853f';
$GLOBALS['csstidy']['replace_colors']['pink'] = '#ffc0cb';
$GLOBALS['csstidy']['replace_colors']['plum'] = '#dda0dd';
$GLOBALS['csstidy']['replace_colors']['powderblue'] = '#b0e0e6';
$GLOBALS['csstidy']['replace_colors']['rosybrown'] = '#bc8f8f';
$GLOBALS['csstidy']['replace_colors']['royalblue'] = '#4169e1';
$GLOBALS['csstidy']['replace_colors']['saddlebrown'] = '#8b4513';
$GLOBALS['csstidy']['replace_colors']['salmon'] = '#fa8072';
$GLOBALS['csstidy']['replace_colors']['sandybrown'] = '#f4a460';
$GLOBALS['csstidy']['replace_colors']['seagreen'] = '#2e8b57';
$GLOBALS['csstidy']['replace_colors']['seashell'] = '#fff5ee';
$GLOBALS['csstidy']['replace_colors']['sienna'] = '#a0522d';
$GLOBALS['csstidy']['replace_colors']['skyblue'] = '#87ceeb';
$GLOBALS['csstidy']['replace_colors']['slateblue'] = '#6a5acd';
$GLOBALS['csstidy']['replace_colors']['slategray'] = '#708090';
$GLOBALS['csstidy']['replace_colors']['snow'] = '#fffafa';
$GLOBALS['csstidy']['replace_colors']['springgreen'] = '#00ff7f';
$GLOBALS['csstidy']['replace_colors']['steelblue'] = '#4682b4';
$GLOBALS['csstidy']['replace_colors']['tan'] = '#d2b48c';
$GLOBALS['csstidy']['replace_colors']['thistle'] = '#d8bfd8';
$GLOBALS['csstidy']['replace_colors']['tomato'] = '#ff6347';
$GLOBALS['csstidy']['replace_colors']['turquoise'] = '#40e0d0';
$GLOBALS['csstidy']['replace_colors']['violet'] = '#ee82ee';
$GLOBALS['csstidy']['replace_colors']['violetred'] = '#d02090';
$GLOBALS['csstidy']['replace_colors']['wheat'] = '#f5deb3';
$GLOBALS['csstidy']['replace_colors']['whitesmoke'] = '#f5f5f5';
$GLOBALS['csstidy']['replace_colors']['yellowgreen'] = '#9acd32';

/**
 * A list of all shorthand properties that are devided into four properties and/or have four subvalues
 *
 * @global array $GLOBALS['csstidy']['shorthands']
 * @todo Are there new ones in CSS3?
 * @see dissolve_4value_shorthands()
 * @see merge_4value_shorthands()
 * @version 1.0
 */
$GLOBALS['csstidy']['shorthands'] = array();
$GLOBALS['csstidy']['shorthands']['border-color'] = array('border-top-color','border-right-color','border-bottom-color','border-left-color');
$GLOBALS['csstidy']['shorthands']['border-style'] = array('border-top-style','border-right-style','border-bottom-style','border-left-style');
$GLOBALS['csstidy']['shorthands']['border-width'] = array('border-top-width','border-right-width','border-bottom-width','border-left-width');
$GLOBALS['csstidy']['shorthands']['margin'] = array('margin-top','margin-right','margin-bottom','margin-left');
$GLOBALS['csstidy']['shorthands']['padding'] = array('padding-top','padding-right','padding-bottom','padding-left');
$GLOBALS['csstidy']['shorthands']['-moz-border-radius'] = 0;

/**
 * All CSS Properties. Needed for csstidy::property_is_next()
 *
 * @global array $GLOBALS['csstidy']['all_properties']
 * @version 1.1
 * @see csstidy::property_is_next()
 */
$GLOBALS['csstidy']['all_properties']['alignment-adjust'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['alignment-baseline'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation-delay'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation-direction'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation-duration'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation-iteration-count'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation-name'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation-play-state'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['animation-timing-function'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['appearance'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['azimuth'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['backface-visibility'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['background'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-attachment'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-clip'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-color'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-image'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-origin'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-position'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-repeat'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['background-size'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['baseline-shift'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['binding'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['bleed'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['bookmark-label'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['bookmark-level'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['bookmark-state'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['bookmark-target'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-bottom'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-bottom-color'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-bottom-left-radius'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-bottom-right-radius'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-bottom-style'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-bottom-width'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-collapse'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-color'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-image'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-image-outset'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-image-repeat'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-image-slice'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-image-source'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-image-width'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-left'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-left-color'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-left-style'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-left-width'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-radius'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-right'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-right-color'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-right-style'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-right-width'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-spacing'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-style'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-top'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-top-color'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-top-left-radius'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-top-right-radius'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-top-style'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-top-width'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['border-width'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['bottom'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['box-decoration-break'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['box-shadow'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['box-sizing'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['break-after'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['break-before'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['break-inside'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['caption-side'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['clear'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['clip'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['color'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['color-profile'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-count'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-fill'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-gap'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-rule'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-rule-color'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-rule-style'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-rule-width'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-span'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['column-width'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['columns'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['content'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['counter-increment'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['counter-reset'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['crop'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['cue'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['cue-after'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['cue-before'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['cursor'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['direction'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['display'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['dominant-baseline'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['drop-initial-after-adjust'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['drop-initial-after-align'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['drop-initial-before-adjust'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['drop-initial-before-align'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['drop-initial-size'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['drop-initial-value'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['elevation'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['empty-cells'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['fit'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['fit-position'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['flex-align'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['flex-flow'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['flex-line-pack'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['flex-order'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['flex-pack'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['float'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['float-offset'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['font'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['font-family'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['font-size'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['font-size-adjust'] = 'CSS2.0,CSS3.0';
$GLOBALS['csstidy']['all_properties']['font-stretch'] = 'CSS2.0,CSS3.0';
$GLOBALS['csstidy']['all_properties']['font-style'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['font-variant'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['font-weight'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['grid-columns'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['grid-rows'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['hanging-punctuation'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['height'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['hyphenate-after'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['hyphenate-before'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['hyphenate-character'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['hyphenate-lines'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['hyphenate-resource'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['hyphens'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['icon'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['image-orientation'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['image-rendering'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['image-resolution'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['inline-box-align'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['left'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['letter-spacing'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['line-break'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['line-height'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['line-stacking'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['line-stacking-ruby'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['line-stacking-shift'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['line-stacking-strategy'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['list-style'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['list-style-image'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['list-style-position'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['list-style-type'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['margin'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['margin-bottom'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['margin-left'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['margin-right'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['margin-top'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['marker-offset'] = 'CSS2.0,CSS3.0';
$GLOBALS['csstidy']['all_properties']['marks'] = 'CSS2.0,CSS3.0';
$GLOBALS['csstidy']['all_properties']['marquee-direction'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['marquee-loop'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['marquee-play-count'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['marquee-speed'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['marquee-style'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['max-height'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['max-width'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['min-height'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['min-width'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['move-to'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['nav-down'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['nav-index'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['nav-left'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['nav-right'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['nav-up'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['opacity'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['orphans'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['outline'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['outline-color'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['outline-offset'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['outline-style'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['outline-width'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['overflow'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['overflow-style'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['overflow-wrap'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['overflow-x'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['overflow-y'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['padding'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['padding-bottom'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['padding-left'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['padding-right'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['padding-top'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['page'] = 'CSS2.0,CSS3.0';
$GLOBALS['csstidy']['all_properties']['page-break-after'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['page-break-before'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['page-break-inside'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['page-policy'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['pause'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['pause-after'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['pause-before'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['perspective'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['perspective-origin'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['phonemes'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['pitch'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['pitch-range'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['play-during'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['position'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['presentation-level'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['punctuation-trim'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['quotes'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['rendering-intent'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['resize'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['rest'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['rest-after'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['rest-before'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['richness'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['right'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['rotation'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['rotation-point'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['ruby-align'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['ruby-overhang'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['ruby-position'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['ruby-span'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['size'] = 'CSS2.0,CSS3.0';
$GLOBALS['csstidy']['all_properties']['speak'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['speak-header'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['speak-numeral'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['speak-punctuation'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['speech-rate'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['src'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['stress'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['string-set'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['tab-size'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['table-layout'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['target'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['target-name'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['target-new'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['target-position'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-align'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-align-last'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-decoration'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-decoration-color'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-decoration-line'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-decoration-skip'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-decoration-style'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-emphasis'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-emphasis-color'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-emphasis-position'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-emphasis-style'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-height'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-indent'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-justify'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-outline'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-shadow'] = 'CSS2.0,CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-space-collapse'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-transform'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-underline-position'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['text-wrap'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['top'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['transform'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['transform-origin'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['transform-style'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['transition'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['transition-delay'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['transition-duration'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['transition-property'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['transition-timing-function'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['unicode-bidi'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['vertical-align'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['visibility'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-balance'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-duration'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-family'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-pitch'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-pitch-range'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-rate'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-stress'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['voice-volume'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['volume'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['white-space'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['widows'] = 'CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['width'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['word-break'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['word-spacing'] = 'CSS1.0,CSS2.0,CSS2.1,CSS3.0';
$GLOBALS['csstidy']['all_properties']['word-wrap'] = 'CSS3.0';
$GLOBALS['csstidy']['all_properties']['z-index'] = 'CSS2.0,CSS2.1,CSS3.0';

/**
 * An array containing all properties that can accept a quoted string as a value.
 *
 * @global array $GLOBALS['csstidy']['quoted_string_properties']
 */
$GLOBALS['csstidy']['quoted_string_properties'] = array('content', 'font-family', 'quotes');

/**
 * An array containing all properties that can be defined multiple times without being overwritten.
 *
 * @global array $GLOBALS['csstidy']['quoted_string_properties']
 */
$GLOBALS['csstidy']['multiple_properties'] = array('background', 'background-image');

/**
 * An array containing all predefined templates.
 *
 * @global array $GLOBALS['csstidy']['predefined_templates']
 * @version 1.0
 * @see csstidy::load_template()
 */
$GLOBALS['csstidy']['predefined_templates']['default'][] = '<span class="at">'; //string before @rule
$GLOBALS['csstidy']['predefined_templates']['default'][] = '</span> <span class="format">{</span>'."\n"; //bracket after @-rule
$GLOBALS['csstidy']['predefined_templates']['default'][] = '<span class="selector">'; //string before selector
$GLOBALS['csstidy']['predefined_templates']['default'][] = '</span> <span class="format">{</span>'."\n"; //bracket after selector
$GLOBALS['csstidy']['predefined_templates']['default'][] = '<span class="property">'; //string before property
$GLOBALS['csstidy']['predefined_templates']['default'][] = '</span><span class="value">'; //string after property+before value
$GLOBALS['csstidy']['predefined_templates']['default'][] = '</span><span class="format">;</span>'."\n"; //string after value
$GLOBALS['csstidy']['predefined_templates']['default'][] = '<span class="format">}</span>'; //closing bracket - selector
$GLOBALS['csstidy']['predefined_templates']['default'][] = "\n\n"; //space between blocks {...}
$GLOBALS['csstidy']['predefined_templates']['default'][] = "\n".'<span class="format">}</span>'. "\n\n"; //closing bracket @-rule
$GLOBALS['csstidy']['predefined_templates']['default'][] = ''; //indent in @-rule
$GLOBALS['csstidy']['predefined_templates']['default'][] = '<span class="comment">'; // before comment
$GLOBALS['csstidy']['predefined_templates']['default'][] = '</span>'."\n"; // after comment
$GLOBALS['csstidy']['predefined_templates']['default'][] = "\n"; // after each line @-rule

$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '<span class="at">';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '</span> <span class="format">{</span>'."\n";
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '<span class="selector">';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '</span><span class="format">{</span>';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '<span class="property">';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '</span><span class="value">';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '</span><span class="format">;</span>';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '<span class="format">}</span>';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = "\n";
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = "\n". '<span class="format">}'."\n".'</span>';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '';
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '<span class="comment">'; // before comment
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = '</span>'; // after comment
$GLOBALS['csstidy']['predefined_templates']['high_compression'][] = "\n";

$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '<span class="at">';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '</span><span class="format">{</span>';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '<span class="selector">';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '</span><span class="format">{</span>';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '<span class="property">';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '</span><span class="value">';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '</span><span class="format">;</span>';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '<span class="format">}</span>';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '<span class="format">}</span>';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '';
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '<span class="comment">'; // before comment
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '</span>'; // after comment
$GLOBALS['csstidy']['predefined_templates']['highest_compression'][] = '';

$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '<span class="at">';
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '</span> <span class="format">{</span>'."\n";
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '<span class="selector">';
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '</span>'."\n".'<span class="format">{</span>'."\n";
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '	<span class="property">';
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '</span><span class="value">';
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '</span><span class="format">;</span>'."\n";
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '<span class="format">}</span>';
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = "\n\n";
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = "\n".'<span class="format">}</span>'."\n\n";
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '	';
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '<span class="comment">'; // before comment
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = '</span>'."\n"; // after comment
$GLOBALS['csstidy']['predefined_templates']['low_compression'][] = "\n";
