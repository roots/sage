<?php
/**
 * Enqueue scripts and stylesheets
 *
 * Enqueue scripts in the following order:
 * 1. jquery-1.10.2.min.js via Google CDN
 * 2. /theme/assets/js/site/vendor/modernizr-2.6.2.min.js
 * 3. /theme/assets/js/site/plugins.js (in footer)
 * 4. /theme/assets/js/site/main.js    (in footer)
 */
function roots_scripts() {
	wp_enqueue_style( 'roots_app', get_template_directory_uri() . '/assets/css/app.css', false, null );

	// jQuery is loaded using the same method from HTML5 Boilerplate:
	// Grab Google CDN's latest jQuery with a protocol relative URL; fallback to local if offline
	// It's kept in the header instead of footer to avoid conflicts with plugins.
	if ( !is_admin() && current_theme_supports( 'jquery-cdn' ) ) {
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false, null, false );
		add_filter( 'script_loader_src', 'roots_jquery_local_fallback', 10, 2 );
	}

	if ( is_single() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_register_script( 'modernizr', get_template_directory_uri() . '/assets/js/site/vendor/modernizr-2.6.2.min.js', false, null, false );
	wp_register_script( 'roots_plugins', get_template_directory_uri() . '/assets/js/site/src/plugins.js', array( 'jquery' ), null, true );
	wp_register_script( 'roots_main', get_template_directory_uri() . '/assets/js/site/src/main.js', array( 'jquery', 'roots_plugins' ), null, true );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'modernizr' );
	wp_enqueue_script( 'roots_plugins' );
	wp_enqueue_script( 'roots_main' );
}
add_action( 'wp_enqueue_scripts', 'roots_scripts', 100 );

// http://wordpress.stackexchange.com/a/12450
function roots_jquery_local_fallback( $src, $handle ) {
	static $add_jquery_fallback = false;

	if ( $add_jquery_fallback ) {
		echo '<script>window.jQuery || document.write(\'<script src="' . get_template_directory_uri() . '/assets/js/site/vendor/jquery-1.10.2.min.js"><\/script>\')</script>' . "\n";
		$add_jquery_fallback = false;
	}

	if ( $handle === 'jquery' ) {
		$add_jquery_fallback = true;
	}

	return $src;
}

function roots_google_analytics() { ?>
<script>
	(function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
	function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
	e=o.createElement(i);r=o.getElementsByTagName(i)[0];
	e.src='//www.google-analytics.com/analytics.js';
	r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
	ga('create','<?php echo GOOGLE_ANALYTICS_ID; ?>');ga('send','pageview');
</script>

<?php }
if ( GOOGLE_ANALYTICS_ID ) {
	add_action( 'wp_footer', 'roots_google_analytics', 20 );
}
