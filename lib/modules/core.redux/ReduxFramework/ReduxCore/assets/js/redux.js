/*global jQuery, document, redux.args, confirm, relid:true, console, jsonView */
(function($){
	'use strict';
	$.redux = $.redux || {};



	$(document).ready(function(){

		//console.log(redux);
		
		jQuery.fn.isOnScreen = function() {
			if (!window) {
				return;
			}
			var win = jQuery(window);
			var viewport = {
				top: win.scrollTop(),
				left: win.scrollLeft()
			};
			viewport.right = viewport.left + win.width();
			viewport.bottom = viewport.top + win.height();
			var bounds = this.offset();
			bounds.right = bounds.left + this.outerWidth();
			bounds.bottom = bounds.top + this.outerHeight();
			return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
		};


		$.redux.required();

		$("body").on('change', '.redux-main select, .redux-main radio, .redux-main input[type=checkbox], .redux-main input[type=hidden]', function(e){
			$.redux.check_dependencies(this);
		});

		$("body").on('check_dependencies', function(e, variable){		
			$.redux.check_dependencies(variable);
        });

		//console.log(redux.fieldsHidden);
		// Hide the hidden fields on load
		for (var i = 0; i < redux.fieldsHidden.length; i++) {
			$.redux.check_dependencies(jQuery('#' + redux.fieldsHidden[i]));
		}
		
	});


	
	$.redux.required = function(){

		// Hide the fold elements on load ,
		// It's better to do this by PHP but there is no filter in tr tag , so is not possible
		// we going to move each attributes we may need for folding to tr tag
		$('.hiddenFold, .showFold').each(function() {
			var current		= $(this), 
            scope			= current.parents('tr:eq(0)'),
            check_data		= current.data();

            if(current.hasClass('hiddenFold')){
				scope.addClass('hiddenFold').attr('data-check-field' , check_data.checkField)
					.attr('data-check-comparison' , check_data.checkComparison)
					.attr('data-check-value' , check_data.checkValue)
					.attr('data-check-id' , check_data.id).hide();
				//we clean here, so we won't get confuse
				current.removeClass('hiddenFold').removeAttr('data-check-field')
					.removeAttr('data-check-comparison')
					.removeAttr('data-check-value');	
			}else{
				scope.attr('data-check-field' , check_data.checkField)
					.attr('data-check-comparison' , check_data.checkComparison)
					.attr('data-check-value' , check_data.checkValue)
					.attr('data-check-id' , check_data.id);
				//we clean here, so we won't get confuse
				current.removeClass('showFold').removeAttr('data-check-field')
					.removeAttr('data-check-comparison')
					.removeAttr('data-check-value');	
			}
		});

		$( ".fold" ).promise().done(function() {
			// Hide the fold elements on load
			$('.foldParent').each(function() {
				// in case of a radio input, take in consideration only the checked value
				if ( $(this).attr('type') =='radio' && $(this).attr('checked') !='checked' ) {
					return;
				}
				var id = $(this).parents('.redux-field:first').data('id');
				if ( redux.folds[ id ] ) {
					if ( !redux.folds[ id ].parent  ) {
						$.redux.verify_fold($(this));
					}
				}
			});
		});

		
	};

	$.redux.check_dependencies = function(variable){
		
		var current = $(variable),
			scope	= current.parents('.redux-group-tab:eq(0)');
 
        if(!scope.length) scope = $('body');

		// Fix for Checkbox + Required issue
        if( $(variable).prop('type') == "checkbox") {
			$(variable).is(":checked") ? $(variable).val('1') : $(variable).val('0');
        }
		
        var id		= current.parents('.redux-field:first').data('id'),
        dependent	= scope.find('tr[data-check-field="'+id+'"]'), 
        value1		= variable.value,
        is_hidden	= current.parents('tr:eq(0)').is('.hiddenFold');
		
        if(!dependent.length) return;

        dependent.each(function(){
            var current		= $(this), 
            check_data	= current.data(), 
            value2		= check_data.checkValue, 
            show		= false,
            value2_array;
			
            if(!is_hidden){
                switch(check_data.checkComparison){
					case '=':
					case 'equals':
						//if value was array
						if (value2.toString().indexOf('|') !== -1){
							value2_array = value2.split('|');
							if($.inArray( value1, value2_array ) != -1){
								show = true;
							}
						} else {
							if(value1 == value2) {
								show = true;
							}
						}
						break;
					case '!=':    
					case 'not':
						//if value was array
						if (value2.toString().indexOf('|') !== -1){
							value2_array = value2.split('|');
							if($.inArray( value1, value2_array ) == -1){
								show = true;
							}
						} else {
							if(value1 != value2) {
								show = true;
							}
						}
                        break;
					case '>':    
					case 'greater':    
					case 'is_larger':
						if(parseFloat(value1) >  parseFloat(value2)) 
							show = true;
						break;
					case '>=':    
						if(parseFloat(value1) >=  parseFloat(value2)) 
							show = true;
						break;						
					case '<':
					case 'less':    
					case 'is_smaller':
						if(parseFloat(value1) < parseFloat(value2)) 
							show = true;
						break;
					case '<=':
						if(parseFloat(value1) <= parseFloat(value2)) 
							show = true;
						break;						
					case 'contains':
						if(value1.toString().indexOf(value2) != -1) 
							show = true;
						break;
					case 'doesnt_contain':
					case 'not_contain':
						if(value1.toString().indexOf(value2) == -1) 
							show = true;
						break;
					case 'is_empty_or':
						if(value1 === "" || value1 == value2) 
							show = true;
						break;
					case 'not_empty_and':
						if(value1 !== "" && value1 != value2) 
							show = true;
						break;
                }
            }
				
            if(show === true && current.is('.hiddenFold')){
                current.css({
                    display:'none'
                }).removeClass('hiddenFold').find('select, radio, input[type=checkbox]').trigger('change');
                current.fadeIn(300);
            }else if(show === false  && !current.is('.hiddenFold')){
                current.css({
                    display:''
                }).addClass('hiddenFold').find('select, radio, input[type=checkbox]').trigger('change');
                current.fadeOut(300);
            }
			//$.redux.verify_fold($(variable)); 
        });
	};

	$.redux.verify_fold = function(item){
		var id = item.parents('.redux-field:first').data('id');
		var itemVal = item.val();
		var scope = (item.parents('.redux-groups-accordion-group:first').length > 0)?item.parents('.redux-groups-accordion-group:first'):item.parents('.redux-group-tab:eq(0)');

		if ( redux.folds[ id ] ) {

			if ( redux.folds[ id ].children ) {

				var theChildren = {};
				$.each(redux.folds[ id ].children, function(index, value) {
					$.each(value, function(index2, value2) { // Each of the children for this value
						if ( ! theChildren[value2] ) { // Create an object if it's not there
							theChildren[value2] = { show:false, hidden:false };
						}
						
						if ( index == itemVal || theChildren[value2] === true ) { // Check to see if it's in the criteria
							theChildren[value2].show = true;
						}

						if ( theChildren[value2].show === true && scope.find('tr[data-check-id="'+id+'"]').hasClass("hiddenFold") ) {
							theChildren[value2].show = false; // If this item is hidden, hide this child
						}

						if ( theChildren[value2].show === true && scope.find('tr[data-check-id="'+redux.folds[ id ].parent+'"]').hasClass('hiddenFold') ) {
							theChildren[value2].show = false; // If the parent of the item is hidden, hide this child
						}
						// Current visibility of this child node
						theChildren[value2].hidden = scope.find('tr[data-check-id="'+value2+'"]').hasClass("hiddenFold");
					});
				});

				$.each(theChildren, function(index) {

					var parent = scope.find('tr[data-check-id="'+index+'"]');
					
					if ( theChildren[index].show === true ) {

						parent.fadeIn('medium', function() {
							parent.removeClass('hiddenFold');
							if ( redux.folds[ index ] && redux.folds[ index ].children ) {
								// Now iterate the children
								$.redux.verify_fold(parent.find('select, radio, input[type=checkbox], input[type=hidden]'));
							}
						});

					} else if ( theChildren[index].hidden === false ) {
						
						parent.fadeOut('medium', function() {
							parent.addClass('hiddenFold');
							if ( redux.folds[ index ].children ) {
								// Now iterate the children
								$.redux.verify_fold(parent.find('select, radio, input[type=checkbox], input[type=hidden]'));
							}
						});
					}
				});
			}
		}	
	};

})(jQuery);

