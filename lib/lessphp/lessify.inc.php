<?php
/**
 * lessify
 * Convert a css file into a less file
 * http://leafo.net/lessphp
 * Copyright 2010, leaf corcoran <leafot@gmail.com>
 *
 * WARNING: THIS DOES NOT WORK ANYMORE. NEEDS TO BE UPDATED FOR
 * LATEST VERSION OF LESSPHP.
 *
 */

require "lessc.inc.php";

//
// check if the merge during mixin is overwriting values. should or should it not?
//

//
// 1. split apart class tags
//

class easyparse {
	var $buffer;
	var $count;

	function __construct($str) {
		$this->count = 0;
		$this->buffer = trim($str);
	}

	function seek($where = null) {
		if ($where === null) return $this->count;
		else $this->count = $where;
		return true;
	}

	function preg_quote($what) {
		return preg_quote($what, '/');
	}

	function match($regex, &$out, $eatWhitespace = true) {
		$r = '/'.$regex.($eatWhitespace ? '\s*' : '').'/Ais';
		if (preg_match($r, $this->buffer, $out, null, $this->count)) {
			$this->count += strlen($out[0]);
			return true;
		}
		return false;
	}

	function literal($what, $eatWhitespace = true) {
		// this is here mainly prevent notice from { } string accessor 
		if ($this->count >= strlen($this->buffer)) return false;

		// shortcut on single letter
		if (!$eatWhitespace and strlen($what) == 1) {
			if ($this->buffer{$this->count} == $what) {
				$this->count++;
				return true;
			}
			else return false;
		}

		return $this->match($this->preg_quote($what), $m, $eatWhitespace);
	}

}

class tagparse extends easyparse {
	static private $combinators = null;
	static private $match_opts = null;

	function parse() {
		if (empty(self::$combinators)) {
			self::$combinators = '('.implode('|', array_map(array($this, 'preg_quote'),
				array('+', '>', '~'))).')';
			self::$match_opts = '('.implode('|', array_map(array($this, 'preg_quote'),
				array('=', '~=', '|=', '$=', '*='))).')';
		}

		// crush whitespace
		$this->buffer = preg_replace('/\s+/', ' ', $this->buffer).' ';

		$tags = array();
		while ($this->tag($t)) $tags[] = $t;

		return $tags;
	}

	static function compileString($string) {
		list(, $delim, $str) = $string;
		$str = str_replace($delim, "\\".$delim, $str);
		$str = str_replace("\n", "\\\n", $str);
		return $delim.$str.$delim;
	}

	static function compilePaths($paths) {
		return implode(', ', array_map(array('self', 'compilePath'), $paths));
	}

	// array of tags
	static function compilePath($path) {
		return implode(' ', array_map(array('self', 'compileTag'), $path));
	}


	static function compileTag($tag) {
		ob_start();
		if (isset($tag['comb'])) echo $tag['comb']." ";
		if (isset($tag['front'])) echo $tag['front'];
		if (isset($tag['attr'])) {
			echo '['.$tag['attr'];
			if (isset($tag['op'])) {
				echo $tag['op'].$tag['op_value'];
			}
			echo ']';
		}
		return ob_get_clean();
	}

	function string(&$out) {
		$s = $this->seek();

		if ($this->literal('"')) {
			$delim = '"';
		} elseif ($this->literal("'")) {
			$delim = "'";
		} else {
			return false;
		}

		while (true) {
			// step through letters looking for either end or escape
			$buff = "";
			$escapeNext = false;
			$finished = false;
			for ($i = $this->count; $i < strlen($this->buffer); $i++) {
				$char = $this->buffer[$i];
				switch ($char) {
				case $delim:
					if ($escapeNext) {
						$buff .= $char;
						$escapeNext = false;
						break;
					}
					$finished = true;
					break 2;
				case "\\":
					if ($escapeNext) {
						$buff .= $char;
						$escapeNext = false;
					} else {
						$escapeNext = true;
					}
					break;
				case "\n":
					if (!$escapeNext) {
						break 3;
					}
					
					$buff .= $char;
					$escapeNext = false;
					break;
				default:
					if ($escapeNext) {
						$buff .= "\\";
						$escapeNext = false;
					}
					$buff .= $char;
				}
			}
			if (!$finished) break;
			$out = array('string', $delim, $buff);
			$this->seek($i+1);
			return true;
		}

		$this->seek($s);
		return false;
	}

	function tag(&$out) {
		$s = $this->seek();
		$tag = array();
		if ($this->combinator($op)) $tag['comb'] = $op;

		if (!$this->match('(.*?)( |$|\[|'.self::$combinators.')', $match)) {
			$this->seek($s);
			return false;
		}

		if (!empty($match[3])) {
			// give back combinator
			$this->count-=strlen($match[3]);
		}

		if (!empty($match[1])) $tag['front'] = $match[1];

		if ($match[2] == '[') {
			if ($this->ident($i)) {
				$tag['attr'] = $i;

				if ($this->match(self::$match_opts, $m) && $this->value($v)) {
					$tag['op'] = $m[1];
					$tag['op_value'] = $v;
				}

				if ($this->literal(']')) {
					$out = $tag;
					return true;
				}
			}
		} elseif (isset($tag['front'])) {
			$out = $tag;
			return true;
		}

		$this->seek($s);
		return false;
	}

