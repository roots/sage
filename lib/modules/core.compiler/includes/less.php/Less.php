<?php


require 'LessCache.php';

class Less_Parser extends Less_Cache{


    private $input;		// LeSS input string
    private $input_len;	// input string length
    private $pos;		// current index in `input`
    private $memo;		// temporarily holds `i`, when backtracking


    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $filename;


    /**
     *
     */
    const version = '1.4.2.1';
    const less_version = '1.4.2';

    /**
     * @var Less_Environment
     */
    private $env;
    private $rules = array();

	private static $imports = array();



    /**
     * @param Environment|null $env
     */
    public function __construct( $env = null ){

		// Top parser on an import tree must be sure there is one "env"
		// which will then be passed around by reference.
		if( $env instanceof Less_Environment ){
			$this->env = $env;
		}else{
			$this->env = new Less_Environment( $env );
			self::$imports = array();
			self::$import_dirs = array();
		}

		$this->pos = 0;
    }




    /**
     * Get the current css buffer
     *
     * @return string
     */
    public function getCss(){

		$precision = ini_get('precision');
		@ini_set('precision',16);

 		$root = new Less_Tree_Ruleset(null, $this->rules );
		$root->root = true;

		//$importVisitor = new Less_importVisitor();
		//$importVisitor->run($root);

		$evaldRoot = $root->compile($this->env);

		$joinSelector = new Less_joinSelectorVisitor();
		$joinSelector->run($evaldRoot);

		$extendsVisitor = new Less_processExtendsVisitor();
		$extendsVisitor->run($evaldRoot);

		$css = $evaldRoot->toCSS($this->env);

		if( $this->env->compress ){
			$css = preg_replace('/(\s)+/',"$1", $css);
		}

		@ini_set('precision',$precision);


        return $css;
    }


    /**
     * Parse a Less string into css
     *
     * @param string $str The string to convert
     * @param bool $returnRoot Indicates whether the return value should be a css string a root node
     * @return Less_Tree_Ruleset|Less_Parser
     */
    public function parse($str){
		$this->input = $str;
		$this->_parse();
    }


    /**
     * Parse a Less string from a given file
     *
     * @throws Less_ParserException
     * @param $filename The file to parse
     * @param $uri_root The url of the file
     * @param bool $returnRoot Indicates whether the return value should be a css string a root node
     * @return Less_Tree_Ruleset|Less_Parser
     */
	public function parseFile( $filename, $uri_root = '', $returnRoot = false){

		if( !file_exists($filename) ){
			throw new Less_ParserException(sprintf('File `%s` not found.', $filename));
		}

		$previousFileInfo = $this->env->currentFileInfo;
		$this->SetFileInfo($filename, $uri_root);

		$previousImportDirs = self::$import_dirs;
		self::AddParsedFile($filename);

		$return = null;
		if( $returnRoot ){
			$rules = $this->GetRules( $filename );
			$return = new Less_Tree_Ruleset(null, $rules );
		}else{
			$this->_parse( $filename );
		}

		if( $previousFileInfo ){
			$this->env->currentFileInfo = $previousFileInfo;
		}
		self::$import_dirs = $previousImportDirs;

		return $return;
	}


	public function SetFileInfo( $filename, $uri_root = ''){

		$this->path = pathinfo($filename, PATHINFO_DIRNAME);
		$this->filename = Less_Environment::NormPath($filename);

		$dirname = preg_replace('/[^\/\\\\]*$/','',$this->filename);

		$currentFileInfo = array();
		$currentFileInfo['currentDirectory'] = $dirname;
		$currentFileInfo['filename'] = $filename;
		$currentFileInfo['rootpath'] = $dirname;
		$currentFileInfo['entryPath'] = $dirname;

		if( empty($uri_root) ){
			$currentFileInfo['uri_root'] = $uri_root;
		}else{
			$currentFileInfo['uri_root'] = rtrim($uri_root,'/').'/';
		}

		$this->env->currentFileInfo = $currentFileInfo;

		self::$import_dirs = array_merge( array( $dirname => $currentFileInfo['uri_root'] ), self::$import_dirs );
	}

	public function SetCacheDir( $dir ){

		if( is_dir($dir) && is_writable($dir) ){
			$dir = str_replace('\\','/',$dir);
			self::$cache_dir = rtrim($dir,'/').'/';
			return true;
		}

	}

	public function SetImportDirs( $dirs ){
		foreach($dirs as $path => $uri_root){

			$path = str_replace('\\','/',$path);
			$uri_root = str_replace('\\','/',$uri_root);

			if( !empty($path) ){
				$path = rtrim($path,'/').'/';
			}
			if( !empty($uri_root) ){
				$uri_root = rtrim($uri_root,'/').'/';
			}
			self::$import_dirs[$path] = $uri_root;
		}
	}

	private function _parse( $file_path = false ){
		$this->rules = array_merge($this->rules, $this->GetRules( $file_path ));
	}


	/**
	 * Return the results of parsePrimary for $file_path
	 * Use cache and save cached results if possible
	 *
	 */
	private function GetRules( $file_path ){

		$cache_file = false;
		if( $file_path ){

			$cache_file = substr($file_path,0,-5).'.lesscache';
			if( file_exists($cache_file) && ($cache = file_get_contents( $cache_file )) && ($cache = unserialize($cache)) ){
				return $cache;
			}

			$cache_file = $this->CacheFile( $file_path );
			if( $cache_file && file_exists($cache_file) && ($cache = file_get_contents( $cache_file )) && ($cache = unserialize($cache)) ){
				touch($cache_file);
				return $cache;
			}

			$this->input = file_get_contents( $file_path );
		}

		$this->pos = 0;
		$this->input = preg_replace('/\r\n/', "\n", $this->input);

		//$this->Chunkify();

		// Remove potential UTF Byte Order Mark
		$this->input = preg_replace('/\\G\xEF\xBB\xBF/', '', $this->input);
		$this->input_len = strlen($this->input);

		$rules = $this->parsePrimary();


		// free up a little memory
		unset($this->input, $this->pos);


		//save the cache
		if( $cache_file ){
			file_put_contents( $cache_file, serialize($rules) );

			if( self::$clean_cache ){
				self::CleanCache();
			}

		}

		return $rules;
	}


	private function Chunkify(){

		$j = 0;
		$skip = '/(?:@\{[\w-]+\}|[^"\'`\{\}\/\(\)\\\\])+/';
		$comment = '/\/\*(?:[^*]|\*+[^\/*])*\*+\/|\/\/.*/';
		$string = '/"((?:[^"\\\r\n]|\\.)*)"|\'((?:[^\'\\\r\n]|\\.)*)\'|`((?:[^`]|\\.)*)`/';
		$level = 0;
		//$chunk = $chunks[0];
		$inParam = false;

		$chunks = array('');





		for( $i = 0; $i < $this->input_len; ){

			$lastIndex = $i;
			if( preg_match($skip, $this->input, $match, PREG_OFFSET_CAPTURE, $i ) ){

				if( $match[0][1] === $i ){
					$i += strlen($match[0][0]);
					$chunks[$j] .= $match[0][0];
				}
			}

			$c = @$this->input[$i];

			//comment.lastIndex = string.lastIndex = i;

			if( preg_match($string, $this->input, $match, PREG_OFFSET_CAPTURE, $i ) ){
				if( $match[0][1] === $i ){
					$i += strlen($match[0][0]);
					$chunks[$j] .= $match[0][0];
					continue;
				}
			}



			if( !$inParam && $c === '/' ){
				$cc = $this->input[$i + 1];
				if( $cc === '/' || $cc === '*' ){

					if( preg_match($comment, $this->input, $match, PREG_OFFSET_CAPTURE, $i ) ){
						if( $match[0][1] === $i ){
							$i += strlen($match[0][0]);
							$chunks[$j] .= $match[0][0];
							continue;
						}
					}
				}
			}


			switch( $c ){
				case '{':
					if (! $inParam) {
						$level ++;
						$chunks[$j] .= $c;
						break;
					}

				case '}':
					if(! $inParam ){
						$level --;
						$chunks[$j] .= $c;
						$chunks[++$j] = '';
						break;
					}

				case '(':
					if(! $inParam ){
						$inParam = true;
						$chunks[$j] .= $c;
						break;
					}

				case ')':
					if(  $inParam ){
						$inParam = false;
						$chunks[$j] .= $c;
						break;
					}

				default:
					$chunks[$j] .= $c;
			}

			$i++;

		}


		if( $level != 0 ){

			throw new Less_ParserException(
				($level > 0) ? 'missing closing `}`' : 'missing opening `{` at index '.($i-1)
			);
		}

	}


	public function CacheFile( $file_path ){

		if( $file_path && self::$cache_dir ){
			$file_size = filesize( $file_path );
			$file_mtime = filemtime( $file_path );
			return self::$cache_dir.'lessphp_'.base_convert( md5($file_path), 16, 36).'.'.base_convert($file_size,10,36).'.'.base_convert($file_mtime,10,36).'.'.self::cache_version.'.lesscache';
		}
	}


	static function AddParsedFile($file){
		self::$imports[] = $file;
	}

	static function AllParsedFiles(){
		return self::$imports;
	}

	static function FileParsed($file){
		return in_array($file,self::$imports);
	}


    function save() {
        $this->memo = $this->pos;
	}

    private function restore() {
        $this->pos = $this->memo;
	}


    private function isWhitespace($offset = 0) {
		return ctype_space($this->input[ $this->pos + $offset]);
    }

    /**
     * Parse from a token, regexp or string, and move forward if match
     *
     * @param string $tok
     * @return null|bool|object
     */
    private function match(){

        // The match is confirmed, add the match length to `this::pos`,
        // and consume any extra white-space characters (' ' || '\n')
        // which come after that. The reason for this is that LeSS's
        // grammar is mostly white-space insensitive.
        //

		for($i = 0, $len = func_num_args(); $i< $len; $i++){
			$tok = func_get_arg($i);

			if( strlen($tok) == 1 ){
				$match = $this->MatchChar($tok);

			}elseif( $tok[0] != '/' ){
				// Non-terminal, match using a function call
				$match = $this->$tok();

			}else{
				$match = $this->MatchReg($tok);
			}

			if( $match ){
				return $match;
			}
		}
	}

	private function MatchFuncs(){

		for($i = 0, $len = func_num_args(); $i< $len; $i++){
			$tok = func_get_arg($i);
			$match = $this->$tok();

			if( $match ){
				return $match;
			}
		}
	}

	// Match a single character in the input,
	private function MatchChar($tok){
		if( ($this->pos < $this->input_len) && ($this->input[$this->pos] === $tok) ){
			$this->skipWhitespace(1);
			return $tok;
		}
	}

	// Match a regexp from the current start point
	private function MatchReg($tok){

		if( preg_match($tok, $this->input, $match, 0, $this->pos) ){
			$this->skipWhitespace(strlen($match[0]));
			return count($match) === 1 ? $match[0] : $match;
		}
	}

	//match a string
	private function MatchString($string){
		$len = strlen($string);

		if( ($this->input_len >= ($this->pos+$len)) && substr_compare( $this->input, $string, $this->pos, $len, true ) === 0 ){
			$this->skipWhitespace( $len );
			return $string;
		}

	}


	/**
	 * Same as match(), but don't change the state of the parser,
	 * just return the match.
	 *
	 * @param $tok
	 * @param int $offset
	 * @return bool
	 */
	public function PeekReg($tok){
		return preg_match($tok, $this->input, $match, 0, $this->pos);
	}

	public function PeekChar($tok, $offset = 0){
		$offset += $this->pos;
		return ($offset < $this->input_len) && ($this->input[$offset] === $tok );
	}


    public function skipWhitespace($length) {
		$this->pos += $length;
		$this->pos += strspn($this->input, "\n\r\t ", $this->pos);
    }


	public function expect($tok, $msg = NULL) {
		$result = $this->match($tok);
		if (!$result) {
			throw new Less_ParserException(
				$msg === NULL
					? "Expected '" . $tok . "' got '" . $this->input[$this->pos] . "'"
					: $msg
			);
		} else {
			return $result;
		}
	}

    //
    // Here in, the parsing rules/functions
    //
    // The basic structure of the syntax tree generated is as follows:
    //
    //   Ruleset ->  Rule -> Value -> Expression -> Entity
    //
    // Here's some LESS code:
    //
    //    .class {
    //      color: #fff;
    //      border: 1px solid #000;
    //      width: @w + 4px;
    //      > .child {...}
    //    }
    //
    // And here's what the parse tree might look like:
    //
    //     Ruleset (Selector '.class', [
    //         Rule ("color",  Value ([Expression [Color #fff]]))
    //         Rule ("border", Value ([Expression [Dimension 1px][Keyword "solid"][Color #000]]))
    //         Rule ("width",  Value ([Expression [Operation "+" [Variable "@w"][Dimension 4px]]]))
    //         Ruleset (Selector [Element '>', '.child'], [...])
    //     ])
    //
    //  In general, most rules will try to parse a token with the `$()` function, and if the return
    //  value is truly, will return a new node, of the relevant type. Sometimes, we need to check
    //  first, before parsing, that's when we use `peek()`.
    //

    //
    // The `primary` rule is the *entry* and *exit* point of the parser.
    // The rules here can appear at any level of the parse tree.
    //
    // The recursive nature of the grammar is an interplay between the `block`
    // rule, which represents `{ ... }`, the `ruleset` rule, and this `primary` rule,
    // as represented by this simplified grammar:
    //
    //     primary  →  (ruleset | rule)+
    //     ruleset  →  selector+ block
    //     block    →  '{' primary '}'
    //
    // Only at one point is the primary rule not called from the
    // block rule: at the root level.
    //
    private function parsePrimary(){
        $root = array();

		$this->skipWhitespace(0);

        while( ($node = $this->MatchFuncs('parseExtendRule', 'parseMixinDefinition', 'parseRule', 'parseRuleset', 'parseMixinCall', 'parseComment', 'parseDirective' ))
							|| $this->skipSemicolons()
        ){
			//not the same as less.js
			if( is_array($node) ){
				$root[] = $node[0];
			}elseif( $node ){
				$root[] = $node;
			}
        }

        return $root;
    }

	public function skipSemicolons(){
		$len = strspn($this->input, ";", $this->pos);
		if( $len ){
			$this->skipWhitespace($len);
			return true;
		}
	}


    // We create a Comment node for CSS comments `/* */`,
    // but keep the LeSS comments `//` silent, by just skipping
    // over them.
    private function parseComment(){

        if ( ! $this->PeekChar('/')) {
            return;
        }


		if ($this->PeekChar('/', 1)) {

			return new Less_Tree_Comment($this->MatchReg('/\\G\/\/.*/'), true);
		//}elseif( $comment = $this->MatchReg('/\\G\/\*(?:[^*]|\*+[^\/*])*\*+\/\n?/')) {
		}elseif( $comment = $this->MatchReg('/\\G\/\*(?s).*?\*+\/\n?/') ) { //not the same as less.js to prevent fatal errors
			return new Less_Tree_Comment($comment, false);
		}
    }

    //
    // A string, which supports escaping " and '
    //
    //     "milky way" 'he\'s the one!'
    //
    private function parseEntitiesQuoted() {
		$j = 0;
		$e = false;
		$index = $this->pos;

        if ($this->PeekChar('~')) {
			$j++;
            $e = true; // Escaped strings
        }

        if ( ! $this->PeekChar('"', $j) && ! $this->PeekChar("'", $j)) {
            return;
        }

        if ($e) {
            $this->MatchChar('~');
        }

        if ($str = $this->MatchReg('/\\G"((?:[^"\\\\\r\n]|\\\\.)*)"|\'((?:[^\'\\\\\r\n]|\\\\.)*)\'/')) {
			$result = $str[0][0] == '"' ? $str[1] : $str[2];
			return new Less_Tree_Quoted($str[0], $result, $e, $index, $this->env->currentFileInfo );
        }
        return;
    }

    //
    // A catch-all word, such as:
    //
    //     black border-collapse
    //
    private function parseEntitiesKeyword()
    {
        if ($k = $this->MatchReg('/\\G[_A-Za-z-][_A-Za-z0-9-]*/')) {
			if (Less_Colors::hasOwnProperty($k))
				// detected named color
				return new Less_Tree_Color(substr(Less_Colors::color($k), 1));
			else
				return new Less_Tree_Keyword($k);
        }
        return;
    }

    //
    // A function call
    //
    //     rgb(255, 0, 255)
    //
    // We also try to catch IE's `alpha()`, but let the `alpha` parser
    // deal with the details.
    //
    // The arguments are parsed with the `entities.arguments` parser.
    //
    private function parseEntitiesCall(){
        $index = $this->pos;

		if( !preg_match('/\\G([\w-]+|%|progid:[\w\.]+)\(/', $this->input, $name,0,$this->pos) ){
            return;
        }
        $name = $name[1];
        $nameLC = strtolower($name);

        if ($nameLC === 'url') {
            return null;
        } else {
            $this->pos += strlen($name);
        }

        if( $nameLC === 'alpha' ){
			$alpha_ret = $this->parseAlpha();
			if( $alpha_ret ){
				return $alpha_ret;
			}
		}

        $this->MatchChar('('); // Parse the '(' and consume whitespace.

        $args = $this->parseEntitiesArguments();

        if( !$this->MatchChar(')') ){
            return;
        }

        if ($name) {
            return new Less_Tree_Call($name, $args, $index, $this->env->currentFileInfo );
        }
    }

    /**
     * Parse a list of arguments
     *
     * @return array
     */
	private function parseEntitiesArguments(){
		$args = array();
		while( $arg = $this->MatchFuncs('parseEntitiesAssignment','parseExpression') ){
			$args[] = $arg;
			if (! $this->MatchChar(',')) {
				break;
			}
		}
		return $args;
	}

    private function parseEntitiesLiteral(){
		return $this->MatchFuncs('parseEntitiesDimension','parseEntitiesColor','parseEntitiesQuoted','parseUnicodeDescriptor');
    }

	// Assignments are argument entities for calls.
	// They are present in ie filter properties as shown below.
	//
	//     filter: progid:DXImageTransform.Microsoft.Alpha( *opacity=50* )
	//
	private function parseEntitiesAssignment() {
		if (($key = $this->MatchReg('/\\G\w+(?=\s?=)/')) && $this->MatchChar('=') && ($value = $this->parseEntity())) {
			return new Less_Tree_Assignment($key, $value);
		}
	}

	//
	// Parse url() tokens
	//
	// We use a specific rule for urls, because they don't really behave like
	// standard function calls. The difference is that the argument doesn't have
	// to be enclosed within a string, so it can't be parsed as an Expression.
	//
	private function parseEntitiesUrl(){


		if( !$this->MatchString('url(') ){
			return;
		}

		$value = $this->match('parseEntitiesQuoted','parseEntitiesVariable','/\\G(?:(?:\\\\[\(\)\'"])|[^\(\)\'"])+/');
		if( !$value ){
			$value = '';
		}


		$this->expect(')');


		return new Less_Tree_Url((isset($value->value) || $value instanceof Less_Tree_Variable)
							? $value : new Less_Tree_Anonymous($value), $this->env->currentFileInfo );
	}


	//
	// A Variable entity, such as `@fink`, in
	//
	//     width: @fink + 2px
	//
	// We use a different parser for variable definitions,
	// see `parsers.variable`.
	//
	private function parseEntitiesVariable(){
		$index = $this->pos;
		if ($this->PeekChar('@') && ($name = $this->MatchReg('/\\G@@?[\w-]+/'))) {
			return new Less_Tree_Variable($name, $index, $this->env->currentFileInfo);
		}
	}


	// A variable entity useing the protective {} e.g. @{var}
	private function parseEntitiesVariableCurly() {
		$index = $this->pos;

		if( $this->input_len > ($this->pos+1) && $this->input[$this->pos] === '@' && ($curly = $this->MatchReg('/\\G@\{([\w-]+)\}/')) ){
			return new Less_Tree_Variable('@'.$curly[1], $index, $this->env->currentFileInfo);
		}
	}

    //
    // A Hexadecimal color
    //
    //     #4F3C2F
    //
    // `rgb` and `hsl` colors are parsed through the `entities.call` parser.
    //
    private function parseEntitiesColor()
    {
        if ($this->PeekChar('#') && ($rgb = $this->MatchReg('/\\G#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/'))) {
            return new Less_Tree_Color($rgb[1]);
        }
    }

    //
    // A Dimension, that is, a number and a unit
    //
    //     0.5em 95%
    //
    private function parseEntitiesDimension(){

        $c = @ord($this->input[$this->pos]);

		//Is the first char of the dimension 0-9, '.', '+' or '-'
		if (($c > 57 || $c < 43) || $c === 47 || $c == 44){
			return;
        }

        if ($value = $this->MatchReg('/\\G([+-]?\d*\.?\d+)(%|[a-z]+)?/')) {
            return new Less_Tree_Dimension($value[1], isset($value[2]) ? $value[2] : null);
        }
    }


