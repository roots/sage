<?php
class Simple_Options_radio extends Simple_Options{	
	
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
		
		$class = (isset($this->field['class']))?' '.$this->field['class'].'" ':'';
		if (!empty($this->field['compiler']) && $this->field['compiler']) {
			$class .= " compiler";
		}

		/**
			Use data from Wordpress to populate options array
		**/
		if (!empty($this->field['data']) && empty($this->field['options'])) {
			if (empty($this->field['args'])) {
				$this->field['args'] = array();
			}
			$this->field['options'] = array();
			$args = wp_parse_args($this->field['args'], array());	
			if ($this->field['data'] == "categories" || $this->field['data'] == "category") {
				$cats = get_categories($args); 
				if (!empty($cats)) {		
					foreach ( $cats as $cat ) {
						$this->field['options'][$cat->term_id] = $cat->name;
					}//foreach
				} // If
			} else if ($this->field['data'] == "menus" || $this->field['data'] == "menu") {
				$menus = wp_get_nav_menus($args);
				if(!empty($menus)) {
					foreach ($menus as $k=>$item) {
						$this->field['options'][$item->term_id] = $item->name;
					}//foreach
				}//if
			} else if ($this->field['data'] == "pages" || $this->field['data'] == "page") {
				$pages = get_pages($args); 
				if (!empty($pages)) {
					foreach ( $pages as $page ) {
						$this->field['options'][$page->ID] = $page->post_title;
					}//foreach
				}//if
			} else if ($this->field['data'] == "posts" || $this->field['data'] == "post") {
				$posts = get_posts($args); 
				if (!empty($posts)) {
					foreach ( $posts as $post ) {
						$this->field['options'][$post->ID] = $post->post_title;
					}//foreach
				}//if
			} else if ($this->field['data'] == "post_type" || $this->field['data'] == "post_types") {
				$post_types = get_post_types($args, 'object'); 
				if (!empty($post_types)) {
					foreach ( $post_types as $k => $post_type ) {
						$this->field['options'][$k] = $post_type->labels->name;
					}//foreach
				}//if
			} else if ($this->field['data'] == "tags" || $this->field['data'] == "tag") {
				$tags = get_tags($args); 
				if (!empty($tags)) {
					foreach ( $tags as $tag ) {
						$this->field['options'][$tag->term_id] = $tag->name;
					}//foreach
				}//if
			}//if
		}//if
		
		echo '<fieldset>';
		
		if (!empty($this->field['options'])) {

			echo '<ul>';
			
			foreach($this->field['options'] as $k => $v){
				
				echo '<li>';
				echo '<label for="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'">';
				echo '<input type="radio" class="radio' . $class . '" id="'.$this->field['id'].'_'.array_search($k,array_keys($this->field['options'])).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" value="'.$k.'" '.checked($this->value, $k, false).'/>';
				echo ' <span>'.$v.'</span>';
				echo '</label>';
				echo '</li>';
				
			}//foreach
				
			echo '</ul>';		

		}

		echo '</fieldset>';

		echo (isset($this->field['description']) && !empty($this->field['description']))?'<div class="description">'.$this->field['description'].'</div>':'';
		
	}//function
	
}//class
?>