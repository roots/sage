<?php get_template_part('templates/head'); ?>
<body <?php body_class(); ?>>

  <!--[if lt IE 8]>
    <div class="alert alert-warning">
      <?php _e('You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.', 'roots'); ?>
    </div>
  <![endif]-->

  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
    <div class="container boxed-container">
  <?php endif; ?>

  <?php do_action( 'get_header' ); ?>

  <?php if ( shoestrap_getVariable( 'navbar_toggle' ) == 1 ) : ?>
    <?php do_action( 'shoestrap_pre_navbar' ); ?>
    <?php if ( !has_action( 'shoestrap_header_top_navbar_override' ) ) : ?>
      <?php get_template_part( 'templates/header-top-navbar' ); ?>
    <?php else : ?>
      <?php do_action( 'shoestrap_header_top_navbar_override' ); ?>
    <?php endif; ?>
  <?php else : ?>
    <?php if ( !has_action( 'shoestrap_header_override' ) ) : ?>
      <?php get_template_part( 'templates/header' ); ?>
    <?php else : ?>
      <?php do_action( 'shoestrap_header_override' ); ?>
    <?php endif; ?>
  <?php endif; ?>

  <?php do_action( 'shoestrap_post_navbar' ); ?>

  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
    </div>
  <?php endif; ?>

  <?php if ( has_action( 'shoestrap_below_top_navbar' ) ) : ?>
    <div class="before-main-wrapper">
      <?php do_action('shoestrap_below_top_navbar'); ?>
    </div>
  <?php endif; ?>

  <?php do_action('shoestrap_pre_wrap'); ?>

  <?php if ( has_action( 'shoestrap_breadcrumbs' ) ) : ?>
    <?php do_action('shoestrap_breadcrumbs'); ?>
  <?php endif; ?>

  <?php do_action('shoestrap_header_media'); ?>

  <div class="wrap main-section <?php echo shoestrap_container_class(); ?>" role="document">

    <?php do_action('shoestrap_pre_content'); ?>

    <div class="content">
      <div class="row bg">

        <?php do_action('shoestrap_pre_main'); ?>

        <?php if ( shoestrap_section_class( 'wrap' ) ) : ?>
          <div class="mp_wrap <?php echo shoestrap_section_class( 'wrapper' ); ?>">
            <div class="row">
        <?php endif; ?>

        <main class="main <?php echo shoestrap_section_class( 'main' ); ?>" role="main">
          <?php include roots_template_path(); ?>
        </main><!-- /.main -->

        <?php do_action('shoestrap_after_main'); ?>

        <?php if ( ( shoestrap_getLayout() != 0 && ( roots_display_sidebar() ) ) || ( is_front_page() && shoestrap_getVariable( 'layout_sidebar_on_front' ) == 1 ) ) : ?>
          <?php if ( !is_front_page() || ( is_front_page() && shoestrap_getVariable( 'layout_sidebar_on_front' ) == 1 ) ) : ?>
            <aside class="sidebar <?php echo shoestrap_section_class( 'primary' ); ?>" role="complementary">
              <?php if ( !has_action( 'shoestrap_sidebar_override' ) ) : ?>
                <?php include roots_sidebar_path(); ?>
              <?php else : ?>
                <?php do_action( 'shoestrap_sidebar_override' ); ?>
              <?php endif; ?>
            </aside><!-- /.sidebar -->
          <?php endif; ?>
        <?php endif; ?>

        <?php if ( shoestrap_section_class( 'wrap' ) ) : ?>
            </div>
          </div>
        <?php endif; ?>

        <?php if ( shoestrap_getLayout() >= 3 && is_active_sidebar( 'sidebar-secondary' ) ) : ?>
          <?php if ( !is_front_page() || ( is_front_page() && shoestrap_getVariable( 'layout_sidebar_on_front' ) == 1 ) ) : ?>
            <aside class="sidebar secondary <?php echo shoestrap_section_class( 'secondary' ); ?>" role="complementary">
              <?php dynamic_sidebar( 'sidebar-secondary' ); ?>
            </aside><!-- /.sidebar -->
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div><!-- /.content -->';
    <?php do_action('shoestrap_after_content'); ?>
  </div><!-- /.wrap -->
  <?php do_action('shoestrap_after_wrap'); ?>

  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
    <div class="container boxed-container">
  <?php endif; ?>

  <?php do_action('shoestrap_pre_footer'); ?>
  <?php if ( !has_action( 'shoestrap_footer_override' ) ) : ?>
    <?php get_template_part('templates/footer'); ?>
  <?php else : ?>
    <?php do_action( 'shoestrap_footer_override' ); ?>
  <?php endif; ?>

  <?php do_action('shoestrap_after_footer'); ?>

  <?php if ( shoestrap_getVariable( 'site_style' ) == 'boxed' ) : ?>
    </div>
  <?php endif; ?>

  <?php wp_footer(); ?>

</body>
</html>