<div class="sidebar-call-to-action">
  <?php if(get_field('site_wide_call_to_action_type', 'options') == "internal") { ?>
  <a href="<?php the_field('site_wide_internal', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('site_wide_call_to_action_text', 'options'); ?>"><?php the_field('site_wide_call_to_action_text', 'options'); ?></a>
  <?php } ?>

  <?php if(get_field('site_wide_call_to_action_type', 'options') == "external") { ?>
  <a href="<?php the_field('site_wide_external', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('site_wide_call_to_action_text', 'options'); ?>"><?php the_field('site_wide_call_to_action_text', 'options'); ?></a>
  <?php } ?>

  <?php if(get_field('site_wide_call_to_action_type', 'options') == "download") { ?>
  <a href="<?php the_field('site_wide_download', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('site_wide_call_to_action_text', 'options'); ?>"><i class="icon-download"></i> <?php the_field('site_wide_call_to_action_text', 'options'); ?></a>
  <?php } ?>

  <?php if(get_field('site_wide_call_to_action_type', 'options') == "form") { ?>
  <a href="<?php the_field('site_wide_form', 'options'); ?>" class="btn btn-primary btn-block btn-brochure" title="<?php the_field('site_wide_call_to_action_text', 'options'); ?>"><?php the_field('site_wide_call_to_action_text', 'options'); ?></a>
  <?php } ?>
</div>