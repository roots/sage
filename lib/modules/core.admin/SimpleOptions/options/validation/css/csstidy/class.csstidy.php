<?php

/**
 * CSSTidy - CSS Parser and Optimiser
 *
 * CSS Parser class
 *
 * Copyright 2005, 2006, 2007 Florian Schmitz
 *
 * This file is part of CSSTidy.
 *
 *   CSSTidy is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU Lesser General Public License as published by
 *   the Free Software Foundation; either version 2.1 of the License, or
 *   (at your option) any later version.
 *
 *   CSSTidy is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU Lesser General Public License for more details.
 *
 *   You should have received a copy of the GNU Lesser General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @license http://opensource.org/licenses/lgpl-license.php GNU Lesser General Public License
 * @package csstidy
 * @author Florian Schmitz (floele at gmail dot com) 2005-2007
 * @author Brett Zamir (brettz9 at yahoo dot com) 2007
 * @author Nikolay Matsievsky (speed at webo dot name) 2009-2010
 * @author Cedric Morin (cedric at yterium dot com) 2010-2012
 * @author Christopher Finke (cfinke at gmail.com) 2012
 */
/**
 * Defines ctype functions if required
 *
 * @version 1.0
 */
require_once('class.csstidy_ctype.php');

/**
 * Various CSS data needed for correct optimisations etc.
 *
 * @version 1.3
 */
require('data.inc.php');

/**
 * Contains a class for printing CSS code
 *
 * @version 1.0
 */
require('class.csstidy_print.php');

/**
 * Contains a class for optimising CSS code
 *
 * @version 1.0
 */
require('class.csstidy_optimise.php');

/**
 * CSS Parser class
 *

 * This class represents a CSS parser which reads CSS code and saves it in an array.
 * In opposite to most other CSS parsers, it does not use regular expressions and
 * thus has full CSS2 support and a higher reliability.
 * Additional to that it applies some optimisations and fixes to the CSS code.
 * An online version should be available here: http://cdburnerxp.se/cssparse/css_optimiser.php
 * @package csstidy
 * @author Florian Schmitz (floele at gmail dot com) 2005-2006
 * @version 1.4.0
 */
class csstidy {

	/**
	 * Saves the parsed CSS. This array is empty if preserve_css is on.
	 * @var array
	 * @access public
	 */
	var $css = array();
	/**
	 * Saves the parsed CSS (raw)
	 * @var array
	 * @access private
	 */
	var $tokens = array();
	/**
	 * Printer class
	 * @see csstidy_print
	 * @var object
	 * @access public
	 */
	var $print;
	/**
	 * Optimiser class
	 * @see csstidy_optimise
	 * @var object
	 * @access private
	 */
	var $optimise;
	/**
	 * Saves the CSS charset (@charset)
	 * @var string
	 * @access private
	 */
	var $charset = '';
	/**
	 * Saves all @import URLs
	 * @var array
	 * @access private
	 */
	var $import = array();
	/**
	 * Saves the namespace
	 * @var string
	 * @access private
	 */
	var $namespace = '';
	/**
	 * Contains the version of csstidy
	 * @var string
	 * @access private
	 */
	var $version = '1.3';
	/**
	 * Stores the settings
	 * @var array
	 * @access private
	 */
	var $settings = array();
	/**
	 * Saves the parser-status.
	 *
	 * Possible values:
	 * - is = in selector
	 * - ip = in property
	 * - iv = in value
	 * - instr = in string (started at " or ' or ( )
	 * - ic = in comment (ignore everything)
	 * - at = in @-block
	 *
	 * @var string
	 * @access private
	 */
	var $status = 'is';
	/**
	 * Saves the current at rule (@media)
	 * @var string
	 * @access private
	 */
	var $at = '';
	/**
	 * Saves the at rule for next selector (during @font-face or other @)
	 * @var string
	 * @access private
	 */
	var $next_selector_at = '';

	/**
	 * Saves the current selector
	 * @var string
	 * @access private
	 */
	var $selector = '';
	/**
	 * Saves the current property
	 * @var string
	 * @access private
	 */
	var $property = '';
	/**
	 * Saves the position of , in selectors
	 * @var array
	 * @access private
	 */
	var $sel_separate = array();
	/**
	 * Saves the current value
	 * @var string
	 * @access private
	 */
	var $value = '';
	/**
	 * Saves the current sub-value
	 *
	 * Example for a subvalue:
	 * background:url(foo.png) red no-repeat;
	 * "url(foo.png)", "red", and  "no-repeat" are subvalues,
	 * seperated by whitespace
	 * @var string
	 * @access private
	 */
	var $sub_value = '';
	/**
	 * Array which saves all subvalues for a property.
	 * @var array
	 * @see sub_value
	 * @access private
	 */
	var $sub_value_arr = array();
	/**
	 * Saves the stack of characters that opened the current strings
	 * @var array
	 * @access private
	 */
	var $str_char = array();
	var $cur_string = array();
	/**
	 * Status from which the parser switched to ic or instr
	 * @var array
	 * @access private
	 */
	var $from = array();
	/**
	/**
	 * =true if in invalid at-rule
	 * @var bool
	 * @access private
	 */
	var $invalid_at = false;
	/**
	 * =true if something has been added to the current selector
	 * @var bool
	 * @access private
	 */
	var $added = false;
	/**
	 * Array which saves the message log
	 * @var array
	 * @access private
	 */
	var $log = array();
	/**
	 * Saves the line number
	 * @var integer
	 * @access private
	 */
	var $line = 1;
	/**
	 * Marks if we need to leave quotes for a string
	 * @var array
	 * @access private
	 */
	var $quoted_string = array();

