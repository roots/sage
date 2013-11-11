<?php
/**
 * Version: 1.1
 * Author: Maintain Web
 * Author URI: http://maintainweb.co
 * Tags: custom post types, post types, latest posts, sidebar widget, plugin
 * License: GPL
 */
function atkore_featured_products_init() {
	if ( !function_exists( 'register_sidebar_widget' ))
		return;

	function atkore_featured_products($args) {
		global $post;
		extract($args);

		// These are our own options
		$options = get_option( 'atkore_featured_products' );
		$title 	 = $options['title']; // Widget title
		$phead 	 = $options['phead']; // Heading format
		$ptype 	 = $options['ptype']; // Post type
		$pshow 	 = $options['pshow']; // Number of Posts

		$beforetitle = '<div class="panel-heading"><'.$phead.' class="panel-title">';
		$aftertitle = '</'.$phead.'></div>';

        // Output
		echo $before_widget;

			if ($title) echo $beforetitle . $title . $aftertitle;

			$pq = new WP_Query(array( 'post_type' => $ptype, 'showposts' => $pshow, 'orderby' => 'rand', 'category_name' => 'featured' ));
			if( $pq->have_posts() ) :
			?>

  <div id="carousel-products" class="carousel slide">
    <div class="carousel-inner">
      <?php $i = 0 ?>
  			 <?php while ($pq->have_posts()) : $pq->the_post();?>
					<div <?php if( $i == $startframe ) : $classes = array('item','active'); elseif ( $i == 1 ) : $classes = array('item',); elseif ( $i == 2 ) : $classes = array('item',); endif; post_class($classes)?>>
						<div class="info">
  						    <?php if ( has_post_thumbnail() ) { ?><div class="thumbnail-wrap"><?php the_post_thumbnail('x-small', array('class' => 'featured-product img-thumbnail')); ?></div><?php } ?>
  						    <div class="sequence-content">
              		  <h4><?php echo get_the_title(); ?></h4>
              		  <p><?php echo get_the_excerpt(); ?></p>
              		  <a class="btn btn-primary btn-featured" href="<?php the_permalink();?>" title="<?php echo get_the_title(); ?>">Read More</a>
  						</div>
    				</div>
    				</div>
          <?php $i++ ?>
          <?php endwhile; ?>
      </div><!-- /#sequence -->
    </div><!-- /#sequence-theme -->
			<?php endif; ?>


		<?php
		/* Print link to custom post_type archive page if one exists.
		if ( function_exists( 'get_post_type_archive_link' ) ) {
			$url = '';
			$label = __( 'entries' );
			$post_type = get_post_type_object( $ptype );
			if ( isset( $post_type->name ) ) {
				$url = get_post_type_archive_link( $post_type->name );
			}
			if ( isset( $post_type->labels->name ) ) {
				$label = $post_type->labels->name;
			}
			if ( ! empty( $url ) ) {
				print '<p class="latest_cpt_icon"><a href="' . esc_url( $url ) . '" rel="bookmark">' . sprintf( esc_html__( 'View all %1$s &rarr;' ), $label ) . '</a></p>';
			}
		}*/

		// echo widget closing tag
		echo $after_widget;
	}

	/**
	 * Widget settings form function
	 */
	function atkore_featured_products_control() {

		// Get options
		$options = get_option( 'atkore_featured_products' );
		// options exist? if not set defaults
		if ( !is_array( $options ))
			$options = array(
				'title' => 'Featured Products',
				'phead' => 'h3',
				'ptype' => 'product',
				'pshow' => '3'
			);
			// form posted?
			if ( isset( $_POST['latest-cpt-submit'] ) ) {
				$options['title'] = strip_tags( $_POST['latest-cpt-title'] );
				$options['phead'] = $_POST['latest-cpt-phead'];
				$options['ptype'] = $_POST['latest-cpt-ptype'];
				$options['pshow'] = $_POST['latest-cpt-pshow'];
				update_option( 'atkore_featured_products', $options );
			}
			// Get options for form fields to show
			$title = $options['title'];
			$phead = $options['phead'];
			$ptype = $options['ptype'];
			$pshow = $options['pshow'];

			// The widget form fields
			?>
			<p>
			<label for="latest-cpt-title"><?php echo __( 'Widget Title' ); ?><br />
				<input id="latest-cpt-title" name="latest-cpt-title" type="text" value="<?php echo $title; ?>" size="30" />
			</label>
			</p>
			<p>
			<label for="latest-cpt-phead"><?php echo __( 'Widget Heading Format' ); ?><br />
			<select name="latest-cpt-phead">
				<option value="h2" <?php if ($phead == 'h2') { echo 'selected="selected"'; } ?>>H2 - &lt;h2&gt;&lt;/h2&gt;</option>
				<option value="h3" <?php if ($phead == 'h3') { echo 'selected="selected"'; } ?>>H3 - &lt;h3&gt;&lt;/h3&gt;</option>
				<option value="h4" <?php if ($phead == 'h4') { echo 'selected="selected"'; } ?>>H4 - &lt;h4&gt;&lt;/h4&gt;</option>
				<option value="strong" <?php if ($phead == 'strong') { echo 'selected="selected"'; } ?>>Bold - &lt;strong&gt;&lt;/strong&gt;</option>
			</select>
			</label>
			</p>
			<p>
			<label for="latest-cpt-ptype">
			<select name="latest-cpt-ptype">
				<option value=""> - <?php echo __( 'Select Post Type' ); ?> - </option>
				<?php $args = array( 'public' => true );
				$post_types = get_post_types( $args, 'names' );
				foreach ( (array) $post_types as $post_type ) { ?>
					<option value="<?php echo $post_type; ?>" <?php if( $options['ptype'] == $post_type) { echo 'selected="selected"'; } ?>><?php echo $post_type;?></option>
				<?php }	?>
			</select>
			</label>
			</p>
			<p>
			<label for="latest-cpt-pshow"><?php echo __( 'Number of posts to show' ); ?>
				<input id="latest-cpt-pshow" name="latest-cpt-pshow" type="text" value="<?php echo $pshow; ?>" size="2" />
			</label>
			</p>
			<input type="hidden" id="latest-cpt-submit" name="latest-cpt-submit" value="1" />
	<?php
	}

	wp_register_sidebar_widget( 'widget_latest_cpt', __('Latest Custom Posts'), 'atkore_featured_products' );
	wp_register_widget_control( 'widget_latest_cpt', __('Latest Custom Posts'), 'atkore_featured_products_control', 300, 200 );

}
add_action( 'widgets_init', 'atkore_featured_products_init' );