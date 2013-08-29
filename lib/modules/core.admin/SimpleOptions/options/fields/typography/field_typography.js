

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
		function typographySelect( mainID, selector ){
			if ($(selector).hasClass('sof-typography-family')) {
				$('#'+mainID+' .typography-style span').text('');
				$('#'+mainID+' .typography-script span').text('');
				$('#'+mainID+' .sof-typography-style').val('');
				$('#'+mainID+' .sof-typography-script').val('');
			}
			var family = $('#'+mainID+' select.sof-typography-family ').val();
			var size = $('#'+mainID+' .sof-typography-size').val();
			var height = $('#'+mainID+' .sof-typography-height').val();
			var style = $('#'+mainID+' select.sof-typography-style').val();
			var script = $('#'+mainID+' select.sof-typography-script').val();
			var color = $('#'+mainID+' .sof-typography-color').val();
			var units = $('#'+mainID).data('units');

			var option = $('#'+mainID+' .sof-typography-family option:selected');
			var google = option.data('google');
			var details = jQuery.parseJSON(decodeURIComponent(option.data('details')));

			if (google && $(selector).hasClass('sof-typography-family')) {
				var html = '<option value="">Select style</option>';

			    for (i = 0; i<=Object.size(details.variants); i++){
			      if (details.variants[i] == null) {
			      	continue;
			      }

			      if (details.variants[i].id == style || Object.size(details.variants) == 1) {
			        var selected = ' selected="selected"';
					$('#'+mainID+' .typography-style span').text(details.variants[i].name.replace('+',' '));
			      } else {
			        selected = "";
			      }

			      html += '<option value="'+details.variants[i].id+'"'+selected+'>'+details.variants[i].name.replace(/\+/g, " ")+'</option>';
			    }

				$('#'+mainID+' .sof-typography-style').html(html);

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

			      html += '<option value="'+details.subsets[i].id+'"'+selected+'>'+details.subsets[i].name.replace(/\+/g, " ")+'</option>';
			    }

				$('#'+mainID+' .sof-typography-script').html(html);

			} else {
				if ($(selector).hasClass('sof-typography-family')) {
					$('#'+mainID+' .sof-typography-script').html('');
					$('#'+mainID+' .sof-typography-style').html('');
					$('#'+mainID+' .typography-style span').text('');
					$('#'+mainID+' .typography-script span').text('');
					var html = '<option value="">Select style</option>';
					$.each(details, function(index, value) {
						if (index=="normal") {
							var selected = ' selected="selected"';
							$('#'+mainID+' .typography-style span').text(value);
						}
					  html += '<option value="'+index+'"'+selected+'>'+value.replace('+',' ')+'</option>';
					});
				    $('#'+mainID+' .sof-typography-style').html(html);
				}
			}
			var script = $('#'+mainID+' .sof-typography-script').val();
			var style = $('#'+mainID+' .sof-typography-style').val();


			var _linkclass = 'style_link_'+ mainID;

			if( family ){ //if var exists and isset
				//Check if selected is not equal with "Select a font" and execute the script.
				if ( family !== 'none' && family !== 'Select a font' ) {

					//remove other elements crested in <head>
					$( '.'+ _linkclass ).remove();

					//replace spaces with "+" sign
					var the_font = family.replace(/\s+/g, '+');
					if ($('#'+mainID+' .sof-typography-family option:selected').data('google')) {

						//add reference to google font family
						var link = 'http://fonts.googleapis.com/css?family='+ the_font;
						if (style)
							link += ':'+style.replace(/\-/g, " ");
						if (script)
							link += '&subset='+script;

						$('head').append('<link href="'+link+'" rel="stylesheet" type="text/css" class="'+ _linkclass +'">');
						$('#'+mainID+' .sof-typography-google').val(true);
					} else {
						$('#'+mainID+' .sof-typography-google').val(false);
					}

					var previewer = $('#'+mainID+' .typography-preview');


					previewer.css('font-size', size+units);
					previewer.css('font-style', "normal");
					if (style.indexOf("-") !== -1) {
						var n = style.split("-");
						previewer.css('font-weight', n[0] );
						previewer.css('font-style', n[1] );
					} else {
						if (!google) {
							previewer.css('font-weight', style );
						}
						previewer.css('font-style', style );
					}

					//show in the preview box the font
					previewer.css('font-family', family +', sans-serif' );

				}else{
					//if selected is not a font remove style "font-family" at preview box
					previewer.css('font-family', '' );
				}

				if (height) {
					previewer.css('line-height', height+units );
				} else {
					previewer.css('line-height', size+units );
				}

				previewer.css('color', color );
			}
			$('#'+mainID+' .typography-style span').text($('#'+mainID+' .sof-typography-style option:selected').text());
			$('#'+mainID+' .typography-script span').text($('#'+mainID+' .sof-typography-script option:selected').text());

		}

		//init for each element
		jQuery( '.sof-typography-container' ).each(function(){
			typographySelect( jQuery(this).attr('id'), $(this) );
		});

		//init when value is changed
		jQuery( '.sof-typography' ).change(function(){
			typographySelect( jQuery(this).closest('.sof-typography-container').attr('id'), $(this) );
		});

		//init when value is changed
		jQuery( '.sof-typography-size, .sof-typography-height' ).keyup(function(){
			typographySelect( jQuery(this).closest('.sof-typography-container').attr('id'), $(this) );
		});		

		// Have to redeclare the wpColorPicker to get a callback function
		$('.sof-typography-color').wpColorPicker({
		    change: function(event, ui){
		    	sof_change(jQuery(this))
		    	jQuery(this).val(ui.color.toString());
		  		typographySelect( jQuery(this).closest('.sof-typography-container').attr('id'), $(this) );
		    },
		});

		/**	Tipsy @since v1.3 */
		if (jQuery().tipsy) {
			$('.sof-typography-size, .sof-typography-height, .sof-typography-family, .sof-typography-style, .sof-typography-script').tipsy({
				fade: true,
				gravity: 's',
				opacity: 0.7,
			});
		}

		jQuery(".sof-typography-size, .sof-typography-height").numeric({negative:false});	

		jQuery(".sof-typography-family").select2({width: 'resolve', triggerChange: true});

	});