	/**
	 * List of tokens
	 * @var string
	 */
	var $tokens_list = "";
	/**
	 * Loads standard template and sets default settings
	 * @access private
	 * @version 1.3
	 */
	function csstidy() {
		$this->settings['remove_bslash'] = true;
		$this->settings['compress_colors'] = true;
		$this->settings['compress_font-weight'] = true;
		$this->settings['lowercase_s'] = false;
		/*
			1 common shorthands optimization
			2 + font property optimization
			3 + background property optimization
		 */
		$this->settings['optimise_shorthands'] = 1;
		$this->settings['remove_last_;'] = true;
		/* rewrite all properties with low case, better for later gzip OK, safe*/
		$this->settings['case_properties'] = 1;
		/* sort properties in alpabetic order, better for later gzip
		 * but can cause trouble in case of overiding same propertie or using hack
		 */
		$this->settings['sort_properties'] = false;
		/*
			1, 3, 5, etc -- enable sorting selectors inside @media: a{}b{}c{}
			2, 5, 8, etc -- enable sorting selectors inside one CSS declaration: a,b,c{}
			preserve order by default cause it can break functionnality
		 */
		$this->settings['sort_selectors'] = 0;
		/* is dangeroues to be used: CSS is broken sometimes */
		$this->settings['merge_selectors'] = 0;
		/* preserve or not browser hacks */
		$this->settings['discard_invalid_selectors'] = false;
		$this->settings['discard_invalid_properties'] = false;
		$this->settings['css_level'] = 'CSS3.0';
		$this->settings['preserve_css'] = false;
		$this->settings['timestamp'] = false;
		$this->settings['template'] = ''; // say that propertie exist
		$this->set_cfg('template','default'); // call load_template
		$this->optimise = new csstidy_optimise($this);

		$this->tokens_list = & $GLOBALS['csstidy']['tokens'];
	}

	/**
	 * Get the value of a setting.
	 * @param string $setting
	 * @access public
	 * @return mixed
	 * @version 1.0
	 */
	function get_cfg($setting) {
		if (isset($this->settings[$setting])) {
			return $this->settings[$setting];
		}
		return false;
	}

	/**
	 * Load a template
	 * @param string $template used by set_cfg to load a template via a configuration setting
	 * @access private
	 * @version 1.4
	 */
	function _load_template($template) {
		switch ($template) {
			case 'default':
				$this->load_template('default');
				break;

			case 'highest':
				$this->load_template('highest_compression');
				break;

			case 'high':
				$this->load_template('high_compression');
				break;

			case 'low':
				$this->load_template('low_compression');
				break;

			default:
				$this->load_template($template);
				break;
		}
	}

	/**
	 * Set the value of a setting.
	 * @param string $setting
	 * @param mixed $value
	 * @access public
	 * @return bool
	 * @version 1.0
	 */
	function set_cfg($setting, $value=null) {
		if (is_array($setting) && $value === null) {
			foreach ($setting as $setprop => $setval) {
				$this->settings[$setprop] = $setval;
			}
			if (array_key_exists('template', $setting)) {
				$this->_load_template($this->settings['template']);
			}
			return true;
		} elseif (isset($this->settings[$setting]) && $value !== '') {
			$this->settings[$setting] = $value;
			if ($setting === 'template') {
				$this->_load_template($this->settings['template']);
			}
			return true;
		}
		return false;
	}

	/**
	 * Adds a token to $this->tokens
	 * @param mixed $type
	 * @param string $data
	 * @param bool $do add a token even if preserve_css is off
	 * @access private
	 * @version 1.0
	 */
	function _add_token($type, $data, $do = false) {
		if ($this->get_cfg('preserve_css') || $do) {
			$this->tokens[] = array($type, ($type == COMMENT) ? $data : trim($data));
		}
	}

