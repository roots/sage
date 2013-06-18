<?php
if ( !has_action( 'shoestrap_page_header_override' ) ) { ?>

<div class="page-header">
  <h1>
    <?php echo roots_title(); ?>
  </h1>
</div>

<?php } else { do_action( 'shoestrap_page_header_override' ); } ?>