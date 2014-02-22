<?php
/**
 * SCSS compiler written in PHP
 *
 * @copyright 2012-2013 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/gpl-license GPL-3.0
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://leafo.net/scssphp
 */

/**
 * The scss compiler and parser.
 *
 * Converting SCSS to CSS is a three stage process. The incoming file is parsed
 * by `scssc_parser` into a syntax tree, then it is compiled into another tree
 * representing the CSS structure by `scssc`. The CSS tree is fed into a
 * formatter, like `scssc_formatter` which then outputs CSS as a string.
 *
 * During the first compile, all values are *reduced*, which means that their
 * types are brought to the lowest form before being dump as strings. This
 * handles math equations, variable dereferences, and the like.
 *
 * The `parse` function of `scssc` is the entry point.
 *
 * In summary:
 *
 * The `scssc` class creates an instance of the parser, feeds it SCSS code,
 * then transforms the resulting tree to a CSS tree. This class also holds the
 * evaluation context, such as all available mixins and variables at any given
 * time.
 *
 * The `scssc_parser` class is only concerned with parsing its input.
 *
 * The `scssc_formatter` takes a CSS tree, and dumps it to a formatted string,
 * handling things like indentation.
 */

/**
 * SCSS compiler
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class scssc {
	static public $VERSION = "v0.0.9";

	static protected $operatorNames = array(
		'+' => "add",
		'-' => "sub",
		'*' => "mul",
		'/' => "div",
		'%' => "mod",

		'==' => "eq",
		'!=' => "neq",
		'<' => "lt",
		'>' => "gt",

		'<=' => "lte",
		'>=' => "gte",
	);

	static protected $namespaces = array(
		"special" => "%",
		"mixin" => "@",
		"function" => "^",
	);

	static protected $unitTable = array(
		"in" => array(
			"in" => 1,
			"pt" => 72,
			"pc" => 6,
			"cm" => 2.54,
			"mm" => 25.4,
			"px" => 96,
		)
	);

	static public $true = array("keyword", "true");
	static public $false = array("keyword", "false");
	static public $null = array("null");

	static public $defaultValue = array("keyword", "");
	static public $selfSelector = array("self");

	protected $importPaths = array("");
	protected $importCache = array();

	protected $userFunctions = array();

	protected $numberPrecision = 5;

	protected $formatter = "scss_formatter_nested";

	public function compile($code, $name=null) {
		$this->indentLevel = -1;
		$this->commentsSeen = array();
		$this->extends = array();
		$this->extendsMap = array();

		$locale = setlocale(LC_NUMERIC, 0);
		setlocale(LC_NUMERIC, "C");

		$this->parsedFiles = array();
		$this->parser = new scss_parser($name);
		$tree = $this->parser->parse($code);

		$this->formatter = new $this->formatter();

		$this->env = null;
		$this->scope = null;

		$this->compileRoot($tree);

		$out = $this->formatter->format($this->scope);

		setlocale(LC_NUMERIC, $locale);
		return $out;
	}

	protected function isSelfExtend($target, $origin) {
		foreach ($origin as $sel) {
			if (in_array($target, $sel)) {
				return true;
			}
		}

		return false;
	}

	protected function pushExtends($target, $origin) {
		if ($this->isSelfExtend($target, $origin)) {
			return;
		}

		$i = count($this->extends);
		$this->extends[] = array($target, $origin);

		foreach ($target as $part) {
			if (isset($this->extendsMap[$part])) {
				$this->extendsMap[$part][] = $i;
			} else {
				$this->extendsMap[$part] = array($i);
			}
		}
	}

	protected function makeOutputBlock($type, $selectors = null) {
		$out = new stdClass;
		$out->type = $type;
		$out->lines = array();
		$out->children = array();
		$out->parent = $this->scope;
		$out->selectors = $selectors;
		$out->depth = $this->env->depth;

		return $out;
	}

	protected function matchExtendsSingle($single, &$outOrigin) {
		$counts = array();
		foreach ($single as $part) {
			if (!is_string($part)) return false; // hmm

			if (isset($this->extendsMap[$part])) {
				foreach ($this->extendsMap[$part] as $idx) {
					$counts[$idx] =
						isset($counts[$idx]) ? $counts[$idx] + 1 : 1;
				}
			}
		}

		$outOrigin = array();
		$found = false;

		foreach ($counts as $idx => $count) {
			list($target, $origin) = $this->extends[$idx];

			// check count
			if ($count != count($target)) continue;

			// check if target is subset of single
			if (array_diff(array_intersect($single, $target), $target)) continue;

			$rem = array_diff($single, $target);

			foreach ($origin as $j => $new) {
				// prevent infinite loop when target extends itself
				foreach ($new as $new_selector) {
					if (!array_diff($single, $new_selector)) {
						continue 2;
					}
				}

				$origin[$j][count($origin[$j]) - 1] = $this->combineSelectorSingle(end($new), $rem);
			}

			$outOrigin = array_merge($outOrigin, $origin);

			$found = true;
		}

		return $found;
	}

	protected function combineSelectorSingle($base, $other) {
		$tag = null;
		$out = array();

		foreach (array($base, $other) as $single) {
			foreach ($single as $part) {
				if (preg_match('/^[^\[.#:]/', $part)) {
					$tag = $part;
				} else {
					$out[] = $part;
				}
			}
		}

		if ($tag) {
			array_unshift($out, $tag);
		}

		return $out;
	}

	protected function matchExtends($selector, &$out, $from = 0, $initial=true) {
		foreach ($selector as $i => $part) {
			if ($i < $from) continue;

			if ($this->matchExtendsSingle($part, $origin)) {
				$before = array_slice($selector, 0, $i);
				$after = array_slice($selector, $i + 1);

				foreach ($origin as $new) {
					$k = 0;

					// remove shared parts
					if ($initial) {
						foreach ($before as $k => $val) {
							if (!isset($new[$k]) || $val != $new[$k]) {
								break;
							}
						}
					}

					$result = array_merge(
						$before,
						$k > 0 ? array_slice($new, $k) : $new,
						$after);


					if ($result == $selector) continue;
					$out[] = $result;

					// recursively check for more matches
					$this->matchExtends($result, $out, $i, false);

					// selector sequence merging
					if (!empty($before) && count($new) > 1) {
						$result2 = array_merge(
							array_slice($new, 0, -1),
							$k > 0 ? array_slice($before, $k) : $before,
							array_slice($new, -1),
							$after);

						$out[] = $result2;
					}
				}
			}
		}
	}

	protected function flattenSelectors($block, $parentKey = null) {
		if ($block->selectors) {
			$selectors = array();
			foreach ($block->selectors as $s) {
				$selectors[] = $s;
				if (!is_array($s)) continue;
				// check extends
				if (!empty($this->extendsMap)) {
					$this->matchExtends($s, $selectors);
				}
			}

			$block->selectors = array();
			$placeholderSelector = false;
			foreach ($selectors as $selector) {
				if ($this->hasSelectorPlaceholder($selector)) {
					$placeholderSelector = true;
					continue;
				}
				$block->selectors[] = $this->compileSelector($selector);
			}

			if ($placeholderSelector && 0 == count($block->selectors) && null !== $parentKey) {
				unset($block->parent->children[$parentKey]);
				return;
			}
		}

		foreach ($block->children as $key => $child) {
			$this->flattenSelectors($child, $key);
		}
	}

	protected function compileRoot($rootBlock) {
		$this->pushEnv($rootBlock);
		$this->scope = $this->makeOutputBlock("root");

		$this->compileChildren($rootBlock->children, $this->scope);
		$this->flattenSelectors($this->scope);

		$this->popEnv();
	}

	protected function compileMedia($media) {
		$this->pushEnv($media);
		$parentScope = $this->mediaParent($this->scope);

		$this->scope = $this->makeOutputBlock("media", array(
			$this->compileMediaQuery($this->multiplyMedia($this->env)))
		);

		$parentScope->children[] = $this->scope;

		// top level properties in a media cause it to be wrapped
		$needsWrap = false;
		foreach ($media->children as $child) {
			$type = $child[0];
			if ($type !== 'block' && $type !== 'media' && $type !== 'directive') {
				$needsWrap = true;
				break;
			}
		}

		if ($needsWrap) {
			$wrapped = (object)array(
				"selectors" => array(),
				"children" => $media->children
			);
			$media->children = array(array("block", $wrapped));
		}

		$this->compileChildren($media->children, $this->scope);

		$this->scope = $this->scope->parent;
		$this->popEnv();
	}

	protected function mediaParent($scope) {
		while (!empty($scope->parent)) {
			if (!empty($scope->type) && $scope->type != "media") {
				break;
			}
			$scope = $scope->parent;
		}

		return $scope;
	}

	// TODO refactor compileNestedBlock and compileMedia into same thing
	protected function compileNestedBlock($block, $selectors) {
		$this->pushEnv($block);

		$this->scope = $this->makeOutputBlock($block->type, $selectors);
		$this->scope->parent->children[] = $this->scope;
		$this->compileChildren($block->children, $this->scope);

		$this->scope = $this->scope->parent;
		$this->popEnv();
	}

	/**
	 * Recursively compiles a block.
	 *
	 * A block is analogous to a CSS block in most cases. A single SCSS document
	 * is encapsulated in a block when parsed, but it does not have parent tags
	 * so all of its children appear on the root level when compiled.
	 *
	 * Blocks are made up of selectors and children.
	 *
	 * The children of a block are just all the blocks that are defined within.
	 *
	 * Compiling the block involves pushing a fresh environment on the stack,
	 * and iterating through the props, compiling each one.
	 *
	 * @see scss::compileChild()
	 *
	 * @param \StdClass $block
	 */
	protected function compileBlock($block) {
		$env = $this->pushEnv($block);

		$env->selectors =
			array_map(array($this, "evalSelector"), $block->selectors);

		$out = $this->makeOutputBlock(null, $this->multiplySelectors($env));
		$this->scope->children[] = $out;
		$this->compileChildren($block->children, $out);

		$this->popEnv();
	}

	// joins together .classes and #ids
	protected function flattenSelectorSingle($single) {
		$joined = array();
		foreach ($single as $part) {
			if (empty($joined) ||
				!is_string($part) ||
				preg_match('/[\[.:#%]/', $part))
			{
				$joined[] = $part;
				continue;
			}

			if (is_array(end($joined))) {
				$joined[] = $part;
			} else {
				$joined[count($joined) - 1] .= $part;
			}
		}

		return $joined;
	}

	// replaces all the interpolates
	protected function evalSelector($selector) {
		return array_map(array($this, "evalSelectorPart"), $selector);
	}

	protected function evalSelectorPart($piece) {
		foreach ($piece as &$p) {
			if (!is_array($p)) continue;

			switch ($p[0]) {
			case "interpolate":
				$p = $this->compileValue($p);
				break;
			case "string":
				$p = $this->compileValue($p);
				break;
			}
		}

		return $this->flattenSelectorSingle($piece);
	}

	// compiles to string
	// self(&) should have been replaced by now
	protected function compileSelector($selector) {
		if (!is_array($selector)) return $selector; // media and the like

		return implode(" ", array_map(
			array($this, "compileSelectorPart"), $selector));
	}

	protected function compileSelectorPart($piece) {
		foreach ($piece as &$p) {
			if (!is_array($p)) continue;

			switch ($p[0]) {
			case "self":
				$p = "&";
				break;
			default:
				$p = $this->compileValue($p);
				break;
			}
		}

		return implode($piece);
	}

	protected function hasSelectorPlaceholder($selector)
	{
		if (!is_array($selector)) return false;

		foreach ($selector as $parts) {
			foreach ($parts as $part) {
				if ('%' == $part[0]) {
					return true;
				}
			}
		}

		return false;
	}

	protected function compileChildren($stms, $out) {
		foreach ($stms as $stm) {
			$ret = $this->compileChild($stm, $out);
			if (!is_null($ret)) return $ret;
		}
	}

	protected function compileMediaQuery($queryList) {
		$out = "@media";
		$first = true;
		foreach ($queryList as $query){
			$parts = array();
			foreach ($query as $q) {
				switch ($q[0]) {
					case "mediaType":
						$parts[] = implode(" ", array_map(array($this, "compileValue"), array_slice($q, 1)));
						break;
					case "mediaExp":
						if (isset($q[2])) {
							$parts[] = "(". $this->compileValue($q[1]) . $this->formatter->assignSeparator . $this->compileValue($q[2]) . ")";
						} else {
							$parts[] = "(" . $this->compileValue($q[1]) . ")";
						}
						break;
				}
			}
			if (!empty($parts)) {
				if ($first) {
					$first = false;
					$out .= " ";
				} else {
					$out .= $this->formatter->tagSeparator;
				}
				$out .= implode(" and ", $parts);
			}
		}
		return $out;
	}

	// returns true if the value was something that could be imported
	protected function compileImport($rawPath, $out) {
		if ($rawPath[0] == "string") {
			$path = $this->compileStringContent($rawPath);
			if ($path = $this->findImport($path)) {
				$this->importFile($path, $out);
				return true;
			}
			return false;
		}
		if ($rawPath[0] == "list") {
			// handle a list of strings
			if (count($rawPath[2]) == 0) return false;
			foreach ($rawPath[2] as $path) {
				if ($path[0] != "string") return false;
			}

			foreach ($rawPath[2] as $path) {
				$this->compileImport($path, $out);
			}

			return true;
		}

		return false;
	}

	// return a value to halt execution
	protected function compileChild($child, $out) {
		$this->sourcePos = isset($child[-1]) ? $child[-1] : -1;
		$this->sourceParser = isset($child[-2]) ? $child[-2] : $this->parser;

		switch ($child[0]) {
		case "import":
			list(,$rawPath) = $child;
			$rawPath = $this->reduce($rawPath);
			if (!$this->compileImport($rawPath, $out)) {
				$out->lines[] = "@import " . $this->compileValue($rawPath) . ";";
			}
			break;
		case "directive":
			list(, $directive) = $child;
			$s = "@" . $directive->name;
			if (!empty($directive->value)) {
				$s .= " " . $this->compileValue($directive->value);
			}
			$this->compileNestedBlock($directive, array($s));
			break;
		case "media":
			$this->compileMedia($child[1]);
			break;
		case "block":
			$this->compileBlock($child[1]);
			break;
		case "charset":
			$out->lines[] = "@charset ".$this->compileValue($child[1]).";";
			break;
		case "assign":
			list(,$name, $value) = $child;
			if ($name[0] == "var") {
				$isDefault = !empty($child[3]);

				if ($isDefault) {
					$existingValue = $this->get($name[1], true);
					$shouldSet = $existingValue === true || $existingValue == self::$null;
				}

				if (!$isDefault || $shouldSet) {
					$this->set($name[1], $this->reduce($value));
				}
				break;
			}

			// if the value reduces to null from something else then
			// the property should be discarded
			if ($value[0] != "null") {
				$value = $this->reduce($value);
				if ($value[0] == "null") {
					break;
				}
			}

			$compiledValue = $this->compileValue($value);
			$out->lines[] = $this->formatter->property(
				$this->compileValue($name),
				$compiledValue);
			break;
		case "comment":
			$out->lines[] = $child[1];
			break;
		case "mixin":
		case "function":
			list(,$block) = $child;
			$this->set(self::$namespaces[$block->type] . $block->name, $block);
			break;
		case "extend":
			list(, $selectors) = $child;
			foreach ($selectors as $sel) {
				// only use the first one
				$sel = current($this->evalSelector($sel));
				$this->pushExtends($sel, $out->selectors);
			}
			break;
		case "if":
			list(, $if) = $child;
			if ($this->isTruthy($this->reduce($if->cond, true))) {
				return $this->compileChildren($if->children, $out);
			} else {
				foreach ($if->cases as $case) {
					if ($case->type == "else" ||
						$case->type == "elseif" && $this->isTruthy($this->reduce($case->cond)))
					{
						return $this->compileChildren($case->children, $out);
					}
				}
			}
			break;
		case "return":
			return $this->reduce($child[1], true);
		case "each":
			list(,$each) = $child;
			$list = $this->coerceList($this->reduce($each->list));
			foreach ($list[2] as $item) {
				$this->pushEnv();
				$this->set($each->var, $item);
				// TODO: allow return from here
				$this->compileChildren($each->children, $out);
				$this->popEnv();
			}
			break;
		case "while":
			list(,$while) = $child;
			while ($this->isTruthy($this->reduce($while->cond, true))) {
				$ret = $this->compileChildren($while->children, $out);
				if ($ret) return $ret;
			}
			break;
		case "for":
			list(,$for) = $child;
			$start = $this->reduce($for->start, true);
			$start = $start[1];
			$end = $this->reduce($for->end, true);
			$end = $end[1];
			$d = $start < $end ? 1 : -1;

			while (true) {
				if ((!$for->until && $start - $d == $end) ||
					($for->until && $start == $end))
				{
					break;
				}

				$this->set($for->var, array("number", $start, ""));
				$start += $d;

				$ret = $this->compileChildren($for->children, $out);
				if ($ret) return $ret;
			}

			break;
		case "nestedprop":
			list(,$prop) = $child;
			$prefixed = array();
			$prefix = $this->compileValue($prop->prefix) . "-";
			foreach ($prop->children as $child) {
				if ($child[0] == "assign") {
					array_unshift($child[1][2], $prefix);
				}
				if ($child[0] == "nestedprop") {
					array_unshift($child[1]->prefix[2], $prefix);
				}
				$prefixed[] = $child;
			}
			$this->compileChildren($prefixed, $out);
			break;
		case "include": // including a mixin
			list(,$name, $argValues, $content) = $child;
			$mixin = $this->get(self::$namespaces["mixin"] . $name, false);
			if (!$mixin) {
				$this->throwError("Undefined mixin $name");
			}

			$callingScope = $this->env;

			// push scope, apply args
			$this->pushEnv();
			if ($this->env->depth > 0) {
				$this->env->depth--;
			}

			if (!is_null($content)) {
				$content->scope = $callingScope;
				$this->setRaw(self::$namespaces["special"] . "content", $content);
			}

			if (!is_null($mixin->args)) {
				$this->applyArguments($mixin->args, $argValues);
			}

			foreach ($mixin->children as $child) {
				$this->compileChild($child, $out);
			}

			$this->popEnv();

			break;
		case "mixin_content":
			$content = $this->get(self::$namespaces["special"] . "content");
			if (is_null($content)) {
				$this->throwError("Expected @content inside of mixin");
			}

			$strongTypes = array('include', 'block', 'for', 'while');
			foreach ($content->children as $child) {
				$this->storeEnv = (in_array($child[0], $strongTypes))
					? null
					: $content->scope;

				$this->compileChild($child, $out);
			}

			unset($this->storeEnv);
			break;
		case "debug":
			list(,$value, $pos) = $child;
			$line = $this->parser->getLineNo($pos);
			$value = $this->compileValue($this->reduce($value, true));
			fwrite(STDERR, "Line $line DEBUG: $value\n");
			break;
		default:
			$this->throwError("unknown child type: $child[0]");
		}
	}

	protected function expToString($exp) {
		list(, $op, $left, $right, $inParens, $whiteLeft, $whiteRight) = $exp;
		$content = array($this->reduce($left));
		if ($whiteLeft) $content[] = " ";
		$content[] = $op;
		if ($whiteRight) $content[] = " ";
		$content[] = $this->reduce($right);
		return array("string", "", $content);
	}

	protected function isTruthy($value) {
		return $value != self::$false && $value != self::$null;
	}

	// should $value cause its operand to eval
	protected function shouldEval($value) {
		switch ($value[0]) {
		case "exp":
			if ($value[1] == "/") {
				return $this->shouldEval($value[2], $value[3]);
			}
		case "var":
		case "fncall":
			return true;
		}
		return false;
	}

	protected function reduce($value, $inExp = false) {
		list($type) = $value;
		switch ($type) {
			case "exp":
				list(, $op, $left, $right, $inParens) = $value;
				$opName = isset(self::$operatorNames[$op]) ? self::$operatorNames[$op] : $op;

				$inExp = $inExp || $this->shouldEval($left) || $this->shouldEval($right);

				$left = $this->reduce($left, true);
				$right = $this->reduce($right, true);

				// only do division in special cases
				if ($opName == "div" && !$inParens && !$inExp) {
					if ($left[0] != "color" && $right[0] != "color") {
						return $this->expToString($value);
					}
				}

				$left = $this->coerceForExpression($left);
				$right = $this->coerceForExpression($right);

				$ltype = $left[0];
				$rtype = $right[0];

				// this tries:
				// 1. op_[op name]_[left type]_[right type]
				// 2. op_[left type]_[right type] (passing the op as first arg
				// 3. op_[op name]
				$fn = "op_${opName}_${ltype}_${rtype}";
				if (is_callable(array($this, $fn)) ||
					(($fn = "op_${ltype}_${rtype}") &&
						is_callable(array($this, $fn)) &&
						$passOp = true) ||
					(($fn = "op_${opName}") &&
						is_callable(array($this, $fn)) &&
						$genOp = true))
				{
					$unitChange = false;
					if (!isset($genOp) &&
						$left[0] == "number" && $right[0] == "number")
					{
						if ($opName == "mod" && $right[2] != "") {
							$this->throwError("Cannot modulo by a number with units: $right[1]$right[2].");
						}

						$unitChange = true;
						$emptyUnit = $left[2] == "" || $right[2] == "";
						$targetUnit = "" != $left[2] ? $left[2] : $right[2];

						if ($opName != "mul") {
							$left[2] = "" != $left[2] ? $left[2] : $targetUnit;
							$right[2] = "" != $right[2] ? $right[2] : $targetUnit;
						}

						if ($opName != "mod") {
							$left = $this->normalizeNumber($left);
							$right = $this->normalizeNumber($right);
						}

						if ($opName == "div" && !$emptyUnit && $left[2] == $right[2]) {
							$targetUnit = "";
						}

						if ($opName == "mul") {
							$left[2] = "" != $left[2] ? $left[2] : $right[2];
							$right[2] = "" != $right[2] ? $right[2] : $left[2];
						} elseif ($opName == "div" && $left[2] == $right[2]) {
							$left[2] = "";
							$right[2] = "";
						}
					}

					$shouldEval = $inParens || $inExp;
					if (isset($passOp)) {
						$out = $this->$fn($op, $left, $right, $shouldEval);
					} else {
						$out = $this->$fn($left, $right, $shouldEval);
					}

					if (!is_null($out)) {
						if ($unitChange && $out[0] == "number") {
							$out = $this->coerceUnit($out, $targetUnit);
						}
						return $out;
					}
				}

				return $this->expToString($value);
			case "unary":
				list(, $op, $exp, $inParens) = $value;
				$inExp = $inExp || $this->shouldEval($exp);

				$exp = $this->reduce($exp);
				if ($exp[0] == "number") {
					switch ($op) {
					case "+":
						return $exp;
					case "-":
						$exp[1] *= -1;
						return $exp;
					}
				}

				if ($op == "not") {
					if ($inExp || $inParens) {
						if ($exp == self::$false) {
							return self::$true;
						} else {
							return self::$false;
						}
					} else {
						$op = $op . " ";
					}
				}

				return array("string", "", array($op, $exp));
			case "var":
				list(, $name) = $value;
				return $this->reduce($this->get($name));
			case "list":
				foreach ($value[2] as &$item) {
					$item = $this->reduce($item);
				}
				return $value;
			case "string":
				foreach ($value[2] as &$item) {
					if (is_array($item)) {
						$item = $this->reduce($item);
					}
				}
				return $value;
			case "interpolate":
				$value[1] = $this->reduce($value[1]);
				return $value;
			case "fncall":
				list(,$name, $argValues) = $value;

				// user defined function?
				$func = $this->get(self::$namespaces["function"] . $name, false);
				if ($func) {
					$this->pushEnv();

					// set the args
					if (isset($func->args)) {
						$this->applyArguments($func->args, $argValues);
					}

					// throw away lines and children
					$tmp = (object)array(
						"lines" => array(),
						"children" => array()
					);
					$ret = $this->compileChildren($func->children, $tmp);
					$this->popEnv();

					return is_null($ret) ? self::$defaultValue : $ret;
				}

				// built in function
				if ($this->callBuiltin($name, $argValues, $returnValue)) {
					return $returnValue;
				}

				// need to flatten the arguments into a list
				$listArgs = array();
				foreach ((array)$argValues as $arg) {
					if (empty($arg[0])) {
						$listArgs[] = $this->reduce($arg[1]);
					}
				}
				return array("function", $name, array("list", ",", $listArgs));
			default:
				return $value;
		}
	}

	public function normalizeValue($value) {
		$value = $this->coerceForExpression($this->reduce($value));
		list($type) = $value;

		switch ($type) {
		case "list":
			$value = $this->extractInterpolation($value);
			if ($value[0] != "list") {
				return array("keyword", $this->compileValue($value));
			}
			foreach ($value[2] as $key => $item) {
				$value[2][$key] = $this->normalizeValue($item);
			}
			return $value;
		case "number":
			return $this->normalizeNumber($value);
		default:
			return $value;
		}
	}

	// just does physical lengths for now
	protected function normalizeNumber($number) {
		list(, $value, $unit) = $number;
		if (isset(self::$unitTable["in"][$unit])) {
			$conv = self::$unitTable["in"][$unit];
			return array("number", $value / $conv, "in");
		}
		return $number;
	}

	// $number should be normalized
	protected function coerceUnit($number, $unit) {
		list(, $value, $baseUnit) = $number;
		if (isset(self::$unitTable[$baseUnit][$unit])) {
			$value = $value * self::$unitTable[$baseUnit][$unit];
		}

		return array("number", $value, $unit);
	}

	protected function op_add_number_number($left, $right) {
		return array("number", $left[1] + $right[1], $left[2]);
	}

	protected function op_mul_number_number($left, $right) {
		return array("number", $left[1] * $right[1], $left[2]);
	}

	protected function op_sub_number_number($left, $right) {
		return array("number", $left[1] - $right[1], $left[2]);
	}

	protected function op_div_number_number($left, $right) {
		return array("number", $left[1] / $right[1], $left[2]);
	}

	protected function op_mod_number_number($left, $right) {
		return array("number", $left[1] % $right[1], $left[2]);
	}

	// adding strings
	protected function op_add($left, $right) {
		if ($strLeft = $this->coerceString($left)) {
			if ($right[0] == "string") {
				$right[1] = "";
			}
			$strLeft[2][] = $right;
			return $strLeft;
		}

		if ($strRight = $this->coerceString($right)) {
			if ($left[0] == "string") {
				$left[1] = "";
			}
			array_unshift($strRight[2], $left);
			return $strRight;
		}
	}

	protected function op_and($left, $right, $shouldEval) {
		if (!$shouldEval) return;
		if ($left != self::$false) return $right;
		return $left;
	}

	protected function op_or($left, $right, $shouldEval) {
		if (!$shouldEval) return;
		if ($left != self::$false) return $left;
		return $right;
	}

	protected function op_color_color($op, $left, $right) {
		$out = array('color');
		foreach (range(1, 3) as $i) {
			$lval = isset($left[$i]) ? $left[$i] : 0;
			$rval = isset($right[$i]) ? $right[$i] : 0;
			switch ($op) {
			case '+':
				$out[] = $lval + $rval;
				break;
			case '-':
				$out[] = $lval - $rval;
				break;
			case '*':
				$out[] = $lval * $rval;
				break;
			case '%':
				$out[] = $lval % $rval;
				break;
			case '/':
				if ($rval == 0) {
					$this->throwError("color: Can't divide by zero");
				}
				$out[] = $lval / $rval;
				break;
			case "==":
				return $this->op_eq($left, $right);
			case "!=":
				return $this->op_neq($left, $right);
			default:
				$this->throwError("color: unknown op $op");
			}
		}

		if (isset($left[4])) $out[4] = $left[4];
		elseif (isset($right[4])) $out[4] = $right[4];

		return $this->fixColor($out);
	}

	protected function op_color_number($op, $left, $right) {
		$value = $right[1];
		return $this->op_color_color($op, $left,
			array("color", $value, $value, $value));
	}

	protected function op_number_color($op, $left, $right) {
		$value = $left[1];
		return $this->op_color_color($op,
			array("color", $value, $value, $value), $right);
	}

	protected function op_eq($left, $right) {
		if (($lStr = $this->coerceString($left)) && ($rStr = $this->coerceString($right))) {
			$lStr[1] = "";
			$rStr[1] = "";
			return $this->toBool($this->compileValue($lStr) == $this->compileValue($rStr));
		}

		return $this->toBool($left == $right);
	}

	protected function op_neq($left, $right) {
		return $this->toBool($left != $right);
	}

	protected function op_gte_number_number($left, $right) {
		return $this->toBool($left[1] >= $right[1]);
	}

	protected function op_gt_number_number($left, $right) {
		return $this->toBool($left[1] > $right[1]);
	}

	protected function op_lte_number_number($left, $right) {
		return $this->toBool($left[1] <= $right[1]);
	}

	protected function op_lt_number_number($left, $right) {
		return $this->toBool($left[1] < $right[1]);
	}

	public function toBool($thing) {
		return $thing ? self::$true : self::$false;
	}

	/**
	 * Compiles a primitive value into a CSS property value.
	 *
	 * Values in scssphp are typed by being wrapped in arrays, their format is
	 * typically:
	 *
	 *     array(type, contents [, additional_contents]*)
	 *
	 * The input is expected to be reduced. This function will not work on
	 * things like expressions and variables.
	 *
	 * @param array $value
	 */
	protected function compileValue($value) {
		$value = $this->reduce($value);

		list($type) = $value;
		switch ($type) {
		case "keyword":
			return $value[1];
		case "color":
			// [1] - red component (either number for a %)
			// [2] - green component
			// [3] - blue component
			// [4] - optional alpha component
			list(, $r, $g, $b) = $value;

			$r = round($r);
			$g = round($g);
			$b = round($b);

			if (count($value) == 5 && $value[4] != 1) { // rgba
				return 'rgba('.$r.', '.$g.', '.$b.', '.$value[4].')';
			}

			$h = sprintf("#%02x%02x%02x", $r, $g, $b);

			// Converting hex color to short notation (e.g. #003399 to #039)
			if ($h[1] === $h[2] && $h[3] === $h[4] && $h[5] === $h[6]) {
				$h = '#' . $h[1] . $h[3] . $h[5];
			}

			return $h;
		case "number":
			return round($value[1], $this->numberPrecision) . $value[2];
		case "string":
			return $value[1] . $this->compileStringContent($value) . $value[1];
		case "function":
			$args = !empty($value[2]) ? $this->compileValue($value[2]) : "";
			return "$value[1]($args)";
		case "list":
			$value = $this->extractInterpolation($value);
			if ($value[0] != "list") return $this->compileValue($value);

			list(, $delim, $items) = $value;

			$filtered = array();
			foreach ($items as $item) {
				if ($item[0] == "null") continue;
				$filtered[] = $this->compileValue($item);
			}

			return implode("$delim ", $filtered);
		case "interpolated": # node created by extractInterpolation
			list(, $interpolate, $left, $right) = $value;
			list(,, $whiteLeft, $whiteRight) = $interpolate;

			$left = count($left[2]) > 0 ?
				$this->compileValue($left).$whiteLeft : "";

			$right = count($right[2]) > 0 ?
				$whiteRight.$this->compileValue($right) : "";

			return $left.$this->compileValue($interpolate).$right;

		case "interpolate": # raw parse node
			list(, $exp) = $value;

			// strip quotes if it's a string
			$reduced = $this->reduce($exp);
			switch ($reduced[0]) {
				case "string":
					$reduced = array("keyword",
						$this->compileStringContent($reduced));
					break;
				case "null":
					$reduced = array("keyword", "");
			}

			return $this->compileValue($reduced);
		case "null":
			return "null";
		default:
			$this->throwError("unknown value type: $type");
		}
	}

	protected function compileStringContent($string) {
		$parts = array();
		foreach ($string[2] as $part) {
			if (is_array($part)) {
				$parts[] = $this->compileValue($part);
			} else {
				$parts[] = $part;
			}
		}

		return implode($parts);
	}

	// doesn't need to be recursive, compileValue will handle that
	protected function extractInterpolation($list) {
		$items = $list[2];
		foreach ($items as $i => $item) {
			if ($item[0] == "interpolate") {
				$before = array("list", $list[1], array_slice($items, 0, $i));
				$after = array("list", $list[1], array_slice($items, $i + 1));
				return array("interpolated", $item, $before, $after);
			}
		}
		return $list;
	}

	// find the final set of selectors
	protected function multiplySelectors($env) {
		$envs = array();
		while (null !== $env) {
			if (!empty($env->selectors)) {
				$envs[] = $env;
			}
			$env = $env->parent;
		};

		$selectors = array();
		$parentSelectors = array(array());
		while ($env = array_pop($envs)) {
			$selectors = array();
			foreach ($env->selectors as $selector) {
				foreach ($parentSelectors as $parent) {
					$selectors[] = $this->joinSelectors($parent, $selector);
				}
			}
			$parentSelectors = $selectors;
		}

		return $selectors;
	}

	// looks for & to replace, or append parent before child
	protected function joinSelectors($parent, $child) {
		$setSelf = false;
		$out = array();
		foreach ($child as $part) {
			$newPart = array();
			foreach ($part as $p) {
				if ($p == self::$selfSelector) {
					$setSelf = true;
					foreach ($parent as $i => $parentPart) {
						if ($i > 0) {
							$out[] = $newPart;
							$newPart = array();
						}

						foreach ($parentPart as $pp) {
							$newPart[] = $pp;
						}
					}
				} else {
					$newPart[] = $p;
				}
			}

			$out[] = $newPart;
		}

		return $setSelf ? $out : array_merge($parent, $child);
	}

	protected function multiplyMedia($env, $childQueries = null) {
		if (is_null($env) ||
			!empty($env->block->type) && $env->block->type != "media")
		{
			return $childQueries;
		}

		// plain old block, skip
		if (empty($env->block->type)) {
			return $this->multiplyMedia($env->parent, $childQueries);
		}

		$parentQueries = $env->block->queryList;
		if ($childQueries == null) {
			$childQueries = $parentQueries;
		} else {
			$originalQueries = $childQueries;
			$childQueries = array();

			foreach ($parentQueries as $parentQuery){
				foreach ($originalQueries as $childQuery) {
					$childQueries []= array_merge($parentQuery, $childQuery);
				}
			}
		}

		return $this->multiplyMedia($env->parent, $childQueries);
	}

	// convert something to list
	protected function coerceList($item, $delim = ",") {
		if (!is_null($item) && $item[0] == "list") {
			return $item;
		}

		return array("list", $delim, is_null($item) ? array(): array($item));
	}

	protected function applyArguments($argDef, $argValues) {
		$hasVariable = false;
		$args = array();
		foreach ($argDef as $i => $arg) {
			list($name, $default, $isVariable) = $argDef[$i];
			$args[$name] = array($i, $name, $default, $isVariable);
			$hasVariable |= $isVariable;
		}

		$keywordArgs = array();
		$deferredKeywordArgs = array();
		$remaining = array();
		// assign the keyword args
		foreach ((array) $argValues as $arg) {
			if (!empty($arg[0])) {
				if (!isset($args[$arg[0][1]])) {
					if ($hasVariable) {
						$deferredKeywordArgs[$arg[0][1]] = $arg[1];
					} else {
						$this->throwError("Mixin or function doesn't have an argument named $%s.", $arg[0][1]);
					}
				} elseif ($args[$arg[0][1]][0] < count($remaining)) {
					$this->throwError("The argument $%s was passed both by position and by name.", $arg[0][1]);
				} else {
					$keywordArgs[$arg[0][1]] = $arg[1];
				}
			} elseif (count($keywordArgs)) {
				$this->throwError('Positional arguments must come before keyword arguments.');
			} elseif ($arg[2] == true) {
				$val = $this->reduce($arg[1], true);
				if ($val[0] == "list") {
					foreach ($val[2] as $name => $item) {
						if (!is_numeric($name)) {
							$keywordArgs[$name] = $item;
						} else {
							$remaining[] = $item;
						}
					}
				} else {
					$remaining[] = $val;
				}
			} else {
				$remaining[] = $arg[1];
			}
		}

		foreach ($args as $arg) {
			list($i, $name, $default, $isVariable) = $arg;
			if ($isVariable) {
				$val = array("list", ",", array());
				for ($count = count($remaining); $i < $count; $i++) {
					$val[2][] = $remaining[$i];
				}
				foreach ($deferredKeywordArgs as $itemName => $item) {
					$val[2][$itemName] = $item;
				}
			} elseif (isset($remaining[$i])) {
				$val = $remaining[$i];
			} elseif (isset($keywordArgs[$name])) {
				$val = $keywordArgs[$name];
			} elseif (!empty($default)) {
				$val = $default;
			} else {
				$this->throwError("Missing argument $name");
			}

			$this->set($name, $this->reduce($val, true), true);
		}
	}

	protected function pushEnv($block=null) {
		$env = new stdClass;
		$env->parent = $this->env;
		$env->store = array();
		$env->block = $block;
		$env->depth = isset($this->env->depth) ? $this->env->depth + 1 : 0;

		$this->env = $env;
		return $env;
	}

	protected function normalizeName($name) {
		return str_replace("-", "_", $name);
	}

	protected function getStoreEnv() {
		return isset($this->storeEnv) ? $this->storeEnv : $this->env;
	}

	protected function set($name, $value, $shadow=false) {
		$name = $this->normalizeName($name);

		if ($shadow) {
			$this->setRaw($name, $value);
		} else {
			$this->setExisting($name, $value);
		}
	}

	protected function setExisting($name, $value, $env = null) {
		if (is_null($env)) $env = $this->getStoreEnv();

		if (isset($env->store[$name]) || is_null($env->parent)) {
			$env->store[$name] = $value;
		} else {
			$this->setExisting($name, $value, $env->parent);
		}
	}

	protected function setRaw($name, $value) {
		$env = $this->getStoreEnv();
		$env->store[$name] = $value;
	}

	public function get($name, $defaultValue = null, $env = null) {
		$name = $this->normalizeName($name);

		if (is_null($env)) $env = $this->getStoreEnv();
		if (is_null($defaultValue)) $defaultValue = self::$defaultValue;

		if (isset($env->store[$name])) {
			return $env->store[$name];
		} elseif (isset($env->parent)) {
			return $this->get($name, $defaultValue, $env->parent);
		}

		return $defaultValue; // found nothing
	}

	protected function popEnv() {
		$env = $this->env;
		$this->env = $this->env->parent;
		return $env;
	}

	public function getParsedFiles() {
		return $this->parsedFiles;
	}

	public function addImportPath($path) {
		$this->importPaths[] = $path;
	}

	public function setImportPaths($path) {
		$this->importPaths = (array)$path;
	}

	public function setNumberPrecision($numberPrecision) {
		$this->numberPrecision = $numberPrecision;
	}

	public function setFormatter($formatterName) {
		$this->formatter = $formatterName;
	}

	public function registerFunction($name, $func) {
		$this->userFunctions[$this->normalizeName($name)] = $func;
	}

	public function unregisterFunction($name) {
		unset($this->userFunctions[$this->normalizeName($name)]);
	}

	protected function importFile($path, $out) {
		// see if tree is cached
		$realPath = realpath($path);
		if (isset($this->importCache[$realPath])) {
			$tree = $this->importCache[$realPath];
		} else {
			$code = file_get_contents($path);
			$parser = new scss_parser($path, false);
			$tree = $parser->parse($code);
			$this->parsedFiles[] = $path;

			$this->importCache[$realPath] = $tree;
		}

		$pi = pathinfo($path);
		array_unshift($this->importPaths, $pi['dirname']);
		$this->compileChildren($tree->children, $out);
		array_shift($this->importPaths);
	}

	// results the file path for an import url if it exists
	public function findImport($url) {
		$urls = array();

		// for "normal" scss imports (ignore vanilla css and external requests)
		if (!preg_match('/\.css|^http:\/\/$/', $url)) {
			// try both normal and the _partial filename
			$urls = array($url, preg_replace('/[^\/]+$/', '_\0', $url));
		}

		foreach ($this->importPaths as $dir) {
			if (is_string($dir)) {
				// check urls for normal import paths
				foreach ($urls as $full) {
					$full = $dir .
						(!empty($dir) && substr($dir, -1) != '/' ? '/' : '') .
						$full;

					if ($this->fileExists($file = $full.'.scss') ||
						$this->fileExists($file = $full))
					{
						return $file;
					}
				}
			} else {
				// check custom callback for import path
				$file = call_user_func($dir,$url,$this);
				if ($file !== null) {
					return $file;
				}
			}
		}

		return null;
	}

	protected function fileExists($name) {
		return is_file($name);
	}

	protected function callBuiltin($name, $args, &$returnValue) {
		// try a lib function
		$name = $this->normalizeName($name);
		$libName = "lib_".$name;
		$f = array($this, $libName);
		$prototype = isset(self::$$libName) ? self::$$libName : null;

		if (is_callable($f)) {
			$sorted = $this->sortArgs($prototype, $args);
			foreach ($sorted as &$val) {
				$val = $this->reduce($val, true);
			}
			$returnValue = call_user_func($f, $sorted, $this);
		} elseif (isset($this->userFunctions[$name])) {
			// see if we can find a user function
			$fn = $this->userFunctions[$name];

			foreach ($args as &$val) {
				$val = $this->reduce($val[1], true);
			}

			$returnValue = call_user_func($fn, $args, $this);
		}

		if (isset($returnValue)) {
			// coerce a php value into a scss one
			if (is_numeric($returnValue)) {
				$returnValue = array('number', $returnValue, "");
			} elseif (is_bool($returnValue)) {
				$returnValue = $returnValue ? self::$true : self::$false;
			} elseif (!is_array($returnValue)) {
				$returnValue = array('keyword', $returnValue);
			}

			return true;
		}

		return false;
	}

	// sorts any keyword arguments
	// TODO: merge with apply arguments
	protected function sortArgs($prototype, $args) {
		$keyArgs = array();
		$posArgs = array();

		foreach ($args as $arg) {
			list($key, $value) = $arg;
			$key = $key[1];
			if (empty($key)) {
				$posArgs[] = $value;
			} else {
				$keyArgs[$key] = $value;
			}
		}

		if (is_null($prototype)) return $posArgs;

		$finalArgs = array();
		foreach ($prototype as $i => $names) {
			if (isset($posArgs[$i])) {
				$finalArgs[] = $posArgs[$i];
				continue;
			}

			$set = false;
			foreach ((array)$names as $name) {
				if (isset($keyArgs[$name])) {
					$finalArgs[] = $keyArgs[$name];
					$set = true;
					break;
				}
			}

			if (!$set) {
				$finalArgs[] = null;
			}
		}

		return $finalArgs;
	}

	protected function coerceForExpression($value) {
		if ($color = $this->coerceColor($value)) {
			return $color;
		}

		return $value;
	}

	protected function coerceColor($value) {
		switch ($value[0]) {
		case "color": return $value;
		case "keyword":
			$name = $value[1];
			if (isset(self::$cssColors[$name])) {
				@list($r, $g, $b, $a) = explode(',', self::$cssColors[$name]);
				return isset($a)
					? array('color', (int) $r, (int) $g, (int) $b, (int) $a)
					: array('color', (int) $r, (int) $g, (int) $b);
			}
			return null;
		}

		return null;
	}

	protected function coerceString($value) {
		switch ($value[0]) {
		case "string":
			return $value;
		case "keyword":
			return array("string", "", array($value[1]));
		}
		return null;
	}

	public function assertList($value) {
		if ($value[0] != "list")
			$this->throwError("expecting list");
		return $value;
	}

	public function assertColor($value) {
		if ($color = $this->coerceColor($value)) return $color;
		$this->throwError("expecting color");
	}

	public function assertNumber($value) {
		if ($value[0] != "number")
			$this->throwError("expecting number");
		return $value[1];
	}

	protected function coercePercent($value) {
		if ($value[0] == "number") {
			if ($value[2] == "%") {
				return $value[1] / 100;
			}
			return $value[1];
		}
		return 0;
	}

	// make sure a color's components don't go out of bounds
	protected function fixColor($c) {
		foreach (range(1, 3) as $i) {
			if ($c[$i] < 0) $c[$i] = 0;
			if ($c[$i] > 255) $c[$i] = 255;
		}

		return $c;
	}

	public function toHSL($red, $green, $blue) {
		$r = $red / 255;
		$g = $green / 255;
		$b = $blue / 255;

		$min = min($r, $g, $b);
		$max = max($r, $g, $b);
		$d = $max - $min;
		$l = ($min + $max) / 2;

		if ($min == $max) {
			$s = $h = 0;
		} else {
			if ($l < 0.5)
				$s = $d / (2 * $l);
			else
				$s = $d / (2 - 2 * $l);

			if ($r == $max)
				$h = 60 * ($g - $b) / $d;
			elseif ($g == $max)
				$h = 60 * ($b - $r) / $d + 120;
			elseif ($b == $max)
				$h = 60 * ($r - $g) / $d + 240;
		}

		return array('hsl', fmod($h, 360), $s * 100, $l * 100);
	}

	public function hueToRGB($m1, $m2, $h) {
		if ($h < 0)
			$h += 1;
		elseif ($h > 1)
			$h -= 1;

		if ($h * 6 < 1)
			return $m1 + ($m2 - $m1) * $h * 6;

		if ($h * 2 < 1)
			return $m2;

		if ($h * 3 < 2)
			return $m1 + ($m2 - $m1) * (2/3 - $h) * 6;

		return $m1;
	}

	// H from 0 to 360, S and L from 0 to 100
	public function toRGB($hue, $saturation, $lightness) {
		if ($hue < 0) {
			$hue += 360;
		}

		$h = $hue / 360;
		$s = min(100, max(0, $saturation)) / 100;
		$l = min(100, max(0, $lightness)) / 100;

		$m2 = $l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s;
		$m1 = $l * 2 - $m2;

		$r = $this->hueToRGB($m1, $m2, $h + 1/3) * 255;
		$g = $this->hueToRGB($m1, $m2, $h) * 255;
		$b = $this->hueToRGB($m1, $m2, $h - 1/3) * 255;

		$out = array('color', $r, $g, $b);
		return $out;
	}

	// Built in functions

	protected static $lib_if = array("condition", "if-true", "if-false");
	protected function lib_if($args) {
		list($cond,$t, $f) = $args;
		if ($cond == self::$false) return $f;
		return $t;
	}

	protected static $lib_index = array("list", "value");
	protected function lib_index($args) {
		list($list, $value) = $args;
		$list = $this->assertList($list);

		$values = array();
		foreach ($list[2] as $item) {
			$values[] = $this->normalizeValue($item);
		}
		$key = array_search($this->normalizeValue($value), $values);

		return false === $key ? false : $key + 1;
	}

	protected static $lib_rgb = array("red", "green", "blue");
	protected function lib_rgb($args) {
		list($r,$g,$b) = $args;
		return array("color", $r[1], $g[1], $b[1]);
	}

	protected static $lib_rgba = array(
		array("red", "color"),
		"green", "blue", "alpha");
	protected function lib_rgba($args) {
		if ($color = $this->coerceColor($args[0])) {
			$num = is_null($args[1]) ? $args[3] : $args[1];
			$alpha = $this->assertNumber($num);
			$color[4] = $alpha;
			return $color;
		}

		list($r,$g,$b, $a) = $args;
		return array("color", $r[1], $g[1], $b[1], $a[1]);
	}

	// helper function for adjust_color, change_color, and scale_color
	protected function alter_color($args, $fn) {
		$color = $this->assertColor($args[0]);

		foreach (array(1,2,3,7) as $i) {
			if (!is_null($args[$i])) {
				$val = $this->assertNumber($args[$i]);
				$ii = $i == 7 ? 4 : $i; // alpha
				$color[$ii] =
					$this->$fn(isset($color[$ii]) ? $color[$ii] : 0, $val, $i);
			}
		}

		if (!is_null($args[4]) || !is_null($args[5]) || !is_null($args[6])) {
			$hsl = $this->toHSL($color[1], $color[2], $color[3]);
			foreach (array(4,5,6) as $i) {
				if (!is_null($args[$i])) {
					$val = $this->assertNumber($args[$i]);
					$hsl[$i - 3] = $this->$fn($hsl[$i - 3], $val, $i);
				}
			}

			$rgb = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);
			if (isset($color[4])) $rgb[4] = $color[4];
			$color = $rgb;
		}

		return $color;
	}

	protected static $lib_adjust_color = array(
		"color", "red", "green", "blue",
		"hue", "saturation", "lightness", "alpha"
	);
	protected function adjust_color_helper($base, $alter, $i) {
		return $base += $alter;
	}
	protected function lib_adjust_color($args) {
		return $this->alter_color($args, "adjust_color_helper");
	}

	protected static $lib_change_color = array(
		"color", "red", "green", "blue",
		"hue", "saturation", "lightness", "alpha"
	);
	protected function change_color_helper($base, $alter, $i) {
		return $alter;
	}
	protected function lib_change_color($args) {
		return $this->alter_color($args, "change_color_helper");
	}

	protected static $lib_scale_color = array(
		"color", "red", "green", "blue",
		"hue", "saturation", "lightness", "alpha"
	);
	protected function scale_color_helper($base, $scale, $i) {
		// 1,2,3 - rgb
		// 4, 5, 6 - hsl
		// 7 - a
		switch ($i) {
		case 1:
		case 2:
		case 3:
			$max = 255; break;
		case 4:
			$max = 360; break;
		case 7:
			$max = 1; break;
		default:
			$max = 100;
		}

		$scale = $scale / 100;
		if ($scale < 0) {
			return $base * $scale + $base;
		} else {
			return ($max - $base) * $scale + $base;
		}
	}
	protected function lib_scale_color($args) {
		return $this->alter_color($args, "scale_color_helper");
	}

	protected static $lib_ie_hex_str = array("color");
	protected function lib_ie_hex_str($args) {
		$color = $this->coerceColor($args[0]);
		$color[4] = isset($color[4]) ? round(255*$color[4]) : 255;

		return sprintf('#%02X%02X%02X%02X', $color[4], $color[1], $color[2], $color[3]);
	}

	protected static $lib_red = array("color");
	protected function lib_red($args) {
		$color = $this->coerceColor($args[0]);
		return $color[1];
	}

	protected static $lib_green = array("color");
	protected function lib_green($args) {
		$color = $this->coerceColor($args[0]);
		return $color[2];
	}

	protected static $lib_blue = array("color");
	protected function lib_blue($args) {
		$color = $this->coerceColor($args[0]);
		return $color[3];
	}

	protected static $lib_alpha = array("color");
	protected function lib_alpha($args) {
		if ($color = $this->coerceColor($args[0])) {
			return isset($color[4]) ? $color[4] : 1;
		}

		// this might be the IE function, so return value unchanged
		return null;
	}

	protected static $lib_opacity = array("color");
	protected function lib_opacity($args) {
		$value = $args[0];
		if ($value[0] === 'number') return null;
		return $this->lib_alpha($args);
	}

	// mix two colors
	protected static $lib_mix = array("color-1", "color-2", "weight");
	protected function lib_mix($args) {
		list($first, $second, $weight) = $args;
		$first = $this->assertColor($first);
		$second = $this->assertColor($second);

		if (is_null($weight)) {
			$weight = 0.5;
		} else {
			$weight = $this->coercePercent($weight);
		}

		$firstAlpha = isset($first[4]) ? $first[4] : 1;
		$secondAlpha = isset($second[4]) ? $second[4] : 1;

		$w = $weight * 2 - 1;
		$a = $firstAlpha - $secondAlpha;

		$w1 = (($w * $a == -1 ? $w : ($w + $a)/(1 + $w * $a)) + 1) / 2.0;
		$w2 = 1.0 - $w1;

		$new = array('color',
			$w1 * $first[1] + $w2 * $second[1],
			$w1 * $first[2] + $w2 * $second[2],
			$w1 * $first[3] + $w2 * $second[3],
		);

		if ($firstAlpha != 1.0 || $secondAlpha != 1.0) {
			$new[] = $firstAlpha * $weight + $secondAlpha * ($weight - 1);
		}

		return $this->fixColor($new);
	}

	protected static $lib_hsl = array("hue", "saturation", "lightness");
	protected function lib_hsl($args) {
		list($h, $s, $l) = $args;
		return $this->toRGB($h[1], $s[1], $l[1]);
	}

	protected static $lib_hsla = array("hue", "saturation",
		"lightness", "alpha");
	protected function lib_hsla($args) {
		list($h, $s, $l, $a) = $args;
		$color = $this->toRGB($h[1], $s[1], $l[1]);
		$color[4] = $a[1];
		return $color;
	}

	protected static $lib_hue = array("color");
	protected function lib_hue($args) {
		$color = $this->assertColor($args[0]);
		$hsl = $this->toHSL($color[1], $color[2], $color[3]);
		return array("number", $hsl[1], "deg");
	}

	protected static $lib_saturation = array("color");
	protected function lib_saturation($args) {
		$color = $this->assertColor($args[0]);
		$hsl = $this->toHSL($color[1], $color[2], $color[3]);
		return array("number", $hsl[2], "%");
	}

	protected static $lib_lightness = array("color");
	protected function lib_lightness($args) {
		$color = $this->assertColor($args[0]);
		$hsl = $this->toHSL($color[1], $color[2], $color[3]);
		return array("number", $hsl[3], "%");
	}

	protected function adjustHsl($color, $idx, $amount) {
		$hsl = $this->toHSL($color[1], $color[2], $color[3]);
		$hsl[$idx] += $amount;
		$out = $this->toRGB($hsl[1], $hsl[2], $hsl[3]);
		if (isset($color[4])) $out[4] = $color[4];
		return $out;
	}

	protected static $lib_adjust_hue = array("color", "degrees");
	protected function lib_adjust_hue($args) {
		$color = $this->assertColor($args[0]);
		$degrees = $this->assertNumber($args[1]);
		return $this->adjustHsl($color, 1, $degrees);
	}

	protected static $lib_lighten = array("color", "amount");
	protected function lib_lighten($args) {
		$color = $this->assertColor($args[0]);
		$amount = 100*$this->coercePercent($args[1]);
		return $this->adjustHsl($color, 3, $amount);
	}

	protected static $lib_darken = array("color", "amount");
	protected function lib_darken($args) {
		$color = $this->assertColor($args[0]);
		$amount = 100*$this->coercePercent($args[1]);
		return $this->adjustHsl($color, 3, -$amount);
	}

	protected static $lib_saturate = array("color", "amount");
	protected function lib_saturate($args) {
		$value = $args[0];
		if ($value[0] === 'number') return null;
		$color = $this->assertColor($value);
		$amount = 100*$this->coercePercent($args[1]);
		return $this->adjustHsl($color, 2, $amount);
	}

	protected static $lib_desaturate = array("color", "amount");
	protected function lib_desaturate($args) {
		$color = $this->assertColor($args[0]);
		$amount = 100*$this->coercePercent($args[1]);
		return $this->adjustHsl($color, 2, -$amount);
	}

	protected static $lib_grayscale = array("color");
	protected function lib_grayscale($args) {
		$value = $args[0];
		if ($value[0] === 'number') return null;
		return $this->adjustHsl($this->assertColor($value), 2, -100);
	}

	protected static $lib_complement = array("color");
	protected function lib_complement($args) {
		return $this->adjustHsl($this->assertColor($args[0]), 1, 180);
	}

	protected static $lib_invert = array("color");
	protected function lib_invert($args) {
		$value = $args[0];
		if ($value[0] === 'number') return null;
		$color = $this->assertColor($value);
		$color[1] = 255 - $color[1];
		$color[2] = 255 - $color[2];
		$color[3] = 255 - $color[3];
		return $color;
	}

	// increases opacity by amount
	protected static $lib_opacify = array("color", "amount");
	protected function lib_opacify($args) {
		$color = $this->assertColor($args[0]);
		$amount = $this->coercePercent($args[1]);

		$color[4] = (isset($color[4]) ? $color[4] : 1) + $amount;
		$color[4] = min(1, max(0, $color[4]));
		return $color;
	}

	protected static $lib_fade_in = array("color", "amount");
	protected function lib_fade_in($args) {
		return $this->lib_opacify($args);
	}

	// decreases opacity by amount
	protected static $lib_transparentize = array("color", "amount");
	protected function lib_transparentize($args) {
		$color = $this->assertColor($args[0]);
		$amount = $this->coercePercent($args[1]);

		$color[4] = (isset($color[4]) ? $color[4] : 1) - $amount;
		$color[4] = min(1, max(0, $color[4]));
		return $color;
	}

	protected static $lib_fade_out = array("color", "amount");
	protected function lib_fade_out($args) {
		return $this->lib_transparentize($args);
	}

	protected static $lib_unquote = array("string");
	protected function lib_unquote($args) {
		$str = $args[0];
		if ($str[0] == "string") $str[1] = "";
		return $str;
	}

	protected static $lib_quote = array("string");
	protected function lib_quote($args) {
		$value = $args[0];
		if ($value[0] == "string" && !empty($value[1]))
			return $value;
		return array("string", '"', array($value));
	}

	protected static $lib_percentage = array("value");
	protected function lib_percentage($args) {
		return array("number",
			$this->coercePercent($args[0]) * 100,
			"%");
	}

	protected static $lib_round = array("value");
	protected function lib_round($args) {
		$num = $args[0];
		$num[1] = round($num[1]);
		return $num;
	}

	protected static $lib_floor = array("value");
	protected function lib_floor($args) {
		$num = $args[0];
		$num[1] = floor($num[1]);
		return $num;
	}

	protected static $lib_ceil = array("value");
	protected function lib_ceil($args) {
		$num = $args[0];
		$num[1] = ceil($num[1]);
		return $num;
	}

	protected static $lib_abs = array("value");
	protected function lib_abs($args) {
		$num = $args[0];
		$num[1] = abs($num[1]);
		return $num;
	}

	protected function lib_min($args) {
		$numbers = $this->getNormalizedNumbers($args);
		$min = null;
		foreach ($numbers as $key => $number) {
			if (null === $min || $number[1] <= $min[1]) {
				$min = array($key, $number[1]);
			}
		}

		return $args[$min[0]];
	}

	protected function lib_max($args) {
		$numbers = $this->getNormalizedNumbers($args);
		$max = null;
		foreach ($numbers as $key => $number) {
			if (null === $max || $number[1] >= $max[1]) {
				$max = array($key, $number[1]);
			}
		}

		return $args[$max[0]];
	}

	protected function getNormalizedNumbers($args) {
		$unit = null;
		$originalUnit = null;
		$numbers = array();
		foreach ($args as $key => $item) {
			if ('number' != $item[0]) {
				$this->throwError("%s is not a number", $item[0]);
			}
			$number = $this->normalizeNumber($item);

			if (null === $unit) {
				$unit = $number[2];
				$originalUnit = $item[2];
			} elseif ($unit !== $number[2]) {
				$this->throwError('Incompatible units: "%s" and "%s".', $originalUnit, $item[2]);
			}

			$numbers[$key] = $number;
		}

		return $numbers;
	}

	protected static $lib_length = array("list");
	protected function lib_length($args) {
		$list = $this->coerceList($args[0]);
		return count($list[2]);
	}

	protected static $lib_nth = array("list", "n");
	protected function lib_nth($args) {
		$list = $this->coerceList($args[0]);
		$n = $this->assertNumber($args[1]) - 1;
		return isset($list[2][$n]) ? $list[2][$n] : self::$defaultValue;
	}

	protected function listSeparatorForJoin($list1, $sep) {
		if (is_null($sep)) return $list1[1];
		switch ($this->compileValue($sep)) {
		case "comma":
			return ",";
		case "space":
			return "";
		default:
			return $list1[1];
		}
	}

	protected static $lib_join = array("list1", "list2", "separator");
	protected function lib_join($args) {
		list($list1, $list2, $sep) = $args;
		$list1 = $this->coerceList($list1, " ");
		$list2 = $this->coerceList($list2, " ");
		$sep = $this->listSeparatorForJoin($list1, $sep);
		return array("list", $sep, array_merge($list1[2], $list2[2]));
	}

	protected static $lib_append = array("list", "val", "separator");
	protected function lib_append($args) {
		list($list1, $value, $sep) = $args;
		$list1 = $this->coerceList($list1, " ");
		$sep = $this->listSeparatorForJoin($list1, $sep);
		return array("list", $sep, array_merge($list1[2], array($value)));
	}

	protected function lib_zip($args) {
		foreach ($args as $arg) {
			$this->assertList($arg);
		}

		$lists = array();
		$firstList = array_shift($args);
		foreach ($firstList[2] as $key => $item) {
			$list = array("list", "", array($item));
			foreach ($args as $arg) {
				if (isset($arg[2][$key])) {
					$list[2][] = $arg[2][$key];
				} else {
					break 2;
				}
			}
			$lists[] = $list;
		}

		return array("list", ",", $lists);
	}

	protected static $lib_type_of = array("value");
	protected function lib_type_of($args) {
		$value = $args[0];
		switch ($value[0]) {
		case "keyword":
			if ($value == self::$true || $value == self::$false) {
				return "bool";
			}

			if ($this->coerceColor($value)) {
				return "color";
			}

			return "string";
		default:
			return $value[0];
		}
	}

	protected static $lib_unit = array("number");
	protected function lib_unit($args) {
		$num = $args[0];
		if ($num[0] == "number") {
			return array("string", '"', array($num[2]));
		}
		return "";
	}

	protected static $lib_unitless = array("number");
	protected function lib_unitless($args) {
		$value = $args[0];
		return $value[0] == "number" && empty($value[2]);
	}

	protected static $lib_comparable = array("number-1", "number-2");
	protected function lib_comparable($args) {
		list($number1, $number2) = $args;
		if (!isset($number1[0]) || $number1[0] != "number" || !isset($number2[0]) || $number2[0] != "number") {
			$this->throwError('Invalid argument(s) for "comparable"');
		}

		$number1 = $this->normalizeNumber($number1);
		$number2 = $this->normalizeNumber($number2);

		return $number1[2] == $number2[2] || $number1[2] == "" || $number2[2] == "";
	}

	/**
	 * Workaround IE7's content counter bug.
	 *
	 * @param array $args
	 */
	protected function lib_counter($args) {
		$list = array_map(array($this, 'compileValue'), $args);
		return array('string', '', array('counter(' . implode(',', $list) . ')'));
	}

	public function throwError($msg = null) {
		if (func_num_args() > 1) {
			$msg = call_user_func_array("sprintf", func_get_args());
		}

		if ($this->sourcePos >= 0 && isset($this->sourceParser)) {
			$this->sourceParser->throwParseError($msg, $this->sourcePos);
		}

		throw new Exception($msg);
	}

	/**
	 * CSS Colors
	 *
	 * @see http://www.w3.org/TR/css3-color
	 */
	static protected $cssColors = array(
		'aliceblue' => '240,248,255',
		'antiquewhite' => '250,235,215',
		'aqua' => '0,255,255',
		'aquamarine' => '127,255,212',
		'azure' => '240,255,255',
		'beige' => '245,245,220',
		'bisque' => '255,228,196',
		'black' => '0,0,0',
		'blanchedalmond' => '255,235,205',
		'blue' => '0,0,255',
		'blueviolet' => '138,43,226',
		'brown' => '165,42,42',
		'burlywood' => '222,184,135',
		'cadetblue' => '95,158,160',
		'chartreuse' => '127,255,0',
		'chocolate' => '210,105,30',
		'coral' => '255,127,80',
		'cornflowerblue' => '100,149,237',
		'cornsilk' => '255,248,220',
		'crimson' => '220,20,60',
		'cyan' => '0,255,255',
		'darkblue' => '0,0,139',
		'darkcyan' => '0,139,139',
		'darkgoldenrod' => '184,134,11',
		'darkgray' => '169,169,169',
		'darkgreen' => '0,100,0',
		'darkgrey' => '169,169,169',
		'darkkhaki' => '189,183,107',
		'darkmagenta' => '139,0,139',
		'darkolivegreen' => '85,107,47',
		'darkorange' => '255,140,0',
		'darkorchid' => '153,50,204',
		'darkred' => '139,0,0',
		'darksalmon' => '233,150,122',
		'darkseagreen' => '143,188,143',
		'darkslateblue' => '72,61,139',
		'darkslategray' => '47,79,79',
		'darkslategrey' => '47,79,79',
		'darkturquoise' => '0,206,209',
		'darkviolet' => '148,0,211',
		'deeppink' => '255,20,147',
		'deepskyblue' => '0,191,255',
		'dimgray' => '105,105,105',
		'dimgrey' => '105,105,105',
		'dodgerblue' => '30,144,255',
		'firebrick' => '178,34,34',
		'floralwhite' => '255,250,240',
		'forestgreen' => '34,139,34',
		'fuchsia' => '255,0,255',
		'gainsboro' => '220,220,220',
		'ghostwhite' => '248,248,255',
		'gold' => '255,215,0',
		'goldenrod' => '218,165,32',
		'gray' => '128,128,128',
		'green' => '0,128,0',
		'greenyellow' => '173,255,47',
		'grey' => '128,128,128',
		'honeydew' => '240,255,240',
		'hotpink' => '255,105,180',
		'indianred' => '205,92,92',
		'indigo' => '75,0,130',
		'ivory' => '255,255,240',
		'khaki' => '240,230,140',
		'lavender' => '230,230,250',
		'lavenderblush' => '255,240,245',
		'lawngreen' => '124,252,0',
		'lemonchiffon' => '255,250,205',
		'lightblue' => '173,216,230',
		'lightcoral' => '240,128,128',
		'lightcyan' => '224,255,255',
		'lightgoldenrodyellow' => '250,250,210',
		'lightgray' => '211,211,211',
		'lightgreen' => '144,238,144',
		'lightgrey' => '211,211,211',
		'lightpink' => '255,182,193',
		'lightsalmon' => '255,160,122',
		'lightseagreen' => '32,178,170',
		'lightskyblue' => '135,206,250',
		'lightslategray' => '119,136,153',
		'lightslategrey' => '119,136,153',
		'lightsteelblue' => '176,196,222',
		'lightyellow' => '255,255,224',
		'lime' => '0,255,0',
		'limegreen' => '50,205,50',
		'linen' => '250,240,230',
		'magenta' => '255,0,255',
		'maroon' => '128,0,0',
		'mediumaquamarine' => '102,205,170',
		'mediumblue' => '0,0,205',
		'mediumorchid' => '186,85,211',
		'mediumpurple' => '147,112,219',
		'mediumseagreen' => '60,179,113',
		'mediumslateblue' => '123,104,238',
		'mediumspringgreen' => '0,250,154',
		'mediumturquoise' => '72,209,204',
		'mediumvioletred' => '199,21,133',
		'midnightblue' => '25,25,112',
		'mintcream' => '245,255,250',
		'mistyrose' => '255,228,225',
		'moccasin' => '255,228,181',
		'navajowhite' => '255,222,173',
		'navy' => '0,0,128',
		'oldlace' => '253,245,230',
		'olive' => '128,128,0',
		'olivedrab' => '107,142,35',
		'orange' => '255,165,0',
		'orangered' => '255,69,0',
		'orchid' => '218,112,214',
		'palegoldenrod' => '238,232,170',
		'palegreen' => '152,251,152',
		'paleturquoise' => '175,238,238',
		'palevioletred' => '219,112,147',
		'papayawhip' => '255,239,213',
		'peachpuff' => '255,218,185',
		'peru' => '205,133,63',
		'pink' => '255,192,203',
		'plum' => '221,160,221',
		'powderblue' => '176,224,230',
		'purple' => '128,0,128',
		'red' => '255,0,0',
		'rosybrown' => '188,143,143',
		'royalblue' => '65,105,225',
		'saddlebrown' => '139,69,19',
		'salmon' => '250,128,114',
		'sandybrown' => '244,164,96',
		'seagreen' => '46,139,87',
		'seashell' => '255,245,238',
		'sienna' => '160,82,45',
		'silver' => '192,192,192',
		'skyblue' => '135,206,235',
		'slateblue' => '106,90,205',
		'slategray' => '112,128,144',
		'slategrey' => '112,128,144',
		'snow' => '255,250,250',
		'springgreen' => '0,255,127',
		'steelblue' => '70,130,180',
		'tan' => '210,180,140',
		'teal' => '0,128,128',
		'thistle' => '216,191,216',
		'tomato' => '255,99,71',
		'transparent' => '0,0,0,0',
		'turquoise' => '64,224,208',
		'violet' => '238,130,238',
		'wheat' => '245,222,179',
		'white' => '255,255,255',
		'whitesmoke' => '245,245,245',
		'yellow' => '255,255,0',
		'yellowgreen' => '154,205,50'
	);
}

