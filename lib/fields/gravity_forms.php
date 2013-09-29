<?php

/*
 * Advanced Custom Fields - Gravity Forms Multi-Select Field add-on
 * 
 * Written by @adam_pope of @stormuk
 *
 * Tags: acf, acf add-on, forms, gravity forms, custom field, form field
 * Tested up to: 3.4.1
 * Stable tag: 0.0
 * 
 * Docs: https://github.com/stormuk/Gravity-Forms-ACF-Field
 */
 
class Gravity_Forms_field extends acf_Field
{

  /*--------------------------------------------------------------------------------------
  *
  * Constructor
  * - This function is called when the field class is initalized on each page.
  * - Here you can add filters / actions and setup any other functionality for your field
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function __construct($parent)
  {
    // do not delete!
      parent::__construct($parent);
      
      // set name / title
      $this->name = 'gravity_forms_field'; // variable name (no spaces / special characters / etc)
      $this->title = __("Gravity Forms",'acf'); // field label (Displayed in edit screens)
    
    }

  
  /*--------------------------------------------------------------------------------------
  *
  * create_options
  * - this function is called from core/field_meta_box.php to create extra options
  * for your field
  *
  * @params
  * - $key (int) - the $_POST obejct key required to save the options to the field
  * - $field (array) - the field object
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function create_options($key, $field)
  {
    //role_capability
    // defaults
    $field['multiple'] = isset($field['multiple']) ? $field['multiple'] : '0';
    $field['allow_null'] = isset($field['allow_null']) ? $field['allow_null'] : '0';
    
    ?>
    <tr class="field_option field_option_<?php echo $this->name; ?>">
      <td class="label">
        <label><?php _e("Allow Null?",'acf'); ?></label>
      </td>
      <td>
<?php 
        $this->parent->create_field(array(
          'type'  =>  'radio',
          'name'  =>  'fields['.$key.'][allow_null]',
          'value' =>  $field['allow_null'],
          'choices' =>  array(
            '1' =>  'Yes',
            '0' =>  'No',
          ),
          'layout'  =>  'horizontal',
        ));
?>
      </td>
    </tr>
    <tr class="field_option field_option_<?php echo $this->name; ?>">
      <td class="label">
        <label><?php _e("Select multiple values?",'acf'); ?></label>
      </td>
      <td>
<?php 
        $this->parent->create_field(array(
          'type'  =>  'radio',
          'name'  =>  'fields['.$key.'][multiple]',
          'value' =>  $field['multiple'],
          'choices' =>  array(
            '1' =>  'Yes',
            '0' =>  'No',
          ),
          'layout'  =>  'horizontal',
        ));
?>
      </td>
    </tr>
<?php
  }
  
  
  /*--------------------------------------------------------------------------------------
  *
  * pre_save_field
  * - this function is called when saving your acf object. Here you can manipulate the
  * field object and it's options before it gets saved to the database.
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function pre_save_field($field)
  {
    // do stuff with field (mostly format options data)
    
    return parent::pre_save_field($field);
  }
  
  
  /*--------------------------------------------------------------------------------------
  *
  * create_field
  * - this function is called on edit screens to produce the html for this field
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function create_field($field)
  {

    $field['multiple'] = isset($field['multiple']) ? $field['multiple'] : false;
        
  
    // multiple select
    $multiple = '';
    if($field['multiple'] == '1')
    {
      $multiple = ' multiple="multiple" size="5" ';
      $field['name'] .= '[]';
    } 
    
    // html
    echo '<select id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';
    
    // null
    if($field['allow_null'] == '1')
    {
      echo '<option value="null"> - Select - </option>';
    }
    
    
      
    $forms = RGFormsModel::get_forms(1);      
      
          
    if($forms)
    {
      
      foreach($forms as $k => $form)
      {
        $key = $form->id;
        $value = ucfirst($form->title); 
        $selected = '';
        
        if(is_array($field['value']))
        {
          // 2. If the value is an array (multiple select), loop through values and check if it is selected
          if(in_array($key, $field['value']))
          {
            $selected = 'selected="selected"';
          }
        }
        else
        {
          // 3. this is not a multiple select, just check normaly
          if($key == $field['value'])
          {
            $selected = 'selected="selected"';
          }
        }
        
        echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';

      } 

    }         
              
    echo '</select>';
  
    
  }
  
  
  /*--------------------------------------------------------------------------------------
  *
  * admin_head
  * - this function is called in the admin_head of the edit screen where your field
  * is created. Use this function to create css and javascript to assist your 
  * create_field() function.
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function admin_head()
  {

  }
  
  
  /*--------------------------------------------------------------------------------------
  *
  * admin_print_scripts / admin_print_styles
  * - this function is called in the admin_print_scripts / admin_print_styles where 
  * your field is created. Use this function to register css and javascript to assist 
  * your create_field() function.
  *
  * @author Elliot Condon
  * @since 3.0.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function admin_print_scripts()
  {
  
  }
  
  function admin_print_styles()
  {
    
  }

  
  /*--------------------------------------------------------------------------------------
  *
  * update_value
  * - this function is called when saving a post object that your field is assigned to.
  * the function will pass through the 3 parameters for you to use.
  *
  * @params
  * - $post_id (int) - usefull if you need to save extra data or manipulate the current
  * post object
  * - $field (array) - usefull if you need to manipulate the $value based on a field option
  * - $value (mixed) - the new value of your field.
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function update_value($post_id, $field, $value)
  {
    // do stuff with value
    
    // save value
    parent::update_value($post_id, $field, $value);
  }
  
  
  
  
  
  /*--------------------------------------------------------------------------------------
  *
  * get_value
  * - called from the edit page to get the value of your field. This function is useful
  * if your field needs to collect extra data for your create_field() function.
  *
  * @params
  * - $post_id (int) - the post ID which your value is attached to
  * - $field (array) - the field object.
  *
  * @author Elliot Condon
  * @since 2.2.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function get_value($post_id, $field)
  {
    // get value
    $value = parent::get_value($post_id, $field);
    
    // format value
    
    // return value
    return $value;    
  }
  
  
  /*--------------------------------------------------------------------------------------
  *
  * get_value_for_api
  * - called from your template file when using the API functions (get_field, etc). 
  * This function is useful if your field needs to format the returned value
  *
  * @params
  * - $post_id (int) - the post ID which your value is attached to
  * - $field (array) - the field object.
  *
  * @author Elliot Condon
  * @since 3.0.0
  * 
  *-------------------------------------------------------------------------------------*/
  
  function get_value_for_api($post_id, $field)
  {
    // get value
    $value = $this->get_value($post_id, $field);
    
    // format value
    
    if(!$value)
    {
      return false;
    }
    
    if($value == 'null')
    {
      return false;
    }
    
    if(is_array($value))
    {
      foreach($value as $k => $v)
      {
        $form = RGFormsModel::get_form($v);
        $value[$k] = array();
        $value[$k] = $form;

      }
    }
    else
    {
      $value = RGFormsModel::get_form($value);
    }
    
    // return value
    return $value;
  }
  
}

?>
