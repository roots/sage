<?php
function bc_customizer_hero() {
  $herotitle        = get_theme_mod( 'bc_customizer_hero_title' );
  $herocontent      = get_theme_mod( 'bc_customizer_hero_content' );
  $cta_text         = get_theme_mod( 'bc_customizer_hero_cta_text' );
  $cta_link         = get_theme_mod( 'bc_customizer_hero_cta_link' );
  $hero_visibility  = get_theme_mod( 'bc_customizer_hero_visibility' );

  $hero = false;
  if ( $hero_visibility == 'front' ) {
    if ( is_front_page() ) {
      if ( !empty( $herotitle ) )   { $hero = true; }
      if ( !empty( $herocontent ) ) { $hero = true; }
      if ( !empty( $cta_text ) )    { $hero = true; }
    }
  } else {
    if ( !empty( $herotitle ) )   { $hero = true; }
    if ( !empty( $herocontent ) ) { $hero = true; }
    if ( !empty( $cta_text ) )    { $hero = true; }
  }

  if ($hero == true) { ?>
    <div class="jumbotron masthead">
      <div class="container">

        <h1><?php echo $herotitle; ?></h1>
        <p><?php echo $herocontent; ?></p>
        <?php if ( !empty( $cta_text ) ) { ?>
          <p>
            <a href="<?php echo $cta_link; ?>" class="<?php if ( function_exists( 'bc_customizer_btn_class' ) ) { bc_customizer_btn_class(); } ?>">
              <?php echo $cta_text; ?>
            </a>
          </p>
        <?php } ?>

      </div>
    </div>
  <?php }
}
add_action( 'bc_core_hero', 'bc_customizer_hero' );