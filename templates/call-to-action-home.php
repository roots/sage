<div class="container">
<div class="row row-action">
<div class="col-xs-12 col-sm-4 col-sm-push-4 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
  <?php if(get_field('homepage_call_to_action_type', 'options') == "internal") { ?>
  <a href="<?php the_field('homepage_internal', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('homepage_call_to_action_text', 'options'); ?>"><?php the_field('homepage_call_to_action_text', 'options'); ?> <i class="fa fa-file"></i></a>
  <?php } ?>

  <?php if(get_field('homepage_call_to_action_type', 'options') == "external") { ?>
  <a href="<?php the_field('homepage_external', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('homepage_call_to_action_text', 'options'); ?>"><?php the_field('homepage_call_to_action_text', 'options'); ?> <i class="fa fa-file"></i></a>
  <?php } ?>

  <?php if(get_field('homepage_call_to_action_type', 'options') == "download") { ?>
  <a href="<?php the_field('homepage_download', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('homepage_call_to_action_text', 'options'); ?>"><?php the_field('homepage_call_to_action_text', 'options'); ?> <i class="fa fa-file"></i> </a>
  <?php } ?>

  <?php if(get_field('homepage_call_to_action_type', 'options') == "form") { ?>
  <a href="<?php the_field('homepage_form', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('homepage_call_to_action_text', 'options'); ?>"><?php the_field('homepage_call_to_action_text', 'options'); ?> <i class="fa fa-file"></i> </a>
  <?php } ?>
</div>
</div>
</div>