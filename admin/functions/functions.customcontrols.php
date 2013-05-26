<?php

// Adds a fold class if it exists
function shoestrap_checkFold($id) {
  global $smof_details;
  //hide items in checkbox group
  $fold='';
  if (array_key_exists("fold",$smof_details[$id])) {
    if ($smof_data[$smof_details[$id]['fold']]) {
      $fold=" f_".$smof_details[$id]['fold']." ";
    } else {
      $fold=" f_".$smof_details[$id]['fold']." temphide ";
    }
  } 
  return $fold; 
}

class Customize_SMOF_Text_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;
?>

    <label class="customizer-text">
          <?php if ( $smof_details[$this->id]['name'] != "" ) { ?>
        <span class="customize-control-title">
          <?php echo esc_html( $this->label ); ?>
        </span>
      <?php } ?>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
      <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?><a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a><a href="#" class="button pointer" style="display: none;" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">P</a><?php } ?>
    </label>
        <?php
  }
}

class Customize_SMOF_Select_Control extends WP_Customize_Control {
  public $type = 'select';
  public function render_content() {
    global $smof_details;
    $value = $smof_details[$this->id];
    if ( empty( $smof_details[$this->id]['options'] ) )
      return;

    $mini ='';
    if ( !isset( $value['mod'] ) ) $value['mod'] = '';
    if ( $value['mod'] == 'mini' ) { $mini = 'mini';}
    $output .= '<div class="select_wrapper ' . $mini . '">';
    $output .= '<select data-customize-setting-link="'.$value['id'].'" class="select of-input" name="'.$value['id'].'" id="'. $value['id'] .'">';
    foreach ( $value['options'] as $select_ID => $option ) {
      $output .= '<option id="' . $select_ID . '" value="'.$option.'" ' . selected( $smof_data[$value['id']], $option, false ) . ' />'.$option.'</option>';
    }
    $output .= '</select></div>';

?>
    <label class="customizer-select">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <?php echo $output; ?>
    </label>
    <?php
  }
}
class Customize_SMOF_Textarea_Control extends WP_Customize_Control {
  public $type = 'textarea';
  public function render_content() {
    global $smof_details;
?>
        <label class="customizer-textarea">
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?> <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?><a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a><?php } ?></span>
            <textarea class="of-input" rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
        </label>
        <?php
  }
}
class Customize_SMOF_Radio_Control extends WP_Customize_Control {
  public $type = 'radio';
  public function render_content() {
    global $smof_details;
    if ( empty( $smof_details[$this->id]['options'] ) )
      return;

    $name = '_customize-radio-' . $this->id;

?>
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
    <?php
    foreach ( $smof_details[$this->id]['options'] as $value => $label ) :
?>
      <label class="customizer-radio">
        <input type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" <?php $this->link(); checked( $this->value(), $value ); ?> />
        <?php echo esc_html( $label ); ?><br/>
      </label>
      <?php
    endforeach;
  }
}
class Customize_SMOF_Checkbox_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;
?>
    <label class="customizer-checkbox">
        <input type="checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
        <strong><?php echo esc_html( $this->label ); ?></strong>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
    </label>
        <?php
  }
}

class Customize_SMOF_Multicheck_Control extends WP_Customize_Control {
  public $type = 'multicheck';
  public function render_content() {
    global $smof_details;
      $value = $smof_details[$this->id];
      //create array of defaults
      if ($value['type'] == 'multicheck'){
        if (is_array($value['std'])){
          foreach($value['std'] as $i=>$key){
            $defaults[$value['id']][$key] = true;
          }
        } else {
            $defaults[$value['id']][$value['std']] = true;
        }
      } else {
        if (isset($value['id'])) $defaults[$value['id']] = $value['std'];
      }

      (isset($smof_data[$value['id']]))? $multi_stored = $smof_data[$value['id']] : $multi_stored="";
  ?>
    <label class="customizer-multicheck">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
    </label>
  <?php
      foreach ($value['options'] as $key => $option) {
        if (!isset($multi_stored[$key])) {$multi_stored[$key] = '';}
        $of_key_string = $value['id'] . '_' . $key;
        ?>

        <label class="customizer-checkbox">
          <input type="checkbox" <?php $this->link(); ?> class="checkbox of-input" data-customize-setting-link="<?php echo $value['id'].'['.$key.']'; ?>" name="<?php echo $value['id'].'['.$key.']'; ?>" id="<?php echo $of_key_string; ?>" value="1" <?php echo checked($multi_stored[$key], 1, false); ?> />
          <?php echo $option; ?>
        </label><br />
        
        
    <?php 
      } 
    ?> 
    
        <?php


  }
}

