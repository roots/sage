<?php
/**
 * Register sidebars and widgets
 */
function shoestrap_widgets_init() {
	$class        = apply_filters( 'shoestrap_widgets_class', '' );
	$before_title = apply_filters( 'shoestrap_widgets_before_title', '<h3 class="widget-title">' );
	$after_title  = apply_filters( 'shoestrap_widgets_after_title', '</h3>' );

	// Sidebars
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'shoestrap' ),
		'id'            => 'sidebar-primary',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Secondary Sidebar', 'shoestrap' ),
		'id'            => 'sidebar-secondary',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Jumbotron', 'shoestrap' ),
		'id'            => 'jumbotron',
		'before_widget' => '<section id="%1$s"><div class="section-inner">',
		'after_widget'  => '</div></section>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>',
	));

	register_sidebar( array(
		'name'          => __( 'Header Area', 'shoestrap' ),
		'id'            => 'header-area',
		'before_widget' => '<div class="container">',
		'after_widget'  => '</div>',
		'before_title'  => '<h1>',
		'after_title'   => '</h1>',
	));

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 1', 'shoestrap' ),
		'id'            => 'sidebar-footer-1',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 2', 'shoestrap' ),
		'id'            => 'sidebar-footer-2',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 3', 'shoestrap' ),
		'id'            => 'sidebar-footer-3',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 4', 'shoestrap' ),
		'id'            => 'sidebar-footer-4',
		'before_widget' => '<section id="%1$s" class="' . $class . ' widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => $before_title,
		'after_title'   => $after_title,
	));

	// Widgets
	register_widget( 'Shoestrap_Vcard_Widget' );
}
add_action( 'widgets_init', 'shoestrap_widgets_init' );


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