/**
 * SCSS parser
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class scss_parser {
	static protected $precedence = array(
		"or" => 0,
		"and" => 1,

		'==' => 2,
		'!=' => 2,
		'<=' => 2,
		'>=' => 2,
		'=' => 2,
		'<' => 3,
		'>' => 2,

		'+' => 3,
		'-' => 3,
		'*' => 4,
		'/' => 4,
		'%' => 4,
	);

	static protected $operators = array("+", "-", "*", "/", "%",
		"==", "!=", "<=", ">=", "<", ">", "and", "or");

	static protected $operatorStr;
	static protected $whitePattern;
	static protected $commentMulti;

	static protected $commentSingle = "//";
	static protected $commentMultiLeft = "/*";
	static protected $commentMultiRight = "*/";

	public function __construct($sourceName = null, $rootParser = true) {
		$this->sourceName = $sourceName;
		$this->rootParser = $rootParser;

		if (empty(self::$operatorStr)) {
			self::$operatorStr = $this->makeOperatorStr(self::$operators);

			$commentSingle = $this->preg_quote(self::$commentSingle);
			$commentMultiLeft = $this->preg_quote(self::$commentMultiLeft);
			$commentMultiRight = $this->preg_quote(self::$commentMultiRight);
			self::$commentMulti = $commentMultiLeft.'.*?'.$commentMultiRight;
			self::$whitePattern = '/'.$commentSingle.'[^\n]*\s*|('.self::$commentMulti.')\s*|\s+/Ais';
		}
	}

	static protected function makeOperatorStr($operators) {
		return '('.implode('|', array_map(array('scss_parser','preg_quote'),
			$operators)).')';
	}

	public function parse($buffer) {
		$this->count = 0;
		$this->env = null;
		$this->inParens = false;
		$this->pushBlock(null); // root block
		$this->eatWhiteDefault = true;
		$this->insertComments = true;

		$this->buffer = $buffer;

		$this->whitespace();
		while (false !== $this->parseChunk());

		if ($this->count != strlen($this->buffer))
			$this->throwParseError();

		if (!empty($this->env->parent)) {
			$this->throwParseError("unclosed block");
		}

		$this->env->isRoot = true;
		return $this->env;
	}

	/**
	 * Parse a single chunk off the head of the buffer and append it to the
	 * current parse environment.
	 *
	 * Returns false when the buffer is empty, or when there is an error.
	 *
	 * This function is called repeatedly until the entire document is
	 * parsed.
	 *
	 * This parser is most similar to a recursive descent parser. Single
	 * functions represent discrete grammatical rules for the language, and
	 * they are able to capture the text that represents those rules.
	 *
	 * Consider the function scssc::keyword(). (All parse functions are
	 * structured the same.)
	 *
	 * The function takes a single reference argument. When calling the
	 * function it will attempt to match a keyword on the head of the buffer.
	 * If it is successful, it will place the keyword in the referenced
	 * argument, advance the position in the buffer, and return true. If it
	 * fails then it won't advance the buffer and it will return false.
	 *
	 * All of these parse functions are powered by scssc::match(), which behaves
	 * the same way, but takes a literal regular expression. Sometimes it is
	 * more convenient to use match instead of creating a new function.
	 *
	 * Because of the format of the functions, to parse an entire string of
	 * grammatical rules, you can chain them together using &&.
	 *
	 * But, if some of the rules in the chain succeed before one fails, then
	 * the buffer position will be left at an invalid state. In order to
	 * avoid this, scssc::seek() is used to remember and set buffer positions.
	 *
	 * Before parsing a chain, use $s = $this->seek() to remember the current
	 * position into $s. Then if a chain fails, use $this->seek($s) to
	 * go back where we started.
	 *
	 * @return boolean
	 */
	protected function parseChunk() {
		$s = $this->seek();

		// the directives
		if (isset($this->buffer[$this->count]) && $this->buffer[$this->count] == "@") {
			if ($this->literal("@media") && $this->mediaQueryList($mediaQueryList) && $this->literal("{")) {
				$media = $this->pushSpecialBlock("media");
				$media->queryList = $mediaQueryList[2];
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@mixin") &&
				$this->keyword($mixinName) &&
				($this->argumentDef($args) || true) &&
				$this->literal("{"))
			{
				$mixin = $this->pushSpecialBlock("mixin");
				$mixin->name = $mixinName;
				$mixin->args = $args;
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@include") &&
				$this->keyword($mixinName) &&
				($this->literal("(") &&
					($this->argValues($argValues) || true) &&
					$this->literal(")") || true) &&
				($this->end() ||
					$this->literal("{") && $hasBlock = true))
			{
				$child = array("include",
					$mixinName, isset($argValues) ? $argValues : null, null);

				if (!empty($hasBlock)) {
					$include = $this->pushSpecialBlock("include");
					$include->child = $child;
				} else {
					$this->append($child, $s);
				}

				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@import") &&
				$this->valueList($importPath) &&
				$this->end())
			{
				$this->append(array("import", $importPath), $s);
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@extend") &&
				$this->selectors($selector) &&
				$this->end())
			{
				$this->append(array("extend", $selector), $s);
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@function") &&
				$this->keyword($fnName) &&
				$this->argumentDef($args) &&
				$this->literal("{"))
			{
				$func = $this->pushSpecialBlock("function");
				$func->name = $fnName;
				$func->args = $args;
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@return") && $this->valueList($retVal) && $this->end()) {
				$this->append(array("return", $retVal), $s);
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@each") &&
				$this->variable($varName) &&
				$this->literal("in") &&
				$this->valueList($list) &&
				$this->literal("{"))
			{
				$each = $this->pushSpecialBlock("each");
				$each->var = $varName[1];
				$each->list = $list;
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@while") &&
				$this->expression($cond) &&
				$this->literal("{"))
			{
				$while = $this->pushSpecialBlock("while");
				$while->cond = $cond;
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@for") &&
				$this->variable($varName) &&
				$this->literal("from") &&
				$this->expression($start) &&
				($this->literal("through") ||
					($forUntil = true && $this->literal("to"))) &&
				$this->expression($end) &&
				$this->literal("{"))
			{
				$for = $this->pushSpecialBlock("for");
				$for->var = $varName[1];
				$for->start = $start;
				$for->end = $end;
				$for->until = isset($forUntil);
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@if") && $this->valueList($cond) && $this->literal("{")) {
				$if = $this->pushSpecialBlock("if");
				$if->cond = $cond;
				$if->cases = array();
				return true;
			} else {
				$this->seek($s);
			}

			if (($this->literal("@debug") || $this->literal("@warn")) &&
				$this->valueList($value) &&
				$this->end()) {
				$this->append(array("debug", $value, $s), $s);
				return true;
			} else {
				$this->seek($s);
			}

			if ($this->literal("@content") && $this->end()) {
				$this->append(array("mixin_content"), $s);
				return true;
			} else {
				$this->seek($s);
			}

			$last = $this->last();
			if (!is_null($last) && $last[0] == "if") {
				list(, $if) = $last;
				if ($this->literal("@else")) {
					if ($this->literal("{")) {
						$else = $this->pushSpecialBlock("else");
					} elseif ($this->literal("if") && $this->valueList($cond) && $this->literal("{")) {
						$else = $this->pushSpecialBlock("elseif");
						$else->cond = $cond;
					}

					if (isset($else)) {
						$else->dontAppend = true;
						$if->cases[] = $else;
						return true;
					}
				}

				$this->seek($s);
			}

			if ($this->literal("@charset") &&
				$this->valueList($charset) && $this->end())
			{
				$this->append(array("charset", $charset), $s);
				return true;
			} else {
				$this->seek($s);
			}

			// doesn't match built in directive, do generic one
			if ($this->literal("@", false) && $this->keyword($dirName) &&
				($this->openString("{", $dirValue) || true) &&
				$this->literal("{"))
			{
				$directive = $this->pushSpecialBlock("directive");
				$directive->name = $dirName;
				if (isset($dirValue)) $directive->value = $dirValue;
				return true;
			}

			$this->seek($s);
			return false;
		}

		// property shortcut
		// captures most properties before having to parse a selector
		if ($this->keyword($name, false) &&
			$this->literal(": ") &&
			$this->valueList($value) &&
			$this->end())
		{
			$name = array("string", "", array($name));
			$this->append(array("assign", $name, $value), $s);
			return true;
		} else {
			$this->seek($s);
		}

		// variable assigns
		if ($this->variable($name) &&
			$this->literal(":") &&
			$this->valueList($value) && $this->end())
		{
			// check for !default
			$defaultVar = $value[0] == "list" && $this->stripDefault($value);
			$this->append(array("assign", $name, $value, $defaultVar), $s);
			return true;
		} else {
			$this->seek($s);
		}

		// misc
		if ($this->literal("-->")) {
			return true;
		}

		// opening css block
		$oldComments = $this->insertComments;
		$this->insertComments = false;
		if ($this->selectors($selectors) && $this->literal("{")) {
			$this->pushBlock($selectors);
			$this->insertComments = $oldComments;
			return true;
		} else {
			$this->seek($s);
		}
		$this->insertComments = $oldComments;

		// property assign, or nested assign
		if ($this->propertyName($name) && $this->literal(":")) {
			$foundSomething = false;
			if ($this->valueList($value)) {
				$this->append(array("assign", $name, $value), $s);
				$foundSomething = true;
			}

			if ($this->literal("{")) {
				$propBlock = $this->pushSpecialBlock("nestedprop");
				$propBlock->prefix = $name;
				$foundSomething = true;
			} elseif ($foundSomething) {
				$foundSomething = $this->end();
			}

			if ($foundSomething) {
				return true;
			}

			$this->seek($s);
		} else {
			$this->seek($s);
		}

		// closing a block
		if ($this->literal("}")) {
			$block = $this->popBlock();
			if (isset($block->type) && $block->type == "include") {
				$include = $block->child;
				unset($block->child);
				$include[3] = $block;
				$this->append($include, $s);
			} elseif (empty($block->dontAppend)) {
				$type = isset($block->type) ? $block->type : "block";
				$this->append(array($type, $block), $s);
			}
			return true;
		}

		// extra stuff
		if ($this->literal(";") ||
			$this->literal("<!--"))
		{
			return true;
		}

		return false;
	}

	protected function stripDefault(&$value) {
		$def = end($value[2]);
		if ($def[0] == "keyword" && $def[1] == "!default") {
			array_pop($value[2]);
			$value = $this->flattenList($value);
			return true;
		}

		if ($def[0] == "list") {
			return $this->stripDefault($value[2][count($value[2]) - 1]);
		}

		return false;
	}

	protected function literal($what, $eatWhitespace = null) {
		if (is_null($eatWhitespace)) $eatWhitespace = $this->eatWhiteDefault;

		// shortcut on single letter
		if (!isset($what[1]) && isset($this->buffer[$this->count])) {
			if ($this->buffer[$this->count] == $what) {
				if (!$eatWhitespace) {
					$this->count++;
					return true;
				}
				// goes below...
			} else {
				return false;
			}
		}

		return $this->match($this->preg_quote($what), $m, $eatWhitespace);
	}

	// tree builders

	protected function pushBlock($selectors) {
		$b = new stdClass;
		$b->parent = $this->env; // not sure if we need this yet

		$b->selectors = $selectors;
		$b->children = array();

		$this->env = $b;
		return $b;
	}

	protected function pushSpecialBlock($type) {
		$block = $this->pushBlock(null);
		$block->type = $type;
		return $block;
	}

	protected function popBlock() {
		if (empty($this->env->parent)) {
			$this->throwParseError("unexpected }");
		}

		$old = $this->env;
		$this->env = $this->env->parent;
		unset($old->parent);
		return $old;
	}

	protected function append($statement, $pos=null) {
		if ($pos !== null) {
			$statement[-1] = $pos;
			if (!$this->rootParser) $statement[-2] = $this;
		}
		$this->env->children[] = $statement;
	}

	// last child that was appended
	protected function last() {
		$i = count($this->env->children) - 1;
		if (isset($this->env->children[$i]))
			return $this->env->children[$i];
	}

	// high level parsers (they return parts of ast)

	protected function mediaQueryList(&$out) {
		return $this->genericList($out, "mediaQuery", ",", false);
	}

	protected function mediaQuery(&$out) {
		$s = $this->seek();

		$expressions = null;
		$parts = array();

		if (($this->literal("only") && ($only = true) || $this->literal("not") && ($not = true) || true) && $this->mixedKeyword($mediaType)) {
			$prop = array("mediaType");
			if (isset($only)) $prop[] = array("keyword", "only");
			if (isset($not)) $prop[] = array("keyword", "not");
			$media = array("list", "", array());
			foreach ((array)$mediaType as $type) {
				if (is_array($type)) {
					$media[2][] = $type;
				} else {
					$media[2][] = array("keyword", $type);
				}
			}
			$prop[] = $media;
			$parts[] = $prop;
		}

		if (empty($parts) || $this->literal("and")) {
			$this->genericList($expressions, "mediaExpression", "and", false);
			if (is_array($expressions)) $parts = array_merge($parts, $expressions[2]);
		}

		$out = $parts;
		return true;
	}

	protected function mediaExpression(&$out) {
		$s = $this->seek();
		$value = null;
		if ($this->literal("(") &&
			$this->expression($feature) &&
			($this->literal(":") && $this->expression($value) || true) &&
			$this->literal(")"))
		{
			$out = array("mediaExp", $feature);
			if ($value) $out[] = $value;
			return true;
		}

		$this->seek($s);
		return false;
	}

	protected function argValues(&$out) {
		if ($this->genericList($list, "argValue", ",", false)) {
			$out = $list[2];
			return true;
		}
		return false;
	}

	protected function argValue(&$out) {
		$s = $this->seek();

		$keyword = null;
		if (!$this->variable($keyword) || !$this->literal(":")) {
			$this->seek($s);
			$keyword = null;
		}

		if ($this->genericList($value, "expression")) {
			$out = array($keyword, $value, false);
			$s = $this->seek();
			if ($this->literal("...")) {
				$out[2] = true;
			} else {
				$this->seek($s);
			}
			return true;
		}

		return false;
	}


	protected function valueList(&$out) {
		return $this->genericList($out, "spaceList", ",");
	}

	protected function spaceList(&$out) {
		return $this->genericList($out, "expression");
	}

	protected function genericList(&$out, $parseItem, $delim="", $flatten=true) {
		$s = $this->seek();
		$items = array();
		while ($this->$parseItem($value)) {
			$items[] = $value;
			if ($delim) {
				if (!$this->literal($delim)) break;
			}
		}

		if (count($items) == 0) {
			$this->seek($s);
			return false;
		}

		if ($flatten && count($items) == 1) {
			$out = $items[0];
		} else {
			$out = array("list", $delim, $items);
		}

		return true;
	}

	protected function expression(&$out) {
		$s = $this->seek();

		if ($this->literal("(")) {
			if ($this->literal(")")) {
				$out = array("list", "", array());
				return true;
			}

			if ($this->valueList($out) && $this->literal(')') && $out[0] == "list") {
				return true;
			}

			$this->seek($s);
		}

		if ($this->value($lhs)) {
			$out = $this->expHelper($lhs, 0);
			return true;
		}

		return false;
	}

	protected function expHelper($lhs, $minP) {
		$opstr = self::$operatorStr;

		$ss = $this->seek();
		$whiteBefore = isset($this->buffer[$this->count - 1]) &&
			ctype_space($this->buffer[$this->count - 1]);
		while ($this->match($opstr, $m) && self::$precedence[$m[1]] >= $minP) {
			$whiteAfter = isset($this->buffer[$this->count - 1]) &&
				ctype_space($this->buffer[$this->count - 1]);

			$op = $m[1];

			// don't turn negative numbers into expressions
			if ($op == "-" && $whiteBefore) {
				if (!$whiteAfter) break;
			}

			if (!$this->value($rhs)) break;

			// peek and see if rhs belongs to next operator
			if ($this->peek($opstr, $next) && self::$precedence[$next[1]] > self::$precedence[$op]) {
				$rhs = $this->expHelper($rhs, self::$precedence[$next[1]]);
			}

			$lhs = array("exp", $op, $lhs, $rhs, $this->inParens, $whiteBefore, $whiteAfter);
			$ss = $this->seek();
			$whiteBefore = isset($this->buffer[$this->count - 1]) &&
				ctype_space($this->buffer[$this->count - 1]);
		}

		$this->seek($ss);
		return $lhs;
	}

	protected function value(&$out) {
		$s = $this->seek();

		if ($this->literal("not", false) && $this->whitespace() && $this->value($inner)) {
			$out = array("unary", "not", $inner, $this->inParens);
			return true;
		} else {
			$this->seek($s);
		}

		if ($this->literal("+") && $this->value($inner)) {
			$out = array("unary", "+", $inner, $this->inParens);
			return true;
		} else {
			$this->seek($s);
		}

		// negation
		if ($this->literal("-", false) &&
			($this->variable($inner) ||
			$this->unit($inner) ||
			$this->parenValue($inner)))
		{
			$out = array("unary", "-", $inner, $this->inParens);
			return true;
		} else {
			$this->seek($s);
		}

		if ($this->parenValue($out)) return true;
		if ($this->interpolation($out)) return true;
		if ($this->variable($out)) return true;
		if ($this->color($out)) return true;
		if ($this->unit($out)) return true;
		if ($this->string($out)) return true;
		if ($this->func($out)) return true;
		if ($this->progid($out)) return true;

		if ($this->keyword($keyword)) {
			if ($keyword == "null") {
				$out = array("null");
			} else {
				$out = array("keyword", $keyword);
			}
			return true;
		}

		return false;
	}

	// value wrappen in parentheses
	protected function parenValue(&$out) {
		$s = $this->seek();

		$inParens = $this->inParens;
		if ($this->literal("(") &&
			($this->inParens = true) && $this->expression($exp) &&
			$this->literal(")"))
		{
			$out = $exp;
			$this->inParens = $inParens;
			return true;
		} else {
			$this->inParens = $inParens;
			$this->seek($s);
		}

		return false;
	}

	protected function progid(&$out) {
		$s = $this->seek();
		if ($this->literal("progid:", false) &&
			$this->openString("(", $fn) &&
			$this->literal("("))
		{
			$this->openString(")", $args, "(");
			if ($this->literal(")")) {
				$out = array("string", "", array(
					"progid:", $fn, "(", $args, ")"
				));
				return true;
			}
		}

		$this->seek($s);
		return false;
	}

	protected function func(&$func) {
		$s = $this->seek();

		if ($this->keyword($name, false) &&
			$this->literal("("))
		{
			if ($name == "alpha" && $this->argumentList($args)) {
				$func = array("function", $name, array("string", "", $args));
				return true;
			}

			if ($name != "expression" && !preg_match("/^(-[a-z]+-)?calc$/", $name)) {
				$ss = $this->seek();
				if ($this->argValues($args) && $this->literal(")")) {
					$func = array("fncall", $name, $args);
					return true;
				}
				$this->seek($ss);
			}

			if (($this->openString(")", $str, "(") || true ) &&
				$this->literal(")"))
			{
				$args = array();
				if (!empty($str)) {
					$args[] = array(null, array("string", "", array($str)));
				}

				$func = array("fncall", $name, $args);
				return true;
			}
		}

		$this->seek($s);
		return false;
	}

	protected function argumentList(&$out) {
		$s = $this->seek();
		$this->literal("(");

		$args = array();
		while ($this->keyword($var)) {
			$ss = $this->seek();

			if ($this->literal("=") && $this->expression($exp)) {
				$args[] = array("string", "", array($var."="));
				$arg = $exp;
			} else {
				break;
			}

			$args[] = $arg;

			if (!$this->literal(",")) break;

			$args[] = array("string", "", array(", "));
		}

		if (!$this->literal(")") || !count($args)) {
			$this->seek($s);
			return false;
		}

		$out = $args;
		return true;
	}

	protected function argumentDef(&$out) {
		$s = $this->seek();
		$this->literal("(");

		$args = array();
		while ($this->variable($var)) {
			$arg = array($var[1], null, false);

			$ss = $this->seek();
			if ($this->literal(":") && $this->genericList($defaultVal, "expression")) {
				$arg[1] = $defaultVal;
			} else {
				$this->seek($ss);
			}

			$ss = $this->seek();
			if ($this->literal("...")) {
				$sss = $this->seek();
				if (!$this->literal(")")) {
					$this->throwParseError("... has to be after the final argument");
				}
				$arg[2] = true;
				$this->seek($sss);
			} else {
				$this->seek($ss);
			}

			$args[] = $arg;
			if (!$this->literal(",")) break;
		}

		if (!$this->literal(")")) {
			$this->seek($s);
			return false;
		}

		$out = $args;
		return true;
	}

	protected function color(&$out) {
		$color = array('color');

		if ($this->match('(#([0-9a-f]{6})|#([0-9a-f]{3}))', $m)) {
			if (isset($m[3])) {
				$num = $m[3];
				$width = 16;
			} else {
				$num = $m[2];
				$width = 256;
			}

			$num = hexdec($num);
			foreach (array(3,2,1) as $i) {
				$t = $num % $width;
				$num /= $width;

				$color[$i] = $t * (256/$width) + $t * floor(16/$width);
			}

			$out = $color;
			return true;
		}

		return false;
	}

	protected function unit(&$unit) {
		if ($this->match('([0-9]*(\.)?[0-9]+)([%a-zA-Z]+)?', $m)) {
			$unit = array("number", $m[1], empty($m[3]) ? "" : $m[3]);
			return true;
		}
		return false;
	}

	protected function string(&$out) {
		$s = $this->seek();
		if ($this->literal('"', false)) {
			$delim = '"';
		} elseif ($this->literal("'", false)) {
			$delim = "'";
		} else {
			return false;
		}

		$content = array();
		$oldWhite = $this->eatWhiteDefault;
		$this->eatWhiteDefault = false;

		while ($this->matchString($m, $delim)) {
			$content[] = $m[1];
			if ($m[2] == "#{") {
				$this->count -= strlen($m[2]);
				if ($this->interpolation($inter, false)) {
					$content[] = $inter;
				} else {
					$this->count += strlen($m[2]);
					$content[] = "#{"; // ignore it
				}
			} elseif ($m[2] == '\\') {
				$content[] = $m[2];
				if ($this->literal($delim, false)) {
					$content[] = $delim;
				}
			} else {
				$this->count -= strlen($delim);
				break; // delim
			}
		}

		$this->eatWhiteDefault = $oldWhite;

		if ($this->literal($delim)) {
			$out = array("string", $delim, $content);
			return true;
		}

		$this->seek($s);
		return false;
	}

	protected function mixedKeyword(&$out) {
		$s = $this->seek();

		$parts = array();

		$oldWhite = $this->eatWhiteDefault;
		$this->eatWhiteDefault = false;

		while (true) {
			if ($this->keyword($key)) {
				$parts[] = $key;
				continue;
			}

			if ($this->interpolation($inter)) {
				$parts[] = $inter;
				continue;
			}

			break;
		}

		$this->eatWhiteDefault = $oldWhite;

		if (count($parts) == 0) return false;

		if ($this->eatWhiteDefault) {
			$this->whitespace();
		}

		$out = $parts;
		return true;
	}

	// an unbounded string stopped by $end
	protected function openString($end, &$out, $nestingOpen=null) {
		$oldWhite = $this->eatWhiteDefault;
		$this->eatWhiteDefault = false;

		$stop = array("'", '"', "#{", $end);
		$stop = array_map(array($this, "preg_quote"), $stop);
		$stop[] = self::$commentMulti;

		$patt = '(.*?)('.implode("|", $stop).')';

		$nestingLevel = 0;

		$content = array();
		while ($this->match($patt, $m, false)) {
			if (isset($m[1]) && $m[1] !== '') {
				$content[] = $m[1];
				if ($nestingOpen) {
					$nestingLevel += substr_count($m[1], $nestingOpen);
				}
			}

			$tok = $m[2];

			$this->count-= strlen($tok);
			if ($tok == $end) {
				if ($nestingLevel == 0) {
					break;
				} else {
					$nestingLevel--;
				}
			}

			if (($tok == "'" || $tok == '"') && $this->string($str)) {
				$content[] = $str;
				continue;
			}

			if ($tok == "#{" && $this->interpolation($inter)) {
				$content[] = $inter;
				continue;
			}

			$content[] = $tok;
			$this->count+= strlen($tok);
		}

		$this->eatWhiteDefault = $oldWhite;

		if (count($content) == 0) return false;

		// trim the end
		if (is_string(end($content))) {
			$content[count($content) - 1] = rtrim(end($content));
		}

		$out = array("string", "", $content);
		return true;
	}

	// $lookWhite: save information about whitespace before and after
	protected function interpolation(&$out, $lookWhite=true) {
		$oldWhite = $this->eatWhiteDefault;
		$this->eatWhiteDefault = true;

		$s = $this->seek();
		if ($this->literal("#{") && $this->valueList($value) && $this->literal("}", false)) {

			// TODO: don't error if out of bounds

			if ($lookWhite) {
				$left = preg_match('/\s/', $this->buffer[$s - 1]) ? " " : "";
				$right = preg_match('/\s/', $this->buffer[$this->count]) ? " ": "";
			} else {
				$left = $right = false;
			}

			$out = array("interpolate", $value, $left, $right);
			$this->eatWhiteDefault = $oldWhite;
			if ($this->eatWhiteDefault) $this->whitespace();
			return true;
		}

		$this->seek($s);
		$this->eatWhiteDefault = $oldWhite;
		return false;
	}

	// low level parsers

	// returns an array of parts or a string
	protected function propertyName(&$out) {
		$s = $this->seek();
		$parts = array();

		$oldWhite = $this->eatWhiteDefault;
		$this->eatWhiteDefault = false;

		while (true) {
			if ($this->interpolation($inter)) {
				$parts[] = $inter;
			} elseif ($this->keyword($text)) {
				$parts[] = $text;
			} elseif (count($parts) == 0 && $this->match('[:.#]', $m, false)) {
				// css hacks
				$parts[] = $m[0];
			} else {
				break;
			}
		}

		$this->eatWhiteDefault = $oldWhite;
		if (count($parts) == 0) return false;

		// match comment hack
		if (preg_match(self::$whitePattern,
			$this->buffer, $m, null, $this->count))
		{
			if (!empty($m[0])) {
				$parts[] = $m[0];
				$this->count += strlen($m[0]);
			}
		}

		$this->whitespace(); // get any extra whitespace

		$out = array("string", "", $parts);
		return true;
	}

	// comma separated list of selectors
	protected function selectors(&$out) {
		$s = $this->seek();
		$selectors = array();
		while ($this->selector($sel)) {
			$selectors[] = $sel;
			if (!$this->literal(",")) break;
			while ($this->literal(",")); // ignore extra
		}

		if (count($selectors) == 0) {
			$this->seek($s);
			return false;
		}

		$out = $selectors;
		return true;
	}

	// whitespace separated list of selectorSingle
	protected function selector(&$out) {
		$selector = array();

		while (true) {
			if ($this->match('[>+~]+', $m)) {
				$selector[] = array($m[0]);
			} elseif ($this->selectorSingle($part)) {
				$selector[] = $part;
				$this->whitespace();
			} elseif ($this->match('\/[^\/]+\/', $m)) {
				$selector[] = array($m[0]);
			} else {
				break;
			}

		}

		if (count($selector) == 0) {
			return false;
		}

		$out = $selector;
		return true;
	}

	// the parts that make up
	// div[yes=no]#something.hello.world:nth-child(-2n+1)%placeholder
	protected function selectorSingle(&$out) {
		$oldWhite = $this->eatWhiteDefault;
		$this->eatWhiteDefault = false;

		$parts = array();

		if ($this->literal("*", false)) {
			$parts[] = "*";
		}

		while (true) {
			// see if we can stop early
			if ($this->match("\s*[{,]", $m)) {
				$this->count--;
				break;
			}

			$s = $this->seek();
			// self
			if ($this->literal("&", false)) {
				$parts[] = scssc::$selfSelector;
				continue;
			}

			if ($this->literal(".", false)) {
				$parts[] = ".";
				continue;
			}

			if ($this->literal("|", false)) {
				$parts[] = "|";
				continue;
			}

			// for keyframes
			if ($this->unit($unit)) {
				$parts[] = $unit;
				continue;
			}

			if ($this->keyword($name)) {
				$parts[] = $name;
				continue;
			}

			if ($this->interpolation($inter)) {
				$parts[] = $inter;
				continue;
			}

			if ($this->literal('%', false) && $this->placeholder($placeholder)) {
				$parts[] = '%';
				$parts[] = $placeholder;
				continue;
			}

			if ($this->literal("#", false)) {
				$parts[] = "#";
				continue;
			}

			// a pseudo selector
			if ($this->match("::?", $m) && $this->mixedKeyword($nameParts)) {
				$parts[] = $m[0];
				foreach ($nameParts as $sub) {
					$parts[] = $sub;
				}

				$ss = $this->seek();
				if ($this->literal("(") &&
					($this->openString(")", $str, "(") || true ) &&
					$this->literal(")"))
				{
					$parts[] = "(";
					if (!empty($str)) $parts[] = $str;
					$parts[] = ")";
				} else {
					$this->seek($ss);
				}

				continue;
			} else {
				$this->seek($s);
			}

			// attribute selector
			// TODO: replace with open string?
			if ($this->literal("[", false)) {
				$attrParts = array("[");
				// keyword, string, operator
				while (true) {
					if ($this->literal("]", false)) {
						$this->count--;
						break; // get out early
					}

					if ($this->match('\s+', $m)) {
						$attrParts[] = " ";
						continue;
					}
					if ($this->string($str)) {
						$attrParts[] = $str;
						continue;
					}

					if ($this->keyword($word)) {
						$attrParts[] = $word;
						continue;
					}

					if ($this->interpolation($inter, false)) {
						$attrParts[] = $inter;
						continue;
					}

					// operator, handles attr namespace too
					if ($this->match('[|-~\$\*\^=]+', $m)) {
						$attrParts[] = $m[0];
						continue;
					}

					break;
				}

				if ($this->literal("]", false)) {
					$attrParts[] = "]";
					foreach ($attrParts as $part) {
						$parts[] = $part;
					}
					continue;
				}
				$this->seek($s);
				// should just break here?
			}

			break;
		}

		$this->eatWhiteDefault = $oldWhite;

		if (count($parts) == 0) return false;

		$out = $parts;
		return true;
	}

	protected function variable(&$out) {
		$s = $this->seek();
		if ($this->literal("$", false) && $this->keyword($name)) {
			$out = array("var", $name);
			return true;
		}
		$this->seek($s);
		return false;
	}

	protected function keyword(&$word, $eatWhitespace = null) {
		if ($this->match('([\w_\-\*!"\'\\\\][\w\-_"\'\\\\]*)',
			$m, $eatWhitespace))
		{
			$word = $m[1];
			return true;
		}
		return false;
	}

	protected function placeholder(&$placeholder) {
		if ($this->match('([\w\-_]+)', $m)) {
			$placeholder = $m[1];
			return true;
		}
		return false;
	}

	// consume an end of statement delimiter
	protected function end() {
		if ($this->literal(';')) {
			return true;
		} elseif ($this->count == strlen($this->buffer) || $this->buffer[$this->count] == '}') {
			// if there is end of file or a closing block next then we don't need a ;
			return true;
		}
		return false;
	}

	// advance counter to next occurrence of $what
	// $until - don't include $what in advance
	// $allowNewline, if string, will be used as valid char set
	protected function to($what, &$out, $until = false, $allowNewline = false) {
		if (is_string($allowNewline)) {
			$validChars = $allowNewline;
		} else {
			$validChars = $allowNewline ? "." : "[^\n]";
		}
		if (!$this->match('('.$validChars.'*?)'.$this->preg_quote($what), $m, !$until)) return false;
		if ($until) $this->count -= strlen($what); // give back $what
		$out = $m[1];
		return true;
	}

	public function throwParseError($msg = "parse error", $count = null) {
		$count = is_null($count) ? $this->count : $count;

		$line = $this->getLineNo($count);

		if (!empty($this->sourceName)) {
			$loc = "$this->sourceName on line $line";
		} else {
			$loc = "line: $line";
		}

		if ($this->peek("(.*?)(\n|$)", $m, $count)) {
			throw new Exception("$msg: failed at `$m[1]` $loc");
		} else {
			throw new Exception("$msg: $loc");
		}
	}

	public function getLineNo($pos) {
		return 1 + substr_count(substr($this->buffer, 0, $pos), "\n");
	}

	/**
	 * Match string looking for either ending delim, escape, or string interpolation
	 *
	 * {@internal This is a workaround for preg_match's 250K string match limit. }}
	 *
	 * @param array  $m     Matches (passed by reference)
	 * @param string $delim Delimeter
	 *
	 * @return boolean True if match; false otherwise
	 */
	protected function matchString(&$m, $delim) {
		$token = null;

		$end = strpos($this->buffer, "\n", $this->count);
		if ($end === false) {
			$end = strlen($this->buffer);
		}

		// look for either ending delim, escape, or string interpolation
		foreach (array('#{', '\\', $delim) as $lookahead) {
			$pos = strpos($this->buffer, $lookahead, $this->count);
			if ($pos !== false && $pos < $end) {
				$end = $pos;
				$token = $lookahead;
			}
		}

		if (!isset($token)) {
			return false;
		}

		$match = substr($this->buffer, $this->count, $end - $this->count);
		$m = array(
			$match . $token,
			$match,
			$token
		);
		$this->count = $end + strlen($token);

		return true;
	}

	// try to match something on head of buffer
	protected function match($regex, &$out, $eatWhitespace = null) {
		if (is_null($eatWhitespace)) $eatWhitespace = $this->eatWhiteDefault;

		$r = '/'.$regex.'/Ais';
		if (preg_match($r, $this->buffer, $out, null, $this->count)) {
			$this->count += strlen($out[0]);
			if ($eatWhitespace) $this->whitespace();
			return true;
		}
		return false;
	}

	// match some whitespace
	protected function whitespace() {
		$gotWhite = false;
		while (preg_match(self::$whitePattern, $this->buffer, $m, null, $this->count)) {
			if ($this->insertComments) {
				if (isset($m[1]) && empty($this->commentsSeen[$this->count])) {
					$this->append(array("comment", $m[1]));
					$this->commentsSeen[$this->count] = true;
				}
			}
			$this->count += strlen($m[0]);
			$gotWhite = true;
		}
		return $gotWhite;
	}

	protected function peek($regex, &$out, $from=null) {
		if (is_null($from)) $from = $this->count;

		$r = '/'.$regex.'/Ais';
		$result = preg_match($r, $this->buffer, $out, null, $from);

		return $result;
	}

	protected function seek($where = null) {
		if ($where === null) return $this->count;
		else $this->count = $where;
		return true;
	}

	static function preg_quote($what) {
		return preg_quote($what, '/');
	}

	protected function show() {
		if ($this->peek("(.*?)(\n|$)", $m, $this->count)) {
			return $m[1];
		}
		return "";
	}

	// turn list of length 1 into value type
	protected function flattenList($value) {
		if ($value[0] == "list" && count($value[2]) == 1) {
			return $this->flattenList($value[2][0]);
		}
		return $value;
	}
}