	/**
	 * Add a message to the message log
	 * @param string $message
	 * @param string $type
	 * @param integer $line
	 * @access private
	 * @version 1.0
	 */
	function log($message, $type, $line = -1) {
		if ($line === -1) {
			$line = $this->line;
		}
		$line = intval($line);
		$add = array('m' => $message, 't' => $type);
		if (!isset($this->log[$line]) || !in_array($add, $this->log[$line])) {
			$this->log[$line][] = $add;
		}
	}

	/**
	 * Parse unicode notations and find a replacement character
	 * @param string $string
	 * @param integer $i
	 * @access private
	 * @return string
	 * @version 1.2
	 */
	function _unicode(&$string, &$i) {
		++$i;
		$add = '';
		$replaced = false;

		while ($i < strlen($string) && (ctype_xdigit($string{$i}) || ctype_space($string{$i})) && strlen($add) < 6) {
			$add .= $string{$i};

			if (ctype_space($string{$i})) {
				break;
			}
			$i++;
		}

		if (hexdec($add) > 47 && hexdec($add) < 58 || hexdec($add) > 64 && hexdec($add) < 91 || hexdec($add) > 96 && hexdec($add) < 123) {
			$this->log('Replaced unicode notation: Changed \\' . $add . ' to ' . chr(hexdec($add)), 'Information');
			$add = chr(hexdec($add));
			$replaced = true;
		} else {
			$add = trim('\\' . $add);
		}

		if (@ctype_xdigit($string{$i + 1}) && ctype_space($string{$i})
						&& !$replaced || !ctype_space($string{$i})) {
			$i--;
		}

		if ($add !== '\\' || !$this->get_cfg('remove_bslash') || strpos($this->tokens_list, $string{$i + 1}) !== false) {
			return $add;
		}

		if ($add === '\\') {
			$this->log('Removed unnecessary backslash', 'Information');
		}
		return '';
	}

	/**
	 * Write formatted output to a file
	 * @param string $filename
	 * @param string $doctype when printing formatted, is a shorthand for the document type
	 * @param bool $externalcss when printing formatted, indicates whether styles to be attached internally or as an external stylesheet
	 * @param string $title when printing formatted, is the title to be added in the head of the document
	 * @param string $lang when printing formatted, gives a two-letter language code to be added to the output
	 * @access public
	 * @version 1.4
	 */
	function write_page($filename, $doctype='xhtml1.1', $externalcss=true, $title='', $lang='en') {
		$this->write($filename, true);
	}

	/**
	 * Write plain output to a file
	 * @param string $filename
	 * @param bool $formatted whether to print formatted or not
	 * @param string $doctype when printing formatted, is a shorthand for the document type
	 * @param bool $externalcss when printing formatted, indicates whether styles to be attached internally or as an external stylesheet
	 * @param string $title when printing formatted, is the title to be added in the head of the document
	 * @param string $lang when printing formatted, gives a two-letter language code to be added to the output
	 * @param bool $pre_code whether to add pre and code tags around the code (for light HTML formatted templates)
	 * @access public
	 * @version 1.4
	 */
	function write($filename, $formatted=false, $doctype='xhtml1.1', $externalcss=true, $title='', $lang='en', $pre_code=true) {
		$filename .= ( $formatted) ? '.xhtml' : '.css';

		if (!is_dir('temp')) {
			$madedir = mkdir('temp');
			if (!$madedir) {
				print 'Could not make directory "temp" in ' . dirname(__FILE__);
				exit;
			}
		}
		$handle = fopen('temp/' . $filename, 'w');
		if ($handle) {
			if (!$formatted) {
				fwrite($handle, $this->print->plain());
			} else {
				fwrite($handle, $this->print->formatted_page($doctype, $externalcss, $title, $lang, $pre_code));
			}
		}
		fclose($handle);
	}

	/**
	 * Loads a new template
	 * @param string $content either filename (if $from_file == true), content of a template file, "high_compression", "highest_compression", "low_compression", or "default"
	 * @param bool $from_file uses $content as filename if true
	 * @access public
	 * @version 1.1
	 * @see http://csstidy.sourceforge.net/templates.php
	 */
	function load_template($content, $from_file=true) {
		$predefined_templates = & $GLOBALS['csstidy']['predefined_templates'];
		if ($content === 'high_compression' || $content === 'default' || $content === 'highest_compression' || $content === 'low_compression') {
			$this->template = $predefined_templates[$content];
			return;
		}


		if ($from_file) {
			$content = strip_tags(file_get_contents($content), '<span>');
		}
		$content = str_replace("\r\n", "\n", $content); // Unify newlines (because the output also only uses \n)
		$template = explode('|', $content);

		for ($i = 0; $i < count($template); $i++) {
			$this->template[$i] = $template[$i];
		}
	}

	/**
	 * Starts parsing from URL
	 * @param string $url
	 * @access public
	 * @version 1.0
	 */
	function parse_from_url($url) {
		return $this->parse(@file_get_contents($url));
	}

