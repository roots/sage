<?php
/*
Plugin Name: Less PHP Compiler
Plugin URI: http://shoestrap.org/
Description: This plugin adds the less.php class and makes it available to other plugins and themes.
Version: 1.6.3
Author: Aristeides Stathopoulos
Author URI: http://wpmu.io
*/

/**
 * This is a simple plugin that loads the Less.php class and makes it available to other plugins and themes.
 * When activated this plugin will not do anything.
 * It has no functionality on its own, but can be used as a dependency for other plugins & themes.
 *
 * Some users had issues when all classes were different files so we are including everything in this file.
 */

/**
 * Release numbers
 *
 * @package Less
 * @subpackage version
 */
class Less_Version{

	const version = '1.7.0.1';			// The current build number of less.php
	const less_version = '1.7';			// The less.js version that this build should be compatible with
	const cache_version = '170';		// The parser cache version

}

/**
 * Utility for handling the generation and caching of css files
 *
 * @package Less
 * @subpackage cache
 *
 */
class Less_Cache{

	public static $cache_dir = false;		// directory less.php can use for storing data


	/**
	 * Save and reuse the results of compiled less files.
	 * The first call to Get() will generate css and save it.
	 * Subsequent calls to Get() with the same arguments will return the same css filename
	 *
	 * @param array $less_files Array of .less files to compile
	 * @param array $parser_options Array of compiler options
	 * @param boolean $use_cache Set to false to regenerate the css file
	 * @return string Name of the css file
	 */
	public static function Get( $less_files, $parser_options = array(), $use_cache = true ){


		//check $cache_dir
		if( isset($parser_options['cache_dir']) ){
			Less_Cache::$cache_dir = $parser_options['cache_dir'];
		}

		if( empty(Less_Cache::$cache_dir) ){
			throw new Exception('cache_dir not set');
		}

		self::CheckCacheDir();

		// generate name for compiled css file
		$less_files = (array)$less_files;
		$hash = md5(json_encode($less_files));
		$list_file = Less_Cache::$cache_dir.'lessphp_'.$hash.'.list';


		if( $use_cache === true ){

			// check cached content
			if( file_exists($list_file) ){


				$list = explode("\n",file_get_contents($list_file));
				$compiled_name = self::CompiledName($list);
				$compiled_file = Less_Cache::$cache_dir.$compiled_name;
				if( file_exists($compiled_file) ){
					@touch($list_file);
					@touch($compiled_file);
					return $compiled_name;
				}
			}

		}

		$compiled = self::Cache( $less_files, $parser_options );
		if( !$compiled ){
			return false;
		}


		//save the file list
		$cache = implode("\n",$less_files);
		file_put_contents( $list_file, $cache );


		//save the css
		$compiled_name = self::CompiledName( $less_files );
		file_put_contents( Less_Cache::$cache_dir.$compiled_name, $compiled );


		//clean up
		self::CleanCache();

		return $compiled_name;
	}

	/**
	 * Force the compiler to regenerate the cached css file
	 *
	 * @param array $less_files Array of .less files to compile
	 * @param array $parser_options Array of compiler options
	 * @return string Name of the css file
	 */
	public static function Regen( $less_files, $parser_options = array() ){
		return self::Get( $less_files, $parser_options, false );
	}

	public static function Cache( &$less_files, $parser_options = array() ){


		// get less.php if it exists
		$file = dirname(__FILE__) . '/Less.php';
		if( file_exists($file) && !class_exists('Less_Parser') ){
			require_once($file);
		}

		$parser_options['cache_dir'] = Less_Cache::$cache_dir;
		$parser = new Less_Parser($parser_options);


		// combine files
		foreach($less_files as $file_path => $uri_or_less ){

			//treat as less markup if there are newline characters
			if( strpos($uri_or_less,"\n") !== false ){
				$parser->Parse( $uri_or_less );
				continue;
			}

			$parser->ParseFile( $file_path, $uri_or_less );
		}

		$compiled = $parser->getCss();


		$less_files = $parser->allParsedFiles();

		return $compiled;
	}


	private static function CompiledName( $files ){

		//save the file list
		$temp = array(Less_Version::cache_version);
		foreach($files as $file){
			$temp[] = filemtime($file)."\t".filesize($file)."\t".$file;
		}

		return 'lessphp_'.sha1(json_encode($temp)).'.css';
	}


	public static function SetCacheDir( $dir ){
		Less_Cache::$cache_dir = $dir;
	}

	public static function CheckCacheDir(){

		Less_Cache::$cache_dir = str_replace('\\','/',Less_Cache::$cache_dir);
		Less_Cache::$cache_dir = rtrim(Less_Cache::$cache_dir,'/').'/';

		if( !file_exists(Less_Cache::$cache_dir) ){
			if( !mkdir(Less_Cache::$cache_dir) ){
				throw new Less_Exception_Parser('Less.php cache directory couldn\'t be created: '.Less_Cache::$cache_dir);
			}

		}elseif( !is_dir(Less_Cache::$cache_dir) ){
			throw new Less_Exception_Parser('Less.php cache directory doesn\'t exist: '.Less_Cache::$cache_dir);

		}elseif( !is_writable(Less_Cache::$cache_dir) ){
			throw new Less_Exception_Parser('Less.php cache directory isn\'t writable: '.Less_Cache::$cache_dir);

		}

	}


	public static function CleanCache(){
		static $clean = false;

		if( $clean ){
			return;
		}

		$files = scandir(Less_Cache::$cache_dir);
		if( $files ){
			$check_time = time() - 604800;
			foreach($files as $file){
				if( strpos($file,'lessphp_') !== 0 ){
					continue;
				}
				$full_path = Less_Cache::$cache_dir.'/'.$file;
				if( filemtime($full_path) > $check_time ){
					continue;
				}
				unlink($full_path);
			}
		}

		$clean = true;
	}

}

/**
 * Class for parsing and compiling less files into css
 *
 * @package Less
 * @subpackage parser
 *
 */
class Less_Parser{


	/**
	 * Default parser options
	 */
	public static $default_options = array(
		'compress'				=> false,			// option - whether to compress
		'strictUnits'			=> false,			// whether units need to evaluate correctly
		'strictMath'			=> false,			// whether math has to be within parenthesis
		'relativeUrls'			=> true,			// option - whether to adjust URL's to be relative
		'urlArgs'				=> array(),			// whether to add args into url tokens
		'numPrecision'			=> 8,

		'import_dirs'			=> array(),
		'import_callback'		=> null,
		'cache_dir'				=> null,
		'cache_method'			=> 'php', 			//false, 'serialize', 'php', 'var_export';

		'sourceMap'				=> false,			// whether to output a source map
		'sourceMapBasepath'		=> null,
		'sourceMapWriteTo'		=> null,
		'sourceMapURL'			=> null,

		'plugins'				=> array(),

	);

	public static $options = array();


	private $input;					// Less input string
	private $input_len;				// input string length
	private $pos;					// current index in `input`
	private $saveStack = array();	// holds state for backtracking
	private $furthest;

	/**
	 * @var Less_Environment
	 */
	private $env;

	private $rules = array();

	private static $imports = array();

	public static $has_extends = false;

	public static $next_id = 0;

	/**
	 * Filename to contents of all parsed the files
	 *
	 * @var array
	 */
	public static $contentsMap = array();


	/**
	 * @param Less_Environment|array|null $env
	 */
	public function __construct( $env = null ){

		// Top parser on an import tree must be sure there is one "env"
		// which will then be passed around by reference.
		if( $env instanceof Less_Environment ){
			$this->env = $env;
		}else{
			$this->SetOptions(Less_Parser::$default_options);
			$this->Reset( $env );
		}

	}


	/**
	 * Reset the parser state completely
	 *
	 */
	public function Reset( $options = null ){
		$this->rules = array();
		self::$imports = array();
		self::$has_extends = false;
		self::$imports = array();
		self::$contentsMap = array();

		$this->env = new Less_Environment($options);
		$this->env->Init();

		//set new options
		if( is_array($options) ){
			$this->SetOptions(Less_Parser::$default_options);
			$this->SetOptions($options);
		}
	}

	/**
	 * Set one or more compiler options
	 *  options: import_dirs, cache_dir, cache_method
	 *
	 */
	public function SetOptions( $options ){
		foreach($options as $option => $value){
			$this->SetOption($option,$value);
		}
	}

	/**
	 * Set one compiler option
	 *
	 */
	public function SetOption($option,$value){

		switch($option){

			case 'import_dirs':
				$this->SetImportDirs($value);
			return;

			case 'cache_dir':
				if( is_string($value) ){
					Less_Cache::SetCacheDir($value);
					Less_Cache::CheckCacheDir();
				}
			return;
		}

		Less_Parser::$options[$option] = $value;
	}




	/**
	 * Get the current css buffer
	 *
	 * @return string
	 */
	public function getCss(){

		$precision = ini_get('precision');
		@ini_set('precision',16);
		$locale = setlocale(LC_NUMERIC, 0);
		setlocale(LC_NUMERIC, "C");


		$root = new Less_Tree_Ruleset(array(), $this->rules );
		$root->root = true;
		$root->firstRoot = true;


		$this->PreVisitors($root);

		self::$has_extends = false;
		$evaldRoot = $root->compile($this->env);



		$this->PostVisitors($evaldRoot);

		if( Less_Parser::$options['sourceMap'] ){
			$generator = new Less_SourceMap_Generator($evaldRoot, Less_Parser::$contentsMap, Less_Parser::$options );
			// will also save file
			// FIXME: should happen somewhere else?
			$css = $generator->generateCSS();
		}else{
			$css = $evaldRoot->toCSS();
		}

		if( Less_Parser::$options['compress'] ){
			$css = preg_replace('/(^(\s)+)|((\s)+$)/', '', $css);
		}

		//reset php settings
		@ini_set('precision',$precision);
		setlocale(LC_NUMERIC, $locale);

		return $css;
	}

	/**
	 * Run pre-compile visitors
	 *
	 */
	private function PreVisitors($root){

		if( Less_Parser::$options['plugins'] ){
			foreach(Less_Parser::$options['plugins'] as $plugin){
				if( !empty($plugin->isPreEvalVisitor) ){
					$plugin->run($root);
				}
			}
		}
	}


	/**
	 * Run post-compile visitors
	 *
	 */
	private function PostVisitors($evaldRoot){

		$visitors = array();
		$visitors[] = new Less_Visitor_joinSelector();
		if( self::$has_extends ){
			$visitors[] = new Less_Visitor_processExtends();
		}
		$visitors[] = new Less_Visitor_toCSS();


		if( Less_Parser::$options['plugins'] ){
			foreach(Less_Parser::$options['plugins'] as $plugin){
				if( property_exists($plugin,'isPreEvalVisitor') && $plugin->isPreEvalVisitor ){
					continue;
				}

				if( property_exists($plugin,'isPreVisitor') && $plugin->isPreVisitor ){
					array_unshift( $visitors, $plugin);
				}else{
					$visitors[] = $plugin;
				}
			}
		}


		for($i = 0; $i < count($visitors); $i++ ){
			$visitors[$i]->run($evaldRoot);
		}

	}


	/**
	 * Parse a Less string into css
	 *
	 * @param string $str The string to convert
	 * @param string $uri_root The url of the file
	 * @return Less_Tree_Ruleset|Less_Parser
	 */
	public function parse( $str, $file_uri = null ){

		if( !$file_uri ){
			$uri_root = '';
			$filename = 'anonymous-file-'.Less_Parser::$next_id++.'.less';
		}else{
			$file_uri = self::WinPath($file_uri);
			$filename = basename($file_uri);
			$uri_root = dirname($file_uri);
		}

		$previousFileInfo = $this->env->currentFileInfo;
		$uri_root = self::WinPath($uri_root);
		$this->SetFileInfo($filename, $uri_root);

		$this->input = $str;
		$this->_parse();

		if( $previousFileInfo ){
			$this->env->currentFileInfo = $previousFileInfo;
		}

		return $this;
	}


	/**
	 * Parse a Less string from a given file
	 *
	 * @throws Less_Exception_Parser
	 * @param string $filename The file to parse
	 * @param string $uri_root The url of the file
	 * @param bool $returnRoot Indicates whether the return value should be a css string a root node
	 * @return Less_Tree_Ruleset|Less_Parser
	 */
	public function parseFile( $filename, $uri_root = '', $returnRoot = false){

		if( !file_exists($filename) ){
			$this->Error(sprintf('File `%s` not found.', $filename));
		}


		// fix uri_root?
		// Instead of The mixture of file path for the first argument and directory path for the second argument has bee
		if( !$returnRoot && !empty($uri_root) && basename($uri_root) == basename($filename) ){
			$uri_root = dirname($uri_root);
		}


		$previousFileInfo = $this->env->currentFileInfo;
		$filename = self::WinPath($filename);
		$uri_root = self::WinPath($uri_root);
		$this->SetFileInfo($filename, $uri_root);

		self::AddParsedFile($filename);

		if( $returnRoot ){
			$rules = $this->GetRules( $filename );
			$return = new Less_Tree_Ruleset(array(), $rules );
		}else{
			$this->_parse( $filename );
			$return = $this;
		}

		if( $previousFileInfo ){
			$this->env->currentFileInfo = $previousFileInfo;
		}

		return $return;
	}


	/**
	 * Allows a user to set variables values
	 * @param array $vars
	 * @return Less_Parser
	 */
	public function ModifyVars( $vars ){

		$this->input = $this->serializeVars( $vars );
		$this->_parse();

		return $this;
	}


	/**
	 * @param string $filename
	 */
	public function SetFileInfo( $filename, $uri_root = ''){

		$filename = Less_Environment::normalizePath($filename);
		$dirname = preg_replace('/[^\/\\\\]*$/','',$filename);

		if( !empty($uri_root) ){
			$uri_root = rtrim($uri_root,'/').'/';
		}

		$currentFileInfo = array();

		//entry info
		if( isset($this->env->currentFileInfo) ){
			$currentFileInfo['entryPath'] = $this->env->currentFileInfo['entryPath'];
			$currentFileInfo['entryUri'] = $this->env->currentFileInfo['entryUri'];
			$currentFileInfo['rootpath'] = $this->env->currentFileInfo['rootpath'];

		}else{
			$currentFileInfo['entryPath'] = $dirname;
			$currentFileInfo['entryUri'] = $uri_root;
			$currentFileInfo['rootpath'] = $dirname;
		}

		$currentFileInfo['currentDirectory'] = $dirname;
		$currentFileInfo['currentUri'] = $uri_root.basename($filename);
		$currentFileInfo['filename'] = $filename;
		$currentFileInfo['uri_root'] = $uri_root;


		//inherit reference
		if( isset($this->env->currentFileInfo['reference']) && $this->env->currentFileInfo['reference'] ){
			$currentFileInfo['reference'] = true;
		}

		$this->env->currentFileInfo = $currentFileInfo;
	}


	/**
	 * @deprecated 1.5.1.2
	 *
	 */
	public function SetCacheDir( $dir ){

		if( !file_exists($dir) ){
			if( mkdir($dir) ){
				return true;
			}
			throw new Less_Exception_Parser('Less.php cache directory couldn\'t be created: '.$dir);

		}elseif( !is_dir($dir) ){
			throw new Less_Exception_Parser('Less.php cache directory doesn\'t exist: '.$dir);

		}elseif( !is_writable($dir) ){
			throw new Less_Exception_Parser('Less.php cache directory isn\'t writable: '.$dir);

		}else{
			$dir = self::WinPath($dir);
			Less_Cache::$cache_dir = rtrim($dir,'/').'/';
			return true;
		}
	}


	/**
	 * Set a list of directories or callbacks the parser should use for determining import paths
	 *
	 * @param array $dirs
	 */
	public function SetImportDirs( $dirs ){
		Less_Parser::$options['import_dirs'] = array();

		foreach($dirs as $path => $uri_root){

			$path = self::WinPath($path);
			if( !empty($path) ){
				$path = rtrim($path,'/').'/';
			}

			if ( !is_callable($uri_root) ){
				$uri_root = self::WinPath($uri_root);
				if( !empty($uri_root) ){
					$uri_root = rtrim($uri_root,'/').'/';
				}
			}

			Less_Parser::$options['import_dirs'][$path] = $uri_root;
		}
	}

	/**
	 * @param string $file_path
	 */
	private function _parse( $file_path = null ){
		$this->rules = array_merge($this->rules, $this->GetRules( $file_path ));
	}


	/**
	 * Return the results of parsePrimary for $file_path
	 * Use cache and save cached results if possible
	 *
	 * @param string|null $file_path
	 */
	private function GetRules( $file_path ){

		$this->SetInput($file_path);

		$cache_file = $this->CacheFile( $file_path );
		if( $cache_file && file_exists($cache_file) ){
			switch(Less_Parser::$options['cache_method']){

				// Using serialize
				// Faster but uses more memory
				case 'serialize':
					$cache = unserialize(file_get_contents($cache_file));
					if( $cache ){
						touch($cache_file);
						$this->UnsetInput();
						return $cache;
					}
				break;


				// Using generated php code
				case 'var_export':
				case 'php':
				$this->UnsetInput();
				return include($cache_file);
			}
		}

		$rules = $this->parsePrimary();

		if( $this->pos < $this->input_len ){
			throw new Less_Exception_Chunk($this->input, null, $this->furthest, $this->env->currentFileInfo);
		}

		$this->UnsetInput();


		//save the cache
		if( $cache_file ){

			//msg('write cache file');
			switch(Less_Parser::$options['cache_method']){
				case 'serialize':
					file_put_contents( $cache_file, serialize($rules) );
				break;
				case 'php':
					file_put_contents( $cache_file, '<?php return '.self::ArgString($rules).'; ?>' );
				break;
				case 'var_export':
					//Requires __set_state()
					file_put_contents( $cache_file, '<?php return '.var_export($rules,true).'; ?>' );
				break;
			}

			Less_Cache::CleanCache();
		}

		return $rules;
	}


	/**
	 * Set up the input buffer
	 *
	 */
	public function SetInput( $file_path ){

		if( $file_path ){
			$this->input = file_get_contents( $file_path );
		}

		$this->pos = $this->furthest = 0;

		// Remove potential UTF Byte Order Mark
		$this->input = preg_replace('/\\G\xEF\xBB\xBF/', '', $this->input);
		$this->input_len = strlen($this->input);


		if( Less_Parser::$options['sourceMap'] && $this->env->currentFileInfo ){
			$uri = $this->env->currentFileInfo['currentUri'];
			Less_Parser::$contentsMap[$uri] = $this->input;
		}

	}


	/**
	 * Free up some memory
	 *
	 */
	public function UnsetInput(){
		unset($this->input, $this->pos, $this->input_len, $this->furthest);
		$this->saveStack = array();
	}


	public function CacheFile( $file_path ){

		if( $file_path && Less_Parser::$options['cache_method'] && Less_Cache::$cache_dir ){

			$env = get_object_vars($this->env);
			unset($env['frames']);

			$parts = array();
			$parts[] = $file_path;
			$parts[] = filesize( $file_path );
			$parts[] = filemtime( $file_path );
			$parts[] = $env;
			$parts[] = Less_Version::cache_version;
			$parts[] = Less_Parser::$options['cache_method'];
			return Less_Cache::$cache_dir.'lessphp_'.base_convert( sha1(json_encode($parts) ), 16, 36).'.lesscache';
		}
	}


	static function AddParsedFile($file){
		self::$imports[] = $file;
	}

	static function AllParsedFiles(){
		return self::$imports;
	}

	/**
	 * @param string $file
	 */
	static function FileParsed($file){
		return in_array($file,self::$imports);
	}


	function save() {
		$this->saveStack[] = $this->pos;
	}

	private function restore() {
		$this->pos = array_pop($this->saveStack);
	}

	private function forget(){
		array_pop($this->saveStack);
	}


	private function isWhitespace($offset = 0) {
		return preg_match('/\s/',$this->input[ $this->pos + $offset]);
	}

	/**
	 * Parse from a token, regexp or string, and move forward if match
	 *
	 * @param array $toks
	 * @return array
	 */
	private function match($toks){

		// The match is confirmed, add the match length to `this::pos`,
		// and consume any extra white-space characters (' ' || '\n')
		// which come after that. The reason for this is that LeSS's
		// grammar is mostly white-space insensitive.
		//

		foreach($toks as $tok){

			$char = $tok[0];

			if( $char === '/' ){
				$match = $this->MatchReg($tok);

				if( $match ){
					return count($match) === 1 ? $match[0] : $match;
				}

			}elseif( $char === '#' ){
				$match = $this->MatchChar($tok[1]);

			}else{
				// Non-terminal, match using a function call
				$match = $this->$tok();

			}

			if( $match ){
				return $match;
			}
		}
	}

	/**
	 * @param string[] $toks
	 *
	 * @return string
	 */
	private function MatchFuncs($toks){

		foreach($toks as $tok){
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
			return $match;
		}
	}


	/**
	 * Same as match(), but don't change the state of the parser,
	 * just return the match.
	 *
	 * @param string $tok
	 * @return integer
	 */
	public function PeekReg($tok){
		return preg_match($tok, $this->input, $match, 0, $this->pos);
	}

	/**
	 * @param string $tok
	 */
	public function PeekChar($tok){
		//return ($this->input[$this->pos] === $tok );
		return ($this->pos < $this->input_len) && ($this->input[$this->pos] === $tok );
	}


	/**
	 * @param integer $length
	 */
	public function skipWhitespace($length){

		$this->pos += $length;

		for(; $this->pos < $this->input_len; $this->pos++ ){
			$c = $this->input[$this->pos];

			if( ($c !== "\n") && ($c !== "\r") && ($c !== "\t") && ($c !== ' ') ){
				break;
			}
		}
	}


	/**
	 * @param string $tok
	 * @param string|null $msg
	 */
	public function expect($tok, $msg = NULL) {
		$result = $this->match( array($tok) );
		if (!$result) {
			$this->Error( $msg	? "Expected '" . $tok . "' got '" . $this->input[$this->pos] . "'" : $msg );
		} else {
			return $result;
		}
	}