/**
 * SCSS base formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class scss_formatter {
	public $indentChar = "  ";

	public $break = "\n";
	public $open = " {";
	public $close = "}";
	public $tagSeparator = ", ";
	public $assignSeparator = ": ";

	public function __construct() {
		$this->indentLevel = 0;
	}

	public function indentStr($n = 0) {
		return str_repeat($this->indentChar, max($this->indentLevel + $n, 0));
	}

	public function property($name, $value) {
		return $name . $this->assignSeparator . $value . ";";
	}

	protected function block($block) {
		if (empty($block->lines) && empty($block->children)) return;

		$inner = $pre = $this->indentStr();

		if (!empty($block->selectors)) {
			echo $pre .
				implode($this->tagSeparator, $block->selectors) .
				$this->open . $this->break;
			$this->indentLevel++;
			$inner = $this->indentStr();
		}

		if (!empty($block->lines)) {
			$glue = $this->break.$inner;
			echo $inner . implode($glue, $block->lines);
			if (!empty($block->children)) {
				echo $this->break;
			}
		}

		foreach ($block->children as $child) {
			$this->block($child);
		}

		if (!empty($block->selectors)) {
			$this->indentLevel--;
			if (empty($block->children)) echo $this->break;
			echo $pre . $this->close . $this->break;
		}
	}

	public function format($block) {
		ob_start();
		$this->block($block);
		$out = ob_get_clean();

		return $out;
	}
}

/**
 * SCSS nested formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class scss_formatter_nested extends scss_formatter {
	public $close = " }";

	// adjust the depths of all children, depth first
	public function adjustAllChildren($block) {
		// flatten empty nested blocks
		$children = array();
		foreach ($block->children as $i => $child) {
			if (empty($child->lines) && empty($child->children)) {
				if (isset($block->children[$i + 1])) {
					$block->children[$i + 1]->depth = $child->depth;
				}
				continue;
			}
			$children[] = $child;
		}

		$count = count($children);
		for ($i = 0; $i < $count; $i++) {
			$depth = $children[$i]->depth;
			$j = $i + 1;
			if (isset($children[$j]) && $depth < $children[$j]->depth) {
				$childDepth = $children[$j]->depth;
				for (; $j < $count; $j++) {
					if ($depth < $children[$j]->depth && $childDepth >= $children[$j]->depth) {
						$children[$j]->depth = $depth + 1;
					}
				}
			}
		}

		$block->children = $children;

		// make relative to parent
		foreach ($block->children as $child) {
			$this->adjustAllChildren($child);
			$child->depth = $child->depth - $block->depth;
		}
	}

	protected function block($block) {
		if ($block->type == "root") {
			$this->adjustAllChildren($block);
		}

		$inner = $pre = $this->indentStr($block->depth - 1);
		if (!empty($block->selectors)) {
			echo $pre .
				implode($this->tagSeparator, $block->selectors) .
				$this->open . $this->break;
			$this->indentLevel++;
			$inner = $this->indentStr($block->depth - 1);
		}

		if (!empty($block->lines)) {
			$glue = $this->break.$inner;
			echo $inner . implode($glue, $block->lines);
			if (!empty($block->children)) echo $this->break;
		}

		foreach ($block->children as $i => $child) {
			// echo "*** block: ".$block->depth." child: ".$child->depth."\n";
			$this->block($child);
			if ($i < count($block->children) - 1) {
				echo $this->break;

				if (isset($block->children[$i + 1])) {
					$next = $block->children[$i + 1];
					if ($next->depth == max($block->depth, 1) && $child->depth >= $next->depth) {
						echo $this->break;
					}
				}
			}
		}

		if (!empty($block->selectors)) {
			$this->indentLevel--;
			echo $this->close;
		}

		if ($block->type == "root") {
			echo $this->break;
		}
	}
}

/**
 * SCSS compressed formatter
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class scss_formatter_compressed extends scss_formatter {
	public $open = "{";
	public $tagSeparator = ",";
	public $assignSeparator = ":";
	public $break = "";

	public function indentStr($n = 0) {
		return "";
	}
}

/**
 * SCSS server
 *
 * @author Leaf Corcoran <leafot@gmail.com>
 */