	/**
	 * Checks if there is a token at the current position
	 * @param string $string
	 * @param integer $i
	 * @access public
	 * @version 1.11
	 */
	function is_token(&$string, $i) {
		return (strpos($this->tokens_list, $string{$i}) !== false && !csstidy::escaped($string, $i));
	}

	/**
	 * Parses CSS in $string. The code is saved as array in $this->css
	 * @param string $string the CSS code
	 * @access public
	 * @return bool
	 * @version 1.1
	 */
	function parse($string) {
		// Temporarily set locale to en_US in order to handle floats properly
		$old = @setlocale(LC_ALL, 0);
		@setlocale(LC_ALL, 'C');

		// PHP bug? Settings need to be refreshed in PHP4
		$this->print = new csstidy_print($this);
		$this->optimise = new csstidy_optimise($this);

		$all_properties = & $GLOBALS['csstidy']['all_properties'];
		$at_rules = & $GLOBALS['csstidy']['at_rules'];
		$quoted_string_properties = & $GLOBALS['csstidy']['quoted_string_properties'];

		$this->css = array();
		$this->print->input_css = $string;
		$string = str_replace("\r\n", "\n", $string) . ' ';
		$cur_comment = '';

		for ($i = 0, $size = strlen($string); $i < $size; $i++) {
			if ($string{$i} === "\n" || $string{$i} === "\r") {
				++$this->line;
			}

			switch ($this->status) {
				/* Case in at-block */
				case 'at':
					if (csstidy::is_token($string, $i)) {
						if ($string{$i} === '/' && @$string{$i + 1} === '*') {
							$this->status = 'ic';
							++$i;
							$this->from[] = 'at';
						} elseif ($string{$i} === '{') {
							$this->status = 'is';
							$this->at = $this->css_new_media_section($this->at);
							$this->_add_token(AT_START, $this->at);
						} elseif ($string{$i} === ',') {
							$this->at = trim($this->at) . ',';
						} elseif ($string{$i} === '\\') {
							$this->at .= $this->_unicode($string, $i);
						}
						// fix for complicated media, i.e @media screen and (-webkit-min-device-pixel-ratio:1.5)
						elseif (in_array($string{$i}, array('(', ')', ':', '.'))) {
							$this->at .= $string{$i};
						}
					} else {
						$lastpos = strlen($this->at) - 1;
						if (!( (ctype_space($this->at{$lastpos}) || csstidy::is_token($this->at, $lastpos) && $this->at{$lastpos} === ',') && ctype_space($string{$i}))) {
							$this->at .= $string{$i};
						}
					}
					break;

				/* Case in-selector */
				case 'is':
					if (csstidy::is_token($string, $i)) {
						if ($string{$i} === '/' && @$string{$i + 1} === '*' && trim($this->selector) == '') {
							$this->status = 'ic';
							++$i;
							$this->from[] = 'is';
						} elseif ($string{$i} === '@' && trim($this->selector) == '') {
							// Check for at-rule
							$this->invalid_at = true;
							foreach ($at_rules as $name => $type) {
								if (!strcasecmp(substr($string, $i + 1, strlen($name)), $name)) {
									($type === 'at') ? $this->at = '@' . $name : $this->selector = '@' . $name;
									if ($type === "atis") {
										$this->next_selector_at = ($this->next_selector_at?$this->next_selector_at:($this->at?$this->at:DEFAULT_AT));
										$this->at = $this->css_new_media_section(' ');
										$type = "is";
									}
									$this->status = $type;
									$i += strlen($name);
									$this->invalid_at = false;
								}
							}

							if ($this->invalid_at) {
								$this->selector = '@';
								$invalid_at_name = '';
								for ($j = $i + 1; $j < $size; ++$j) {
									if (!ctype_alpha($string{$j})) {
										break;
									}
									$invalid_at_name .= $string{$j};
								}
								$this->log('Invalid @-rule: ' . $invalid_at_name . ' (removed)', 'Warning');
							}
						} elseif (($string{$i} === '"' || $string{$i} === "'")) {
							$this->cur_string[] = $string{$i};
							$this->status = 'instr';
							$this->str_char[] = $string{$i};
							$this->from[] = 'is';
							/* fixing CSS3 attribute selectors, i.e. a[href$=".mp3" */
							$this->quoted_string[] = ($string{$i - 1} == '=' );
						} elseif ($this->invalid_at && $string{$i} === ';') {
							$this->invalid_at = false;
							$this->status = 'is';
							if ($this->next_selector_at) {
								$this->at = $this->css_new_media_section($this->next_selector_at);
								$this->next_selector_at = '';
							}
						} elseif ($string{$i} === '{') {
							$this->status = 'ip';
							if ($this->at == '') {
								$this->at = $this->css_new_media_section(DEFAULT_AT);
							}
							$this->selector = $this->css_new_selector($this->at,$this->selector);
							$this->_add_token(SEL_START, $this->selector);
							$this->added = false;
						} elseif ($string{$i} === '}') {
							$this->_add_token(AT_END, $this->at);
							$this->at = '';
							$this->selector = '';
							$this->sel_separate = array();
						} elseif ($string{$i} === ',') {
							$this->selector = trim($this->selector) . ',';
							$this->sel_separate[] = strlen($this->selector);
						} elseif ($string{$i} === '\\') {
							$this->selector .= $this->_unicode($string, $i);
						} elseif ($string{$i} === '*' && @in_array($string{$i + 1}, array('.', '#', '[', ':'))) {
							// remove unnecessary universal selector, FS#147
						} else {
							$this->selector .= $string{$i};
						}
					} else {
						$lastpos = strlen($this->selector) - 1;
						if ($lastpos == -1 || !( (ctype_space($this->selector{$lastpos}) || csstidy::is_token($this->selector, $lastpos) && $this->selector{$lastpos} === ',') && ctype_space($string{$i}))) {
							$this->selector .= $string{$i};
						}
					}
					break;

				/* Case in-property */
				case 'ip':
					if (csstidy::is_token($string, $i)) {
						if (($string{$i} === ':' || $string{$i} === '=') && $this->property != '') {
							$this->status = 'iv';
							if (!$this->get_cfg('discard_invalid_properties') || csstidy::property_is_valid($this->property)) {
								$this->property = $this->css_new_property($this->at,$this->selector,$this->property);
								$this->_add_token(PROPERTY, $this->property);
							}
						} elseif ($string{$i} === '/' && @$string{$i + 1} === '*' && $this->property == '') {
							$this->status = 'ic';
							++$i;
							$this->from[] = 'ip';
						} elseif ($string{$i} === '}') {
							$this->explode_selectors();
							$this->status = 'is';
							$this->invalid_at = false;
							$this->_add_token(SEL_END, $this->selector);
							$this->selector = '';
							$this->property = '';
							if ($this->next_selector_at) {
								$this->at = $this->css_new_media_section($this->next_selector_at);
								$this->next_selector_at = '';
							}
						} elseif ($string{$i} === ';') {
							$this->property = '';
						} elseif ($string{$i} === '\\') {
							$this->property .= $this->_unicode($string, $i);
						}
						// else this is dumb IE a hack, keep it
						// including //
						elseif (($this->property=='' AND !ctype_space($string{$i}))
							OR ($this->property=='/' OR $string{$i}=="/")) {
							$this->property .= $string{$i};
						}
					} elseif (!ctype_space($string{$i})) {
						$this->property .= $string{$i};
					}
					break;

				/* Case in-value */
				case 'iv':
					$pn = (($string{$i} === "\n" || $string{$i} === "\r") && $this->property_is_next($string, $i + 1) || $i == strlen($string) - 1);
					if (csstidy::is_token($string, $i) || $pn) {
						if ($string{$i} === '/' && @$string{$i + 1} === '*') {
							$this->status = 'ic';
							++$i;
							$this->from[] = 'iv';
						} elseif (($string{$i} === '"' || $string{$i} === "'" || $string{$i} === '(')) {
							$this->cur_string[] = $string{$i};
							$this->str_char[] = ($string{$i} === '(') ? ')' : $string{$i};
							$this->status = 'instr';
							$this->from[] = 'iv';
							$this->quoted_string[] = in_array(strtolower($this->property), $quoted_string_properties);
						} elseif ($string{$i} === ',') {
							$this->sub_value = trim($this->sub_value) . ',';
						} elseif ($string{$i} === '\\') {
							$this->sub_value .= $this->_unicode($string, $i);
						} elseif ($string{$i} === ';' || $pn) {
							if ($this->selector{0} === '@' && isset($at_rules[substr($this->selector, 1)]) && $at_rules[substr($this->selector, 1)] === 'iv') {
								/* Add quotes to charset, import, namespace */
								$this->sub_value_arr[] = trim($this->sub_value);

								$this->status = 'is';

								switch ($this->selector) {
									case '@charset': $this->charset = '"'.$this->sub_value_arr[0].'"';
										break;
									case '@namespace': $this->namespace = implode(' ', $this->sub_value_arr);
										break;
									case '@import': $this->import[] = implode(' ', $this->sub_value_arr);
										break;
								}

								$this->sub_value_arr = array();
								$this->sub_value = '';
								$this->selector = '';
								$this->sel_separate = array();
							} else {
								$this->status = 'ip';
							}
						} elseif ($string{$i} !== '}') {
							$this->sub_value .= $string{$i};
						}
						if (($string{$i} === '}' || $string{$i} === ';' || $pn) && !empty($this->selector)) {
							if ($this->at == '') {
								$this->at = $this->css_new_media_section(DEFAULT_AT);
							}

							// case settings
							if ($this->get_cfg('lowercase_s')) {
								$this->selector = strtolower($this->selector);
							}
							$this->property = strtolower($this->property);

							$this->optimise->subvalue();
							if ($this->sub_value != '') {
								$this->sub_value_arr[] = $this->sub_value;
								$this->sub_value = '';
							}

							$this->value = '';
							while (count($this->sub_value_arr)) {
								$sub = array_shift($this->sub_value_arr);
								if (strstr($this->selector, "font-face")) {
									$sub = $this->quote_font_format($sub);
								}

								if ($sub != '')
									$this->value .= ((!strlen($this->value) OR substr($this->value,-1,1)==',')?'':' ').$sub;
							}

							$this->optimise->value();

							$valid = csstidy::property_is_valid($this->property);
							if ((!$this->invalid_at || $this->get_cfg('preserve_css')) && (!$this->get_cfg('discard_invalid_properties') || $valid)) {
								$this->css_add_property($this->at, $this->selector, $this->property, $this->value);
								$this->_add_token(VALUE, $this->value);
								$this->optimise->shorthands();
							}
							if (!$valid) {
								if ($this->get_cfg('discard_invalid_properties')) {
									$this->log('Removed invalid property: ' . $this->property, 'Warning');
								} else {
									$this->log('Invalid property in ' . strtoupper($this->get_cfg('css_level')) . ': ' . $this->property, 'Warning');
								}
							}

							$this->property = '';
							$this->sub_value_arr = array();
							$this->value = '';
						}
						if ($string{$i} === '}') {
							$this->explode_selectors();
							$this->_add_token(SEL_END, $this->selector);
							$this->status = 'is';
							$this->invalid_at = false;
							$this->selector = '';
							if ($this->next_selector_at) {
								$this->at = $this->css_new_media_section($this->next_selector_at);
								$this->next_selector_at = '';
							}
						}
					} elseif (!$pn) {
						$this->sub_value .= $string{$i};

						if (ctype_space($string{$i})) {
							$this->optimise->subvalue();
							if ($this->sub_value != '') {
								$this->sub_value_arr[] = $this->sub_value;
								$this->sub_value = '';
							}
						}
					}
					break;

				/* Case in string */
				case 'instr':
					$_str_char = $this->str_char[count($this->str_char)-1];
					$_cur_string = $this->cur_string[count($this->cur_string)-1];
					$_quoted_string = $this->quoted_string[count($this->quoted_string)-1];
					$temp_add = $string{$i};

					// Add another string to the stack. Strings can't be nested inside of quotes, only parentheses, but
					// parentheticals can be nested more than once.
					if ($_str_char === ")" && ($string{$i} === "(" || $string{$i} === '"' || $string{$i} === '\'') && !csstidy::escaped($string, $i)) {
						$this->cur_string[] = $string{$i};
						$this->str_char[] = $string{$i} == "(" ? ")" : $string{$i};
						$this->from[] = 'instr';
						$this->quoted_string[] = ($_str_char === ")" AND $string{$i} !== "(" AND trim($_cur_string)=="(")?$_quoted_string:!($string{$i} === "(");
						continue;
					}

					if ($_str_char !== ")" && ($string{$i} === "\n" || $string{$i} === "\r") && !($string{$i - 1} === '\\' && !csstidy::escaped($string, $i - 1))) {
						$temp_add = "\\A";
						$this->log('Fixed incorrect newline in string', 'Warning');
					}

					$_cur_string .= $temp_add;

					if ($string{$i} === $_str_char && !csstidy::escaped($string, $i)) {
						$this->status = array_pop($this->from);

						if (!preg_match('|[' . implode('', $GLOBALS['csstidy']['whitespace']) . ']|uis', $_cur_string) && $this->property !== 'content') {
							if (!$_quoted_string) {
								if ($_str_char !== ')') {
									// Convert properties like
									// font-family: 'Arial';
									// to
									// font-family: Arial;
									// or
									// url("abc")
									// to
									// url(abc)
									$_cur_string = substr($_cur_string, 1, -1);
								}
							} else {
								$_quoted_string = false;
							}
						}

						array_pop($this->cur_string);
						array_pop($this->quoted_string);
						array_pop($this->str_char);

						if ($_str_char === ")") {
							$_cur_string = "(" . trim(substr($_cur_string, 1, -1)) . ")";
						}

						if ($this->status === 'iv') {
							if (!$_quoted_string) {
								if (strpos($_cur_string,',')!==false)
									// we can on only remove space next to ','
									$_cur_string = implode(',',array_map('trim',explode(',',$_cur_string)));
								// and multiple spaces (too expensive)
								if (strpos($_cur_string,'  ')!==false)
									$_cur_string = preg_replace(",\s+,"," ",$_cur_string);
							}
							$this->sub_value .= $_cur_string;
						} elseif ($this->status === 'is') {
							$this->selector .= $_cur_string;
						} elseif ($this->status === 'instr') {
							$this->cur_string[count($this->cur_string)-1] .= $_cur_string;
						}
					} else {
						$this->cur_string[count($this->cur_string)-1] = $_cur_string;
					}
					break;

				/* Case in-comment */
				case 'ic':
					if ($string{$i} === '*' && $string{$i + 1} === '/') {
						$this->status = array_pop($this->from);
						$i++;
						$this->_add_token(COMMENT, $cur_comment);
						$cur_comment = '';
					} else {
						$cur_comment .= $string{$i};
					}
					break;
			}
		}

		$this->optimise->postparse();

		$this->print->_reset();

		@setlocale(LC_ALL, $old); // Set locale back to original setting

		return!(empty($this->css) && empty($this->import) && empty($this->charset) && empty($this->tokens) && empty($this->namespace));
	}


