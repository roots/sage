<?php




function shoestrap_customizer_init( $wp_customize ) {

  $sections   = array();
  $sections[] = array( 'slug' => 'typography',      'title' => __( 'Typography', 'shoestrap' ),       'priority' => 6 );
  $sections[] = array( 'slug' => 'advanced',        'title' => __( 'Advanced', 'shoestrap' ),         'priority' => 10 );

  foreach( $sections as $section ){
    $wp_customize->add_section( $section['slug'], array( 'title' => $section['title'], 'priority' => $section['priority'] ) );
  }

  // Background Color hack
  $background_color = get_theme_mod( 'background_color' );
  $background_color = '#' . str_replace( '#', '', $background_color );
  set_theme_mod( 'background_color', get_theme_mod( 'color_body_bg' ) );

  $settings   = array();

  $settings[] = array(
    'setting'   => 'typography_sans_serif',
    'default'   => '"Helvetica Neue", Helvetica, Arial, sans-serif',
    'type'      => 'text',
    'label'     => __( 'Sans Serif Font Family', 'shoestrap' ),
    'section'   => 'typography', 'priority' => 3
  );

  $settings[] = array(
    'setting'   => 'typography_serif',
    'default'   => 'Georgia, "Times New Roman", Times, serif',
    'type'      => 'text',
    'label'     => __( 'Serif Font Family', 'shoestrap' ),
    'section'   => 'typography', 'priority' => 3
  );

  $settings[] = array(
    'setting'   => 'typography_font_size_base',
    'default'   => 14,
    'type'      => 'text',
    'label'     => __( 'Sans Serif Font Family', 'shoestrap' ),
    'section'   => 'typography', 'priority' => 3
  );

  $settings[] = array(
    'setting'   => 'typography_webfont_weight',
    'default'   => 400,
    'type'      => 'select',
    'label'     => __( 'Webfont weight:', 'shoestrap' ),
    'section'   => 'typography',
    'priority'  => 4,
    'choices'   => array(
      200     => __( '200', 'shoestrap' ),
      300     => __( '300', 'shoestrap' ),
      400     => __( '400', 'shoestrap' ),
      600     => __( '600', 'shoestrap' ),
      700     => __( '700', 'shoestrap' ),
      800     => __( '800', 'shoestrap' ),
      900     => __( '900', 'shoestrap' )
    )
  );

  $settings[] = array(
    'setting'   => 'typography_webfont',
    'default'   => 'latin',
    'type'      => 'select',
    'label'     => __( 'Webfont character set:', 'shoestrap' ),
    'section'   => 'typography',
    'priority'  => 5,
    'choices'   => array(
      'cyrillic'      => __( 'Cyrillic', 'shoestrap' ),
      'cyrillic-ext'  => __( 'Cyrillic Extended', 'shoestrap' ),
      'greek'         => __( 'Greek', 'shoestrap' ),
      'greek-ext'     => __( 'Greek Extended', 'shoestrap' ),
      'latin'         => __( 'Latin', 'shoestrap' ),
      'latin-ext'     => __( 'Latin Extended', 'shoestrap' ),
      'vietnamese'    => __( 'Vietnamese', 'shoestrap' )
    )
  );

  $settings[] = array(
    'setting'   => 'typography_webfont_assign',
    'default'   => 'all',
    'type'      => 'select',
    'label'     => __( 'Apply Webfont to:', 'shoestrap' ),
    'section'   => 'typography',
    'priority'  => 6,
    'choices'   => array(
      'sitename'  => __( 'Site Name', 'shoestrap' ),
      'headers'   => __( 'Headers', 'shoestrap' ),
      'all'       => __( 'Everywhere', 'shoestrap' )
    )
  );

  $settings[] = array(
    'setting'   => 'advanced_head',
    'type'      => 'textarea',
    'default'   => '<style></style>',
    'label'     => __( 'Header Scripts (CSS/JS)', 'shoestrap' ),
    'section'   => 'advanced',
    'priority'  => 1
  );

  $settings[] = array(
    'setting'   => 'advanced_footer',
    'type'      => 'textarea',
    'default'   => '<script></script>',
    'label'     => __( 'Footer Scripts (CSS/JS)', 'shoestrap' ),
    'section'   => 'advanced',
    'priority'  => 3
  );

  foreach ( $settings as $setting ) {
    if ( $setting['type'] == 'text' ) {
      $wp_customize->add_setting( $setting[ 'setting' ], array(
        'default'     => $setting['default'],
        'type'        => 'theme_mod',
        'capability'  => 'edit_theme_options'
      ));
      $wp_customize->add_control( $setting['setting'], array(
        'label'     => $setting['label'],
        'section'   => $setting['section'],
        'settings'  => $setting['setting'],
        'type'      => 'text',
        'priority'  => $setting['priority']
      ));
    } elseif ( $setting['type'] == 'radio' ) {
      $wp_customize->add_setting( $setting[ 'setting' ], array(
        'default'     => $setting['default'],
        'type'        => 'theme_mod',
        'capability'  => 'edit_theme_options'
      ));
      $wp_customize->add_control( $setting[ 'setting' ], array(
        'label'     => $setting['label'],
        'section'   => $setting['section'],
        'type'      => 'radio',
        'choices'   => $setting['choices'],
        'priority'  => $setting['priority']
      ));
    } elseif ( $setting['type'] == 'color' ) {
      $wp_customize->add_setting( $setting[ 'setting' ], array(
        'default'           => $setting['default'],
        'sanitize_callback' => 'sanitize_hex_color',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options'
      ));
      $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, $setting['setting'],array(
        'label'     => $setting['label'],
        'section'   => $setting['section'],
        'settings'  => $setting['setting'],
        'priority'  => $setting['priority'],
      )));
    } elseif ( $setting['type'] == 'image' ) {
      $wp_customize->add_setting( $setting[ 'setting' ], array(
        'default'     => $setting['default'],
        'type'        => 'theme_mod',
        'capability'  => 'edit_theme_options'
      ));
      $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, $setting['setting'], array(
        'label'     => $setting['label'],
        'section'   => $setting['section'],
        'settings'  => $setting['setting'],
        'priority'  => $setting['priority']
      )));
    } elseif ( $setting['type'] == 'select' ) {
      $wp_customize->add_setting( $setting[ 'setting' ], array(
        'default'     => $setting['default'],
        'type'        => 'theme_mod',
        'capability'  => 'edit_theme_options'
      ));
      $wp_customize->add_control( $setting['setting'], array(
        'label'     => $setting['label'],
        'section'   => $setting['section'],
        'settings'  => $setting['setting'],
        'type'      => 'select',
        'priority'  => $setting['priority'],
        'choices'   => $setting['choices']
      ));
    } elseif ( $setting['type'] == 'checkbox' ) {
      $wp_customize->add_setting( $setting[ 'setting' ], array(
        'default'     => $setting['default'],
        'type'        => 'theme_mod',
        'capability'  => 'edit_theme_options'
      ));
      $wp_customize->add_control( $setting['setting'], array(
        'label'     => $setting['label'],
        'section'   => $setting['section'],
        'settings'  => $setting['setting'],
        'type'      => 'checkbox',
        'priority'  => $setting['priority'],
      ));
    } elseif ( $setting['type'] == 'textarea' ) {
      $wp_customize->add_setting( $setting[ 'setting' ], array(
        'default'     => $setting['default'],
        'type'        => 'theme_mod',
        'capability'  => 'edit_theme_options'
      ));
      $wp_customize->add_control( new Shoestrap_Customize_Textarea_Control( $wp_customize, $setting['setting'], array(
        'label'     => $setting['label'],
        'section'   => $setting['section'],
        'settings'  => $setting['setting'],
        'priority'  => $setting['priority'],
      )));
    }
  }
}
add_action( 'customize_register', 'shoestrap_customizer_init' );

/*
 * This class creates a custom textarea control to be used in the "advanced" settings of the theme.
 * This will allow users to add their custom css & sripts right from the customizer
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
  class Shoestrap_Customize_Textarea_Control extends WP_Customize_Control {
    public $type = 'textarea';

    public function render_content() { ?>
      <label>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
      </label>
    <?php }
  }
}

/*
 * This class creates a custom control. This control is called "label"
 * and is used to display additional help between between the other controls
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
  class Shoestrap_Google_WebFont_Control extends WP_Customize_Control {
    public $type = 'select';

    public function render_content() { ?>

        <label>
          <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
          <select <?php $this->link(); ?>>
            <option value="" data-details=''>Select a Font</option>
            <?php
              $fonts = shoestrap_google_webfonts_cache();
              foreach ($fonts as $font => $details) {
                if ($this->value() == $font) {
                  $selected = ' selected="selected"';
                } else {
                  $selected = '';
                }
                ?>
                  <option value="<?php echo $font;?>" data-details='<?php echo json_encode($details)?>'<?php echo $selected; ?>><?php echo $font;?></option>
                <?php
              }
            ?>
          </select>
        </label>
    <?php }
  }
}