	/**
	 * @param string $tok
	 */
	public function expectChar($tok, $msg = null ){
		$result = $this->MatchChar($tok);
		if( !$result ){
			$this->Error( $msg ? "Expected '" . $tok . "' got '" . $this->input[$this->pos] . "'" : $msg );
		}else{
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
	//	.class {
	//	  color: #fff;
	//	  border: 1px solid #000;
	//	  width: @w + 4px;
	//	  > .child {...}
	//	}
	//
	// And here's what the parse tree might look like:
	//
	//	 Ruleset (Selector '.class', [
	//		 Rule ("color",  Value ([Expression [Color #fff]]))
	//		 Rule ("border", Value ([Expression [Dimension 1px][Keyword "solid"][Color #000]]))
	//		 Rule ("width",  Value ([Expression [Operation "+" [Variable "@w"][Dimension 4px]]]))
	//		 Ruleset (Selector [Element '>', '.child'], [...])
	//	 ])
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
	//	 primary  →  (ruleset | rule)+
	//	 ruleset  →  selector+ block
	//	 block	→  '{' primary '}'
	//
	// Only at one point is the primary rule not called from the
	// block rule: at the root level.
	//
	private function parsePrimary(){
		$root = array();

		while( true ){

			if( $this->pos >= $this->input_len ){
				break;
			}

			$node = $this->parseExtend(true);
			if( $node ){
				$root = array_merge($root,$node);
				continue;
			}

			//$node = $this->MatchFuncs( array( 'parseMixinDefinition', 'parseRule', 'parseRuleset', 'parseMixinCall', 'parseComment', 'parseDirective'));
			$node = $this->MatchFuncs( array( 'parseMixinDefinition', 'parseNameValue', 'parseRule', 'parseRuleset', 'parseMixinCall', 'parseComment', 'parseRulesetCall', 'parseDirective'));

			if( $node ){
				$root[] = $node;
			}elseif( !$this->MatchReg('/\\G[\s\n;]+/') ){
				break;
			}

			if( $this->PeekChar('}') ){
				break;
			}
		}

		return $root;
	}



	// We create a Comment node for CSS comments `/* */`,
	// but keep the LeSS comments `//` silent, by just skipping
	// over them.
	private function parseComment(){

		if( $this->input[$this->pos] !== '/' ){
			return;
		}

		if( $this->input[$this->pos+1] === '/' ){
			$match = $this->MatchReg('/\\G\/\/.*/');
			return $this->NewObj4('Less_Tree_Comment',array($match[0], true, $this->pos, $this->env->currentFileInfo));
		}

		//$comment = $this->MatchReg('/\\G\/\*(?:[^*]|\*+[^\/*])*\*+\/\n?/');
		$comment = $this->MatchReg('/\\G\/\*(?s).*?\*+\/\n?/');//not the same as less.js to prevent fatal errors
		if( $comment ){
			return $this->NewObj4('Less_Tree_Comment',array($comment[0], false, $this->pos, $this->env->currentFileInfo));
		}
	}

	private function parseComments(){
		$comments = array();

		while( $this->pos < $this->input_len ){
			$comment = $this->parseComment();
			if( !$comment ){
				break;
			}

			$comments[] = $comment;
		}

		return $comments;
	}



	//
	// A string, which supports escaping " and '
	//
	//	 "milky way" 'he\'s the one!'
	//
	private function parseEntitiesQuoted() {
		$j = $this->pos;
		$e = false;
		$index = $this->pos;

		if( $this->input[$this->pos] === '~' ){
			$j++;
			$e = true; // Escaped strings
		}

		if( $this->input[$j] != '"' && $this->input[$j] !== "'" ){
			return;
		}

		if ($e) {
			$this->MatchChar('~');
		}
		$str = $this->MatchReg('/\\G"((?:[^"\\\\\r\n]|\\\\.)*)"|\'((?:[^\'\\\\\r\n]|\\\\.)*)\'/');
		if( $str ){
			$result = $str[0][0] == '"' ? $str[1] : $str[2];
			return $this->NewObj5('Less_Tree_Quoted',array($str[0], $result, $e, $index, $this->env->currentFileInfo) );
		}
		return;
	}


	//
	// A catch-all word, such as:
	//
	//	 black border-collapse
	//
	private function parseEntitiesKeyword(){

		//$k = $this->MatchReg('/\\G[_A-Za-z-][_A-Za-z0-9-]*/');
		$k = $this->MatchReg('/\\G%|\\G[_A-Za-z-][_A-Za-z0-9-]*/');
		if( $k ){
			$k = $k[0];
			$color = $this->fromKeyword($k);
			if( $color ){
				return $color;
			}
			return $this->NewObj1('Less_Tree_Keyword',$k);
		}
	}

	// duplicate of Less_Tree_Color::FromKeyword
	private function FromKeyword( $keyword ){
		$keyword = strtolower($keyword);

		if( Less_Colors::hasOwnProperty($keyword) ){
			// detect named color
			return $this->NewObj1('Less_Tree_Color',substr(Less_Colors::color($keyword), 1));
		}

		if( $keyword === 'transparent' ){
			return $this->NewObj3('Less_Tree_Color', array( array(0, 0, 0), 0, true));
		}
	}

	//
	// A function call
	//
	//	 rgb(255, 0, 255)
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
		}

		$this->pos += strlen($name);

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
			return $this->NewObj4('Less_Tree_Call',array($name, $args, $index, $this->env->currentFileInfo) );
		}
	}

	/**
	 * Parse a list of arguments
	 *
	 * @return array
	 */
	private function parseEntitiesArguments(){

		$args = array();
		while( true ){
			$arg = $this->MatchFuncs( array('parseEntitiesAssignment','parseExpression') );
			if( !$arg ){
				break;
			}

			$args[] = $arg;
			if( !$this->MatchChar(',') ){
				break;
			}
		}
		return $args;
	}

	private function parseEntitiesLiteral(){
		return $this->MatchFuncs( array('parseEntitiesDimension','parseEntitiesColor','parseEntitiesQuoted','parseUnicodeDescriptor') );
	}

	// Assignments are argument entities for calls.
	// They are present in ie filter properties as shown below.
	//
	//	 filter: progid:DXImageTransform.Microsoft.Alpha( *opacity=50* )
	//
	private function parseEntitiesAssignment() {

		$key = $this->MatchReg('/\\G\w+(?=\s?=)/');
		if( !$key ){
			return;
		}

		if( !$this->MatchChar('=') ){
			return;
		}

		$value = $this->parseEntity();
		if( $value ){
			return $this->NewObj2('Less_Tree_Assignment',array($key[0], $value));
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


		if( $this->input[$this->pos] !== 'u' || !$this->matchReg('/\\Gurl\(/') ){
			return;
		}

		$value = $this->match( array('parseEntitiesQuoted','parseEntitiesVariable','/\\Gdata\:.*?[^\)]+/','/\\G(?:(?:\\\\[\(\)\'"])|[^\(\)\'"])+/') );
		if( !$value ){
			$value = '';
		}


		$this->expectChar(')');


		if( isset($value->value) || $value instanceof Less_Tree_Variable ){
			return $this->NewObj2('Less_Tree_Url',array($value, $this->env->currentFileInfo));
		}

		return $this->NewObj2('Less_Tree_Url', array( $this->NewObj1('Less_Tree_Anonymous',$value), $this->env->currentFileInfo) );
	}


	//
	// A Variable entity, such as `@fink`, in
	//
	//	 width: @fink + 2px
	//
	// We use a different parser for variable definitions,
	// see `parsers.variable`.
	//
	private function parseEntitiesVariable(){
		$index = $this->pos;
		if ($this->PeekChar('@') && ($name = $this->MatchReg('/\\G@@?[\w-]+/'))) {
			return $this->NewObj3('Less_Tree_Variable', array( $name[0], $index, $this->env->currentFileInfo));
		}
	}


	// A variable entity useing the protective {} e.g. @{var}
	private function parseEntitiesVariableCurly() {
		$index = $this->pos;

		if( $this->input_len > ($this->pos+1) && $this->input[$this->pos] === '@' && ($curly = $this->MatchReg('/\\G@\{([\w-]+)\}/')) ){
			return $this->NewObj3('Less_Tree_Variable',array('@'.$curly[1], $index, $this->env->currentFileInfo));
		}
	}

	//
	// A Hexadecimal color
	//
	//	 #4F3C2F
	//
	// `rgb` and `hsl` colors are parsed through the `entities.call` parser.
	//
	private function parseEntitiesColor(){
		if ($this->PeekChar('#') && ($rgb = $this->MatchReg('/\\G#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/'))) {
			return $this->NewObj1('Less_Tree_Color',$rgb[1]);
		}
	}

	//
	// A Dimension, that is, a number and a unit
	//
	//	 0.5em 95%
	//
	private function parseEntitiesDimension(){

		$c = @ord($this->input[$this->pos]);

		//Is the first char of the dimension 0-9, '.', '+' or '-'
		if (($c > 57 || $c < 43) || $c === 47 || $c == 44){
			return;
		}

		$value = $this->MatchReg('/\\G([+-]?\d*\.?\d+)(%|[a-z]+)?/');
		if( $value ){

			if( isset($value[2]) ){
				return $this->NewObj2('Less_Tree_Dimension', array($value[1],$value[2]));
			}
			return $this->NewObj1('Less_Tree_Dimension',$value[1]);
		}
	}


	//
	// A unicode descriptor, as is used in unicode-range
	//
	// U+0?? or U+00A1-00A9
	//
	function parseUnicodeDescriptor() {
		$ud = $this->MatchReg('/\\G(U\+[0-9a-fA-F?]+)(\-[0-9a-fA-F?]+)?/');
		if( $ud ){
			return $this->NewObj1('Less_Tree_UnicodeDescriptor', $ud[0]);
		}
	}


	//
	// JavaScript code to be evaluated
	//
	//	 `window.location.href`
	//
	private function parseEntitiesJavascript(){
		$e = false;
		$j = $this->pos;
		if( $this->input[$j] === '~' ){
			$j++;
			$e = true;
		}
		if( $this->input[$j] !== '`' ){
			return;
		}
		if( $e ){
			$this->MatchChar('~');
		}
		$str = $this->MatchReg('/\\G`([^`]*)`/');
		if( $str ){
			return $this->NewObj3('Less_Tree_Javascript', array($str[1], $this->pos, $e));
		}
	}


	//
	// The variable part of a variable definition. Used in the `rule` parser
	//
	//	 @fink:
	//
	private function parseVariable(){
		if ($this->PeekChar('@') && ($name = $this->MatchReg('/\\G(@[\w-]+)\s*:/'))) {
			return $name[1];
		}
	}


	//
	// The variable part of a variable definition. Used in the `rule` parser
	//
	// @fink();
	//
	private function parseRulesetCall(){

		if( $this->input[$this->pos] === '@' && ($name = $this->MatchReg('/\\G(@[\w-]+)\s*\(\s*\)\s*;/')) ){
			return $this->NewObj1('Less_Tree_RulesetCall', $name[1] );
		}
	}


	//
	// extend syntax - used to extend selectors
	//
	function parseExtend($isRule = false){

		$index = $this->pos;
		$extendList = array();


		if( !$this->MatchReg( $isRule ? '/\\G&:extend\(/' : '/\\G:extend\(/' ) ){ return; }

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

			$extendList[] = $this->NewObj3('Less_Tree_Extend', array( $this->NewObj1('Less_Tree_Selector',$elements), $option, $index ));

		}while( $this->MatchChar(",") );

		$this->expect('/\\G\)/');

		if( $isRule ){
			$this->expect('/\\G;/');
		}

		return $extendList;
	}


	//
	// A Mixin call, with an optional argument list
	//
	//	 #mixins > .square(#fff);
	//	 .rounded(4px, black);
	//	 .button;
	//
	// The `while` loop is there because mixins can be
	// namespaced, but we only support the child and descendant
	// selector for now.
	//
	private function parseMixinCall(){

		$char = $this->input[$this->pos];
		if( $char !== '.' && $char !== '#' ){
			return;
		}

		$index = $this->pos;
		$this->save(); // stop us absorbing part of an invalid selector

		$elements = $this->parseMixinCallElements();

		if( $elements ){

			if( $this->MatchChar('(') ){
				$returned = $this->parseMixinArgs(true);
				$args = $returned['args'];
				$this->expectChar(')');
			}else{
				$args = array();
			}

			$important = $this->parseImportant();

			if( $this->parseEnd() ){
				$this->forget();
				return $this->NewObj5('Less_Tree_Mixin_Call', array( $elements, $args, $index, $this->env->currentFileInfo, $important));
			}
		}

		$this->restore();
	}


	private function parseMixinCallElements(){
		$elements = array();
		$c = null;

		while( true ){
			$elemIndex = $this->pos;
			$e = $this->MatchReg('/\\G[#.](?:[\w-]|\\\\(?:[A-Fa-f0-9]{1,6} ?|[^A-Fa-f0-9]))+/');
			if( !$e ){
				break;
			}
			$elements[] = $this->NewObj4('Less_Tree_Element', array($c, $e[0], $elemIndex, $this->env->currentFileInfo));
			$c = $this->MatchChar('>');
		}

		return $elements;
	}



	/**
	 * @param boolean $isCall
	 */
	private function parseMixinArgs( $isCall ){
		$expressions = array();
		$argsSemiColon = array();
		$isSemiColonSeperated = null;
		$argsComma = array();
		$expressionContainsNamed = null;
		$name = null;
		$returner = array('args'=>array(), 'variadic'=> false);

		$this->save();

		while( true ){
			if( $isCall ){
				$arg = $this->MatchFuncs( array( 'parseDetachedRuleset','parseExpression' ) );
			} else {
				$this->parseComments();
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
				$arg = $this->MatchFuncs( array('parseEntitiesVariable','parseEntitiesLiteral','parseEntitiesKeyword') );
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
				if( property_exists($arg,'value') && count($arg->value) == 1 ){
					$val = $arg->value[0];
				}
			} else {
				$val = $arg;
			}


			if( $val instanceof Less_Tree_Variable ){

				if( $this->MatchChar(':') ){
					if( $expressions ){
						if( $isSemiColonSeperated ){
							$this->Error('Cannot mix ; and , as delimiter types');
						}
						$expressionContainsNamed = true;
					}

					// we do not support setting a ruleset as a default variable - it doesn't make sense
					// However if we do want to add it, there is nothing blocking it, just don't error
					// and remove isCall dependency below
					$value = null;
					if( $isCall ){
						$value = $this->parseDetachedRuleset();
					}
					if( !$value ){
						$value = $this->parseExpression();
					}

					if( !$value ){
						if( $isCall ){
							$this->Error('could not understand value for named argument');
						} else {
							$this->restore();
							$returner['args'] = array();
							return $returner;
						}
					}

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
					$this->Error('Cannot mix ; and , as delimiter types');
				}

				$isSemiColonSeperated = true;

				if( count($expressions) > 1 ){
					$value = $this->NewObj1('Less_Tree_Value', $expressions);
				}
				$argsSemiColon[] = array('name'=>$name, 'value'=>$value );

				$name = null;
				$expressions = array();
				$expressionContainsNamed = false;
			}
		}

		$this->forget();
		$returner['args'] = ($isSemiColonSeperated ? $argsSemiColon : $argsComma);
		return $returner;
	}



	//
	// A Mixin definition, with a list of parameters
	//
	//	 .rounded (@radius: 2px, @color) {
	//		...
	//	 }
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
		$cond = null;

		$char = $this->input[$this->pos];
		if( ($char !== '.' && $char !== '#') || ($char === '{' && $this->PeekReg('/\\G[^{]*\}/')) ){
			return;
		}

		$this->save();

		$match = $this->MatchReg('/\\G([#.](?:[\w-]|\\\(?:[A-Fa-f0-9]{1,6} ?|[^A-Fa-f0-9]))+)\s*\(/');
		if( $match ){
			$name = $match[1];

			$argInfo = $this->parseMixinArgs( false );
			$params = $argInfo['args'];
			$variadic = $argInfo['variadic'];


			// .mixincall("@{a}");
			// looks a bit like a mixin definition..
			// also
			// .mixincall(@a: {rule: set;});
			// so we have to be nice and restore
			if( !$this->MatchChar(')') ){
				$this->furthest = $this->pos;
				$this->restore();
				return;
			}


			$this->parseComments();

			if ($this->MatchReg('/\\Gwhen/')) { // Guard
				$cond = $this->expect('parseConditions', 'Expected conditions');
			}

			$ruleset = $this->parseBlock();

			if( is_array($ruleset) ){
				$this->forget();
				return $this->NewObj5('Less_Tree_Mixin_Definition', array( $name, $params, $ruleset, $cond, $variadic));
			}

			$this->restore();
		}else{
			$this->forget();
		}
	}

	//
	// Entities are the smallest recognized token,
	// and can be found inside a rule's value.
	//
	private function parseEntity(){

		return $this->MatchFuncs( array('parseEntitiesLiteral','parseEntitiesVariable','parseEntitiesUrl','parseEntitiesCall','parseEntitiesKeyword','parseEntitiesJavascript','parseComment') );
	}

	//
	// A Rule terminator. Note that we use `peek()` to check for '}',
	// because the `block` rule will be expecting it, but we still need to make sure
	// it's there, if ';' was ommitted.
	//
	private function parseEnd(){
		return $this->MatchChar(';') || $this->PeekChar('}');
	}

	//
	// IE's alpha function
	//
	//	 alpha(opacity=88)
	//
	private function parseAlpha(){

		if ( ! $this->MatchReg('/\\G\(opacity=/i')) {
			return;
		}

		$value = $this->MatchReg('/\\G[0-9]+/');
		if( $value ){
			$value = $value[0];
		}else{
			$value = $this->parseEntitiesVariable();
			if( !$value ){
				return;
			}
		}

		$this->expectChar(')');
		return $this->NewObj1('Less_Tree_Alpha',$value);
	}


	//
	// A Selector Element
	//
	//	 div
	//	 + h1
	//	 #socks
	//	 input[type="text"]
	//
	// Elements are the building blocks for Selectors,
	// they are made out of a `Combinator` (see combinator rule),
	// and an element name, such as a tag a class, or `*`.
	//
	private function parseElement(){
		$c = $this->parseCombinator();
		$index = $this->pos;

		$e = $this->match( array('/\\G(?:\d+\.\d+|\d+)%/', '/\\G(?:[.#]?|:*)(?:[\w-]|[^\x00-\x9f]|\\\\(?:[A-Fa-f0-9]{1,6} ?|[^A-Fa-f0-9]))+/',
			'#*', '#&', 'parseAttribute', '/\\G\([^()@]+\)/', '/\\G[\.#](?=@)/', 'parseEntitiesVariableCurly') );

		if( is_null($e) ){
			$this->save();
			if( $this->MatchChar('(') ){
				if( ($v = $this->parseSelector()) && $this->MatchChar(')') ){
					$e = $this->NewObj1('Less_Tree_Paren',$v);
					$this->forget();
				}else{
					$this->restore();
				}
			}else{
				$this->forget();
			}
		}

		if( !is_null($e) ){
			return $this->NewObj4('Less_Tree_Element',array( $c, $e, $index, $this->env->currentFileInfo));
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
	private function parseCombinator(){
		$c = $this->input[$this->pos];
		if ($c === '>' || $c === '+' || $c === '~' || $c === '|' || $c === '^' ){

			$this->pos++;
			if( $this->input[$this->pos] === '^' ){
				$c = '^^';
				$this->pos++;
			}

			$this->skipWhitespace(0);

			return $c;
		}

		if( $this->pos > 0 && $this->isWhitespace(-1) ){
			return ' ';
		}
	}

	//
	// A CSS selector (see selector below)
	// with less extensions e.g. the ability to extend and guard
	//
	private function parseLessSelector(){
		return $this->parseSelector(true);
	}

	//
	// A CSS Selector
	//
	//	 .class > div + h1
	//	 li a:hover
	//
	// Selectors are made out of one or more Elements, see above.
	//
	private function parseSelector( $isLess = false ){
		$elements = array();
		$extendList = array();
		$condition = null;
		$when = false;
		$extend = false;
		$e = null;
		$c = null;
		$index = $this->pos;

		while( ($isLess && ($extend = $this->parseExtend())) || ($isLess && ($when = $this->MatchReg('/\\Gwhen/') )) || ($e = $this->parseElement()) ){
			if( $when ){
				$condition = $this->expect('parseConditions', 'expected condition');
			}elseif( $condition ){
				//error("CSS guard can only be used at the end of selector");
			}elseif( $extend ){
				$extendList = array_merge($extendList,$extend);
			}else{
				//if( count($extendList) ){
					//error("Extend can only be used at the end of selector");
				//}
				$c = $this->input[ $this->pos ];
				$elements[] = $e;
				$e = null;
			}

			if( $c === '{' || $c === '}' || $c === ';' || $c === ',' || $c === ')') { break; }
		}

		if( $elements ){
			return $this->NewObj5('Less_Tree_Selector',array($elements, $extendList, $condition, $index, $this->env->currentFileInfo));
		}
		if( $extendList ) {
			$this->Error('Extend must be used to extend a selector, it cannot be used on its own');
		}
	}

	private function parseTag(){
		return ( $tag = $this->MatchReg('/\\G[A-Za-z][A-Za-z-]*[0-9]?/') ) ? $tag : $this->MatchChar('*');
	}

	private function parseAttribute(){

		$val = null;

		if( !$this->MatchChar('[') ){
			return;
		}

		$key = $this->parseEntitiesVariableCurly();
		if( !$key ){
			$key = $this->expect('/\\G(?:[_A-Za-z0-9-\*]*\|)?(?:[_A-Za-z0-9-]|\\\\.)+/');
		}

		$op = $this->MatchReg('/\\G[|~*$^]?=/');
		if( $op ){
			$val = $this->match( array('parseEntitiesQuoted','/\\G[0-9]+%/','/\\G[\w-]+/','parseEntitiesVariableCurly') );
		}

		$this->expectChar(']');

		return $this->NewObj3('Less_Tree_Attribute',array( $key, $op[0], $val));
	}

	//
	// The `block` rule is used by `ruleset` and `mixin.definition`.
	// It's a wrapper around the `primary` rule, with added `{}`.
	//
	private function parseBlock(){
		if( $this->MatchChar('{') ){
			$content = $this->parsePrimary();
			if( $this->MatchChar('}') ){
				return $content;
			}
		}
	}

	private function parseBlockRuleset(){
		$block = $this->parseBlock();

		if( $block ){
			$block = $this->NewObj2('Less_Tree_Ruleset',array( null, $block));
		}

		return $block;
	}

	private function parseDetachedRuleset(){
		$blockRuleset = $this->parseBlockRuleset();
		if( $blockRuleset ){
			return $this->NewObj1('Less_Tree_DetachedRuleset',$blockRuleset);
		}
	}

	//
	// div, .class, body > p {...}
	//
	private function parseRuleset(){
		$selectors = array();

		$this->save();

		while( true ){
			$s = $this->parseLessSelector();
			if( !$s ){
				break;
			}
			$selectors[] = $s;
			$this->parseComments();

			if( $s->condition && count($selectors) > 1 ){
				$this->Error('Guards are only currently allowed on a single selector.');
			}

			if( !$this->MatchChar(',') ){
				break;
			}
			if( $s->condition ){
				$this->Error('Guards are only currently allowed on a single selector.');
			}
			$this->parseComments();
		}


		if( $selectors ){
			$rules = $this->parseBlock();
			if( is_array($rules) ){
				$this->forget();
				return $this->NewObj2('Less_Tree_Ruleset',array( $selectors, $rules)); //Less_Environment::$strictImports
			}
		}

		// Backtrack
		$this->furthest = $this->pos;
		$this->restore();
	}

	/**
	 * Custom less.php parse function for finding simple name-value css pairs
	 * ex: width:100px;
	 *
	 */
	private function parseNameValue(){

		$index = $this->pos;
		$this->save();


		//$match = $this->MatchReg('/\\G([a-zA-Z\-]+)\s*:\s*((?:\'")?[a-zA-Z0-9\-% \.,!]+?(?:\'")?)\s*([;}])/');
		$match = $this->MatchReg('/\\G([a-zA-Z\-]+)\s*:\s*([\'"]?[#a-zA-Z0-9\-%\.,]+?[\'"]?) *(! *important)?\s*([;}])/');
		if( $match ){

			if( $match[4] == '}' ){
				$this->pos = $index + strlen($match[0])-1;
			}

			if( $match[3] ){
				$match[2] .= ' !important';
			}

			return $this->NewObj4('Less_Tree_NameValue',array( $match[1], $match[2], $index, $this->env->currentFileInfo));
		}

		$this->restore();
	}


	private function parseRule( $tryAnonymous = null ){

		$merge = false;
		$startOfRule = $this->pos;

		$c = $this->input[$this->pos];
		if( $c === '.' || $c === '#' || $c === '&' ){
			return;
		}

		$this->save();
		$name = $this->MatchFuncs( array('parseVariable','parseRuleProperty'));

		if( $name ){

			$isVariable = is_string($name);

			$value = null;
			if( $isVariable ){
				$value = $this->parseDetachedRuleset();
			}

			$important = null;
			if( !$value ){

				// prefer to try to parse first if its a variable or we are compressing
				// but always fallback on the other one
				//if( !$tryAnonymous && is_string($name) && $name[0] === '@' ){
				if( !$tryAnonymous && (Less_Parser::$options['compress'] || $isVariable) ){
					$value = $this->MatchFuncs( array('parseValue','parseAnonymousValue'));
				}else{
					$value = $this->MatchFuncs( array('parseAnonymousValue','parseValue'));
				}

				$important = $this->parseImportant();

				// a name returned by this.ruleProperty() is always an array of the form:
				// [string-1, ..., string-n, ""] or [string-1, ..., string-n, "+"]
				// where each item is a tree.Keyword or tree.Variable
				if( !$isVariable && is_array($name) ){
					$nm = array_pop($name);
					if( $nm->value ){
						$merge = $nm->value;
					}
				}
			}


			if( $value && $this->parseEnd() ){
				$this->forget();
				return $this->NewObj6('Less_Tree_Rule',array( $name, $value, $important, $merge, $startOfRule, $this->env->currentFileInfo));
			}else{
				$this->furthest = $this->pos;
				$this->restore();
				if( $value && !$tryAnonymous ){
					return $this->parseRule(true);
				}
			}
		}else{
			$this->forget();
		}
	}

	function parseAnonymousValue(){

		if( preg_match('/\\G([^@+\/\'"*`(;{}-]*);/',$this->input, $match, 0, $this->pos) ){
			$this->pos += strlen($match[1]);
			return $this->NewObj1('Less_Tree_Anonymous',$match[1]);
		}
	}

	//
	// An @import directive
	//
	//	 @import "lib";
	//
	// Depending on our environment, importing is done differently:
	// In the browser, it's an XHR request, in Node, it would be a
	// file-system operation. The function used for importing is
	// stored in `import`, which we pass to the Import constructor.
	//
	private function parseImport(){

		$this->save();

		$dir = $this->MatchReg('/\\G@import?\s+/');

		if( $dir ){
			$options = $this->parseImportOptions();
			$path = $this->MatchFuncs( array('parseEntitiesQuoted','parseEntitiesUrl'));

			if( $path ){
				$features = $this->parseMediaFeatures();
				if( $this->MatchChar(';') ){
					if( $features ){
						$features = $this->NewObj1('Less_Tree_Value',$features);
					}

					$this->forget();
					return $this->NewObj5('Less_Tree_Import',array( $path, $features, $options, $this->pos, $this->env->currentFileInfo));
				}
			}
		}

		$this->restore();
	}

	private function parseImportOptions(){

		$options = array();

		// list of options, surrounded by parens
		if( !$this->MatchChar('(') ){
			return $options;
		}
		do{
			$optionName = $this->parseImportOption();
			if( $optionName ){
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
		}while( $optionName );
		$this->expectChar(')');
		return $options;
	}

	private function parseImportOption(){
		$opt = $this->MatchReg('/\\G(less|css|multiple|once|inline|reference)/');
		if( $opt ){
			return $opt[1];
		}
	}

	private function parseMediaFeature() {
		$nodes = array();

		do{
			$e = $this->MatchFuncs(array('parseEntitiesKeyword','parseEntitiesVariable'));
			if( $e ){
				$nodes[] = $e;
			} elseif ($this->MatchChar('(')) {
				$p = $this->parseProperty();
				$e = $this->parseValue();
				if ($this->MatchChar(')')) {
					if ($p && $e) {
						$r = $this->NewObj7('Less_Tree_Rule', array( $p, $e, null, null, $this->pos, $this->env->currentFileInfo, true));
						$nodes[] = $this->NewObj1('Less_Tree_Paren',$r);
					} elseif ($e) {
						$nodes[] = $this->NewObj1('Less_Tree_Paren',$e);
					} else {
						return null;
					}
				} else
					return null;
			}
		} while ($e);

		if ($nodes) {
			return $this->NewObj1('Less_Tree_Expression',$nodes);
		}
	}

	private function parseMediaFeatures() {
		$features = array();

		do{
			$e = $this->parseMediaFeature();
			if( $e ){
				$features[] = $e;
				if (!$this->MatchChar(',')) break;
			}else{
				$e = $this->parseEntitiesVariable();
				if( $e ){
					$features[] = $e;
					if (!$this->MatchChar(',')) break;
				}
			}
		} while ($e);

		return $features ? $features : null;
	}

	private function parseMedia() {
		if( $this->MatchReg('/\\G@media/') ){
			$features = $this->parseMediaFeatures();
			$rules = $this->parseBlock();

			if( is_array($rules) ){
				return $this->NewObj4('Less_Tree_Media',array( $rules, $features, $this->pos, $this->env->currentFileInfo));
			}
		}
	}


	//
	// A CSS Directive
	//
	// @charset "utf-8";
	//
	private function parseDirective(){

		if( !$this->PeekChar('@') ){
			return;
		}

		$rules = null;
		$index = $this->pos;
		$hasBlock = true;
		$hasIdentifier = false;
		$hasExpression = false;
		$hasUnknown = false;


		$value = $this->MatchFuncs(array('parseImport','parseMedia'));
		if( $value ){
			return $value;
		}

		$this->save();

		$name = $this->MatchReg('/\\G@[a-z-]+/');

		if( !$name ) return;
		$name = $name[0];


		$nonVendorSpecificName = $name;
		$pos = strpos($name,'-', 2);
		if( $name[1] == '-' && $pos > 0 ){
			$nonVendorSpecificName = "@" . substr($name, $pos + 1);
		}


		switch( $nonVendorSpecificName ){
			/*
			case "@font-face":
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
			hasBlock = true;
			break;
			*/
			case "@charset":
				$hasIdentifier = true;
				$hasBlock = false;
				break;
			case "@namespace":
				$hasExpression = true;
				$hasBlock = false;
				break;
			case "@keyframes":
				$hasIdentifier = true;
				break;
			case "@host":
			case "@page":
			case "@document":
			case "@supports":
				$hasUnknown = true;
				break;
		}

		if( $hasIdentifier ){
			$value = $this->parseEntity();
			if( !$value ){
				$this->error("expected " . $name . " identifier");
			}
		} else if( $hasExpression ){
			$value = $this->parseExpression();
			if( !$value ){
				$this->error("expected " . $name. " expression");
			}
		} else if ($hasUnknown) {

			$value = $this->MatchReg('/\\G[^{;]+/');
			if( $value ){
				$value = $this->NewObj1('Less_Tree_Anonymous',trim($value[0]));
			}
		}

		if( $hasBlock ){
			$rules = $this->parseBlockRuleset();
		}

		if( $rules || (!$hasBlock && $value && $this->MatchChar(';'))) {
			$this->forget();
			return $this->NewObj5('Less_Tree_Directive',array($name, $value, $rules, $index, $this->env->currentFileInfo));
		}

		$this->restore();
	}


	//
	// A Value is a comma-delimited list of Expressions
	//
	//	 font-family: Baskerville, Georgia, serif;
	//
	// In a Rule, a Value represents everything after the `:`,
	// and before the `;`.
	//
	private function parseValue(){
		$expressions = array();

		do{
			$e = $this->parseExpression();
			if( $e ){
				$expressions[] = $e;
				if (! $this->MatchChar(',')) {
					break;
				}
			}
		}while($e);

		if( $expressions ){
			return $this->NewObj1('Less_Tree_Value',$expressions);
		}
	}

	private function parseImportant (){
		if( $this->PeekChar('!') && $this->MatchReg('/\\G! *important/') ){
			return ' !important';
		}
	}

	private function parseSub (){

		if( $this->MatchChar('(') ){
			$a = $this->parseAddition();
			if( $a ){
				$this->expectChar(')');
				return $this->NewObj2('Less_Tree_Expression',array( array($a), true) ); //instead of $e->parens = true so the value is cached
			}
		}
	}


	/**
	 * Parses multiplication operation
	 *
	 * @return Less_Tree_Operation|null
	 */
	function parseMultiplication(){

		$return = $m = $this->parseOperand();
		if( $return ){
			while( true ){

				$isSpaced = $this->isWhitespace( -1 );

				if( $this->PeekReg('/\\G\/[*\/]/') ){
					break;
				}

				$op = $this->MatchChar('/');
				if( !$op ){
					$op = $this->MatchChar('*');
					if( !$op ){
						break;
					}
				}

				$a = $this->parseOperand();

				if(!$a) { break; }

				$m->parensInOp = true;
				$a->parensInOp = true;
				$return = $this->NewObj3('Less_Tree_Operation',array( $op, array( $return, $a ), $isSpaced) );
			}
		}
		return $return;

	}


	/**
	 * Parses an addition operation
	 *
	 * @return Less_Tree_Operation|null
	 */
	private function parseAddition (){

		$return = $m = $this->parseMultiplication();
		if( $return ){
			while( true ){

				$isSpaced = $this->isWhitespace( -1 );

				$op = $this->MatchReg('/\\G[-+]\s+/');
				if( $op ){
					$op = $op[0];
				}else{
					if( !$isSpaced ){
						$op = $this->match(array('#+','#-'));
					}
					if( !$op ){
						break;
					}
				}

				$a = $this->parseMultiplication();
				if( !$a ){
					break;
				}

				$m->parensInOp = true;
				$a->parensInOp = true;
				$return = $this->NewObj3('Less_Tree_Operation',array($op, array($return, $a), $isSpaced));
			}
		}

		return $return;
	}


	/**
	 * Parses the conditions
	 *
	 * @return Less_Tree_Condition|null
	 */
	private function parseConditions() {
		$index = $this->pos;
		$return = $a = $this->parseCondition();
		if( $a ){
			while( true ){
				if( !$this->PeekReg('/\\G,\s*(not\s*)?\(/') ||  !$this->MatchChar(',') ){
					break;
				}
				$b = $this->parseCondition();
				if( !$b ){
					break;
				}

				$return = $this->NewObj4('Less_Tree_Condition',array('or', $return, $b, $index));
			}
			return $return;
		}
	}

	private function parseCondition() {
		$index = $this->pos;
		$negate = false;
		$c = null;

		if ($this->MatchReg('/\\Gnot/')) $negate = true;
		$this->expectChar('(');
		$a = $this->MatchFuncs(array('parseAddition','parseEntitiesKeyword','parseEntitiesQuoted'));

		if( $a ){
			$op = $this->MatchReg('/\\G(?:>=|<=|=<|[<=>])/');
			if( $op ){
				$b = $this->MatchFuncs(array('parseAddition','parseEntitiesKeyword','parseEntitiesQuoted'));
				if( $b ){
					$c = $this->NewObj5('Less_Tree_Condition',array($op[0], $a, $b, $index, $negate));
				} else {
					$this->Error('Unexpected expression');
				}
			} else {
				$k = $this->NewObj1('Less_Tree_Keyword','true');
				$c = $this->NewObj5('Less_Tree_Condition',array('=', $a, $k, $index, $negate));
			}
			$this->expectChar(')');
			return $this->MatchReg('/\\Gand/') ? $this->NewObj3('Less_Tree_Condition',array('and', $c, $this->parseCondition())) : $c;
		}
	}

	/**
	 * An operand is anything that can be part of an operation,
	 * such as a Color, or a Variable
	 *
	 */
	private function parseOperand (){

		$negate = false;
		$offset = $this->pos+1;
		if( $offset >= $this->input_len ){
			return;
		}
		$char = $this->input[$offset];
		if( $char === '@' || $char === '(' ){
			$negate = $this->MatchChar('-');
		}

		$o = $this->MatchFuncs(array('parseSub','parseEntitiesDimension','parseEntitiesColor','parseEntitiesVariable','parseEntitiesCall'));

		if( $negate ){
			$o->parensInOp = true;
			$o = $this->NewObj1('Less_Tree_Negative',$o);
		}

		return $o;
	}


	/**
	 * Expressions either represent mathematical operations,
	 * or white-space delimited Entities.
	 *
	 *	 1px solid black
	 *	 @var * 2
	 *
	 * @return Less_Tree_Expression|null
	 */
	private function parseExpression (){
		$entities = array();

		do{
			$e = $this->MatchFuncs(array('parseAddition','parseEntity'));
			if( $e ){
				$entities[] = $e;
				// operations do not allow keyword "/" dimension (e.g. small/20px) so we support that here
				if( !$this->PeekReg('/\\G\/[\/*]/') ){
					$delim = $this->MatchChar('/');
					if( $delim ){
						$entities[] = $this->NewObj1('Less_Tree_Anonymous',$delim);
					}
				}
			}
		}while($e);

		if( $entities ){
			return $this->NewObj1('Less_Tree_Expression',$entities);
		}
	}


	/**
	 * Parse a property
	 * eg: 'min-width', 'orientation', etc
	 *
	 * @return string
	 */
	private function parseProperty (){
		$name = $this->MatchReg('/\\G(\*?-?[_a-zA-Z0-9-]+)\s*:/');
		if( $name ){
			return $name[1];
		}
	}


	/**
	 * Parse a rule property
	 * eg: 'color', 'width', 'height', etc
	 *
	 * @return string
	 */
	private function parseRuleProperty(){
		$offset = $this->pos;
		$name = array();
		$index = array();
		$length = 0;


		$this->rulePropertyMatch('/\\G(\*?)/', $offset, $length, $index, $name );
		while( $this->rulePropertyMatch('/\\G((?:[\w-]+)|(?:@\{[\w-]+\}))/', $offset, $length, $index, $name )); // !

		if( (count($name) > 1) && $this->rulePropertyMatch('/\\G\s*((?:\+_|\+)?)\s*:/', $offset, $length, $index, $name) ){
			// at last, we have the complete match now. move forward,
			// convert name particles to tree objects and return:
			$this->skipWhitespace($length);

			if( $name[0] === '' ){
				array_shift($name);
				array_shift($index);
			}
			foreach($name as $k => $s ){
				if( !$s || $s[0] !== '@' ){
					$name[$k] = $this->NewObj1('Less_Tree_Keyword',$s);
				}else{
					$name[$k] = $this->NewObj3('Less_Tree_Variable',array('@' . substr($s,2,-1), $index[$k], $this->env->currentFileInfo));
				}
			}
			return $name;
		}


	}

	private function rulePropertyMatch( $re, &$offset, &$length,  &$index, &$name ){
		preg_match($re, $this->input, $a, 0, $offset);
		if( $a ){
			$index[] = $this->pos + $length;
			$length += strlen($a[0]);
			$offset += strlen($a[0]);
			$name[] = $a[1];
			return true;
		}
	}

	public function serializeVars( $vars ){
		$s = '';

		foreach($vars as $name => $value){
			$s .= (($name[0] === '@') ? '' : '@') . $name .': '. $value . ((substr($value,-1) === ';') ? '' : ';');
		}

		return $s;
	}


	/**
	 * Some versions of php have trouble with method_exists($a,$b) if $a is not an object
	 *
	 * @param string $b
	 */
	public static function is_method($a,$b){
		return is_object($a) && method_exists($a,$b);
	}


	/**
	 * Round numbers similarly to javascript
	 * eg: 1.499999 to 1 instead of 2
	 *
	 */
	public static function round($i, $precision = 0){

		$precision = pow(10,$precision);
		$i = $i*$precision;

		$ceil = ceil($i);
		$floor = floor($i);
		if( ($ceil - $i) <= ($i - $floor) ){
			return $ceil/$precision;
		}else{
			return $floor/$precision;
		}
	}


	/**
	 * Create Less_Tree_* objects and optionally generate a cache string
	 *
	 * @return mixed
	 */
	public function NewObj0($class){
		$obj = new $class();
		if( Less_Cache::$cache_dir ){
			$obj->cache_string = ' new '.$class.'()';
		}
		return $obj;
	}

	public function NewObj1($class, $arg){
		$obj = new $class( $arg );
		if( Less_Cache::$cache_dir ){
			$obj->cache_string = ' new '.$class.'('.Less_Parser::ArgString($arg).')';
		}
		return $obj;
	}

	public function NewObj2($class, $args){
		$obj = new $class( $args[0], $args[1] );
		if( Less_Cache::$cache_dir ){
			$this->ObjCache( $obj, $class, $args);
		}
		return $obj;
	}

	public function NewObj3($class, $args){
		$obj = new $class( $args[0], $args[1], $args[2] );
		if( Less_Cache::$cache_dir ){
			$this->ObjCache( $obj, $class, $args);
		}
		return $obj;
	}

	public function NewObj4($class, $args){
		$obj = new $class( $args[0], $args[1], $args[2], $args[3] );
		if( Less_Cache::$cache_dir ){
			$this->ObjCache( $obj, $class, $args);
		}
		return $obj;
	}

	public function NewObj5($class, $args){
		$obj = new $class( $args[0], $args[1], $args[2], $args[3], $args[4] );
		if( Less_Cache::$cache_dir ){
			$this->ObjCache( $obj, $class, $args);
		}
		return $obj;
	}

	public function NewObj6($class, $args){
		$obj = new $class( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5] );
		if( Less_Cache::$cache_dir ){
			$this->ObjCache( $obj, $class, $args);
		}
		return $obj;
	}

	public function NewObj7($class, $args){
		$obj = new $class( $args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6] );
		if( Less_Cache::$cache_dir ){
			$this->ObjCache( $obj, $class, $args);
		}
		return $obj;
	}

	//caching
	public function ObjCache($obj, $class, $args=array()){
		$obj->cache_string = ' new '.$class.'('. self::ArgCache($args).')';
	}

	public function ArgCache($args){
		return implode(',',array_map( array('Less_Parser','ArgString'),$args));
	}


	/**
	 * Convert an argument to a string for use in the parser cache
	 *
	 * @return string
	 */
	public static function ArgString($arg){

		$type = gettype($arg);

		if( $type === 'object'){
			$string = $arg->cache_string;
			unset($arg->cache_string);
			return $string;

		}elseif( $type === 'array' ){
			$string = ' Array(';
			foreach($arg as $k => $a){
				$string .= var_export($k,true).' => '.self::ArgString($a).',';
			}
			return $string . ')';
		}

		return var_export($arg,true);
	}

	public function Error($msg){
		throw new Less_Exception_Parser($msg, null, $this->furthest, $this->env->currentFileInfo);
	}

	public static function WinPath($path){
		return str_replace('\\', '/', $path);
	}

}