	/**
	 * format() in font-face needs quoted values for somes browser (FF at least)
	 *
	 * @param $value
	 * @return string
	 */
	function quote_font_format($value) {
		if (strncmp($value,'format',6)==0) {
			$p = strrpos($value,")");
			$end = substr($value,$p);
			$format_strings = csstidy::parse_string_list(substr($value, 7, $p-7));
			if (!$format_strings) {
				$value = "";
			} else {
				$value = "format(";

				foreach ($format_strings as $format_string) {
					$value .= '"' . str_replace('"', '\\"', $format_string) . '",';
				}

				$value = substr($value, 0, -1) . $end;
			}
		}
		return $value;
	}

	/**
	 * Explodes selectors
	 * @access private
	 * @version 1.0
	 */
	function explode_selectors() {
		// Explode multiple selectors
		if ($this->get_cfg('merge_selectors') === 1) {
			$new_sels = array();
			$lastpos = 0;
			$this->sel_separate[] = strlen($this->selector);
			foreach ($this->sel_separate as $num => $pos) {
				if ($num == count($this->sel_separate) - 1) {
					$pos += 1;
				}

				$new_sels[] = substr($this->selector, $lastpos, $pos - $lastpos - 1);
				$lastpos = $pos;
			}

			if (count($new_sels) > 1) {
				foreach ($new_sels as $selector) {
					if (isset($this->css[$this->at][$this->selector])) {
						$this->merge_css_blocks($this->at, $selector, $this->css[$this->at][$this->selector]);
					}
				}
				unset($this->css[$this->at][$this->selector]);
			}
		}
		$this->sel_separate = array();
	}