jQuery.noConflict();
var confirmOnPageExit = function(e) {
		//return; // ONLY FOR DEBUGGING
		// If we haven't been passed the event get the window.event
		e = e || window.event;
		var message = redux.args.save_pending;
		// For IE6-8 and Firefox prior to version 4
		if (e) {
			e.returnValue = message;
		}
		window.onbeforeunload = null;
		// For Chrome, Safari, IE8+ and Opera 12+
		return message;
	};
	
function getContrastColour(hexcolour){
	// default value is black.
	retVal = '#444444';
	
	// In case - for some reason - a blank value is passed.
	// This should *not* happen.  If a function passing a value
	// is canceled, it should pass the current value instead of
	// a blank.  This is how the Windows Common Controls do it.  :P
	if (hexcolour !== '') {
		
		// Replace the hash with a blank.
		hexcolour = hexcolour.replace('#','');

		var r = parseInt(hexcolour.substr(0, 2), 16);
		var g = parseInt(hexcolour.substr(2, 2), 16);
		var b = parseInt(hexcolour.substr(4, 2), 16);
		var res = ((r * 299) + (g * 587) + (b * 114)) / 1000;
	
		// Instead of pure black, I opted to use WP 3.8 black, so it looks uniform.  :) - kp
		retVal = (res >= 128) ? '#444444' : '#ffffff';
	}
	
	return retVal;
}	

