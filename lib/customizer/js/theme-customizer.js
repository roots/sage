( function( $ ){
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( 'a.brand, a.brand-logo .sitename' ).html( to );
		} );
	} );

  wp.customize( 'shoestrap_hero_title', function( value ) {
    value.bind( function( to ) {
      $( 'h1.hero-title' ).html( to );
    } );
  } );

  wp.customize( 'shoestrap_hero_content', function( value ) {
    value.bind( function( to ) {
      $( 'p.hero-content' ).html( to );
    } );
  } );

  wp.customize( 'shoestrap_hero_cta_text', function( value ) {
    value.bind( function( to ) {
      $( '.hero-button a' ).html( to );
    } );
  } );
  
  wp.customize( 'background_color', function( setval )  {
    setval.bind( function( opt ) {
      opt = (opt) ? '#'+opt : 'ffffff';
      $('#wrap').css('background', opt );
  } );

} )( jQuery );