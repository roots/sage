<?php

function shoestrap_login_logo() {
    if ( get_theme_mod( 'shoestrap_logo' ) ) {
      echo( get_theme_mod( 'shoestrap_logo' ) );
    }
}

function shoestrap_login_scripts() {
  $color                  = get_theme_mod('background_color' );
  $variation              = get_theme_mod('shoestrap_text_variation');
  $header_bg_color        = get_theme_mod('shoestrap_header_backgroundcolor');
  $header_sitename_color  = get_theme_mod('shoestrap_header_textcolor');
  $btn_class              = get_theme_mod('shoestrap_buttons_color');
  $link_color             = get_theme_mod('shoestrap_link_color');

  // $background is the saved custom image, or the default image.
  $background = get_background_image();

  // $color is the saved custom color.
  // A default has to be specified in style.css. It will not be printed here.
  $color = get_theme_mod( 'background_color' );

  if ( ! $background && ! $color )
    return;

  $style = $color ? "background-color: #$color;" : '';

  if ( $background ) {
    $image = " background-image: url('$background');";

    $repeat = get_theme_mod( 'background_repeat', 'repeat' );
    if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
      $repeat = 'repeat';
    $repeat = " background-repeat: $repeat;";

    $position = get_theme_mod( 'background_position_x', 'left' );
    if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
      $position = 'left';
    $position = " background-position: top $position;";

    $attachment = get_theme_mod( 'background_attachment', 'scroll' );
    if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
      $attachment = 'scroll';
    $attachment = " background-attachment: $attachment;";

    $style .= $image . $repeat . $position . $attachment;
  }

  ?>
    <style>
      .login #nav a, .login #backtoblog a, a, a.active, a:hover, a.hover, a.visited, a:visited, a.link, a:link{color: #<?php echo $link_color; ?> !important; color: <?php echo $link_color; ?> !important;}
      body.login{<?php echo trim( $style ); ?> overflow-x: hidden;}
        .login #nav, .login #backtoblog{text-shadow: none; text-shadow: 0; color: #fff;}
        body.login div#login h1 a {
            background-image: url("<?php shoestrap_login_logo(); ?>");
            background-size: contain;
            padding-bottom: 30px;
        }
    #login {
      padding: 20px;
      -webkit-border-radius: 0px 0px 4px 4px;
      border-radius: 0px 0px 4px 4px;
    }
    .login form{
      margin-left: 0;
    }
    #login h1{
      margin-left: -9999px;
      padding: 20px 9999px;
      background: <?php echo $header_bg_color; ?>;
      margin-top: -20px;
      margin-bottom: 50px;
    }
  </style>
<?php }
add_action( 'login_enqueue_scripts', 'shoestrap_login_scripts' );

function shoestrap_login_url( $url ) {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'shoestrap_login_url' );