/**
 * Utility for css colors
 *
 * @package Less
 * @subpackage color
 */
class Less_Colors {

	public static $colors = array(
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
			'turquoise'=>'#40e0d0',
			'violet'=>'#ee82ee',
			'wheat'=>'#f5deb3',
			'white'=>'#ffffff',
			'whitesmoke'=>'#f5f5f5',
			'yellow'=>'#ffff00',
			'yellowgreen'=>'#9acd32'
		);

	public static function hasOwnProperty($color) {
		return isset(self::$colors[$color]);
	}


	public static function color($color) {
		return self::$colors[$color];
	}

}



/**
 * Environment
 *
 * @package Less
 * @subpackage environment
 */
class Less_Environment{

	//public $paths = array();				// option - unmodified - paths to search for imports on
	//public static $files = array();		// list of files that have been imported, used for import-once
	//public $rootpath;						// option - rootpath to append to URL's
	//public static $strictImports = null;	// option -
	//public $insecure;						// option - whether to allow imports from insecure ssl hosts
	//public $processImports;				// option - whether to process imports. if false then imports will not be imported
	//public $javascriptEnabled;			// option - whether JavaScript is enabled. if undefined, defaults to true
	//public $useFileCache;					// browser only - whether to use the per file session cache
	public $currentFileInfo;				// information about the current file - for error reporting and importing and making urls relative etc.

	public $importMultiple = false; 		// whether we are currently importing multiple copies


	/**
	 * @var array
	 */
	public $frames = array();

	/**
	 * @var array
	 */
	public $mediaBlocks = array();

	/**
	 * @var array
	 */
	public $mediaPath = array();

	public static $parensStack = 0;

	public static $tabLevel = 0;

	public static $lastRule = false;

	public static $_outputMap;

	public static $mixin_stack = 0;


	public function Init(){

		self::$parensStack = 0;
		self::$tabLevel = 0;
		self::$lastRule = false;
		self::$mixin_stack = 0;

		if( Less_Parser::$options['compress'] ){

			Less_Environment::$_outputMap = array(
				','	=> ',',
				': ' => ':',
				''  => '',
				' ' => ' ',
				':' => ' :',
				'+' => '+',
				'~' => '~',
				'>' => '>',
				'|' => '|',
				'^' => '^',
				'^^' => '^^'
			);

		}else{

			Less_Environment::$_outputMap = array(
				','	=> ', ',
				': ' => ': ',
				''  => '',
				' ' => ' ',
				':' => ' :',
				'+' => ' + ',
				'~' => ' ~ ',
				'>' => ' > ',
				'|' => '|',
				'^' => ' ^ ',
				'^^' => ' ^^ '
			);

		}
	}


	public function copyEvalEnv($frames = array() ){
		$new_env = new Less_Environment();
		$new_env->frames = $frames;
		return $new_env;
	}


	public static function isMathOn(){
		return !Less_Parser::$options['strictMath'] || Less_Environment::$parensStack;
	}

	public static function isPathRelative($path){
		return !preg_match('/^(?:[a-z-]+:|\/)/',$path);
	}


	/**
	 * Canonicalize a path by resolving references to '/./', '/../'
	 * Does not remove leading "../"
	 * @param string path or url
	 * @return string Canonicalized path
	 *
	 */
	static function normalizePath($path){

		$segments = explode('/',$path);
		$segments = array_reverse($segments);

		$path = array();
		$path_len = 0;

		while( $segments ){
			$segment = array_pop($segments);
			switch( $segment ) {

				case '.':
				break;

				case '..':
					if( !$path_len || ( $path[$path_len-1] === '..') ){
						$path[] = $segment;
						$path_len++;
					}else{
						array_pop($path);
						$path_len--;
					}
				break;

				default:
					$path[] = $segment;
					$path_len++;
				break;
			}
		}

		return implode('/',$path);
	}


	public function unshiftFrame($frame){
		array_unshift($this->frames, $frame);
	}

	public function shiftFrame(){
		return array_shift($this->frames);
	}

}


/**
 * Builtin functions
 *
 * @package Less
 * @subpackage function
 * @see http://lesscss.org/functions/
 */
class Less_Functions{

	public $env;
	public $currentFileInfo;

	function __construct($env, $currentFileInfo = null ){
		$this->env = $env;
		$this->currentFileInfo = $currentFileInfo;
	}


	/**
	 * @param string $op
	 */
	static public function operate( $op, $a, $b ){
		switch ($op) {
			case '+': return $a + $b;
			case '-': return $a - $b;
			case '*': return $a * $b;
			case '/': return $a / $b;
		}
	}

	static public function clamp($val, $max = 1){
		return min( max($val, 0), $max);
	}

	static function fround( $value ){

		if( $value === 0 ){
			return $value;
		}

		if( Less_Parser::$options['numPrecision'] ){
			$p = pow(10, Less_Parser::$options['numPrecision']);
			return round( $value * $p) / $p;
		}
		return $value;
	}

	static public function number($n){

		if ($n instanceof Less_Tree_Dimension) {
			return floatval( $n->unit->is('%') ? $n->value / 100 : $n->value);
		} else if (is_numeric($n)) {
			return $n;
		} else {
			throw new Less_Exception_Compiler("color functions take numbers as parameters");
		}
	}

	static public function scaled($n, $size = 255 ){
		if( $n instanceof Less_Tree_Dimension && $n->unit->is('%') ){
			return (float)$n->value * $size / 100;
		} else {
			return Less_Functions::number($n);
		}
	}

	public function rgb ($r, $g, $b){
		return $this->rgba($r, $g, $b, 1.0);
	}

	public function rgba($r, $g, $b, $a){
		$rgb = array($r, $g, $b);
		$rgb = array_map(array('Less_Functions','scaled'),$rgb);

		$a = self::number($a);
		return new Less_Tree_Color($rgb, $a);
	}

	public function hsl($h, $s, $l){
		return $this->hsla($h, $s, $l, 1.0);
	}

	public function hsla($h, $s, $l, $a){

		$h = fmod(self::number($h), 360) / 360; // Classic % operator will change float to int
		$s = self::clamp(self::number($s));
		$l = self::clamp(self::number($l));
		$a = self::clamp(self::number($a));

		$m2 = $l <= 0.5 ? $l * ($s + 1) : $l + $s - $l * $s;

		$m1 = $l * 2 - $m2;

		return $this->rgba( self::hsla_hue($h + 1/3, $m1, $m2) * 255,
							self::hsla_hue($h, $m1, $m2) * 255,
							self::hsla_hue($h - 1/3, $m1, $m2) * 255,
							$a);
	}

	/**
	 * @param double $h
	 */
	function hsla_hue($h, $m1, $m2){
		$h = $h < 0 ? $h + 1 : ($h > 1 ? $h - 1 : $h);
		if	  ($h * 6 < 1) return $m1 + ($m2 - $m1) * $h * 6;
		else if ($h * 2 < 1) return $m2;
		else if ($h * 3 < 2) return $m1 + ($m2 - $m1) * (2/3 - $h) * 6;
		else				 return $m1;
	}

	public function hsv($h, $s, $v) {
		return $this->hsva($h, $s, $v, 1.0);
	}

	/**
	 * @param double $a
	 */
	public function hsva($h, $s, $v, $a) {
		$h = ((Less_Functions::number($h) % 360) / 360 ) * 360;
		$s = Less_Functions::number($s);
		$v = Less_Functions::number($v);
		$a = Less_Functions::number($a);

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

		return $this->rgba($vs[$perm[$i][0]] * 255,
						 $vs[$perm[$i][1]] * 255,
						 $vs[$perm[$i][2]] * 255,
						 $a);
	}

	public function hue($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension(Less_Parser::round($c['h']));
	}

	public function saturation($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension(Less_Parser::round($c['s'] * 100), '%');
	}

	public function lightness($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension(Less_Parser::round($c['l'] * 100), '%');
	}

	public function hsvhue( $color ){
		$hsv = $color->toHSV();
		return new Less_Tree_Dimension( Less_Parser::round($hsv['h']) );
	}


	public function hsvsaturation( $color ){
		$hsv = $color->toHSV();
		return new Less_Tree_Dimension( Less_Parser::round($hsv['s'] * 100), '%' );
	}

	public function hsvvalue( $color ){
		$hsv = $color->toHSV();
		return new Less_Tree_Dimension( Less_Parser::round($hsv['v'] * 100), '%' );
	}

	public function red($color) {
		return new Less_Tree_Dimension( $color->rgb[0] );
	}

	public function green($color) {
		return new Less_Tree_Dimension( $color->rgb[1] );
	}

	public function blue($color) {
		return new Less_Tree_Dimension( $color->rgb[2] );
	}

	public function alpha($color){
		$c = $color->toHSL();
		return new Less_Tree_Dimension($c['a']);
	}

	public function luma ($color) {
		return new Less_Tree_Dimension(Less_Parser::round( $color->luma() * $color->alpha * 100), '%');
	}

	public function luminance( $color ){
		$luminance =
			(0.2126 * $color->rgb[0] / 255)
		  + (0.7152 * $color->rgb[1] / 255)
		  + (0.0722 * $color->rgb[2] / 255);

		return new Less_Tree_Dimension(Less_Parser::round( $luminance * $color->alpha * 100), '%');
	}

	public function saturate($color, $amount = null){
		// filter: saturate(3.2);
		// should be kept as is, so check for color
		if( !property_exists($color,'rgb') ){
			return null;
		}
		$hsl = $color->toHSL();

		$hsl['s'] += $amount->value / 100;
		$hsl['s'] = self::clamp($hsl['s']);

		return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	/**
	 * @param Less_Tree_Dimension $amount
	 */
	public function desaturate($color, $amount){
		$hsl = $color->toHSL();

		$hsl['s'] -= $amount->value / 100;
		$hsl['s'] = self::clamp($hsl['s']);

		return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}



	public function lighten($color, $amount){
		$hsl = $color->toHSL();

		$hsl['l'] += $amount->value / 100;
		$hsl['l'] = self::clamp($hsl['l']);

		return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	public function darken($color, $amount){

		if( $color instanceof Less_Tree_Color ){
			$hsl = $color->toHSL();
			$hsl['l'] -= $amount->value / 100;
			$hsl['l'] = self::clamp($hsl['l']);

			return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
		}

		Less_Functions::Expected('color',$color);
	}

	public function fadein($color, $amount){
		$hsl = $color->toHSL();
		$hsl['a'] += $amount->value / 100;
		$hsl['a'] = self::clamp($hsl['a']);
		return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	public function fadeout($color, $amount){
		$hsl = $color->toHSL();
		$hsl['a'] -= $amount->value / 100;
		$hsl['a'] = self::clamp($hsl['a']);
		return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	public function fade($color, $amount){
		$hsl = $color->toHSL();

		$hsl['a'] = $amount->value / 100;
		$hsl['a'] = self::clamp($hsl['a']);
		return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}



	public function spin($color, $amount){
		$hsl = $color->toHSL();
		$hue = fmod($hsl['h'] + $amount->value, 360);

		$hsl['h'] = $hue < 0 ? 360 + $hue : $hue;

		return $this->hsla($hsl['h'], $hsl['s'], $hsl['l'], $hsl['a']);
	}

	//
	// Copyright (c) 2006-2009 Hampton Catlin, Nathan Weizenbaum, and Chris Eppstein
	// http://sass-lang.com
	//

	/**
	 * @param Less_Tree_Color $color1
	 */
	public function mix($color1, $color2, $weight = null){
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

	public function greyscale($color){
		return $this->desaturate($color, new Less_Tree_Dimension(100));
	}


	public function contrast( $color, $dark = null, $light = null, $threshold = null){
		// filter: contrast(3.2);
		// should be kept as is, so check for color
		if( !property_exists($color,'rgb') ){
			return null;
		}
		if( !$light ){
			$light = $this->rgba(255, 255, 255, 1.0);
		}
		if( !$dark ){
			$dark = $this->rgba(0, 0, 0, 1.0);
		}
		//Figure out which is actually light and dark!
		if( $dark->luma() > $light->luma() ){
			$t = $light;
			$light = $dark;
			$dark = $t;
		}
		if( !$threshold ){
			$threshold = 0.43;
		} else {
			$threshold = Less_Functions::number($threshold);
		}

		if( $color->luma() < $threshold ){
			return $light;
		} else {
			return $dark;
		}
	}

	public function e ($str){
		if( is_string($str) ){
			return new Less_Tree_Anonymous($str);
		}
		return new Less_Tree_Anonymous($str instanceof Less_Tree_JavaScript ? $str->expression : $str->value);
	}

	public function escape ($str){

		$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'",'%3F'=>'?','%26'=>'&','%2C'=>',','%2F'=>'/','%40'=>'@','%2B'=>'+','%24'=>'$');

		return new Less_Tree_Anonymous(strtr(rawurlencode($str->value), $revert));
	}


	/**
	 * todo: This function will need some additional work to make it work the same as less.js
	 *
	 */
	public function replace( $string, $pattern, $replacement, $flags = null ){
		$result = $string->value;

		$expr = '/'.str_replace('/','\\/',$pattern->value).'/';
		if( $flags && $flags->value){
			$expr .= self::replace_flags($flags->value);
		}

		$result = preg_replace($expr,$replacement->value,$result);


		if( property_exists($string,'quote') ){
			return new Less_Tree_Quoted( $string->quote, $result, $string->escaped);
		}
		return new Less_Tree_Quoted( '', $result );
	}

	public static function replace_flags($flags){
		$flags = str_split($flags,1);
		$new_flags = '';

		foreach($flags as $flag){
			switch($flag){
				case 'e':
				case 'g':
				break;

				default:
				$new_flags .= $flag;
				break;
			}
		}

		return $new_flags;
	}

	public function _percent(){
		$string = func_get_arg(0);

		$args = func_get_args();
		array_shift($args);
		$result = $string->value;

		foreach($args as $arg){
			if( preg_match('/%[sda]/i',$result, $token) ){
				$token = $token[0];
				$value = stristr($token, 's') ? $arg->value : $arg->toCSS();
				$value = preg_match('/[A-Z]$/', $token) ? urlencode($value) : $value;
				$result = preg_replace('/%[sda]/i',$value, $result, 1);
			}
		}
		$result = str_replace('%%', '%', $result);

		return new Less_Tree_Quoted( $string->quote , $result, $string->escaped);
	}

	public function unit( $val, $unit = null) {
		if( !($val instanceof Less_Tree_Dimension) ){
			throw new Less_Exception_Compiler('The first argument to unit must be a number' . ($val instanceof Less_Tree_Operation ? '. Have you forgotten parenthesis?' : '.') );
		}

		if( $unit ){
			if( $unit instanceof Less_Tree_Keyword ){
				$unit = $unit->value;
			} else {
				$unit = $unit->toCSS();
			}
		} else {
			$unit = "";
		}
		return new Less_Tree_Dimension($val->value, $unit );
	}

	public function convert($val, $unit){
		return $val->convertTo($unit->value);
	}

	public function round($n, $f = false) {

		$fraction = 0;
		if( $f !== false ){
			$fraction = $f->value;
		}

		return $this->_math('Less_Parser::round',null, $n, $fraction);
	}

	public function pi(){
		return new Less_Tree_Dimension(M_PI);
	}

	public function mod($a, $b) {
		return new Less_Tree_Dimension( $a->value % $b->value, $a->unit);
	}



	public function pow($x, $y) {
		if( is_numeric($x) && is_numeric($y) ){
			$x = new Less_Tree_Dimension($x);
			$y = new Less_Tree_Dimension($y);
		}elseif( !($x instanceof Less_Tree_Dimension) || !($y instanceof Less_Tree_Dimension) ){
			throw new Less_Exception_Compiler('Arguments must be numbers');
		}

		return new Less_Tree_Dimension( pow($x->value, $y->value), $x->unit );
	}

	// var mathFunctions = [{name:"ce ...
	public function ceil( $n ){		return $this->_math('ceil', null, $n); }
	public function floor( $n ){	return $this->_math('floor', null, $n); }
	public function sqrt( $n ){		return $this->_math('sqrt', null, $n); }
	public function abs( $n ){		return $this->_math('abs', null, $n); }

	public function tan( $n ){		return $this->_math('tan', '', $n);	}
	public function sin( $n ){		return $this->_math('sin', '', $n);	}
	public function cos( $n ){		return $this->_math('cos', '', $n);	}

	public function atan( $n ){		return $this->_math('atan', 'rad', $n);	}
	public function asin( $n ){		return $this->_math('asin', 'rad', $n);	}
	public function acos( $n ){		return $this->_math('acos', 'rad', $n);	}

	private function _math() {
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
			throw new Less_Exception_Compiler("math functions take numbers as parameters");
		}
	}

	/**
	 * @param boolean $isMin
	 */
	function _minmax( $isMin, $args ){

		$arg_count = count($args);

		if( $arg_count < 1 ){
			throw new Less_Exception_Compiler( 'one or more arguments required');
		}

		$j = null;
		$unitClone = null;
		$unitStatic = null;


		$order = array();	// elems only contains original argument values.
		$values = array();	// key is the unit.toString() for unified tree.Dimension values,
							// value is the index into the order array.


		for( $i = 0; $i < $arg_count; $i++ ){
			$current = $args[$i];
			if( !($current instanceof Less_Tree_Dimension) ){
				if( is_array($args[$i]->value) ){
					$args[] = $args[$i]->value;
				}
				continue;
			}

			if( $current->unit->toString() === '' && !$unitClone ){
				$temp = new Less_Tree_Dimension($current->value, $unitClone);
				$currentUnified = $temp->unify();
			}else{
				$currentUnified = $current->unify();
			}

			if( $currentUnified->unit->toString() === "" && !$unitStatic ){
				$unit = $unitStatic;
			}else{
				$unit = $currentUnified->unit->toString();
			}

			if( $unit !== '' && !$unitStatic || $unit !== '' && $order[0]->unify()->unit->toString() === "" ){
				$unitStatic = $unit;
			}

			if( $unit != '' && !$unitClone ){
				$unitClone = $current->unit->toString();
			}

			if( isset($values['']) && $unit !== '' && $unit === $unitStatic ){
				$j = $values[''];
			}elseif( isset($values[$unit]) ){
				$j = $values[$unit];
			}else{

				if( $unitStatic && $unit !== $unitStatic ){
					throw new Less_Exception_Compiler( 'incompatible types');
				}
				$values[$unit] = count($order);
				$order[] = $current;
				continue;
			}


			if( $order[$j]->unit->toString() === "" && $unitClone ){
				$temp = new Less_Tree_Dimension( $order[$j]->value, $unitClone);
				$referenceUnified = $temp->unifiy();
			}else{
				$referenceUnified = $order[$j]->unify();
			}
			if( ($isMin && $currentUnified->value < $referenceUnified->value) || (!$isMin && $currentUnified->value > $referenceUnified->value) ){
				$order[$j] = $current;
			}
		}

		if( count($order) == 1 ){
			return $order[0];
		}
		$args = array();
		foreach($order as $a){
			$args[] = $a->toCSS($this->env);
		}
		return new Less_Tree_Anonymous( ($isMin?'min(':'max(') . implode(Less_Environment::$_outputMap[','],$args).')');
	}

	public function min(){
		$args = func_get_args();
		return $this->_minmax( true, $args );
	}

	public function max(){
		$args = func_get_args();
		return $this->_minmax( false, $args );
	}

	public function getunit($n){
		return new Less_Tree_Anonymous($n->unit);
	}

	public function argb($color) {
		return new Less_Tree_Anonymous($color->toARGB());
	}

	public function percentage($n) {
		return new Less_Tree_Dimension($n->value * 100, '%');
	}

	public function color($n) {

		if( $n instanceof Less_Tree_Quoted ){
			$colorCandidate = $n->value;
			$returnColor = Less_Tree_Color::fromKeyword($colorCandidate);
			if( $returnColor ){
				return $returnColor;
			}
			if( preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})/',$colorCandidate) ){
				return new Less_Tree_Color(substr($colorCandidate, 1));
			}
			throw new Less_Exception_Compiler("argument must be a color keyword or 3/6 digit hex e.g. #FFF");
		} else {
			throw new Less_Exception_Compiler("argument must be a string");
		}
	}


	public function iscolor($n) {
		return $this->_isa($n, 'Less_Tree_Color');
	}

	public function isnumber($n) {
		return $this->_isa($n, 'Less_Tree_Dimension');
	}

	public function isstring($n) {
		return $this->_isa($n, 'Less_Tree_Quoted');
	}

	public function iskeyword($n) {
		return $this->_isa($n, 'Less_Tree_Keyword');
	}

	public function isurl($n) {
		return $this->_isa($n, 'Less_Tree_Url');
	}

	public function ispixel($n) {
		return $this->isunit($n, 'px');
	}

	public function ispercentage($n) {
		return $this->isunit($n, '%');
	}

	public function isem($n) {
		return $this->isunit($n, 'em');
	}

	/**
	 * @param string $unit
	 */
	public function isunit( $n, $unit ){
		return ($n instanceof Less_Tree_Dimension) && $n->unit->is( ( property_exists($unit,'value') ? $unit->value : $unit) ) ? new Less_Tree_Keyword('true') : new Less_Tree_Keyword('false');
	}

	/**
	 * @param string $type
	 */
	private function _isa($n, $type) {
		return is_a($n, $type) ? new Less_Tree_Keyword('true') : new Less_Tree_Keyword('false');
	}

	public function tint($color, $amount) {
		return $this->mix( $this->rgb(255,255,255), $color, $amount);
	}

	public function shade($color, $amount) {
		return $this->mix($this->rgb(0, 0, 0), $color, $amount);
	}

	public function extract($values, $index ){
		$index = (int)$index->value - 1; // (1-based index)
		// handle non-array values as an array of length 1
		// return 'undefined' if index is invalid
		if( property_exists($values,'value') && is_array($values->value) ){
			if( isset($values->value[$index]) ){
				return $values->value[$index];
			}
			return null;

		}elseif( (int)$index === 0 ){
			return $values;
		}

		return null;
	}

	function length($values){
		$n = (property_exists($values,'value') && is_array($values->value)) ? count($values->value) : 1;
		return new Less_Tree_Dimension($n);
	}

	function datauri($mimetypeNode, $filePathNode = null ) {

		$filePath = ( $filePathNode ? $filePathNode->value : null );
		$mimetype = $mimetypeNode->value;

		$args = 2;
		if( !$filePath ){
			$filePath = $mimetype;
			$args = 1;
		}

		$filePath = str_replace('\\','/',$filePath);
		if( Less_Environment::isPathRelative($filePath) ){

			if( Less_Parser::$options['relativeUrls'] ){
				$temp = $this->currentFileInfo['currentDirectory'];
			} else {
				$temp = $this->currentFileInfo['entryPath'];
			}

			if( !empty($temp) ){
				$filePath = Less_Environment::normalizePath(rtrim($temp,'/').'/'.$filePath);
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
			if( $useBase64 ){ $mimetype .= ';base64'; }

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
			$filePath = '"data:' . $mimetype . ',' . $buf . '"';
		}

		return new Less_Tree_Url( new Less_Tree_Anonymous($filePath) );
	}

	//svg-gradient
	function svggradient( $direction ){

		$throw_message = 'svg-gradient expects direction, start_color [start_position], [color position,]..., end_color [end_position]';
		$arguments = func_get_args();

		if( count($arguments) < 3 ){
			throw new Less_Exception_Compiler( $throw_message );
		}

		$stops = array_slice($arguments,1);
		$gradientType = 'linear';
		$rectangleDimension = 'x="0" y="0" width="1" height="1"';
		$useBase64 = true;
		$directionValue = $direction->toCSS();


		switch( $directionValue ){
			case "to bottom":
				$gradientDirectionSvg = 'x1="0%" y1="0%" x2="0%" y2="100%"';
				break;
			case "to right":
				$gradientDirectionSvg = 'x1="0%" y1="0%" x2="100%" y2="0%"';
				break;
			case "to bottom right":
				$gradientDirectionSvg = 'x1="0%" y1="0%" x2="100%" y2="100%"';
				break;
			case "to top right":
				$gradientDirectionSvg = 'x1="0%" y1="100%" x2="100%" y2="0%"';
				break;
			case "ellipse":
			case "ellipse at center":
				$gradientType = "radial";
				$gradientDirectionSvg = 'cx="50%" cy="50%" r="75%"';
				$rectangleDimension = 'x="-50" y="-50" width="101" height="101"';
				break;
			default:
				throw new Less_Exception_Compiler( "svg-gradient direction must be 'to bottom', 'to right', 'to bottom right', 'to top right' or 'ellipse at center'" );
		}

		$returner = '<?xml version="1.0" ?>' .
			'<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="100%" height="100%" viewBox="0 0 1 1" preserveAspectRatio="none">' .
			'<' . $gradientType . 'Gradient id="gradient" gradientUnits="userSpaceOnUse" ' . $gradientDirectionSvg . '>';

		for( $i = 0; $i < count($stops); $i++ ){
			if( is_object($stops[$i]) && property_exists($stops[$i],'value') ){
				$color = $stops[$i]->value[0];
				$position = $stops[$i]->value[1];
			}else{
				$color = $stops[$i];
				$position = null;
			}

			if( !($color instanceof Less_Tree_Color) || (!(($i === 0 || $i+1 === count($stops)) && $position === null) && !($position instanceof Less_Tree_Dimension)) ){
				throw new Less_Exception_Compiler( $throw_message );
			}
			if( $position ){
				$positionValue = $position->toCSS();
			}elseif( $i === 0 ){
				$positionValue = '0%';
			}else{
				$positionValue = '100%';
			}
			$alpha = $color->alpha;
			$returner .= '<stop offset="' . $positionValue . '" stop-color="' . $color->toRGB() . '"' . ($alpha < 1 ? ' stop-opacity="' . $alpha . '"' : '') . '/>';
		}

		$returner .= '</' . $gradientType . 'Gradient><rect ' . $rectangleDimension . ' fill="url(#gradient)" /></svg>';


		if( $useBase64 ){
			$returner = "'data:image/svg+xml;base64,".base64_encode($returner)."'";
		}else{
			$returner = "'data:image/svg+xml,".$returner."'";
		}

		return new Less_Tree_URL( new Less_Tree_Anonymous( $returner ) );
	}


	/**
	 * @param string $type
	 */
	private static function Expected( $type, $arg ){

		$debug = debug_backtrace();
		array_shift($debug);
		$last = array_shift($debug);
		$last = array_intersect_key($last,array('function'=>'','class'=>'','line'=>''));

		$message = 'Object of type '.get_class($arg).' passed to darken function. Expecting `'.$type.'`. '.$arg->toCSS().'. '.print_r($last,true);
		throw new Less_Exception_Compiler($message);

	}

	/**
	 * Php version of javascript's `encodeURIComponent` function
	 *
	 * @param string $string The string to encode
	 * @return string The encoded string
	 */
	public static function encodeURIComponent($string){
		$revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
		return strtr(rawurlencode($string), $revert);
	}


	// Color Blending
	// ref: http://www.w3.org/TR/compositing-1

	public function colorBlend( $mode, $color1, $color2 ){
		$ab = $color1->alpha;	// backdrop
		$as = $color2->alpha;	// source
		$r = array();			// result

		$ar = $as + $ab * (1 - $as);
		for( $i = 0; $i < 3; $i++ ){
			$cb = $color1->rgb[$i] / 255;
			$cs = $color2->rgb[$i] / 255;
			$cr = call_user_func( $mode, $cb, $cs );
			if( $ar ){
				$cr = ($as * $cs + $ab * ($cb - $as * ($cb + $cs - $cr))) / $ar;
			}
			$r[$i] = $cr * 255;
		}

		return new Less_Tree_Color($r, $ar);
	}

	public function multiply($color1, $color2 ){
		return $this->colorBlend( array($this,'colorBlendMultiply'),  $color1, $color2 );
	}

	private function colorBlendMultiply($cb, $cs){
		return $cb * $cs;
	}

	public function screen($color1, $color2 ){
		return $this->colorBlend( array($this,'colorBlendScreen'),  $color1, $color2 );
	}

	private function colorBlendScreen( $cb, $cs){
		return $cb + $cs - $cb * $cs;
	}

	public function overlay($color1, $color2){
		return $this->colorBlend( array($this,'colorBlendOverlay'),  $color1, $color2 );
	}

	private function colorBlendOverlay($cb, $cs ){
		$cb *= 2;
		return ($cb <= 1)
			? $this->colorBlendMultiply($cb, $cs)
			: $this->colorBlendScreen($cb - 1, $cs);
	}

	public function softlight($color1, $color2){
		return $this->colorBlend( array($this,'colorBlendSoftlight'),  $color1, $color2 );
	}

	private function colorBlendSoftlight($cb, $cs ){
		$d = 1;
		$e = $cb;
		if( $cs > 0.5 ){
			$e = 1;
			$d = ($cb > 0.25) ? sqrt($cb)
				: ((16 * $cb - 12) * $cb + 4) * $cb;
		}
		return $cb - (1 - 2 * $cs) * $e * ($d - $cb);
	}

	public function hardlight($color1, $color2){
		return $this->colorBlend( array($this,'colorBlendHardlight'),  $color1, $color2 );
	}

	private function colorBlendHardlight( $cb, $cs ){
		return $this->colorBlendOverlay($cs, $cb);
	}

	public function difference($color1, $color2) {
		return $this->colorBlend( array($this,'colorBlendDifference'),  $color1, $color2 );
	}

	private function colorBlendDifference( $cb, $cs ){
		return abs($cb - $cs);
	}

	public function exclusion( $color1, $color2 ){
		return $this->colorBlend( array($this,'colorBlendExclusion'),  $color1, $color2 );
	}

	private function colorBlendExclusion( $cb, $cs ){
		return $cb + $cs - 2 * $cb * $cs;
	}

	public function average($color1, $color2){
		return $this->colorBlend( array($this,'colorBlendAverage'),  $color1, $color2 );
	}

	// non-w3c functions:
	function colorBlendAverage($cb, $cs ){
		return ($cb + $cs) / 2;
	}

	public function negation($color1, $color2 ){
		return $this->colorBlend( array($this,'colorBlendNegation'),  $color1, $color2 );
	}

	function colorBlendNegation($cb, $cs){
		return 1 - abs($cb + $cs - 1);
	}

	// ~ End of Color Blending

}


/**
 * Mime lookup
 *
 * @package Less
 * @subpackage node
 */
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

	static function charsets_lookup( $type = null ){
		// assumes all text types are UTF-8
		return $type && preg_match('/^text\//',$type) ? 'UTF-8' : '';
	}
}

/**
 * Tree
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree{

	public $cache_string;

	public function toCSS(){
		$output = new Less_Output();
		$this->genCSS($output);
		return $output->toString();
	}


	/**
	 * Generate CSS by adding it to the output object
	 *
	 * @param Less_Output $output The output
	 * @return void
	 */
	public function genCSS($output){}


	/**
	 * @param Less_Tree_Ruleset[] $rules
	 */
	public static function outputRuleset( $output, $rules ){

		$ruleCnt = count($rules);
		Less_Environment::$tabLevel++;


		// Compressed
		if( Less_Parser::$options['compress'] ){
			$output->add('{');
			for( $i = 0; $i < $ruleCnt; $i++ ){
				$rules[$i]->genCSS( $output );
			}

			$output->add( '}' );
			Less_Environment::$tabLevel--;
			return;
		}


		// Non-compressed
		$tabSetStr = "\n".str_repeat( '  ' , Less_Environment::$tabLevel-1 );
		$tabRuleStr = $tabSetStr.'  ';

		$output->add( " {" );
		for($i = 0; $i < $ruleCnt; $i++ ){
			$output->add( $tabRuleStr );
			$rules[$i]->genCSS( $output );
		}
		Less_Environment::$tabLevel--;
		$output->add( $tabSetStr.'}' );

	}

	public function accept($visitor){}


	public static function ReferencedArray($rules){
		foreach($rules as $rule){
			if( method_exists($rule, 'markReferenced') ){
				$rule->markReferenced();
			}
		}
	}


	/**
	 * Requires php 5.3+
	 */
	public static function __set_state($args){

		$class = get_called_class();
		$obj = new $class(null,null,null,null);
		foreach($args as $key => $val){
			$obj->$key = $val;
		}
		return $obj;
	}

}

/**
 * Parser output
 *
 * @package Less
 * @subpackage output
 */
class Less_Output{