class scss_server {
	/**
	 * Join path components
	 *
	 * @param string $left  Path component, left of the directory separator
	 * @param string $right Path component, right of the directory separator
	 *
	 * @return string
	 */
	protected function join($left, $right) {
		return rtrim($left, '/\\') . DIRECTORY_SEPARATOR . ltrim($right, '/\\');
	}

	/**
	 * Get name of requested .scss file
	 *
	 * @return string|null
	 */
	protected function inputName() {
		switch (true) {
			case isset($_GET['p']):
				return $_GET['p'];
			case isset($_SERVER['PATH_INFO']):
				return $_SERVER['PATH_INFO'];
			case isset($_SERVER['DOCUMENT_URI']):
				return substr($_SERVER['DOCUMENT_URI'], strlen($_SERVER['SCRIPT_NAME']));
		}
	}

	/**
	 * Get path to requested .scss file
	 *
	 * @return string
	 */
	protected function findInput() {
		if (($input = $this->inputName())
			&& strpos($input, '..') === false
			&& substr($input, -5) === '.scss'
		) {
			$name = $this->join($this->dir, $input);

			if (is_file($name) && is_readable($name)) {
				return $name;
			}
		}

		return false;
	}

	/**
	 * Get path to cached .css file
	 *
	 * @return string
	 */
	protected function cacheName($fname) {
		return $this->join($this->cacheDir, md5($fname) . '.css');
	}

