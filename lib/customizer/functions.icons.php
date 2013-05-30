<?php

function shoestrap_icons() {
  $favicon_url        = shoestrap_getVariable( 'favicon' );
  $apple_icon_url     = shoestrap_getVariable( 'apple_icon' );

  $favicon            = matthewruddy_image_resize( $favicon_url, 32, 32, true, false );
  $iphone_icon        = matthewruddy_image_resize( $apple_icon_url, 57, 57, true, false );
  $iphone_icon_retina = matthewruddy_image_resize( $apple_icon_url, 57, 57, true, true );
  $ipad_icon          = matthewruddy_image_resize( $apple_icon_url, 72, 72, true, false );
  $ipad_icon_retina   = matthewruddy_image_resize( $apple_icon_url, 72, 72, true, true );
  ?>

  <?php if( $favicon_url != '' ) : ?>
    <link rel="shortcut icon" href="<?php echo $favicon['url'] ?>" type="image/x-icon" />
  <?php endif;?>

  <?php if( $apple_icon_url != '' ) : ?>
    <!-- For iPhone --><link rel="apple-touch-icon-precomposed" href="<?php echo $iphone_icon['url'] ?>">
    <!-- For iPhone 4 Retina display --><link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo $iphone_icon_retina['url'] ?>">
    <!-- For iPad --><link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo $ipad_icon['url'] ?>">
    <!-- For iPad Retina display --><link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo $ipad_icon_retina['url'] ?>">
  <?php endif;

  echo $content;
}
add_action( 'wp_head', 'shoestrap_icons' );
