<?php

/**
 * vCard widget
 */
class Shoestrap_Vcard_Widget extends WP_Widget {
	private $fields = array(
		'title'          => 'Title ( optional )',
		'street_address' => 'Street Address',
		'locality'       => 'City/Locality',
		'region'         => 'State/Region',
		'postal_code'    => 'Zipcode/Postal Code',
		'tel'            => 'Telephone',
		'email'          => 'Email'
	 );

	function __construct() {
		$widget_ops = array( 'classname' => 'widget_shoestrap_vcard', 'description' => __( 'Use this widget to add a vCard', 'shoestrap' ) );

		$this->WP_Widget( 'widget_shoestrap_vcard', __( 'Shoestrap: vCard', 'shoestrap' ), $widget_ops );
		$this->alt_option_name = 'widget_shoestrap_vcard';

		add_action( 'save_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'widget_shoestrap_vcard', 'widget' );

		if ( !is_array( $cache ) ) {
			$cache = array();
		}

		if ( !isset( $args['widget_id'] ) ) {
			$args['widget_id'] = null;
		}

		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract( $args, EXTR_SKIP );

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'vCard', 'shoestrap' ) : $instance['title'], $instance, $this->id_base );

		foreach( $this->fields as $name => $label ) {
			if ( !isset( $instance[$name] ) ) { $instance[$name] = ''; }
		}

		echo $before_widget;

		if ( $title ) {
			echo $before_title, $title, $after_title;
		}
	?>
		<p class="vcard">
			<a class="fn org url" href="<?php echo home_url( '/' ); ?>"><?php bloginfo( 'name' ); ?></a><br>
			<span class="adr">
				<span class="street-address"><?php echo $instance['street_address']; ?></span><br>
				<span class="locality"><?php echo $instance['locality']; ?></span>,
				<span class="region"><?php echo $instance['region']; ?></span>
				<span class="postal-code"><?php echo $instance['postal_code']; ?></span><br>
			</span>
			<span class="tel"><span class="value"><?php echo $instance['tel']; ?></span></span><br>
			<a class="email" href="mailto:<?php echo $instance['email']; ?>"><?php echo $instance['email']; ?></a>
		</p>
	<?php
		echo $after_widget;

		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'widget_shoestrap_vcard', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance = array_map( 'strip_tags', $new_instance );

		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );

		if ( isset( $alloptions['widget_shoestrap_vcard'] ) ) {
			delete_option( 'widget_shoestrap_vcard' );
		}

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_shoestrap_vcard', 'widget' );
	}

	function form( $instance ) {
		foreach( $this->fields as $name => $label ) {
			${$name} = isset( $instance[$name] ) ? esc_attr( $instance[$name] ) : '';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>"><?php _e( "{$label}:", 'shoestrap' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $name ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $name ) ); ?>" type="text" value="<?php echo ${$name}; ?>">
		</p>
		<?php
		}
	}
}

function shoestrap_vcard_widget_init() {
	register_widget( 'Shoestrap_Vcard_Widget' );
}
add_action( 'widgets_init', 'shoestrap_vcard_widget_init' );
