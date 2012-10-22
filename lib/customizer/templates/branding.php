<?php function bc_customizer_branding(){ ?>
  <div class="container-fluid logo-wrapper">
    <div class="logo container">
      <div class="row-fluid">
        <?php require_once dirname( __FILE__ ) . '/logo.php'; ?>
        <?php require_once dirname( __FILE__ ) . '/social-links.php'; ?>
      </div>
    </div>
  </div>
<?php }
add_action( 'bc_core_branding', 'bc_customizer_branding');
