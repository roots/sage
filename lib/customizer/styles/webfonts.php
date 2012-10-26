<?php

function shoestrap_typography_css() {
  $webfont        = get_theme_mod('shoestrap_google_webfonts'); 
  $assign_webfont = get_theme_mod('shoestrap_webfonts_assign');
  ?>
  <style>
    <?php if ( $assign_webfont == 'sitename' ) { ?>
      .brand {
    <?php } elseif ( $assign_webfont == 'headers' ) { ?>
      .brand, h1, h2, h3, h4, h5 {
    <?php } else { ?>
      body, input, button, select, textarea, .search-query {
    <?php } ?>
        font-family: '<?php echo $webfont; ?>';
      }
  </style>
<?php }
add_action( 'wp_head', 'shoestrap_typography_css', 200 );

function shoestrap_typography_webfont() {
  $webfont = get_theme_mod( 'shoestrap_google_webfonts' );
  $f       = strlen( $webfont );
  if ($f > 3){
    $webfontname = str_replace( ' ', '+', $webfont );
    echo "<link href='http://fonts.googleapis.com/css?family=" . $webfontname . "' rel='stylesheet' type='text/css'>";
  }
}
add_action( 'wp_head', 'shoestrap_typography_webfont', 201 );
