<?php

/**
 * Options Sorter Field for Redux Options
 * @author  Yannis - Pastis Glaros <mrpc@pramnoshosting.gr>
 * @url     http://www.pramhost.com
 * @license [http://www.gnu.org/copyleft/gpl.html GPLv3
 *
 * This is actually based on: [SMOF - Slightly Modded Options Framework](http://aquagraphite.com/2011/09/slightly-modded-options-framework/)
 * Original Credits:
 * Author		: Syamil MJ
 * Author URI   	: http://aquagraphite.com
 * License		: GPLv3 - http://www.gnu.org/copyleft/gpl.html
 * Credits		: Thematic Options Panel - http://wptheming.com/2010/11/thematic-options-panel-v2/
  KIA Thematic Options Panel - https://github.com/helgatheviking/thematic-options-KIA
  Woo Themes - http://woothemes.com/
  Option Tree - http://wordpress.org/extend/plugins/option-tree/
 * Twitter: http://twitter.com/syamilmj
 * Website: http://aquagraphite.com
 */
class ReduxFramework_sorter extends ReduxFramework {

    /**
     * Field Constructor.
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     * @since Redux_Options 1.0.0
     */
    function __construct($field = array(), $value = '', $parent) {
        parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
        $this->field = $field;
        $this->value = $value;
        if (!is_array($this->value) && isset($this->field['options'])) {
            $this->value = $this->field['options'];
        }
    }

    /**
     * Field Render Function.
     * Takes the vars and outputs the HTML for the field in the settings
     * @since 1.0.0
     */
    function render() {

		// Make sure to get list of all the default blocks first
	    $all_blocks = !empty( $this->field['options'] ) ? $this->field['options'] : array();

	    $temp = array(); // holds default blocks
	    $temp2 = array(); // holds saved blocks

		foreach($all_blocks as $blocks) {
		    $temp = array_merge($temp, $blocks);
		}

	    $sortlists = $this->value;

	    if ( is_array( $sortlists ) ) {
		    foreach( $sortlists as $sortlist ) {
				$temp2 = array_merge($temp2, $sortlist);
		    }

		    // now let's compare if we have anything missing
		    foreach($temp as $k => $v) {
				if(!array_key_exists($k, $temp2)) {
				    $sortlists['disabled'][$k] = $v;
				}
		    }

		    // now check if saved blocks has blocks not registered under default blocks
		    foreach( $sortlists as $key => $sortlist ) {
				foreach($sortlist as $k => $v) {
				    if(!array_key_exists($k, $temp)) {
					unset($sortlist[$k]);
				    }
				}
				$sortlists[$key] = $sortlist;
		    }

		    // assuming all sync'ed, now get the correct naming for each block
		    foreach( $sortlists as $key => $sortlist ) {
				foreach($sortlist as $k => $v) {
				    $sortlist[$k] = $temp[$k];
				}
				$sortlists[$key] = $sortlist;
		    }

			

			    if ($sortlists) {
			    	echo '<fieldset id="'.$this->field['id'].'" class="redux-sorter-container sorter">';

					foreach ($sortlists as $group=>$sortlist) {

					    echo '<ul id="'.$this->field['id'].'_'.$group.'" class="sortlist_'.$this->field['id'].'">';
					    echo '<h3>'.$group.'</h3>';

					    foreach ($sortlist as $key => $list) {

							echo '<input class="sorter-placebo" type="hidden" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $group . '][placebo]" value="placebo">';

							if ($key != "placebo") {

							    echo '<li id="'.$key.'" class="sortee">';
							    echo '<input class="position '.$this->field['class'].'" type="hidden" name="' . $this->args['opt_name'] . '[' . $this->field['id'] . '][' . $group . '][' . $key . ']" value="'.$list.'">';
							    echo $list;
							    echo '</li>';

							}

					    }

					    echo '</ul>';
					}
					echo '</fieldset>';
			    }
		    }

	        
        
    }

    function enqueue() {
        wp_enqueue_script('jquery-ui-sortable');
        wp_register_script('options-sorter', ReduxFramework::$_url . 'inc/fields/sorter/field_sorter.min.js', array(
            'jquery'));
        wp_register_style('options-sorter', ReduxFramework::$_url . 'inc/fields/sorter/field_sorter.css');
        wp_enqueue_script('options-sorter');
        wp_enqueue_style('options-sorter');
    }

}