	/**
	 * Get path to cached imports
	 *
	 * @return string
	 */
	protected function importsCacheName($out) {
		return $out . '.imports';
	}

	/**
	 * Determine whether .scss file needs to be re-compiled.
	 *
	 * @param string $in  Input path
	 * @param string $out Output path
	 *
	 * @return boolean True if compile required.
	 */
	protected function needsCompile($in, $out) {
		if (!is_file($out)) return true;

		$mtime = filemtime($out);
		if (filemtime($in) > $mtime) return true;

		// look for modified imports
		$icache = $this->importsCacheName($out);
		if (is_readable($icache)) {
			$imports = unserialize(file_get_contents($icache));
			foreach ($imports as $import) {
				if (filemtime($import) > $mtime) return true;
			}
		}
		return false;
	}

	/**
	 * Compile .scss file
	 *
	 * @param string $in  Input path (.scss)
	 * @param string $out Output path (.css)
	 *
	 * @return string
	 */
	protected function compile($in, $out) {
		$start = microtime(true);
		$css = $this->scss->compile(file_get_contents($in), $in);
		$elapsed = round((microtime(true) - $start), 4);

		$v = scssc::$VERSION;
		$t = date('r');
		$css = "/* compiled by scssphp $v on $t (${elapsed}s) */\n\n" . $css;

		file_put_contents($out, $css);
		file_put_contents($this->importsCacheName($out),
			serialize($this->scss->getParsedFiles()));
		return $css;
	}