	function ident(&$out) {
		// [-]?{nmstart}{nmchar}*
		// nmstart: [_a-z]|{nonascii}|{escape}
		// nmchar: [_a-z0-9-]|{nonascii}|{escape}
		if ($this->match('(-?[_a-z][_\w]*)', $m)) {
			$out = $m[1];
			return true;
		}
		return false;
	}

	function value(&$out) {
		if ($this->string($str)) {
			$out = $this->compileString($str);
			return true;
		} elseif ($this->ident($id)) {
			$out = $id;
			return true;
		}
		return false;
	}


	function combinator(&$op) {
		if ($this->match(self::$combinators, $m)) {
			$op = $m[1];
			return true;
		}
		return false;
	}
}

class nodecounter {
	var $count = 0;
	var $children = array();

	var $name;
	var $child_blocks;
	var $the_block;

	function __construct($name) {
		$this->name = $name;
	}

	function dump($stack = null) {
		if (is_null($stack)) $stack = array();
		$stack[] = $this->getName();
		echo implode(' -> ', $stack)." ($this->count)\n";
		foreach ($this->children as $child) {
			$child->dump($stack);
		}
	}

	static function compileProperties($c, $block) {
		foreach($block as $name => $value) {
			if ($c->isProperty($name, $value)) {
				echo $c->compileProperty($name, $value)."\n";
			}
		}
	}

	function compile($c, $path = null) {
		if (is_null($path)) $path = array();
		$path[] = $this->name;

		$isVisible = !is_null($this->the_block) || !is_null($this->child_blocks);

		if ($isVisible) {
			echo $c->indent(implode(' ', $path).' {');
			$c->indentLevel++;
			$path = array();

			if ($this->the_block) {
				$this->compileProperties($c, $this->the_block);
			}

			if ($this->child_blocks) {
				foreach ($this->child_blocks as $block) {
					echo $c->indent(tagparse::compilePaths($block['__tags']).' {');
					$c->indentLevel++;
					$this->compileProperties($c, $block);
					$c->indentLevel--;
					echo $c->indent('}');
				}
			}
		}

		// compile child nodes
		foreach($this->children as $node) {
			$node->compile($c, $path);
		}

		if ($isVisible) {
			$c->indentLevel--;
			echo $c->indent('}');
		}

	}

	function getName() {
		if (is_null($this->name)) return "[root]";
		else return $this->name;
	}

	function getNode($name) {
		if (!isset($this->children[$name])) {
			$this->children[$name] = new nodecounter($name);
		}

		return $this->children[$name];
	}

	function findNode($path) {
		$current = $this;
		for ($i = 0; $i < count($path); $i++) {
			$t = tagparse::compileTag($path[$i]);
			$current = $current->getNode($t);
		}

		return $current;
	}

	function addBlock($path, $block) {
		$node = $this->findNode($path);
		if (!is_null($node->the_block)) throw new exception("can this happen?");

		unset($block['__tags']);
		$node->the_block = $block;
	}

	function addToNode($path, $block) {
		$node = $this->findNode($path);
		$node->child_blocks[] = $block;
	}
}

/**
 * create a less file from a css file by combining blocks where appropriate
 */
class lessify extends lessc {
	public function dump() {
		print_r($this->env);
	}

	public function parse($str = null) {
		$this->prepareParser($str ? $str : $this->buffer);
		while (false !== $this->parseChunk());

		$root = new nodecounter(null);

		// attempt to preserve some of the block order
		$order = array();

		$visitedTags = array();
		foreach (end($this->env) as $name => $block) {
			if (!$this->isBlock($name, $block)) continue;
			if (isset($visitedTags[$name])) continue;

			foreach ($block['__tags'] as $t) {
				$visitedTags[$t] = true;
			}

			// skip those with more than 1
			if (count($block['__tags']) == 1) {
				$p = new tagparse(end($block['__tags']));
				$path = $p->parse();
				$root->addBlock($path, $block);
				$order[] = array('compressed', $path, $block);
				continue;
			} else {
				$common = null;
				$paths = array();
				foreach ($block['__tags'] as $rawtag) {
					$p = new tagparse($rawtag);
					$paths[] = $path = $p->parse();
					if (is_null($common)) $common = $path;
					else {
						$new_common = array();
						foreach ($path as $tag) {
							$head = array_shift($common);
							if ($tag == $head) {
								$new_common[] = $head;
							} else break;
						}
						$common = $new_common;
						if (empty($common)) {
							// nothing in common
							break;
						}
					}
				}

				if (!empty($common)) {
					$new_paths = array();
					foreach ($paths as $p) $new_paths[] = array_slice($p, count($common));
					$block['__tags'] = $new_paths;
					$root->addToNode($common, $block);
					$order[] = array('compressed', $common, $block);
					continue;
				}
				
			}

			$order[] = array('none', $block['__tags'], $block);
		}


		$compressed = $root->children;
		foreach ($order as $item) {
			list($type, $tags, $block) = $item;
			if ($type == 'compressed') {
				$top = tagparse::compileTag(reset($tags));
				if (isset($compressed[$top])) {
					$compressed[$top]->compile($this);
					unset($compressed[$top]);
				}
			} else {
				echo $this->indent(implode(', ', $tags).' {');
				$this->indentLevel++;
				nodecounter::compileProperties($this, $block);
				$this->indentLevel--;
				echo $this->indent('}');
			}
		}
	}
}
