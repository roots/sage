

jQuery.noConflict();

    /** Fire up jQuery - let's dance! 
     */
    jQuery(document).ready(function($){


		Object.size = function(obj) {
		  var size = 0, key;
		  for (key in obj) {
		      if (obj.hasOwnProperty(key)) size++;
		  }
		  return size;
		};

		/**
		  * Google Fonts
		  * Dependencies 	 : google.com, jquery
		  * Feature added by : Dovy Paukstys - http://simplerain.com/
		  * Date 			 : 06.14.2013
		  */
		function GoogleFontSelectHybrid( mainID, selector ){
			
			if ($(selector).hasClass('of-typography-new-face')) {
				$('#'+mainID+' .typography-style span').text('');
				$('#'+mainID+' .typography-script span').text('');
				$('#'+mainID+' .of-typography-style').val('');
				$('#'+mainID+' .of-typography-script').val('');
			}
			var face = $('#'+mainID+' .of-typography-new-face').val();
			var script = $('#'+mainID+' .of-typography-script').val();
			var style = $('#'+mainID+' .of-typography-style').val();
			var size = $('#'+mainID+' .of-typography-size').val();
			var height = $('#'+mainID+' .of-typography-height').val();
			var color = $('#'+mainID+' .of-typography-color').val();

			
			var option = $('#'+mainID+' .of-typography-new-face option:selected');
			var google = option.data('google');

			var details = jQuery.parseJSON(decodeURIComponent(option.data('details')));

			if (google) {
				var html = '<option value="">Select style</option>';

			    for (i = 0; i<=Object.size(details.variants); i++){
			      if (details.variants[i] == null)
			        continue;

			      if (details.variants[i].id == style || Object.size(details.variants) == 1) {
			        var selected = ' selected="selected"';
					$('#'+mainID+' .typography-style span').text(details.variants[i].name.replace('+',' '));
			      } else {
			        selected = "";
			      }
			      html += '<option value="'+details.variants[i].id+'"'+selected+'>'+details.variants[i].name.replace('+',' ')+'</option>';
			    }
				$('#'+mainID+' .of-typography-style').html(html);
				

				html = '<option value="">Select script</option>';

			    for (i = 0; i<=Object.size(details.subsets); i++){
			      if (details.subsets[i] == null)
			        continue;
			      if (details.subsets[i].id == script || Object.size(details.subsets) == 1) {
			        var selected = ' selected="selected"';
			        $('#'+mainID+' .typography-script span').text(details.subsets[i].name.replace('+',' '));
			      } else {
			        selected = "";
			      }
			      html += '<option value="'+details.subsets[i].id+'"'+selected+'>'+details.subsets[i].name.replace('+',' ')+'</option>';
			    }

			   
				$('#'+mainID+' .of-typography-script').html(html);
			
			} else {
				if ($(selector).hasClass('of-typography-new-face')) {
					$('#'+mainID+' .of-typography-script').html('');
					$('#'+mainID+' .of-typography-style').html('');
					$('#'+mainID+' .typography-style span').text('');
					$('#'+mainID+' .typography-script span').text('');
					var html = '<option value="">Select style</option>';
					$.each(details, function(index, value) {
						if (index=="normal") {
							var selected = ' selected="selected"';
							$('#'+mainID+' .typography-style span').text(value);
						}
					  html += '<option value="'+index+'"'+selected+'>'+value+'</option>';
					});
				    $('#'+mainID+' .of-typography-style').html(html);								
				}
			}

			var _linkclass = 'style_link_'+ mainID;
			var _previewer = mainID.replace('section-','') +'_ggf_previewer';
			
			if( face ){ //if var exists and isset
				//Check if selected is not equal with "Select a font" and execute the script.
				if ( face !== 'none' && face !== 'Select a font' ) {
					
					//remove other elements crested in <head>
					$( '.'+ _linkclass ).remove();

					
					//replace spaces with "+" sign
					var the_font = face.replace(/\s+/g, '+');
					if ($('#'+mainID+' .of-typography-new-face option:selected').data('google')) {
						//add reference to google font family
						$('head').append('<link href="http://fonts.googleapis.com/css?family='+ the_font +':'+style.replace('-','')+'&subset='+script+'" rel="stylesheet" type="text/css" class="'+ _linkclass +'">');
						$('#'+mainID+' .typography-google').val('true');
					} else {
						$('#'+mainID+' .typography-google').val('false');
					}
					
					$('.'+ _previewer ).css('font-size', size);
					$('.'+ _previewer ).css('font-style', "normal");
					if (style.indexOf("-") !== -1) {
						var n = style.split("-");
						$('.'+ _previewer ).css('font-weight', n[0] );
						$('.'+ _previewer ).css('font-style', n[1] );
					} else {
						if (!google) {
							$('.'+ _previewer ).css('font-weight', style );	
						}
						$('.'+ _previewer ).css('font-style', style );
					}
					
					//show in the preview box the font
					$('.'+ _previewer ).css('font-family', face +', sans-serif' );
					
				}else{
					//if selected is not a font remove style "font-family" at preview box
					$('.'+ _previewer ).css('font-family', '' );
				}

				//$('.'+ _previewer ).css('line-height', height );
				$('.'+ _previewer ).css('color', color );
			}
		
		}
		
		//init for each element
		jQuery( '.section-select_google_font_hybrid' ).each(function(){ 
			var mainID = jQuery(this).attr('id');
			GoogleFontSelectHybrid( mainID, $(this) );
		});
		
		//init when value is changed
		jQuery( '.google_font_hybrid_value' ).change(function(){ 			
			var mainID = jQuery(this).closest('.section-select_google_font_hybrid').attr('id');
			GoogleFontSelectHybrid( mainID, $(this) );
		});

		// Because wordpress's color picker callback function doesn't run on pallet color clicks. Odd.
		$('.iris-palette').live('click', function() {
			var mainID = jQuery(this).closest('.section-select_google_font_hybrid').attr('id');
			GoogleFontSelectHybrid( mainID, $(this) );
		});
		// Have to redeclare the wpColorPicker to get a callback function 
		$('.section-select_google_font_hybrid .of-color').wpColorPicker({
		    change: function(event, ui){
		    	var mainID = jQuery(this).closest('.section-select_google_font_hybrid').attr('id');
				GoogleFontSelectHybrid( mainID, $(this) );
		    },
		});


	
		

    });