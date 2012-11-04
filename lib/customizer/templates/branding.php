<?php

function shoestrap_branding() {
  if ( get_theme_mod( 'shoestrap_header_mode' ) == 'header' ) { ?>
    <div class="container-fluid logo-wrapper">
      <div class="logo container">
        <div class="row-fluid">
          <?php require_once dirname( __FILE__ ) . '/logo.php'; ?>
          <ul class="pull-right social-networks">
            <?php if ( has_action( 'shoestrap_branding_branding_right' ) ) { do_action( 'shoestrap_branding_branding_right' ); } ?>
          </ul>
        </div>
      </div>
    </div>
  <?php }
}
add_action( 'shoestrap_branding', 'shoestrap_branding' );
