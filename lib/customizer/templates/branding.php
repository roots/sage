<?php function shoestrap_branding(){ ?>
  <div class="container-fluid logo-wrapper">
    <div class="logo container">
      <div class="row-fluid">
        <?php require_once dirname( __FILE__ ) . '/logo.php'; ?>
        <?php require_once dirname( __FILE__ ) . '/social-links.php'; ?>
      </div>
    </div>
  </div>
<?php }
add_action( 'shoestrap_branding', 'shoestrap_branding');