	//
	// A unicode descriptor, as is used in unicode-range
	//
	// U+0?? or U+00A1-00A9
	//
	function parseUnicodeDescriptor() {

		if ($ud = $this->MatchReg('/\\G(U\+[0-9a-fA-F?]+)(\-[0-9a-fA-F?]+)?/')) {
			return new Less_Tree_UnicodeDescriptor($ud[0]);
		}
	}


    //
    // JavaScript code to be evaluated
    //
    //     `window.location.href`
    //
    private function parseEntitiesJavascript()
    {
        $e = false;
        if ($this->PeekChar('~')) {
            $e = true;
        }
        if (! $this->PeekChar('`', $e)) {
            return;
        }
        if ($e) {
            $this->MatchChar('~');
        }
        if ($str = $this->MatchReg('/\\G`([^`]*)`/')) {
            return new Less_Tree_Javascript($str[1], $this->pos, $e);
        }
    }


	//
	// The variable part of a variable definition. Used in the `rule` parser
	//
	//     @fink:
	//
	private function parseVariable(){
		if ($this->PeekChar('@') && ($name = $this->MatchReg('/\\G(@[\w-]+)\s*:/'))) {
			return $name[1];
		}
	}

    //
	// extend syntax - used to extend selectors
	//
	function parseExtend($isRule = false){

		$index = $this->pos;
		$extendList = array();


		//if( !$this->MatchReg( $isRule ? '/\\G&:extend\(/' : '/\\G:extend\(/' ) ){ return; }
		if( !$this->MatchString( $isRule ? '&:extend(' : ':extend(' ) ){ return; }

		do{
			$option = null;
			$elements = array();
			while( true ){
				$option = $this->MatchReg('/\\G(all)(?=\s*(\)|,))/');
				if( $option ){ break; }
				$e = $this->parseElement();
				if( !$e ){ break; }
				$elements[] = $e;
			}

			if( $option ){
				$option = $option[1];
			}

			$extendList[] = new Less_Tree_Extend( new Less_Tree_Selector($elements), $option, $index );

		}while( $this->MatchChar(",") );

		$this->expect('/\\G\)/');

		if( $isRule ){
			$this->expect('/\\G;/');
		}

		return $extendList;
	}

	function parseExtendRule(){
		return $this->parseExtend(true);
	}


	//
	// A Mixin call, with an optional argument list
	//
	//     #mixins > .square(#fff);
	//     .rounded(4px, black);
	//     .button;
	//
	// The `while` loop is there because mixins can be
	// namespaced, but we only support the child and descendant
	// selector for now.
	//
    private function parseMixinCall(){
		$elements = array();
		$index = $this->pos;
		$important = false;
		$args = null;
		$c = null;

        if( !$this->PeekChar('.') && !$this->PeekChar('#') ){
            return;
        }

		$this->save(); // stop us absorbing part of an invalid selector

		while( $e = $this->MatchReg('/\\G[#.](?:[\w-]|\\\\(?:[A-Fa-f0-9]{1,6} ?|[^A-Fa-f0-9]))+/') ){
			$elements[] = new Less_Tree_Element($c, $e, $this->pos);
			$c = $this->MatchChar('>');
		}

		if( $this->MatchChar('(') ){
			$returned = $this->parseMixinArgs(true);
			$args = $returned['args'];
			$this->expect(')');
		}

		if( !$args ){
			$args = array();
		}

		if( $this->parseImportant() ){
			$important = true;
		}

		if( count($elements) > 0 && ($this->MatchChar(';') || $this->PeekChar('}')) ){
			return new Less_Tree_MixinCall($elements, $args, $index, $this->env->currentFileInfo, $important);
		}

		$this->restore();
	}


	private function parseMixinArgs( $isCall ){
		$expressions = array();
		$argsSemiColon = array();
		$isSemiColonSeperated = null;
		$argsComma = array();
		$expressionContainsNamed = null;
		$name = null;
		$nameLoop = null;
		$returner = array('args'=>null, 'variadic'=> false);

		while( true ){
			if( $isCall ){
				$arg = $this->parseExpression();
			} else {
				$this->parseComment();
				if( $this->input[ $this->pos ] === '.' && $this->MatchReg('/\\G\.{3}/') ){
					$returner['variadic'] = true;
					if( $this->MatchChar(";") && !$isSemiColonSeperated ){
						$isSemiColonSeperated = true;
					}

					if( $isSemiColonSeperated ){
						$argsSemiColon[] = array('variadic'=>true);
					}else{
						$argsComma[] = array('variadic'=>true);
					}
					break;
				}
				$arg = $this->MatchFuncs('parseEntitiesVariable','parseEntitiesLiteral','parseEntitiesKeyword');
			}


			if( !$arg ){
				break;
			}


			$nameLoop = null;
			if( $arg instanceof Less_Tree_Expression ){
				$arg->throwAwayComments();
			}
			$value = $arg;
			$val = null;

			if( $isCall ){
				// Variable
				if( count($arg->value) == 1) {
					$val = $arg->value[0];
				}
			} else {
				$val = $arg;
			}


			if( $val && $val instanceof Less_Tree_Variable ){

				if( $this->MatchChar(':') ){
					if( count($expressions) > 0 ){
						if( $isSemiColonSeperated ){
							throw new Less_ParserException('Cannot mix ; and , as delimiter types');
						}
						$expressionContainsNamed = true;
					}
					$value = $this->expect('parseExpression');
					$nameLoop = ($name = $val->name);
				}elseif( !$isCall && $this->MatchReg('/\\G\.{3}/') ){
					$returner['variadic'] = true;
					if( $this->MatchChar(";") && !$isSemiColonSeperated ){
						$isSemiColonSeperated = true;
					}
					if( $isSemiColonSeperated ){
						$argsSemiColon[] = array('name'=> $arg->name, 'variadic' => true);
					}else{
						$argsComma[] = array('name'=> $arg->name, 'variadic' => true);
					}
					break;
				}elseif( !$isCall ){
					$name = $nameLoop = $val->name;
					$value = null;
				}
			}

			if( $value ){
				$expressions[] = $value;
			}

			$argsComma[] = array('name'=>$nameLoop, 'value'=>$value );

			if( $this->MatchChar(',') ){
				continue;
			}

			if( $this->MatchChar(';') || $isSemiColonSeperated ){

				if( $expressionContainsNamed ){
					throw new Less_ParserException('Cannot mix ; and , as delimiter types');
				}

				$isSemiColonSeperated = true;

				if( count($expressions) > 1 ){
					$value = new Less_Tree_Value($expressions);
				}
				$argsSemiColon[] = array('name'=>$name, 'value'=>$value );

				$name = null;
				$expressions = array();
				$expressionContainsNamed = false;
			}
		}

		$returner['args'] = ($isSemiColonSeperated ? $argsSemiColon : $argsComma);
		return $returner;
	}


    //
    // A Mixin definition, with a list of parameters
    //
    //     .rounded (@radius: 2px, @color) {
    //        ...
    //     }
    //
    // Until we have a finer grained state-machine, we have to
    // do a look-ahead, to make sure we don't have a mixin call.
    // See the `rule` function for more information.
    //
    // We start by matching `.rounded (`, and then proceed on to
    // the argument list, which has optional default values.
    // We store the parameters in `params`, with a `value` key,
    // if there is a value, such as in the case of `@radius`.
    //
    // Once we've got our params list, and a closing `)`, we parse
    // the `{...}` block.
    //
    private function parseMixinDefinition(){
        $params = array();
		$variadic = false;
		$cond = null;

        if ((! $this->PeekChar('.') && ! $this->PeekChar('#')) || $this->PeekChar('/\\G[^{]*\}/')) {
            return;
        }

		$this->save();

        if ($match = $this->MatchReg('/\\G([#.](?:[\w-]|\\\(?:[A-Fa-f0-9]{1,6} ?|[^A-Fa-f0-9]))+)\s*\(/')) {
            $name = $match[1];

			$argInfo = $this->parseMixinArgs( false );
			$params = $argInfo['args'];
			$variadic = $argInfo['variadic'];


			// .mixincall("@{a}");
			// looks a bit like a mixin definition.. so we have to be nice and restore
			if( !$this->MatchChar(')') ){
				//furthest = i;
				$this->restore();
			}

			$this->parseComment();

			if( $this->MatchString('when') ){ // Guard
			//if ($this->MatchReg('/\\Gwhen/')) { // Guard
				$cond = $this->expect('parseConditions', 'Expected conditions');
			}

            $ruleset = $this->parseBlock();

            if( is_array($ruleset) ){
                return new Less_Tree_MixinDefinition($name, $params, $ruleset, $cond, $variadic);
            } else {
				$this->restore();
			}
        }
    }

	//
	// Entities are the smallest recognized token,
	// and can be found inside a rule's value.
	//
	private function parseEntity(){

		return $this->MatchFuncs('parseEntitiesLiteral','parseEntitiesVariable','parseEntitiesUrl','parseEntitiesCall','parseEntitiesKeyword','parseEntitiesJavascript','parseComment');
	}

    //
    // A Rule terminator. Note that we use `peek()` to check for '}',
    // because the `block` rule will be expecting it, but we still need to make sure
    // it's there, if ';' was ommitted.
    //
    private function parseEnd()
    {
        return ($end = $this->MatchChar(';') ) ? $end : $this->PeekChar('}');
    }

    //
    // IE's alpha function
    //
    //     alpha(opacity=88)
    //
    private function parseAlpha(){

		if( !$this->MatchString('(opacity=') ){
        //if ( ! $this->MatchReg('/\\G\(opacity=/i')) {
            return;
        }

        $value = $this->MatchReg('/\\G[0-9]+/');
        if ($value === null) {
            $value = $this->parseEntitiesVariable();
        }

        if ($value !== null) {
			$this->expect(')');
            return new Less_Tree_Alpha($value);
        }
    }


    //
    // A Selector Element
    //
    //     div
    //     + h1
    //     #socks
    //     input[type="text"]
    //
    // Elements are the building blocks for Selectors,
    // they are made out of a `Combinator` (see combinator rule),
    // and an element name, such as a tag a class, or `*`.
    //
    private function parseElement(){
        $c = $this->parseCombinator();

        $e = $this->match( '/\\G(?:\d+\.\d+|\d+)%/', '/\\G(?:[.#]?|:*)(?:[\w-]|[^\x00-\x9f]|\\\\(?:[A-Fa-f0-9]{1,6} ?|[^A-Fa-f0-9]))+/',
			'*', '&', 'parseAttribute', '/\\G\([^()@]+\)/', '/\\G[\.#](?=@)/', 'parseEntitiesVariableCurly');

		if( !$e ){
			if( $this->MatchChar('(') ){
				if( ($v = $this->parseSelector()) && $this->MatchChar(')') ){
					$e = new Less_Tree_Paren($v);
				}
			}
		}

        if ($e) {
            return new Less_Tree_Element($c, $e, $this->pos);
        }
    }

    //
    // Combinators combine elements together, in a Selector.
    //
    // Because our parser isn't white-space sensitive, special care
    // has to be taken, when parsing the descendant combinator, ` `,
    // as it's an empty space. We have to check the previous character
    // in the input, to see if it's a ` ` character.
    //
    private function parseCombinator()
    {
        $c = isset($this->input[$this->pos]) ? $this->input[$this->pos] : '';
        if ($c === '>' || $c === '+' || $c === '~' || $c === '|') {

            $this->pos++;
            while( $this->isWhitespace() ){
                $this->pos++;
            }
            return new Less_Tree_Combinator($c);
        } elseif ($this->pos > 0 && (preg_match('/\s/', $this->input[$this->pos - 1]))) {
            return new Less_Tree_Combinator(' ');
        } else {
            return new Less_Tree_Combinator();
        }
    }

    //
    // A CSS Selector
    //
    //     .class > div + h1
    //     li a:hover
    //
    // Selectors are made out of one or more Elements, see above.
    //
    private function parseSelector(){
		$elements = array();
		$extendList = array();

		while( ($extend = $this->parseExtend()) ? $extend : ($e = $this->parseElement()) ){
			if( $extend ){
				$extendList = array_merge($extendList,$extend);
			}else{
				if( count($extendList) ){
					//error("Extend can only be used at the end of selector");
				}
				$c = $this->input[ $this->pos ];
				$elements[] = $e;
				$e = null;
			}

			if( $c === '{' || $c === '}' || $c === ';' || $c === ',' || $c === ')') { break; }
		}

		if( count($elements) ) { return new Less_Tree_Selector( $elements, $extendList); }
		if( count($extendList) ) { throw new Less_ParserException('Extend must be used to extend a selector, it cannot be used on its own'); }
    }

	private function parseTag(){
		return ( $tag = $this->MatchReg('/\\G[A-Za-z][A-Za-z-]*[0-9]?/') ) ? $tag : $this->MatchChar('*');
	}

	private function parseAttribute(){

		$val = null;
		$op = null;

		if( !$this->MatchChar('[') ){
			return;
		}

		if( !($key = $this->parseEntitiesVariableCurly()) ){
			$key = $this->expect('/\\G(?:[_A-Za-z0-9-\*]*\|)?(?:[_A-Za-z0-9-]|\\\\.)+/');
		}

		if( ($op = $this->MatchReg('/\\G[|~*$^]?=/')) ){
			$val = $this->match('parseEntitiesQuoted','/\\G[\w-]+/','parseEntitiesVariableCurly');
		}

		$this->expect(']');

		return new Less_Tree_Attribute($key, $op, $val);


        if (! $this->MatchChar('[')) {
            return;
        }

        $attr = '';

		if( $key = $this->match('/\\G(?:[_A-Za-z0-9-]|\\\\.)+/','parseEntitiesQuoted') ){
			if( ($op = $this->MatchReg('/\\G[|~*$^]?=/')) &&
				($val = $this->match('parseEntitiesQuoted','/\\G[\w-]+/')) ){
				if( !is_string($val) ){
					$val = $val->toCss();
				}
				$attr = $key.$op.$val;
			}else{
				$attr = $key;
			}
		}

        if (! $this->MatchChar(']')) {
            return;
        }

        if ($attr) {
            return "[" . $attr . "]";
        }
    }

    //
    // The `block` rule is used by `ruleset` and `mixin.definition`.
    // It's a wrapper around the `primary` rule, with added `{}`.
    //
    private function parseBlock(){
        if ($this->MatchChar('{') && (is_array($content = $this->parsePrimary())) && $this->MatchChar('}')) {
            return $content;
        }
    }

	//
	// div, .class, body > p {...}
	//
	private function parseRuleset(){
		$selectors = array();
		$start = $this->pos;

		while( $selectors[] = $this->parseSelector() ){
			$this->parseComment();
			if( !$this->MatchChar(',') ){
				break;
			}
			$this->parseComment();
		}

		if( count($selectors) > 0 && (is_array($rules = $this->parseBlock())) ){
			return new Less_Tree_Ruleset($selectors, $rules, $this->env->strictImports);
		} else {
			// Backtrack
			$this->pos = $start;
		}
	}


	private function parseRule( $tryAnonymous = null ){
		$start = $this->pos;
		$this->save();

		if( isset($this->input[$this->pos]) ){
			$c = $this->input[$this->pos];

			if( $c === '.' || $c === '#' || $c === '&' ){
				return;
			}
		}

		if( $name = $this->MatchFuncs('parseVariable','parseProperty') ){


			// prefer to try to parse first if its a variable or we are compressing
			// but always fallback on the other one
			if( !$tryAnonymous && ($this->env->compress || ( $name[0] === '@')) ){
				$value = $this->MatchFuncs('parseValue','parseAnonymousValue');
			}else{
				$value = $this->MatchFuncs('parseAnonymousValue','parseValue');
			}

			$important = $this->parseImportant();

			if( $value && $this->parseEnd() ){
				return new Less_Tree_Rule($name, $value, $important, $start, $this->env->currentFileInfo);
			}else{
				//not the same as less.js
				$this->restore();
				/*
				if( $value && !$tryAnonymous ){
					//return new Less_Tree_Rule(true);
				}else{
					$this->restore();
				}
				*/
			}
		}
	}

	function parseAnonymousValue(){

		if( preg_match('/\\G([^@+\/\'"*`(;{}-]*);/',$this->input, $match, 0, $this->pos) ){
			$this->pos += strlen($match[0]) - 1;
			return new Less_Tree_Anonymous($match[1]);
		}
	}

	//
	// An @import directive
	//
	//     @import "lib";
	//
	// Depending on our environment, importing is done differently:
	// In the browser, it's an XHR request, in Node, it would be a
	// file-system operation. The function used for importing is
	// stored in `import`, which we pass to the Import constructor.
	//
	private function parseImport(){
		$index = $this->pos;

		$this->save();

		$dir = $this->MatchString('@import');
		//$dir = $this->MatchReg('/\\G@import?\s+/');

		$options = array();
		if( $dir ){
			$options = $this->parseImportOptions();
		}

		if( $dir && ($path = $this->MatchFuncs('parseEntitiesQuoted','parseEntitiesUrl')) ){
			$features = $this->parseMediaFeatures();
			if( $this->MatchChar(';') ){
				if( $features ){
					$features = new Less_Tree_Value($features);
				}

				return new Less_Tree_Import($path, $features, $options, $this->pos, $this->env->currentFileInfo );
			}
		}

		$this->restore();
	}

	private function parseImportOptions(){

		$options = array();

		// list of options, surrounded by parens
		if( !$this->MatchChar('(') ){ return null; }
		do{
			if( $o = $this->parseImportOption() ){
				$optionName = $o;
				$value = true;
				switch( $optionName ){
					case "css":
						$optionName = "less";
						$value = false;
					break;
					case "once":
						$optionName = "multiple";
						$value = false;
					break;
				}
				$options[$optionName] = $value;
				if( !$this->MatchChar(',') ){ break; }
			}
		}while($o);
		$this->expect(')');
		return $options;
	}

	private function parseImportOption(){
		$opt = $this->MatchReg('/\\G(less|css|multiple|once)/');
		if( $opt ){
			return $opt[1];
		}
	}

	private function parseMediaFeature() {
		$nodes = array();

		do {
			if ($e = $this->parseEntitiesKeyword()) {
				$nodes[] = $e;
			} elseif ($this->MatchChar('(')) {
				$p = $this->parseProperty();
				$e = $this->parseValue();
				if ($this->MatchChar(')')) {
					if ($p && $e) {
						$nodes[] = new Less_Tree_Paren(new Less_Tree_Rule($p, $e, null, $this->pos, $this->env->currentFileInfo, true));
					} elseif ($e) {
						$nodes[] = new Less_Tree_Paren($e);
					} else {
						return null;
					}
				} else
					return null;
			}
		} while ($e);

		if ($nodes) {
			return new Less_Tree_Expression($nodes);
		}
	}

	private function parseMediaFeatures() {
		$features = array();

		do {
			if ($e = $this->parseMediaFeature()) {
				$features[] = $e;
				if (!$this->MatchChar(',')) break;
			} elseif ($e = $this->parseEntitiesVariable()) {
				$features[] = $e;
				if (!$this->MatchChar(',')) break;
			}
		} while ($e);

		return $features ? $features : null;
	}

	private function parseMedia() {
		if( $this->MatchString('@media') ){
		//if ($this->MatchReg('/\\G@media/')) {
			$features = $this->parseMediaFeatures();

			if ($rules = $this->parseBlock()) {
				return new Less_Tree_Media($rules, $features);
			}
		}
	}

    //
    // A CSS Directive
    //
    //     @charset "utf-8";
    //
    private function parseDirective()
    {
		$hasBlock = false;
		$hasIdentifier = false;
		$hasExpression = false;

        if (! $this->PeekChar('@')) {
            return;
        }

		$value = $this->MatchFuncs('parseImport','parseMedia');
        if( $value ){
            return $value;
		}

		$this->save();

		$name = $this->MatchReg('/\\G@[a-z-]+/');

		if( !$name ) return;

		$nonVendorSpecificName = $name;
		$pos = strpos($name,'-', 2);
		if( $name[1] == '-' && $pos > 0 ){
			$nonVendorSpecificName = "@" . substr($name, $pos + 1);
		}

		switch($nonVendorSpecificName) {
			case "@font-face":
				$hasBlock = true;
				break;
			case "@viewport":
			case "@top-left":
			case "@top-left-corner":
			case "@top-center":
			case "@top-right":
			case "@top-right-corner":
			case "@bottom-left":
			case "@bottom-left-corner":
			case "@bottom-center":
			case "@bottom-right":
			case "@bottom-right-corner":
			case "@left-top":
			case "@left-middle":
			case "@left-bottom":
			case "@right-top":
			case "@right-middle":
			case "@right-bottom":
				$hasBlock = true;
				break;
			case "@page":
			case "@document":
			case "@supports":
			case "@keyframes":
				$hasBlock = true;
				$hasIdentifier = true;
				break;
			case "@namespace":
				$hasExpression = true;
				break;
		}

		if( $hasIdentifier ){
			$temp = $this->MatchReg('/\\G[^{]+/');
			if( !$temp ){
				$temp = '';
			}
			$name .= " " . trim($temp);
		}


		if( $hasBlock ){

			if ($rules = $this->parseBlock()) {
				return new Less_Tree_Directive($name, $rules);
			}
		} else {
			if (($value = $hasExpression ? $this->parseExpression() : $this->parseEntity()) && $this->MatchChar(';')) {
				return new Less_Tree_Directive($name, $value);
			}
		}

		$this->restore();
    }