	/**
	 * Compile requested scss and serve css.  Outputs HTTP response.
	 *
	 * @param string $salt Prefix a string to the filename for creating the cache name hash
	 */
	public function serve($salt = '') {
		if ($input = $this->findInput()) {
			$output = $this->cacheName($salt . $input);
			header('Content-type: text/css');

			if ($this->needsCompile($input, $output)) {
				try {
					echo $this->compile($input, $output);
				} catch (Exception $e) {
					header('HTTP/1.1 500 Internal Server Error');
					echo 'Parse error: ' . $e->getMessage() . "\n";
				}
			} else {
				header('X-SCSS-Cache: true');
				echo file_get_contents($output);
			}

			return;
		}

		header('HTTP/1.0 404 Not Found');
		header('Content-type: text');
		$v = scssc::$VERSION;
		echo "/* INPUT NOT FOUND scss $v */\n";
	}

	/**
	 * Constructor
	 *
	 * @param string      $dir      Root directory to .scss files
	 * @param string      $cacheDir Cache directory
	 * @param \scssc|null $scss     SCSS compiler instance
	 */
	public function __construct($dir, $cacheDir=null, $scss=null) {
		$this->dir = $dir;

		if (is_null($cacheDir)) {
			$cacheDir = $this->join($dir, 'scss_cache');
		}

		$this->cacheDir = $cacheDir;
		if (!is_dir($this->cacheDir)) mkdir($this->cacheDir, 0755, true);

		if (is_null($scss)) {
			$scss = new scssc();
			$scss->setImportPaths($this->dir);
		}
		$this->scss = $scss;
	}

	/**
	 * Helper method to serve compiled scss
	 *
	 * @param string $path Root path
	 */
	static public function serveFrom($path) {
		$server = new self($path);
		$server->serve();
	}
}