	/**
	 * Checks if a character is escaped (and returns true if it is)
	 * @param string $string
	 * @param integer $pos
	 * @access public
	 * @return bool
	 * @version 1.02
	 */
	static function escaped(&$string, $pos) {
		return!(@($string{$pos - 1} !== '\\') || csstidy::escaped($string, $pos - 1));
	}

	/**
	 * Adds a property with value to the existing CSS code
	 * @param string $media
	 * @param string $selector
	 * @param string $property
	 * @param string $new_val
	 * @access private
	 * @version 1.2
	 */
	function css_add_property($media, $selector, $property, $new_val) {
		if ($this->get_cfg('preserve_css') || trim($new_val) == '') {
			return;
		}

		$this->added = true;
		if (isset($this->css[$media][$selector][$property])) {
			if ((csstidy::is_important($this->css[$media][$selector][$property]) && csstidy::is_important($new_val)) || !csstidy::is_important($this->css[$media][$selector][$property])) {
				$this->css[$media][$selector][$property] = trim($new_val);
			}
		} else {
			$this->css[$media][$selector][$property] = trim($new_val);
		}
	}

	/**
	 * Start a new media section.
	 * Check if the media is not already known,
	 * else rename it with extra spaces
	 * to avoid merging
	 *
	 * @param string $media
	 * @return string
	 */
	function css_new_media_section($media) {
		if ($this->get_cfg('preserve_css')) {
			return $media;
		}

		// if the last @media is the same as this
		// keep it
		if (!$this->css OR !is_array($this->css) OR empty($this->css)) {
			return $media;
		}
		end($this->css);
		list($at,) = each($this->css);
		if ($at == $media) {
			return $media;
		}
		while (isset($this->css[$media]))
			if (is_numeric($media))
				$media++;
			else
				$media .= " ";
		return $media;
	}

