<?php
/**
 *  Generates a form field to display in a search form
 */
Class WPAS_Field {
    
    private $id;
    private $label;
    private $type;
    private $format;
    private $placeholder;
    private $values;
    private $selected = '';
    private $selected_r = array();

    function __construct($id, $args = array()) {
        $defaults = array(  'label' => '',
                            'format' => 'select',
                            'placeholder' => false,
                            'values' => array()
                            );

        $this->id = $id;
        extract(wp_parse_args($args,$defaults));
        $this->label = $label;
        $this->type = $type;
        $this->format = $format;
        $this->values = $values;
        $this->placeholder = $placeholder;
        
        if (empty($values) && isset($value)) {
            $this->values = $value;
        }
        
        if(isset($_REQUEST[$id])) {
            $this->selected = $_REQUEST[$id];
            $this->selected_r = $_REQUEST[$id];
        } elseif (isset($default)) {
            $this->selected = $default;
            $this->selected_r = $default;           
        }

        if (!is_array($this->selected)) {
            $this->selected_r = explode(',',$this->selected);
        }

    }

    function build_field() {
        if ($this->format != 'hidden') {
            $output = '<div id="wpas-'.$this->id.'" class="wpas-'.$this->id.' wpas-'.$this->type.'-field  wpas-field">';
            if ($this->label) {
                $output .= '<div class="label-container"><label for="'.$this->id.'">'.$this->label.'</label></div>';
            }
        }
        switch($this->format) {
            case 'select':
                $output .= $this->select();
                break;
            case 'multi-select':
                $output .= $this->select(true);
                break;
            case 'checkbox':
                $output .= $this->checkbox();
                break;
            case 'radio':
                $output .= $this->radio();
                break;
            case 'text':
                $output .= $this->text();
                break;
            case 'textarea':
                $output .= $this->textarea();
                break;
            case 'html':
                $output .= $this->html();
                break;
            case 'hidden':
                $output .= $this->hidden();
                break;
            case 'submit':
                $output .= $this->submit();
                break;
        }
        if ($this->format != 'hidden') {
         $output .= '</div>';
        }
        return $output;
    }

    function select($multi = false) {

            if ($multi) {
                $multiple = ' multiple="multiple"';
            } else {
                $multiple = '';
            }

            $output = '<select id="'.$this->id.'" name="'.$this->id;
            if ($multi) {
                $output .= '[]';
            }
            $output .=  '"'.$multiple.'>';

            foreach ($this->values as $value => $label) {   
                $value = esc_attr($value);
                $label = esc_attr($label);
                $output .= '<option value="'.$value.'"';

                    if (in_array($value, $this->selected_r)) {
                        $output .= ' selected="selected"';
                    }

                $output .= '>'.$label.'</option>';
            }

            $output .= '</select>';
            return $output;
    }

    function checkbox() {
        $output = '<div class="wpas-'.$this->id.'-checkboxes wpas-checkboxes field-container">';
        $ctr = 1;
        foreach ($this->values as $value => $label) {
            $value = esc_attr($value);
            $label = esc_attr($label);
            $output .= '<div class="wpas-'.$this->id.'-checkbox-'.$ctr.'-container wpas-'.$this->id.'-checkbox-container wpas-checkbox-container">';
            $output .= '<input type="checkbox" id="wpas-'.$this->id.'-checkbox-'.$ctr.'" class="wpas-'.$this->id.'-checkbox wpas-checkbox" name="'.$this->id.'[]" value="'.$value.'"';
                if (in_array($value, $this->selected_r)) {
                    $output .= ' checked="checked"';
                }
            $output .= '>';
            $output .= '<label for="wpas-'.$this->id.'-checkbox-'.$ctr.'"> '.$label.'</label></div>';
            $ctr++;
        }
        $output .= '</div>';        
        return $output;
    }

    function radio() {
        $output = '<div class="wpas-'.$this->id.'-radio-buttons wpas-radio-buttons field-container">';
        $ctr = 1;
        foreach ($this->values as $value => $label) {
            $value = esc_attr($value);
            $label = esc_attr($label);
            $output .= '<div class="wpas-'.$this->id.'-radio-'.$ctr.'-container wpas-'.$this->id.'-radio-container wpas-radio-container">';
            $output .= '<input type="radio" id="wpas-'.$this->id.'-radio-'.$ctr.'" class="wpas-'.$this->id.'-radio wpas-radio" name="'.$this->id.'" value="'.$value.'"';
                if (in_array($value, $this->selected_r)) {
                    $output .= ' checked="checked"';
                }
            $output .= '>';
            $output .= '<label for="wpas-'.$this->id.'-radio-'.$ctr.'"> '.$label.'</label></div>';
            $ctr++;
        }
        $output .= '</div>';    
        return $output; 
    }

    function text() {
        if (is_array($this->selected)) {
            if (isset($this->selected[0]))
                $value = $this->selected[0];
            else
                $value = '';
        } elseif (isset($this->selected)) {
            $value = $this->selected;
        } elseif (is_array($this->values)) {
            $value = reset($this->values);
        } else {
            $value = $this->values;
        }
        $value = esc_attr($value);
        $placeholder = '';
        if ($this->placeholder)
            $placeholder = ' placeholder="'.$this->placeholder.'"';
        $output = '<input type="text" id="'.$this->id.'" value="'.$value.'" name="'.$this->id.'"'.$placeholder.'>';
        return $output;
    }

    function textarea() {
        if (is_array($this->selected)) {
            if (isset($this->selected[0]))
                $value = $this->selected[0];
            else
                $value = '';
        } elseif (isset($this->selected)) {
            $value = $this->selected;
        } elseif (is_array($this->values)) {
            $value = reset($this->values);
        } else {
            $value = $this->values;
        }
        $value = esc_textarea($value);
        $placeholder = '';
        if ($this->placeholder)
            $placeholder = ' placeholder="'.$this->placeholder.'"';
        $output = '<textarea id="'.$this->id.'" name="'.$this->id.'"'.$placeholder.'>'.$value.'</textarea>';    
        return $output; 
    }

    function submit() {
        $output = '<input type="submit" value="'.esc_attr($this->values).'">';
        return $output;
    }

    function html() {
        $output = $this->values;
        return $output;
    }

    function hidden() {
        $value = $this->values;
        if (is_array($value)) {
            $value = reset($value);
        } 
        $value = esc_attr($value);
        $output = '<input type="hidden" name="'.$this->id.'" value="'.$value.'">';
        return $output;
    }

} // Class