class Customize_SMOF_Upload_Control extends WP_Customize_Control {
  public $type    = 'upload';
  public $removed = '';
  public $context;
  public $extensions = array();

  /**
   * Enqueue control related scripts/styles.
   *
   * @since 3.4.0
   */
  public function enqueue() {
    wp_enqueue_script( 'wp-plupload' );
  }

  /**
   * Refresh the parameters passed to the JavaScript via JSON.
   *
   * @since 3.4.0
   * @uses WP_Customize_Control::to_json()
   */
  public function to_json() {
    parent::to_json();

    $this->json['removed'] = $this->removed;

    if ( $this->context )
      $this->json['context'] = $this->context;

    if ( $this->extensions )
      $this->json['extensions'] = implode( ',', $this->extensions );
  }

  /**
   * Render the control's content.
   *
   * @since 3.4.0
   */
  public function render_content() {
    global $smof_details;
?>
    <label class="customizer-upload">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <div>
        <a href="#" class="button-secondary upload"><?php _e( 'Upload' ); ?></a>
        <a href="#" class="remove"><?php _e( 'Remove' ); ?></a>
      </div>
    </label>
    <?php
  }
}
class Customize_SMOF_Media_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;
?>
    <label class="customizer-mediacontrol">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}
class Customize_SMOF_Color_Control extends WP_Customize_Control {
  /**
   *
   *
   * @access public
   * @var string
   */
  public $type = 'color';

  /**
   *
   *
   * @access public
   * @var array
   */
  public $statuses;

  /**
   * Constructor.
   *
   * If $args['settings'] is not defined, use the $id as the setting ID.
   *
   * @since 3.4.0
   * @uses WP_Customize_Control::__construct()
   *
   * @param WP_Customize_Manager $manager
   * @param string  $id
   * @param array   $args
   */
  public function __construct( $manager, $id, $args = array() ) {
    $this->statuses = array( '' => __( 'Default' ) );
    parent::__construct( $manager, $id, $args );
  }

  /**
   * Enqueue control related scripts/styles.
   *
   * @since 3.4.0
   */
  public function enqueue() {
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
  }

  /**
   * Refresh the parameters passed to the JavaScript via JSON.
   *
   * @since 3.4.0
   * @uses WP_Customize_Control::to_json()
   */
  public function to_json() {
    parent::to_json();
    $this->json['statuses'] = $this->statuses;
  }

  /**
   * Render the control's content.
   *
   * @since 3.4.0
   */
  public function render_content() {
    global $smof_details;

    $this_default = $this->setting->default;
    $default_attr = '';
    if ( $this_default ) {
      if ( false === strpos( $this_default, '#' ) )
        $this_default = '#' . $this_default;
      $default_attr = ' data-default-color="' . esc_attr( $this_default ) . '"';
    }
    // The input's value gets set by JS. Don't fill it.
?>
    <label class="customizer-color">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <div class="customize-control-content">
        <input class="color-picker-hex" type="text" maxlength="7" placeholder="<?php esc_attr_e( 'Hex Value' ); ?>"<?php echo $default_attr ?> />
      </div>
    </label>
    <?php
  }
}

class Customize_SMOF_Typography_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
    <label class="customizer-typography">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}
class Customize_SMOF_Border_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
    <label class="customizer-border">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}

class Customize_SMOF_Images_Control extends WP_Customize_Control {
  public $type = 'radio';
  public function render_content() {
    global $smof_details, $smof_data;

          $i = 0;
          $value = $smof_details[$this->id];

          $select_value = (isset($smof_data[$value['id']])) ? $smof_data[$value['id']] : '';

          foreach ($value['options'] as $key => $option)
          {
          $i++;

            $checked = '';
            $selected = '';
            if(NULL!=checked($select_value, $key, false)) {
              $checked = checked($select_value, $key, false);
              $selected = 'of-radio-img-selected';
            }
            $output .= '<span>';
            //$output .= '<input type="radio" id="of-radio-img-' . $value['id'] . $i . '" class="checkbox of-radio-img-radio2" value="'.$key.'" name="'.$value['id'].'" '.$checked.' '.$this->link().' />';
            // Wordpress $this->link() won't work unless it's not in PHP style. Annoying.
            ?>
              <input type="radio" id="of-radio-img-<?php echo $value['id'] . $i; ?>" class="checkbox of-radio-img-radio" value="<?php echo $key; ?>" name="<?php echo $value['id']; ?>" <?php echo $checked; ?> <?php $this->link(); ?> />
            <?php
            $output .= '<div class="of-radio-img-label">'. $key .'</div>';
            $output .= '<img src="'.$option.'" alt="" class="of-radio-img-img '. $selected .'" rel="of-radio-img-'. $value['id'] . $i.'" />';
            $output .= '</span>';


          }
      ?>
    <label class="customizer-images">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <span class="controls">
        <?php echo $output; ?>
      </span>
    </label>

    <?php
  }
}
class Customize_SMOF_Info_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
    <label class="customizer-info">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}
