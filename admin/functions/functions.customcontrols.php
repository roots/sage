<?php

	class Customize_Textarea_Control extends WP_Customize_Control {
	    public $type = 'textarea';
	    public function render_content() {
	    	global $smof_details;
	        ?>

	        <label>
	        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?> <?php if ($smof_details[$this->id]['desc'] != "") { ?><a href="#" class="button tooltip" title="<?php echo strip_tags($smof_details[$this->id]['desc']); ?>">?</a><?php } ?></span>
	        <textarea class="of-input" rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
	        </label>
	        <?php
	    }
	}
	class Customize_Color_Control extends WP_Customize_Control {
	    public $type = 'color';
	    public function render_content() {
	    	global $smof_details;
	        ?>

	        <label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<div class="customize-control-content">
				<input class="color-picker-hex" type="text" maxlength="7" placeholder="Hex Value" data-default-color="<?php echo esc_textarea( $this->value() ); ?>" /> <?php if ($smof_details[$this->id]['desc'] != "") { ?><a href="#" class="button tooltip" title="<?php echo strip_tags($smof_details[$this->id]['desc']); ?>">?</a><?php } ?>
			</div>
	        </label>
	        <?php
	    }
	}

	class Customize_Switch_Control extends WP_Customize_Control {
	    public $type = 'checkbox';
	    public function render_content() {
	    	global $smof_details;
	        ?>
	        <label class="switch-options">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<label class="cb-enable<? if ($this->value() == 0) echo " selected"; ?>" data-id="layout_sidebar_on_front"><span><?php echo $smof_details[$this->id]['on'] ? $smof_details[$this->id]['on']: "On"  ?></span></label>
				<label class="cb-disable<? if ($this->value() != 0) echo " selected"; ?>" data-id="layout_sidebar_on_front"><span><?php echo $smof_details[$this->id]['off'] ? $smof_details[$this->id]['off']: "Off"  ?></span></label>
				<input type="checkbox" id="<?php echo $this->id; ?>" class="checkbox of-input main_checkbox" name="<?php echo $this->id; ?>" <?php echo $this->get_link(); ?> value="0" <?php if ($this->value() == 0) echo 'checked="checked"'; ?> />
				<?php if ($smof_details[$this->id]['desc'] != "") { ?><a href="#" class="button tooltip" title="<?php echo strip_tags($smof_details[$this->id]['desc']); ?>">?</a><?php } ?>
			</label>
	        <?php
	    }
	}

	class Customize_Slider_Control extends WP_Customize_Control {
	    public $type = 'text';
	    public function render_content() {
	    	global $smof_details;
	    			add_action($this->id, array($this, $this->id));

					$s_val = $s_min = $s_max = $s_step = $s_edit = '';//no errors, please
					$value = $smof_details[$this->id];
					$s_val  = $this->value;

					if(!isset($value['min'])){ $s_min  = '0'; }else{ $s_min = $value['min']; }
					if(!isset($value['max'])){ $s_max  = $s_min + 1; }else{ $s_max = $value['max']; }
					if(!isset($value['step'])){ $s_step  = '1'; }else{ $s_step = $value['step']; }

					if(!isset($value['edit'])){
						$s_edit  = ' readonly="readonly"';
					}
					else
					{
						$s_edit  = '';
					}

					if ($s_val == '') $s_val = $s_min;

					//values
					$s_data = 'data-id="'.$this->id.'" data-val="'.$this->value().'" data-min="'.$s_min.'" data-max="'.$s_max.'" data-step="'.$s_step.'"';

					//html output
					$output .= '<input type="text" '.$this->get_link().' name="'.$this->id.'" id="'.$this->id.'" value="'. $this->value() .'" class="mini" '. $s_edit .' />';
					$output .= '<div id="'.$this->id.'-slider" class="smof_sliderui" style="margin-left: 7px;" '. $s_data .'></div>';
	        ?>
		        <label>
		        	<div class="sliderui">
						<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
						<?php echo $output; ?>
						<?php if ($smof_details[$this->id]['desc'] != "") { ?><a href="#" class="button tooltip" title="<?php echo strip_tags($smof_details[$this->id]['desc']); ?>">?</a><?php } ?>
					</div>
				</label>
	        <?php
	    }
	}


	class Customize_Text_Control extends WP_Customize_Control {
	    public $type = 'text';
	    public function render_content() {
	    	global $smof_details;
	        ?>
	        <label class="customizer-text">
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				<?php if ($smof_details[$this->id]['desc'] != "") { ?><a href="#" class="button tooltip" title="<?php echo strip_tags($smof_details[$this->id]['desc']); ?>">?</a><?php } ?>
			</label>
	        <?php
	    }
	}

/*


		switch( $this->type ) {
			case 'text':
				?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
				</label>
				<?php
				break;
			case 'checkbox':
				?>
				<label>
					<input type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
					<?php echo esc_html( $this->label ); ?>
				</label>
				<?php
				break;
			case 'radio':
				if ( empty( $this->choices ) )
					return;

				$name = '_customize-radio-' . $this->id;

				?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php
				foreach ( $this->choices as $value => $label ) :
					?>
					<label>
						<input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
						<?php echo esc_html( $label ); ?><br/>
					</label>
					<?php
				endforeach;
				break;
			case 'select':
				if ( empty( $this->choices ) )
					return;

				?>
				<label>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
					<select <?php $this->link(); ?>>
						<?php
						foreach ( $this->choices as $value => $label )
							echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . $label . '</option>';
						?>
					</select>
				</label>
				<?php
				break;
			case 'dropdown-pages':
				$dropdown = wp_dropdown_pages(
					array(
						'name'              => '_customize-dropdown-pages-' . $this->id,
						'echo'              => 0,
						'show_option_none'  => __( '&mdash; Select &mdash;' ),
						'option_none_value' => '0',
						'selected'          => $this->value(),
					)
				);

				// Hackily add in the data link parameter.
				$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

				printf(
					'<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
					$this->label,
					$dropdown
				);
				break;

				*/

