<?php
class ReduxFramework_typography extends ReduxFramework{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		$this->googleAPIKey = $parent->args['google_api_key'];
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function render(){

		global $wp_filesystem, $ReduxFramework;

		// Initialize the Wordpress filesystem, no more using file_put_contents function
		if (empty($wp_filesystem)) {
			require_once(ABSPATH .'/wp-admin/includes/file.php');
			WP_Filesystem();
		}  
	
		// No errors please
		$defaults = array(
			'family' => true,
			'size' => true,
			'style' => true,
			'script' => false,
			'color' => true,
			'preview' => true,
			'height' => true,
			'google' => true,
			);
		$this->field = wp_parse_args( $this->field, $defaults );

		$defaults = array(
			'family'=>'',
			'height'=>'',
			'style'=>'',
			'script'=>'',
			'color'=>'',
			'height'=>'',
			'size'=>'',
		);

		$this->value = wp_parse_args( $this->value, $defaults );

		if(!empty($this->field['default'])) { 
			$this->value = wp_parse_args( $this->value, $this->field['default'] );
		}

		$units = array('px', 'em', '%');
		if (!empty($this->field['units']) && in_array($this->field['units'], $units)) {
			$unit = $this->field['units'];
		} else {
			$unit = 'px';
		}
	
		
	  echo '<div id="'.$this->field['id'].'-container" class="redux-typography-container" data-id="'.$this->field['id'].'" data-units="'.$unit.'">';

	  /**
			Font Family
		**/
	  if ($this->field['family'] === true):

	    echo '<div class="select_wrapper typography-family" style="width: 220px; margin-right: 5px;">';
	    echo '<select data-placeholder="'.__('Font family','redux-framework').'" class="redux-typography redux-typography-family '.$this->field['class'].'" id="'.$this->field['id'].'-family" name="'.$this->args['opt_name'].'['.$this->field['id'].'][family]" data-id="'.$this->field['id'].'" data-value="'.$this->value['family'].'">';
		 	echo '<option data-google="false" data-details="" value=""></option>';
		 	echo '<optgroup label="Standard Fonts">';

		 	if (empty($this->field['fonts'])) {
   			$this->field['fonts'] = array(
		      "Arial, Helvetica, sans-serif" => "Arial, Helvetica, sans-serif",
		      "'Arial Black', Gadget, sans-serif" => "'Arial Black', Gadget, sans-serif",
		      "'Bookman Old Style', serif" => "'Bookman Old Style', serif",
		      "'Comic Sans MS', cursive" => "'Comic Sans MS', cursive",
		      "Courier, monospace" => "Courier, monospace",
		      "Garamond, serif" => "Garamond, serif",
		      "Georgia, serif" => "Georgia, serif",
		      "Impact, Charcoal, sans-serif" => "Impact, Charcoal, sans-serif",
		      "'Lucida Console', Monaco, monospace" => "'Lucida Console', Monaco, monospace",
		      "'Lucida Sans Unicode', 'Lucida Grande', sans-serif" => "'Lucida Sans Unicode', 'Lucida Grande', sans-serif",
		      "'MS Sans Serif', Geneva, sans-serif" => "'MS Sans Serif', Geneva, sans-serif",
		      "'MS Serif', 'New York', sans-serif" =>"'MS Serif', 'New York', sans-serif",
		      "'Palatino Linotype', 'Book Antiqua', Palatino, serif" => "'Palatino Linotype', 'Book Antiqua', Palatino, serif",
		      "Tahoma, Geneva, sans-serif" =>"Tahoma, Geneva, sans-serif",
		      "'Times New Roman', Times, serif" => "'Times New Roman', Times, serif",
		      "'Trebuchet MS', Helvetica, sans-serif" => "'Trebuchet MS', Helvetica, sans-serif",
		      "Verdana, Geneva, sans-serif" => "Verdana, Geneva, sans-serif",
		    );
		 	}
			
			if ($this->field['google'] === true) {
				$google = false;
			}
			// Standard sizes for normal fonts
			$font_sizes = urlencode( json_encode( array( '400'=>'Normal 400', '700'=>'Bold 700', '400-italic'=>'Normal 400 Italic', '700-italic'=>'Bold 700 Italic' ) ) );
	    foreach ($this->field['fonts'] as $i=>$family) {
	      echo '<option data-google="false" data-details="'.$font_sizes.'" value="'. $i .'"' . selected($this->value['family'], $i, false) . '>'. $family .'</option>';
	    }
			if ($this->field['google'] === true) {
		    echo '</optgroup>';
				if( !file_exists( REDUX_DIR.'inc/fields/typography/googlefonts.html' ) && !empty($ReduxFramework->args['google_api_key']) ) {
					$this->getGoogleFonts($wp_filesystem);
				}

				if( file_exists( REDUX_DIR.'inc/fields/typography/googlefonts.html' )) {
					echo $wp_filesystem->get_contents(REDUX_DIR.'inc/fields/typography/googlefonts.html');
					$google = true;
				}
			}

	    echo '</select></div>';

	    if ($this->field['google'] === true) { // Set a flag so we know to set a header style or not
				echo '<input type="hidden" class="redux-typography-google'.$this->field['class'].'" id="'.$this->field['id'].'-google" name="'.$this->args['opt_name'].'['.$this->field['id'].'][google]" type="text" value="'. $this->field['google'] .'" data-id="'.$this->field['id'].'" />';
	    }
	  
	  endif;



    /** 
    Font Style/Weight 
    **/
    if ($this->field['style'] === true):
      echo '<div class="select_wrapper typography-style" original-title="'.__('Font style','redux-framework').'">';
      echo '<select data-placeholder="'.__('Style','redux-framework').'" class="redux-typography redux-typography-style select'.$this->field['class'].'" original-title="'.__('Font style','redux-framework').'" name="'.$this->args['opt_name'].'['.$this->field['id'].'][style]" id="'. $this->field['id'].'_style" data-id="'.$this->field['id'].'" data-value="'.$this->value['style'].'">';
		 	if (empty($this->value['style'])) {
		 		echo '<option value=""></option>';
		 	}
      $styles = array('100'=>'Ultra-Light 100',
                '200'=>'Light 200',
                '300'=>'Book 300',
                '400'=>'Normal 400',
                '500'=>'Medium 500',
                '600'=>'Semi-Bold 600',
                '700'=>'Bold 700',
                '800'=>'Extra-Bold 800',
                '900'=>'Ultra-Bold 900',
                '100-italic'=>'Ultra-Light 100 Italic',
                '200-italic'=>'Light 200 Italic',
                '300-italic'=>'Book 300 Italic',
                '400-italic'=>'Normal 400 Italic',
                '500-italic'=>'Medium 500 Italic',
                '600-italic'=>'Semi-Bold 600 Italic',
                '700-italic'=>'Bold 700 Italic',
                '800-italic'=>'Extra-Bold 800 Italic',
                '900-italic'=>'Ultra-Bold 900 Italic',
              );
      $nonGStyles = array('200'=>'Lighter','400'=>'Normal','700'=>'Bold','900'=>'Bolder');
      if (isset($gfonts[$this->value['family']])) {
        $styles = array();
        foreach ($gfonts[$this->value['family']]['variants'] as $k=>$v) {
          echo '<option value="'. $v['id'] .'" ' . selected($this->value['style'], $v['id'], false) . '>'. $v['name'] .'</option>';
        }
      } else {
        foreach ($nonGStyles as $i=>$style){
          if (!isset($this->value['style']))
            $this->value['style'] = false;
          echo '<option value="'. $i .'" ' . selected($this->value['style'], $i, false) . '>'. $style .'</option>';
        }
      }

      echo '</select></div>';

    endif;


    /** 
    Font Script 
    **/
    if ($this->field['style'] === true || $this->field['google'] === true):
      echo '<div class="select_wrapper typography-script tooltip" original-title="'.__('Font script','redux-framework').'">';
      echo '<select data-placeholder="'.__('Script','redux-framework').'" class="redux-typography redux-typography-script'.$this->field['class'].'" original-title="'.__('Font script','redux-framework').'"  id="'.$this->field['id'].'-script" name="'.$this->args['opt_name'].'['.$this->field['id'].'][script]" data-value="'.$this->value['script'].'">';
		 	if (empty($this->value['script'])) {
		 		echo '<option value=""></option>';
		 	}
      if (isset($gfonts[$this->value['family']])) {
        $styles = array();
        foreach ($gfonts[$this->value['family']]['subsets'] as $k=>$v) {
          echo '<option value="'. $v['id'] .'" ' . selected($this->value['style'], $v['id'], false) . '>'. $v['name'] .'</option>';
        }
      }
      echo '</select></div>';

    endif;


		/**
		Font Size
		**/
	if ($this->field['size'] === true):
	echo '<div class="input-append"><input type="text" class="span2 redux-typography-size mini'.$this->field['class'].'" placeholder="'.__('Size','redux-framework').'" id="'.$this->field['id'].'-size" name="'.$this->args['opt_name'].'['.$this->field['id'].'][size]" value="'.$this->value['size'].'" data-value="'.$this->value['size'].'"><span class="add-on">'.$unit.'</span></div>';
  	endif;


		/**
		Line Height 
		**/
		if ($this->field['height'] === true):
			echo '<div class="input-append"><input type="text" class="span2 redux-typography redux-typography-height mini'.$this->field['class'].'" placeholder="'.__('Height','redux-framework').'" id="'.$this->field['id'].'-height" name="'.$this->args['opt_name'].'['.$this->field['id'].'][height]" value="'.$this->value['height'].'" data-value="'.$this->value['height'].'"><span class="add-on">'.$unit.'</span></div>';
		endif;




    /** 
    Font Color 
    **/
    if ($this->field['color'] === true):
    	$default = "";
    	if (empty($this->field['default']['color']) && !empty($this->field['color'])) {
    		$default = $this->value['color'];
			} else if (!empty($this->field['default']['color'])) {
				$default = $this->field['default']['color'];
			}
      echo '<div id="' . $this->field['id'] . '_color_picker" class="colorSelector typography-color"><div style="background-color: '.$this->value['color'].'"></div></div>';
      echo '<input data-default-color="'.$default.'" class="redux-color redux-typography-color'.$this->field['class'].'" original-title="'.__('Font color','redux-framework').'" id="'.$this->field['id'].'-color" name="'.$this->args['opt_name'].'['.$this->field['id'].'][color]" type="text" value="'. $this->value['color'] .'" data-id="'.$this->field['id'].'" />';
    endif;


    /**
		Font Preview
    **/
		if ($this->field['preview'] === true):
	    if(isset($value['preview']['text'])){
	      $g_text = $value['preview']['text'];
	    } else {
	      $g_text = '0123456789 ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz';
	    }
	    if(isset($value['preview']['size'])) {
	      $g_size = 'style="font-size: '. $value['preview']['size'] .';"';
	    } else {
	      $g_size = '';
	    }

	    echo '<p class="'.$this->field['id'].'_previewer typography-preview" '. $g_size .'>'. $g_text .'</p>';
	    echo "</div>";

	    echo (isset($this->field['desc']) && !empty($this->field['desc']))?'<div class="description">'.$this->field['desc'].'</div>':'';
    endif;

	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function enqueue(){
		wp_enqueue_script( 'select2-js' );
		wp_enqueue_style( 'select2-css' );	

		wp_enqueue_script(
			'redux-field-typography-js', 
			REDUX_URL.'inc/fields/typography/field_typography.min.js', 
			array('jquery', 'wp-color-picker', 'redux-field-color-js', 'select2-js', 'jquery-numeric'),
			time(),
			true
		);

		wp_enqueue_style(
			'redux-field-typography-css', 
			REDUX_URL.'inc/fields/typography/field_typography.css', 
			time(),
			true
		);	
			
		
	}//function


	/**
	 * getGoogleScript Function.
	 *
	 * Used to retrieve and append the proper stylesheet to the page.
	 *
	 * @since ReduxFramework 1.0.0
	*/
	function getGoogleScript($font) {
	  $link = 'http://fonts.googleapis.com/css?family='.str_replace(" ","+",$font['face']);
	  if (!empty($font['style']))
	    $link .= ':'.str_replace('-','',$font['style']);
	  if (!empty($font['script']))
	    $link .= '&subset='.$font['script'];

	  return '<link href="'.$link.'" rel="stylesheet" type="text/css" class="base_font">';
	}

	/**
	 * getGoogleFonts Function.
	 *
	 * Used to retrieve Google Web Fonts from their API
	 *
	 * @since ReduxFramework 0.2.0
	*/	
	function getGoogleFonts($wp_filesystem) {
		
/*
		$sid = session_id();
		if($sid) {
		    $googleArray = $_SESSION['googleArray'];
		} else {
		    session_start();
		    $googleArray = array();
		}

		if (empty($_SESSION['googleArray'])) :
			*/
			if( !file_exists( REDUX_DIR.'inc/fields/typography/googlefonts.json' ) ) {
		  	$result = wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts?key='.$this->googleAPIKey);
		  	if ($result['response']['code'] == 200) {
		  		$result = json_decode($result['body']);
		  		$res = array();
					foreach ($result->items as $font) {
						$googleArray[$font->family] = array(
							'variants' => $this->getVariants($font->variants),
							'subsets' => $this->getSubsets($font->subsets)
						);
					}
					
					$wp_filesystem->put_contents(
					  REDUX_DIR.'inc/fields/typography/googlefonts.json',
					  json_encode($googleArray),
					  FS_CHMOD_FILE // predefined mode settings for WP files
					);		
				}//if		
			}//if
			if (empty($googleArray)) {
				$googleArray = json_decode($wp_filesystem->get_contents(REDUX_DIR.'inc/fields/typography/googlefonts.json' ), true );
			}
			$hasGoogle = false;
			$gfonts = '<optgroup label="Google Web Fonts">';
	    foreach ($googleArray as $i => $face) {
	      $gfonts .= '<option data-details="'.urlencode(json_encode($face)).'" data-google="true" value="'.$i.'">'. $i .'</option>';
	    }
	    $gfonts .= '</optgroup>';			
	  //endif;
    if (empty($googleArray)) {
			$gfonts = "";	
    }

		$wp_filesystem->put_contents(
		  REDUX_DIR.'inc/fields/typography/googlefonts.html',
		  $gfonts,
		  FS_CHMOD_FILE // predefined mode settings for WP files
		);	      	      
	}//function

	/**
	 * getGoogleFonts Function.
	 *
	 * Clean up the Google Webfonts subsets to be human readable
	 *
	 * @since ReduxFramework 0.2.0
	*/	
	function getSubsets($var) {
		$result = array();
		foreach ($var as $v) {
			if (strpos($v,"-ext")) {
				$name = ucfirst(str_replace("-ext"," Extended",$v));
			} else {
				$name = ucfirst($v);
			}
			array_push($result, array('id'=>$v, 'name'=>$name));
		}
		return array_filter($result);
	}//function

	/**
	 * getGoogleFonts Function.
	 *
	 * Clean up the Google Webfonts variants to be human readable
	 *
	 * @since ReduxFramework 0.2.0
	*/		
	function getVariants($var) {
		$result = array();
		$italic = array();
		foreach ($var as $v) {
			$name = "";
			if ($v[0] == 1) {
				$name = 'Ultra-Light 100';
			} else if ($v[0] == 2) {
				$name = 'Light 200';
			} else if ($v[0] == 3) {
				$name = 'Book 300';
			} else if ($v[0] == 4 || $v[0] == "r" || $v[0] == "i") {
				$name = 'Normal 400';
			} else if ($v[0] == 5) {
				$name = 'Medium 500';
			} else if ($v[0] == 6) {
				$name = 'Semi-Bold 600';
			} else if ($v[0] == 7) {
				$name = 'Bold 700';
			} else if ($v[0] == 8) {
				$name = 'Extra-Bold 800';
			} else if ($v[0] == 9) {
				$name = 'Ultra-Bold 900';
			}
			if ($v == "regular") {
				$v = "400";
			}
			if (strpos($v,"italic") || $v == "italic") {
				$name .= " Italic";
				$name = trim($name);
				$italic[] = array('id'=>$v, 'name'=>$name);
			} else {
				$result[] = array('id'=>$v, 'name'=>$name);
			}
		}

		array_push($result, array_pop($italic));

		return array_filter($result);
	}//function

	
}//class
?>