    //
    // A Value is a comma-delimited list of Expressions
    //
    //     font-family: Baskerville, Georgia, serif;
    //
    // In a Rule, a Value represents everything after the `:`,
    // and before the `;`.
    //
    private function parseValue ()
    {
        $expressions = array();

        while ($e = $this->parseExpression()) {
            $expressions[] = $e;
            if (! $this->MatchChar(',')) {
                break;
            }
        }

        if (count($expressions) > 0) {
            return new Less_Tree_Value($expressions);
        }
    }

    private function parseImportant (){
        if ($this->PeekChar('!')) {
            return $this->MatchReg('/\\G! *important/');
        }
    }

	private function parseSub (){

		if( $this->MatchChar('(') ){
			if( $a = $this->parseAddition() ){
				$e = new Less_Tree_Expression( array($a) );
				$this->expect(')');
				$e->parens = true;
				return $e;
			}
		}
	}

	private function parseMultiplication() {
		$operation = false;

		if ($m = $this->parseOperand()) {
			$isSpaced = $this->isWhitespace( -1 );
			while( !$this->PeekReg('/\\G\/[*\/]/') && ($op = $this->match('/','*')) ){

				if( $a = $this->parseOperand() ){
					$m->parensInOp = true;
					$a->parensInOp = true;
					$operation = new Less_Tree_Operation( $op, array( $operation ? $operation : $m, $a ), $isSpaced );
					$isSpaced = $this->isWhitespace( -1 );
				}else{
					break;
				}
			}
			return ($operation ? $operation : $m);
		}
	}

    private function parseAddition (){
        $operation = false;
        if ($m = $this->parseMultiplication()) {
			$isSpaced = $this->isWhitespace( -1 );

            while( ($op = ($op = $this->MatchReg('/\\G[-+]\s+/')) ? $op : ( !$isSpaced ? ($this->match('+','-')) : false )) && ($a = $this->parseMultiplication()) ){
				$m->parensInOp = true;
				$a->parensInOp = true;
                $operation = new Less_Tree_Operation($op, array($operation ? $operation : $m, $a), $isSpaced);
                $isSpaced = $this->isWhitespace( -1 );
            }
            return $operation ? $operation : $m;
        }
    }

	private function parseConditions() {
		$index = $this->pos;
		$condition = null;
		if ($a = $this->parseCondition()) {
			while ($this->MatchChar(',') && ($b = $this->parseCondition())) {
				$condition = new Less_Tree_Condition('or', $condition ? $condition : $a, $b, $index);
			}
			return $condition ? $condition : $a;
		}
	}

	private function parseCondition() {
		$index = $this->pos;
		$negate = false;


		if ($this->MatchString('not')) $negate = true;
		//if ($this->MatchReg('/\\Gnot/')) $negate = true;
		$this->expect('(');
		if ($a = ($this->MatchFuncs('parseAddition','parseEntitiesKeyword','parseEntitiesQuoted')) ) {
			if ($op = $this->MatchReg('/\\G(?:>=|=<|[<=>])/')) {
				if ($b = ($this->MatchFuncs('parseAddition','parseEntitiesKeyword','parseEntitiesQuoted'))) {
					$c = new Less_Tree_Condition($op, $a, $b, $index, $negate);
				} else {
					throw new Less_ParserException('Unexpected expression');
				}
			} else {
				$c = new Less_Tree_Condition('=', $a, new Less_Tree_Keyword('true'), $index, $negate);
			}
			$this->expect(')');
			return $this->MatchString('and') ? new Less_Tree_Condition('and', $c, $this->parseCondition()) : $c;
			//return $this->MatchReg('/\\Gand/') ? new Less_Tree_Condition('and', $c, $this->parseCondition()) : $c;
		}
	}

	//
	// An operand is anything that can be part of an operation,
	// such as a Color, or a Variable
	//
	private function parseOperand (){

		$negate = false;
		if( $this->PeekChar('@',1) || $this->PeekChar('(',1) ){
			$negate = $this->MatchChar('-');
		}

		$o = $this->MatchFuncs('parseSub','parseEntitiesDimension','parseEntitiesColor','parseEntitiesVariable','parseEntitiesCall');

		if( $negate ){
			$o->parensInOp = true;
			$o = new Less_Tree_Negative($o);
		}

		return $o;
	}

	//
	// Expressions either represent mathematical operations,
	// or white-space delimited Entities.
	//
	//     1px solid black
	//     @var * 2
	//
	private function parseExpression (){
		$entities = array();

		while( $e = $this->MatchFuncs('parseAddition','parseEntity') ){
			$entities[] = $e;
			// operations do not allow keyword "/" dimension (e.g. small/20px) so we support that here
			if( !$this->PeekReg('/\\G\/[\/*]/') && ($delim = $this->MatchChar('/')) ){
				$entities[] = new Less_Tree_Anonymous($delim);
			}

		}
		if (count($entities) > 0) {
			return new Less_Tree_Expression($entities);
		}
	}

    private function parseProperty (){
        if ($name = $this->MatchReg('/\\G(\*?-?[_a-zA-Z0-9-]+)\s*:/')) {
            return $name[1];
        }
    }

	/**
	 * Some versions of php have trouble with method_exists($a,$b) if $a is not an object
	 *
	 */
    public static function is_method($a,$b){
		return is_object($a) && method_exists($a,$b);
	}


}


	if( !function_exists('pre') ){
		function pre($arg){
			global $debug;

			if( !isset($debug) || !$debug ){
				//return;
			}
			ob_start();
			echo "\n\n<pre>";
			if( $arg === 0 ){
				echo '0';
			}elseif( !$arg ){
				var_dump($arg);
			}else{
				print_r($arg);
			}
			echo "</pre>\n";
			return ob_get_clean();
		}
	}

	if( !function_exists('msg') ){
		function msg($arg){
			echo Pre($arg);
		}
	}

 

//less.js : lib/less/colors.js

class Less_Colors {

	public static $colors;

	private static function all() {
		if (self::$colors)
			return self::$colors;

		self::$colors = array(
			'aliceblue'=>'#f0f8ff',
			'antiquewhite'=>'#faebd7',
			'aqua'=>'#00ffff',
			'aquamarine'=>'#7fffd4',
			'azure'=>'#f0ffff',
			'beige'=>'#f5f5dc',
			'bisque'=>'#ffe4c4',
			'black'=>'#000000',
			'blanchedalmond'=>'#ffebcd',
			'blue'=>'#0000ff',
			'blueviolet'=>'#8a2be2',
			'brown'=>'#a52a2a',
			'burlywood'=>'#deb887',
			'cadetblue'=>'#5f9ea0',
			'chartreuse'=>'#7fff00',
			'chocolate'=>'#d2691e',
			'coral'=>'#ff7f50',
			'cornflowerblue'=>'#6495ed',
			'cornsilk'=>'#fff8dc',
			'crimson'=>'#dc143c',
			'cyan'=>'#00ffff',
			'darkblue'=>'#00008b',
			'darkcyan'=>'#008b8b',
			'darkgoldenrod'=>'#b8860b',
			'darkgray'=>'#a9a9a9',
			'darkgrey'=>'#a9a9a9',
			'darkgreen'=>'#006400',
			'darkkhaki'=>'#bdb76b',
			'darkmagenta'=>'#8b008b',
			'darkolivegreen'=>'#556b2f',
			'darkorange'=>'#ff8c00',
			'darkorchid'=>'#9932cc',
			'darkred'=>'#8b0000',
			'darksalmon'=>'#e9967a',
			'darkseagreen'=>'#8fbc8f',
			'darkslateblue'=>'#483d8b',
			'darkslategray'=>'#2f4f4f',
			'darkslategrey'=>'#2f4f4f',
			'darkturquoise'=>'#00ced1',
			'darkviolet'=>'#9400d3',
			'deeppink'=>'#ff1493',
			'deepskyblue'=>'#00bfff',
			'dimgray'=>'#696969',
			'dimgrey'=>'#696969',
			'dodgerblue'=>'#1e90ff',
			'firebrick'=>'#b22222',
			'floralwhite'=>'#fffaf0',
			'forestgreen'=>'#228b22',
			'fuchsia'=>'#ff00ff',
			'gainsboro'=>'#dcdcdc',
			'ghostwhite'=>'#f8f8ff',
			'gold'=>'#ffd700',
			'goldenrod'=>'#daa520',
			'gray'=>'#808080',
			'grey'=>'#808080',
			'green'=>'#008000',
			'greenyellow'=>'#adff2f',
			'honeydew'=>'#f0fff0',
			'hotpink'=>'#ff69b4',
			'indianred'=>'#cd5c5c',
			'indigo'=>'#4b0082',
			'ivory'=>'#fffff0',
			'khaki'=>'#f0e68c',
			'lavender'=>'#e6e6fa',
			'lavenderblush'=>'#fff0f5',
			'lawngreen'=>'#7cfc00',
			'lemonchiffon'=>'#fffacd',
			'lightblue'=>'#add8e6',
			'lightcoral'=>'#f08080',
			'lightcyan'=>'#e0ffff',
			'lightgoldenrodyellow'=>'#fafad2',
			'lightgray'=>'#d3d3d3',
			'lightgrey'=>'#d3d3d3',
			'lightgreen'=>'#90ee90',
			'lightpink'=>'#ffb6c1',
			'lightsalmon'=>'#ffa07a',
			'lightseagreen'=>'#20b2aa',
			'lightskyblue'=>'#87cefa',
			'lightslategray'=>'#778899',
			'lightslategrey'=>'#778899',
			'lightsteelblue'=>'#b0c4de',
			'lightyellow'=>'#ffffe0',
			'lime'=>'#00ff00',
			'limegreen'=>'#32cd32',
			'linen'=>'#faf0e6',
			'magenta'=>'#ff00ff',
			'maroon'=>'#800000',
			'mediumaquamarine'=>'#66cdaa',
			'mediumblue'=>'#0000cd',
			'mediumorchid'=>'#ba55d3',
			'mediumpurple'=>'#9370d8',
			'mediumseagreen'=>'#3cb371',
			'mediumslateblue'=>'#7b68ee',
			'mediumspringgreen'=>'#00fa9a',
			'mediumturquoise'=>'#48d1cc',
			'mediumvioletred'=>'#c71585',
			'midnightblue'=>'#191970',
			'mintcream'=>'#f5fffa',
			'mistyrose'=>'#ffe4e1',
			'moccasin'=>'#ffe4b5',
			'navajowhite'=>'#ffdead',
			'navy'=>'#000080',
			'oldlace'=>'#fdf5e6',
			'olive'=>'#808000',
			'olivedrab'=>'#6b8e23',
			'orange'=>'#ffa500',
			'orangered'=>'#ff4500',
			'orchid'=>'#da70d6',
			'palegoldenrod'=>'#eee8aa',
			'palegreen'=>'#98fb98',
			'paleturquoise'=>'#afeeee',
			'palevioletred'=>'#d87093',
			'papayawhip'=>'#ffefd5',
			'peachpuff'=>'#ffdab9',
			'peru'=>'#cd853f',
			'pink'=>'#ffc0cb',
			'plum'=>'#dda0dd',
			'powderblue'=>'#b0e0e6',
			'purple'=>'#800080',
			'red'=>'#ff0000',
			'rosybrown'=>'#bc8f8f',
			'royalblue'=>'#4169e1',
			'saddlebrown'=>'#8b4513',
			'salmon'=>'#fa8072',
			'sandybrown'=>'#f4a460',
			'seagreen'=>'#2e8b57',
			'seashell'=>'#fff5ee',
			'sienna'=>'#a0522d',
			'silver'=>'#c0c0c0',
			'skyblue'=>'#87ceeb',
			'slateblue'=>'#6a5acd',
			'slategray'=>'#708090',
			'slategrey'=>'#708090',
			'snow'=>'#fffafa',
			'springgreen'=>'#00ff7f',
			'steelblue'=>'#4682b4',
			'tan'=>'#d2b48c',
			'teal'=>'#008080',
			'thistle'=>'#d8bfd8',
			'tomato'=>'#ff6347',
			//'transparent'=>'rgba(0,0,0,0)',
			'turquoise'=>'#40e0d0',
			'violet'=>'#ee82ee',
			'wheat'=>'#f5deb3',
			'white'=>'#ffffff',
			'whitesmoke'=>'#f5f5f5',
			'yellow'=>'#ffff00',
			'yellowgreen'=>'#9acd32'
		);
		return self::$colors;
	}

	public static function hasOwnProperty($color) {
		$colors = self::all();
		return isset($colors[$color]);
	}


	public static function color($color) {
		$colors = self::all();
		return $colors[$color];
	}

}
 

//less.js : lib/less/functions.js


class Less_Environment{

	public $paths = array();			// option - unmodified - paths to search for imports on
	static $files = array();			// list of files that have been imported, used for import-once
	public $relativeUrls;				// option - whether to adjust URL's to be relative
	public $strictImports = false;		// option -
	public $compress = false;			// option - whether to compress
	public $processImports;				// option - whether to process imports. if false then imports will not be imported
	public $currentFileInfo;			// information about the current file - for error reporting and importing and making urls relative etc.

	/**
	 * @var array
	 */
	public $frames = array();


	/**
	 * @var bool
	 */
	public $debug = false;


	/**
	 * @var array
	 */
	public $mediaBlocks = array();

	/**
	 * @var array
	 */
	public $mediaPath = array();

	public $selectors = array();

	public $charset;

	public $parensStack = array();

	public $strictMath = false;

	public $strictUnits = false;

	public function __construct( $options = null ){
		$this->frames = array();


		if( isset($options['compress']) ){
			$this->compress = (bool)$options['compress'];
		}
		if( isset($options['strictUnits']) ){
			$this->strictUnits = (bool)$options['strictUnits'];
		}

	}


	//may want to just use the __clone()?
	public function copyEvalEnv($frames = array() ){
		$new_env = clone $this;
		$new_env->frames = $frames;
		return $new_env;
	}

	public function inParenthesis(){
		$this->parensStack[] = true;
	}

	public function outOfParenthesis() {
		array_pop($this->parensStack);
	}

	public function isMathOn() {
		return $this->strictMath ? ($this->parensStack && count($this->parensStack)) : true;
	}

	public function isPathRelative($path){
		return !preg_match('/^(?:[a-z-]+:|\/)/',$path);
	}

	/**
	 * @return bool
	 */
	public function getCompress(){
		return $this->compress;
	}

	/**
	 * @param bool $compress
	 * @return void
	 */
	public function setCompress($compress){
		$this->compress = $compress;
	}

	/**
	 * @return bool
	 */
	public function getDebug(){
		return $this->debug;
	}

	/**
	 * @param $debug
	 * @return void
	 */
	public function setDebug($debug){
		$this->debug = $debug;
	}

	public function unshiftFrame($frame){
		array_unshift($this->frames, $frame);
	}

	public function shiftFrame(){
		return array_shift($this->frames);
	}

	public function addFrame($frame){
		$this->frames[] = $frame;
	}

	public function addFrames(array $frames){
		$this->frames = array_merge($this->frames, $frames);
	}

	//tree.operate()
	static public function operate ($env, $op, $a, $b){
		switch ($op) {
			case '+': return $a + $b;
			case '-': return $a - $b;
			case '*': return $a * $b;
			case '/': return $a / $b;
		}
	}

	static public function clamp($val){
		return min(1, max(0, $val));
	}

	static public function number($n){

		if ($n instanceof Less_Tree_Dimension) {
			return floatval( $n->unit->is('%') ? $n->value / 100 : $n->value);
		} else if (is_numeric($n)) {
			return $n;
		} else {
			throw new Less_CompilerException("color functions take numbers as parameters");
		}
	}

	static public function scaled($n, $size = 256 ){
		if( $n instanceof Less_Tree_Dimension && $n->unit->is('%') ){
			return (float)$n->value * $size / 100;
		} else {
			return Less_Environment::number($n);
		}
	}



	/** Function **/
	public static function rgb ($r, $g, $b){
		return Less_Environment::rgba($r, $g, $b, 1.0);
	}

	public static function rgba($r, $g, $b, $a){
		$rgb = array($r, $g, $b);
		$rgb = array_map(array('Less_Environment','scaled'),$rgb);

		$a = self::number($a);
		return new Less_Tree_Color($rgb, $a);
	}

	public static function hsl($h, $s, $l){
		return Less_Environment::hsla($h, $s, $l, 1.0);
	}

	public static function hsla($h, $s, $l, $a){

		$h = fmod(self::number($h), 360) / 360; // Classic % operator will change float to int
		$s = self::clamp(self::number($s));
		$l = self::clamp(self::number($l));
		$a = self::clamp(self::number($a));

		$m2 = $l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s;

		$m1 = $l * 2 - $m2;

		return Less_Environment::rgba( self::hsla_hue($h + 1/3, $m1, $m2) * 255,
							self::hsla_hue($h, $m1, $m2) * 255,
							self::hsla_hue($h - 1/3, $m1, $m2) * 255,
							$a);
	}

	static function hsla_hue($h, $m1, $m2){
		$h = $h < 0 ? $h + 1 : ($h > 1 ? $h - 1 : $h);
		if	  ($h * 6 < 1) return $m1 + ($m2 - $m1) * $h * 6;
		else if ($h * 2 < 1) return $m2;
		else if ($h * 3 < 2) return $m1 + ($m2 - $m1) * (2/3 - $h) * 6;
		else				 return $m1;
	}

	public static function hsv($h, $s, $v) {
		return Less_Environment::hsva($h, $s, $v, 1.0);
	}

	public static function hsva($h, $s, $v, $a) {
		$h = ((Less_Environment::number($h) % 360) / 360 ) * 360;
		$s = Less_Environment::number($s);
		$v = Less_Environment::number($v);
		$a = Less_Environment::number($a);

		$i = floor(($h / 60) % 6);
		$f = ($h / 60) - $i;

		$vs = array( $v,
				  $v * (1 - $s),
				  $v * (1 - $f * $s),
				  $v * (1 - (1 - $f) * $s));

		$perm = array(array(0, 3, 1),
					array(2, 0, 1),
					array(1, 0, 3),
					array(1, 2, 0),
					array(3, 1, 0),
					array(0, 1, 2));

		return Less_Environment::rgba($vs[$perm[$i][0]] * 255,
						 $vs[$perm[$i][1]] * 255,
						 $vs[$perm[$i][2]] * 255,
						 $a);
	}

	public static function hue($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension(round($c['h']));
	}

	public static function saturation($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension(round($c['s'] * 100), '%');
	}

	public static function lightness($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension(round($c['l'] * 100), '%');
	}

	public static function hsvhue( $color ){
		$hsv = $color->toHSV();
		return new Less_Tree_Dimension( round($hsv['h']) );
	}


	public static function hsvsaturation( $color ){
		$hsv = $color->toHSV();
		return new Less_Tree_Dimension( round($hsv['s'] * 100), '%' );
	}

	public static function hsvvalue( $color ){
		$hsv = $color->toHSV();
		return new Less_Tree_Dimension( round($hsv['v'] * 100), '%' );
	}

	public static function red($color) {
		return new Less_Tree_Dimension( $color->rgb[0] );
	}

	public static function green($color) {
		return new Less_Tree_Dimension( $color->rgb[1] );
	}

	public static function blue($color) {
		return new Less_Tree_Dimension( $color->rgb[2] );
	}

	public static function alpha($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension($c['a']);
	}

	public static function luma ($color) {
		return new Less_Tree_Dimension(round( $color->luma() * $color->alpha * 100), '%');
	}

