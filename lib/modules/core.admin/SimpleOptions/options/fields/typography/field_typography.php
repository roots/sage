<?php
class Simple_Options_typography extends Simple_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Simple_Options 1.0.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	
	
	
	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since Simple_Options 1.0.0
	*/
	function render(){

		global $wp_filesystem;
		// Initialize the Wordpress filesystem, no more using file_put_contents function
		if (empty($wp_filesystem)) {
			require_once(ABSPATH .'/wp-admin/includes/file.php');
			WP_Filesystem();
		}  


		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}		

	// TESTING
			if( !file_exists( dirname(__FILE__) . '/googlefonts.html' ) && defined('SOF_GOOGLE_KEY') ) {
				$this->getGoogleFonts($wp_filesystem);
			}
	

		// No errors please
		$defaults = array(
			'family' => '',
			'size' => '',
			'style' => '',
			'color' => '',
			'height' => '',
			'google' => false,
			);
		$this->value = wp_parse_args( $this->value, $defaults );

		if(!empty($this->field['std'])) { 
			$this->value = wp_parse_args( $this->value, $this->field['std'] );
		}

		$units = array('px', 'em', '%');
		if (!empty($this->field['units']['type']) && in_array($this->field['units']['type'], $units)) {
			$unit = $this->field['units']['type'];
		} else {
			$unit = 'px';
		}
	
		
	  echo '<div id="'.$this->field['id'].'-container" class="sof-typography-container" data-id="'.$this->field['id'].'" data-units="'.$unit.'">';

	  /**
			Font Family
		**/
	  if (empty($field['display']['family'])):	 

	  	$output = "";

	    echo '<div class="select_wrapper typography-family" original-title="'.__('Font family','simple-options').'" style="width: 220px; margin-right: 5px;">';
	    echo '<select class="sof-typography sof-typography-family '.$class.'" id="'.$this->field['id'].'-family" name="'.$this->args['opt_name'].'['.$this->field['id'].'][family]" data-id="'.$this->field['id'].'" data-value="'.$this->value['family'].'">';
		 	echo '<optgroup label="Standard Fonts">';
	    $faces = array(
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
			
			$output = "";
			$google = false;
	    foreach ($faces as $i=>$face) {
	      $output .= '<option data-google="false" data-details="'.urlencode(json_encode(
	        array('400'=>'Normal',
	              '700'=>'Bold',
	              '400-italic'=>'Normal Italic',
	              '700-italic'=>'Bold Italic',
	            )
	        )).'" value="'. $i .'" ' . selected($this->value['family'], $i, false) . '>'. $face .'</option>';
	    }
	    $output .= '</optgroup>';
			if( !file_exists( dirname(__FILE__) . '/googlefonts.html' ) && defined('SOF_GOOGLE_KEY') ) {
				$this->getGoogleFonts($wp_filesystem);
			}

			if( file_exists( dirname(__FILE__) . '/googlefonts.html' )) {
				$output .= $wp_filesystem->get_contents(SOF_OPTIONS_URL.'fields/typography/googlefonts.html');
				$google = true;
			}	

			//$output = str_replace('"'.$this->value['family'].'"', $this->value['family'].'" selected="selected"', $output);
			
			echo $output;

	    echo '</select></div>';

	    if ($google) { // Set a flag so we know to set a header style or not
				echo '<input type="hidden" class="sof-typography-google'.$class.'" id="'.$this->field['id'].'-google" name="'.$this->args['opt_name'].'['.$this->field['id'].'][google]" type="text" value="'. $this->value['google'] .'" data-id="'.$this->field['id'].'" />';	    	
	    }
	  
	  endif;



    /** 
    Font Weight 
    **/
    if(empty($this->value['display']['style'])):
      echo '<div class="select_wrapper typography-style" original-title="'.__('Font style','simple-options').'">';
      echo '<select class="sof-typography sof-typography-style select'.$class.'" original-title="'.__('Font style','simple-options').'" name="'.$this->field['id'].'[style]" id="'. $this->field['id'].'_style" data-id="'.$this->field['id'].'">';
		 	if (empty($this->value['style'])) {
		 		echo '<option value="">Inherit</option>';
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
    if(empty($this->value['display']['script'])):
      echo '<div class="select_wrapper typography-script tooltip" original-title="'.__('Font script','simple-options').'">';
      echo '<select class="sof-typography sof-typography-script'.$class.'" original-title="'.__('Font script','simple-options').'"  id="'.$this->field['id'].'-script" name="'.$this->args['opt_name'].'['.$this->field['id'].'][script]">';
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
  	if(empty($this->value['display']['size'])):
    	echo '<div class="input-append"><input type="text" class="span2 sof-typography-size mini'.$class.'" original-title="'.__('Font size','simple-options').'" id="'.$this->field['id'].'-size" name="'.$this->args['opt_name'].'['.$this->field['id'].'][size]" value="'.$this->value['size'].'"><span class="add-on">'.$unit.'</span></div>';
  	endif;


		/**
		Line Height 
		**/
		if(empty($this->value['display']['height'])):
		 	echo '<div class="input-append"><input type="text" class="span2 sof-typography sof-typography-height mini'.$class.'" original-title="'.__('Font height','simple-options').'" id="'.$this->field['id'].'-height" name="'.$this->args['opt_name'].'['.$this->field['id'].'][height]" value="'.$this->value['height'].'"><span class="add-on">'.$unit.'</span></div>';
		endif;




    /** 
    Font Color 
    **/
    if(empty($this->value['display']['color'])):
    	$default = "";
    	if (empty($this->field['std']['color']) && !empty($this->field['color'])) {
    		$default = $this->value['color'];
			} else if (!empty($this->field['std']['color'])) {
				$default = $this->field['std']['color'];
			}
      echo '<div id="' . $this->field['id'] . '_color_picker" class="colorSelector typography-color"><div style="background-color: '.$this->value['color'].'"></div></div>';
      echo '<input data-default-color="'.$default.'" class="sof-color sof-typography-color'.$class.'" original-title="'.__('Font color','simple-options').'" id="'.$this->field['id'].'-color" name="'.$this->args['opt_name'].'['.$this->field['id'].'][color]" type="text" value="'. $this->value['color'] .'" data-id="'.$this->field['id'].'" />';
    endif;


    /**
		Font Preview
    **/
		if(empty($this->value['display']['preview'])):
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

	    echo (isset($this->field['description']) && !empty($this->field['description']))?'<div class="description">'.$this->field['description'].'</div>':'';
    endif;

	}//function
	
	
	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since Simple_Options 1.0.0
	*/
	function enqueue(){
	
		wp_enqueue_script(
			'simple-options-field-typography-js', 
			SOF_OPTIONS_URL.'fields/typography/field_typography.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_style(
			'simple-options-field-typography-css', 
			SOF_OPTIONS_URL.'fields/typography/field_typography.css', 
			time(),
			true
		);	

		wp_enqueue_script(
			'select2', 
			SOF_OPTIONS_URL.'fields/select/select2/select2.min.js', 
			array('jquery'),
			time(),
			true
		);

		wp_enqueue_script(
			'select2-init', 
			SOF_OPTIONS_URL.'fields/select/field_select.js', 
			array('jquery'),
			time(),
			true
		);		

		wp_enqueue_style(
			'select2', 
			SOF_OPTIONS_URL.'fields/select/select2/select2.css', 
			time(),
			true
		);				
		
	}//function


	/**
	 * getGoogleScript Function.
	 *
	 * Used to retrieve and append the proper stylesheet to the page.
	 *
	 * @since Simple_Options 1.0.0
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
	 * @since Simple_Options 0.2.0
	*/	
	function getGoogleFonts($wp_filesystem) {
		$sid = session_id();
		if($sid) {
		    $googleArray = $_SESSION['googleArray'];
		} else {
		    session_start();
		    $googleArray = array();
		}		

		if (empty($_SESSION['googleArray'])) :
			
			if( !file_exists( dirname(__FILE__) . '/googlefonts.json' ) ) {
		  	$result = wp_remote_get( 'https://www.googleapis.com/webfonts/v1/webfonts?key='.SOF_GOOGLE_KEY);
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
					  dirname(__FILE__) . '/googlefonts.json',
					  json_encode($googleArray),
					  FS_CHMOD_FILE // predefined mode settings for WP files
					);		
				}//if		
			}//if
			if (empty($googleArray)) {
				$googleArray = json_decode($wp_filesystem->get_contents(dirname(__FILE__) . '/googlefonts.json' ), true );
			}
			$hasGoogle = false;
			$gfonts = '<optgroup label="Google Web Fonts">';
	    foreach ($googleArray as $i => $face) {
	      $gfonts .= '<option data-details="'.urlencode(json_encode($face)).'" data-google="true" value="'.$i.'">'. $i .'</option>';
	    }
	    $gfonts .= '</optgroup>';			
	  endif;
    if (empty($googleArray)) {
			$gfonts = "";	
    }
		$wp_filesystem->put_contents(
		  dirname(__FILE__) . '/googlefonts.html',
		  $gfonts,
		  FS_CHMOD_FILE // predefined mode settings for WP files
		);	      	      
	}//function

	/**
	 * getGoogleFonts Function.
	 *
	 * Clean up the Google Webfonts subsets to be human readable
	 *
	 * @since Simple_Options 0.2.0
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
	 * @since Simple_Options 0.2.0
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
			if (strpos($v,"italic") || $v == "italic") {
				$name .= " Italic";
				$name = trim($name);
				array_push($italic, array('id'=>$v, 'name'=>$name));
			} else {
				array_push($result, array('id'=>$v, 'name'=>$name));
			}
		}
		array_push($result, array_pop($italic));
		return array_filter($result);
	}//function

	
}//class
?>