class Customize_SMOF_Image_Control extends WP_Customize_Control {
  public $type = 'image';
  public $get_url;
  public $statuses;
  public $extensions = array( 'jpg', 'jpeg', 'gif', 'png' );

  protected $tabs = array();

  /**
   * Constructor.
   *
   * If $args['settings'] is not defined, use the $id as the setting ID.
   *
   * @since 3.4.0
   * @uses WP_Customize_Upload_Control::__construct()
   *
   * @param WP_Customize_Manager $manager
   * @param string  $id
   * @param array   $args
   */
  public function __construct( $manager, $id, $args ) {
    $this->statuses = array( '' => __( 'No Image' ) );

    parent::__construct( $manager, $id, $args );

    $this->add_tab( 'upload-new', __( 'Upload New' ), array( $this, 'tab_upload_new' ) );
    $this->add_tab( 'uploaded',   __( 'Uploaded' ),   array( $this, 'tab_uploaded' ) );

    // Early priority to occur before $this->manager->prepare_controls();
    add_action( 'customize_controls_init', array( $this, 'prepare_control' ), 5 );
  }

  /**
   * Prepares the control.
   *
   * If no tabs exist, removes the control from the manager.
   *
   * @since 3.4.2
   */
  public function prepare_control() {
    if ( ! $this->tabs )
      $this->manager->remove_control( $this->id );
  }

  /**
   * Refresh the parameters passed to the JavaScript via JSON.
   *
   * @since 3.4.0
   * @uses WP_Customize_Upload_Control::to_json()
   */
  public function to_json() {
    parent::to_json();
    $this->json['statuses'] = $this->statuses;
  }

  /**
   * Render the control's content.
   *
   * @since 3.4.0
   */
  public function render_content() {
    global $smof_details;

    $src = $this->value();
    if ( isset( $this->get_url ) )
      $src = call_user_func( $this->get_url, $src );

?>
    <div class="customize-image-picker">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>

      <div class="customize-control-content">
        <div class="dropdown preview-thumbnail" tabindex="0">
          <div class="dropdown-content">
            <?php if ( empty( $src ) ): ?>
              <img style="display:none;" />
            <?php else: ?>
              <img src="<?php echo esc_url( set_url_scheme( $src ) ); ?>" />
            <?php endif; ?>
            <div class="dropdown-status"></div>
          </div>
          <div class="dropdown-arrow"></div>
        </div>
      </div>

      <div class="library">
        <ul>
          <?php foreach ( $this->tabs as $id => $tab ): ?>
            <li data-customize-tab='<?php echo esc_attr( $id ); ?>' tabindex='0'>
              <?php echo esc_html( $tab['label'] ); ?>
            </li>
          <?php endforeach; ?>
        </ul>
        <?php foreach ( $this->tabs as $id => $tab ): ?>
          <div class="library-content" data-customize-tab='<?php echo esc_attr( $id ); ?>'>
            <?php call_user_func( $tab['callback'] ); ?>
          </div>
        <?php endforeach; ?>
      </div>

      <div class="actions">
        <a href="#" class="remove"><?php _e( 'Remove Image' ); ?></a>
      </div>
    </div>
    <?php
  }

  /**
   * Add a tab to the control.
   *
   * @since 3.4.0
   *
   * @param string  $id
   * @param string  $label
   * @param mixed   $callback
   */
  public function add_tab( $id, $label, $callback ) {
    $this->tabs[ $id ] = array(
      'label'    => $label,
      'callback' => $callback,
    );
  }

  /**
   * Remove a tab from the control.
   *
   * @since 3.4.0
   *
   * @param string  $id
   */
  public function remove_tab( $id ) {
    unset( $this->tabs[ $id ] );
  }

  /**
   *
   *
   * @since 3.4.0
   */
  public function tab_upload_new() {
    if ( ! _device_can_upload() ) {
?>
      <p><?php _e( 'The web browser on your device cannot be used to upload files. You may be able to use the <a href="http://wordpress.org/extend/mobile/">native app for your device</a> instead.' ); ?></p>
      <?php
    } else {
?>
      <div class="upload-dropzone">
        <?php _e( 'Drop a file here or <a href="#" class="upload">select a file</a>.' ); ?>
      </div>
      <div class="upload-fallback">
        <span class="button-secondary"><?php _e( 'Select File' ); ?></span>
      </div>
      <?php
    }
  }

