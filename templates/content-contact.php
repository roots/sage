<?php while (have_posts()) : the_post(); ?>

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php get_template_part('templates/page', 'header'); ?>
  </div>
</div>

<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">

<?php if( get_field('locations', 'options') ): ?>

	<?php while( the_repeater_field('locations', 'options') ): ?>
	<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <?php if ( the_sub_field('location_image', 'options') ) { ?>
        <img src="<?php the_sub_field('location_image', 'options'); ?>" title="<?php the_sub_field('location_title', 'options'); ?>" alt="<?php the_sub_field('location_title', 'options'); ?>" />
        <?php } ?>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><?php the_sub_field('location_title', 'options'); ?></h3>
        </div>
        <div class="panel-body">
          <address>
      		<p class="address"><?php the_sub_field('address', 'options'); ?><br/>
      		<?php the_sub_field('city', 'options'); ?>, <?php the_sub_field('state', 'options'); ?> <?php the_sub_field('zip_code', 'options'); ?> <?php the_sub_field('country', 'options'); ?></p>
      		<p class="phone"><?php the_sub_field('phone', 'options'); ?></p>
          </address>
        </div>
      </div>
    </div>
  </div>
	<?php endwhile; ?>

<?php endif; ?>

  </div>
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="well">
      <?php
          $form = get_field('contact_page_form', 'options');
          gravity_form_enqueue_scripts($form->id, true);
          gravity_form($form->id, false, false, false, '', true, 1);
      ?>
    </div>
  </div>
</div>
<?php endwhile; ?>



