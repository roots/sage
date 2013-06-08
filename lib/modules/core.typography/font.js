

jQuery.noConflict();

    /** Fire up jQuery - let's dance! 
     */
    jQuery(document).ready(function($){


		/**
		  * Google Fonts
		  * Dependencies 	 : google.com, jquery
		  * Feature added by : Smartik - http://smartik.ws/
		  * Date 			 : 03.17.2013
		  */
		function GoogleFontSelectHybrid( slctr, mainID ){
			
			var _selected = $(slctr).val(); 						//get current value - selected and saved
			var _linkclass = 'style_link_'+ mainID;
			var _previewer = mainID +'_ggf_previewer';
			
			if( _selected ){ //if var exists and isset
				
				//Check if selected is not equal with "Select a font" and execute the script.
				if ( _selected !== 'none' && _selected !== 'Select a font' ) {
					
					//remove other elements crested in <head>
					$( '.'+ _linkclass ).remove();
					
					//replace spaces with "+" sign
					var the_font = _selected.replace(/\s+/g, '+');
					
					//add reference to google font family
					$('head').append('<link href="http://fonts.googleapis.com/css?family='+ the_font +'" rel="stylesheet" type="text/css" class="'+ _linkclass +'">');
					
					//show in the preview box the font
					$('.'+ _previewer ).css('font-family', _selected +', sans-serif' );
					
				}else{
					
					//if selected is not a font remove style "font-family" at preview box
					$('.'+ _previewer ).css('font-family', '' );
					
				}
			
			}
		
		}
		
		//init for each element
		jQuery( '.google_font_select_hybrid' ).each(function(){ 
			var mainID = jQuery(this).attr('id');
			GoogleFontSelectHybrid( this, mainID );
		});
		
		//init when value is changed
		jQuery( '.google_font_select_hybrid' ).change(function(){ 
			var mainID = jQuery(this).attr('id');
			GoogleFontSelectHybrid( this, mainID );
		});



    });