	/**
	 * Output holder
	 *
	 * @var string
	 */
	protected $strs = array();

	/**
	 * Adds a chunk to the stack
	 *
	 * @param string $chunk The chunk to output
	 * @param Less_FileInfo $fileInfo The file information
	 * @param integer $index The index
	 * @param mixed $mapLines
	 */
	public function add($chunk, $fileInfo = null, $index = 0, $mapLines = null){
		$this->strs[] = $chunk;
	}

	/**
	 * Is the output empty?
	 *
	 * @return boolean
	 */
	public function isEmpty(){
		return count($this->strs) === 0;
	}


	/**
	 * Converts the output to string
	 *
	 * @return string
	 */
	public function toString(){
		return implode('',$this->strs);
	}

}

/**
 * Visitor
 *
 * @package Less
 * @subpackage visitor
 */
class Less_Visitor{

	var $methods = array();
	var $_visitFnCache = array();

	function __construct(){
		$this->_visitFnCache = get_class_methods(get_class($this));
		$this->_visitFnCache = array_flip($this->_visitFnCache);
	}

	function visitObj( $node ){

		$funcName = 'visit'.$node->type;
		if( isset($this->_visitFnCache[$funcName]) ){

			$visitDeeper = true;
			$this->$funcName( $node, $visitDeeper );

			if( $visitDeeper ){
				$node->accept($this);
			}

			$funcName = $funcName . "Out";
			if( isset($this->_visitFnCache[$funcName]) ){
				$this->$funcName( $node );
			}

		}else{
			$node->accept($this);
		}

		return $node;
	}

	function visitArray( $nodes ){

		array_map( array($this,'visitObj'), $nodes);
		return $nodes;
	}
}



/**
 * Replacing Visitor
 *
 * @package Less
 * @subpackage visitor
 */
class Less_VisitorReplacing extends Less_Visitor{

	function visitObj( $node ){

		$funcName = 'visit'.$node->type;
		if( isset($this->_visitFnCache[$funcName]) ){

			$visitDeeper = true;
			$node = $this->$funcName( $node, $visitDeeper );

			if( $node ){
				if( $visitDeeper && is_object($node) ){
					$node->accept($this);
				}

				$funcName = $funcName . "Out";
				if( isset($this->_visitFnCache[$funcName]) ){
					$this->$funcName( $node );
				}
			}

		}else{
			$node->accept($this);
		}

		return $node;
	}

	function visitArray( $nodes ){

		$newNodes = array();
		foreach($nodes as $node){
			$evald = $this->visitObj($node);
			if( $evald ){
				if( is_array($evald) ){
					self::flatten($evald,$newNodes);
				}else{
					$newNodes[] = $evald;
				}
			}
		}
		return $newNodes;
	}

	function flatten( $arr, &$out ){

		foreach($arr as $item){
			if( !is_array($item) ){
				$out[] = $item;
				continue;
			}

			foreach($item as $nestedItem){
				if( is_array($nestedItem) ){
					self::flatten( $nestedItem, $out);
				}else{
					$out[] = $nestedItem;
				}
			}
		}

		return $out;
	}

}




/**
 * Configurable
 *
 * @package Less
 * @subpackage Core
 */
abstract class Less_Configurable {

	/**
	 * Array of options
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Array of default options
	 *
	 * @var array
	 */
	protected $defaultOptions = array();


	/**
	 * Set options
	 *
	 * If $options is an object it will be converted into an array by called
	 * it's toArray method.
	 *
	 * @throws Exception
	 * @param array|object $options
	 *
	 */
	public function setOptions($options){
		$options = array_intersect_key($options,$this->defaultOptions);
		$this->options = array_merge($this->defaultOptions, $this->options, $options);
	}


	/**
	 * Get an option value by name
	 *
	 * If the option is empty or not set a NULL value will be returned.
	 *
	 * @param string $name
	 * @param mixed $default Default value if confiuration of $name is not present
	 * @return mixed
	 */
	public function getOption($name, $default = null){
		if(isset($this->options[$name])){
			return $this->options[$name];
		}
		return $default;
	}


	/**
	 * Set an option
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setOption($name, $value){
		$this->options[$name] = $value;
	}

}

/**
 * Alpha
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Alpha extends Less_Tree{
	public $value;
	public $type = 'Alpha';

	public function __construct($val){
		$this->value = $val;
	}

	//function accept( $visitor ){
	//	$this->value = $visitor->visit( $this->value );
	//}

	public function compile($env){

		if( is_object($this->value) ){
			$this->value = $this->value->compile($env);
		}

		return $this;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){

		$output->add( "alpha(opacity=" );

		if( is_string($this->value) ){
			$output->add( $this->value );
		}else{
			$this->value->genCSS( $output);
		}

		$output->add( ')' );
	}

	public function toCSS(){
		return "alpha(opacity=" . (is_string($this->value) ? $this->value : $this->value->toCSS()) . ")";
	}


}

/**
 * Anonymous
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Anonymous extends Less_Tree{
	public $value;
	public $quote;
	public $index;
	public $mapLines;
	public $currentFileInfo;
	public $type = 'Anonymous';

	/**
	 * @param integer $index
	 * @param boolean $mapLines
	 */
	public function __construct($value, $index = null, $currentFileInfo = null, $mapLines = null ){
		$this->value = $value;
		$this->index = $index;
		$this->mapLines = $mapLines;
		$this->currentFileInfo = $currentFileInfo;
	}

	public function compile(){
		return new Less_Tree_Anonymous($this->value, $this->index, $this->currentFileInfo, $this->mapLines);
	}

	function compare($x){
		if( !is_object($x) ){
			return -1;
		}

		$left = $this->toCSS();
		$right = $x->toCSS();

		if( $left === $right ){
			return 0;
		}

		return $left < $right ? -1 : 1;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){
		$output->add( $this->value, $this->currentFileInfo, $this->index, $this->mapLines );
	}

	public function toCSS(){
		return $this->value;
	}

}


/**
 * Assignment
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Assignment extends Less_Tree{

	public $key;
	public $value;
	public $type = 'Assignment';

	function __construct($key, $val) {
		$this->key = $key;
		$this->value = $val;
	}

	function accept( $visitor ){
		$this->value = $visitor->visitObj( $this->value );
	}

	public function compile($env) {
		return new Less_Tree_Assignment( $this->key, $this->value->compile($env));
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){
		$output->add( $this->key . '=' );
		$this->value->genCSS( $output );
	}

	public function toCss(){
		return $this->key . '=' . $this->value->toCSS();
	}
}


/**
 * Attribute
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Attribute extends Less_Tree{

	public $key;
	public $op;
	public $value;
	public $type = 'Attribute';

	function __construct($key, $op, $value){
		$this->key = $key;
		$this->op = $op;
		$this->value = $value;
	}

	function compile($env){

		$key_obj = is_object($this->key);
		$val_obj = is_object($this->value);

		if( !$key_obj && !$val_obj ){
			return $this;
		}

		return new Less_Tree_Attribute(
			$key_obj ? $this->key->compile($env) : $this->key ,
			$this->op,
			$val_obj ? $this->value->compile($env) : $this->value);
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$output->add( $this->toCSS() );
	}

	function toCSS(){
		$value = $this->key;

		if( $this->op ){
			$value .= $this->op;
			$value .= (is_object($this->value) ? $this->value->toCSS() : $this->value);
		}

		return '[' . $value . ']';
	}
}


/**
 * Call
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Call extends Less_Tree{
	public $value;

	var $name;
	var $args;
	var $index;
	var $currentFileInfo;
	public $type = 'Call';

	public function __construct($name, $args, $index, $currentFileInfo = null ){
		$this->name = $name;
		$this->args = $args;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	function accept( $visitor ){
		$this->args = $visitor->visitArray( $this->args );
	}

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
	public function compile($env=null){
		$args = array();
		foreach($this->args as $a){
			$args[] = $a->compile($env);
		}

		$nameLC = strtolower($this->name);
		switch($nameLC){
			case '%':
			$nameLC = '_percent';
			break;

			case 'get-unit':
			$nameLC = 'getunit';
			break;

			case 'data-uri':
			$nameLC = 'datauri';
			break;

			case 'svg-gradient':
			$nameLC = 'svggradient';
			break;
		}

		$result = null;
		if( $nameLC === 'default' ){
			$result = Less_Tree_DefaultFunc::compile();

		}else{

			if( method_exists('Less_Functions',$nameLC) ){ // 1.
				try {

					$func = new Less_Functions($env, $this->currentFileInfo);
					$result = call_user_func_array( array($func,$nameLC),$args);

				} catch (Exception $e) {
					throw new Less_Exception_Compiler('error evaluating function `' . $this->name . '` '.$e->getMessage().' index: '. $this->index);
				}
			}
		}

		if( $result !== null ){
			return $result;
		}


		return new Less_Tree_Call( $this->name, $args, $this->index, $this->currentFileInfo );
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){

		$output->add( $this->name . '(', $this->currentFileInfo, $this->index );
		$args_len = count($this->args);
		for($i = 0; $i < $args_len; $i++ ){
			$this->args[$i]->genCSS( $output );
			if( $i + 1 < $args_len ){
				$output->add( ', ' );
			}
		}

		$output->add( ')' );
	}


	//public function toCSS(){
	//    return $this->compile()->toCSS();
	//}

}


/**
 * Color
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Color extends Less_Tree{
	public $rgb;
	public $alpha;
	public $isTransparentKeyword;
	public $type = 'Color';

	public function __construct($rgb, $a = 1, $isTransparentKeyword = null ){

		if( $isTransparentKeyword ){
			$this->rgb = $rgb;
			$this->alpha = $a;
			$this->isTransparentKeyword = true;
			return;
		}

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

	public function compile(){
		return $this;
	}

	public function luma(){
		$r = $this->rgb[0] / 255;
		$g = $this->rgb[1] / 255;
		$b = $this->rgb[2] / 255;

		$r = ($r <= 0.03928) ? $r / 12.92 : pow((($r + 0.055) / 1.055), 2.4);
		$g = ($g <= 0.03928) ? $g / 12.92 : pow((($g + 0.055) / 1.055), 2.4);
		$b = ($b <= 0.03928) ? $b / 12.92 : pow((($b + 0.055) / 1.055), 2.4);

		return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){
		$output->add( $this->toCSS() );
	}

	public function toCSS( $doNotCompress = false ){
		$compress = Less_Parser::$options['compress'] && !$doNotCompress;
		$alpha = Less_Functions::fround( $this->alpha );


		//
		// If we have some transparency, the only way to represent it
		// is via `rgba`. Otherwise, we use the hex representation,
		// which has better compatibility with older browsers.
		// Values are capped between `0` and `255`, rounded and zero-padded.
		//
		if( $alpha < 1 ){
			if( $alpha === 0 && isset($this->isTransparentKeyword) && $this->isTransparentKeyword ){
				return 'transparent';
			}

			$values = array();
			foreach($this->rgb as $c){
				$values[] = Less_Functions::clamp( round($c), 255);
			}
			$values[] = $alpha;

			$glue = ($compress ? ',' : ', ');
			return "rgba(" . implode($glue, $values) . ")";
		}else{

			$color = $this->toRGB();

			if( $compress ){

				// Convert color to short format
				if( $color[1] === $color[2] && $color[3] === $color[4] && $color[5] === $color[6]) {
					$color = '#'.$color[1] . $color[3] . $color[5];
				}
			}

			return $color;
		}
	}

	//
	// Operations have to be done per-channel, if not,
	// channels will spill onto each other. Once we have
	// our result, in the form of an integer triplet,
	// we create a new Color node to hold the result.
	//

	/**
	 * @param string $op
	 */
	public function operate( $op, $other) {
		$rgb = array();
		$alpha = $this->alpha * (1 - $other->alpha) + $other->alpha;
		for ($c = 0; $c < 3; $c++) {
			$rgb[$c] = Less_Functions::operate( $op, $this->rgb[$c], $other->rgb[$c]);
		}
		return new Less_Tree_Color($rgb, $alpha);
	}

