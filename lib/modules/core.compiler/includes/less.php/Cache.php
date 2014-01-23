<?php


class Less_Cache{

	public static $cache_dir = false;		// directory less.php can use for storing data
	public static $import_dirs = array();
	private static $use_cache = true;

    const cache_version = '1513';
	protected static $clean_cache = true;


	public static function Get( $less_files, $parser_options = array() ){

		//check $cache_dir
		if( empty(self::$cache_dir) ){
			throw new Exception('cache_dir not set');
			return false;
		}

		self::$cache_dir = str_replace('\\','/',self::$cache_dir);
		self::$cache_dir = rtrim(self::$cache_dir,'/').'/';

		if( !is_dir(self::$cache_dir) ){
			throw new Exception('cache_dir does not exist');
			return false;
		}

		// generate name for compiled css file
		$less_files = (array)$less_files;
		$hash = md5(json_encode($less_files));
 		$list_file = self::$cache_dir.'lessphp_'.$hash.'.list';


		if( self::$use_cache === true ){

	 		// check cached content
			$compiled_file = false;
			$less_cache = false;
	 		if( file_exists($list_file) ){


				$list = explode("\n",file_get_contents($list_file));
				$compiled_name = self::CompiledName($list);
				$compiled_file = self::$cache_dir.$compiled_name;
				if( file_exists($compiled_file) ){
					touch($list_file);
					touch($compiled_file);
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
		file_put_contents( self::$cache_dir.$compiled_name, $compiled );


		//clean up
		self::CleanCache();
		self::$use_cache = true;

		return $compiled_name;

	}

	public static function Regen( $less_files, $parser_options = array() ){
		self::$use_cache = false;
		return self::Get( $less_files, $parser_options );
	}

	public static function Cache( &$less_files, $parser_options = array() ){


		// get less.php if it exists
		$file = dirname(__FILE__) . '/Less.php';
		if( file_exists($file) && !class_exists('Less_Parser') ){
			require_once($file);
		}


		$parser = new Less_Parser($parser_options);
		$parser->SetCacheDir( self::$cache_dir );
		$parser->SetImportDirs( self::$import_dirs );


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
		$temp = array(self::cache_version);
		foreach($files as $file){
			$temp[] = filemtime($file)."\t".filesize($file)."\t".$file;
		}

		return 'lessphp_'.sha1(json_encode($temp)).'.css';
	}


	public static function CleanCache(){
		static $clean = false;

		if( $clean ){
			return;
		}

		$files = scandir(self::$cache_dir);
		if( $files ){
			$check_time = time() - 604800;
			foreach($files as $file){
				if( strpos($file,'lessphp_') !== 0 ){
					continue;
				}
				$full_path = self::$cache_dir.'/'.$file;
				if( filemtime($full_path) > $check_time ){
					continue;
				}
				unlink($full_path);
			}
		}

		$clean = true;
	}

}