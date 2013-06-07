<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 7]><div class="alert">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</div><![endif]-->

  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) :?><div class="container boxed-container"><?php endif; ?>
  <?php
    do_action('get_header');
    // Use Bootstrap's navbar if enabled in config.php
    if (current_theme_supports('bootstrap-top-navbar')) {
      get_template_part('templates/header-top-navbar');
    } else {
      get_template_part('templates/header');
    }
  ?>
  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) :?></div><?php endif; ?>

  <?php if ( has_action( 'shoestrap_below_top_navbar' ) ) : ?>
    <div class="before-main-wrapper">
      <?php do_action('shoestrap_below_top_navbar'); ?>
    </div>
  <?php endif; ?>

  <?php do_action('shoestrap_pre_wrap'); ?>
  <div class="wrap main-section <?php echo shoestrap_container_class(); ?>" role="document">
    <?php do_action('shoestrap_pre_content'); ?>
    <div class="content row">
      <?php do_action('shoestrap_pre_main'); ?>
      <?php if ( shoestrap_section_class( 'wrap' ) ) : ?><div class="mp_wrap <?php shoestrap_section_class( 'wrapper', true ); ?>"><div class="row"><?php endif; ?>
      <div class="main <?php shoestrap_section_class( 'main', true ); ?>" role="main">
        <?php do_action('shoestrap_breadcrumbs'); ?>
        <?php include roots_template_path(); ?>
      </div><!-- /.main -->
      <?php if ( ( get_theme_mod( 'layout' ) != 0 && ( roots_display_sidebar() ) ) || ( is_front_page() && get_theme_mod( 'layout_sidebar_on_front' ) == 1 ) ) : ?>
        <?php if ( !is_front_page() || ( is_front_page() && get_theme_mod( 'layout_sidebar_on_front' ) == 1 ) ) : ?>
          <aside class="sidebar <?php shoestrap_section_class( 'primary', true ); ?>" role="complementary">
            <?php include roots_sidebar_path(); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
      <?php endif; ?>
      <?php if ( shoestrap_section_class( 'wrap' ) ) : ?></div></div><?php endif; ?>
      <?php if ( get_theme_mod( 'layout' ) >= 3 && is_active_sidebar( 'sidebar-secondary' ) ) : ?>
        <?php if ( !is_front_page() || ( is_front_page() && get_theme_mod( 'layout_sidebar_on_front' ) == 1 ) ) : ?>
          <aside class="sidebar secondary <?php shoestrap_section_class( 'secondary', true ); ?>" role="complementary">
            <?php dynamic_sidebar('sidebar-secondary'); ?>
          </aside><!-- /.sidebar -->
        <?php endif; ?>
      <?php endif; ?>
    </div><!-- /.content -->
    <?php do_action('shoestrap_after_content'); ?>
  </div><!-- /.wrap -->
  <?php do_action('shoestrap_after_wrap'); ?>

  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) :?><div class="container boxed-container"><?php endif; ?>
  <?php do_action('shoestrap_pre_footer'); ?>
  <?php get_template_part('templates/footer'); ?>
  <?php do_action('shoestrap_after_footer'); ?>
  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) :?></div><?php endif; ?>

</body>
</html>