	public function toRGB(){
		return $this->toHex($this->rgb);
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

		$h = $s = 0;
		if( $max !== $min ){
			$s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);

			switch ($max) {
				case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
				case $g: $h = ($b - $r) / $d + 2;				 break;
				case $b: $h = ($r - $g) / $d + 4;				 break;
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

		$h = 0;
		if( $max !== $min ){
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
		$argb = array_merge( (array) Less_Parser::round($this->alpha * 255), $this->rgb);
		return $this->toHex( $argb );
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

	function toHex( $v ){

		$ret = '#';
		foreach($v as $c){
			$c = Less_Functions::clamp( Less_Parser::round($c), 255);
			if( $c < 16 ){
				$ret .= '0';
			}
			$ret .= dechex($c);
		}

		return $ret;
	}


	/**
	 * @param string $keyword
	 */
	public static function fromKeyword( $keyword ){
		$keyword = strtolower($keyword);

		if( Less_Colors::hasOwnProperty($keyword) ){
			// detect named color
			return new Less_Tree_Color(substr(Less_Colors::color($keyword), 1));
		}

		if( $keyword === 'transparent' ){
			return new Less_Tree_Color( array(0, 0, 0), 0, true);
		}
	}

}


/**
 * Comment
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Comment extends Less_Tree{

	public $value;
	public $silent;
	public $isReferenced;
	public $currentFileInfo;
	public $type = 'Comment';

	public function __construct($value, $silent, $index = null, $currentFileInfo = null ){
		$this->value = $value;
		$this->silent = !! $silent;
		$this->currentFileInfo = $currentFileInfo;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){
		//if( $this->debugInfo ){
			//$output->add( tree.debugInfo($env, $this), $this->currentFileInfo, $this->index);
		//}
		$output->add( trim($this->value) );//TODO shouldn't need to trim, we shouldn't grab the \n
	}

	public function toCSS(){
		return Less_Parser::$options['compress'] ? '' : $this->value;
	}

	public function isSilent(){
		$isReference = ($this->currentFileInfo && isset($this->currentFileInfo['reference']) && (!isset($this->isReferenced) || !$this->isReferenced) );
		$isCompressed = Less_Parser::$options['compress'] && !preg_match('/^\/\*!/', $this->value);
		return $this->silent || $isReference || $isCompressed;
	}

	public function compile(){
		return $this;
	}

	public function markReferenced(){
		$this->isReferenced = true;
	}

}


/**
 * Condition
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Condition extends Less_Tree{

	public $op;
	public $lvalue;
	public $rvalue;
	public $index;
	public $negate;
	public $type = 'Condition';

	public function __construct($op, $l, $r, $i = 0, $negate = false) {
		$this->op = trim($op);
		$this->lvalue = $l;
		$this->rvalue = $r;
		$this->index = $i;
		$this->negate = $negate;
	}

	public function accept($visitor){
		$this->lvalue = $visitor->visitObj( $this->lvalue );
		$this->rvalue = $visitor->visitObj( $this->rvalue );
	}

	public function compile($env) {
		$a = $this->lvalue->compile($env);
		$b = $this->rvalue->compile($env);

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
					throw new Less_Exception_Compiler('Unable to perform comparison', null, $this->index);
				}

				switch ($result) {
					case -1:
					$result = $this->op === '<' || $this->op === '=<' || $this->op === '<=';
					break;

					case  0:
					$result = $this->op === '=' || $this->op === '>=' || $this->op === '=<' || $this->op === '<=';
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


/**
 * DefaultFunc
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_DefaultFunc{

	static $error_;
	static $value_;

	static function compile(){
		if( self::$error_ ){
			throw Exception(self::$error_);
		}
		if( self::$value_ !== null ){
			return self::$value_ ? new Less_Tree_Keyword('true') : new Less_Tree_Keyword('false');
		}
	}

	static function value( $v ){
		self::$value_ = $v;
	}

	static function error( $e ){
		self::$error_ = $e;
	}

	static function reset(){
		self::$value_ = self::$error_ = null;
	}
}

/**
 * DetachedRuleset
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_DetachedRuleset extends Less_Tree{

	public $ruleset;
	public $frames;
	public $type = 'DetachedRuleset';

	function __construct( $ruleset, $frames = null ){
		$this->ruleset = $ruleset;
		$this->frames = $frames;
	}

	function accept($visitor) {
		$this->ruleset = $visitor->visitObj($this->ruleset);
	}

	function compile($env){
		if( $this->frames ){
			$frames = $this->frames;
		}else{
			$frames = $env->frames;
		}
		return new Less_Tree_DetachedRuleset($this->ruleset, $frames);
	}

	function callEval($env) {
		if( $this->frames ){
			return $this->ruleset->compile( $env->copyEvalEnv( array_merge($this->frames,$env->frames) ) );
		}
		return $this->ruleset->compile( $env );
	}
}



/**
 * Dimension
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Dimension extends Less_Tree{

	public $value;
	public $unit;
	public $type = 'Dimension';

	public function __construct($value, $unit = null){
		$this->value = floatval($value);

		if( $unit && ($unit instanceof Less_Tree_Unit) ){
			$this->unit = $unit;
		}elseif( $unit ){
			$this->unit = new Less_Tree_Unit( array($unit) );
		}else{
			$this->unit = new Less_Tree_Unit( );
		}
	}

	function accept( $visitor ){
		$this->unit = $visitor->visitObj( $this->unit );
	}

	public function compile(){
		return $this;
	}

	public function toColor() {
		return new Less_Tree_Color(array($this->value, $this->value, $this->value));
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){

		if( Less_Parser::$options['strictUnits'] && !$this->unit->isSingular() ){
			throw new Less_Exception_Compiler("Multiple units in dimension. Correct the units or use the unit function. Bad unit: ".$this->unit->toString());
		}

		$value = Less_Functions::fround( $this->value );
		$strValue = (string)$value;

		if( $value !== 0 && $value < 0.000001 && $value > -0.000001 ){
			// would be output 1e-6 etc.
			$strValue = number_format($strValue,10);
			$strValue = preg_replace('/\.?0+$/','', $strValue);
		}

		if( Less_Parser::$options['compress'] ){
			// Zero values doesn't need a unit
			if( $value === 0 && $this->unit->isLength() ){
				$output->add( $strValue );
				return $strValue;
			}

			// Float values doesn't need a leading zero
			if( $value > 0 && $value < 1 && $strValue[0] === '0' ){
				$strValue = substr($strValue,1);
			}
		}

		$output->add( $strValue );
		$this->unit->genCSS( $output );
	}

	public function __toString(){
		return $this->toCSS();
	}

	// In an operation between two Dimensions,
	// we default to the first Dimension's unit,
	// so `1px + 2em` will yield `3px`.

	/**
	 * @param string $op
	 */
	public function operate( $op, $other){

		$value = Less_Functions::operate( $op, $this->value, $other->value);
		$unit = clone $this->unit;

		if( $op === '+' || $op === '-' ){

			if( !$unit->numerator && !$unit->denominator ){
				$unit->numerator = $other->unit->numerator;
				$unit->denominator = $other->unit->denominator;
			}elseif( !$other->unit->numerator && !$other->unit->denominator ){
				// do nothing
			}else{
				$other = $other->convertTo( $this->unit->usedUnits());

				if( Less_Parser::$options['strictUnits'] && $other->unit->toString() !== $unit->toCSS() ){
					throw new Less_Exception_Compiler("Incompatible units. Change the units or use the unit function. Bad units: '".$unit->toString() . "' and ".$other->unit->toString()+"'.");
				}

				$value = Less_Functions::operate( $op, $this->value, $other->value);
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

			if( $this->unit->isEmpty() || $other->unit->isEmpty() ){
				$a = $this;
				$b = $other;
			} else {
				$a = $this->unify();
				$b = $other->unify();
				if( $a->unit->compare($b->unit) !== 0 ){
					return -1;
				}
			}
			$aValue = $a->value;
			$bValue = $b->value;

			if ($bValue > $aValue) {
				return -1;
			} elseif ($bValue < $aValue) {
				return 1;
			} else {
				return 0;
			}
		} else {
			return -1;
		}
	}

	function unify() {
		return $this->convertTo(array('length'=> 'px', 'duration'=> 's', 'angle' => 'rad' ));
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
			foreach($unit->numerator as $i => $atomicUnit){
				$atomicUnit = $unit->numerator[$i];
				if( !isset($group[$atomicUnit]) ){
					continue;
				}

				$value = $value * ($group[$atomicUnit] / $group[$targetUnit]);

				$unit->numerator[$i] = $targetUnit;
			}

			//denominator
			foreach($unit->denominator as $i => $atomicUnit){
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


/**
 * Directive
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Directive extends Less_Tree{

	public $name;
	public $value;
	public $rules;
	public $index;
	public $isReferenced;
	public $currentFileInfo;
	public $debugInfo;
	public $type = 'Directive';

	public function __construct($name, $value = null, $rules, $index = null, $currentFileInfo = null, $debugInfo = null ){
		$this->name = $name;
		$this->value = $value;
		if( $rules ){
			$this->rules = $rules;
			$this->rules->allowImports = true;
		}

		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
		$this->debugInfo = $debugInfo;
	}


	function accept( $visitor ){
		if( $this->rules ){
			$this->rules = $visitor->visitObj( $this->rules );
		}
		if( $this->value ){
			$this->value = $visitor->visitObj( $this->value );
		}
	}


	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$value = $this->value;
		$rules = $this->rules;
		$output->add( $this->name, $this->currentFileInfo, $this->index );
		if( $this->value ){
			$output->add(' ');
			$this->value->genCSS($output);
		}
		if( $this->rules ){
			Less_Tree::outputRuleset( $output, array($this->rules));
		} else {
			$output->add(';');
		}
	}

	public function compile($env){

		$value = $this->value;
		$rules = $this->rules;
		if( $value ){
			$value = $value->compile($env);
		}

		if( $rules ){
			$rules = $rules->compile($env);
			$rules->root = true;
		}

		return new Less_Tree_Directive( $this->name, $value, $rules, $this->index, $this->currentFileInfo, $this->debugInfo );
	}


	public function variable($name){
		if( $this->rules ){
			return $this->rules->variable($name);
		}
	}

	public function find($selector){
		if( $this->rules ){
			return $this->rules->find($selector, $this);
		}
	}

	//rulesets: function () { if (this.rules) return tree.Ruleset.prototype.rulesets.apply(this.rules); },

	public function markReferenced(){
		$this->isReferenced = true;
		if( $this->rules ){
			Less_Tree::ReferencedArray($this->rules->rules);
		}
	}

}


/**
 * Element
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Element extends Less_Tree{

	public $combinator = '';
	public $value = '';
	public $index;
	public $currentFileInfo;
	public $type = 'Element';

	public $value_is_object = false;

	public function __construct($combinator, $value, $index = null, $currentFileInfo = null ){

		$this->value = $value;
		$this->value_is_object = is_object($value);

		if( $combinator ){
			$this->combinator = $combinator;
		}

		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	function accept( $visitor ){
		if( $this->value_is_object ){ //object or string
			$this->value = $visitor->visitObj( $this->value );
		}
	}

	public function compile($env){

		if( Less_Environment::$mixin_stack ){
			return new Less_Tree_Element($this->combinator, ($this->value_is_object ? $this->value->compile($env) : $this->value), $this->index, $this->currentFileInfo );
		}

		if( $this->value_is_object ){
			$this->value = $this->value->compile($env);
		}

		return $this;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){
		$output->add( $this->toCSS(), $this->currentFileInfo, $this->index );
	}

	public function toCSS(){

		if( $this->value_is_object ){
			$value = $this->value->toCSS();
		}else{
			$value = $this->value;
		}


		if( $value === '' && $this->combinator && $this->combinator === '&' ){
			return '';
		}


		return Less_Environment::$_outputMap[$this->combinator] . $value;
	}

}


/**
 * Expression
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Expression extends Less_Tree{

	public $value = array();
	public $parens = false;
	public $parensInOp = false;
	public $type = 'Expression';

	public function __construct( $value, $parens = null ){
		$this->value = $value;
		$this->parens = $parens;
	}

	function accept( $visitor ){
		$this->value = $visitor->visitArray( $this->value );
	}

	public function compile($env) {

		$doubleParen = false;

		if( $this->parens && !$this->parensInOp ){
			Less_Environment::$parensStack++;
		}

		$returnValue = null;
		if( $this->value ){

			$count = count($this->value);

			if( $count > 1 ){

				$ret = array();
				foreach($this->value as $e){
					$ret[] = $e->compile($env);
				}
				$returnValue = new Less_Tree_Expression($ret);

			}else{

				if( ($this->value[0] instanceof Less_Tree_Expression) && $this->value[0]->parens && !$this->value[0]->parensInOp ){
					$doubleParen = true;
				}

				$returnValue = $this->value[0]->compile($env);
			}

		} else {
			$returnValue = $this;
		}

		if( $this->parens ){
			if( !$this->parensInOp ){
				Less_Environment::$parensStack--;

			}elseif( !Less_Environment::isMathOn() && !$doubleParen ){
				$returnValue = new Less_Tree_Paren($returnValue);

			}
		}
		return $returnValue;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$val_len = count($this->value);
		for( $i = 0; $i < $val_len; $i++ ){
			$this->value[$i]->genCSS( $output );
			if( $i + 1 < $val_len ){
				$output->add( ' ' );
			}
		}
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


/**
 * Extend
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Extend extends Less_Tree{

	public $selector;
	public $option;
	public $index;
	public $selfSelectors = array();
	public $allowBefore;
	public $allowAfter;
	public $firstExtendOnThisSelectorPath;
	public $type = 'Extend';
	public $ruleset;


	public $object_id;
	public $parent_ids = array();

	/**
	 * @param integer $index
	 */
	function __construct($selector, $option, $index){
		static $i = 0;
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

		$this->object_id = $i++;
		$this->parent_ids = array($this->object_id);
	}

	function accept( $visitor ){
		$this->selector = $visitor->visitObj( $this->selector );
	}

	function compile( $env ){
		Less_Parser::$has_extends = true;
		$this->selector = $this->selector->compile($env);
		return $this;
		//return new Less_Tree_Extend( $this->selector->compile($env), $this->option, $this->index);
	}

	function findSelfSelectors( $selectors ){
		$selfElements = array();


		for( $i = 0, $selectors_len = count($selectors); $i < $selectors_len; $i++ ){
			$selectorElements = $selectors[$i]->elements;
			// duplicate the logic in genCSS function inside the selector node.
			// future TODO - move both logics into the selector joiner visitor
			if( $i && $selectorElements && $selectorElements[0]->combinator === "") {
				$selectorElements[0]->combinator = ' ';
			}
			$selfElements = array_merge( $selfElements, $selectors[$i]->elements );
		}

		$this->selfSelectors = array(new Less_Tree_Selector($selfElements));
	}

}

/**
 * CSS @import node
 *
 * The general strategy here is that we don't want to wait
 * for the parsing to be completed, before we start importing
 * the file. That's because in the context of a browser,
 * most of the time will be spent waiting for the server to respond.
 *
 * On creation, we push the import path to our import queue, though
 * `import,push`, we also pass it a callback, which it'll call once
 * the file has been fetched, and parsed.
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Import extends Less_Tree{

	public $options;
	public $index;
	public $path;
	public $features;
	public $currentFileInfo;
	public $css;
	public $skip;
	public $root;
	public $type = 'Import';

	function __construct($path, $features, $options, $index, $currentFileInfo = null ){
		$this->options = $options;
		$this->index = $index;
		$this->path = $path;
		$this->features = $features;
		$this->currentFileInfo = $currentFileInfo;

		if( is_array($options) ){
			$this->options += array('inline'=>false);

			if( isset($this->options['less']) || $this->options['inline'] ){
				$this->css = !isset($this->options['less']) || !$this->options['less'] || $this->options['inline'];
			} else {
				$pathValue = $this->getPath();
				if( $pathValue && preg_match('/css([\?;].*)?$/',$pathValue) ){
					$this->css = true;
				}
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

	function accept($visitor){

		if( $this->features ){
			$this->features = $visitor->visitObj($this->features);
		}
		$this->path = $visitor->visitObj($this->path);

		if( !$this->options['inline'] && $this->root ){
			$this->root = $visitor->visit($this->root);
		}
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		if( $this->css ){

			$output->add( '@import ', $this->currentFileInfo, $this->index );

			$this->path->genCSS( $output );
			if( $this->features ){
				$output->add( ' ' );
				$this->features->genCSS( $output );
			}
			$output->add( ';' );
		}
	}

	function toCSS(){
		$features = $this->features ? ' ' . $this->features->toCSS() : '';

		if ($this->css) {
			return "@import " . $this->path->toCSS() . $features . ";\n";
		} else {
			return "";
		}
	}

	/**
	 * @return string
	 */
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
		$rootpath = '';
		if( $this->currentFileInfo && $this->currentFileInfo['rootpath'] ){
			$rootpath = $this->currentFileInfo['rootpath'];
		}


		if( !($path instanceof Less_Tree_URL) ){
			if( $rootpath ){
				$pathValue = $path->value;
				// Add the base path if the import is relative
				if( $pathValue && Less_Environment::isPathRelative($pathValue) ){
					$path->value = $this->currentFileInfo['uri_root'].$pathValue;
				}
			}
			$path->value = Less_Environment::normalizePath($path->value);
		}



		return $path;
	}

	function compile( $env ){

		$evald = $this->compileForImport($env);

		//get path & uri
		$path_and_uri = null;
		if( is_callable(Less_Parser::$options['import_callback']) ){
			$path_and_uri = call_user_func(Less_Parser::$options['import_callback'],$evald);
		}

		if( !$path_and_uri ){
			$path_and_uri = $evald->PathAndUri();
		}

		if( $path_and_uri ){
			list($full_path, $uri) = $path_and_uri;
		}else{
			$full_path = $uri = $evald->getPath();
		}


		//import once
		if( $evald->skip( $full_path, $env) ){
			return array();
		}

		if( $this->options['inline'] ){
			//todo needs to reference css file not import
			//$contents = new Less_Tree_Anonymous($this->root, 0, array('filename'=>$this->importedFilename), true );

			Less_Parser::AddParsedFile($full_path);
			$contents = new Less_Tree_Anonymous( file_get_contents($full_path), 0, array(), true );

			if( $this->features ){
				return new Less_Tree_Media( array($contents), $this->features->value );
			}

			return array( $contents );
		}


		// css ?
		if( $evald->css ){
			$features = ( $evald->features ? $evald->features->compile($env) : null );
			return new Less_Tree_Import( $this->compilePath( $env), $features, $this->options, $this->index);
		}


		return $this->ParseImport( $full_path, $uri, $env );
	}


	/**
	 * Using the import directories, get the full absolute path and uri of the import
	 *
	 * @param Less_Tree_Import $evald
	 */
	function PathAndUri(){

		$evald_path = $this->getPath();

		if( $evald_path ){

			$import_dirs = array();

			if( Less_Environment::isPathRelative($evald_path) ){
				//if the path is relative, the file should be in the current directory
				$import_dirs[ $this->currentFileInfo['currentDirectory'] ] = $this->currentFileInfo['uri_root'];

			}else{
				//otherwise, the file should be relative to the server root
				$import_dirs[ $this->currentFileInfo['entryPath'] ] = $this->currentFileInfo['entryUri'];

				//if the user supplied entryPath isn't the actual root
				$import_dirs[ $_SERVER['DOCUMENT_ROOT'] ] = '';

			}

			// always look in user supplied import directories
			$import_dirs = array_merge( $import_dirs, Less_Parser::$options['import_dirs'] );


			foreach( $import_dirs as $rootpath => $rooturi){
				if( is_callable($rooturi) ){
					list($path, $uri) = call_user_func($rooturi, $evald_path);
					if( is_string($path) ){
						$full_path = $path;
						return array( $full_path, $uri );
					}
				}else{
					$path = rtrim($rootpath,'/\\').'/'.ltrim($evald_path,'/\\');

					if( file_exists($path) ){
						$full_path = Less_Environment::normalizePath($path);
						$uri = Less_Environment::normalizePath(dirname($rooturi.$evald_path));
						return array( $full_path, $uri );
					}
				}
			}
		}
	}


	/**
	 * Parse the import url and return the rules
	 *
	 * @return Less_Tree_Media|array
	 */
	function ParseImport( $full_path, $uri, $env ){

		$import_env = clone $env;
		if( (isset($this->options['reference']) && $this->options['reference']) || isset($this->currentFileInfo['reference']) ){
			$import_env->currentFileInfo['reference'] = true;
		}

		if( (isset($this->options['multiple']) && $this->options['multiple']) ){
			$import_env->importMultiple = true;
		}

		$parser = new Less_Parser($import_env);
		$root = $parser->parseFile($full_path, $uri, true);


		$ruleset = new Less_Tree_Ruleset(array(), $root->rules );
		$ruleset->evalImports($import_env);

		return $this->features ? new Less_Tree_Media($ruleset->rules, $this->features->value) : $ruleset->rules;
	}


	/**
	 * Should the import be skipped?
	 *
	 * @return boolean|null
	 */
	private function Skip($path, $env){

		$path = realpath($path);

		if( $path && Less_Parser::FileParsed($path) ){

			if( isset($this->currentFileInfo['reference']) ){
				return true;
			}

			return !isset($this->options['multiple']) && !$env->importMultiple;
		}

	}
}



/**
 * Javascript
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Javascript extends Less_Tree{

	public $type = 'Javascript';
	public $escaped;
	public $expression;
	public $index;

	/**
	 * @param boolean $index
	 * @param boolean $escaped
	 */
	public function __construct($string, $index, $escaped){
		$this->escaped = $escaped;
		$this->expression = $string;
		$this->index = $index;
	}

	public function compile(){
		return new Less_Tree_Anonymous('/* Sorry, can not do JavaScript evaluation in PHP... :( */');
	}

}


/**
 * Keyword
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Keyword extends Less_Tree{

	public $value;
	public $type = 'Keyword';

	/**
	 * @param string $value
	 */
	public function __construct($value){
		$this->value = $value;
	}

	public function compile(){
		return $this;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){

		if( $this->value === '%') {
			throw new Less_Exception_Compiler("Invalid % without number");
		}

		$output->add( $this->value );
	}

	public function compare($other) {
		if ($other instanceof Less_Tree_Keyword) {
			return $other->value === $this->value ? 0 : 1;
		} else {
			return -1;
		}
	}
}


/**
 * Media
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Media extends Less_Tree{

	public $features;
	public $rules;
	public $index;
	public $currentFileInfo;
	public $isReferenced;
	public $type = 'Media';

	public function __construct($value = array(), $features = array(), $index = null, $currentFileInfo = null ){

		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;

		$selectors = $this->emptySelectors();

		$this->features = new Less_Tree_Value($features);

		$this->rules = array(new Less_Tree_Ruleset($selectors, $value));
		$this->rules[0]->allowImports = true;
	}

	function accept( $visitor ){
		$this->features = $visitor->visitObj($this->features);
		$this->rules = $visitor->visitArray($this->rules);
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){

		$output->add( '@media ', $this->currentFileInfo, $this->index );
		$this->features->genCSS( $output );
		Less_Tree::outputRuleset( $output, $this->rules);

	}

	public function compile($env) {

		$media = new Less_Tree_Media(array(), array(), $this->index, $this->currentFileInfo );

		$strictMathBypass = false;
		if( Less_Parser::$options['strictMath'] === false) {
			$strictMathBypass = true;
			Less_Parser::$options['strictMath'] = true;
		}

		$media->features = $this->features->compile($env);

		if( $strictMathBypass ){
			Less_Parser::$options['strictMath'] = false;
		}

		$env->mediaPath[] = $media;
		$env->mediaBlocks[] = $media;

		array_unshift($env->frames, $this->rules[0]);
		$media->rules = array($this->rules[0]->compile($env));
		array_shift($env->frames);

		array_pop($env->mediaPath);

		return !$env->mediaPath ? $media->compileTop($env) : $media->compileNested($env);
	}

	public function variable($name) {
		return $this->rules[0]->variable($name);
	}

	public function find($selector) {
		return $this->rules[0]->find($selector, $this);
	}

	public function emptySelectors(){
		$el = new Less_Tree_Element('','&', $this->index, $this->currentFileInfo );
		$sels = array( new Less_Tree_Selector(array($el), array(), null, $this->index, $this->currentFileInfo) );
		$sels[0]->mediaEmpty = true;
		return $sels;
	}

	public function markReferenced(){
		$this->rules[0]->markReferenced();
		$this->isReferenced = true;
		Less_Tree::ReferencedArray($this->rules[0]->rules);
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

		if( !$selectors) return;

		$this->rules = array(new Less_Tree_Ruleset( $selectors, array($this->rules[0])));
	}

}


/**
 * A simple css name-value pair
 * ex: width:100px;
 *
 * In bootstrap, there are about 600-1,000 simple name-value pairs (depending on how forgiving the match is) -vs- 6,020 dynamic rules (Less_Tree_Rule)
 * Using the name-value object can speed up bootstrap compilation slightly, but it breaks color keyword interpretation: color:red -> color:#FF0000;
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_NameValue extends Less_Tree{

	public $name;
	public $value;
	public $index;
	public $currentFileInfo;
	public $type = 'NameValue';

	public function __construct($name, $value = null, $index = null, $currentFileInfo = null ){
		$this->name = $name;
		$this->value = $value;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	function genCSS( $output ){

		$output->add(
			$this->name
			. Less_Environment::$_outputMap[': ']
			. $this->value
			. (((Less_Environment::$lastRule && Less_Parser::$options['compress'])) ? "" : ";")
			, $this->currentFileInfo, $this->index);
	}

	public function compile ($env){
		return $this;
	}
}


/**
 * Negative
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Negative extends Less_Tree{

	public $value;
	public $type = 'Negative';

	function __construct($node){
		$this->value = $node;
	}

	//function accept($visitor) {
	//	$this->value = $visitor->visit($this->value);
	//}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$output->add( '-' );
		$this->value->genCSS( $output );
	}

	function compile($env) {
		if( Less_Environment::isMathOn() ){
			$ret = new Less_Tree_Operation('*', array( new Less_Tree_Dimension(-1), $this->value ) );
			return $ret->compile($env);
		}
		return new Less_Tree_Negative( $this->value->compile($env) );
	}
}

/**
 * Operation
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Operation extends Less_Tree{

	public $op;
	public $operands;
	public $isSpaced;
	public $type = 'Operation';

	/**
	 * @param string $op
	 */
	public function __construct($op, $operands, $isSpaced = false){
		$this->op = trim($op);
		$this->operands = $operands;
		$this->isSpaced = $isSpaced;
	}

	function accept($visitor) {
		$this->operands = $visitor->visitArray($this->operands);
	}

	public function compile($env){
		$a = $this->operands[0]->compile($env);
		$b = $this->operands[1]->compile($env);


		if( Less_Environment::isMathOn() ){

			if( $a instanceof Less_Tree_Dimension && $b instanceof Less_Tree_Color ){
				$a = $a->toColor();

			}elseif( $b instanceof Less_Tree_Dimension && $a instanceof Less_Tree_Color ){
				$b = $b->toColor();

			}

			if( !method_exists($a,'operate') ){
				throw new Less_Exception_Compiler("Operation on an invalid type");
			}

			return $a->operate( $this->op, $b);
		}

		return new Less_Tree_Operation($this->op, array($a, $b), $this->isSpaced );
	}


	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$this->operands[0]->genCSS( $output );
		if( $this->isSpaced ){
			$output->add( " " );
		}
		$output->add( $this->op );
		if( $this->isSpaced ){
			$output->add( ' ' );
		}
		$this->operands[1]->genCSS( $output );
	}

}


/**
 * Paren
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Paren extends Less_Tree{

	public $value;
	public $type = 'Paren';

	public function __construct($value) {
		$this->value = $value;
	}

	function accept($visitor){
		$this->value = $visitor->visitObj($this->value);
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$output->add( '(' );
		$this->value->genCSS( $output );
		$output->add( ')' );
	}

	public function compile($env) {
		return new Less_Tree_Paren($this->value->compile($env));
	}

}


/**
 * Quoted
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Quoted extends Less_Tree{
	public $escaped;
	public $value;
	public $quote;
	public $index;
	public $currentFileInfo;
	public $type = 'Quoted';

	/**
	 * @param string $str
	 */
	public function __construct($str, $content = '', $escaped = false, $index = false, $currentFileInfo = null ){
		$this->escaped = $escaped;
		$this->value = $content;
		if( $str ){
			$this->quote = $str[0];
		}
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){
		if( !$this->escaped ){
			$output->add( $this->quote, $this->currentFileInfo, $this->index );
		}
		$output->add( $this->value );
		if( !$this->escaped ){
			$output->add( $this->quote );
		}
	}

	public function compile($env){

		$value = $this->value;
		if( preg_match_all('/`([^`]+)`/', $this->value, $matches) ){
			foreach($matches as $i => $match){
				$js = new Less_Tree_JavaScript($matches[1], $this->index, true);
				$js = $js->compile()->value;
				$value = str_replace($matches[0][$i], $js, $value);
			}
		}

		if( preg_match_all('/@\{([\w-]+)\}/',$value,$matches) ){
			foreach($matches[1] as $i => $match){
				$v = new Less_Tree_Variable('@' . $match, $this->index, $this->currentFileInfo );
				$v = $v->compile($env);
				$v = ($v instanceof Less_Tree_Quoted) ? $v->value : $v->toCSS();
				$value = str_replace($matches[0][$i], $v, $value);
			}
		}

		return new Less_Tree_Quoted($this->quote . $value . $this->quote, $value, $this->escaped, $this->index, $this->currentFileInfo);
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


/**
 * Rule
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Rule extends Less_Tree{

	public $name;
	public $value;
	public $important;
	public $merge;
	public $index;
	public $inline;
	public $variable;
	public $currentFileInfo;
	public $type = 'Rule';

	/**
	 * @param string $important
	 */
	public function __construct($name, $value = null, $important = null, $merge = null, $index = null, $currentFileInfo = null,  $inline = false){
		$this->name = $name;
		$this->value = ($value instanceof Less_Tree_Value || $value instanceof Less_Tree_Ruleset) ? $value : new Less_Tree_Value(array($value));
		$this->important = $important ? ' ' . trim($important) : '';
		$this->merge = $merge;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
		$this->inline = $inline;
		$this->variable = ( is_string($name) && $name[0] === '@');
	}

	function accept($visitor) {
		$this->value = $visitor->visitObj( $this->value );
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){

		$output->add( $this->name . Less_Environment::$_outputMap[': '], $this->currentFileInfo, $this->index);
		try{
			$this->value->genCSS( $output);

		}catch( Less_Exception_Parser $e ){
			$e->index = $this->index;
			$e->currentFile = $this->currentFileInfo;
			throw $e;
		}
		$output->add( $this->important . (($this->inline || (Less_Environment::$lastRule && Less_Parser::$options['compress'])) ? "" : ";"), $this->currentFileInfo, $this->index);
	}

	public function compile ($env){

		$name = $this->name;
		if( is_array($name) ){
			// expand 'primitive' name directly to get
			// things faster (~10% for benchmark.less):
			if( count($name) === 1 && $name[0] instanceof Less_Tree_Keyword ){
				$name = $name[0]->value;
			}else{
				$name = $this->CompileName($env,$name);
			}
		}

		$strictMathBypass = Less_Parser::$options['strictMath'];
		if( $name === "font" && !Less_Parser::$options['strictMath'] ){
			Less_Parser::$options['strictMath'] = true;
		}

		try {
			$evaldValue = $this->value->compile($env);

			if( !$this->variable && $evaldValue->type === "DetachedRuleset") {
				throw new Less_Exception_Compiler("Rulesets cannot be evaluated on a property.", null, $this->index, $this->currentFileInfo);
			}

			if( Less_Environment::$mixin_stack ){
				$return = new Less_Tree_Rule($name, $evaldValue, $this->important, $this->merge, $this->index, $this->currentFileInfo, $this->inline);
			}else{
				$this->name = $name;
				$this->value = $evaldValue;
				$return = $this;
			}

		}catch( Less_Exception_Parser $e ){
			if( !is_numeric($e->index) ){
				$e->index = $this->index;
				$e->currentFile = $this->currentFileInfo;
			}
			throw $e;
		}

		Less_Parser::$options['strictMath'] = $strictMathBypass;

		return $return;
	}


	function CompileName( $env, $name ){
		$output = new Less_Output();
		foreach($name as $n){
			$n->compile($env)->genCSS($output);
		}
		return $output->toString();
	}

	function makeImportant(){
		return new Less_Tree_Rule($this->name, $this->value, '!important', $this->merge, $this->index, $this->currentFileInfo, $this->inline);
	}

}


