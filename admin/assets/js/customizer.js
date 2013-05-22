jQuery.noConflict();

/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($){



/* Not sure if I like the wordpress pointer. Here's how to do it though...
	$('.pointer').live('click', function() {
		var parent = $(this).closest('li').attr('id');

		var header = $.trim($('#'+parent+' .customize-control-title').text());
		var body = $(this).attr('title');
		var content = "<h3>"+header+"<\/h3><p>"+body+"<\/p>";

		$('#'+parent + ' .customize-control-title').pointer({
	        content: content,
	        position: 'left',
	        close: function() {
	            // This function is fired when you click the close button
	        }
      	}).pointer('open');

	})
*/

	// Display last current tab
	if ($.cookie("of_current_opt") === null) {
	} else {
		//console.log($.cookie("of_current_opt").replace("#of-option-","#customize-section-"));
		$($.cookie("of_current_opt").replace("#of-option-","#customize-section-")).addClass("open");
	}

	//Current Menu Class
	$('.control-section').click(function(){
		if ($(this).hasClass('open')) {
			//console.log($.cookie("of_current_opt").replace("#of-option-","#customize-section-"));
			$.cookie('of_current_opt', $(this).attr('id').replace("customize-section-", "#of-option-"), { expires: 7, path: '/' });
		} else {
			$.cookie('of_current_opt', "", { expires: 0, path: '/' });
		}
	});


	/**	Tipsy @since v1.3 */
	if (jQuery().tipsy) {
		$('a.tooltip').tipsy({
			fade: true,
			gravity: 'w',
			opacity: 0.9,
			live: true
		});
	}

	//(un)fold options in a checkbox-group
  	jQuery('.fld').click(function() {
    	var $fold='.f_'+this.id;
    	$($fold).slideToggle('normal', "swing");
  	});

	//delays until AjaxUpload is finished loading
	//fixes bug in Safari and Mac Chrome
	if (typeof AjaxUpload != 'function') {
			return ++counter < 6 && window.setTimeout(init, counter * 500);
	}


	//Masked Inputs (images as radio buttons)
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});
	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	//Masked Inputs (background images as radio buttons)
	$('.of-radio-tile-img').click(function(){
		$(this).parent().parent().find('.of-radio-tile-img').removeClass('of-radio-tile-selected');
		$(this).addClass('of-radio-tile-selected');
	});
	$('.of-radio-tile-label').hide();
	$('.of-radio-tile-img').show();
	$('.of-radio-tile-radio').hide();

	// Style Select
	(function ($) {
	styleSelect = {
		init: function () {
		$('.select_wrapper').each(function () {
			$(this).prepend('<span>' + $(this).find('.select option:selected').text() + '</span>');
		});
		$('.select').live('change', function () {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		$('.select').bind($.browser.msie ? 'click' : 'change', function(event) {
			$(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
		});
		}
	};
	$(document).ready(function () {
		styleSelect.init()
	})
	})(jQuery);

	/**
	  * JQuery UI Slider function
	  * Dependencies 	 : jquery, jquery-ui-slider
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	  */





	jQuery('.smof_sliderui').each(function() {

		var obj   = jQuery(this);
		var sId   = "#" + obj.data('id');
		var val   = parseInt(obj.data('val'));
		var min   = parseInt(obj.data('min'));
		var max   = parseInt(obj.data('max'));
		var step  = parseInt(obj.data('step'));

		//slider init
		obj.slider({
			value: val,
			min: min,
			max: max,
			step: step,
			slide: function( event, ui ) {
				jQuery(sId).val( ui.value );
				jQuery(sId).change();
			}
		});

	});


	/**
	  * Switch
	  * Dependencies 	 : jquery
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	  */
	jQuery(".cb-enable").click(function(){
		if (jQuery(this).hasClass('selected')) {
			return false;
		}
		var parent = $(this).parents('.switch-options');
		jQuery('.cb-disable',parent).removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery('.main_checkbox',parent).attr('checked', false);

		//fold/unfold related options
		var obj = jQuery(this);
		var $fold='.f_'+obj.data('id');
		jQuery($fold).slideDown('normal', "swing");
	});
	jQuery(".cb-disable").click(function(){
		if (jQuery(this).hasClass('selected')) {
			return false;
		}
		var parent = $(this).parents('.switch-options');
		jQuery('.cb-enable',parent).removeClass('selected');
		jQuery(this).addClass('selected');
		jQuery('.main_checkbox',parent).attr('checked', true);

		//fold/unfold related options
		var obj = jQuery(this);
		var $fold='.f_'+obj.data('id');
		jQuery($fold).slideUp('normal', "swing");
	});
	//disable text select(for modern chrome, safari and firefox is done via CSS)
	if (($.browser.msie && $.browser.version < 10) || $.browser.opera) {
		$('.cb-enable span, .cb-disable span').find().attr('unselectable', 'on');
	}


	/**
	  * Google Fonts
	  * Dependencies 	 : google.com, jquery
	  * Feature added by : Smartik - http://smartik.ws/
	  * Date 			 : 03.17.2013
	  */
	function GoogleFontSelect( slctr, mainID ){

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
	jQuery( '.google_font_select' ).each(function(){
		var mainID = jQuery(this).attr('id');
		GoogleFontSelect( this, mainID );
	});

	//init when value is changed
	jQuery( '.google_font_select' ).change(function(){
		var mainID = jQuery(this).attr('id');
		GoogleFontSelect( this, mainID );
	});

});