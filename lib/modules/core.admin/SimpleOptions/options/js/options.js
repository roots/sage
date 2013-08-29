jQuery('.sof-action_bar, .sof-presets-bar').click(function() {
	window.onbeforeunload = null;
});

function verify_fold(variable) {
	jQuery(document).ready(function($){		
		// Hide errors if the user changed the field
		
		if (variable.hasClass('fold')) {
			var varVisible = jQuery('#'+variable.attr('id')).closest('td').is(":visible");
			var data = variable.data();
			var fold = variable.attr('data-fold').split(',');
			var value = variable.val();
			jQuery.each(fold,function(n){
				var theData = variable.data(fold[n]);
				var hide = false;
				if( theData == value ) {
    			hide = true;
				}
				if (theData instanceof String) {
					if (theData.indexOf(",") != -1) {
						theData = theData.split(",");
					} else {
						theData = theData.split();
					}
				}
				if (!hide && jQuery.inArray(value, theData) != -1) {
					hide = true;
				} 
				var foldChild = jQuery('#'+fold[n]);

				if ( !hide && varVisible ) {
					jQuery('#foldChild-'+fold[n]).parent().parent().fadeIn('medium', function() {
						if (foldChild.hasClass('fold')) {
							verify_fold(foldChild);
						}
					});					
				} else {
					jQuery('#foldChild-'+fold[n]).parent().parent().fadeOut('medium', function() {						
						if (foldChild.hasClass('fold')) {
							verify_fold(foldChild);
						}
					});					
				}
			});
		}
	});
}

function sof_change(variable) {
//console.log('value changed!');
	if (variable.hasClass('compiler')) {
		jQuery('#sof-compiler-hook').val(1);
		//console.log('Compiler init');
	}
	
	window.onbeforeunload = confirmOnPageExit;
	jQuery(document).ready(function($){		
		verify_fold(variable); // Verify if the variable is visible
		if (jQuery(this).hasClass('simple-options-field-error')) {
			jQuery(this).removeClass('simple-options-field-error');
			jQuery(this).parent().find('.simple-options-th-error').slideUp();
			var parentID = jQuery(this).closest('.simple-options-group-tab').attr('id');
			var hideError = true;
			jQuery('#'+parentID+' .simple-options-field-error').each(function() {
				hideError = false;
			});
			if (hideError) {
				jQuery('#'+parentID+'_li .simple-options-menu-error').hide();
			}			
		}
		jQuery('#simple-options-save-warn').slideDown();	
	});	
}


var confirmOnPageExit = function (e) {
    // If we haven't been passed the event get the window.event
    e = e || window.event;

    var message = sof_opts.save_pending;

    // For IE6-8 and Firefox prior to version 4
    if (e) 
    {
        e.returnValue = message;
    }

    // For Chrome, Safari, IE8+ and Opera 12+
    return message;
};