	public static function saturate($color, $amount){
		$hsl = $color->toHSL();

		$hsl['s'] += $amount->value / 100;
		$hsl['s'] = self::clamp($hsl['s']);

		return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	public static function desaturate($color, $amount){
		$hsl = $color->toHSL();

		$hsl['s'] -= $amount->value / 100;
		$hsl['s'] = self::clamp($hsl['s']);

		return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}



	public static function lighten($color, $amount){
		$hsl = $color->toHSL();

		$hsl['l'] += $amount->value / 100;
		$hsl['l'] = self::clamp($hsl['l']);

		return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	public static function darken($color, $amount){

		if( $color instanceof Less_Tree_Color ){
			$hsl = $color->toHSL();

			$hsl['l'] -= $amount->value / 100;
			$hsl['l'] = self::clamp($hsl['l']);

			return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
		}

		Less_Environment::Expected('color',$color);
	}

	public static function fadein($color, $amount){
		$hsl = $color->toHSL();
		$hsl['a'] += $amount->value / 100;
		$hsl['a'] = self::clamp($hsl['a']);
		return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	public static function fadeout($color, $amount){
		$hsl = $color->toHSL();
		$hsl['a'] -= $amount->value / 100;
		$hsl['a'] = self::clamp($hsl['a']);
		return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	public static function fade($color, $amount){
		$hsl = $color->toHSL();

		if ($amount->unit == '%') {
			$hsl['a'] = $amount->value / 100;
		} else {
			$hsl['a'] = $amount->value;
		}
		$hsl['a'] = self::clamp($hsl['a']);

		return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}



	public static function spin($color, $amount){
		$hsl = $color->toHSL();
		$hue = fmod($hsl['h'] + $amount->value, 360);

		$hsl['h'] = $hue < 0 ? 360 + $hue : $hue;

		return Less_Environment::hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	//
	// Copyright (c) 2006-2009 Hampton Catlin, Nathan Weizenbaum, and Chris Eppstein
	// http://sass-lang.com
	//
	public static function mix($color1, $color2, $weight = null){
		if (!$weight) {
			$weight = new Less_Tree_Dimension('50', '%');
		}

		$p = $weight->value / 100.0;
		$w = $p * 2 - 1;
		$hsl1 = $color1->toHSL();
		$hsl2 = $color2->toHSL();
		$a = $hsl1['a'] - $hsl2['a'];

		$w1 = (((($w * $a) == -1) ? $w : ($w + $a) / (1 + $w * $a)) + 1) / 2;
		$w2 = 1 - $w1;

		$rgb = array($color1->rgb[0] * $w1 + $color2->rgb[0] * $w2,
					 $color1->rgb[1] * $w1 + $color2->rgb[1] * $w2,
					 $color1->rgb[2] * $w1 + $color2->rgb[2] * $w2);

		$alpha = $color1->alpha * $p + $color2->alpha * (1 - $p);

		return new Less_Tree_Color($rgb, $alpha);
	}

	public static function greyscale($color){
		return Less_Environment::desaturate($color, new Less_Tree_Dimension(100));
	}


	public static function contrast( $color, $dark = false, $light = false, $threshold = false) {
		// filter: contrast(3.2);
		// should be kept as is, so check for color
		if( !property_exists($color,'rgb') ){
			return null;
		}
		if( $light === false ){
			$light = Less_Environment::rgba(255, 255, 255, 1.0);
		}
		if( $dark === false ){
			$dark = Less_Environment::rgba(0, 0, 0, 1.0);
		}
		//Figure out which is actually light and dark!
		if( $dark->luma() > $light->luma() ){
			$t = $light;
			$light = $dark;
			$dark = $t;
		}
		if( $threshold === false ){
			$threshold = 0.43;
		} else {
			$threshold = Less_Environment::number($threshold);
		}

		if( ($color->luma() * $color->alpha) < $threshold ){
			return $light;
		} else {
			return $dark;
		}
	}

	public static function e ($str){
		return new Less_Tree_Anonymous($str instanceof Less_Tree_JavaScript ? $str->evaluated : $str);
	}

	public static function escape ($str){
		return new Less_Tree_Anonymous(urlencode($str->value));
	}


	public static function _percent(){
		$quoted = func_get_arg(0);

		$args = func_get_args();
		array_shift($args);
		$str = $quoted->value;

		foreach($args as $arg){
			if( preg_match('/%[sda]/i',$str, $token) ){
				$token = $token[0];
				$value = stristr($token, 's') ? $arg->value : $arg->toCSS();
				$value = preg_match('/[A-Z]$/', $token) ? urlencode($value) : $value;
				$str = preg_replace('/%[sda]/i',$value, $str, 1);
			}
		}
		$str = str_replace('%%', '%', $str);

		return new Less_Tree_Quoted('"' . $str . '"', $str);
	}

	public static function unit($val, $unit = null ){
		return new Less_Tree_Dimension($val->value, $unit ? $unit->toCSS() : "");
	}

	public static function convert($val, $unit){
		return $val->convertTo($unit->value);
	}

	public static function round($n, $f = false) {

		$fraction = 0;
		if( $f !== false ){
			$fraction = $f->value;
		}

		return Less_Environment::_math('round',null, $n, $fraction);
	}

	public static function pi(){
		return new Less_Tree_Dimension(M_PI);
	}

	public static function mod($a, $b) {
		return new Less_Tree_Dimension( $a->value % $b->value, $a->unit);
	}



	public static function pow($x, $y) {
		if( is_numeric($x) && is_numeric($y) ){
			$x = new Less_Tree_Dimension($x);
			$y = new Less_Tree_Dimension($y);
		}elseif( !($x instanceof Less_Tree_Dimension) || !($y instanceof Less_Tree_Dimension) ){
			throw new Less_CompilerException('Arguments must be numbers');
		}

		return new Less_Tree_Dimension( pow($x->value, $y->value), $x->unit );
	}

	// var mathFunctions = [{name:"ce ...
	public static function ceil( $n ){		return Less_Environment::_math('ceil', null, $n); }
	public static function floor( $n ){	return Less_Environment::_math('floor', null, $n); }
	public static function sqrt( $n ){		return Less_Environment::_math('sqrt', null, $n); }
	public static function abs( $n ){		return Less_Environment::_math('abs', null, $n); }

	public static function tan( $n ){		return Less_Environment::_math('tan', '', $n);	}
	public static function sin( $n ){		return Less_Environment::_math('sin', '', $n);	}
	public static function cos( $n ){		return Less_Environment::_math('cos', '', $n);	}

	public static function atan( $n ){		return Less_Environment::_math('atan', 'rad', $n);	}
	public static function asin( $n ){		return Less_Environment::_math('asin', 'rad', $n);	}
	public static function acos( $n ){		return Less_Environment::_math('acos', 'rad', $n);	}

	private static function _math() {
		$args = func_get_args();
		$fn = array_shift($args);
		$unit = array_shift($args);

		if ($args[0] instanceof Less_Tree_Dimension) {

			if( $unit === null ){
				$unit = $args[0]->unit;
			}else{
				$args[0] = $args[0]->unify();
			}
			$args[0] = (float)$args[0]->value;
			return new Less_Tree_Dimension( call_user_func_array($fn, $args), $unit);
		} else if (is_numeric($args[0])) {
			return call_user_func_array($fn,$args);
		} else {
			throw new Less_CompilerException("math functions take numbers as parameters");
		}
	}

	public static function argb($color) {
		return new Less_Tree_Anonymous($color->toARGB());
	}

	public static function percentage($n) {
		return new Less_Tree_Dimension($n->value * 100, '%');
	}

	public static function color($n) {
		if ($n instanceof Less_Tree_Quoted) {
			return new Less_Tree_Color(substr($n->value, 1));
		} else {
			throw new Less_CompilerException("Argument must be a string");
		}
	}


	public static function iscolor($n) {
		return Less_Environment::_isa($n, 'Less_Tree_Color');
	}

	public static function isnumber($n) {
		return Less_Environment::_isa($n, 'Less_Tree_Dimension');
	}

	public static function isstring($n) {
		return Less_Environment::_isa($n, 'Less_Tree_Quoted');
	}

	public static function iskeyword($n) {
		return Less_Environment::_isa($n, 'Less_Tree_Keyword');
	}

	public static function isurl($n) {
		return Less_Environment::_isa($n, 'Less_Tree_Url');
	}

	public static function ispixel($n) {
		return Less_Environment::isunit($n, 'px');
	}

	public static function ispercentage($n) {
		return Less_Environment::isunit($n, '%');
	}

	public static function isem($n) {
		return Less_Environment::isunit($n, 'em');
	}

	public static function isunit( $n, $unit ){
		return ($n instanceof Less_Tree_Dimension) && $n->unit->is( ( property_exists($unit,'value') ? $unit->value : $unit) ) ? new Less_Tree_Keyword('true') : new Less_Tree_Keyword('false');
	}

	private static function _isa($n, $type) {
		return is_a($n, $type) ? new Less_Tree_Keyword('true') : new Less_Tree_Keyword('false');
	}

	/* Blending modes */

	public static function multiply($color1, $color2) {
		$r = $color1->rgb[0] * $color2->rgb[0] / 255;
		$g = $color1->rgb[1] * $color2->rgb[1] / 255;
		$b = $color1->rgb[2] * $color2->rgb[2] / 255;
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function screen($color1, $color2) {
		$r = 255 - (255 - $color1->rgb[0]) * (255 - $color2->rgb[0]) / 255;
		$g = 255 - (255 - $color1->rgb[1]) * (255 - $color2->rgb[1]) / 255;
		$b = 255 - (255 - $color1->rgb[2]) * (255 - $color2->rgb[2]) / 255;
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function overlay($color1, $color2) {
		$r = $color1->rgb[0] < 128 ? 2 * $color1->rgb[0] * $color2->rgb[0] / 255 : 255 - 2 * (255 - $color1->rgb[0]) * (255 - $color2->rgb[0]) / 255;
		$g = $color1->rgb[1] < 128 ? 2 * $color1->rgb[1] * $color2->rgb[1] / 255 : 255 - 2 * (255 - $color1->rgb[1]) * (255 - $color2->rgb[1]) / 255;
		$b = $color1->rgb[2] < 128 ? 2 * $color1->rgb[2] * $color2->rgb[2] / 255 : 255 - 2 * (255 - $color1->rgb[2]) * (255 - $color2->rgb[2]) / 255;
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function softlight($color1, $color2) {
		$t = $color2->rgb[0] * $color1->rgb[0] / 255;
		$r = $t + $color1->rgb[0] * (255 - (255 - $color1->rgb[0]) * (255 - $color2->rgb[0]) / 255 - $t) / 255;
		$t = $color2->rgb[1] * $color1->rgb[1] / 255;
		$g = $t + $color1->rgb[1] * (255 - (255 - $color1->rgb[1]) * (255 - $color2->rgb[1]) / 255 - $t) / 255;
		$t = $color2->rgb[2] * $color1->rgb[2] / 255;
		$b = $t + $color1->rgb[2] * (255 - (255 - $color1->rgb[2]) * (255 - $color2->rgb[2]) / 255 - $t) / 255;
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function hardlight($color1, $color2) {
		$r = $color2->rgb[0] < 128 ? 2 * $color2->rgb[0] * $color1->rgb[0] / 255 : 255 - 2 * (255 - $color2->rgb[0]) * (255 - $color1->rgb[0]) / 255;
		$g = $color2->rgb[1] < 128 ? 2 * $color2->rgb[1] * $color1->rgb[1] / 255 : 255 - 2 * (255 - $color2->rgb[1]) * (255 - $color1->rgb[1]) / 255;
		$b = $color2->rgb[2] < 128 ? 2 * $color2->rgb[2] * $color1->rgb[2] / 255 : 255 - 2 * (255 - $color2->rgb[2]) * (255 - $color1->rgb[2]) / 255;
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function difference($color1, $color2) {
		$r = abs($color1->rgb[0] - $color2->rgb[0]);
		$g = abs($color1->rgb[1] - $color2->rgb[1]);
		$b = abs($color1->rgb[2] - $color2->rgb[2]);
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function exclusion($color1, $color2) {
		$r = $color1->rgb[0] + $color2->rgb[0] * (255 - $color1->rgb[0] - $color1->rgb[0]) / 255;
		$g = $color1->rgb[1] + $color2->rgb[1] * (255 - $color1->rgb[1] - $color1->rgb[1]) / 255;
		$b = $color1->rgb[2] + $color2->rgb[2] * (255 - $color1->rgb[2] - $color1->rgb[2]) / 255;
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function average($color1, $color2) {
		$r = ($color1->rgb[0] + $color2->rgb[0]) / 2;
		$g = ($color1->rgb[1] + $color2->rgb[1]) / 2;
		$b = ($color1->rgb[2] + $color2->rgb[2]) / 2;
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function negation($color1, $color2) {
		$r = 255 - abs(255 - $color2->rgb[0] - $color1->rgb[0]);
		$g = 255 - abs(255 - $color2->rgb[1] - $color1->rgb[1]);
		$b = 255 - abs(255 - $color2->rgb[2] - $color1->rgb[2]);
		return Less_Environment::rgb($r, $g, $b);
	}

	public static function tint($color, $amount) {
		return Less_Environment::mix( Less_Environment::rgb(255,255,255), $color, $amount);
	}

	public static function shade($color, $amount) {
		return Less_Environment::mix(Less_Environment::rgb(0, 0, 0), $color, $amount);
	}

	public static function extract($values, $index ) {
		$index = $index->value - 1; // (1-based index)
		return $values->value[$index];
	}

	function datauri($mimetypeNode, $filePathNode = null ) {

		$filePath = ( $filePathNode ? $filePathNode->value : null );
		$mimetype = $mimetypeNode->value;
		$useBase64 = false;

		$args = 2;
		if( !$filePath ){
			$filePath = $mimetype;
			$args = 1;
		}

		$filePath = str_replace('\\','/',$filePath);
		if( Less_Environment::isPathRelative($filePath) ){

			if( $this->relativeUrls ){
				$temp = $this->currentFileInfo['currentDirectory'];
			} else {
				$temp = $this->currentFileInfo['entryPath'];
			}

			if( !empty($temp) ){
				$filePath = Less_Environment::NormPath(rtrim($temp,'/').'/'.$filePath);
			}

		}


		// detect the mimetype if not given
		if( $args < 2 ){

			/* incomplete
			$mime = require('mime');
			mimetype = mime.lookup(path);

			// use base 64 unless it's an ASCII or UTF-8 format
			var charset = mime.charsets.lookup(mimetype);
			useBase64 = ['US-ASCII', 'UTF-8'].indexOf(charset) < 0;
			if (useBase64) mimetype += ';base64';
			*/

			$mimetype = Less_Mime::lookup($filePath);

			$charset = Less_Mime::charsets_lookup($mimetype);
			$useBase64 = !in_array($charset,array('US-ASCII', 'UTF-8'));
			if ($useBase64) $mimetype .= ';base64';

		}else{
			$useBase64 = preg_match('/;base64$/',$mimetype);
		}


		if( file_exists($filePath) ){
			$buf = @file_get_contents($filePath);
		}else{
			$buf = false;
		}


		// IE8 cannot handle a data-uri larger than 32KB. If this is exceeded
		// and the --ieCompat flag is enabled, return a normal url() instead.
		$DATA_URI_MAX_KB = 32;
		$fileSizeInKB = round( strlen($buf) / 1024 );
		if( $fileSizeInKB >= $DATA_URI_MAX_KB ){
			$url = new Less_Tree_Url( ($filePathNode ? $filePathNode : $mimetypeNode), $this->currentFileInfo);
			return $url->compile($this);
		}

		if( $buf ){
			$buf = $useBase64 ? base64_encode($buf) : rawurlencode($buf);
			$filePath = "'data:" . $mimetype . ',' . $buf . "'";
		}

		return new Less_Tree_Url( new Less_Tree_Anonymous($filePath) );
	}


	private static function Expected( $type, $arg ){

		$debug = debug_backtrace();
		array_shift($debug);
		$last = array_shift($debug);
		$last = array_intersect_key($last,array('function'=>'','class'=>'','line'=>''));

		$message = 'Object of type '.get_class($arg).' passed to darken function. Expecting `Color`. '.$arg->toCSS().'. '.print_r($last,true);
		throw new Less_CompilerException($message);

	}


	/**
	 * Canonicalize a path by resolving references to '/./', '/../'
	 * Does not remove leading "../"
	 * @param string path or url
	 * @return string Canonicalized path
	 *
	 */
	static function NormPath($path){

		$temp = explode('/',$path);
		$result = array();
		foreach($temp as $i => $p){
			if( $p == '.' ){
				continue;
			}
			if( $p == '..' ){
				for($j=$i-1;$j>0;$j--){
					if( isset($result[$j]) ){
						unset($result[$j]);
						continue 2;
					}
				}
			}
			$result[$i] = $p;
		}

		return implode('/',$result);
	}

}
 

//less.js : lib/less/functions.js

class Less_Mime{

	// this map is intentionally incomplete
	// if you want more, install 'mime' dep
	static $_types = array(
	        '.htm' => 'text/html',
	        '.html'=> 'text/html',
	        '.gif' => 'image/gif',
	        '.jpg' => 'image/jpeg',
	        '.jpeg'=> 'image/jpeg',
	        '.png' => 'image/png'
	        );

	static function lookup( $filepath ){
		$parts = explode('.',$filepath);
		$ext = '.'.strtolower(array_pop($parts));

		if( !isset(self::$_types[$ext]) ){
			return null;
		}
		return self::$_types[$ext];
	}

	static function charsets_lookup( $type = false ){
		// assumes all text types are UTF-8
		return $type && preg_match('/^text\//',$type) ? 'UTF-8' : '';
	}
} 


class Less_Tree_Alpha{
	private $value;
	//public $type = 'Alpha';

	public function __construct($val){
		$this->value = $val;
	}

	/*
	function accept( $visitor ){
		$visitor->visit( $this->value );
	}
	*/

	public function toCss($env){
		return "alpha(opacity=" . (is_string($this->value) ? $this->value : $this->value->toCSS()) . ")";
	}

	public function compile($env){
		if ( ! is_string($this->value)) {
			$this->value = $this->value->compile($env);
		}
		return $this;
	}
} 


class Less_Tree_Anonymous{
	public $value;
	public $quote;
	//public $type = 'Anonymous';

	public function __construct($value){
		$this->value = is_string($value) ? $value : $value->value;
	}

	public function toCss(){
		return $this->value;
	}

	public function compile($env){
		return $this;
	}

	function compare($x){
		if( !Less_Parser::is_method( $x, 'toCSS' ) ){
			return -1;
		}

		$left = $this->toCSS();
		$right = $x->toCSS();

		if( $left === $right ){
			return 0;
		}

		return $left < $right ? -1 : 1;
	}
}
 


class Less_Tree_Assignment {

	//public $type = 'Assignment';
	private $key;
	private $value;

	function __construct($key, $val) {
		$this->key = $key;
		$this->value = $val;
	}

	/*
	function accept( $visitor ){
		$visitor->visit( $this->value );
	}
	*/

    public function toCss($env) {
        return $this->key . '=' . (is_string($this->value) ? $this->value : $this->value->toCSS());
    }

    public function compile($env) {
		if( Less_Parser::is_method($this->value,'compile') ){
			return new Less_Tree_Assignment( $this->key, $this->value->compile($env));
        }
        return $this;
    }

}
 


class Less_Tree_Attribute{

	//public $type = "Attribute";
	public $key;
	public $op;
	public $value;

	function __construct($key, $op, $value){
		$this->key = $key;
		$this->op = $op;
		$this->value = $value;
	}

	/*
	function accept($visitor){
		$visitor->visit($this->value);
	}
	*/

	function compile($env){
		return new Less_Tree_Attribute( ( (Less_Parser::is_method($this->key,'compile')) ? $this->key->compile($env) : $this->key),
			$this->op, ( Less_Parser::is_method($this->value,'compile')) ? $this->value->compile($env) : $this->value);
	}

	function toCSS($env){
		$value = $this->key;

		if( $this->op ){
			$value .= $this->op;
			$value .= ( Less_Parser::is_method($this->value,'toCSS') ? $this->value->toCSS($env) : $this->value);
		}

		return '[' . $value . ']';
	}
} 


//
// A function call node.
//

class Less_Tree_Call{
    public $type = 'Call';
    private $value;

    var $name;
    var $args;
    var $index;
    var $currentFileInfo;

	public function __construct($name, $args, $index, $currentFileInfo = null ){
		$this->name = $name;
		$this->args = $args;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	/*
	function accept( $visitor ){
		$visitor->visit( $this->args );
	}
	*/

    //
    // When evaluating a function call,
    // we either find the function in `tree.functions` [1],
    // in which case we call it, passing the  evaluated arguments,
    // or we simply print it out as it appeared originally [2].
    //
    // The *functions.js* file contains the built-in functions.
    //
    // The reason why we evaluate the arguments, is in the case where
    // we try to pass a variable to a function, like: `saturate(@color)`.
    // The function should receive the value, not the variable.
    //
    public function compile($env){
		$args = array();
		foreach($this->args as $a){
			$args[] = $a->compile($env);
		}

		$name = $this->name;
		if( $name == '%' ){
			$name = '_percent';
		}elseif( $name == 'data-uri' ){
			$name = 'datauri';
		}

		if( Less_Parser::is_method($env, $name) ){ // 1.
			try {

				$result = call_user_func_array( array($env, $name), $args);
				if( $result != null ){
					return $result;
				}

			} catch (Exception $e) {
				throw Less_CompilerException('error evaluating function `' . $this->name . '` '.$e->getMessage().' index: '. $this->index);
			}
		}

		// 2.
		$temp = array();
		foreach($args as $a){
			$temp[] = $a->toCSS($env);
		}
		return new Less_Tree_Anonymous($this->name .
				   "(" . implode(', ', $temp) . ")");
    }

    public function toCSS ($env) {
        return $this->compile($env)->toCSS();
    }

}
 


class Less_Tree_Color{
	//public $type = 'Color';
	var $rgb;
	var $alpha;

	public function __construct($rgb, $a = 1){
		$this->rgb = array();
		if( is_array($rgb) ){
			$this->rgb = $rgb;
		}else if( strlen($rgb) == 6 ){
			foreach(str_split($rgb, 2) as $c){
				$this->rgb[] = hexdec($c);
			}
		}else{
			foreach(str_split($rgb, 1) as $c){
				$this->rgb[] = hexdec($c.$c);
			}
		}
		$this->alpha = is_numeric($a) ? $a : 1;
	}

    public function compile($env = null){
        return $this;
    }

	public function luma(){
		return (0.2126 * $this->rgb[0] / 255) + (0.7152 * $this->rgb[1] / 255) + (0.0722 * $this->rgb[2] / 255);
	}

    //
    // If we have some transparency, the only way to represent it
    // is via `rgba`. Otherwise, we use the hex representation,
    // which has better compatibility with older browsers.
    // Values are capped between `0` and `255`, rounded and zero-padded.
    //
    public function toCSS($env = null, $doNotCompress = false ){
		$compress = $env && $env->compress && !$doNotCompress;
		if ($this->alpha < 1.0) {
			$values = array_map('round', $this->rgb);
			$values[] = $this->alpha;

			$glue = ($compress ? ',' : ', ');
			return "rgba(" . implode($glue, $values) . ")";
		} else {

			$color = '';
			foreach($this->rgb as $i){
				$i = round($i);
				$i = ($i > 255 ? 255 : ($i < 0 ? 0 : $i));
				$i = dechex($i);
				$color .= str_pad($i, 2, '0', STR_PAD_LEFT);
			}

			if( $compress ){

				// Convert color to short format
				if( $color[0] == $color[1] && $color[2] == $color[3] && $color[4] == $color[5]) {
					$color = $color[0] . $color[2] . $color[4];
				}
			}

			return '#'.$color;
		}
    }

    //
    // Operations have to be done per-channel, if not,
    // channels will spill onto each other. Once we have
    // our result, in the form of an integer triplet,
    // we create a new Color node to hold the result.
    //
    public function operate($env, $op, $other) {
        $result = array();

        if (! ($other instanceof Less_Tree_Color)) {
            $other = $other->toColor();
        }

        for ($c = 0; $c < 3; $c++) {
            $result[$c] = Less_Environment::operate($env, $op, $this->rgb[$c], $other->rgb[$c]);
        }
        return new Less_Tree_Color($result, $this->alpha + $other->alpha);
    }

	public function toHSL(){
		$r = $this->rgb[0] / 255;
		$g = $this->rgb[1] / 255;
		$b = $this->rgb[2] / 255;
		$a = $this->alpha;

		$max = max($r, $g, $b);
		$min = min($r, $g, $b);
		$l = ($max + $min) / 2;
		$d = $max - $min;

		if( $max === $min ){
			$h = $s = 0;
		} else {
			$s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

			switch ($max) {
				case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
				case $g: $h = ($b - $r) / $d + 2;                 break;
				case $b: $h = ($r - $g) / $d + 4;                 break;
			}
			$h /= 6;
		}
		return array('h' => $h * 360, 's' => $s, 'l' => $l, 'a' => $a );
	}

	//Adapted from http://mjijackson.com/2008/02/rgb-to-hsl-and-rgb-to-hsv-color-model-conversion-algorithms-in-javascript
	function toHSV() {
		$r = $this->rgb[0] / 255;
		$g = $this->rgb[1] / 255;
		$b = $this->rgb[2] / 255;
		$a = $this->alpha;

		$max = max($r, $g, $b);
		$min = min($r, $g, $b);

		$v = $max;

		$d = $max - $min;
		if ($max === 0) {
			$s = 0;
		} else {
			$s = $d / $max;
		}

		if ($max === $min) {
			$h = 0;
		} else {
			switch($max){
				case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
				case $g: $h = ($b - $r) / $d + 2; break;
				case $b: $h = ($r - $g) / $d + 4; break;
			}
			$h /= 6;
		}
		return array('h'=> $h * 360, 's'=> $s, 'v'=> $v, 'a' => $a );
	}

	public function toARGB(){
		$argb = array_merge( (array) round($this->alpha * 255), $this->rgb);

		$temp = '';
		foreach($argb as $i){
			$i = round($i);
			$i = dechex($i > 255 ? 255 : ($i < 0 ? 0 : $i));
			$temp .= str_pad($i, 2, '0', STR_PAD_LEFT);
		}
		return '#' . $temp;
	}

    public function compare($x){

		if( !property_exists( $x, 'rgb' ) ){
			return -1;
		}


        return ($x->rgb[0] === $this->rgb[0] &&
            $x->rgb[1] === $this->rgb[1] &&
            $x->rgb[2] === $this->rgb[2] &&
            $x->alpha === $this->alpha) ? 0 : -1;
    }
}
 

// less.js : lib/less/tree/element.js


class Less_Tree_Combinator {

	//public $type = 'Combinator';
	public $value;

	public function __construct($value = null) {
		if ($value == ' ') {
			$this->value = ' ';
		} else {
			$this->value = trim($value);
		}
	}

	public function toCSS ($env) {
		$v = array(
			''   => '',
			' '  => ' ',
			':'  => ' :',
			'+'  => $env->compress ? '+' : ' + ',
			'~'  => $env->compress ? '~' : ' ~ ',
			'>'  => $env->compress ? '>' : ' > ',
			'|'  => $env->compress ? '|' : ' | '

		);

		return $v[$this->value];
	}
}
 

class Less_Tree_Comment{

	//public $type = 'Comment';

    public function __construct($value, $silent)
    {
        $this->value = $value;
        $this->silent = !! $silent;
    }

    public function compile($env = null)
    {
        return $this;
    }

    public function toCSS($env)
    {
        return $env->compress ? '' : $this->value;
    }

}
 

class Less_Tree_Condition {

	//public $type = 'Condition';
	private $op;
	private $lvalue;
	private $rvalue;
	private $index;
	private $negate;

	public function __construct($op, $l, $r, $i = 0, $negate = false) {
		$this->op = trim($op);
		$this->lvalue = $l;
		$this->rvalue = $r;
		$this->index = $i;
		$this->negate = $negate;
	}

	/*
	public function accept($visitor){
		$visitor->visit( $this->lvalue );
		$visitor->visit( $this->rvalue );
	}
	*/

    public function compile($env) {
		$a = $this->lvalue->compile($env);
		$b = $this->rvalue->compile($env);

		$i = $this->index;

		switch( $this->op ){
			case 'and':
				$result = $a && $b;
			break;

			case 'or':
				$result = $a || $b;
			break;

			default:
				if( Less_Parser::is_method($a, 'compare') ){
					$result = $a->compare($b);
				}elseif( Less_Parser::is_method($b, 'compare') ){
					$result = $b->compare($a);
				}else{
					throw new Less_CompilerException('Unable to perform comparison', $this->index);
				}

				switch ($result) {
					case -1:
					$result = $this->op === '<' || $this->op === '=<';
					break;

					case  0:
					$result = $this->op === '=' || $this->op === '>=' || $this->op === '=<';
					break;

					case  1:
					$result = $this->op === '>' || $this->op === '>=';
					break;
				}
			break;
		}

		return $this->negate ? !$result : $result;
    }

}
 


class Less_Tree_Dimension{

	//public $type = 'Dimension';

    public function __construct($value, $unit = false){
        $this->value = floatval($value);

		if( $unit && ($unit instanceof Less_Tree_Unit) ){
			$this->unit = $unit;
		}elseif( $unit ){
			$this->unit = new Less_Tree_Unit( array($unit) );
		}else{
			$this->unit = new Less_Tree_Unit( );
		}
    }

	/*
	function accept( $visitor ){
		$visitor->visit( $this->unit );
	}
	*/

    public function compile($env = null) {
        return $this;
    }

    public function toColor() {
        return new Less_Tree_Color(array($this->value, $this->value, $this->value));
    }

	public function toCSS( $env = null ){

		if( ($env && $env->strictUnits) && !$this->unit->isSingular() ){
			throw new Less_CompilerException("Multiple units in dimension. Correct the units or use the unit function. Bad unit: ".$this->unit->toString());
		}

		$value = $this->value;
		$strValue = (string)$value;

		if( $value !== 0 && $value < 0.000001 && $value > -0.000001 ){
			// would be output 1e-6 etc.
			$strValue = number_format($strValue,10);
			$strValue = preg_replace('/\.?0+$/','', $strValue);
		}

		if( $env && $env->compress ){
			// Zero values doesn't need a unit
			if( $value === 0 && !$this->unit->isAngle() ){
				return $strValue;
			}

			// Float values doesn't need a leading zero
			if( $value > 0 && $value < 1 && $strValue[0] === '0' ){
				$strValue = substr($strValue,1);
			}
		}

		return $strValue . $this->unit->toCSS($env);
	}

    public function __toString(){
        return $this->toCSS();
    }

    // In an operation between two Dimensions,
    // we default to the first Dimension's unit,
    // so `1px + 2em` will yield `3px`.
    public function operate($env, $op, $other){

		$value = Less_Environment::operate($env, $op, $this->value, $other->value);
		$unit = clone $this->unit;

		if( $op === '+' || $op === '-' ){

			if( !count($unit->numerator) && !count($unit->denominator) ){
				$unit->numerator = $other->unit->numerator;
				$unit->denominator = $other->unit->denominator;
			}elseif( !count($other->unit->numerator) && !count($other->unit->denominator) ){
				// do nothing
			}else{
				$other = $other->convertTo( $this->unit->usedUnits());

				if( $env->strictUnits && $other->unit->toString() !== $unit->toCSS() ){
					throw new Less_CompilerException("Incompatible units. Change the units or use the unit function. Bad units: '".$unit->toString() . "' and ".$other->unit->toString()+"'.");
				}

				$value = Less_Environment::operate($env, $op, $this->value, $other->value);
			}
		}elseif( $op === '*' ){
			$unit->numerator = array_merge($unit->numerator, $other->unit->numerator);
			$unit->denominator = array_merge($unit->denominator, $other->unit->denominator);
			sort($unit->numerator);
			sort($unit->denominator);
			$unit->cancel();
		}elseif( $op === '/' ){
			$unit->numerator = array_merge($unit->numerator, $other->unit->denominator);
			$unit->denominator = array_merge($unit->denominator, $other->unit->numerator);
			sort($unit->numerator);
			sort($unit->denominator);
			$unit->cancel();
		}
		return new Less_Tree_Dimension( $value, $unit);
    }

	public function compare($other) {
		if ($other instanceof Less_Tree_Dimension) {

			$a = $this->unify();
			$b = $other->unify();
			$aValue = $a->value;
			$bValue = $b->value;

			if ($bValue > $aValue) {
				return -1;
			} elseif ($bValue < $aValue) {
				return 1;
			} else {
				if( !$b->unit->isEmpty() && $a->unit->compare($b->unit) !== 0) {
					return -1;
				}
				return 0;
			}
		} else {
			return -1;
		}
	}

	function unify() {
		return $this->convertTo(array('length'=> 'm', 'duration'=> 's', 'angle' => 'rad' ));
	}

    function convertTo($conversions) {
		$value = $this->value;
		$unit = clone $this->unit;

		if( is_string($conversions) ){
			$derivedConversions = array();
			foreach( Less_Tree_UnitConversions::$groups as $i ){
				if( isset(Less_Tree_UnitConversions::${$i}[$conversions]) ){
					$derivedConversions = array( $i => $conversions);
				}
			}
			$conversions = $derivedConversions;
		}


		foreach($conversions as $groupName => $targetUnit){
			$group = Less_Tree_UnitConversions::${$groupName};

			//numerator
			for($i=0; $i < count($unit->numerator); $i++ ){
				$atomicUnit = $unit->numerator[$i];
				if( !isset($group[$atomicUnit]) ){
					continue;
				}

				$value = $value * ($group[$atomicUnit] / $group[$targetUnit]);

				$unit->numerator[$i] = $targetUnit;
			}

			//denominator
			for($i=0; $i < count($unit->denominator); $i++ ){
				$atomicUnit = $unit->denominator[$i];
				if( !isset($group[$atomicUnit]) ){
					continue;
				}

				$value = $value / ($group[$atomicUnit] / $group[$targetUnit]);

				$unit->denominator[$i] = $targetUnit;
			}
		}

		$unit->cancel();

		return new Less_Tree_Dimension( $value, $unit);
    }
}
 

class Less_Tree_Directive{

	//public $type = 'Directive';
    public $name;
    public $value;
    public $ruleset;

    public function __construct($name, $value = null){
        $this->name = $name;
        if (is_array($value)) {
            $this->ruleset = new Less_Tree_Ruleset(false, $value);
            $this->ruleset->allowImports = true;
        } else {
            $this->value = $value;
        }
    }


	function accept( $visitor ){
		$visitor->visit( $this->ruleset );
		//$visitor->visit( $this->value );
	}

	public function toCSS( $env){
		if ($this->ruleset) {
			$this->ruleset->root = true;
			return $this->name . ($env->compress ? '{' : " {\n  ") .
				   preg_replace('/\n/', "\n  ", trim($this->ruleset->toCSS($env))) .
				   ($env->compress ? '}': "\n}\n");
		} else {
			return $this->name . ' ' . $this->value->toCSS($env) . ";\n";
		}
	}

    public function compile($env)
    {
        $evaldDirective = $this;
        if( $this->ruleset ){
			$env->unshiftFrame($this);
            $evaldDirective = new Less_Tree_Directive( $this->name );
            $evaldDirective->ruleset = $this->ruleset->compile($env);
            $env->shiftFrame();
        }
        return $evaldDirective;
    }
    // TODO: Not sure if this is right...
    public function variable($name)
    {
        return $this->ruleset->variable($name);
    }

    public function find($selector)
    {
        return $this->ruleset->find($selector, $this);
    }

}
 

//less.js : lib/less/tree/element.js

class Less_Tree_Element{
	//public $type = 'Element';
	public $combinator;
    public $value;
	public $index;

    public function __construct($combinator, $value, $index = null) {
        if ( ! ($combinator instanceof Less_Tree_Combinator)) {
            $combinator = new Less_Tree_Combinator($combinator);
        }

		if (is_string($value)) {
			$this->value = trim($value);
		} elseif ($value) {
			$this->value = $value;
		} else {
			$this->value = "";
		}

        $this->combinator = $combinator;
		$this->index = $index;
    }

	/*
	function accept( $visitor ){
		$visitor->visit( $this->combinator );
		$visitor->visit( $this->value );
	}
	*/

	//less.js : tree.Element.prototype.toCSS
    public function toCSS ($env) {

        $value = $this->value;
        if( !is_string($value) ){
			$value = $value->toCSS($env);
		}

		if( $value == '' && strlen($this->combinator->value) && $this->combinator->value[0] == '&' ){
			return '';
		}
		return $this->combinator->toCSS($env) . $value;
    }

	public function compile($env) {
		return new Less_Tree_Element($this->combinator,
			is_string($this->value) ? $this->value : $this->value->compile($env),
			$this->index
		);
	}
}
 

class Less_Tree_Expression {

	//public $type = 'Expression';
	public $value = array();
	public $parens = false;
	public $parensInOp = false;

	public function __construct($value) {
		$this->value = $value;
	}

	/*function accept( $visitor ){
		$visitor->visit( $this->value );
	}
	*/

	public function compile($env) {

		$inParenthesis = $this->parens && !$this->parensInOp;
		$doubleParen = false;
		if( $inParenthesis ) {
			$env->inParenthesis();
		}

		if (is_array($this->value) && count($this->value) > 1) {

			$ret = array();
			foreach($this->value as $e){
				$ret[] = $e->compile($env);
			}
			$returnValue = new Less_Tree_Expression($ret);

		} else if (is_array($this->value) && count($this->value) == 1) {

			if( !isset($this->value[0]) ){
				$this->value = array_slice($this->value,0);
			}

			if( property_exists($this->value[0], 'parens') && $this->value[0]->parens && !$this->value[0]->parensInOp ){
				$doubleParen = true;
			}

			$returnValue = $this->value[0]->compile($env);
		} else {
			$returnValue = $this;
		}
		if( $inParenthesis ){
			$env->outOfParenthesis();
		}
		if( $this->parens && $this->parensInOp && !$env->isMathOn() && !$doubleParen ){
			$returnValue = new Less_Tree_Paren($returnValue);
		}
		return $returnValue;
	}

	public function toCSS ($env) {

		$ret = array();
		foreach($this->value as $e){
			$ret[] = Less_Parser::is_method($e, 'toCSS') ? $e->toCSS($env) : '';
		}

		return implode(' ',$ret);
	}

	function throwAwayComments() {

		if( is_array($this->value) ){
			$new_value = array();
			foreach($this->value as $v){
				if( $v instanceof Less_Tree_Comment ){
					continue;
				}
				$new_value[] = $v;
			}
			$this->value = $new_value;
		}
	}
}
 


class Less_Tree_Extend{

	//public $type = 'Extend';
	public $selector;
	public $option;
	public $index;
	public $selfSelectors = array();
	public $allowBefore;
	public $allowAfter;
	public $parents = array();
	public $firstExtendOnThisSelectorPath;


	function __construct($selector, $option, $index){
		$this->selector = $selector;
		$this->option = $option;
		$this->index = $index;

		switch($option){
	        case "all":
	            $this->allowBefore = true;
	            $this->allowAfter = true;
	        break;
	        default:
	            $this->allowBefore = false;
	            $this->allowAfter = false;
	        break;
		}

	}

	/*
	function accept( $visitor ){
		$visitor->visit( $this->selector );
	}
	*/

	function compile( $env ){
		return new Less_Tree_Extend( $this->selector->compile($env), $this->option, $this->index);
	}


	function findSelfSelectors( $selectors){
		$selfElements = array();

		for($i = 0; $i < count($selectors); $i++ ){
			$selfElements = array_merge($selfElements, $selectors[$i]->elements);
		}

		$this->selfSelectors = array(new Less_Tree_Selector($selfElements));
	}
} 



//
// CSS @import node
//
// The general strategy here is that we don't want to wait
// for the parsing to be completed, before we start importing
// the file. That's because in the context of a browser,
// most of the time will be spent waiting for the server to respond.
//
// On creation, we push the import path to our import queue, though
// `import,push`, we also pass it a callback, which it'll call once
// the file has been fetched, and parsed.
//
class Less_Tree_Import{

	//public $type = 'Import';
	public $options;
	public $index;
	public $path;
	public $features;
	public $currentFileInfo;
	public $css;
	public $skip;
	public $root;

	function __construct($path, $features, $options, $index, $currentFileInfo = null ){
		$this->options = $options;
		$this->index = $index;
		$this->path = $path;
		$this->features = $features;
		$this->currentFileInfo = $currentFileInfo;


		if( isset($this->options['less']) ){
			$this->css = !$this->options['less'];
		} else {
			$pathValue = $this->getPath();
			if( $pathValue && preg_match('/css([\?;].*)?$/',$pathValue) ){
				$this->css = true;
			}
		}
    }

//
// The actual import node doesn't return anything, when converted to CSS.
// The reason is that it's used at the evaluation stage, so that the rules
// it imports can be treated like any other rules.
//
// In `eval`, we make sure all Import nodes get evaluated, recursively, so
// we end up with a flat structure, which can easily be imported in the parent
// ruleset.
//
	/*
	function accept($visitor) {
		$visitor->visit($this->features);
		$visitor->visit($this->path);
		$visitor->visit($this->root);
	}
	*/

	function toCSS($env) {
		$features = $this->features ? ' ' . $this->features->toCSS($env) : '';

		if ($this->css) {
			return "@import " . $this->path->toCSS() . $features . ";\n";
		} else {
			return "";
		}
	}

	function getPath(){
		if ($this->path instanceof Less_Tree_Quoted) {
			$path = $this->path->value;
			return ( isset($this->css) || preg_match('/(\.[a-z]*$)|([\?;].*)$/',$path)) ? $path : $path . '.less';
		} else if ($this->path instanceof Less_Tree_URL) {
			return $this->path->value->value;
		}
		return null;
	}

	function compileForImport( $env ){
		return new Less_Tree_Import( $this->path->compile($env), $this->features, $this->options, $this->index, $this->currentFileInfo);
	}

	function compilePath($env) {
		$path = $this->path->compile($env);
		if( $this->currentFileInfo && $this->currentFileInfo['rootpath'] && !($path instanceof Less_Tree_URL)) {
			$pathValue = $path->value;
			// Add the base path if the import is relative
			if( $pathValue && $env->isPathRelative($pathValue) ){
				$path->value = Less_Environment::NormPath($this->currentFileInfo['uri_root']. $pathValue);
			}
		}
		return $path;
	}

	function compile($env) {

		$evald = $this->compileForImport($env);
		$uri = $full_path = false;

		//get path & uri
		$evald_path = $evald->getPath();
		if( $evald_path && $env->isPathRelative($evald_path) ){
			foreach(Less_Parser::$import_dirs as $rootpath => $rooturi){
				$temp = $rootpath.$evald_path;
				if( file_exists($temp) ){
					$full_path = Less_Environment::NormPath($temp);
					$uri = Less_Environment::NormPath(dirname($rooturi.$evald_path));
					break;
				}
			}
		}

		if( !$full_path ){
			$uri = $evald_path;
			$full_path = $evald_path;
		}

		//import once
		$realpath = realpath($full_path);
		if( !isset($evald->options['multiple']) && $realpath && Less_Parser::FileParsed($realpath) ){
			$evald->skip = true;
		}

		$features = ( $evald->features ? $evald->features->compile($env) : null );

		if ($evald->skip) { return array(); }

		if( $evald->css ){
			$temp = $this->compilePath( $env);
			return new Less_Tree_Import( $this->compilePath( $env), $features, $this->options, $this->index);
		}

		$parser = new Less_Parser($env);
		$evald->root = $parser->parseFile($full_path, $uri, true);
		$ruleset = new Less_Tree_Ruleset(array(), $evald->root->rules );
		$ruleset->evalImports($env);

		return $this->features ? new Less_Tree_Media($ruleset->rules, $this->features->value) : $ruleset->rules;
	}
}

 

class Less_Tree_Javascript{

	//public $type = 'Javascript';

	public function __construct($string, $index, $escaped){
		$this->escaped = $escaped;
		$this->expression = $string;
		$this->index = $index;
	}

	public function compile($env){
		return $this;
	}

	public function toCss($env){
		return $env->compress ? '' : '/* Sorry, can not do JavaScript evaluation in PHP... :( */';
	}
}
 


class Less_Tree_Keyword{

	//public $type = 'Keyword';
	public function __construct($value){
		$this->value = $value;
	}

	public function toCss(){
		return $this->value;
	}

	public function compile($env){
		return $this;
	}

	public function compare($other) {
		if ($other instanceof Less_Tree_Keyword) {
			return $other->value === $this->value ? 0 : 1;
		} else {
			return -1;
		}
	}
}
 

//less.js : lib/less/tree/media.js

class Less_Tree_Media {

	//public $type = 'Media';
	public $features;
	public $ruleset;

	public function __construct($value = array(), $features = array()) {
		$selectors = $this->emptySelectors();
		$this->features = new Less_Tree_Value($features);
		$this->ruleset = new Less_Tree_Ruleset($selectors, $value);
		$this->ruleset->allowImports = true;
	}

	function accept( $visitor ){
		//$visitor->visit($this->features);
		$visitor->visit($this->ruleset);
	}

	public function toCSS($env) {
		$features = $this->features->toCSS($env);
		return '@media ' . $features . ($env->compress ? '{' : " {\n  ")
			. str_replace("\n", "\n  ", trim($this->ruleset->toCSS($env)))
			. ($env->compress ? '}' : "\n}\n");
	}

	public function compile($env) {

		$media = new Less_Tree_Media(array(), array());

		$strictMathBypass = false;
		if( $env->strictMath === false) {
			$strictMathBypass = true;
			$env->strictMath = true;
		}
		try {
			$media->features = $this->features->compile($env);
		}catch(Exception $e){}

		if( $strictMathBypass ){
			$env->strictMath = false;
		}

		$env->mediaPath[] = $media;
		$env->mediaBlocks[] = $media;

		array_unshift($env->frames, $this->ruleset);
		$media->ruleset = $this->ruleset->compile($env);
		array_shift($env->frames);

		array_pop($env->mediaPath);

		return count($env->mediaPath) == 0 ? $media->compileTop($env) : $media->compileNested($env);
	}

	// TODO: Not sure if this is right...
	public function variable($name) {
		return $this->ruleset->variable($name);
	}

	public function find($selector) {
		return $this->ruleset->find($selector, $this);
	}

	public function emptySelectors(){
		$el = new Less_Tree_Element('','&', 0);
		return array(new Less_Tree_Selector(array($el)));
	}


	// evaltop
	public function compileTop($env) {
		$result = $this;

		if (count($env->mediaBlocks) > 1) {
			$selectors = $this->emptySelectors();
			$result = new Less_Tree_Ruleset($selectors, $env->mediaBlocks);
			$result->multiMedia = true;
		}

		$env->mediaBlocks = array();
		$env->mediaPath = array();

		return $result;
	}

	public function compileNested($env) {
		$path = array_merge($env->mediaPath, array($this));

		// Extract the media-query conditions separated with `,` (OR).
		foreach ($path as $key => $p) {
			$value = $p->features instanceof Less_Tree_Value ? $p->features->value : $p->features;
			$path[$key] = is_array($value) ? $value : array($value);
		}

		// Trace all permutations to generate the resulting media-query.
		//
		// (a, b and c) with nested (d, e) ->
		//	a and d
		//	a and e
		//	b and c and d
		//	b and c and e

		$permuted = $this->permute($path);
		$expressions = array();
		foreach($permuted as $path){

			for( $i=0, $len=count($path); $i < $len; $i++){
				$path[$i] = Less_Parser::is_method($path[$i], 'toCSS') ? $path[$i] : new Less_Tree_Anonymous($path[$i]);
			}

			for( $i = count($path) - 1; $i > 0; $i-- ){
				array_splice($path, $i, 0, array(new Less_Tree_Anonymous('and')));
			}

			$expressions[] = new Less_Tree_Expression($path);
		}
		$this->features = new Less_Tree_Value($expressions);



		// Fake a tree-node that doesn't output anything.
		return new Less_Tree_Ruleset(array(), array());
	}

	public function permute($arr) {
		if (!$arr)
			return array();

		if (count($arr) == 1)
			return $arr[0];

		$result = array();
		$rest = $this->permute(array_slice($arr, 1));
		foreach ($rest as $r) {
			foreach ($arr[0] as $a) {
				$result[] = array_merge(
					is_array($a) ? $a : array($a),
					is_array($r) ? $r : array($r)
				);
			}
		}

		return $result;
	}

    function bubbleSelectors($selectors) {
		$this->ruleset = new Less_Tree_Ruleset( $selectors, array($this->ruleset) );
    }

}
 


class Less_Tree_MixinCall{

	//public $type = 'MixinCall';
	private $selector;
	private $arguments;
	private $index;
	private $currentFileInfo;

	public $important;

	/**
	 * less.js: tree.mixin.Call
	 *
	 */
    public function __construct($elements, $args, $index, $currentFileInfo, $important = false){
        $this->selector = new Less_Tree_Selector($elements);
        $this->arguments = $args;
        $this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
		$this->important = $important;
    }

	/*
	function accept($visitor){
		$visitor->visit($this->selector);
		$visitor->visit($this->arguments);
	}
	*/


	/**
	 * less.js: tree.mixin.Call.prototype()
	 *
	 */
    public function compile($env){

        $rules = array();
        $match = false;
        $isOneFound = false;

		$args = array();
		foreach($this->arguments as $a){
			$args[] = array('name'=> $a['name'], 'value' => $a['value']->compile($env) );
		}

		for($i = 0; $i< count($env->frames); $i++){

			$mixins = $env->frames[$i]->find($this->selector, null, $env);

            if( !$mixins ){
				continue;
			}

			$isOneFound = true;
			foreach( $mixins as $mixin ){

				$isRecursive = false;
				foreach($env->frames as $recur_frame){
					if( !($mixin instanceof Less_Tree_MixinDefinition) ){
						if( (isset($recur_frame->originalRuleset) && $mixin === $recur_frame->originalRuleset) || ($mixin === $recur_frame) ){
							$isRecursive = true;
							break;
						}
					}
				}
				if( $isRecursive ){
					continue;
				}

				if ($mixin->matchArgs($args, $env)) {
					if( !Less_Parser::is_method($mixin,'matchCondition') || $mixin->matchCondition($args, $env) ){
						try {
							$rules = array_merge($rules, $mixin->compile($env, $args, $this->important)->rules);
						} catch (Exception $e) {
							//throw new Less_CompilerException($e->getMessage(), $e->index, null, $this->currentFileInfo['filename']);
							throw new Less_CompilerException($e->getMessage(), null, null, $this->currentFileInfo['filename']);
						}
					}
					$match = true;
				}

			}

			if( $match ){
				return $rules;
			}

        }


        if( $isOneFound ){

			$message = array();
			if( $args ){
				foreach($args as $a){
					$argValue = '';
					if( $a['name'] ){
						$argValue += $a['name']+':';
					}
					if( Less_Parser::is_method($a['value'],'toCSS') ){
						$argValue += $a['value']->toCSS();
					}else{
						$argValue += '???';
					}
					$message[] = $argValue;
				}
			}
			$message = implode(', ',$message);


			throw new Less_CompilerException('No matching definition was found for `'.
				trim($this->selector->toCSS($env)) . '(' .$message.')',
				$this->index, null, $this->currentFileInfo['filename']);

		}else{
			throw new Less_CompilerException(trim($this->selector->toCSS($env)) . " is undefined", $this->index);
		}
    }
}


 

class Less_Tree_MixinDefinition extends Less_Tree_Ruleset{
	//public $type = 'MixinDefinition';
	public $name;
	public $selectors;
	public $params;
	public $arity;
	public $rules;
	public $lookups;
	public $required;
	public $frames;
	public $condition;
	public $variadic;

	// less.js : /lib/less/tree/mixin.js : tree.mixin.Definition
	public function __construct($name, $params, $rules, $condition, $variadic = false){
		$this->name = $name;
		$this->selectors = array(new Less_Tree_Selector(array( new Less_Tree_Element(null, $name))));
		$this->params = $params;
		$this->condition = $condition;
		$this->variadic = $variadic;
		$this->arity = count($params);
		$this->rules = $rules;
		$this->lookups = array();

		$this->required = 0;
		foreach( $params as $p ){
			if (! isset($p['name']) || ($p['name'] && !isset($p['value']))) {
				$this->required++;
			}
		}

		$this->frames = array();
	}

	/*
	function accept( $visitor ){
		$visitor->visit($this->params);
		$visitor->visit($this->rules);
		$visitor->visit($this->condition);
	}
	*/

	public function toCss($env){
		return '';
	}

	// less.js : /lib/less/tree/mixin.js : tree.mixin.Definition.evalParams
	public function compileParams($env, $mixinEnv, $args = array() , &$evaldArguments = array() ){
		$frame = new Less_Tree_Ruleset(null, array());
		$varargs;
		$params = $this->params;
		$val;
		$name;
		$isNamedFound;


		$mixinEnv = clone $mixinEnv;
		$mixinEnv->frames = array_merge( array($frame), $mixinEnv->frames);
		//$mixinEnv = $mixinEnv->copyEvalEnv( array_merge( array($frame), $mixinEnv->frames) );

		for($i = 0; $i < count($args); $i++ ){
			$arg = $args[$i];

			if( $arg && $arg['name'] ){
				$name = $arg['name'];
				$isNamedFound = false;

				foreach($params as $j => $param){
					if( !isset($evaldArguments[$j]) && $name === $params[$j]['name']) {
						$evaldArguments[$j] = $arg['value']->compile($env);
						array_unshift($frame->rules, new Less_Tree_Rule( $name, $arg['value']->compile($env) ) );
						$isNamedFound = true;
						break;
					}
				}
				if ($isNamedFound) {
					array_splice($args, $i, 1);
					$i--;
					continue;
				} else {
					throw new Less_CompilerException("Named argument for " . $this->name .' '.$args[$i]['name'] . ' not found');
				}
			}
		}

		$argIndex = 0;
		foreach($params as $i => $param){

			if ( isset($evaldArguments[$i]) ) continue;

			$arg = null;
			if( array_key_exists($argIndex,$args) && $args[$argIndex] ){
				$arg = $args[$argIndex];
			}

			if (isset($param['name']) && $param['name']) {
				$name = $param['name'];

				if( isset($param['variadic']) && $args ){
					$varargs = array();
					for ($j = $argIndex; $j < count($args); $j++) {
						$varargs[] = $args[$j]['value']->compile($env);
					}
					$expression = new Less_Tree_Expression($varargs);
					array_unshift($frame->rules, new Less_Tree_Rule($param['name'], $expression->compile($env)));
				}else{
					$val = ($arg && $arg['value']) ? $arg['value'] : false;

					if ($val) {
						$val = $val->compile($env);
					} else if ( isset($param['value']) ) {
						$val = $param['value']->compile($mixinEnv);
						$frame->resetCache();
					} else {
						throw new Less_CompilerException("Wrong number of arguments for " . $this->name . " (" . count($args) . ' for ' . $this->arity . ")");
					}

					array_unshift($frame->rules, new Less_Tree_Rule($param['name'], $val));
					$evaldArguments[$i] = $val;
				}
			}

			if ( isset($param['variadic']) && $args) {
				for ($j = $argIndex; $j < count($args); $j++) {
					$evaldArguments[$j] = $args[$j]['value']->compile($env);
				}
			}
			$argIndex++;
		}

		asort($evaldArguments);

		return $frame;
	}

	// less.js : /lib/less/tree/mixin.js : tree.mixin.Definition.eval
	public function compile($env, $args = NULL, $important = NULL) {
		$_arguments = array();

		$mixinFrames = array_merge($this->frames, $env->frames);

		$mixinEnv = new Less_Environment();
		$mixinEnv->addFrames($mixinFrames);

		$frame = $this->compileParams($env, $mixinEnv, $args, $_arguments);



		$ex = new Less_Tree_Expression($_arguments);
		array_unshift($frame->rules, new Less_Tree_Rule('@arguments', $ex->compile($env)));

		$rules = $important
			? Less_Tree_Ruleset::makeImportant($this->selectors, $this->rules)->rules
			: array_slice($this->rules, 0);

		$ruleset = new Less_Tree_Ruleset(null, $rules);


		// duplicate the environment, adding new frames.
		$ruleSetEnv = new Less_Environment();
		$ruleSetEnv->addFrame($this);
		$ruleSetEnv->addFrame($frame);
		$ruleSetEnv->addFrames($mixinFrames);
		$ruleSetEnv->compress = $env->compress;
		$ruleset = $ruleset->compile($ruleSetEnv);

		$ruleset->originalRuleset = $this;
		return $ruleset;
	}


	public function matchCondition($args, $env) {

		if( !$this->condition ){
			return true;
		}

		$mixinEnv = $env->copyEvalEnv( array_merge($this->frames, $env->frames) );

		$frame = $this->compileParams( $env, $mixinEnv, $args );

		$env->frames = array_merge( array($frame) , $env->frames);

		if( !$this->condition->compile($env) ){
			return false;
		}

		return true;
	}

	public function matchArgs($args, $env = NULL){

		if (!$this->variadic) {
			if (count($args) < $this->required){
				return false;
			}
			if (count($args) > count($this->params)){
				return false;
			}
			if (($this->required > 0) && (count($args) > count($this->params))){
				return false;
			}
		}

		$len = min(count($args), $this->arity);

		for( $i = 0; $i < $len; $i++ ){
			if( !isset($this->params[$i]['name']) && !isset($this->params[$i]['variadic']) ){
				if( $args[$i]['value']->compile($env)->toCSS() != $this->params[$i]['value']->compile($env)->toCSS() ){
					return false;
				}
			}
		}

		return true;
	}

}
 


class Less_Tree_Negative{

	//public $type = 'Negative';
	public $value;

	function __construct($node){
		$this->value = $node;
	}

	/*
	function accept($visitor) {
		$visitor->visit($this->value);
	}
	*/

	function toCSS($env){
		return '-'.$this->value->toCSS($env);
	}

	function compile($env) {
		if( $env->isMathOn() ){
			$ret = new Less_Tree_Operation('*', array( new Less_Tree_Dimension(-1), $this->value ) );
			return $ret->compile($env);
		}
		return new Less_Tree_Negative( $this->value->compile($env) );
	}
} 

class Less_Tree_Operation{

	//public $type = 'Operation';

	public function __construct($op, $operands, $isSpaced = false){
		$this->op = trim($op);
		$this->operands = $operands;
		$this->isSpaced = $isSpaced;
	}

	/*function accept($visitor) {
		$visitor->visit($this->operands);
	}*/

	public function compile($env){
		$a = $this->operands[0]->compile($env);
		$b = $this->operands[1]->compile($env);


		if( $env->isMathOn() ){
			if( $a instanceof Less_Tree_Dimension && $b instanceof Less_Tree_Color ){
				if ($this->op === '*' || $this->op === '+') {
					$temp = $b;
					$b = $a;
					$a = $temp;
				} else {
					throw new Less_CompilerError("Operation on an invalid type");
				}
			}
			if ( !Less_Parser::is_method($a,'operate') ) {
				throw new Less_CompilerError("Operation on an invalid type");
			}

			return $a->operate($env,$this->op, $b);
		} else {
			return new Less_Tree_Operation($this->op, array($a, $b), $this->isSpaced );
		}
	}

	function toCSS($env){
		$separator = $this->isSpaced ? " " : "";
		return $this->operands[0]->toCSS($env) . $separator . $this->op . $separator . $this->operands[1]->toCSS($env);
	}
}
 

class Less_Tree_Paren {

	//public $type = 'Paren';
	public $value;

	public function __construct($value) {
		$this->value = $value;
	}

	/*
	function accept($visitor){
		$visitor->visit($this->value);
	}
	*/

	public function toCSS($env) {
		return '(' . trim($this->value->toCSS($env)) . ')';
	}

	public function compile($env) {
		return new Less_Tree_Paren($this->value->compile($env));
	}

}
 


class Less_Tree_Quoted{
	//public $type = 'Quoted';
	public $value;
	public $content;
	public $index;
	public $currentFileInfo;

	public function __construct($str, $content = '', $escaped = false, $index = false, $currentFileInfo = null ){
		$this->escaped = $escaped;
		$this->value = $content;
		$this->quote = $str[0];
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	public function toCSS (){
		if ($this->escaped) {
			return $this->value;
		} else {
			return $this->quote . $this->value . $this->quote;
		}
	}

	public function compile($env){

		$value = $this->value;
		if( preg_match_all('/`([^`]+)`/', $this->value, $matches) ){
			foreach($matches as $i => $match){
				$js = new Less_Tree_JavaScript($matches[1], $this->index, true);
				$js = $js->compile($env)->value;
				$value = str_replace($matches[0][$i], $js, $value);
			}
		}

		if( preg_match_all('/@\{([\w-]+)\}/',$value,$matches) ){
			foreach($matches[1] as $i => $match){
				$v = new Less_Tree_Variable('@' . $match, $this->index, $this->currentFileInfo );
				$v = $v->compile($env,true);
				$v = ($v instanceof Less_Tree_Quoted) ? $v->value : $v->toCSS($env);
				$value = str_replace($matches[0][$i], $v, $value);
			}
		}

		return new Less_Tree_Quoted($this->quote . $value . $this->quote, $value, $this->escaped, $this->index);
	}

	function compare($x) {

		if( !Less_Parser::is_method($x, 'toCSS') ){
			return -1;
		}

		$left = $this->toCSS();
		$right = $x->toCSS();

		if ($left === $right) {
			return 0;
		}

		return $left < $right ? -1 : 1;
	}
}
 


class Less_Tree_Rule{
	//public $type = 'Rule';
	public $name;
	public $value;
	public $important;
	public $index;
	public $inline;
	public $variable;
	public $currentFileInfo;

	public function __construct($name, $value = null, $important = null, $index = null, $currentFileInfo = null,  $inline = false){
		$this->name = $name;
		$this->value = ($value instanceof Less_Tree_Value) ? $value : new Less_Tree_Value(array($value));
		$this->important = $important ? ' ' . trim($important) : '';
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
		$this->inline = $inline;

		if ($name[0] === '@') {
			$this->variable = true;
		} else {
			$this->variable = false;
		}
	}

	/*
	function accept($visitor) {
		$visitor->visit( $this->value );
	}
	*/

	public function toCSS ($env){
		if ($this->variable) {
			return "";
		} else {
			try {
				return $this->name . ($env->compress ? ':' : ': ')
					. $this->value->toCSS($env)
					. $this->important . ($this->inline ? "" : ";");
			}catch( Exception $e ){
				$e->index = $this->index;
				$e->filename = $this->currentFileInfo['filename'];
				throw $e;
			}
		}
	}

	public function compile ($env){

		$return = null;
		$strictMathBypass = false;
		if( $this->name === "font" && !$env->strictMath ){
			$strictMathBypass = true;
			$env->strictMath = true;
		}
		try{
			$return = new Less_Tree_Rule($this->name,
										$this->value->compile($env),
										$this->important,
										$this->currentFileInfo,
										$this->index, $this->inline);
		}
		catch(Exception $e){}

		if( $strictMathBypass ){
			$env->strictMath = false;
		}

		return $return;
	}

	function makeImportant(){
		return new Less_Tree_Rule($this->name, $this->value, '!important', $this->index, $this->currentFileInfo, $this->inline);
	}

}
 

//less.js : /lib/less/tree/ruleset.js


class Less_Tree_Ruleset{
	//public $type = 'Ruleset';
	protected $lookups;
	private $_variables;
	private $_rulesets;

	public $strictImports;

	public $selectors;
	public $rules;
	public $root;
	public $firstRoot;
	public $allowImports;
	public $paths = array();

	public function __construct($selectors, $rules, $strictImports = false){
		$this->selectors = $selectors;
		$this->rules = $rules;
		$this->lookups = array();
		$this->strictImports = $strictImports;
	}

	function accept( $visitor ){
		//$visitor->visit($this->selectors);
		$visitor->visit($this->rules);
	}

	public function compile($env){

		$selectors = array();
		if( $this->selectors ){
			foreach($this->selectors as $s){
				if( Less_Parser::is_method($s,'compile') ){
					$selectors[] = $s->compile($env);
				}
			}
		}
		$ruleset = new Less_Tree_Ruleset($selectors, $this->rules, $this->strictImports);
		$rules = array();

		$ruleset->originalRuleset = $this;
		$ruleset->root = $this->root;
		$ruleset->firstRoot = $this->firstRoot;
		$ruleset->allowImports = $this->allowImports;

		// push the current ruleset to the frames stack
		$env->unshiftFrame($ruleset);

		// currrent selectors
		array_unshift($env->selectors,$this->selectors);


		// Evaluate imports
		if ($ruleset->root || $ruleset->allowImports || !$ruleset->strictImports) {
			$ruleset->evalImports($env);
		}


		// Store the frames around mixin definitions,
		// so they can be evaluated like closures when the time comes.
		foreach($ruleset->rules as $i => $rule) {
			if ($rule instanceof Less_Tree_MixinDefinition) {
				$ruleset->rules[$i]->frames = $env->frames;
			}
		}

		$mediaBlockCount = 0;
		if( $env instanceof Less_Environment ){
			$mediaBlockCount = count($env->mediaBlocks);
		}

		// Evaluate mixin calls.
		for($i=0; $i < count($ruleset->rules); $i++){
			$rule = $ruleset->rules[$i];
			if( $rule instanceof Less_Tree_MixinCall ){
				$rules = $rule->compile($env);

				$temp = array();
				foreach($rules as $r){
					if( ($r instanceof Less_Tree_Rule) && $r->variable ){
						// do not pollute the scope if the variable is
						// already there. consider returning false here
						// but we need a way to "return" variable from mixins
						if( !$ruleset->variable($r->name) ){
							$temp[] = $r;
						}
					}else{
						$temp[] = $r;
					}
				}
				$rules = $temp;
				array_splice($ruleset->rules, $i, 1, $rules);
				$i += count($rules)-1;
				$ruleset->resetCache();
            }
        }


		foreach($ruleset->rules as $i => $rule) {
			if(! ($rule instanceof Less_Tree_MixinDefinition) ){
				$ruleset->rules[$i] = Less_Parser::is_method($rule,'compile') ? $rule->compile($env) : $rule;
			}
		}


		// Pop the stack
		$env->shiftFrame();
		array_shift($env->selectors);

        if ($mediaBlockCount) {
			for($i = $mediaBlockCount; $i < count($env->mediaBlocks); $i++ ){
				$env->mediaBlocks[$i]->bubbleSelectors($selectors);
			}
        }

		return $ruleset;
	}

    function evalImports($env) {

		for($i=0; $i < count($this->rules); $i++){
			$rule = $this->rules[$i];

			if( $rule instanceof Less_Tree_Import  ){
				$rules = $rule->compile($env);
				if( is_array($rules) ){
					array_splice($this->rules, $i, 1, $rules);
				}else{
					array_splice($this->rules, $i, 1, array($rules));
				}
				if( count($rules) ){
					$i += count($rules)-1;
				}
				$this->resetCache();
			}
		}
    }

	static function makeImportant($selectors = null, $rules = null, $strictImports = false) {

		$important_rules = array();
		foreach($rules as $rule){
			if( Less_Parser::is_method($rule,'makeImportant') && property_exists($rule,'selectors') ){
				$important_rules[] = $rule->makeImportant($rule->selectors, $rule->rules, $strictImports);
			}elseif( Less_Parser::is_method($rule,'makeImportant') ){
				$important_rules[] = $rule->makeImportant();
			}else{
				$important_rules[] = $rule;
			}
		}

		return new Less_Tree_Ruleset($selectors, $important_rules, $strictImports );
	}

	public function matchArgs($args){
		return !is_array($args) || count($args) === 0;
	}

	function resetCache() {
		$this->_rulesets = null;
		$this->_variables = null;
		$this->lookups = array();
	}

	public function variables(){

		if( !$this->_variables ){
			$this->_variables = array();
			foreach( $this->rules as $r){
				if ($r instanceof Less_Tree_Rule && $r->variable === true) {
					$this->_variables[$r->name] = $r;
				}
			}
		}

		return $this->_variables;
	}

	public function variable($name){
		$vars = $this->variables();
		return isset($vars[$name]) ? $vars[$name] : null;
	}



	public function find( $selector, $self = null, $env = null){

		if( !$self ){
			$self = $this;
		}

		$key = $selector->toCSS($env);

		if( !array_key_exists($key, $this->lookups) ){
			$this->lookups[$key] = array();;


			foreach($this->rules as $rule){

				if( $rule == $self ){
					continue;
				}

				if( ($rule instanceof Less_Tree_Ruleset) || ($rule instanceof Less_Tree_MixinDefinition) ){

					foreach( $rule->selectors as $ruleSelector ){
						if( $selector->match($ruleSelector) ){

							if (count($selector->elements) > count($ruleSelector->elements)) {
								$this->lookups[$key] = array_merge($this->lookups[$key], $rule->find( new Less_Tree_Selector(array_slice($selector->elements, 1)), $self, $env));
							} else {
								$this->lookups[$key][] = $rule;
							}
							break;
						}
					}
				}
			}
		}

		return $this->lookups[$key];
	}

	//
	// Entry point for code generation
	//
	//	 `context` holds an array of arrays.
	//
	public function toCSS($env){
		$css = '';	  // The CSS output
		$rules = array();	// node.Rule instances
		$_rules = array();
		$rulesets = array(); // node.Ruleset instances


		// Compile rules and rulesets
		foreach($this->rules as $rule) {
			if (isset($rule->rules) || ($rule instanceof Less_Tree_Media)) {
				$rulesets[] = $rule->toCSS($env);

			} else if ( $rule instanceof Less_Tree_Directive ){
				$cssValue = $rule->toCSS($env);
                // Output only the first @charset definition as such - convert the others
                // to comments in case debug is enabled
                if ($rule->name === "@charset") {
                    // Only output the debug info together with subsequent @charset definitions
                    // a comment (or @media statement) before the actual @charset directive would
                    // be considered illegal css as it has to be on the first line
                    if ($env->charset) {
                        continue;
                    }
                    $env->charset = true;
                }
                $rulesets[] = $cssValue;

			} else if ($rule instanceof Less_Tree_Comment) {
				if (!$rule->silent) {
					if ($this->root) {
						$rulesets[] = $rule->toCSS($env);
					} else {
						$rules[] = $rule->toCSS($env);
					}
				}
			} else {
				if( Less_Parser::is_method($rule, 'toCSS') && (!isset($rule->variable) || !$rule->variable) ){
                    if( $this->firstRoot && $rule instanceof Less_Tree_Rule ){
						throw new Less_CompilerError("properties must be inside selector blocks, they cannot be in the root.");
                    }
					$rules[] = $rule->toCSS($env);
				} else if (isset($rule->value) && $rule->value && ! $rule->variable) {
					$rules[] = (string) $rule->value;
				}
			}
		}

		$rulesets = implode('', $rulesets);

		// If this is the root node, we don't render
		// a selector, or {}.
		// Otherwise, only output if this ruleset has rules.
		if ($this->root) {
			if( $env->compress ){
				$css .= rtrim(implode('', $rules),';');
			}else{
				$css .= implode("\n", $rules);
			}
		} else {
			if (count($rules)) {

				$selector = array();
				foreach($this->paths as $p){
					$_p = '';
					foreach($p as $s){
						$_p .= $s->toCSS($env);
					}
					$selector[] = trim($_p);
				}
				$css .= implode($env->compress ? ',' : ",\n", $selector);

				// Remove duplicates
				for ($i = count($rules) - 1; $i >= 0; $i--) {
					if( substr($rules[$i],0,2) === "/*" || !in_array($rules[$i], $_rules) ){
						array_unshift($_rules, $rules[$i]);
					}
				}
				$rules = $_rules;

				if( $env->compress ){
					$css .= '{'.rtrim(implode('',$rules),';').'}';
				}else{
					$css .= " {\n  ".implode("\n  ",$rules)."\n}\n";
				}
			}
		}
		$css .= $rulesets;

		return $css . ($env->compress ? "\n" : '' );
	}


	public function joinSelectors( &$paths, $context, $selectors ){
		if( is_array($selectors) ){
			foreach($selectors as $selector) {
				$this->joinSelector($paths, $context, $selector);
			}
		}
	}

	public function joinSelector (&$paths, $context, $selector){

		$hasParentSelector = false; $newSelectors; $el; $sel; $parentSel;
		$newSelectorPath; $afterParentJoin; $newJoinedSelector;
		$newJoinedSelectorEmpty; $lastSelector; $currentElements;
		$selectorsMultiplied;

		foreach($selector->elements as $el) {
			if( $el->value === '&') {
				$hasParentSelector = true;
			}
		}

		if( !$hasParentSelector ){
			if( count($context) > 0 ) {
				foreach($context as $context_el){
					$paths[] = array_merge($context_el, array($selector) );
				}
			}else {
				$paths[] = array($selector);
			}
			return;
		}


		// The paths are [[Selector]]
		// The first list is a list of comma seperated selectors
		// The inner list is a list of inheritance seperated selectors
		// e.g.
		// .a, .b {
		//   .c {
		//   }
		// }
		// == [[.a] [.c]] [[.b] [.c]]
		//

		// the elements from the current selector so far
		$currentElements = array();
		// the current list of new selectors to add to the path.
		// We will build it up. We initiate it with one empty selector as we "multiply" the new selectors
		// by the parents
		$newSelectors = array(array());


		foreach( $selector->elements as $el){

			// non parent reference elements just get added
			if( $el->value !== '&' ){
				$currentElements[] = $el;
			} else {
				// the new list of selectors to add
				$selectorsMultiplied = array();

				// merge the current list of non parent selector elements
				// on to the current list of selectors to add
				if( count($currentElements) > 0) {
					$this->mergeElementsOnToSelectors( $currentElements, $newSelectors);
				}

				// loop through our current selectors
				foreach($newSelectors as $sel){

					// if we don't have any parent paths, the & might be in a mixin so that it can be used
					// whether there are parents or not
					if( !count($context) ){
						// the combinator used on el should now be applied to the next element instead so that
						// it is not lost
						if( count($sel) > 0 ){
							$sel[0]->elements = array_slice($sel[0]->elements,0);
							$sel[0]->elements[] = new Less_Tree_Element($el->combinator, '', 0); //new Element(el.Combinator,  ""));
						}
						$selectorsMultiplied[] = $sel;
					}else {

						// and the parent selectors
						foreach($context as $parentSel){
							// We need to put the current selectors
							// then join the last selector's elements on to the parents selectors

							// our new selector path
							$newSelectorPath = array();
							// selectors from the parent after the join
							$afterParentJoin = array();
							$newJoinedSelectorEmpty = true;

							//construct the joined selector - if & is the first thing this will be empty,
							// if not newJoinedSelector will be the last set of elements in the selector
							if ( count($sel) > 0) {
								$newSelectorPath = $sel;
								$lastSelector = array_pop($newSelectorPath);
								$newJoinedSelector = new Less_Tree_Selector( $lastSelector->elements, $selector->extendList);
								$newJoinedSelectorEmpty = false;
							}
							else {
								$newJoinedSelector = new Less_Tree_Selector( array(), $selector->extendList);
							}

							//put together the parent selectors after the join
							if ( count($parentSel) > 1) {
								$afterParentJoin = array_merge($afterParentJoin, array_slice($parentSel,1) );
							}

							if ( count($parentSel) > 0) {
								$newJoinedSelectorEmpty = false;

								// join the elements so far with the first part of the parent
								$newJoinedSelector->elements[] = new Less_Tree_Element( $el->combinator, $parentSel[0]->elements[0]->value, 0 );

								$newJoinedSelector->elements = array_merge( $newJoinedSelector->elements, array_slice($parentSel[0]->elements, 1) );
							}

							if (!$newJoinedSelectorEmpty) {
								// now add the joined selector
								$newSelectorPath[] = $newJoinedSelector;
							}

							// and the rest of the parent
							$newSelectorPath = array_merge($newSelectorPath, $afterParentJoin);

							// add that to our new set of selectors
							$selectorsMultiplied[] = $newSelectorPath;
						}
					}
				}

				// our new selectors has been multiplied, so reset the state
				$newSelectors = $selectorsMultiplied;
				$currentElements = array();
			}
		}

		// if we have any elements left over (e.g. .a& .b == .b)
		// add them on to all the current selectors
		if( count($currentElements) > 0) {
			$this->mergeElementsOnToSelectors($currentElements, $newSelectors);
		}
		foreach( $newSelectors as $new_sel){
			if( count($new_sel) ){
				$paths[] = $new_sel;
			}
		}
	}

	function mergeElementsOnToSelectors( $elements, &$selectors){

		if( count($selectors) == 0) {
			$selectors[] = array( new Less_Tree_Selector($elements) );
			return;
		}


		foreach( $selectors as &$sel){

			// if the previous thing in sel is a parent this needs to join on to it
			if ( count($sel) > 0) {
				$last = count($sel)-1;
				$sel[$last] = new Less_Tree_Selector( array_merge( $sel[$last]->elements, $elements), $sel[$last]->extendList );
			}else{
				$sel[] = new Less_Tree_Selector( $elements );
			}
		}
	}
}
 

//less.js : lib/less/tree/selector.js


class Less_Tree_Selector {

	//public $type = 'Selector';
	public $elements;
	public $extendList = array();
	private $_css;

	public function __construct($elements, $extendList = array() ){
		$this->elements = $elements;
		$this->extendList = $extendList;
	}

	/*
	function accept($visitor) {
		$visitor->visit($this->elements);
		$visitor->visit($this->extendList);
	}
	*/

	public function match($other) {
		$len   = count($this->elements);

		$olen = $offset = 0;
		if( $other && count($other->elements) ){

			if( $other->elements[0]->value === "&" ){
				$offset = 1;
			}
			$olen = count($other->elements) - $offset;
		}

		$max = min($len, $olen);

		if( !$max ){
			return false;
		}

		for ($i = 0; $i < $max; $i ++) {
			if ($this->elements[$i]->value !== $other->elements[$i + $offset]->value) {
				return false;
			}
		}

		return true;
	}





	public function compile($env) {
		$extendList = array();

		for($i = 0, $len = count($this->extendList); $i < $len; $i++){
			$extendList[] = $this->extendList[$i]->compile($this->extendList[$i]);
		}

		$elements = array();
		for( $i = 0, $len = count($this->elements); $i < $len; $i++){
			$elements[] = $this->elements[$i]->compile($env);
		}

		return new Less_Tree_Selector($elements, $extendList);
	}

	public function toCSS ($env){

		if ($this->_css) {
			return $this->_css;
		}

		if (is_array($this->elements) && isset($this->elements[0]) &&
			$this->elements[0]->combinator instanceof Less_Tree_Combinator &&
			$this->elements[0]->combinator->value === '') {
				$this->_css = ' ';
		}else{
			$this->_css = '';
		}

		foreach($this->elements as $e){
			if( is_string($e) ){
				$this->_css .= ' ' . trim($e);
			}else{
				$this->_css .= $e->toCSS($env);
			}
		}

		return $this->_css;
	}

}
 


class Less_Tree_UnicodeDescriptor{
	//public $type = 'UnicodeDescriptor';
	public function __construct($value){
		$this->value = $value;
	}

	public function toCss($env){
		return $this->value;
	}

	public function compile($env){
		return $this;
	}
}

 


class Less_Tree_Unit{

	//public $type = 'Unit';
	var $numerator = array();
	var $denominator = array();

	function __construct($numerator = array(), $denominator = array(), $backupUnit = null ){
		$this->numerator = $numerator;
		$this->denominator = $denominator;
		$this->backupUnit = $backupUnit;
	}

	function __clone(){
	}

	function toCSS($env){

		if( count($this->numerator) ){
			return $this->numerator[0];
		}
		if( count($this->denominator) ){
			return $this->denominator[0];
		}
		if( (!$env || !$env->strictUnits) && $this->backupUnit ){
			return $this->backupUnit;
		}
		return "";
	}

	function toString(){
		$returnStr = implode('*',$this->numerator);
		foreach($this->denominator as $d){
			$returnStr .= '/'.$d;
		}
		return $returnStr;
	}

	function compare($other) {
		return $this->is( $other->toString() ) ? 0 : -1;
	}

	function is($unitString){
		return $this->toString() === $unitString;
	}

	function isAngle() {
		return isset( Less_Tree_UnitConversions::$angle[$this->toCSS()] );
	}

	function isEmpty(){
		return count($this->numerator) === 0 && count($this->denominator) === 0;
	}

	function isSingular() {
		return count($this->numerator) <= 1 && count($this->denominator) == 0;
	}


	function usedUnits(){
		$result = array();

		foreach(Less_Tree_UnitConversions::$groups as $groupName){
			$group = Less_Tree_UnitConversions::${$groupName};

			for($i=0; $i < count($this->numerator); $i++ ){
				$atomicUnit = $this->numerator[$i];
				if( isset($group[$atomicUnit]) && !isset($result[$groupName]) ){
					$result[$groupName] = $atomicUnit;
				}
			}

			for($i=0; $i < count($this->denominator); $i++ ){
				$atomicUnit = $this->denominator[$i];
				if( isset($group[$atomicUnit]) && !isset($result[$groupName]) ){
					$result[$groupName] = $atomicUnit;
				}
			}
		}

		return $result;
	}

	function cancel(){
		$counter = array();
		$backup = null;

		for( $i = 0; $i < count($this->numerator); $i++ ){
			$atomicUnit = $this->numerator[$i];
			if( !$backup ){
				$backup = $atomicUnit;
			}
			$counter[$atomicUnit] = ( isset($counter[$atomicUnit]) ? $counter[$atomicUnit] : 0) + 1;
		}

		for( $i = 0; $i < count($this->denominator); $i++ ){
			$atomicUnit = $this->denominator[$i];
			if( !$backup ){
				$backup = $atomicUnit;
			}
			$counter[$atomicUnit] = ( isset($counter[$atomicUnit]) ? $counter[$atomicUnit] : 0) - 1;
		}

		$this->numerator = array();
		$this->denominator = array();

		foreach($counter as $atomicUnit => $count){
			if( $count > 0 ){
				for( $i = 0; $i < $count; $i++ ){
					$this->numerator[] = $atomicUnit;
				}
			}elseif( $count < 0 ){
				for( $i = 0; $i < -$count; $i++ ){
					$this->denominator[] = $atomicUnit;
				}
			}
		}

		if( count($this->numerator) === 0 && count($this->denominator) === 0 && $backup ){
			$this->backupUnit = $backup;
		}

		sort($this->numerator);
		sort($this->denominator);
	}


}

 


class Less_Tree_UnitConversions{

	static $groups = array('length','duration','angle');

	static $length = array(
		'm'=> 1,
		'cm'=> 0.01,
		'mm'=> 0.001,
		'in'=> 0.0254,
		'pt'=> 0.000352778, // 0.0254 / 72,
		'pc'=> 0.004233333, //0.0254 / 72 * 12
		);

	static $duration = array(
		's'=> 1,
		'ms'=> 0.001
		);

	static $angle = array(
		'rad' => 0.1591549430919,	// 1/(2*M_PI),
		'deg' => 0.002777778, 		// 1/360,
		'grad'=> 0.0025,			// 1/400,
		'turn'=> 1
		);

} 


class Less_Tree_Url{
	//public $type = "Url";
	public $attrs;
	public $value;
	public $currentFileInfo;

	public function __construct($value, $currentFileInfo = null){
		$this->value = $value;
		$this->currentFileInfo = $currentFileInfo;
	}

	/*
	function accept( $visitor ){
		$visitor->visit($this->value);
	}
	*/

	public function toCSS(){
		return "url(" . $this->value->toCSS() . ")";
	}

	public function compile($ctx){
		$val = $this->value->compile($ctx);

		// Add the base path if the URL is relative
		if( $this->currentFileInfo && is_string($val->value) && $ctx->isPathRelative($val->value) ){
			$rootpath = $this->currentFileInfo['uri_root'];
			if ( !$val->quote ){
				$rootpath = preg_replace('/[\(\)\'"\s]/', '\\$1', $rootpath );
			}
			$val->value = $rootpath . $val->value;
		}


		return new Less_Tree_URL($val, null);
	}

}
 


class Less_Tree_Value{

	//public $type = 'Value';

	public function __construct($value){
		$this->value = $value;
		$this->is = 'value';
	}

	/*
	function accept($visitor) {
		$visitor->visit($this->value);
	}
	*/

	public function compile($env){

		if( count($this->value) == 1 ){
			return $this->value[0]->compile($env);
		}

		$ret = array();
		foreach($this->value as $v){
			$ret[] = $v->compile($env);
		}

		return new Less_Tree_Value($ret);
	}

	public function toCSS ($env){

		$ret = array();
		foreach($this->value as $e){
			$ret[] = $e->toCSS($env);
		}
		return implode($env->compress ? ',' : ', ', $ret);
	}
}
 


class Less_Tree_Variable {

	//public $type = 'Variable';
	public $name;
	public $index;
	public $currentFileInfo;
	private $evaluating = false;

    public function __construct($name, $index, $currentFileInfo = null) {
        $this->name = $name;
        $this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
    }

	public function compile($env) {
		$name = $this->name;
		if (strpos($name, '@@') === 0) {
			$v = new Less_Tree_Variable(substr($name, 1), $this->index + 1);
			$name = '@' . $v->compile($env)->value;
		}

		if ($this->evaluating) {
			throw new Less_CompilerException("Recursive variable definition for " . $name, $this->index, null, $this->currentFileInfo['file']);
		}

		$this->evaluating = true;


		foreach($env->frames as $frame){
			if( $v = $frame->variable($name) ){
				$this->evaluating = false;
				return $v->value->compile($env);
			}
		}

		throw new Less_CompilerException("variable " . $name . " is undefined", $this->index, null);
	}

}
 


class Less_extendFinderVisitor extends Less_visitor{

	public $contexts = array();
	public $allExtendsStack;
	public $foundExtends;

	public $visitRuleDeeper = false;
	public $visitMixinDefinitionDeeper = false;

	function __construct(){
		$this->contexts = array();
		$this->allExtendsStack = array(array());
	}

	function run($root) {
		$this->visit($root);
		$root->allExtends =& $this->allExtendsStack[0];
	}

	function visitRuleset($rulesetNode) {

		if( $rulesetNode->root ){
			return;
		}

		$allSelectorsExtendList = array();

		// get &:extend(.a); rules which apply to all selectors in this ruleset
		for( $i = 0; $i < count($rulesetNode->rules); $i++ ){
			if( $rulesetNode->rules[$i] instanceof Less_Tree_Extend ){
				$allSelectorsExtendList[] = $rulesetNode->rules[$i];
			}
		}

		// now find every selector and apply the extends that apply to all extends
		// and the ones which apply to an individual extend
		for($i = 0; $i < count($rulesetNode->paths); $i++ ){

			$selectorPath = $rulesetNode->paths[$i];
			$selector = end($selectorPath); //$selectorPath[ count($selectorPath)-1];


			$list = array_merge($selector->extendList, $allSelectorsExtendList);

			$extendList = array();
			foreach($list as $allSelectorsExtend){
				$extendList[] = clone $allSelectorsExtend;
			}

			for($j = 0; $j < count($extendList); $j++ ){
				$this->foundExtends = true;
				$extend = $extendList[$j];
				$extend->findSelfSelectors( $selectorPath );
				$extend->ruleset = $rulesetNode;
				if( $j === 0 ){ $extend->firstExtendOnThisSelectorPath = true; }

				$temp = count($this->allExtendsStack)-1;
				$this->allExtendsStack[ $temp ][] = $extend;
			}
		}

		$this->contexts[] = $rulesetNode->selectors;
	}

	function visitRulesetOut( $rulesetNode ){
		if( !$rulesetNode->root) {
			array_pop($this->contexts);
		}
	}

	function visitMedia( $mediaNode ){
		$mediaNode->allExtends = array();
		$this->allExtendsStack[] =& $mediaNode->allExtends;
	}

	function visitMediaOut( $mediaNode ){
		array_pop($this->allExtendsStack);
	}

	function visitDirective( $directiveNode ){
		$directiveNode->allExtends = array();
		$this->allExtendsStack[] =& $directiveNode->allExtends;
	}

	function visitDirectiveOut( $directiveNode ){
		array_pop($this->allExtendsStack);
	}
}


 

/*
class Less_importVisitor{

	public $_visitor;
	public $_importer;
	public $isReplacing = true;
	public $importCount;

	function __construct( $importer = null, $evalEnv = null ){
		$this->_visitor = new Less_visitor($this);
		$this->_importer = $importer;
		if( $evalEnv ){
			$this->env = $evalEnv;
		}else{
			$this->env = new Less_Environment();
		}
		$this->importCount = 0;
	}


	function run( $root ){
		// process the contents
		$this->_visitor->visit($root);

		$this->isFinished = true;

		//if( $this->importCount === 0) {
		//	$this->_finish();
		//}
	}

	function visitImport($importNode, &$visitArgs ){
		$importVisitor = $this;

		$visitArgs['visitDeeper'] = false;

		if( $importNode->css ){
			return $importNode;
		}

		$evaldImportNode = $importNode->compileForImport($this->env);

		if( $evaldImportNode && !$evaldImportNode->css ){
			$importNode = $evaldImportNode;
			$this->importCount++;
		}

		return $importNode;
	}


	function visitRule( $ruleNode, &$visitArgs ){
		$visitArgs['visitDeeper'] = false;
		return $ruleNode;
	}

	function visitDirective($directiveNode, $visitArgs){
		array_unshift($this->env->frames,$directiveNode);
		return $directiveNode;
	}

	function visitDirectiveOut($directiveNode) {
		array_shift($this->env->frames);
	}

	function visitMixinDefinition($mixinDefinitionNode, $visitArgs) {
		array_unshift($this->env->frames,$mixinDefinitionNode);
		return $mixinDefinitionNode;
	}

	function visitMixinDefinitionOut($mixinDefinitionNode) {
		array_shift($this->env->frames);
	}

	function visitRuleset($rulesetNode, $visitArgs) {
		array_unshift($this->env->frames,$rulesetNode);
		return $rulesetNode;
	}

	function visitRulesetOut($rulesetNode) {
		array_shift($this->env->frames);
	}

	function visitMedia($mediaNode, $visitArgs) {
		array_unshift($this->env->frames, $mediaNode->ruleset);
		return $mediaNode;
	}

	function visitMediaOut($mediaNode) {
		array_shift($this->env->frames);
	}

}
*/ 

class Less_joinSelectorVisitor extends Less_visitor{

	public $contexts = array( array() );

	public $visitRuleDeeper = false;
	public $visitMixinDefinition = false;


	function run( $root ){
		$this->visit($root);
	}

	function visitRuleset($rulesetNode) {
		$context = end($this->contexts); //$context = $this->contexts[ count($this->contexts) - 1];
		$paths = array();
		//$this->contexts[] = $paths;
		if( !$rulesetNode->root ){
			$rulesetNode->joinSelectors($paths, $context, $rulesetNode->selectors);
			$rulesetNode->paths = $paths;
		}

		//array_pop($this->contexts);
		$this->contexts[] = $paths;

	}

	function visitRulesetOut( $rulesetNode ){
		array_pop($this->contexts);
	}

	function visitMedia($mediaNode) {
		$context = end($this->contexts); //$context = $this->contexts[ count($this->contexts) - 1];
		$mediaNode->ruleset->root = ( count($context) === 0 || @$context[0]->multiMedia);
	}

}

 


class Less_processExtendsVisitor extends Less_visitor{

	public $allExtendsStack;

	public $visitRuleDeeper = false;
	public $visitMixinDefinitionDeeper = false;
	public $visitSelectorDeeper = false;



	function run( $root ){
		$extendFinder = new Less_extendFinderVisitor();
		$extendFinder->run( $root );
		if( !$extendFinder->foundExtends) { return $root; }

		$root->allExtends = $this->doExtendChaining( $root->allExtends, $root->allExtends);

		$this->allExtendsStack = array();
		$this->allExtendsStack[] = &$root->allExtends;

		$this->visit( $root );
	}

	function doExtendChaining( $extendsList, $extendsListTarget, $iterationCount = 0){
		//
		// chaining is different from normal extension.. if we extend an extend then we are not just copying, altering and pasting
		// the selector we would do normally, but we are also adding an extend with the same target selector
		// this means this new extend can then go and alter other extends
		//
		// this method deals with all the chaining work - without it, extend is flat and doesn't work on other extend selectors
		// this is also the most expensive.. and a match on one selector can cause an extension of a selector we had already processed if
		// we look at each selector at a time, as is done in visitRuleset

		$extendsToAdd = array();


		//loop through comparing every extend with every target extend.
		// a target extend is the one on the ruleset we are looking at copy/edit/pasting in place
		// e.g. .a:extend(.b) {} and .b:extend(.c) {} then the first extend extends the second one
		// and the second is the target.
		// the seperation into two lists allows us to process a subset of chains with a bigger set, as is the
		// case when processing media queries
		for( $extendIndex = 0, $extendsList_len = count($extendsList); $extendIndex < $extendsList_len; $extendIndex++ ){
			for( $targetExtendIndex = 0; $targetExtendIndex < count($extendsListTarget); $targetExtendIndex++ ){

				$extend = $extendsList[$extendIndex];
				$targetExtend = $extendsListTarget[$targetExtendIndex];

				// look for circular references
				if( $this->inInheritanceChain( $targetExtend, $extend)) {
					continue;
				}

				// find a match in the target extends self selector (the bit before :extend)
				$selectorPath = array( $targetExtend->selfSelectors[0] );
				$matches = $this->findMatch( $extend, $selectorPath);


				if( $matches ){

					// we found a match, so for each self selector..
					foreach($extend->selfSelectors as $selfSelector ){


						// process the extend as usual
						$newSelector = $this->extendSelector( $matches, $selectorPath, $selfSelector);

						// but now we create a new extend from it
						$newExtend = new Less_Tree_Extend( $targetExtend->selector, $targetExtend->option, 0);
						$newExtend->selfSelectors = $newSelector;

						// add the extend onto the list of extends for that selector
						end($newSelector)->extendList = array($newExtend);
						//$newSelector[ count($newSelector)-1]->extendList = array($newExtend);

						// record that we need to add it.
						$extendsToAdd[] = $newExtend;
						$newExtend->ruleset = $targetExtend->ruleset;

						//remember its parents for circular references
						$newExtend->parents = array($targetExtend, $extend);

						// only process the selector once.. if we have :extend(.a,.b) then multiple
						// extends will look at the same selector path, so when extending
						// we know that any others will be duplicates in terms of what is added to the css
						if( $targetExtend->firstExtendOnThisSelectorPath ){
							$newExtend->firstExtendOnThisSelectorPath = true;
							$targetExtend->ruleset->paths[] = $newSelector;
						}
					}
				}
			}
		}

		if( $extendsToAdd ){
			// try to detect circular references to stop a stack overflow.
			// may no longer be needed.			$this->extendChainCount++;
			if( $iterationCount > 100) {
				$selectorOne = "{unable to calculate}";
				$selectorTwo = "{unable to calculate}";
				try{
					$selectorOne = $extendsToAdd[0]->selfSelectors[0]->toCSS();
					$selectorTwo = $extendsToAdd[0]->selector->toCSS();
				}catch(Exception $e){}
				throw new Less_ParserException("extend circular reference detected. One of the circular extends is currently:"+$selectorOne+":extend(" + $selectorTwo+")");
			}

			// now process the new extends on the existing rules so that we can handle a extending b extending c ectending d extending e...
			$extendsToAdd = $this->doExtendChaining( $extendsToAdd, $extendsListTarget, $iterationCount+1);
		}

		return array_merge($extendsList, $extendsToAdd);
	}

	function inInheritanceChain( $possibleParent, $possibleChild ){

		if( $possibleParent === $possibleChild) {
			return true;
		}

		if( $possibleChild->parents ){
			if( $this->inInheritanceChain( $possibleParent, $possibleChild->parents[0]) ){
				return true;
			}
			if( $this->inInheritanceChain( $possibleParent, $possibleChild->parents[1]) ){
				return true;
			}
		}
		return false;
	}


	function visitRuleset($rulesetNode){


		if( $rulesetNode->root ){
			return;
		}

		$allExtends = end($this->allExtendsStack);
		$paths_len = count($rulesetNode->paths);

		// look at each selector path in the ruleset, find any extend matches and then copy, find and replace
		for( $extendIndex = 0, $all_extend_len = count($allExtends); $extendIndex < $all_extend_len; $extendIndex++ ){
			for($pathIndex = 0; $pathIndex < $paths_len; $pathIndex++ ){

				$selectorPath = $rulesetNode->paths[$pathIndex];

				// extending extends happens initially, before the main pass
				if( end($selectorPath)->extendList ){ continue; }

				$matches = $this->findMatch($allExtends[$extendIndex], $selectorPath);

				if( $matches ){
					foreach($allExtends[$extendIndex]->selfSelectors as $selfSelector ){
						$rulesetNode->paths[] = $this->extendSelector($matches, $selectorPath, $selfSelector);
					}
				}
			}
		}
	}

	function findMatch($extend, $haystackSelectorPath ){

		//
		// look through the haystack selector path to try and find the needle - extend.selector
		// returns an array of selector matches that can then be replaced
		//
		$needleElements = $extend->selector->elements;
		$needleElements_len = count($needleElements);
		$potentialMatches = array();
		$potentialMatches_len = 0;
		$potentialMatch = null;
		$matches = array();

		// loop through the haystack elements
		for($haystackSelectorIndex = 0, $haystack_path_len = count($haystackSelectorPath); $haystackSelectorIndex < $haystack_path_len; $haystackSelectorIndex++ ){
			$hackstackSelector = $haystackSelectorPath[$haystackSelectorIndex];

			for($hackstackElementIndex = 0, $haystack_elements_len = count($hackstackSelector->elements); $hackstackElementIndex < $haystack_elements_len; $hackstackElementIndex++ ){

				$haystackElement = $hackstackSelector->elements[$hackstackElementIndex];

				// if we allow elements before our match we can add a potential match every time. otherwise only at the first element.
				if( $extend->allowBefore || ($haystackSelectorIndex == 0 && $hackstackElementIndex == 0) ){
					$potentialMatches[] = array('pathIndex'=> $haystackSelectorIndex, 'index'=> $hackstackElementIndex, 'matched'=> 0, 'initialCombinator'=> $haystackElement->combinator);
					$potentialMatches_len++;
				}

				for($i = 0; $i < $potentialMatches_len; $i++ ){
					$potentialMatch = &$potentialMatches[$i];

					// selectors add " " onto the first element. When we use & it joins the selectors together, but if we don't
					// then each selector in haystackSelectorPath has a space before it added in the toCSS phase. so we need to work out
					// what the resulting combinator will be
					$targetCombinator = $haystackElement->combinator->value;
					if( $targetCombinator == '' && $hackstackElementIndex === 0 ){
						$targetCombinator = ' ';
					}

					// if we don't match, null our match to indicate failure
					if( !$this->isElementValuesEqual( $needleElements[$potentialMatch['matched'] ]->value, $haystackElement->value) ||
						($potentialMatch['matched'] > 0 && $needleElements[ $potentialMatch['matched'] ]->combinator->value !== $targetCombinator) ){
						$potentialMatch = null;
					} else {
						$potentialMatch['matched']++;
					}

					// if we are still valid and have finished, test whether we have elements after and whether these are allowed
					if( $potentialMatch ){
						$potentialMatch['finished'] = ($potentialMatch['matched'] === $needleElements_len );

						if( $potentialMatch['finished'] &&
							(!$extend->allowAfter && ($hackstackElementIndex+1 < $haystack_elements_len || $haystackSelectorIndex+1 < $haystack_path_len)) ){
							$potentialMatch = null;
						}
					}
					// if null we remove, if not, we are still valid, so either push as a valid match or continue
					if( $potentialMatch ){
						if( $potentialMatch['finished'] ){
							$potentialMatch['length'] = $needleElements_len;
							$potentialMatch['endPathIndex'] = $haystackSelectorIndex;
							$potentialMatch['endPathElementIndex'] = $hackstackElementIndex + 1; // index after end of match
							$potentialMatches = array(); // we don't allow matches to overlap, so start matching again
							$potentialMatches_len = 0;
							$matches[] = $potentialMatch;
						}
					} else {
						array_splice($potentialMatches, $i, 1);
						$potentialMatches_len--;
						$i--;
					}
				}
			}
		}
		return $matches;
	}

	function isElementValuesEqual( $elementValue1, $elementValue2 ){

		if( is_string($elementValue1) || is_string($elementValue2) ) {
			return $elementValue1 === $elementValue2;
		}

		if( $elementValue1 instanceof Less_Tree_Attribute ){

			if( $elementValue1->op !== $elementValue2->op || $elementValue1->key !== $elementValue2->key ){
				return false;
			}

			if( !$elementValue1->value || !$elementValue2->value ){
				if( $elementValue1->value || $elementValue2->value ) {
					return false;
				}
				return true;
			}
			$elementValue1 = ($elementValue1->value->value ? $elementValue1->value->value : $elementValue1->value );
			$elementValue2 = ($elementValue2->value->value ? $elementValue2->value->value : $elementValue2->value );
			return $elementValue1 === $elementValue2;
		}
		return false;
	}

	function extendSelector($matches, $selectorPath, $replacementSelector){

		//for a set of matches, replace each match with the replacement selector

		$currentSelectorPathIndex = 0;
		$currentSelectorPathElementIndex = 0;
		$path = array();
		$selectorPath_len = count($selectorPath);

		for($matchIndex = 0, $matches_len = count($matches); $matchIndex < $matches_len; $matchIndex++ ){


			$match = $matches[$matchIndex];
			$selector = $selectorPath[ $match['pathIndex'] ];
			$firstElement = new Less_Tree_Element(
				$match['initialCombinator'],
				$replacementSelector->elements[0]->value,
				$replacementSelector->elements[0]->index
			);

			if( $match['pathIndex'] > $currentSelectorPathIndex && $currentSelectorPathElementIndex > 0 ){
				$last_path = end($path);
				$last_path->elements = array_merge( $last_path->elements, array_slice( $selectorPath[$currentSelectorPathIndex]->elements, $currentSelectorPathElementIndex));
				$currentSelectorPathElementIndex = 0;
				$currentSelectorPathIndex++;
			}

			$slice_len = $match['pathIndex'] - $currentSelectorPathIndex;
			$temp = array_slice($selectorPath, $currentSelectorPathIndex, $slice_len);
			$path = array_merge( $path, $temp);

			$slice_len = $match['index'] - $currentSelectorPathElementIndex;
			$new_elements = array_slice($selector->elements,$currentSelectorPathElementIndex, $slice_len);
			$new_elements = array_merge($new_elements, array($firstElement) );
			$new_elements = array_merge($new_elements, array_slice($replacementSelector->elements,1) );
			$path[] = new Less_Tree_Selector( $new_elements );

			$currentSelectorPathIndex = $match['endPathIndex'];
			$currentSelectorPathElementIndex = $match['endPathElementIndex'];
			if( $currentSelectorPathElementIndex >= count($selector->elements) ){
				$currentSelectorPathElementIndex = 0;
				$currentSelectorPathIndex++;
			}
		}

		if( $currentSelectorPathIndex < $selectorPath_len && $currentSelectorPathElementIndex > 0 ){
			$last_path = end($path);
			$last_path->elements = array_merge( $last_path->elements, array_slice($selectorPath[$currentSelectorPathIndex]->elements, $currentSelectorPathElementIndex));
			$currentSelectorPathElementIndex = 0;
			$currentSelectorPathIndex++;
		}

		$slice_len = $selectorPath_len - $currentSelectorPathIndex;
		$path = array_merge($path, array_slice($selectorPath, $currentSelectorPathIndex, $slice_len));

		return $path;
	}


	function visitMedia( $mediaNode ){
		$newAllExtends = array_merge( $mediaNode->allExtends, end($this->allExtendsStack) );
		$this->allExtendsStack[] = $this->doExtendChaining($newAllExtends, $mediaNode->allExtends);
	}

	function visitMediaOut( $mediaNode ){
		array_pop( $this->allExtendsStack );
	}

	function visitDirective( $directiveNode ){
		$newAllExtends = array_merge( $directiveNode->allExtends, end($this->allExtendsStack) );
		$this->allExtendsStack[] = $this->doExtendChaining($newAllExtends, $directiveNode->allExtends);
	}

	function visitDirectiveOut( $directiveNode ){
		array_pop($this->allExtendsStack);
	}

} 

class Less_visitor{

	function visit($nodes){

		if( !is_array($nodes) ){
			$nodes = array($nodes);
		}

		foreach($nodes as $node){

			if( !is_object($node) ){
				continue;
			}

			$class = get_class($node);
			$funcName = 'visit'.substr($class,10); //remove 'Less_Tree_' from the class name

			if( method_exists($this,$funcName) ){
				$this->$funcName( $node );
			}

			$deeper_property = $funcName.'Deeper';
			if( !isset($this->$deeper_property) && Less_Parser::is_method($node,'accept') ){
				$node->accept($this);
			}

			$funcName = $funcName . "Out";
			if( method_exists($this,$funcName) ){
				$this->$funcName( $node );
			}
		}
	}

}

 


class Less_CompilerException extends Exception {

	private $filename;

	public function __construct($message = null, $code = 0, Exception $previous = null, $filename = null) {
		parent::__construct($message, $code, $previous);
		$this->filename = $filename;
	}

	public function getFilename() {
		return $this->filename;
	}

	public function __toString() {
		return $this->message . " (" . $this->filename . ")";
	}
}
 


class Less_ParserException extends Exception{

} 