function verify_fold(item) {
	
	jQuery(document).ready(function($) {
		console.log(verify_fold);
		


		if (item.hasClass('redux-info') || item.hasClass('redux-typography')) {
			return;
		}

		var id = item.parents('.redux-field:first').data('id');
		//console.log(id);
		var itemVal = item.val();

		if ( redux.folds[ id ] ) {

/*
		if ( redux.folds[ id ].parent && jQuery( '#' + redux.folds[ id ].parent ).is('hidden') ) {
			console.log('Going to parent: '+redux.folds[ id ].parent+' for field: '+id);
			//verify_fold( jQuery( '#' + redux.folds[ id ].parent ) );
		} 
*/
			if ( redux.folds[ id ].children ) {
				//console.log('Children for: '+id);

				var theChildren = {};
				$.each(redux.folds[ id ].children, function(index, value) {
					$.each(value, function(index2, value2) { // Each of the children for this value
						if ( ! theChildren[value2] ) { // Create an object if it's not there
							theChildren[value2] = { show:false, hidden:false };
						}
						//console.log('id: '+id+' childID: '+value2+' parent value: '+index+' itemVal: '+itemVal);
						if ( index == itemVal || theChildren[value2] === true ) { // Check to see if it's in the criteria
							theChildren[value2].show = true;
							//console.log('theChildren['+value2+'].show = true');
						}

						if ( theChildren[value2].show === true && jQuery('#' + id).parents("tr:first").hasClass("hiddenFold") ) {
							theChildren[value2].show = false; // If this item is hidden, hide this child
							//console.log('set '+value2+' false');
						}

						if ( theChildren[value2].show === true && jQuery( '#' + redux.folds[ id ].parent ).hasClass('hiddenFold') ) {
							theChildren[value2].show = false; // If the parent of the item is hidden, hide this child
							//console.log('set '+value2+' false2');
						}
						// Current visibility of this child node
						theChildren[value2].hidden = jQuery('#' + value2).parents("tr:first").hasClass("hiddenFold");
					});
				});

				//console.log(theChildren);

				$.each(theChildren, function(index) {

					var parent = jQuery('#' + index).parents("tr:first");
					
					if ( theChildren[index].show === true ) {
						//console.log('FadeIn '+index);
						
						parent.fadeIn('medium', function() {
							parent.removeClass('hiddenFold');
							if ( redux.folds[ index ] && redux.folds[ index ].children ) {
								//verify_fold(jQuery('#'+index)); // Now iterate the children
							}
						});

					} else if ( theChildren[index].hidden === false ) {
						//console.log('FadeOut '+index);
						
						parent.fadeOut('medium', function() {
							parent.addClass('hiddenFold');
							if ( redux.folds[ index ].children ) {
								//verify_fold(jQuery('#'+index)); // Now iterate the children
							}
						});
					}
				});
			}
		}
			
	});
}