jQuery(document).ready(function($){

/**	Tipsy @since v1.3 */
if (jQuery().tipsy) {
	$('.tips').tipsy({
		fade: true,
		gravity: 's',
		opacity: 0.7,
	});
}	

var confirmOnPageExit = function (e) {
    // If we haven't been passed the event get the window.event
    e = e || window.event;

    var message = sof_opts.save_pending;

    // For IE6-8 and Firefox prior to version 4
    if (e) 
    {
        e.returnValue = message;
    }

    // For Chrome, Safari, IE8+ and Opera 12+
    return message;
};
	
	/**
		Unfolding elements. Used by switch, checkbox, select
	**/
	//(un)fold options in a checkbox-group
	jQuery('.fld').click(function() {
  	var $fold='.f_'+this.id;
  	$($fold).slideToggle('normal', "swing");
	});
	// (un)fold options where the id equals the value
	jQuery('.fld-parent').change(function() {
  	var $fold='.f_'+this.id+"-"+this.val();
  	$($fold).slideToggle('normal', "swing");
	});

	/**
		Current tab checks, based on cookies
	**/
	jQuery('.simple-options-group-tab-link-a').click(function(){
		relid = jQuery(this).data('rel'); // The group ID of interest

		$('#'+relid).children('.fold').each(function() {
			verify_fold(jQuery(this));
		});


		// Set the proper page cookie
		$.cookie('sof_current_tab', relid, { expires: 7, path: '/' });	
		// Remove the old active tab
		oldid = jQuery('.simple-options-group-tab-link-li.active .simple-options-group-tab-link-a').data('rel');

		jQuery('#'+oldid+'_section_group_li').removeClass('active');

		// Show the group
		jQuery('#'+oldid+'_section_group').hide();
		jQuery('#'+relid+'_section_group').fadeIn(300, function() {
			stickyInfo();// race condition fix
		});

		jQuery('#'+relid+'_section_group_li').addClass('active');
	});

	// Get the URL parameter for tab
	function getURLParameter(name) {
	    return decodeURI(
	        (RegExp(name + '=' + '(.+?)(&|$)').exec(location.search)||[,''])[1]
	    );
	}
	
	// If the $_GET param of tab is set, use that for the tab that should be open
	var tab = getURLParameter('tab');
	if (tab != "") {
		if ($.cookie("sof_current_tab_get") != tab) {
			$.cookie('sof_current_tab', tab, { expires: 7, path: '/' });	
			$.cookie('sof_current_tab_get', tab, { expires: 7, path: '/' });
			jQuery('#'+tab+'_section_group_li').click();
		}
	} else if ($.cookie('sof_current_tab_get') != "") {
		$.removeCookie('sof_current_tab_get');
	}

	var sTab = jQuery('#'+$.cookie("sof_current_tab")+'_section_group_li_a');
	// Tab the first item or the saved one
	if($.cookie("sof_current_tab") === null || typeof($.cookie("sof_current_tab")) == "undefined" || sTab.length == 0){
		jQuery('.simple-options-group-tab-link-a:first').click();
	}else{
		sTab.delay(300).click();
	}


	
	// Default button clicked
	jQuery('input[name="'+sof_opts.opt_name+'[defaults]"]').click(function(){
		if(!confirm(sof_opts.reset_confirm)){
			return false;
		}
		window.onbeforeunload = null;

	});
	

	
	

	jQuery('#expand_options').click(function(e) {
		e.preventDefault();
		
		var trigger = jQuery('#expand_options');
		var width = jQuery('#simple-options-sidebar').width();
		var id = jQuery('#simple-options-group-menu .active a').data('rel')+'_section_group';
		
		if (trigger.hasClass('expanded')) {
			trigger.removeClass('expanded');
			jQuery('#simple-options-main').removeClass('expand');
			jQuery('#simple-options-sidebar').stop().animate({'margin-left':'0px'},500);
			jQuery('#simple-options-main').stop().animate({'margin-left':width},500);

			

			jQuery('.simple-options-group-tab').each(function(){
					if(jQuery(this).attr('id') != id){
						jQuery(this).fadeOut('fast');
					}
			});
			// Show the only active one

		} else {
			trigger.addClass('expanded');
			jQuery('#simple-options-main').addClass('expand');
			jQuery('#simple-options-sidebar').stop().animate({'margin-left':-width-2},500);
			jQuery('#simple-options-main').stop().animate({'margin-left':'0px'},500);	
			jQuery('.simple-options-group-tab').fadeIn();

		}
		return false;
	});	
	
	jQuery('#simple-options-import').click(function(e) {
		if (jQuery('#import-code-value').val() == "" && jQuery('#import-link-value').val() == "" ) {
			e.preventDefault();
			return false;
		}
	});

	
	if(jQuery('#simple-options-save').is(':visible')){
		jQuery('#simple-options-save').slideDown();
	}
	
	if(jQuery('#simple-options-imported').is(':visible')){
		jQuery('#simple-options-imported').slideDown();
	}	
	
	jQuery('input, textarea, select').live('change',function() {
		if (!jQuery(this).hasClass('noUpdate')) {
			sof_change(jQuery(this));	
		}
	});
	

	
	jQuery('#simple-options-import-code-button').click(function(){
		if(jQuery('#simple-options-import-link-wrapper').is(':visible')){
			jQuery('#simple-options-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#simple-options-import-code-wrapper').fadeIn('slow');
	});
	
	jQuery('#simple-options-import-link-button').click(function(){
		if(jQuery('#simple-options-import-code-wrapper').is(':visible')){
			jQuery('#simple-options-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#simple-options-import-link-wrapper').fadeIn('slow');
	});
	
	jQuery('#simple-options-export-code-copy').click(function(){
		if(jQuery('#simple-options-export-link-value').is(':visible')){jQuery('#simple-options-export-link-value').fadeOut('slow');}
		jQuery('#simple-options-export-code').toggle('fade');
	});
	
	jQuery('#simple-options-export-link').click(function(){
		if(jQuery('#simple-options-export-code').is(':visible')){jQuery('#simple-options-export-code').fadeOut('slow');}
		jQuery('#simple-options-export-link-value').toggle('fade');
	});
	
	
jQuery.fn.isOnScreen = function(){
    
    var win = jQuery(window);
    
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();
    
    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();
    
    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    
};


/**
	Show the sticky header bar and notes!
**/
  var stickyHeight = jQuery('#simple-options-footer').height();
  var stickyWidth = jQuery('#simple-options-footer').width();
  jQuery('#simple-options-sticky-padder').css({height: stickyHeight});

  function stickyInfo() {
    if( !jQuery('#info_bar').isOnScreen() && !jQuery('#simple-options-footer-sticky').isOnScreen()) {
        jQuery('#simple-options-footer').css({position: 'fixed', bottom: '0', width: stickyWidth});
        jQuery('#simple-options-footer').addClass('sticky-footer-fixed');
        jQuery('#simple-options-sticky-padder').show();
    } else {
    		jQuery('#simple-options-footer').css({background: '#eee',position: 'inherit', bottom: 'inherit', width: 'inherit' });
    		jQuery('#simple-options-sticky-padder').hide();
    		jQuery('#simple-options-footer').removeClass('sticky-footer-fixed');
    }  	
  }  
  jQuery(window).scroll(function(){
		stickyInfo();
  });
  jQuery(window).resize(function(){
		stickyInfo();
  });

	
  jQuery('#simple-options-save, #simple-options-imported').delay(4000).slideUp();
  jQuery('#simple-options-field-errors').delay(5000).slideUp();


  jQuery('.of-save').click(function() {
  	window.onbeforeunload = null;
  });

	jQuery('.fold-data').each(function() {
		var id = jQuery(this).attr('id').replace("foldChild-","");
		var foldata = jQuery(this).attr('id');
		var data = jQuery(this).val(); // Items that make this element fold
		var split = "";

		if (data.indexOf(",") != -1) {
			split = data.split(',');
		} else {
			split = data.split();
		}		

		jQuery.each(split,function(n){
			var fid = jQuery('#'+split[n]); // ID of the unit that causes a fold
			fid.addClass('fold'); // Add the fold class
			var ndata = jQuery('#'+foldata).attr('data-'+split[n]); // The values of fid that cause the fold
			if (fid.attr('data-'+id)) { // If this fold object already has values that cause a fold
				ndata = fid.attr('data-'+id)+","+ndata;
			}		
			fid.attr('data-'+id, ndata);

			// This is where we say, these are the elements that cause you to hide!
			var fold = "";
			var fdata = jQuery('#'+split[n]);

			var currentData = jQuery('#'+split[n]).attr('data-fold');
			if (typeof(fdata) !== 'undefined' && typeof(currentData) !== 'undefined') {
				fold += jQuery('#'+split[n]).attr('data-fold'); // All what's already there	
			}
			if (fold != "") {
				fold += ",";
			}
			fold += id;

			jQuery('#'+split[n]).attr('data-fold', fold);		
			verify_fold(jQuery('#'+split[n]));
			
    });
	});  
	


	// Markdown Viewer for Theme Documentation
	if ($('#theme_docs_section_group').length != 0) {
		var converter = new Showdown.converter();
		var text = jQuery('#theme_docs_section_group').html();
		text = converter.makeHtml(text);
		jQuery('#theme_docs_section_group').html(text);
	}

});