	/**
	 * Start a new selector.
	 * If already referenced in this media section,
	 * rename it with extra space to avoid merging
	 * except if merging is required,
	 * or last selector is the same (merge siblings)
	 *
	 * never merge @font-face
	 *
	 * @param string $media
	 * @param string $selector
	 * @return string
	 */
	function css_new_selector($media,$selector) {
		if ($this->get_cfg('preserve_css')) {
			return $selector;
		}
		$selector = trim($selector);
		if (strncmp($selector,"@font-face",10)!=0) {
			if ($this->settings['merge_selectors'] != false)
				return $selector;

			if (!$this->css OR !isset($this->css[$media]) OR !$this->css[$media])
				return $selector;

			// if last is the same, keep it
			end($this->css[$media]);
			list($sel,) = each($this->css[$media]);
			if ($sel == $selector) {
				return $selector;
			}
		}

		while (isset($this->css[$media][$selector]))
			$selector .= " ";
		return $selector;
	}

	/**
	 * Start a new propertie.
	 * If already references in this selector,
	 * rename it with extra space to avoid override
	 *
	 * @param string $media
	 * @param string $selector
	 * @param string $property
	 * @return string
	 */
	function css_new_property($media, $selector, $property) {
		if ($this->get_cfg('preserve_css')) {
			return $property;
		}
		if (!$this->css OR !isset($this->css[$media][$selector]) OR !$this->css[$media][$selector])
			return $property;

		while (isset($this->css[$media][$selector][$property]))
			$property .= " ";

		return $property;
	}