  /**
   *
   *
   * @since 3.4.0
   */
  public function tab_uploaded() {
?>
    <div class="uploaded-target"></div>
    <?php
  }

  /**
   *
   *
   * @since 3.4.0
   *
   * @param string  $url
   * @param string  $thumbnail_url
   */
  public function print_tab_image( $url, $thumbnail_url = null ) {
    $url = set_url_scheme( $url );
    $thumbnail_url = ( $thumbnail_url ) ? set_url_scheme( $thumbnail_url ) : $url;
?>
    <a href="#" class="thumbnail" data-customize-image-value="<?php echo esc_url( $url ); ?>">
      <img src="<?php echo esc_url( $thumbnail_url ); ?>" />
    </a>
    <?php
  }
}
class Customize_SMOF_Slider_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
    <label class="customizer-slider">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}
class Customize_SMOF_Sorter_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
    <label class="customizer-sorter">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}
class Customize_SMOF_Titles_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
    <label class="customizer-titles">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}
class Customize_SMOF_SelectGoogleFont_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
    <label class="customizer-googlefont">
      <span class="customize-control-title">
        <?php echo esc_html( $this->label ); ?>
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </span>
      <input type="text" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
    </label>
        <?php
  }
}
class Customize_SMOF_Sliderui_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

    add_action( $this->id, array( $this, $this->id ) );

    $s_val = $s_min = $s_max = $s_step = $s_edit = '';//no errors, please
    $value = $smof_details[$this->id];
    $s_val  = $this->value;

    if ( !isset( $value['min'] ) ) { $s_min  = '0'; }else { $s_min = $value['min']; }
    if ( !isset( $value['max'] ) ) { $s_max  = $s_min + 1; }else { $s_max = $value['max']; }
    if ( !isset( $value['step'] ) ) { $s_step  = '1'; }else { $s_step = $value['step']; }

    if ( !isset( $value['edit'] ) ) {
      $s_edit  = ' readonly="readonly"';
    }
    else {
      $s_edit  = '';
    }

    if ( $s_val == '' ) $s_val = $s_min;

    //values
    $s_data = 'data-id="'.$this->id.'" data-val="'.$this->value().'" data-min="'.$s_min.'" data-max="'.$s_max.'" data-step="'.$s_step.'"';

    //html output
    $output .= '<input type="text" '.$this->get_link().' name="'.$this->id.'" id="'.$this->id.'" value="'. $this->value() .'" class="mini" '. $s_edit .' />';
    $output .= '<div id="'.$this->id.'-slider" class="smof_sliderui" style="margin-left: 7px;" '. $s_data .'></div>';
?>
      <label class="customizer-sliderui">
          <span class="customize-control-title">
            <?php echo esc_html( $this->label ); ?>
          </span>
          <?php echo $output; ?>
          <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
            <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
          <?php } ?>
      </label>
        <?php
  }
}
class Customize_SMOF_Switch_Control extends WP_Customize_Control {
  public $type = 'text';
  public function render_content() {
    global $smof_details;

?>
      <label class="customizer-switch switch-options">
        <span class="customize-control-title">
          <?php echo esc_html( $this->label ); ?>
        </span>
        <label class="cb-enable<?php if ( $this->value() != 0 ) echo " selected"; ?>" data-id="layout_sidebar_on_front"><span><?php echo $smof_details[$this->id]['on'] ? $smof_details[$this->id]['on']: "On"  ?></span></label>
        <label class="cb-disable<?php if ( $this->value() == 0 ) echo " selected"; ?>" data-id="layout_sidebar_on_front"><span><?php echo $smof_details[$this->id]['off'] ? $smof_details[$this->id]['off']: "Off"  ?></span></label>
        <input type="checkbox" id="<?php echo $this->id; ?>" class="checkbox of-input main_checkbox" name="<?php echo $this->id; ?>" <?php echo $this->get_link(); ?> value="0" <?php if ( $this->value() == 0 ) echo 'checked="checked"'; ?> />
        <?php if ( $smof_details[$this->id]['desc'] != "" ) { ?>
          <a href="#" class="button tooltip" title="<?php echo strip_tags( esc_html( $smof_details[$this->id]['desc'] ) ); ?>">?</a>
        <?php } ?>
      </label>
        <?php
  }
}