/**
 * Ruleset
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Ruleset extends Less_Tree{

	protected $lookups;
	public $_variables;
	public $_rulesets;

	public $strictImports;

	public $selectors;
	public $rules;
	public $root;
	public $allowImports;
	public $paths;
	public $firstRoot;
	public $type = 'Ruleset';
	public $multiMedia;
	public $allExtends;

	var $ruleset_id;
	var $originalRuleset;

	var $first_oelements;

	public function SetRulesetIndex(){
		$this->ruleset_id = Less_Parser::$next_id++;
		$this->originalRuleset = $this->ruleset_id;

		if( $this->selectors ){
			foreach($this->selectors as $sel){
				if( $sel->_oelements ){
					$this->first_oelements[$sel->_oelements[0]] = true;
				}
			}
		}
	}

	public function __construct($selectors, $rules, $strictImports = null){
		$this->selectors = $selectors;
		$this->rules = $rules;
		$this->lookups = array();
		$this->strictImports = $strictImports;
		$this->SetRulesetIndex();
	}

	function accept( $visitor ){
		if( $this->paths ){
			$paths_len = count($this->paths);
			for($i = 0,$paths_len; $i < $paths_len; $i++ ){
				$this->paths[$i] = $visitor->visitArray($this->paths[$i]);
			}
		}elseif( $this->selectors ){
			$this->selectors = $visitor->visitArray($this->selectors);
		}

		if( $this->rules ){
			$this->rules = $visitor->visitArray($this->rules);
		}
	}

	public function compile($env){

		$ruleset = $this->PrepareRuleset($env);


		// Store the frames around mixin definitions,
		// so they can be evaluated like closures when the time comes.
		$rsRuleCnt = count($ruleset->rules);
		for( $i = 0; $i < $rsRuleCnt; $i++ ){
			if( $ruleset->rules[$i] instanceof Less_Tree_Mixin_Definition || $ruleset->rules[$i] instanceof Less_Tree_DetachedRuleset ){
				$ruleset->rules[$i] = $ruleset->rules[$i]->compile($env);
			}
		}

		$mediaBlockCount = 0;
		if( $env instanceof Less_Environment ){
			$mediaBlockCount = count($env->mediaBlocks);
		}

		// Evaluate mixin calls.
		$this->EvalMixinCalls( $ruleset, $env, $rsRuleCnt );


		// Evaluate everything else
		for( $i=0; $i<$rsRuleCnt; $i++ ){
			if(! ($ruleset->rules[$i] instanceof Less_Tree_Mixin_Definition || $ruleset->rules[$i] instanceof Less_Tree_DetachedRuleset) ){
				$ruleset->rules[$i] = $ruleset->rules[$i]->compile($env);
			}
		}

		// Evaluate everything else
		for( $i=0; $i<$rsRuleCnt; $i++ ){
			$rule = $ruleset->rules[$i];

			// for rulesets, check if it is a css guard and can be removed
			if( $rule instanceof Less_Tree_Ruleset && $rule->selectors && count($rule->selectors) === 1 ){

				// check if it can be folded in (e.g. & where)
				if( $rule->selectors[0]->isJustParentSelector() ){
					array_splice($ruleset->rules,$i--,1);
					$rsRuleCnt--;

					for($j = 0; $j < count($rule->rules); $j++ ){
						$subRule = $rule->rules[$j];
						if( !($subRule instanceof Less_Tree_Rule) || !$subRule->variable ){
							array_splice($ruleset->rules, ++$i, 0, array($subRule));
							$rsRuleCnt++;
						}
					}

				}
			}
		}


		// Pop the stack
		$env->shiftFrame();

		if ($mediaBlockCount) {
			$len = count($env->mediaBlocks);
			for($i = $mediaBlockCount; $i < $len; $i++ ){
				$env->mediaBlocks[$i]->bubbleSelectors($ruleset->selectors);
			}
		}

		return $ruleset;
	}

	/**
	 * Compile Less_Tree_Mixin_Call objects
	 *
	 * @param Less_Tree_Ruleset $ruleset
	 * @param integer $rsRuleCnt
	 */
	private function EvalMixinCalls( $ruleset, $env, &$rsRuleCnt ){
		for($i=0; $i < $rsRuleCnt; $i++){
			$rule = $ruleset->rules[$i];

			if( $rule instanceof Less_Tree_Mixin_Call ){
				$rule = $rule->compile($env);

				$temp = array();
				foreach($rule as $r){
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
				$temp_count = count($temp)-1;
				array_splice($ruleset->rules, $i, 1, $temp);
				$rsRuleCnt += $temp_count;
				$i += $temp_count;
				$ruleset->resetCache();

			}elseif( $rule instanceof Less_Tree_RulesetCall ){

				$rule = $rule->compile($env);
				$rules = array();
				foreach($rule->rules as $r){
					if( ($r instanceof Less_Tree_Rule) && $r->variable ){
						continue;
					}
					$rules[] = $r;
				}

				array_splice($ruleset->rules, $i, 1, $rules);
				$temp_count = count($rules);
				$rsRuleCnt += $temp_count - 1;
				$i += $temp_count-1;
				$ruleset->resetCache();
			}

		}
	}


	/**
	 * Compile the selectors and create a new ruleset object for the compile() method
	 *
	 */
	private function PrepareRuleset($env){

		$hasOnePassingSelector = false;
		$selectors = array();
		if( $this->selectors ){
			Less_Tree_DefaultFunc::error("it is currently only allowed in parametric mixin guards,");

			foreach($this->selectors as $s){
				$selector = $s->compile($env);
				$selectors[] = $selector;
				if( $selector->evaldCondition ){
					$hasOnePassingSelector = true;
				}
			}

			Less_Tree_DefaultFunc::reset();
		} else {
			$hasOnePassingSelector = true;
		}

		if( $this->rules && $hasOnePassingSelector ){
			$rules = $this->rules;
		}else{
			$rules = array();
		}

		$ruleset = new Less_Tree_Ruleset($selectors, $rules, $this->strictImports);

		$ruleset->originalRuleset = $this->ruleset_id;

		$ruleset->root = $this->root;
		$ruleset->firstRoot = $this->firstRoot;
		$ruleset->allowImports = $this->allowImports;


		// push the current ruleset to the frames stack
		$env->unshiftFrame($ruleset);


		// Evaluate imports
		if( $ruleset->root || $ruleset->allowImports || !$ruleset->strictImports ){
			$ruleset->evalImports($env);
		}

		return $ruleset;
	}

	function evalImports($env) {

		$rules_len = count($this->rules);
		for($i=0; $i < $rules_len; $i++){
			$rule = $this->rules[$i];

			if( $rule instanceof Less_Tree_Import ){
				$rules = $rule->compile($env);
				if( is_array($rules) ){
					array_splice($this->rules, $i, 1, $rules);
					$temp_count = count($rules)-1;
					$i += $temp_count;
					$rules_len += $temp_count;
				}else{
					array_splice($this->rules, $i, 1, array($rules));
				}

				$this->resetCache();
			}
		}
	}

	function makeImportant(){

		$important_rules = array();
		foreach($this->rules as $rule){
			if( $rule instanceof Less_Tree_Rule || $rule instanceof Less_Tree_Ruleset ){
				$important_rules[] = $rule->makeImportant();
			}else{
				$important_rules[] = $rule;
			}
		}

		return new Less_Tree_Ruleset($this->selectors, $important_rules, $this->strictImports );
	}

	public function matchArgs($args){
		return !$args;
	}

	// lets you call a css selector with a guard
	public function matchCondition( $args, $env ){
		$lastSelector = end($this->selectors);

		if( !$lastSelector->evaldCondition ){
			return false;
		}
		if( $lastSelector->condition && !$lastSelector->condition->compile( $env->copyEvalEnv( $env->frames ) ) ){
			return false;
		}
		return true;
	}

	function resetCache(){
		$this->_rulesets = null;
		$this->_variables = null;
		$this->lookups = array();
	}

	public function variables(){
		$this->_variables = array();
		foreach( $this->rules as $r){
			if ($r instanceof Less_Tree_Rule && $r->variable === true) {
				$this->_variables[$r->name] = $r;
			}
		}
	}

	public function variable($name){

		if( is_null($this->_variables) ){
			$this->variables();
		}
		return isset($this->_variables[$name]) ? $this->_variables[$name] : null;
	}

	public function find( $selector, $self = null ){

		$key = implode(' ',$selector->_oelements);

		if( !isset($this->lookups[$key]) ){

			if( !$self ){
				$self = $this->ruleset_id;
			}

			$this->lookups[$key] = array();

			$first_oelement = $selector->_oelements[0];

			foreach($this->rules as $rule){
				if( $rule instanceof Less_Tree_Ruleset && $rule->ruleset_id != $self ){

					if( isset($rule->first_oelements[$first_oelement]) ){

						foreach( $rule->selectors as $ruleSelector ){
							$match = $selector->match($ruleSelector);
							if( $match ){
								if( $selector->elements_len > $match ){
									$this->lookups[$key] = array_merge($this->lookups[$key], $rule->find( new Less_Tree_Selector(array_slice($selector->elements, $match)), $self));
								} else {
									$this->lookups[$key][] = $rule;
								}
								break;
							}
						}
					}
				}
			}
		}

		return $this->lookups[$key];
	}


	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){

		if( !$this->root ){
			Less_Environment::$tabLevel++;
		}

		$tabRuleStr = $tabSetStr = '';
		if( !Less_Parser::$options['compress'] ){
			if( Less_Environment::$tabLevel ){
				$tabRuleStr = "\n".str_repeat( '  ' , Less_Environment::$tabLevel );
				$tabSetStr = "\n".str_repeat( '  ' , Less_Environment::$tabLevel-1 );
			}else{
				$tabSetStr = $tabRuleStr = "\n";
			}
		}


		$ruleNodes = array();
		$rulesetNodes = array();
		foreach($this->rules as $rule){

			$class = get_class($rule);
			if( ($class === 'Less_Tree_Media') || ($class === 'Less_Tree_Directive') || ($this->root && $class === 'Less_Tree_Comment') || ($class === 'Less_Tree_Ruleset' && $rule->rules) ){
				$rulesetNodes[] = $rule;
			}else{
				$ruleNodes[] = $rule;
			}
		}

		// If this is the root node, we don't render
		// a selector, or {}.
		if( !$this->root ){

			/*
			debugInfo = tree.debugInfo(env, this, tabSetStr);

			if (debugInfo) {
				output.add(debugInfo);
				output.add(tabSetStr);
			}
			*/

			$paths_len = count($this->paths);
			for( $i = 0; $i < $paths_len; $i++ ){
				$path = $this->paths[$i];
				$firstSelector = true;

				foreach($path as $p){
					$p->genCSS( $output, $firstSelector );
					$firstSelector = false;
				}

				if( $i + 1 < $paths_len ){
					$output->add( ',' . $tabSetStr );
				}
			}

			$output->add( (Less_Parser::$options['compress'] ? '{' : " {") . $tabRuleStr );
		}

		// Compile rules and rulesets
		$ruleNodes_len = count($ruleNodes);
		$rulesetNodes_len = count($rulesetNodes);
		for( $i = 0; $i < $ruleNodes_len; $i++ ){
			$rule = $ruleNodes[$i];

			// @page{ directive ends up with root elements inside it, a mix of rules and rulesets
			// In this instance we do not know whether it is the last property
			if( $i + 1 === $ruleNodes_len && (!$this->root || $rulesetNodes_len === 0 || $this->firstRoot ) ){
				Less_Environment::$lastRule = true;
			}

			$rule->genCSS( $output );

			if( !Less_Environment::$lastRule ){
				$output->add( $tabRuleStr );
			}else{
				Less_Environment::$lastRule = false;
			}
		}

		if( !$this->root ){
			$output->add( $tabSetStr . '}' );
			Less_Environment::$tabLevel--;
		}

		$firstRuleset = true;
		$space = ($this->root ? $tabRuleStr : $tabSetStr);
		for( $i = 0; $i < $rulesetNodes_len; $i++ ){

			if( $ruleNodes_len && $firstRuleset ){
				$output->add( $space );
			}elseif( !$firstRuleset ){
				$output->add( $space );
			}
			$firstRuleset = false;
			$rulesetNodes[$i]->genCSS( $output);
		}

		if( !Less_Parser::$options['compress'] && $this->firstRoot ){
			$output->add( "\n" );
		}

	}


	function markReferenced(){
		if( !$this->selectors ){
			return;
		}
		foreach($this->selectors as $selector){
			$selector->markReferenced();
		}
	}

	public function joinSelectors( $context, $selectors ){
		$paths = array();
		if( is_array($selectors) ){
			foreach($selectors as $selector) {
				$this->joinSelector( $paths, $context, $selector);
			}
		}
		return $paths;
	}

	public function joinSelector( &$paths, $context, $selector){

		$hasParentSelector = false;

		foreach($selector->elements as $el) {
			if( $el->value === '&') {
				$hasParentSelector = true;
			}
		}

		if( !$hasParentSelector ){
			if( $context ){
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
				if( $currentElements ){
					$this->mergeElementsOnToSelectors( $currentElements, $newSelectors);
				}

				// loop through our current selectors
				foreach($newSelectors as $sel){

					// if we don't have any parent paths, the & might be in a mixin so that it can be used
					// whether there are parents or not
					if( !$context ){
						// the combinator used on el should now be applied to the next element instead so that
						// it is not lost
						if( $sel ){
							$sel[0]->elements = array_slice($sel[0]->elements,0);
							$sel[0]->elements[] = new Less_Tree_Element($el->combinator, '', $el->index, $el->currentFileInfo );
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
							if( $sel ){
								$newSelectorPath = $sel;
								$lastSelector = array_pop($newSelectorPath);
								$newJoinedSelector = $selector->createDerived( array_slice($lastSelector->elements,0) );
								$newJoinedSelectorEmpty = false;
							}
							else {
								$newJoinedSelector = $selector->createDerived(array());
							}

							//put together the parent selectors after the join
							if ( count($parentSel) > 1) {
								$afterParentJoin = array_merge($afterParentJoin, array_slice($parentSel,1) );
							}

							if ( $parentSel ){
								$newJoinedSelectorEmpty = false;

								// join the elements so far with the first part of the parent
								$newJoinedSelector->elements[] = new Less_Tree_Element( $el->combinator, $parentSel[0]->elements[0]->value, $el->index, $el->currentFileInfo);

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
		if( $currentElements ){
			$this->mergeElementsOnToSelectors($currentElements, $newSelectors);
		}
		foreach( $newSelectors as $new_sel){
			if( $new_sel ){
				$paths[] = $new_sel;
			}
		}
	}

	function mergeElementsOnToSelectors( $elements, &$selectors){

		if( !$selectors ){
			$selectors[] = array( new Less_Tree_Selector($elements) );
			return;
		}


		foreach( $selectors as &$sel){

			// if the previous thing in sel is a parent this needs to join on to it
			if( $sel ){
				$last = count($sel)-1;
				$sel[$last] = $sel[$last]->createDerived( array_merge($sel[$last]->elements, $elements) );
			}else{
				$sel[] = new Less_Tree_Selector( $elements );
			}
		}
	}
}


/**
 * RulesetCall
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_RulesetCall extends Less_Tree{

	public $variable;
	public $type = "RulesetCall";

	function __construct($variable){
		$this->variable = $variable;
	}

	function accept($visitor) {}

	function compile( $env ){
		$variable = new Less_Tree_Variable($this->variable);
		$detachedRuleset = $variable->compile($env);
		return $detachedRuleset->callEval($env);
	}
}



/**
 * Selector
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Selector extends Less_Tree{

	public $elements;
	public $condition;
	public $extendList = array();
	public $_css;
	public $index;
	public $evaldCondition = false;
	public $type = 'Selector';
	public $currentFileInfo = array();
	public $isReferenced;
	public $mediaEmpty;

	public $elements_len = 0;

	public $_oelements;
	public $_oelements_len;
	public $cacheable = true;

	/**
	 * @param boolean $isReferenced
	 */
	public function __construct( $elements, $extendList = array() , $condition = null, $index=null, $currentFileInfo=null, $isReferenced=null ){

		$this->elements = $elements;
		$this->elements_len = count($elements);
		$this->extendList = $extendList;
		$this->condition = $condition;
		if( $currentFileInfo ){
			$this->currentFileInfo = $currentFileInfo;
		}
		$this->isReferenced = $isReferenced;
		if( !$condition ){
			$this->evaldCondition = true;
		}

		$this->CacheElements();
	}

	function accept($visitor) {
		$this->elements = $visitor->visitArray($this->elements);
		$this->extendList = $visitor->visitArray($this->extendList);
		if( $this->condition ){
			$this->condition = $visitor->visitObj($this->condition);
		}

		if( $visitor instanceof Less_Visitor_extendFinder ){
			$this->CacheElements();
		}
	}

	function createDerived( $elements, $extendList = null, $evaldCondition = null ){
		$newSelector = new Less_Tree_Selector( $elements, ($extendList ? $extendList : $this->extendList), null, $this->index, $this->currentFileInfo, $this->isReferenced);
		$newSelector->evaldCondition = $evaldCondition ? $evaldCondition : $this->evaldCondition;
		return $newSelector;
	}


	public function match( $other ){

		if( !$other->_oelements || ($this->elements_len < $other->_oelements_len) ){
			return 0;
		}

		for( $i = 0; $i < $other->_oelements_len; $i++ ){
			if( $this->elements[$i]->value !== $other->_oelements[$i]) {
				return 0;
			}
		}

		return $other->_oelements_len; // return number of matched elements
	}


	public function CacheElements(){

		$this->_oelements = array();
		$css = '';

		foreach($this->elements as $v){

			$css .= $v->combinator;
			if( !$v->value_is_object ){
				$css .= $v->value;
				continue;
			}

			if( !property_exists($v->value,'value') || !is_string($v->value->value) ){
				$this->cacheable = false;
				return;
			}
			$css .= $v->value->value;
		}

		$this->_oelements_len = preg_match_all('/[,&#\.\w-](?:[\w-]|(?:\\\\.))*/', $css, $matches);
		if( $this->_oelements_len ){
			$this->_oelements = $matches[0];

			if( $this->_oelements[0] === '&' ){
				array_shift($this->_oelements);
				$this->_oelements_len--;
			}
		}
	}

	public function isJustParentSelector(){
		return !$this->mediaEmpty &&
			count($this->elements) === 1 &&
			$this->elements[0]->value === '&' &&
			($this->elements[0]->combinator === ' ' || $this->elements[0]->combinator === '');
	}

	public function compile($env) {

		$elements = array();
		foreach($this->elements as $el){
			$elements[] = $el->compile($env);
		}

		$extendList = array();
		foreach($this->extendList as $el){
			$extendList[] = $el->compile($el);
		}

		$evaldCondition = false;
		if( $this->condition ){
			$evaldCondition = $this->condition->compile($env);
		}

		return $this->createDerived( $elements, $extendList, $evaldCondition );
	}


	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output, $firstSelector = true ){

		if( !$firstSelector && $this->elements[0]->combinator === "" ){
			$output->add(' ', $this->currentFileInfo, $this->index);
		}

		foreach($this->elements as $element){
			$element->genCSS( $output );
		}
	}

	function markReferenced(){
		$this->isReferenced = true;
	}

	function getIsReferenced(){
		return !isset($this->currentFileInfo['reference']) || !$this->currentFileInfo['reference'] || $this->isReferenced;
	}

	function getIsOutput(){
		return $this->evaldCondition;
	}

}


/**
 * UnicodeDescriptor
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_UnicodeDescriptor extends Less_Tree{

	public $value;
	public $type = 'UnicodeDescriptor';

	public function __construct($value){
		$this->value = $value;
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	public function genCSS( $output ){
		$output->add( $this->value );
	}

	public function compile(){
		return $this;
	}
}



/**
 * Unit
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Unit extends Less_Tree{

	var $numerator = array();
	var $denominator = array();
	public $backupUnit;
	public $type = 'Unit';

	function __construct($numerator = array(), $denominator = array(), $backupUnit = null ){
		$this->numerator = $numerator;
		$this->denominator = $denominator;
		$this->backupUnit = $backupUnit;
	}

	function __clone(){
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){

		if( $this->numerator ){
			$output->add( $this->numerator[0] );
		}elseif( $this->denominator ){
			$output->add( $this->denominator[0] );
		}elseif( !Less_Parser::$options['strictUnits'] && $this->backupUnit ){
			$output->add( $this->backupUnit );
			return ;
		}
	}

	function toString(){
		$returnStr = implode('*',$this->numerator);
		foreach($this->denominator as $d){
			$returnStr .= '/'.$d;
		}
		return $returnStr;
	}

	function __toString(){
		return $this->toString();
	}


	/**
	 * @param Less_Tree_Unit $other
	 */
	function compare($other) {
		return $this->is( $other->toString() ) ? 0 : -1;
	}

	function is($unitString){
		return $this->toString() === $unitString;
	}

	function isLength(){
		$css = $this->toCSS();
		return !!preg_match('/px|em|%|in|cm|mm|pc|pt|ex/',$css);
	}

	function isAngle() {
		return isset( Less_Tree_UnitConversions::$angle[$this->toCSS()] );
	}

	function isEmpty(){
		return !$this->numerator && !$this->denominator;
	}

	function isSingular() {
		return count($this->numerator) <= 1 && !$this->denominator;
	}


	function usedUnits(){
		$result = array();

		foreach(Less_Tree_UnitConversions::$groups as $groupName){
			$group = Less_Tree_UnitConversions::${$groupName};

			foreach($this->numerator as $atomicUnit){
				if( isset($group[$atomicUnit]) && !isset($result[$groupName]) ){
					$result[$groupName] = $atomicUnit;
				}
			}

			foreach($this->denominator as $atomicUnit){
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

		foreach($this->numerator as $atomicUnit){
			if( !$backup ){
				$backup = $atomicUnit;
			}
			$counter[$atomicUnit] = ( isset($counter[$atomicUnit]) ? $counter[$atomicUnit] : 0) + 1;
		}

		foreach($this->denominator as $atomicUnit){
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

		if( !$this->numerator && !$this->denominator && $backup ){
			$this->backupUnit = $backup;
		}

		sort($this->numerator);
		sort($this->denominator);
	}


}



/**
 * UnitConversions
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_UnitConversions{

	public static $groups = array('length','duration','angle');

	public static $length = array(
		'm'=> 1,
		'cm'=> 0.01,
		'mm'=> 0.001,
		'in'=> 0.0254,
		'px'=> 0.000264583, // 0.0254 / 96,
		'pt'=> 0.000352778, // 0.0254 / 72,
		'pc'=> 0.004233333, // 0.0254 / 72 * 12
		);

	public static $duration = array(
		's'=> 1,
		'ms'=> 0.001
		);

	public static $angle = array(
		'rad' => 0.1591549430919,	// 1/(2*M_PI),
		'deg' => 0.002777778, 		// 1/360,
		'grad'=> 0.0025,			// 1/400,
		'turn'=> 1
		);

}

/**
 * Url
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Url extends Less_Tree{

	public $attrs;
	public $value;
	public $currentFileInfo;
	public $isEvald;
	public $type = 'Url';

	public function __construct($value, $currentFileInfo = null, $isEvald = null){
		$this->value = $value;
		$this->currentFileInfo = $currentFileInfo;
		$this->isEvald = $isEvald;
	}

	function accept( $visitor ){
		$this->value = $visitor->visitObj($this->value);
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$output->add( 'url(' );
		$this->value->genCSS( $output );
		$output->add( ')' );
	}

	/**
	 * @param Less_Functions $ctx
	 */
	public function compile($ctx){
		$val = $this->value->compile($ctx);

		if( !$this->isEvald ){
			// Add the base path if the URL is relative
			if( Less_Parser::$options['relativeUrls']
				&& $this->currentFileInfo
				&& is_string($val->value)
				&& Less_Environment::isPathRelative($val->value)
			){
				$rootpath = $this->currentFileInfo['uri_root'];
				if ( !$val->quote ){
					$rootpath = preg_replace('/[\(\)\'"\s]/', '\\$1', $rootpath );
				}
				$val->value = $rootpath . $val->value;
			}

			$val->value = Less_Environment::normalizePath( $val->value);
		}

		// Add cache buster if enabled
		if( Less_Parser::$options['urlArgs'] ){
			if( !preg_match('/^\s*data:/',$val->value) ){
				$delimiter = strpos($val->value,'?') === false ? '?' : '&';
				$urlArgs = $delimiter . Less_Parser::$options['urlArgs'];
				$hash_pos = strpos($val->value,'#');
				if( $hash_pos !== false ){
					$val->value = substr_replace($val->value,$urlArgs, $hash_pos, 0);
				} else {
					$val->value .= $urlArgs;
				}
			}
		}

		return new Less_Tree_URL($val, $this->currentFileInfo, true);
	}

}


/**
 * Value
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Value extends Less_Tree{

	public $type = 'Value';
	public $value;

	public function __construct($value){
		$this->value = $value;
	}

	function accept($visitor) {
		$this->value = $visitor->visitArray($this->value);
	}

	public function compile($env){

		$ret = array();
		$i = 0;
		foreach($this->value as $i => $v){
			$ret[] = $v->compile($env);
		}
		if( $i > 0 ){
			return new Less_Tree_Value($ret);
		}
		return $ret[0];
	}

	/**
	 * @see Less_Tree::genCSS
	 */
	function genCSS( $output ){
		$len = count($this->value);
		for($i = 0; $i < $len; $i++ ){
			$this->value[$i]->genCSS( $output );
			if( $i+1 < $len ){
				$output->add( Less_Environment::$_outputMap[','] );
			}
		}
	}

}


/**
 * Variable
 *
 * @package Less
 * @subpackage tree
 */
class Less_Tree_Variable extends Less_Tree{

	public $name;
	public $index;
	public $currentFileInfo;
	public $evaluating = false;
	public $type = 'Variable';

	/**
	 * @param string $name
	 */
	public function __construct($name, $index = null, $currentFileInfo = null) {
		$this->name = $name;
		$this->index = $index;
		$this->currentFileInfo = $currentFileInfo;
	}

	public function compile($env) {

		if( $this->name[1] === '@' ){
			$v = new Less_Tree_Variable(substr($this->name, 1), $this->index + 1);
			$name = '@' . $v->compile($env)->value;
		}else{
			$name = $this->name;
		}

		if ($this->evaluating) {
			throw new Less_Exception_Compiler("Recursive variable definition for " . $name, null, $this->index, $this->currentFileInfo);
		}

		$this->evaluating = true;

		foreach($env->frames as $frame){
			if( $v = $frame->variable($name) ){
				$this->evaluating = false;
				return $v->value->compile($env);
			}
		}

		throw new Less_Exception_Compiler("variable " . $name . " is undefined", null, $this->index );
	}

}



class Less_Tree_Mixin_Call extends Less_Tree{

	public $selector;
	public $arguments;
	public $index;
	public $currentFileInfo;

	public $important;
	public $type = 'MixinCall';

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

	//function accept($visitor){
	//	$this->selector = $visitor->visit($this->selector);
	//	$this->arguments = $visitor->visit($this->arguments);
	//}


	public function compile($env){

		$rules = array();
		$match = false;
		$isOneFound = false;
		$candidates = array();
		$defaultUsed = false;
		$conditionResult = array();

		$args = array();
		foreach($this->arguments as $a){
			$args[] = array('name'=> $a['name'], 'value' => $a['value']->compile($env) );
		}

		foreach($env->frames as $frame){

			$mixins = $frame->find($this->selector);

			if( !$mixins ){
				continue;
			}

			$isOneFound = true;
			$defNone = 0;
			$defTrue = 1;
			$defFalse = 2;

			// To make `default()` function independent of definition order we have two "subpasses" here.
			// At first we evaluate each guard *twice* (with `default() == true` and `default() == false`),
			// and build candidate list with corresponding flags. Then, when we know all possible matches,
			// we make a final decision.

			$mixins_len = count($mixins);
			for( $m = 0; $m < $mixins_len; $m++ ){
				$mixin = $mixins[$m];

				if( $this->IsRecursive( $env, $mixin ) ){
					continue;
				}

				if( $mixin->matchArgs($args, $env) ){

					$candidate = array('mixin' => $mixin, 'group' => $defNone);

					if( $mixin instanceof Less_Tree_Ruleset ){

						for( $f = 0; $f < 2; $f++ ){
							Less_Tree_DefaultFunc::value($f);
							$conditionResult[$f] = $mixin->matchCondition( $args, $env);
						}
						if( $conditionResult[0] || $conditionResult[1] ){
							if( $conditionResult[0] != $conditionResult[1] ){
								$candidate['group'] = $conditionResult[1] ? $defTrue : $defFalse;
							}

							$candidates[] = $candidate;
						}
					}else{
						$candidates[] = $candidate;
					}

					$match = true;
				}
			}

			Less_Tree_DefaultFunc::reset();


			$count = array(0, 0, 0);
			for( $m = 0; $m < count($candidates); $m++ ){
				$count[ $candidates[$m]['group'] ]++;
			}

			if( $count[$defNone] > 0 ){
				$defaultResult = $defFalse;
			} else {
				$defaultResult = $defTrue;
				if( ($count[$defTrue] + $count[$defFalse]) > 1 ){
					throw Exception( 'Ambiguous use of `default()` found when matching for `'. $this->format($args) + '`' );
				}
			}


			$candidates_length = count($candidates);
			$length_1 = ($candidates_length == 1);

			for( $m = 0; $m < $candidates_length; $m++){
				$candidate = $candidates[$m]['group'];
				if( ($candidate === $defNone) || ($candidate === $defaultResult) ){
					try{
						$mixin = $candidates[$m]['mixin'];
						if( !($mixin instanceof Less_Tree_Mixin_Definition) ){
							$mixin = new Less_Tree_Mixin_Definition('', array(), $mixin->rules, null, false);
							$mixin->originalRuleset = $mixins[$m]->originalRuleset;
						}
						$rules = array_merge($rules, $mixin->evalCall($env, $args, $this->important)->rules);
					} catch (Exception $e) {
						//throw new Less_Exception_Compiler($e->getMessage(), $e->index, null, $this->currentFileInfo['filename']);
						throw new Less_Exception_Compiler($e->getMessage(), null, null, $this->currentFileInfo);
					}
				}
			}

			if( $match ){
				if( !$this->currentFileInfo || !isset($this->currentFileInfo['reference']) || !$this->currentFileInfo['reference'] ){
					Less_Tree::ReferencedArray($rules);
				}

				return $rules;
			}
		}

		if( $isOneFound ){
			throw new Less_Exception_Compiler('No matching definition was found for `'.$this->Format( $args ).'`', null, $this->index, $this->currentFileInfo);

		}else{
			throw new Less_Exception_Compiler(trim($this->selector->toCSS()) . " is undefined", null, $this->index);
		}

	}

	/**
	 * Format the args for use in exception messages
	 *
	 */
	private function Format($args){
		$message = array();
		if( $args ){
			foreach($args as $a){
				$argValue = '';
				if( $a['name'] ){
					$argValue += $a['name']+':';
				}
				if( is_object($a['value']) ){
					$argValue += $a['value']->toCSS();
				}else{
					$argValue += '???';
				}
				$message[] = $argValue;
			}
		}
		return implode(', ',$message);
	}


	/**
	 * Are we in a recursive mixin call?
	 *
	 * @return bool
	 */
	private function IsRecursive( $env, $mixin ){

		foreach($env->frames as $recur_frame){
			if( !($mixin instanceof Less_Tree_Mixin_Definition) ){

				if( $mixin === $recur_frame ){
					return true;
				}

				if( isset($recur_frame->originalRuleset) && $mixin->ruleset_id === $recur_frame->originalRuleset ){
					return true;
				}
			}
		}

		return false;
	}

}




class Less_Tree_Mixin_Definition extends Less_Tree_Ruleset{
	public $name;
	public $selectors;
	public $params;
	public $arity		= 0;
	public $rules;
	public $lookups		= array();
	public $required	= 0;
	public $frames		= array();
	public $condition;
	public $variadic;
	public $type		= 'MixinDefinition';


	// less.js : /lib/less/tree/mixin.js : tree.mixin.Definition
	public function __construct($name, $params, $rules, $condition, $variadic = false, $frames = null ){
		$this->name = $name;
		$this->selectors = array(new Less_Tree_Selector(array( new Less_Tree_Element(null, $name))));

		$this->params = $params;
		$this->condition = $condition;
		$this->variadic = $variadic;
		$this->rules = $rules;

		if( $params ){
			$this->arity = count($params);
			foreach( $params as $p ){
				if (! isset($p['name']) || ($p['name'] && !isset($p['value']))) {
					$this->required++;
				}
			}
		}

		$this->frames = $frames;
		$this->SetRulesetIndex();
	}



	//function accept( $visitor ){
	//	$this->params = $visitor->visit($this->params);
	//	$this->rules = $visitor->visit($this->rules);
	//	$this->condition = $visitor->visit($this->condition);
	//}


	public function toCSS(){
		return '';
	}

	// less.js : /lib/less/tree/mixin.js : tree.mixin.Definition.evalParams
	public function compileParams($env, $mixinFrames, $args = array() , &$evaldArguments = array() ){
		$frame = new Less_Tree_Ruleset(null, array());
		$params = $this->params;
		$mixinEnv = null;
		$argsLength = 0;

		if( $args ){
			$argsLength = count($args);
			for($i = 0; $i < $argsLength; $i++ ){
				$arg = $args[$i];

				if( $arg && $arg['name'] ){
					$isNamedFound = false;

					foreach($params as $j => $param){
						if( !isset($evaldArguments[$j]) && $arg['name'] === $params[$j]['name']) {
							$evaldArguments[$j] = $arg['value']->compile($env);
							array_unshift($frame->rules, new Less_Tree_Rule( $arg['name'], $arg['value']->compile($env) ) );
							$isNamedFound = true;
							break;
						}
					}
					if ($isNamedFound) {
						array_splice($args, $i, 1);
						$i--;
						$argsLength--;
						continue;
					} else {
						throw new Less_Exception_Compiler("Named argument for " . $this->name .' '.$args[$i]['name'] . ' not found');
					}
				}
			}
		}

		$argIndex = 0;
		foreach($params as $i => $param){

			if ( isset($evaldArguments[$i]) ){ continue; }

			$arg = null;
			if( isset($args[$argIndex]) ){
				$arg = $args[$argIndex];
			}

			if (isset($param['name']) && $param['name']) {

				if( isset($param['variadic']) ){
					$varargs = array();
					for ($j = $argIndex; $j < $argsLength; $j++) {
						$varargs[] = $args[$j]['value']->compile($env);
					}
					$expression = new Less_Tree_Expression($varargs);
					array_unshift($frame->rules, new Less_Tree_Rule($param['name'], $expression->compile($env)));
				}else{
					$val = ($arg && $arg['value']) ? $arg['value'] : false;

					if ($val) {
						$val = $val->compile($env);
					} else if ( isset($param['value']) ) {

						if( !$mixinEnv ){
							$mixinEnv = new Less_Environment();
							$mixinEnv->frames = array_merge( array($frame), $mixinFrames);
						}

						$val = $param['value']->compile($mixinEnv);
						$frame->resetCache();
					} else {
						throw new Less_Exception_Compiler("Wrong number of arguments for " . $this->name . " (" . $argsLength . ' for ' . $this->arity . ")");
					}

					array_unshift($frame->rules, new Less_Tree_Rule($param['name'], $val));
					$evaldArguments[$i] = $val;
				}
			}

			if ( isset($param['variadic']) && $args) {
				for ($j = $argIndex; $j < $argsLength; $j++) {
					$evaldArguments[$j] = $args[$j]['value']->compile($env);
				}
			}
			$argIndex++;
		}

		ksort($evaldArguments);
		$evaldArguments = array_values($evaldArguments);

		return $frame;
	}

	public function compile($env) {
		if( $this->frames ){
			return new Less_Tree_Mixin_Definition($this->name, $this->params, $this->rules, $this->condition, $this->variadic, $this->frames );
		}
		return new Less_Tree_Mixin_Definition($this->name, $this->params, $this->rules, $this->condition, $this->variadic, $env->frames );
	}

	public function evalCall($env, $args = NULL, $important = NULL) {

		Less_Environment::$mixin_stack++;

		$_arguments = array();

		if( $this->frames ){
			$mixinFrames = array_merge($this->frames, $env->frames);
		}else{
			$mixinFrames = $env->frames;
		}

		$frame = $this->compileParams($env, $mixinFrames, $args, $_arguments);

		$ex = new Less_Tree_Expression($_arguments);
		array_unshift($frame->rules, new Less_Tree_Rule('@arguments', $ex->compile($env)));


		$ruleset = new Less_Tree_Ruleset(null, $this->rules);
		$ruleset->originalRuleset = $this->ruleset_id;


		$ruleSetEnv = new Less_Environment();
		$ruleSetEnv->frames = array_merge( array($this, $frame), $mixinFrames );
		$ruleset = $ruleset->compile( $ruleSetEnv );

		if( $important ){
			$ruleset = $ruleset->makeImportant();
		}

		Less_Environment::$mixin_stack--;

		return $ruleset;
	}


	public function matchCondition($args, $env) {

		if( !$this->condition ){
			return true;
		}

		$frame = $this->compileParams($env, array_merge($this->frames,$env->frames), $args );

		$compile_env = new Less_Environment();
		$compile_env->frames = array_merge(
				array($frame)		// the parameter variables
				, $this->frames		// the parent namespace/mixin frames
				, $env->frames		// the current environment frames
			);

		return (bool)$this->condition->compile($compile_env);
	}

	public function matchArgs($args, $env = NULL){
		$argsLength = count($args);

		if( !$this->variadic ){
			if( $argsLength < $this->required ){
				return false;
			}
			if( $argsLength > count($this->params) ){
				return false;
			}
		}else{
			if( $argsLength < ($this->required - 1)){
				return false;
			}
		}

		$len = min($argsLength, $this->arity);

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


/**
 * Extend Finder Visitor
 *
 * @package Less
 * @subpackage visitor
 */
class Less_Visitor_extendFinder extends Less_Visitor{

	public $contexts = array();
	public $allExtendsStack;
	public $foundExtends;

	function __construct(){
		$this->contexts = array();
		$this->allExtendsStack = array(array());
		parent::__construct();
	}

	/**
	 * @param Less_Tree_Ruleset $root
	 */
	function run($root){
		$root = $this->visitObj($root);
		$root->allExtends =& $this->allExtendsStack[0];
		return $root;
	}

	function visitRule($ruleNode, &$visitDeeper ){
		$visitDeeper = false;
	}

	function visitMixinDefinition( $mixinDefinitionNode, &$visitDeeper ){
		$visitDeeper = false;
	}

	function visitRuleset($rulesetNode){

		if( $rulesetNode->root ){
			return;
		}

		$allSelectorsExtendList = array();

		// get &:extend(.a); rules which apply to all selectors in this ruleset
		if( $rulesetNode->rules ){
			foreach($rulesetNode->rules as $rule){
				if( $rule instanceof Less_Tree_Extend ){
					$allSelectorsExtendList[] = $rule;
					$rulesetNode->extendOnEveryPath = true;
				}
			}
		}


		// now find every selector and apply the extends that apply to all extends
		// and the ones which apply to an individual extend
		foreach($rulesetNode->paths as $selectorPath){
			$selector = end($selectorPath); //$selectorPath[ count($selectorPath)-1];

			$j = 0;
			foreach($selector->extendList as $extend){
				$this->allExtendsStackPush($rulesetNode, $selectorPath, $extend, $j);
			}
			foreach($allSelectorsExtendList as $extend){
				$this->allExtendsStackPush($rulesetNode, $selectorPath, $extend, $j);
			}
		}

		$this->contexts[] = $rulesetNode->selectors;
	}

	function allExtendsStackPush($rulesetNode, $selectorPath, $extend, &$j){
		$this->foundExtends = true;
		$extend = clone $extend;
		$extend->findSelfSelectors( $selectorPath );
		$extend->ruleset = $rulesetNode;
		if( $j === 0 ){
			$extend->firstExtendOnThisSelectorPath = true;
		}

		$end_key = count($this->allExtendsStack)-1;
		$this->allExtendsStack[$end_key][] = $extend;
		$j++;
	}


	function visitRulesetOut( $rulesetNode ){
		if( !is_object($rulesetNode) || !$rulesetNode->root ){
			array_pop($this->contexts);
		}
	}

	function visitMedia( $mediaNode ){
		$mediaNode->allExtends = array();
		$this->allExtendsStack[] =& $mediaNode->allExtends;
	}

	function visitMediaOut(){
		array_pop($this->allExtendsStack);
	}

	function visitDirective( $directiveNode ){
		$directiveNode->allExtends = array();
		$this->allExtendsStack[] =& $directiveNode->allExtends;
	}

	function visitDirectiveOut(){
		array_pop($this->allExtendsStack);
	}
}




/*
class Less_Visitor_import extends Less_VisitorReplacing{

	public $_visitor;
	public $_importer;
	public $importCount;

	function __construct( $evalEnv ){
		$this->env = $evalEnv;
		$this->importCount = 0;
		parent::__construct();
	}


	function run( $root ){
		$root = $this->visitObj($root);
		$this->isFinished = true;

		//if( $this->importCount === 0) {
		//	$this->_finish();
		//}
	}

	function visitImport($importNode, &$visitDeeper ){
		$importVisitor = $this;
		$inlineCSS = $importNode->options['inline'];

		if( !$importNode->css || $inlineCSS ){
			$evaldImportNode = $importNode->compileForImport($this->env);

			if( $evaldImportNode && (!$evaldImportNode->css || $inlineCSS) ){
				$importNode = $evaldImportNode;
				$this->importCount++;
				$env = clone $this->env;

				if( (isset($importNode->options['multiple']) && $importNode->options['multiple']) ){
					$env->importMultiple = true;
				}

				//get path & uri
				$path_and_uri = null;
				if( is_callable(Less_Parser::$options['import_callback']) ){
					$path_and_uri = call_user_func(Less_Parser::$options['import_callback'],$importNode);
				}

				if( !$path_and_uri ){
					$path_and_uri = $importNode->PathAndUri();
				}

				if( $path_and_uri ){
					list($full_path, $uri) = $path_and_uri;
				}else{
					$full_path = $uri = $importNode->getPath();
				}


				//import once
				if( $importNode->skip( $full_path, $env) ){
					return array();
				}

				if( $importNode->options['inline'] ){
					//todo needs to reference css file not import
					//$contents = new Less_Tree_Anonymous($importNode->root, 0, array('filename'=>$importNode->importedFilename), true );

					Less_Parser::AddParsedFile($full_path);
					$contents = new Less_Tree_Anonymous( file_get_contents($full_path), 0, array(), true );

					if( $importNode->features ){
						return new Less_Tree_Media( array($contents), $importNode->features->value );
					}

					return array( $contents );
				}


				// css ?
				if( $importNode->css ){
					$features = ( $importNode->features ? $importNode->features->compile($env) : null );
					return new Less_Tree_Import( $importNode->compilePath( $env), $features, $importNode->options, $this->index);
				}

				return $importNode->ParseImport( $full_path, $uri, $env );
			}

		}

		$visitDeeper = false;
		return $importNode;
	}


	function visitRule( $ruleNode, &$visitDeeper ){
		$visitDeeper = false;
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




/**
 * Join Selector Visitor
 *
 * @package Less
 * @subpackage visitor
 */
class Less_Visitor_joinSelector extends Less_Visitor{

	public $contexts = array( array() );

	/**
	 * @param Less_Tree_Ruleset $root
	 */
	function run( $root ){
		return $this->visitObj($root);
	}

	function visitRule( $ruleNode, &$visitDeeper ){
		$visitDeeper = false;
	}

	function visitMixinDefinition( $mixinDefinitionNode, &$visitDeeper ){
		$visitDeeper = false;
	}

	function visitRuleset( $rulesetNode ){

		$paths = array();

		if( !$rulesetNode->root ){
			$selectors = array();

			if( $rulesetNode->selectors && $rulesetNode->selectors ){
				foreach($rulesetNode->selectors as $selector){
					if( $selector->getIsOutput() ){
						$selectors[] = $selector;
					}
				}
			}

			if( !$selectors ){
				$rulesetNode->selectors = null;
				$rulesetNode->rules = null;
			}else{
				$context = end($this->contexts); //$context = $this->contexts[ count($this->contexts) - 1];
				$paths = $rulesetNode->joinSelectors( $context, $selectors);
			}

			$rulesetNode->paths = $paths;
		}

		$this->contexts[] = $paths; //different from less.js. Placed after joinSelectors() so that $this->contexts will get correct $paths
	}

	function visitRulesetOut(){
		array_pop($this->contexts);
	}

	function visitMedia($mediaNode) {
		$context = end($this->contexts); //$context = $this->contexts[ count($this->contexts) - 1];

		if( !count($context) || (is_object($context[0]) && $context[0]->multiMedia) ){
			$mediaNode->rules[0]->root = true;
		}
	}

}



/**
 * Process Extends Visitor
 *
 * @package Less
 * @subpackage visitor
 */
class Less_Visitor_processExtends extends Less_Visitor{

	public $allExtendsStack;

	/**
	 * @param Less_Tree_Ruleset $root
	 */
	public function run( $root ){
		$extendFinder = new Less_Visitor_extendFinder();
		$extendFinder->run( $root );
		if( !$extendFinder->foundExtends){
			return $root;
		}

		$root->allExtends = $this->doExtendChaining( $root->allExtends, $root->allExtends);

		$this->allExtendsStack = array();
		$this->allExtendsStack[] = &$root->allExtends;

		return $this->visitObj( $root );
	}

	private function doExtendChaining( $extendsList, $extendsListTarget, $iterationCount = 0){
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
				if( in_array($targetExtend->object_id, $extend->parent_ids,true) ){
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
						$newExtend->parent_ids = array_merge($newExtend->parent_ids,$targetExtend->parent_ids,$extend->parent_ids);

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

				try{
					$selectorOne = $extendsToAdd[0]->selfSelectors[0]->toCSS();
					$selectorTwo = $extendsToAdd[0]->selector->toCSS();
				}catch(Exception $e){
					$selectorOne = "{unable to calculate}";
					$selectorTwo = "{unable to calculate}";
				}

				throw new Less_Exception_Parser("extend circular reference detected. One of the circular extends is currently:"+$selectorOne+":extend(" + $selectorTwo+")");
			}

			// now process the new extends on the existing rules so that we can handle a extending b extending c ectending d extending e...
			$extendsToAdd = $this->doExtendChaining( $extendsToAdd, $extendsListTarget, $iterationCount+1);
		}

		return array_merge($extendsList, $extendsToAdd);
	}


	protected function visitRule( $ruleNode, &$visitDeeper ){
		$visitDeeper = false;
	}

	protected function visitMixinDefinition( $mixinDefinitionNode, &$visitDeeper ){
		$visitDeeper = false;
	}

	protected function visitSelector( $selectorNode, &$visitDeeper ){
		$visitDeeper = false;
	}

	protected function visitRuleset($rulesetNode){


		if( $rulesetNode->root ){
			return;
		}

		$allExtends	= end($this->allExtendsStack);
		$paths_len = count($rulesetNode->paths);

		// look at each selector path in the ruleset, find any extend matches and then copy, find and replace
		foreach($allExtends as $allExtend){
			for($pathIndex = 0; $pathIndex < $paths_len; $pathIndex++ ){

				// extending extends happens initially, before the main pass
				if( isset($rulesetNode->extendOnEveryPath) && $rulesetNode->extendOnEveryPath ){
					continue;
				}

				$selectorPath = $rulesetNode->paths[$pathIndex];

				if( end($selectorPath)->extendList ){
					continue;
				}

				$this->ExtendMatch( $rulesetNode, $allExtend, $selectorPath);

			}
		}
	}


	private function ExtendMatch( $rulesetNode, $extend, $selectorPath ){
		$matches = $this->findMatch($extend, $selectorPath);

		if( $matches ){
			foreach($extend->selfSelectors as $selfSelector ){
				$rulesetNode->paths[] = $this->extendSelector($matches, $selectorPath, $selfSelector);
			}
		}
	}



	private function findMatch($extend, $haystackSelectorPath ){


		if( !$this->HasMatches($extend, $haystackSelectorPath) ){
			return false;
		}


		//
		// look through the haystack selector path to try and find the needle - extend.selector
		// returns an array of selector matches that can then be replaced
		//
		$needleElements = $extend->selector->elements;
		$potentialMatches = array();
		$potentialMatches_len = 0;
		$potentialMatch = null;
		$matches = array();



		// loop through the haystack elements
		$haystack_path_len = count($haystackSelectorPath);
		for($haystackSelectorIndex = 0; $haystackSelectorIndex < $haystack_path_len; $haystackSelectorIndex++ ){
			$hackstackSelector = $haystackSelectorPath[$haystackSelectorIndex];

			$haystack_elements_len = count($hackstackSelector->elements);
			for($hackstackElementIndex = 0; $hackstackElementIndex < $haystack_elements_len; $hackstackElementIndex++ ){

				$haystackElement = $hackstackSelector->elements[$hackstackElementIndex];

				// if we allow elements before our match we can add a potential match every time. otherwise only at the first element.
				if( $extend->allowBefore || ($haystackSelectorIndex === 0 && $hackstackElementIndex === 0) ){
					$potentialMatches[] = array('pathIndex'=> $haystackSelectorIndex, 'index'=> $hackstackElementIndex, 'matched'=> 0, 'initialCombinator'=> $haystackElement->combinator);
					$potentialMatches_len++;
				}

				for($i = 0; $i < $potentialMatches_len; $i++ ){

					$potentialMatch = &$potentialMatches[$i];
					$potentialMatch = $this->PotentialMatch( $potentialMatch, $needleElements, $haystackElement, $hackstackElementIndex );


					// if we are still valid and have finished, test whether we have elements after and whether these are allowed
					if( $potentialMatch && $potentialMatch['matched'] === $extend->selector->elements_len ){
						$potentialMatch['finished'] = true;

						if( !$extend->allowAfter && ($hackstackElementIndex+1 < $haystack_elements_len || $haystackSelectorIndex+1 < $haystack_path_len) ){
							$potentialMatch = null;
						}
					}

					// if null we remove, if not, we are still valid, so either push as a valid match or continue
					if( $potentialMatch ){
						if( $potentialMatch['finished'] ){
							$potentialMatch['length'] = $extend->selector->elements_len;
							$potentialMatch['endPathIndex'] = $haystackSelectorIndex;
							$potentialMatch['endPathElementIndex'] = $hackstackElementIndex + 1; // index after end of match
							$potentialMatches = array(); // we don't allow matches to overlap, so start matching again
							$potentialMatches_len = 0;
							$matches[] = $potentialMatch;
						}
						continue;
					}

					array_splice($potentialMatches, $i, 1);
					$potentialMatches_len--;
					$i--;
				}
			}
		}

		return $matches;
	}


	// Before going through all the nested loops, lets check to see if a match is possible
	// Reduces Bootstrap 3.1 compile time from ~6.5s to ~5.6s
	private function HasMatches($extend, $haystackSelectorPath){

		if( !$extend->selector->cacheable ){
			return true;
		}

		$first_el = $extend->selector->_oelements[0];

		foreach($haystackSelectorPath as $hackstackSelector){
			if( !$hackstackSelector->cacheable ){
				return true;
			}

			if( in_array($first_el, $hackstackSelector->_oelements) ){
				return true;
			}
		}

		return false;
	}


	/**
	 * @param integer $hackstackElementIndex
	 */
	private function PotentialMatch( $potentialMatch, $needleElements, $haystackElement, $hackstackElementIndex ){


		if( $potentialMatch['matched'] > 0 ){

			// selectors add " " onto the first element. When we use & it joins the selectors together, but if we don't
			// then each selector in haystackSelectorPath has a space before it added in the toCSS phase. so we need to work out
			// what the resulting combinator will be
			$targetCombinator = $haystackElement->combinator;
			if( $targetCombinator === '' && $hackstackElementIndex === 0 ){
				$targetCombinator = ' ';
			}

			if( $needleElements[ $potentialMatch['matched'] ]->combinator !== $targetCombinator ){
				return null;
			}
		}

		// if we don't match, null our match to indicate failure
		if( !$this->isElementValuesEqual( $needleElements[$potentialMatch['matched'] ]->value, $haystackElement->value) ){
			return null;
		}

		$potentialMatch['finished'] = false;
		$potentialMatch['matched']++;

		return $potentialMatch;
	}


	private function isElementValuesEqual( $elementValue1, $elementValue2 ){

		if( $elementValue1 === $elementValue2 ){
			return true;
		}

		if( is_string($elementValue1) || is_string($elementValue2) ) {
			return false;
		}

		if( $elementValue1 instanceof Less_Tree_Attribute ){
			return $this->isAttributeValuesEqual( $elementValue1, $elementValue2 );
		}

		$elementValue1 = $elementValue1->value;
		if( $elementValue1 instanceof Less_Tree_Selector ){
			return $this->isSelectorValuesEqual( $elementValue1, $elementValue2 );
		}

		return false;
	}


	/**
	 * @param Less_Tree_Selector $elementValue1
	 */
	private function isSelectorValuesEqual( $elementValue1, $elementValue2 ){

		$elementValue2 = $elementValue2->value;
		if( !($elementValue2 instanceof Less_Tree_Selector) || $elementValue1->elements_len !== $elementValue2->elements_len ){
			return false;
		}

		for( $i = 0; $i < $elementValue1->elements_len; $i++ ){

			if( $elementValue1->elements[$i]->combinator !== $elementValue2->elements[$i]->combinator ){
				if( $i !== 0 || ($elementValue1->elements[$i]->combinator || ' ') !== ($elementValue2->elements[$i]->combinator || ' ') ){
					return false;
				}
			}

			if( !$this->isElementValuesEqual($elementValue1->elements[$i]->value, $elementValue2->elements[$i]->value) ){
				return false;
			}
		}

		return true;
	}


	/**
	 * @param Less_Tree_Attribute $elementValue1
	 */
	private function isAttributeValuesEqual( $elementValue1, $elementValue2 ){

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


	private function extendSelector($matches, $selectorPath, $replacementSelector){

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
				$replacementSelector->elements[0]->index,
				$replacementSelector->elements[0]->currentFileInfo
			);

			if( $match['pathIndex'] > $currentSelectorPathIndex && $currentSelectorPathElementIndex > 0 ){
				$last_path = end($path);
				$last_path->elements = array_merge( $last_path->elements, array_slice( $selectorPath[$currentSelectorPathIndex]->elements, $currentSelectorPathElementIndex));
				$currentSelectorPathElementIndex = 0;
				$currentSelectorPathIndex++;
			}

			$newElements = array_merge(
				array_slice($selector->elements, $currentSelectorPathElementIndex, ($match['index'] - $currentSelectorPathElementIndex) ) // last parameter of array_slice is different than the last parameter of javascript's slice
				, array($firstElement)
				, array_slice($replacementSelector->elements,1)
				);

			if( $currentSelectorPathIndex === $match['pathIndex'] && $matchIndex > 0 ){
				$last_key = count($path)-1;
				$path[$last_key]->elements = array_merge($path[$last_key]->elements,$newElements);
			}else{
				$path = array_merge( $path, array_slice( $selectorPath, $currentSelectorPathIndex, $match['pathIndex'] ));
				$path[] = new Less_Tree_Selector( $newElements );
			}

			$currentSelectorPathIndex = $match['endPathIndex'];
			$currentSelectorPathElementIndex = $match['endPathElementIndex'];
			if( $currentSelectorPathElementIndex >= count($selectorPath[$currentSelectorPathIndex]->elements) ){
				$currentSelectorPathElementIndex = 0;
				$currentSelectorPathIndex++;
			}
		}

		if( $currentSelectorPathIndex < $selectorPath_len && $currentSelectorPathElementIndex > 0 ){
			$last_path = end($path);
			$last_path->elements = array_merge( $last_path->elements, array_slice($selectorPath[$currentSelectorPathIndex]->elements, $currentSelectorPathElementIndex));
			$currentSelectorPathIndex++;
		}

		$slice_len = $selectorPath_len - $currentSelectorPathIndex;
		$path = array_merge($path, array_slice($selectorPath, $currentSelectorPathIndex, $slice_len));

		return $path;
	}


	protected function visitMedia( $mediaNode ){
		$newAllExtends = array_merge( $mediaNode->allExtends, end($this->allExtendsStack) );
		$this->allExtendsStack[] = $this->doExtendChaining($newAllExtends, $mediaNode->allExtends);
	}

	protected function visitMediaOut(){
		array_pop( $this->allExtendsStack );
	}

	protected function visitDirective( $directiveNode ){
		$newAllExtends = array_merge( $directiveNode->allExtends, end($this->allExtendsStack) );
		$this->allExtendsStack[] = $this->doExtendChaining($newAllExtends, $directiveNode->allExtends);
	}

	protected function visitDirectiveOut(){
		array_pop($this->allExtendsStack);
	}

}

/**
 * toCSS Visitor
 *
 * @package Less
 * @subpackage visitor
 */
class Less_Visitor_toCSS extends Less_VisitorReplacing{

	private $charset;

	function __construct(){
		parent::__construct();
	}

	/**
	 * @param Less_Tree_Ruleset $root
	 */
	function run( $root ){
		return $this->visitObj($root);
	}

	function visitRule( $ruleNode ){
		if( $ruleNode->variable ){
			return array();
		}
		return $ruleNode;
	}

	function visitMixinDefinition($mixinNode){
		// mixin definitions do not get eval'd - this means they keep state
		// so we have to clear that state here so it isn't used if toCSS is called twice
		$mixinNode->frames = array();
		return array();
	}

	function visitExtend(){
		return array();
	}

	function visitComment( $commentNode ){
		if( $commentNode->isSilent() ){
			return array();
		}
		return $commentNode;
	}

	function visitMedia( $mediaNode, &$visitDeeper ){
		$mediaNode->accept($this);
		$visitDeeper = false;

		if( !$mediaNode->rules ){
			return array();
		}
		return $mediaNode;
	}

	function visitDirective( $directiveNode ){
		if( isset($directiveNode->currentFileInfo['reference']) && (!property_exists($directiveNode,'isReferenced') || !$directiveNode->isReferenced) ){
			return array();
		}
		if( $directiveNode->name === '@charset' ){
			// Only output the debug info together with subsequent @charset definitions
			// a comment (or @media statement) before the actual @charset directive would
			// be considered illegal css as it has to be on the first line
			if( isset($this->charset) && $this->charset ){

				//if( $directiveNode->debugInfo ){
				//	$comment = new Less_Tree_Comment('/* ' . str_replace("\n",'',$directiveNode->toCSS())." */\n");
				//	$comment->debugInfo = $directiveNode->debugInfo;
				//	return $this->visit($comment);
				//}


				return array();
			}
			$this->charset = true;
		}
		return $directiveNode;
	}

	function checkPropertiesInRoot( $rulesetNode ){

		if( !$rulesetNode->firstRoot ){
			return;
		}

		foreach($rulesetNode->rules as $ruleNode){
			if( $ruleNode instanceof Less_Tree_Rule && !$ruleNode->variable ){
				$msg = "properties must be inside selector blocks, they cannot be in the root. Index ".$ruleNode->index.($ruleNode->currentFileInfo ? (' Filename: '.$ruleNode->currentFileInfo['filename']) : null);
				throw new Less_Exception_Compiler($msg);
			}
		}
	}


	function visitRuleset( $rulesetNode, &$visitDeeper ){

		$visitDeeper = false;

		$this->checkPropertiesInRoot( $rulesetNode );

		if( $rulesetNode->root ){
			return $this->visitRulesetRoot( $rulesetNode );
		}

		$rulesets = array();
		$rulesetNode->paths = $this->visitRulesetPaths($rulesetNode);


		// Compile rules and rulesets
		$nodeRuleCnt = count($rulesetNode->rules);
		for( $i = 0; $i < $nodeRuleCnt; ){
			$rule = $rulesetNode->rules[$i];

			if( property_exists($rule,'rules') ){
				// visit because we are moving them out from being a child
				$rulesets[] = $this->visitObj($rule);
				array_splice($rulesetNode->rules,$i,1);
				$nodeRuleCnt--;
				continue;
			}
			$i++;
		}


		// accept the visitor to remove rules and refactor itself
		// then we can decide now whether we want it or not
		if( $nodeRuleCnt > 0 ){
			$rulesetNode->accept($this);

			if( $rulesetNode->rules ){

				if( count($rulesetNode->rules) >  1 ){
					$this->_mergeRules( $rulesetNode->rules );
					$this->_removeDuplicateRules( $rulesetNode->rules );
				}

				// now decide whether we keep the ruleset
				if( $rulesetNode->paths ){
					//array_unshift($rulesets, $rulesetNode);
					array_splice($rulesets,0,0,array($rulesetNode));
				}
			}

		}


		if( count($rulesets) === 1 ){
			return $rulesets[0];
		}
		return $rulesets;
	}


	/**
	 * Helper function for visitiRuleset
	 *
	 * return array|Less_Tree_Ruleset
	 */
	private function visitRulesetRoot( $rulesetNode ){
		$rulesetNode->accept( $this );
		if( $rulesetNode->firstRoot || $rulesetNode->rules ){
			return $rulesetNode;
		}
		return array();
	}


	/**
	 * Helper function for visitRuleset()
	 *
	 * @return array
	 */
	private function visitRulesetPaths($rulesetNode){

		$paths = array();
		foreach($rulesetNode->paths as $p){
			if( $p[0]->elements[0]->combinator === ' ' ){
				$p[0]->elements[0]->combinator = '';
			}

			foreach($p as $pi){
				if( $pi->getIsReferenced() && $pi->getIsOutput() ){
					$paths[] = $p;
					break;
				}
			}
		}

		return $paths;
	}

	function _removeDuplicateRules( &$rules ){
		// remove duplicates
		$ruleCache = array();
		for( $i = count($rules)-1; $i >= 0 ; $i-- ){
			$rule = $rules[$i];
			if( $rule instanceof Less_Tree_Rule || $rule instanceof Less_Tree_NameValue ){

				if( !isset($ruleCache[$rule->name]) ){
					$ruleCache[$rule->name] = $rule;
				}else{
					$ruleList =& $ruleCache[$rule->name];

					if( $ruleList instanceof Less_Tree_Rule || $ruleList instanceof Less_Tree_NameValue ){
						$ruleList = $ruleCache[$rule->name] = array( $ruleCache[$rule->name]->toCSS() );
					}

					$ruleCSS = $rule->toCSS();
					if( array_search($ruleCSS,$ruleList) !== false ){
						array_splice($rules,$i,1);
					}else{
						$ruleList[] = $ruleCSS;
					}
				}
			}
		}
	}

	function _mergeRules( &$rules ){
		$groups = array();

		//obj($rules);

		$rules_len = count($rules);
		for( $i = 0; $i < $rules_len; $i++ ){
			$rule = $rules[$i];

			if( ($rule instanceof Less_Tree_Rule) && $rule->merge ){

				$key = $rule->name;
				if( $rule->important ){
					$key .= ',!';
				}

				if( !isset($groups[$key]) ){
					$groups[$key] = array();
				}else{
					array_splice($rules, $i--, 1);
					$rules_len--;
				}

				$groups[$key][] = $rule;
			}
		}


		foreach($groups as $parts){

			if( count($parts) > 1 ){
				$rule = $parts[0];
				$spacedGroups = array();
				$lastSpacedGroup = array();
				$parts_mapped = array();
				foreach($parts as $p){
					if( $p->merge === '+' ){
						if( $lastSpacedGroup ){
							$spacedGroups[] = self::toExpression($lastSpacedGroup);
						}
						$lastSpacedGroup = array();
					}
					$lastSpacedGroup[] = $p;
				}

				$spacedGroups[] = self::toExpression($lastSpacedGroup);
				$rule->value = self::toValue($spacedGroups);
			}
		}

	}

	static function toExpression($values){
		$mapped = array();
		foreach($values as $p){
			$mapped[] = $p->value;
		}
		return new Less_Tree_Expression( $mapped );
	}

	static function toValue($values){
		//return new Less_Tree_Value($values); ??

		$mapped = array();
		foreach($values as $p){
			$mapped[] = $p;
		}
		return new Less_Tree_Value($mapped);
	}
}



/**
 * Parser Exception
 *
 * @package Less
 * @subpackage exception
 */
class Less_Exception_Parser extends Exception{

	/**
	 * The current file
	 *
	 * @var Less_ImportedFile
	 */
	public $currentFile;

	/**
	 * The current parser index
	 *
	 * @var integer
	 */
	public $index;

	protected $input;

	protected $details = array();


	/**
	 * Constructor
	 *
	 * @param string $message
	 * @param Exception $previous Previous exception
	 * @param integer $index The current parser index
	 * @param Less_FileInfo|string $currentFile The file
	 * @param integer $code The exception code
	 */
	public function __construct($message = null, Exception $previous = null, $index = null, $currentFile = null, $code = 0){

		if (PHP_VERSION_ID < 50300) {
			$this->previous = $previous;
			parent::__construct($message, $code);
		} else {
			parent::__construct($message, $code, $previous);
		}

		$this->currentFile = $currentFile;
		$this->index = $index;

		$this->genMessage();
	}


	protected function getInput(){

		if( !$this->input && $this->currentFile && $this->currentFile['filename'] ){
			$this->input = file_get_contents( $this->currentFile['filename'] );
		}
	}



	/**
	 * Converts the exception to string
	 *
	 * @return string
	 */
	public function genMessage(){

		if( $this->currentFile && $this->currentFile['filename'] ){
			$this->message .= ' in '.basename($this->currentFile['filename']);
		}

		if( $this->index !== null ){
			$this->getInput();
			if( $this->input ){
				$line = self::getLineNumber();
				$this->message .= ' on line '.$line.', column '.self::getColumn();

				$lines = explode("\n",$this->input);

				$count = count($lines);
				$start_line = max(0, $line-3);
				$last_line = min($count, $start_line+6);
				$num_len = strlen($last_line);
				for( $i = $start_line; $i < $last_line; $i++ ){
					$this->message .= "\n".str_pad($i+1,$num_len,'0',STR_PAD_LEFT).'| '.$lines[$i];
				}
			}
		}

	}

	/**
	 * Returns the line number the error was encountered
	 *
	 * @return integer
	 */
	public function getLineNumber(){
		if( $this->index ){
			return substr_count($this->input, "\n", 0, $this->index) + 1;
		}
		return 1;
	}


	/**
	 * Returns the column the error was encountered
	 *
	 * @return integer
	 */
	public function getColumn(){

		$part = substr($this->input, 0, $this->index);
		$pos = strrpos($part,"\n");
		return $this->index - $pos;
	}

}


/**
 * Chunk Exception
 *
 * @package Less
 * @subpackage exception
 */
class Less_Exception_Chunk extends Less_Exception_Parser{


	protected $parserCurrentIndex = 0;

	protected $emitFrom = 0;

	protected $input_len;


	/**
	 * Constructor
	 *
	 * @param string $input
	 * @param Exception $previous Previous exception
	 * @param integer $index The current parser index
	 * @param Less_FileInfo|string $currentFile The file
	 * @param integer $code The exception code
	 */
	public function __construct($input, Exception $previous = null, $index = null, $currentFile = null, $code = 0){

		$this->message = 'ParseError: Unexpected input'; //default message

		$this->index = $index;

		$this->currentFile = $currentFile;

		$this->input = $input;
		$this->input_len = strlen($input);

		$this->Chunks();
		$this->genMessage();
	}


	/**
	 * See less.js chunks()
	 * We don't actually need the chunks
	 *
	 */
	function Chunks(){
		$level = 0;
		$parenLevel = 0;
		$lastMultiCommentEndBrace = null;
		$lastOpening = null;
		$lastMultiComment = null;
		$lastParen = null;

		for( $this->parserCurrentIndex = 0; $this->parserCurrentIndex < $this->input_len; $this->parserCurrentIndex++ ){
			$cc = $this->CharCode($this->parserCurrentIndex);
			if ((($cc >= 97) && ($cc <= 122)) || ($cc < 34)) {
				// a-z or whitespace
				continue;
			}

			switch ($cc) {

				// (
				case 40:
					$parenLevel++;
					$lastParen = $this->parserCurrentIndex;
					continue;

				// )
				case 41:
					$parenLevel--;
					if( $parenLevel < 0 ){
						return $this->fail("missing opening `(`");
					}
					continue;

				// ;
				case 59:
					//if (!$parenLevel) { $this->emitChunk();	}
					continue;

				// {
				case 123:
					$level++;
					$lastOpening = $this->parserCurrentIndex;
					continue;

				// }
				case 125:
					$level--;
					if( $level < 0 ){
						return $this->fail("missing opening `{`");

					}
					//if (!$level && !$parenLevel) { $this->emitChunk(); }
					continue;
				// \
				case 92:
					if ($this->parserCurrentIndex < $this->input_len - 1) { $this->parserCurrentIndex++; continue; }
					return $this->fail("unescaped `\\`");

				// ", ' and `
				case 34:
				case 39:
				case 96:
					$matched = 0;
					$currentChunkStartIndex = $this->parserCurrentIndex;
					for ($this->parserCurrentIndex = $this->parserCurrentIndex + 1; $this->parserCurrentIndex < $this->input_len; $this->parserCurrentIndex++) {
						$cc2 = $this->CharCode($this->parserCurrentIndex);
						if ($cc2 > 96) { continue; }
						if ($cc2 == $cc) { $matched = 1; break; }
						if ($cc2 == 92) {        // \
							if ($this->parserCurrentIndex == $this->input_len - 1) {
								return $this->fail("unescaped `\\`");
							}
							$this->parserCurrentIndex++;
						}
					}
					if ($matched) { continue; }
					return $this->fail("unmatched `" + chr($cc) + "`", $currentChunkStartIndex);

				// /, check for comment
				case 47:
					if ($parenLevel || ($this->parserCurrentIndex == $this->input_len - 1)) { continue; }
					$cc2 = $this->CharCode($this->parserCurrentIndex+1);
					if ($cc2 == 47) {
						// //, find lnfeed
						for ($this->parserCurrentIndex = $this->parserCurrentIndex + 2; $this->parserCurrentIndex < $this->input_len; $this->parserCurrentIndex++) {
							$cc2 = $this->CharCode($this->parserCurrentIndex);
							if (($cc2 <= 13) && (($cc2 == 10) || ($cc2 == 13))) { break; }
						}
					} else if ($cc2 == 42) {
						// /*, find */
						$lastMultiComment = $currentChunkStartIndex = $this->parserCurrentIndex;
						for ($this->parserCurrentIndex = $this->parserCurrentIndex + 2; $this->parserCurrentIndex < $this->input_len - 1; $this->parserCurrentIndex++) {
							$cc2 = $this->CharCode($this->parserCurrentIndex);
							if ($cc2 == 125) { $lastMultiCommentEndBrace = $this->parserCurrentIndex; }
							if ($cc2 != 42) { continue; }
							if ($this->CharCode($this->parserCurrentIndex+1) == 47) { break; }
						}
						if ($this->parserCurrentIndex == $this->input_len - 1) {
							return $this->fail("missing closing `*/`", $currentChunkStartIndex);
						}
					}
					continue;

				// *, check for unmatched */
				case 42:
					if (($this->parserCurrentIndex < $this->input_len - 1) && ($this->CharCode($this->parserCurrentIndex+1) == 47)) {
						return $this->fail("unmatched `/*`");
					}
					continue;
			}
		}

		if( $level !== 0 ){
			if( ($lastMultiComment > $lastOpening) && ($lastMultiCommentEndBrace > $lastMultiComment) ){
				return $this->fail("missing closing `}` or `*/`", $lastOpening);
			} else {
				return $this->fail("missing closing `}`", $lastOpening);
			}
		} else if ( $parenLevel !== 0 ){
			return $this->fail("missing closing `)`", $lastParen);
		}


		//chunk didn't fail


		//$this->emitChunk(true);
	}

	function CharCode($pos){
		return ord($this->input[$pos]);
	}


	function fail( $msg, $index = null ){

		if( !$index ){
			$this->index = $this->parserCurrentIndex;
		}else{
			$this->index = $index;
		}
		$this->message = 'ParseError: '.$msg;
	}


	/*
	function emitChunk( $force = false ){
		$len = $this->parserCurrentIndex - $this->emitFrom;
		if ((($len < 512) && !$force) || !$len) {
			return;
		}
		$chunks[] = substr($this->input, $this->emitFrom, $this->parserCurrentIndex + 1 - $this->emitFrom );
		$this->emitFrom = $this->parserCurrentIndex + 1;
	}
	*/

}


/**
 * Compiler Exception
 *
 * @package Less
 * @subpackage exception
 */
class Less_Exception_Compiler extends Less_Exception_Parser{

}

/**
 * Parser output with source map
 *
 * @package Less
 * @subpackage Output
 */
class Less_Output_Mapped extends Less_Output {

	/**
	 * The source map generator
	 *
	 * @var Less_SourceMap_Generator
	 */
	protected $generator;

	/**
	 * Current line
	 *
	 * @var integer
	 */
	protected $lineNumber = 0;

	/**
	 * Current column
	 *
	 * @var integer
	 */
	protected $column = 0;

	/**
	 * Array of contents map (file and its content)
	 *
	 * @var array
	 */
	protected $contentsMap = array();

	/**
	 * Constructor
	 *
	 * @param array $contentsMap Array of filename to contents map
	 * @param Less_SourceMap_Generator $generator
	 */
	public function __construct(array $contentsMap, $generator){
		$this->contentsMap = $contentsMap;
		$this->generator = $generator;
	}

	/**
	 * Adds a chunk to the stack
	 * The $index for less.php may be different from less.js since less.php does not chunkify inputs
	 *
	 * @param string $chunk
	 * @param string $fileInfo
	 * @param integer $index
	 * @param mixed $mapLines
	 */
	public function add($chunk, $fileInfo = null, $index = 0, $mapLines = null){

		//ignore adding empty strings
		if( $chunk === '' ){
			return;
		}


		$sourceLines = array();
		$sourceColumns = ' ';


		if( $fileInfo ){

			$url = $fileInfo['currentUri'];

			if( isset($this->contentsMap[$url]) ){
				$inputSource = substr($this->contentsMap[$url], 0, $index);
				$sourceLines = explode("\n", $inputSource);
				$sourceColumns = end($sourceLines);
			}else{
				throw new Exception('Filename '.$url.' not in contentsMap');
			}

		}

		$lines = explode("\n", $chunk);
		$columns = end($lines);

		if($fileInfo){

			if(!$mapLines){
				$this->generator->addMapping(
						$this->lineNumber + 1,					// generated_line
						$this->column,							// generated_column
						count($sourceLines),					// original_line
						strlen($sourceColumns),					// original_column
						$fileInfo['currentUri']
				);
			}else{
				for($i = 0, $count = count($lines); $i < $count; $i++){
					$this->generator->addMapping(
						$this->lineNumber + $i + 1,				// generated_line
						$i === 0 ? $this->column : 0,			// generated_column
						count($sourceLines) + $i,				// original_line
						$i === 0 ? strlen($sourceColumns) : 0, 	// original_column
						$fileInfo['currentUri']
					);
				}
			}
		}

		if(count($lines) === 1){
			$this->column += strlen($columns);
		}else{
			$this->lineNumber += count($lines) - 1;
			$this->column = strlen($columns);
		}

		// add only chunk
		parent::add($chunk);
	}

}

/**
 * Encode / Decode Base64 VLQ.
 *
 * @package Less
 * @subpackage SourceMap
 */
class Less_SourceMap_Base64VLQ {

	/**
	 * Shift
	 *
	 * @var integer
	 */
	private $shift = 5;

	/**
	 * Mask
	 *
	 * @var integer
	 */
	private $mask = 0x1F; // == (1 << shift) == 0b00011111

	/**
	 * Continuation bit
	 *
	 * @var integer
	 */
	private $continuationBit = 0x20; // == (mask - 1 ) == 0b00100000

	/**
	 * Char to integer map
	 *
	 * @var array
	 */
	private $charToIntMap = array(
		'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6,
		'H' => 7,'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12, 'N' => 13,
		'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18, 'T' => 19, 'U' => 20,
		'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24, 'Z' => 25, 'a' => 26, 'b' => 27,
		'c' => 28, 'd' => 29, 'e' => 30, 'f' => 31, 'g' => 32, 'h' => 33, 'i' => 34,
		'j' => 35, 'k' => 36, 'l' => 37, 'm' => 38, 'n' => 39, 'o' => 40, 'p' => 41,
		'q' => 42, 'r' => 43, 's' => 44, 't' => 45, 'u' => 46, 'v' => 47, 'w' => 48,
		'x' => 49, 'y' => 50, 'z' => 51, 0 => 52, 1 => 53, 2 => 54, 3 => 55, 4 => 56,
		5 => 57,	6 => 58, 7 => 59, 8 => 60, 9 => 61, '+' => 62, '/' => 63,
	);

	/**
	 * Integer to char map
	 *
	 * @var array
	 */
	private $intToCharMap = array(
		0 => 'A', 1 => 'B', 2 => 'C', 3 => 'D', 4 => 'E', 5 => 'F', 6 => 'G',
		7 => 'H', 8 => 'I', 9 => 'J', 10 => 'K', 11 => 'L', 12 => 'M', 13 => 'N',
		14 => 'O', 15 => 'P', 16 => 'Q', 17 => 'R', 18 => 'S', 19 => 'T', 20 => 'U',
		21 => 'V', 22 => 'W', 23 => 'X', 24 => 'Y', 25 => 'Z', 26 => 'a', 27 => 'b',
		28 => 'c', 29 => 'd', 30 => 'e', 31 => 'f', 32 => 'g', 33 => 'h', 34 => 'i',
		35 => 'j', 36 => 'k', 37 => 'l', 38 => 'm', 39 => 'n', 40 => 'o', 41 => 'p',
		42 => 'q', 43 => 'r', 44 => 's', 45 => 't', 46 => 'u', 47 => 'v', 48 => 'w',
		49 => 'x', 50 => 'y', 51 => 'z', 52 => '0', 53 => '1', 54 => '2', 55 => '3',
		56 => '4', 57 => '5', 58 => '6', 59 => '7', 60 => '8', 61 => '9', 62 => '+',
		63 => '/',
	);

	/**
	 * Constructor
	 */
	public function __construct(){
		// I leave it here for future reference
		// foreach(str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/') as $i => $char)
		// {
		//	 $this->charToIntMap[$char] = $i;
		//	 $this->intToCharMap[$i] = $char;
		// }
	}

	/**
	 * Convert from a two-complement value to a value where the sign bit is
	 * is placed in the least significant bit.	For example, as decimals:
	 *	 1 becomes 2 (10 binary), -1 becomes 3 (11 binary)
	 *	 2 becomes 4 (100 binary), -2 becomes 5 (101 binary)
	 * We generate the value for 32 bit machines, hence -2147483648 becomes 1, not 4294967297,
	 * even on a 64 bit machine.
	 * @param string $aValue
	 */
	public function toVLQSigned($aValue){
		return 0xffffffff & ($aValue < 0 ? ((-$aValue) << 1) + 1 : ($aValue << 1) + 0);
	}

	/**
	 * Convert to a two-complement value from a value where the sign bit is
	 * is placed in the least significant bit. For example, as decimals:
	 *	 2 (10 binary) becomes 1, 3 (11 binary) becomes -1
	 *	 4 (100 binary) becomes 2, 5 (101 binary) becomes -2
	 * We assume that the value was generated with a 32 bit machine in mind.
	 * Hence
	 *	 1 becomes -2147483648
	 * even on a 64 bit machine.
	 * @param integer $aValue
	 */
	public function fromVLQSigned($aValue){
		return $aValue & 1 ? $this->zeroFill(~$aValue + 2, 1) | (-1 - 0x7fffffff) : $this->zeroFill($aValue, 1);
	}

	/**
	 * Return the base 64 VLQ encoded value.
	 *
	 * @param string $aValue The value to encode
	 * @return string The encoded value
	 */
	public function encode($aValue){
		$encoded = '';
		$vlq = $this->toVLQSigned($aValue);
		do
		{
			$digit = $vlq & $this->mask;
			$vlq = $this->zeroFill($vlq, $this->shift);
			if($vlq > 0){
				$digit |= $this->continuationBit;
			}
			$encoded .= $this->base64Encode($digit);
		} while($vlq > 0);

		return $encoded;
	}

	/**
	 * Return the value decoded from base 64 VLQ.
	 *
	 * @param string $encoded The encoded value to decode
	 * @return integer The decoded value
	 */
	public function decode($encoded){
		$vlq = 0;
		$i = 0;
		do
		{
			$digit = $this->base64Decode($encoded[$i]);
			$vlq |= ($digit & $this->mask) << ($i * $this->shift);
			$i++;
		} while($digit & $this->continuationBit);

		return $this->fromVLQSigned($vlq);
	}

	/**
	 * Right shift with zero fill.
	 *
	 * @param integer $a number to shift
	 * @param integer $b number of bits to shift
	 * @return integer
	 */
	public function zeroFill($a, $b){
		return ($a >= 0) ? ($a >> $b) : ($a >> $b) & (PHP_INT_MAX >> ($b - 1));
	}

	/**
	 * Encode single 6-bit digit as base64.
	 *
	 * @param integer $number
	 * @return string
	 * @throws Exception If the number is invalid
	 */
	public function base64Encode($number){
		if($number < 0 || $number > 63){
			throw new Exception(sprintf('Invalid number "%s" given. Must be between 0 and 63.', $number));
		}
		return $this->intToCharMap[$number];
	}

	/**
	 * Decode single 6-bit digit from base64
	 *
	 * @param string $char
	 * @return number
	 * @throws Exception If the number is invalid
	 */
	public function base64Decode($char){
		if(!array_key_exists($char, $this->charToIntMap)){
			throw new Exception(sprintf('Invalid base 64 digit "%s" given.', $char));
		}
		return $this->charToIntMap[$char];
	}

}


/**
 * Source map generator
 *
 * @package Less
 * @subpackage Output
 */
class Less_SourceMap_Generator extends Less_Configurable {

	/**
	 * What version of source map does the generator generate?
	 */
	const VERSION = 3;

	/**
	 * Array of default options
	 *
	 * @var array
	 */
	protected $defaultOptions = array(
			// an optional source root, useful for relocating source files
			// on a server or removing repeated values in the 'sources' entry.
			// This value is prepended to the individual entries in the 'source' field.
			'sourceRoot'			=> '',

			// an optional name of the generated code that this source map is associated with.
			'sourceMapFilename'		=> null,

			// url of the map
			'sourceMapURL'			=> null,

			// absolute path to a file to write the map to
			'sourceMapWriteTo'		=> null,

			// output source contents?
			'outputSourceFiles'		=> false,

			// base path for filename normalization
			'sourceMapBasepath'		=> ''
	);

	/**
	 * The base64 VLQ encoder
	 *
	 * @var Less_SourceMap_Base64VLQ
	 */
	protected $encoder;

	/**
	 * Array of mappings
	 *
	 * @var array
	 */
	protected $mappings = array();

	/**
	 * The root node
	 *
	 * @var Less_Tree_Ruleset
	 */
	protected $root;

	/**
	 * Array of contents map
	 *
	 * @var array
	 */
	protected $contentsMap = array();

	/**
	 * File to content map
	 *
	 * @var array
	 */
	protected $sources = array();

	/**
	 * Constructor
	 *
	 * @param Less_Tree_Ruleset $root The root node
	 * @param array $options Array of options
	 */
	public function __construct(Less_Tree_Ruleset $root, $contentsMap, $options = array()){
		$this->root = $root;
		$this->contentsMap = $contentsMap;
		$this->encoder = new Less_SourceMap_Base64VLQ();

		$this->SetOptions($options);


		// fix windows paths
		if( isset($this->options['sourceMapBasepath']) ){
			$this->options['sourceMapBasepath'] = str_replace('\\', '/', $this->options['sourceMapBasepath']);
		}
	}

	/**
	 * Generates the CSS
	 *
	 * @return string
	 */
	public function generateCSS(){
		$output = new Less_Output_Mapped($this->contentsMap, $this);

		// catch the output
		$this->root->genCSS($output);


		$sourceMapUrl				= $this->getOption('sourceMapURL');
		$sourceMapFilename			= $this->getOption('sourceMapFilename');
		$sourceMapContent			= $this->generateJson();
		$sourceMapWriteTo			= $this->getOption('sourceMapWriteTo');

		if( !$sourceMapUrl && $sourceMapFilename ){
			$sourceMapUrl = $this->normalizeFilename($sourceMapFilename);
		}

		// write map to a file
		if( $sourceMapWriteTo ){
			$this->saveMap($sourceMapWriteTo, $sourceMapContent);
		}

		// inline the map
		if( !$sourceMapUrl ){
			$sourceMapUrl = sprintf('data:application/json,%s', Less_Functions::encodeURIComponent($sourceMapContent));
		}

		if( $sourceMapUrl ){
			$output->add( sprintf('/*# sourceMappingURL=%s */', $sourceMapUrl) );
		}

		return $output->toString();
	}

	/**
	 * Saves the source map to a file
	 *
	 * @param string $file The absolute path to a file
	 * @param string $content The content to write
	 * @throws Exception If the file could not be saved
	 */
	protected function saveMap($file, $content){
		$dir = dirname($file);
		// directory does not exist
		if( !is_dir($dir) ){
			// FIXME: create the dir automatically?
			throw new Exception(sprintf('The directory "%s" does not exist. Cannot save the source map.', $dir));
		}
		// FIXME: proper saving, with dir write check!
		if(file_put_contents($file, $content) === false){
			throw new Exception(sprintf('Cannot save the source map to "%s"', $file));
		}
		return true;
	}

	/**
	 * Normalizes the filename
	 *
	 * @param string $filename
	 * @return string
	 */
	protected function normalizeFilename($filename){
		$filename = str_replace('\\', '/', $filename);
		$basePath = $this->getOption('sourceMapBasepath');

		if( $basePath && ($pos = strpos($filename, $basePath)) !== false ){
			$filename = substr($filename, $pos + strlen($basePath));
			if(strpos($filename, '\\') === 0 || strpos($filename, '/') === 0){
				$filename = substr($filename, 1);
			}
		}
		return sprintf('%s%s', $this->getOption('sourceMapRootpath'), $filename);
	}

	/**
	 * Adds a mapping
	 *
	 * @param integer $generatedLine The line number in generated file
	 * @param integer $generatedColumn The column number in generated file
	 * @param integer $originalLine The line number in original file
	 * @param integer $originalColumn The column number in original file
	 * @param string $sourceFile The original source file
	 */
	public function addMapping($generatedLine, $generatedColumn, $originalLine, $originalColumn, $sourceFile){
		$this->mappings[] = array(
			'generated_line' => $generatedLine,
			'generated_column' => $generatedColumn,
			'original_line' => $originalLine,
			'original_column' => $originalColumn,
			'source_file' => $sourceFile
		);


		$norm_file = $this->normalizeFilename($sourceFile);

		$this->sources[$norm_file] = $sourceFile;
	}


	/**
	 * Generates the JSON source map
	 *
	 * @return string
	 * @see https://docs.google.com/document/d/1U1RGAehQwRypUTovF1KRlpiOFze0b-_2gc6fAH0KY0k/edit#
	 */
	protected function generateJson(){

		$sourceMap = array();
		$mappings = $this->generateMappings();

		// File version (always the first entry in the object) and must be a positive integer.
		$sourceMap['version'] = self::VERSION;


		// An optional name of the generated code that this source map is associated with.
		$file = $this->getOption('sourceMapFilename');
		if( $file ){
			$sourceMap['file'] = $file;
		}


		// An optional source root, useful for relocating source files on a server or removing repeated values in the 'sources' entry.	This value is prepended to the individual entries in the 'source' field.
		$root = $this->getOption('sourceRoot');
		if( $root ){
			$sourceMap['sourceRoot'] = $root;
		}


		// A list of original sources used by the 'mappings' entry.
		$sourceMap['sources'] = array_keys($this->sources);



		// A list of symbol names used by the 'mappings' entry.
		$sourceMap['names'] = array();

		// A string with the encoded mapping data.
		$sourceMap['mappings'] = $mappings;

		if( $this->getOption('outputSourceFiles') ){
			// An optional list of source content, useful when the 'source' can't be hosted.
			// The contents are listed in the same order as the sources above.
			// 'null' may be used if some original sources should be retrieved by name.
			$sourceMap['sourcesContent'] = $this->getSourcesContent();
		}

		// less.js compat fixes
		if( count($sourceMap['sources']) && empty($sourceMap['sourceRoot']) ){
			unset($sourceMap['sourceRoot']);
		}

		return json_encode($sourceMap);
	}

	/**
	 * Returns the sources contents
	 *
	 * @return array|null
	 */
	protected function getSourcesContent(){
		if(empty($this->sources)){
			return;
		}
		$content = array();
		foreach($this->sources as $sourceFile){
			$content[] = file_get_contents($sourceFile);
		}
		return $content;
	}

	/**
	 * Generates the mappings string
	 *
	 * @return string
	 */
	public function generateMappings(){

		if( !count($this->mappings) ){
			return '';
		}

		// group mappings by generated line number.
		$groupedMap = $groupedMapEncoded = array();
		foreach($this->mappings as $m){
			$groupedMap[$m['generated_line']][] = $m;
		}
		ksort($groupedMap);

		$lastGeneratedLine = $lastOriginalIndex = $lastOriginalLine = $lastOriginalColumn = 0;

		foreach($groupedMap as $lineNumber => $line_map){
			while(++$lastGeneratedLine < $lineNumber){
				$groupedMapEncoded[] = ';';
			}

			$lineMapEncoded = array();
			$lastGeneratedColumn = 0;

			foreach($line_map as $m){
				$mapEncoded = $this->encoder->encode($m['generated_column'] - $lastGeneratedColumn);
				$lastGeneratedColumn = $m['generated_column'];

				// find the index
				if( $m['source_file'] ){
					$index = $this->findFileIndex($this->normalizeFilename($m['source_file']));
					if( $index !== false ){
						$mapEncoded .= $this->encoder->encode($index - $lastOriginalIndex);
						$lastOriginalIndex = $index;

						// lines are stored 0-based in SourceMap spec version 3
						$mapEncoded .= $this->encoder->encode($m['original_line'] - 1 - $lastOriginalLine);
						$lastOriginalLine = $m['original_line'] - 1;

						$mapEncoded .= $this->encoder->encode($m['original_column'] - $lastOriginalColumn);
						$lastOriginalColumn = $m['original_column'];
					}
				}

				$lineMapEncoded[] = $mapEncoded;
			}

			$groupedMapEncoded[] = implode(',', $lineMapEncoded) . ';';
		}

		return rtrim(implode($groupedMapEncoded), ';');
	}

	/**
	 * Finds the index for the filename
	 *
	 * @param string $filename
	 * @return integer|false
	 */
	protected function findFileIndex($filename){
		return array_search($filename, array_keys($this->sources));
	}

}