	/**
	 * Adds CSS to an existing media/selector
	 * @param string $media
	 * @param string $selector
	 * @param array $css_add
	 * @access private
	 * @version 1.1
	 */
	function merge_css_blocks($media, $selector, $css_add) {
		foreach ($css_add as $property => $value) {
			$this->css_add_property($media, $selector, $property, $value, false);
		}
	}

	/**
	 * Checks if $value is !important.
	 * @param string $value
	 * @return bool
	 * @access public
	 * @version 1.0
	 */
	static function is_important(&$value) {
		return (
			strpos($value,"!")!==false // quick test
			AND !strcasecmp(substr(str_replace($GLOBALS['csstidy']['whitespace'], '', $value), -10, 10), '!important'));
	}

	/**
	 * Returns a value without !important
	 * @param string $value
	 * @return string
	 * @access public
	 * @version 1.0
	 */
	static function gvw_important($value) {
		if (csstidy::is_important($value)) {
			$value = trim($value);
			$value = substr($value, 0, -9);
			$value = trim($value);
			$value = substr($value, 0, -1);
			$value = trim($value);
			return $value;
		}
		return $value;
	}

	/**
	 * Checks if the next word in a string from pos is a CSS property
	 * @param string $istring
	 * @param integer $pos
	 * @return bool
	 * @access private
	 * @version 1.2
	 */
	function property_is_next($istring, $pos) {
		$all_properties = & $GLOBALS['csstidy']['all_properties'];
		$istring = substr($istring, $pos, strlen($istring) - $pos);
		$pos = strpos($istring, ':');
		if ($pos === false) {
			return false;
		}
		$istring = strtolower(trim(substr($istring, 0, $pos)));
		if (isset($all_properties[$istring])) {
			$this->log('Added semicolon to the end of declaration', 'Warning');
			return true;
		}
		return false;
	}

	/**
	 * Checks if a property is valid
	 * @param string $property
	 * @return bool;
	 * @access public
	 * @version 1.0
	 */
	function property_is_valid($property) {
		if (in_array(trim($property), $GLOBALS['csstidy']['multiple_properties'])) $property = trim($property);
		$all_properties = & $GLOBALS['csstidy']['all_properties'];
		return (isset($all_properties[$property]) && strpos($all_properties[$property], strtoupper($this->get_cfg('css_level'))) !== false );
	}

	/**
	 * Accepts a list of strings (e.g., the argument to format() in a @font-face src property)
	 * and returns a list of the strings.  Converts things like:
	 *
	 * format(abc) => format("abc")
	 * format(abc def) => format("abc","def")
	 * format(abc "def") => format("abc","def")
	 * format(abc, def, ghi) => format("abc","def","ghi")
	 * format("abc",'def') => format("abc","def")
	 * format("abc, def, ghi") => format("abc, def, ghi")
	 *
	 * @param string
	 * @return array
	 */

	function parse_string_list($value) {
		$value = trim($value);

		// Case: empty
		if (!$value) return array();

		$strings = array();

		$in_str = false;
		$current_string = "";

		for ($i = 0, $_len = strlen($value); $i < $_len; $i++) {
			if (($value{$i} == "," || $value{$i} === " ") && $in_str === true) {
				$in_str = false;
				$strings[] = $current_string;
				$current_string = "";
			} elseif ($value{$i} == '"' || $value{$i} == "'") {
				if ($in_str === $value{$i}) {
					$strings[] = $current_string;
					$in_str = false;
					$current_string = "";
					continue;
				} elseif (!$in_str) {
					$in_str = $value{$i};
				}
			} else {
				if ($in_str) {
					$current_string .= $value{$i};
				} else {
					if (!preg_match("/[\s,]/", $value{$i})) {
						$in_str = true;
						$current_string = $value{$i};
					}
				}
			}
		}

		if ($current_string) {
			$strings[] = $current_string;
		}

		return $strings;
	}
}