function redux_change(variable) {
	//We need this for switch and image select fields , jquery dosn't catch it on fly
	//if(variable.is('input[type=hidden]') || variable.hasClass('spinner-input') || variable.hasClass('slider-input') || variable.hasClass('upload') || jQuery(variable).parents('fieldset:eq(0)').is('.redux-container-image_select') ) {
		
		jQuery('body').trigger('check_dependencies' , variable);
	//}
		
	if (variable.hasClass('compiler')) {
		jQuery('#redux-compiler-hook').val(1);
		//console.log('Compiler init');
	}


	if (variable.hasClass('foldParent')) {
		//verify_fold(variable);
	}
	window.onbeforeunload = confirmOnPageExit;
	if (jQuery(variable).parents('fieldset.redux-field:first').hasClass('redux-field-error')) {
		jQuery(variable).parents('fieldset.redux-field:first').removeClass('redux-field-error');
		jQuery(variable).parent().find('.redux-th-error').slideUp();
		var parentID = jQuery(variable).closest('.redux-group-tab').attr('id');
		var hideError = true;
		jQuery('#' + parentID + ' .redux-field-error').each(function() {
			hideError = false;
		});
		if (hideError) {
			jQuery('#' + parentID + '_li .redux-menu-error').hide();
			jQuery('#' + parentID + '_li .redux-group-tab-link-a').removeClass('hasError');
		}
	}
	jQuery('#redux-save-warn').slideDown();
}
jQuery(document).ready(function($) {
	jQuery('.redux-action_bar, .redux-presets-bar').on('click', function() {
		window.onbeforeunload = null;
	}); /**	Tipsy @since v1.3 DEPRICATE? */
	if (jQuery().tipsy) {
		$('.tips').tipsy({
			fade: true,
			gravity: 's',
			opacity: 0.7
		});
	}

	$('#toplevel_page_'+redux.args.slug+' .wp-submenu a').click(function(e) {
		//if ( $(this).hasClass('wp-menu-open') ) {
			e.preventDefault();
			var url = $(this).attr('href').split('&tab=');
			$('#'+url[1]+'_section_group_li_a').click();
			console.log(url[1]);
			return false;	
		//}
	});

/**
		Current tab checks, based on cookies
	**/
	jQuery('.redux-group-tab-link-a').click(function() {
		relid = jQuery(this).data('rel'); // The group ID of interest
		jQuery('#currentSection').val(relid);
		// Set the proper page cookie
		$.cookie('redux_current_tab', relid, {
			expires: 7,
			path: '/'
		});

		$('#toplevel_page_'+redux.args.slug+' .wp-submenu a.current').removeClass('current');
		$('#toplevel_page_'+redux.args.slug+' .wp-submenu li.current').removeClass('current');

		$('#toplevel_page_'+redux.args.slug+' .wp-submenu a').each(function() {
			var url = $(this).attr('href').split('&tab=');
			if (url[1] == relid) {
				$(this).addClass('current');
				$(this).parent().addClass('current');
			}
		});

		// Remove the old active tab
		var oldid = jQuery('.redux-group-tab-link-li.active .redux-group-tab-link-a').data('rel');
		jQuery('#' + oldid + '_section_group_li').removeClass('active');
		// Show the group
		jQuery('#' + oldid + '_section_group').hide();
		jQuery('#' + relid + '_section_group').fadeIn(200, function() {
			stickyInfo(); // race condition fix
		});
		jQuery('#' + relid + '_section_group_li').addClass('active');
	});
	// Get the URL parameter for tab

	function getURLParameter(name) {
		return decodeURI((new RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, ''])[1]);
	}
	// If the $_GET param of tab is set, use that for the tab that should be open
	var tab = getURLParameter('tab');
	if (tab !== "") {
		if ($.cookie("redux_current_tab_get") !== tab) {
			$.cookie('redux_current_tab', tab, {
				expires: 7,
				path: '/'
			});
			$.cookie('redux_current_tab_get', tab, {
				expires: 7,
				path: '/'
			});
			jQuery('#' + tab + '_section_group_li').click();
		}
	} else if ($.cookie('redux_current_tab_get') !== "") {
		$.removeCookie('redux_current_tab_get');
	}
	var sTab = jQuery('#' + $.cookie("redux_current_tab") + '_section_group_li_a');
	// Tab the first item or the saved one
	if ($.cookie("redux_current_tab") === null || typeof($.cookie("redux_current_tab")) === "undefined" || sTab.length === 0) {
		jQuery('.redux-group-tab-link-a:first').click();
	} else {
		sTab.click();
	}
	// Default button clicked
	jQuery('input[name="' + redux.args.opt_name + '[defaults]"]').click(function() {
		if (!confirm(redux.args.reset_confirm)) {
			return false;
		}
		window.onbeforeunload = null;
	});
	// Default button clicked
	jQuery('input[name="' + redux.args.opt_name + '[defaults-section]"]').click(function() {
		if (!confirm(redux.args.reset_section_confirm)) {
			return false;
		}
		window.onbeforeunload = null;
	});	
	jQuery('#expand_options').click(function(e) {
		e.preventDefault();
		var trigger = jQuery('#expand_options');
		var width = jQuery('#redux-sidebar').width();
		var id = jQuery('#redux-group-menu .active a').data('rel') + '_section_group';
		if (trigger.hasClass('expanded')) {
			trigger.removeClass('expanded');
			jQuery('.redux-main').removeClass('expand');
			jQuery('#redux-sidebar').stop().animate({
				'margin-left': '0px'
			}, 500);
			jQuery('.redux-main').stop().animate({
				'margin-left': width
			}, 500);
			jQuery('.redux-group-tab').each(function() {
				if (jQuery(this).attr('id') !== id) {
					jQuery(this).fadeOut('fast');
				}
			});
			// Show the only active one
		} else {
			trigger.addClass('expanded');
			jQuery('.redux-main').addClass('expand');
			jQuery('#redux-sidebar').stop().animate({
				'margin-left': -width - 2
			}, 500);
			jQuery('.redux-main').stop().animate({
				'margin-left': '0px'
			}, 500);
			jQuery('.redux-group-tab').fadeIn();
		}
		return false;
	});
	jQuery('#redux-import').click(function(e) {
		if (jQuery('#import-code-value').val() === "" && jQuery('#import-link-value').val() === "") {
			e.preventDefault();
			return false;
		}
	});
	if (jQuery('#redux-save').is(':visible')) {
		jQuery('#redux-save').slideDown();
	}
	if (jQuery('#redux-imported').is(':visible')) {
		jQuery('#redux-imported').slideDown();
	}
	jQuery(document.body).on('change', 'input, textarea, select', function() {
		if (!jQuery(this).hasClass('noUpdate')) {
			redux_change(jQuery(this));
		}
	});
	jQuery('#redux-import-code-button').click(function() {
		if (jQuery('#redux-import-link-wrapper').is(':visible')) {
			jQuery('#redux-import-link-wrapper').fadeOut('fast');
			jQuery('#import-link-value').val('');
		}
		jQuery('#redux-import-code-wrapper').fadeIn('slow');
	});
	jQuery('#redux-import-link-button').click(function() {
		if (jQuery('#redux-import-code-wrapper').is(':visible')) {
			jQuery('#redux-import-code-wrapper').fadeOut('fast');
			jQuery('#import-code-value').val('');
		}
		jQuery('#redux-import-link-wrapper').fadeIn('slow');
	});
	jQuery('#redux-export-code-copy').click(function() {
		if (jQuery('#redux-export-link-value').is(':visible')) {
			jQuery('#redux-export-link-value').fadeOut('slow');
		}
		jQuery('#redux-export-code').toggle('fade');
	});
	jQuery('#redux-export-link').click(function() {
		if (jQuery('#redux-export-code').is(':visible')) {
			jQuery('#redux-export-code').fadeOut('slow');
		}
		jQuery('#redux-export-link-value').toggle('fade');
	});

	/**
		BEGIN Sticky footer bar
	**/
	var stickyHeight = jQuery('#redux-footer').height();
	jQuery('#redux-sticky-padder').css({
		height: stickyHeight
	});

	function stickyInfo() {
		var stickyWidth = jQuery('#info_bar').width() - 2;
		if (!jQuery('#info_bar').isOnScreen() && !jQuery('#redux-footer-sticky').isOnScreen()) {
			jQuery('#redux-footer').css({
				position: 'fixed',
				bottom: '0',
				width: stickyWidth
			});
			jQuery('#redux-footer').addClass('sticky-footer-fixed');
			jQuery('#redux-sticky-padder').show();
		} else {
			jQuery('#redux-footer').css({
				background: '#eee',
				position: 'inherit',
				bottom: 'inherit',
				width: 'inherit'
			});
			jQuery('#redux-sticky-padder').hide();
			jQuery('#redux-footer').removeClass('sticky-footer-fixed');
		}
	}
	
	if (jQuery('#redux-footer').length !== 0) {
		jQuery(window).scroll(function() {
			stickyInfo();
		});
		jQuery(window).resize(function() {
			stickyInfo();
		});
	}

	jQuery('#redux-save, #redux-imported').delay(4000).slideUp();
	jQuery('#redux-field-errors').delay(8000).slideUp();
	jQuery('.redux-save').click(function() {
		window.onbeforeunload = null;
	});
	/**
		END Sticky footer bar
	**/

	/**
		BEGIN dev_mode commands
	**/	
	$('#consolePrintObject').on('click', function() {
		console.log(jQuery.parseJSON(jQuery("#redux-object-json").html()));
	});

	if (typeof jsonView === 'function') {
		jsonView('#redux-object-json', '#redux-object-browser');
	}
	/**
		END dev_mode commands
	**/	

	/**
		BEGIN error and warning notices
	**/	
	// Display errors on page load
	if (redux.errors !== undefined) {
		jQuery("#redux-field-errors span").html(redux.errors.total);
		jQuery("#redux-field-errors").show();
		jQuery.each(redux.errors.errors, function(sectionID, sectionArray) {
			jQuery("#" + sectionID + "_section_group_li_a").prepend('<span class="redux-menu-error">' + sectionArray.total + '</span>');
			jQuery("#" + sectionID + "_section_group_li_a").addClass("hasError");
			jQuery.each(sectionArray.errors, function(key, value) {
				console.log(value);
				jQuery("#" + redux.args.opt_name+'-'+value.id).addClass("redux-field-error");
				jQuery("#" + redux.args.opt_name+'-'+value.id).append('<div class="redux-th-error">' + value.msg + '</div>');
			});
		});
	}
	// Display warnings on page load
	if (redux.warnings !== undefined) {
		jQuery("#redux-field-warnings span").html(redux.warnings.total);
		jQuery("#redux-field-warnings").show();
		jQuery.each(redux.warnings.warnings, function(sectionID, sectionArray) {
			jQuery("#" + sectionID + "_section_group_li_a").prepend('<span class="redux-menu-warning">' + sectionArray.total + '</span>');
			jQuery("#" + sectionID + "_section_group_li_a").addClass("hasWarning");
			jQuery.each(sectionArray.warnings, function(key, value) {
				jQuery("#" + redux.args.opt_name+'-'+value.id).addClass("redux-field-warning");
				jQuery("#" + redux.args.opt_name+'-'+value.id).append('<div class="redux-th-warning">' + value.msg + '</div>');
			});
		});
	}
	/**
		END error and warning notices
	**/	



	/**
		BEGIN Control the tabs of the site to the left. Eventually (perhaps) across the top too.
	**/
	//jQuery( ".redux-section-tabs" ).tabs();
	jQuery('.redux-section-tabs div').hide();
	jQuery('.redux-section-tabs div:first').show();
	jQuery('.redux-section-tabs ul li:first').addClass('active');
 
	jQuery('.redux-section-tabs ul li a').click(function(){
		jQuery('.redux-section-tabs ul li').removeClass('active');
		jQuery(this).parent().addClass('active');
		var currentTab = $(this).attr('href');
		jQuery('.redux-section-tabs div').hide();
		jQuery(currentTab).fadeIn();
		return false;
	});
	/**
		END Control the tabs of the site to the left. Eventually (perhaps) across the top too.
	**/


});
