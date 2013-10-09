<?php get_template_part('templates/page', 'header'); ?>

<?php

	$args = array(
	'post_type' => 'atkore_location', // or define custom post type here
	'meta_key' => 'lattitude' // whatever key you have given your custom meta
	);

	$map_page = new WP_Query($args);

	if( $map_page->have_posts() ) : ?>

	<div style="display: none;">

		<?php $i = 1;

		 while ( $map_page->have_posts() ) : $map_page->the_post();

		 // just test to see if there is a lat value - if so carry on
		  if ( get_post_meta($post->ID,  'lattitude', true) !== '' ) : ?> 

		<div id="item<?php echo $i; ?>">

		<div class="entry-summary">
      <?php if ( has_post_thumbnail( get_the_id($post->ID) ) ) { the_post_thumbnail( 'small-tall' ); } else {   } ?>
    </div>

<?php 
  $address = get_field('address');
  $address2 = get_field('address2');
  $city = get_field('city');
  $state = get_field('state');
  $zip = get_field('zip');
  $email = get_field('email');
  $phone = get_field('phone');
  $fax = get_field('fax');
  ?>

		<address>
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <p><strong><?php the_field('company_name'); ?></strong></p>
    <p><?php the_field('first_name'); ?> <?php the_field('last_name'); ?></p>
    <p><?php the_field('address'); ?><br\>
    <?php the_field('address2'); ?><br\>
    <?php the_field('city'); ?>, <?php the_field('state'); ?> <?php the_field('zip'); ?>
    <?php echo '<p><abbr title="Email Address" class=""><i class="icon-envelope-alt"></i></abbr>  ' . $email . '</p>'; ?>
    <div class="row">
    <div class="col-lg-6" style="white-space:nowrap;"><?php echo '<abbr title="Phone Number" class=""><i class="icon-phone"></i></abbr> ' . $phone . ''; ?></div>
    <div class="col-lg-6" style="white-space:nowrap;"><?php echo '<abbr title="Fax Number" class=""><i class="icon-print"></i></abbr> ' . $fax . ''; ?></div>
    </div>
    <p><button class="btn btn-default btn-block btn-xs" data-toggle="modal" data-target="#request-call-back" type="button">Contact <?php the_field('first_name'); ?> <?php the_field('last_name'); ?></button></p>
		</address>
    
		</div>

		<?php endif; ?>

		<?php $i++;	?>

	<?php endwhile; ?>

	</div>

	<script type="text/javascript">

	var locations = [

		<?php  $i = 1; while( $map_page->have_posts() ) : $map_page->the_post();

		// pull out our lattitude and longitude values and concatenate to use in Google maps 
		if ( get_post_meta($post->ID,  'lattitude', true) !== '' ) : 

		$lat = get_post_meta($post->ID,  'lattitude', true); 
		$lon = get_post_meta($post->ID,  'longitude', true); 

		$latlng = "$lat" . ', ' . "$lon";

		?>
		{
		latlng : new google.maps.LatLng<?php echo '(' . $latlng . ')'; ?>, 
		info : document.getElementById('item<?php echo $i; ?>')
		},

		<?php endif; ?>

		<?php $i++; endwhile; ?>
	];

	</script>

	<div id="map-canvas" style="width:100%; height:600px;"></div>

	<?php

else :

endif;