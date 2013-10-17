

/* **********************************************
     Begin acf.js
********************************************** */

/*
*  input.js
*
*  All javascript needed for ACF to work
*
*  @type	awesome
*  @date	1/08/13
*
*  @param	N/A
*  @return	N/A
*/ 

var acf = {
	
	// vars
	ajaxurl				:	'',
	admin_url			:	'',
	wp_version			:	'',
	post_id				:	0,
	nonce				:	'',
	l10n				:	null,
	o					:	null,
	
	// helper functions
	helpers				:	{
		get_atts		: 	null,
		version_compare	:	null,
		uniqid			:	null,
		sortable		:	null,
		add_message		:	null,
		is_clone_field	:	null,
		url_to_object	:	null
	},
	
	
	// modules
	validation			:	null,
	conditional_logic	:	null,
	media				:	null,
	
	
	// fields
	fields				:	{
		date_picker		:	null,
		color_picker	:	null,
		Image			:	null,
		file			:	null,
		wysiwyg			:	null,
		gallery			:	null,
		relationship	:	null
	}
};

(function($){
	
	
	/*
	*  acf.helpers.isset
	*
	*  http://phpjs.org/functions/isset
	*
	*  @type	function
	*  @date	20/07/13
	*
	*  @param	{mixed}		arguments
	*  @return	{boolean}	
	*/
	
	acf.helpers.isset = function(){
		
		var a = arguments,
	        l = a.length,
	        i = 0,
	        undef;
	
	    if (l === 0) {
	        throw new Error('Empty isset');
	    }
	
	    while (i !== l) {
	        if (a[i] === undef || a[i] === null) {
	            return false;
	        }
	        i++;
	    }
	    return true;
			
	};
	
	
	/*
	*  acf.helpers.get_atts
	*
	*  description
	*
	*  @type	function
	*  @date	1/06/13
	*
	*  @param	{el}		$el
	*  @return	{object}	atts
	*/
	
	acf.helpers.get_atts = function( $el ){
		
		var atts = {};
		
		$.each( $el[0].attributes, function( index, attr ) {
        	
        	if( attr.name.substr(0, 5) == 'data-' )
        	{
	        	atts[ attr.name.replace('data-', '') ] = attr.value;
        	}
        });
        
        return atts;
			
	};
        
           
	/**
	 * Simply compares two string version values.
	 * 
	 * Example:
	 * versionCompare('1.1', '1.2') => -1
	 * versionCompare('1.1', '1.1') =>  0
	 * versionCompare('1.2', '1.1') =>  1
	 * versionCompare('2.23.3', '2.22.3') => 1
	 * 
	 * Returns:
	 * -1 = left is LOWER than right
	 *  0 = they are equal
	 *  1 = left is GREATER = right is LOWER
	 *  And FALSE if one of input versions are not valid
	 *
	 * @function
	 * @param {String} left  Version #1
	 * @param {String} right Version #2
	 * @return {Integer|Boolean}
	 * @author Alexey Bass (albass)
	 * @since 2011-07-14
	 */
	 
	acf.helpers.version_compare = function(left, right)
	{
	    if (typeof left + typeof right != 'stringstring')
	        return false;
	    
	    var a = left.split('.')
	    ,   b = right.split('.')
	    ,   i = 0, len = Math.max(a.length, b.length);
	        
	    for (; i < len; i++) {
	        if ((a[i] && !b[i] && parseInt(a[i]) > 0) || (parseInt(a[i]) > parseInt(b[i]))) {
	            return 1;
	        } else if ((b[i] && !a[i] && parseInt(b[i]) > 0) || (parseInt(a[i]) < parseInt(b[i]))) {
	            return -1;
	        }
	    }
	    
	    return 0;
	};
	
	
	/*
	*  Helper: uniqid
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	acf.helpers.uniqid = function()
    {
    	var newDate = new Date;
    	return newDate.getTime();
    };
    
    
    /*
	*  Helper: url_to_object
	*
	*  @description: 
	*  @since: 4.0.0
	*  @created: 17/01/13
	*/
	
    acf.helpers.url_to_object = function( url ){
	    
	    // vars
	    var obj = {},
	    	pairs = url.split('&');
	    
	    
		for( i in pairs )
		{
		    var split = pairs[i].split('=');
		    obj[decodeURIComponent(split[0])] = decodeURIComponent(split[1]);
		}
		
		return obj;
	    
    };
    
	
	/*
	*  Sortable Helper
	*
	*  @description: keeps widths of td's inside a tr
	*  @since 3.5.1
	*  @created: 10/11/12
	*/
	
	acf.helpers.sortable = function(e, ui)
	{
		ui.children().each(function(){
			$(this).width($(this).width());
		});
		return ui;
	};
	
	
	/*
	*  is_clone_field
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	acf.helpers.is_clone_field = function( input )
	{
		if( input.attr('name') && input.attr('name').indexOf('[acfcloneindex]') != -1 )
		{
			return true;
		}
		
		return false;
	};
	
	
	/*
	*  acf.helpers.add_message
	*
	*  @description: 
	*  @since: 3.2.7
	*  @created: 10/07/2012
	*/
	
	acf.helpers.add_message = function( message, div ){
		
		var message = $('<div class="acf-message-wrapper"><div class="message updated"><p>' + message + '</p></div></div>');
		
		div.prepend( message );
		
		setTimeout(function(){
			
			message.animate({
				opacity : 0
			}, 250, function(){
				message.remove();
			});
			
		}, 1500);
			
	};
	
	
	/*
	*  Exists
	*
	*  @description: returns true / false		
	*  @created: 1/03/2011
	*/
	
	$.fn.exists = function()
	{
		return $(this).length>0;
	};
	
	
	/*
	*  3.5 Media
	*
	*  @description: 
	*  @since: 3.5.7
	*  @created: 16/01/13
	*/
	
	acf.media = {
	
		div : null,
		frame : null,
		render_timout : null,
		
		clear_frame : function(){
			
			// validate
			if( !this.frame )
			{
				return;
			}
			
			
			// detach
			this.frame.detach();
			this.frame.dispose();
			
			
			// reset var
			this.frame = null;
			
		},
		type : function(){
			
			// default
			var type = 'thickbox';
			
			
			// if wp exists
			if( typeof(wp) == "object" )
			{
				type = 'backbone';
			}
			
			
			// return
			return type;
			
		},
		init : function(){
			
			// vars
			var _prototype = wp.media.view.AttachmentCompat.prototype;
			
			
			// orig
			_prototype.orig_render = _prototype.render;
			_prototype.orig_dispose = _prototype.dispose;
			
			
			// update class
			_prototype.className = 'compat-item acf_postbox no_box';
			
			
			// modify render
			_prototype.render = function() {
				
				// reference
				var _this = this;
				
				
				// validate
				if( _this.ignore_render )
				{
					return this;	
				}
				
				
				// run the old render function
				this.orig_render();
				
				
				// add button
				setTimeout(function(){
					
					// vars
					var $media_model = _this.$el.closest('.media-modal');
					
					
					// is this an edit only modal?
					if( $media_model.hasClass('acf-media-modal') )
					{
						return;	
					}
					
					
					// does button already exist?
					if( $media_model.find('.media-frame-router .acf-expand-details').exists() )
					{
						return;	
					}
					
					
					// create button
					var button = $([
						'<a href="#" class="acf-expand-details">',
							'<span class="icon"></span>',
							'<span class="is-closed">' + acf.l10n.core.expand_details +  '</span>',
							'<span class="is-open">' + acf.l10n.core.collapse_details +  '</span>',
						'</a>'
					].join('')); 
					
					
					// add events
					button.on('click', function( e ){
						
						e.preventDefault();
						
						if( $media_model.hasClass('acf-expanded') )
						{
							$media_model.removeClass('acf-expanded');
						}
						else
						{
							$media_model.addClass('acf-expanded');
						}
						
					});
					
					
					// append
					$media_model.find('.media-frame-router').append( button );
						
				
				}, 0);
				
				
				// setup fields
				// The clearTimout is needed to prevent many setup functions from running at the same time
				clearTimeout( acf.media.render_timout );
				acf.media.render_timout = setTimeout(function(){

					$(document).trigger( 'acf/setup_fields', _this.$el );
					
				}, 50);

				
				// return based on the origional render function
				return this;
			};
			
			
			// modify dispose
			_prototype.dispose = function() {
				
				// remove
				$(document).trigger('acf/remove_fields', [ this.$el ]);
				
				
				// run the old render function
				this.orig_dispose();
				
			};
			
			
			// override save
			_prototype.save = function( event ) {
			
				var data = {},
					names = {};
				
				if ( event )
					event.preventDefault();
					
					
				_.each( this.$el.serializeArray(), function( pair ) {
				
					// initiate name
					if( pair.name.slice(-2) === '[]' )
					{
						// remove []
						pair.name = pair.name.replace('[]', '');
						
						
						// initiate counter
						if( typeof names[ pair.name ] === 'undefined'){
							
							names[ pair.name ] = -1;
							//console.log( names[ pair.name ] );
							
						}
						
						
						names[ pair.name ]++
						
						pair.name += '[' + names[ pair.name ] +']';
						
						
					}
 
					data[ pair.name ] = pair.value;
				});
 
				this.ignore_render = true;
				this.model.saveCompat( data );
				
			};
		}
	};
	
	
	/*
	*  Conditional Logic Calculate
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	acf.conditional_logic = {
		
		items : [],
		
		init : function(){
			
			// reference
			var _this = this;
			
			
			// events
			$(document).on('change', '.field input, .field textarea, .field select', function(){
				
				_this.change();
				
			});
			
			
			_this.change();
			
		},
		change : function(){
			
			// reference
			var _this = this;
			
			
			// vars
			//var $el		=	$( e.target ),
			//	$field	=	$el.closest('.field');
			
			
			// loop through items
			$.each(this.items, function( k, item ){
				
				// vars
				var show	=	true,
					$field	=	$('.field_key-' + item.field);
				
				
				// if 'any' was selected, start of as false and any match will result in show = true
				if( item.allorany == 'any' )
				{
					show = false;
				}
				
				
				// loop through rules
				$.each(item.rules, function( k2, rule ){
					
					var calculate = _this.calculate( rule );
					
					if( item.allorany == 'all' )
					{
						if( calculate == false )
						{
							show = false;
							
							// end loop
							return false;
						}
					}
					else
					{
						if( calculate == true )
						{
							show = true;
							
							// end loop
							return false;
						}
					}
					
				});
				
				
				// hide / show field
				if( show )
				{
					// remove "disabled"
					$field.find('input, textarea, select').removeAttr('disabled');
					
					
					$field.removeClass('acf-conditional_logic-hide').addClass('acf-conditional_logic-show');
				}
				else
				{
					// add "disabled"
					$field.find('input, textarea, select').attr('disabled', 'disabled');
					
					
					$field.removeClass('acf-conditional_logic-show').addClass('acf-conditional_logic-hide');
				}
				
				
			});
			
		},
		calculate : function( rule ){
			
			// vars
			var $field	=	$('.field_key-' + rule.field),
				r		=	false;
			
			
			// compare values
			if( $field.hasClass('field_type-true_false') || $field.hasClass('field_type-checkbox') || $field.hasClass('field_type-radio') )
			{
				var exists = $field.find('input[value="' + rule.value + '"]:checked').exists();
				
				
				if( rule.operator == "==" )
				{
					if( exists )
					{
						r = true;
					}
				}
				else
				{
					if( ! exists )
					{
						r = true;
					}
				}
				
			}
			else
			{
				// get val and make sure it is an array
				var val = $field.find('input, textarea, select').last().val();
				
				if( ! $.isArray(val) )
				{
					val = [ val ];
				}
				
				
				if( rule.operator == "==" )
				{
					if( $.inArray(rule.value, val) > -1 )
					{
						r = true;
					}
				}
				else
				{
					if( $.inArray(rule.value, val) < 0 )
					{
						r = true;
					}
				}
				
			}
			
			
			// return
			return r;
			
		}
		
	};
	
	
	
	
		
	/*
	*  Document Ready
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$(document).ready(function(){
		
		
		// conditional logic
		acf.conditional_logic.init();
		
		
		// fix for older options page add-on
		$('.acf_postbox > .inside > .options').each(function(){
			
			$(this).closest('.acf_postbox').addClass( $(this).attr('data-layout') );
			
		});
		
	
	});
	
	
	/*
	*  window load
	*
	*  @description: 
	*  @since: 3.5.5
	*  @created: 22/12/12
	*/
	
	$(window).load(function(){
		
		// init
		acf.media.init();
		
		
		setTimeout(function(){
			
			// Hack for CPT without a content editor
			try
			{
				wp.media.view.settings.post.id = acf.post_id;	
			} 
			catch(e)
			{
				// one of the objects was 'undefined'...
			}
			
			
			// setup fields
			$(document).trigger('acf/setup_fields', [ $('#poststuff') ]);
			
		}, 10);
		
	});
	
	
	/*
	*  Gallery field Add-on Fix
	*
	*  Gallery field v1.0.0 required some data in the acf object.
	*  Now not required, but older versions of gallery field need this.
	*
	*  @type	object
	*  @date	1/08/13
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	acf.fields.gallery = {
		add : function(){},
		edit : function(){},
		update_count : function(){},
		hide_selected_items : function(){},
		text : {
			title_add : "Select Images"
		}
	};
	
	
})(jQuery);

/* **********************************************
     Begin ajax.js
********************************************** */

(function($){
	
	
	/*
	*  acf.screen
	*
	*  Data used by AJAX to hide / show field groups
	*
	*  @type	object
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	acf.screen = {
		action 			:	'acf/location/match_field_groups_ajax',
		post_id			:	0,
		page_template	:	0,
		page_parent		:	0,
		page_type		:	0,
		post_category	:	0,
		post_format		:	0,
		taxonomy		:	0,
		lang			:	0,
		nonce			:	0
	};
	
	
	/*
	*  Document Ready
	*
	*  Updates acf.screen with more data
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).ready(function(){
		
		
		// update post_id
		acf.screen.post_id = acf.o.post_id;
		acf.screen.nonce = acf.o.nonce;
		
		
		// MPML
		if( $('#icl-als-first').length > 0 )
		{
			var href = $('#icl-als-first').children('a').attr('href'),
				regex = new RegExp( "lang=([^&#]*)" ),
				results = regex.exec( href );
			
			// lang
			acf.screen.lang = results[1];
			
		}
		
	});
	
	
	/*
	*  acf/update_field_groups
	*
	*  finds the new id's for metaboxes and show's hides metaboxes
	*
	*  @type	event
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('acf/update_field_groups', function(){
		
		
		// Only for a post.
		// This is an attempt to stop the action running on the options page add-on.
		if( ! acf.o.post_id )
		{
			return false;	
		}
		
		
		$.ajax({
			url: ajaxurl,
			data: acf.screen,
			type: 'post',
			dataType: 'json',
			success: function(result){
				
				// validate
				if( !result )
				{
					return false;
				}
				
				
				// hide all metaboxes
				$('.acf_postbox').addClass('acf-hidden');
				$('.acf_postbox-toggle').addClass('acf-hidden');
		
				
				// dont bother loading style or html for inputs
				if( result.length == 0 )
				{
					return false;
				}
				
				
				// show the new postboxes
				$.each(result, function(k, v) {
					
					
					// vars
					var $el = $('#acf_' + v),
						$toggle = $('#adv-settings .acf_postbox-toggle[for="acf_' + v + '-hide"]');
					
					
					// classes
					$el.removeClass('acf-hidden hide-if-js');
					$toggle.removeClass('acf-hidden');
					$toggle.find('input[type="checkbox"]').attr('checked', 'checked');
					
					
					// load fields if needed
					$el.find('.acf-replace-with-fields').each(function(){
						
						var $replace = $(this);
						
						$.ajax({
							url			:	ajaxurl,
							data		:	{
								action	:	'acf/input/render_fields',
								acf_id	:	v,
								post_id	:	acf.o.post_id,
								nonce	:	acf.o.nonce
							},
							type		:	'post',
							dataType	:	'html',
							success		:	function( html ){
							
								$replace.replaceWith( html );
								
								$(document).trigger('acf/setup_fields', $el);
								
							}
						});
						
					});
				});
				
				
				// load style
				$.ajax({
					url			:	ajaxurl,
					data		:	{
						action	:	'acf/input/get_style',
						acf_id	:	result[0],
						nonce	:	acf.o.nonce
					},
					type		: 'post',
					dataType	: 'html',
					success		: function( result ){
					
						$('#acf_style').html( result );
						
					}
				});
				
				
				
			}
		});
	});

	
	/*
	*  Events
	*
	*  Updates acf.screen with more data and triggers the update event
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('change', '#page_template', function(){
		
		acf.screen.page_template = $(this).val();
		
		$(document).trigger('acf/update_field_groups');
	    
	});
	
	
	$(document).on('change', '#parent_id', function(){
		
		var val = $(this).val();
		
		
		// set page_type / page_parent
		if( val != "" )
		{
			acf.screen.page_type = 'child';
			acf.screen.page_parent = val;
		}
		else
		{
			acf.screen.page_type = 'parent';
			acf.screen.page_parent = 0;
		}
		
		
		$(document).trigger('acf/update_field_groups');
	    
	});

	
	$(document).on('change', '#post-formats-select input[type="radio"]', function(){
		
		var val = $(this).val();
		
		if( val == '0' )
		{
			val = 'standard';
		}
		
		acf.screen.post_format = val;
		
		$(document).trigger('acf/update_field_groups');
		
	});	
	
	
	$(document).on('change', '.categorychecklist input[type="checkbox"]', function(){
		
		// set timeout to fix issue with chrome which does not register the change has yet happened
		setTimeout(function(){
			
			// vars
			var values = [];
			
			
			$('.categorychecklist input[type="checkbox"]:checked').each(function(){
				
				if( $(this).is(':hidden') || $(this).is(':disabled') )
				{
					return;
				}
			
				values.push( $(this).val() );
			});
	
			
			acf.screen.post_category = values;
			acf.screen.taxonomy = values;
	
	
			$(document).trigger('acf/update_field_groups');
		
		}, 1);
		
		
	});
	
	
	
})(jQuery);

/* **********************************************
     Begin color-picker.js
********************************************** */

(function($){
	
	/*
	*  Color Picker
	*
	*  jQuery functionality for this field type
	*
	*  @type	object
	*  @date	20/07/13
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	var _cp = acf.fields.color_picker = {
		
		$el : null,
		$input : null,
		
		set : function( o ){
			
			// merge in new option
			$.extend( this, o );
			
			
			// find input
			this.$input = this.$el.find('input[type="text"]');
			
			
			// return this for chaining
			return this;
			
		},
		init : function(){
			
			// is clone field?
			if( acf.helpers.is_clone_field(this.$input) )
			{
				return;
			}
			
			
			this.$input.wpColorPicker();
			
			
			
		}
	};
	
	
	/*
	*  acf/setup_fields
	*
	*  run init function on all elements for this field
	*
	*  @type	event
	*  @date	20/07/13
	*
	*  @param	{object}	e		event object
	*  @param	{object}	el		DOM object which may contain new ACF elements
	*  @return	N/A
	*/
	
	$(document).on('acf/setup_fields', function(e, el){
		
		$(el).find('.acf-color_picker').each(function(){
			
			_cp.set({ $el : $(this) }).init();
			
		});
		
	});
		

})(jQuery);

/* **********************************************
     Begin date-picker.js
********************************************** */

(function($){
	
	/*
	*  Date Picker
	*
	*  static model for this field
	*
	*  @type	event
	*  @date	1/06/13
	*
	*/
	
	acf.fields.date_picker = {
		
		$el : null,
		$input : null,
		$hidden : null,
		
		o : {},
		
		set : function( o ){
			
			// merge in new option
			$.extend( this, o );
			
			
			// find input
			this.$input = this.$el.find('input[type="text"]');
			this.$hidden = this.$el.find('input[type="hidden"]');
			
			
			// get options
			this.o = acf.helpers.get_atts( this.$el );
			
			
			// return this for chaining
			return this;
			
		},
		init : function(){

			// is clone field?
			if( acf.helpers.is_clone_field(this.$hidden) )
			{
				return;
			}
			
			
			// get and set value from alt field
			this.$input.val( this.$hidden.val() );
			
			
			// create options
			var options = $.extend( {}, acf.l10n.date_picker, { 
				dateFormat		:	this.o.save_format,
				altField		:	this.$hidden,
				altFormat		:	this.o.save_format,
				changeYear		:	true,
				yearRange		:	"-100:+100",
				changeMonth		:	true,
				showButtonPanel	:	true,
				firstDay		:	this.o.first_day
			});
			
			
			// add date picker
			this.$input.addClass('active').datepicker( options );
			
			
			// now change the format back to how it should be.
			this.$input.datepicker( "option", "dateFormat", this.o.display_format );
			
			
			// wrap the datepicker (only if it hasn't already been wrapped)
			if( $('body > #ui-datepicker-div').length > 0 )
			{
				$('#ui-datepicker-div').wrap('<div class="ui-acf" />');
			}
			
		},
		blur : function(){
			
			if( !this.$input.val() )
			{
				this.$hidden.val('');
			}
			
		}
		
	};
	
	
	/*
	*  acf/setup_fields
	*
	*  run init function on all elements for this field
	*
	*  @type	event
	*  @date	20/07/13
	*
	*  @param	{object}	e		event object
	*  @param	{object}	el		DOM object which may contain new ACF elements
	*  @return	N/A
	*/
	
	$(document).on('acf/setup_fields', function(e, el){
		
		$(el).find('.acf-date_picker').each(function(){
			
			acf.fields.date_picker.set({ $el : $(this) }).init();
			
		});
		
	});
	
	
	/*
	*  Events
	*
	*  jQuery events for this field
	*
	*  @type	event
	*  @date	1/06/13
	*
	*/
	
	$(document).on('blur', '.acf-date_picker input[type="text"]', function( e ){
		
		acf.fields.date_picker.set({ $el : $(this).parent() }).blur();
					
	});
	

})(jQuery);

/* **********************************************
     Begin file.js
********************************************** */

(function($){
	
	/*
	*  File
	*
	*  static model for this field
	*
	*  @type	event
	*  @date	1/06/13
	*
	*/
	
	
	// reference
	var _media = acf.media;
	
	
	acf.fields.file = {
		
		$el : null,
		$input : null,
		
		o : {},
		
		set : function( o ){
			
			// merge in new option
			$.extend( this, o );
			
			
			// find input
			this.$input = this.$el.find('input[type="hidden"]');
			
			
			// get options
			this.o = acf.helpers.get_atts( this.$el );
			
			
			// multiple?
			this.o.multiple = this.$el.closest('.repeater').exists() ? true : false;
			
			
			// wp library query
			this.o.query = {};
			
			
			// library
			if( this.o.library == 'uploadedTo' )
			{
				this.o.query.uploadedTo = acf.o.post_id;
			}
			
			
			// return this for chaining
			return this;
			
		},
		init : function(){

			// is clone field?
			if( acf.helpers.is_clone_field(this.$input) )
			{
				return;
			}
					
		},
		add : function( file ){
			
			// this function must reference a global div variable due to the pre WP 3.5 uploader
			// vars
			var div = _media.div;
			
			
			// set atts
			div.find('.acf-file-icon').attr( 'src', file.icon );
		 	div.find('.acf-file-title').text( file.title );
		 	div.find('.acf-file-name').text( file.name ).attr( 'href', file.url );
		 	div.find('.acf-file-size').text( file.size );
			div.find('.acf-file-value').val( file.id ).trigger('change');
		 	
		 	
		 	// set div class
		 	div.addClass('active');
		 	
		 	
		 	// validation
			div.closest('.field').removeClass('error');
	
		},
		edit : function(){
			
			// vars
			var id = this.$input.val();
			
			
			// set global var
			_media.div = this.$el;
			

			// clear the frame
			_media.clear_frame();
			
			
			// create the media frame
			_media.frame = wp.media({
				title		:	acf.l10n.file.edit,
				multiple	:	false,
				button		:	{ text : acf.l10n.file.update }
			});
			
			
			// log events
			/*
			acf.media.frame.on('all', function(e){
				
				console.log( e );
				
			});
			*/
			
			
			// open
			_media.frame.on('open',function() {
				
				// set to browse
				if( _media.frame.content._mode != 'browse' )
				{
					_media.frame.content.mode('browse');
				}
				
				
				// add class
				_media.frame.$el.closest('.media-modal').addClass('acf-media-modal acf-expanded');
					
				
				// set selection
				var selection	=	_media.frame.state().get('selection'),
					attachment	=	wp.media.attachment( id );
				
				
				// to fetch or not to fetch
				if( $.isEmptyObject(attachment.changed) )
				{
					attachment.fetch();
				}
				

				selection.add( attachment );
						
			});
			
			
			// close
			_media.frame.on('close',function(){
			
				// remove class
				_media.frame.$el.closest('.media-modal').removeClass('acf-media-modal');
				
			});
			
							
			// Finally, open the modal
			acf.media.frame.open();
			
		},
		remove : function()
		{
			
			// set atts
			this.$el.find('.acf-file-icon').attr( 'src', '' );
			this.$el.find('.acf-file-title').text( '' );
		 	this.$el.find('.acf-file-name').text( '' ).attr( 'href', '' );
		 	this.$el.find('.acf-file-size').text( '' );
			this.$el.find('.acf-file-value').val( '' ).trigger('change');
			
			
			// remove class
			this.$el.removeClass('active');
			
		},
		popup : function()
		{
			// reference
			var t = this;
			
			
			// set global var
			_media.div = this.$el;
			

			// clear the frame
			_media.clear_frame();
			
			
			 // Create the media frame
			 _media.frame = wp.media({
				states : [
					new wp.media.controller.Library({
						library		:	wp.media.query( t.o.query ),
						multiple	:	t.o.multiple,
						title		:	acf.l10n.file.select,
						priority	:	20,
						filterable	:	'all'
					})
				]
			});
			
			
			// customize model / view
			acf.media.frame.on('content:activate', function(){
				
				// vars
				var toolbar = null,
					filters = null;
					
				
				// populate above vars making sure to allow for failure
				try
				{
					toolbar = acf.media.frame.content.get().toolbar;
					filters = toolbar.get('filters');
				} 
				catch(e)
				{
					// one of the objects was 'undefined'... perhaps the frame open is Upload Files
					//console.log( e );
				}
				
				
				// validate
				if( !filters )
				{
					return false;
				}
				
				
				// no need for 'uploaded' filter
				if( t.o.library == 'uploadedTo' )
				{
					filters.$el.find('option[value="uploaded"]').remove();
					filters.$el.after('<span>' + acf.l10n.file.uploadedTo + '</span>')
					
					$.each( filters.filters, function( k, v ){
						
						v.props.uploadedTo = acf.o.post_id;
						
					});
				}
								
			});
			
			
			// When an image is selected, run a callback.
			acf.media.frame.on( 'select', function() {
				
				// get selected images
				selection = _media.frame.state().get('selection');
				
				if( selection )
				{
					var i = 0;
					
					selection.each(function(attachment){
	
				    	// counter
				    	i++;
				    	
				    	
				    	// select / add another file field?
				    	if( i > 1 )
						{
							// vars
							var $td			=	_media.div.closest('td'),
								$tr 		=	$td.closest('.row'),
								$repeater 	=	$tr.closest('.repeater'),
								key 		=	$td.attr('data-field_key'),
								selector	=	'td .acf-file-uploader:first';
								
							
							// key only exists for repeater v1.0.1 +
							if( key )
							{
								selector = 'td[data-field_key="' + key + '"] .acf-file-uploader';
							}
							
							
							// add row?
							if( ! $tr.next('.row').exists() )
							{
								$repeater.find('.add-row-end').trigger('click');
								
							}
							
							
							// update current div
							_media.div = $tr.next('.row').find( selector );
							
						}
												
						
				    	// vars
				    	var file = {
					    	id		:	attachment.id,
					    	title	:	attachment.attributes.title,
					    	name	:	attachment.attributes.filename,
					    	url		:	attachment.attributes.url,
					    	icon	:	attachment.attributes.icon,
					    	size	:	attachment.attributes.filesize
				    	};
				    	
				    	
				    	// add file to field
				        acf.fields.file.add( file );
				        
						
				    });
				    // selection.each(function(attachment){
				}
				// if( selection )
				
			});
			// acf.media.frame.on( 'select', function() {
					 
				
			// Finally, open the modal
			acf.media.frame.open();
				
			
			return false;
		}
		
	};
	
	
	/*
	*  Events
	*
	*  jQuery events for this field
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('click', '.acf-file-uploader .acf-button-edit', function( e ){
		
		e.preventDefault();
		
		acf.fields.file.set({ $el : $(this).closest('.acf-file-uploader') }).edit();
			
	});
	
	$(document).on('click', '.acf-file-uploader .acf-button-delete', function( e ){
		
		e.preventDefault();
		
		acf.fields.file.set({ $el : $(this).closest('.acf-file-uploader') }).remove();
			
	});
	
	
	$(document).on('click', '.acf-file-uploader .add-file', function( e ){
		
		e.preventDefault();
		
		acf.fields.file.set({ $el : $(this).closest('.acf-file-uploader') }).popup();
		
	});
	

})(jQuery);

/* **********************************************
     Begin image.js
********************************************** */

(function($){
	
	/*
	*  Image
	*
	*  static model for this field
	*
	*  @type	event
	*  @date	1/06/13
	*
	*/
	
	
	// reference
	var _media = acf.media;
	
	
	acf.fields.image = {
		
		$el : null,
		$input : null,
		
		o : {},
		
		set : function( o ){
			
			// merge in new option
			$.extend( this, o );
			
			
			// find input
			this.$input = this.$el.find('input[type="hidden"]');
			
			
			// get options
			this.o = acf.helpers.get_atts( this.$el );
			
			
			// multiple?
			this.o.multiple = this.$el.closest('.repeater').exists() ? true : false;
			
			
			// wp library query
			this.o.query = {
				type : 'image'
			};
			
			
			// library
			if( this.o.library == 'uploadedTo' )
			{
				this.o.query.uploadedTo = acf.o.post_id;
			}
			
			
			// return this for chaining
			return this;
			
		},
		init : function(){

			// is clone field?
			if( acf.helpers.is_clone_field(this.$input) )
			{
				return;
			}
					
		},
		add : function( image ){
			
			// this function must reference a global div variable due to the pre WP 3.5 uploader
			// vars
			var div = _media.div;
			
			
			// set atts
			div.find('.acf-image-image').attr( 'src', image.url );
			div.find('.acf-image-value').val( image.id ).trigger('change');
		 	
			
		 	// set div class
		 	div.addClass('active');
		 	
		 	
		 	// validation
			div.closest('.field').removeClass('error');
	
		},
		edit : function(){
			
			// vars
			var id = this.$input.val();
			
			
			// set global var
			_media.div = this.$el;
			

			// clear the frame
			_media.clear_frame();
			
			
			// create the media frame
			_media.frame = wp.media({
				title		:	acf.l10n.image.edit,
				multiple	:	false,
				button		:	{ text : acf.l10n.image.update }
			});
			
			
			// log events
			/*
			acf.media.frame.on('all', function(e){
				
				console.log( e );
				
			});
			*/
			
			
			// open
			_media.frame.on('open',function() {
				
				// set to browse
				if( _media.frame.content._mode != 'browse' )
				{
					_media.frame.content.mode('browse');
				}
				
				
				// add class
				_media.frame.$el.closest('.media-modal').addClass('acf-media-modal acf-expanded');
					
				
				// set selection
				var selection	=	_media.frame.state().get('selection'),
					attachment	=	wp.media.attachment( id );
				
				
				// to fetch or not to fetch
				if( $.isEmptyObject(attachment.changed) )
				{
					attachment.fetch();
				}
				

				selection.add( attachment );
						
			});
			
			
			// close
			_media.frame.on('close',function(){
			
				// remove class
				_media.frame.$el.closest('.media-modal').removeClass('acf-media-modal');
				
			});
			
							
			// Finally, open the modal
			acf.media.frame.open();
			
		},
		remove : function()
		{
			
			// set atts
		 	this.$el.find('.acf-image-image').attr( 'src', '' );
			this.$el.find('.acf-image-value').val( '' ).trigger('change');
			
			
			// remove class
			this.$el.removeClass('active');
			
		},
		popup : function()
		{
			// reference
			var t = this;
			
			
			// set global var
			_media.div = this.$el;
			

			// clear the frame
			_media.clear_frame();
			
			
			 // Create the media frame
			 _media.frame = wp.media({
				states : [
					new wp.media.controller.Library({
						library		:	wp.media.query( t.o.query ),
						multiple	:	t.o.multiple,
						title		:	acf.l10n.image.select,
						priority	:	20,
						filterable	:	'all'
					})
				]
			});
			
			
			/*acf.media.frame.on('all', function(e){
				
				console.log( e );
				
			});*/
			
			
			// customize model / view
			acf.media.frame.on('content:activate', function(){

				// vars
				var toolbar = null,
					filters = null;
					
				
				// populate above vars making sure to allow for failure
				try
				{
					toolbar = acf.media.frame.content.get().toolbar;
					filters = toolbar.get('filters');
				} 
				catch(e)
				{
					// one of the objects was 'undefined'... perhaps the frame open is Upload Files
					//console.log( e );
				}
				
				
				// validate
				if( !filters )
				{
					return false;
				}
				
				
				// filter only images
				$.each( filters.filters, function( k, v ){
				
					v.props.type = 'image';
					
				});
				
				
				// no need for 'uploaded' filter
				if( t.o.library == 'uploadedTo' )
				{
					filters.$el.find('option[value="uploaded"]').remove();
					filters.$el.after('<span>' + acf.l10n.image.uploadedTo + '</span>')
					
					$.each( filters.filters, function( k, v ){
						
						v.props.uploadedTo = acf.o.post_id;
						
					});
				}
				
				
				// remove non image options from filter list
				filters.$el.find('option').each(function(){
					
					// vars
					var v = $(this).attr('value');
					
					
					// don't remove the 'uploadedTo' if the library option is 'all'
					if( v == 'uploaded' && t.o.library == 'all' )
					{
						return;
					}
					
					if( v.indexOf('image') === -1 )
					{
						$(this).remove();
					}
					
				});
				
				
				// set default filter
				filters.$el.val('image').trigger('change');
				
			});
			
			
			// When an image is selected, run a callback.
			acf.media.frame.on( 'select', function() {
				
				// get selected images
				selection = _media.frame.state().get('selection');
				
				if( selection )
				{
					var i = 0;
					
					selection.each(function(attachment){
	
				    	// counter
				    	i++;
				    	
				    	
				    	// select / add another image field?
				    	if( i > 1 )
						{
							// vars
							var $td			=	_media.div.closest('td'),
								$tr 		=	$td.closest('.row'),
								$repeater 	=	$tr.closest('.repeater'),
								key 		=	$td.attr('data-field_key'),
								selector	=	'td .acf-image-uploader:first';
								
							
							// key only exists for repeater v1.0.1 +
							if( key )
							{
								selector = 'td[data-field_key="' + key + '"] .acf-image-uploader';
							}
							
							
							// add row?
							if( ! $tr.next('.row').exists() )
							{
								$repeater.find('.add-row-end').trigger('click');
								
							}
							
							
							// update current div
							_media.div = $tr.next('.row').find( selector );
							
						}
						
						
				    	// vars
				    	var image = {
					    	id		:	attachment.id,
					    	url		:	attachment.attributes.url
				    	};
				    	
				    	// is preview size available?
				    	if( attachment.attributes.sizes && attachment.attributes.sizes[ t.o.preview_size ] )
				    	{
					    	image.url = attachment.attributes.sizes[ t.o.preview_size ].url;
				    	}
				    	
				    	// add image to field
				        acf.fields.image.add( image );
				        
						
				    });
				    // selection.each(function(attachment){
				}
				// if( selection )
				
			});
			// acf.media.frame.on( 'select', function() {
					 
				
			// Finally, open the modal
			acf.media.frame.open();
				

			return false;
		},
		
		// temporary gallery fix		
		text : {
			title_add : "Select Image",
			title_edit : "Edit Image"
		}
		
	};
	
	
	/*
	*  Events
	*
	*  jQuery events for this field
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('click', '.acf-image-uploader .acf-button-edit', function( e ){
		
		e.preventDefault();
		
		acf.fields.image.set({ $el : $(this).closest('.acf-image-uploader') }).edit();
			
	});
	
	$(document).on('click', '.acf-image-uploader .acf-button-delete', function( e ){
		
		e.preventDefault();
		
		acf.fields.image.set({ $el : $(this).closest('.acf-image-uploader') }).remove();
			
	});
	
	
	$(document).on('click', '.acf-image-uploader .add-image', function( e ){
		
		e.preventDefault();
		
		acf.fields.image.set({ $el : $(this).closest('.acf-image-uploader') }).popup();
		
	});
	

})(jQuery);

/* **********************************************
     Begin radio.js
********************************************** */

(function($){
	
	/*
	*  Radio
	*
	*  static model and events for this field
	*
	*  @type	event
	*  @date	1/06/13
	*
	*/
	
	acf.fields.radio = {
		
		$el : null,
		$input : null,
		$other : null,
		farbtastic : null,
		
		set : function( o ){
			
			// merge in new option
			$.extend( this, o );
			
			
			// find input
			this.$input = this.$el.find('input[type="radio"]:checked');
			this.$other = this.$el.find('input[type="text"]');
			
			
			// return this for chaining
			return this;
			
		},
		change : function(){

			if( this.$input.val() == 'other' )
			{
				this.$other.attr('name', this.$input.attr('name'));
				this.$other.show();
			}
			else
			{
				this.$other.attr('name', '');
				this.$other.hide();
			}
		}
	};
	
	
	/*
	*  Events
	*
	*  jQuery events for this field
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('change', '.acf-radio-list input[type="radio"]', function( e ){
		
		acf.fields.radio.set({ $el : $(this).closest('.acf-radio-list') }).change();
		
	});
	

})(jQuery);

/* **********************************************
     Begin relationship.js
********************************************** */

(function($){
	
	/*
	*  Relationship
	*
	*  static model for this field
	*
	*  @type	event
	*  @date	1/06/13
	*
	*/
	
	acf.fields.relationship = {
		
		$el : null,
		$input : null,
		$left : null,
		$right : null,
				
		o : {},
		
		timeout : null,
		
		set : function( o ){
			
			// merge in new option
			$.extend( this, o );
			
			
			// find elements
			this.$input = this.$el.children('input[type="hidden"]');
			this.$left = this.$el.find('.relationship_left'),
			this.$right = this.$el.find('.relationship_right');
			
			
			// get options
			this.o = acf.helpers.get_atts( this.$el );
			
			
			// return this for chaining
			return this;
			
		},
		init : function(){
			
			// reference
			var _this = this;
			
			
			// is clone field?
			if( acf.helpers.is_clone_field(this.$input) )
			{
				return;
			}
			
			
			// set height of right column
			this.$right.find('.relationship_list').height( this.$left.height() -2 );
			
			
			// right sortable
			this.$right.find('.relationship_list').sortable({
				axis					:	'y',
				items					:	'> li',
				forceHelperSize			:	true,
				forcePlaceholderSize	:	true,
				scroll					:	true,
				update					:	function(){
					
					_this.$input.trigger('change');
					
				}
			});
			
			
			// load more
			var $el = this.$el;
			
			this.$left.find('.relationship_list').scrollTop( 0 ).on('scroll', function(e){
				
				// validate
				if( $el.hasClass('loading') || $el.hasClass('no-results') )
				{
					return;
				}
				
				
				// Scrolled to bottom
				if( $(this).scrollTop() + $(this).innerHeight() >= $(this).get(0).scrollHeight )
				{
					var paged = parseInt( $el.attr('data-paged') );
					
					// update paged
					$el.attr('data-paged', (paged + 1) );
					
					// fetch
					_this.set({ $el : $el }).fetch();
				}
				
			});
			
			
			// ajax fetch values for left side
			this.fetch();
					
		},
		fetch : function(){
			
			// reference
			var _this = this,
				$el = this.$el;
			
			
			// add loading class, stops scroll loading
			$el.addClass('loading');
			
			
			// get results
		    $.ajax({
				url				:	acf.o.ajaxurl,
				type			:	'post',
				dataType		:	'json',
				data			:	$.extend({ 
					action		:	'acf/fields/relationship/query_posts', 
					post_id		:	acf.o.post_id,
					nonce		:	acf.o.nonce
				}, this.o ),
				success			:	function( json ){
					
					
					// render
					_this.set({ $el : $el }).render( json );
					
				}
			});
			
		},
		render : function( json ){
			
			// reference
			var _this = this;
			
			
			// update classes
			this.$el.removeClass('no-results').removeClass('loading');
			
			
			// new search?
			if( this.o.paged == 1 )
			{
				this.$el.find('.relationship_left li:not(.load-more)').remove();
			}
			
			
			// no results?
			if( ! json || ! json.html )
			{
				this.$el.addClass('no-results');
				return;
			}
			
			
			// append new results
			this.$el.find('.relationship_left .load-more').before( json.html );
			
			
			// next page?
			if( ! json.next_page_exists )
			{
				this.$el.addClass('no-results');
			}
							
			
			// apply .hide to left li's
			this.$left.find('a').each(function(){
				
				var id = $(this).attr('data-post_id');
				
				if( _this.$right.find('a[data-post_id="' + id + '"]').exists() )
				{
					$(this).parent().addClass('hide');
				}
				
			});
			
		},
		add : function( $a ){
			
			// vars
			var id = $a.attr('data-post_id'),
				title = $a.html();
			
			
			// max posts
			if( this.$right.find('a').length >= this.o.max )
			{
				alert( acf.l10n.relationship.max.replace('{max}', this.o.max) );
				return false;
			}
			
			
			// can be added?
			if( $a.parent().hasClass('hide') )
			{
				return false;
			}
			
			
			// hide
			$a.parent().addClass('hide');
			
			
			// template
			var data = {
					post_id		:	$a.attr('data-post_id'),
					title		:	$a.html(),
					name		:	this.$input.attr('name')
				},
				tmpl = _.template(acf.l10n.relationship.tmpl_li, data);
			
			
	
			// add new li
			this.$right.find('.relationship_list').append( tmpl )
			
			
			// trigger change on new_li
			this.$input.trigger('change');
			
			
			// validation
			this.$el.closest('.field').removeClass('error');

			
		},
		remove : function( $a ){
			
			// remove
			$a.parent().remove();
			
			
			// show
			this.$left.find('a[data-post_id="' + $a.attr('data-post_id') + '"]').parent('li').removeClass('hide');
			
			
			// trigger change on new_li
			this.$input.trigger('change');
			
		}
		
	};
	
	
	/*
	*  acf/setup_fields
	*
	*  run init function on all elements for this field
	*
	*  @type	event
	*  @date	20/07/13
	*
	*  @param	{object}	e		event object
	*  @param	{object}	el		DOM object which may contain new ACF elements
	*  @return	N/A
	*/
	
	$(document).on('acf/setup_fields', function(e, el){
		
		$(el).find('.acf_relationship').each(function(){
			
			acf.fields.relationship.set({ $el : $(this) }).init();
			
		});
		
	});
	
	
	/*
	*  Events
	*
	*  jQuery events for this field
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('change', '.acf_relationship .select-post_type', function(e){
		
		// vars
		var val = $(this).val(),
			$el = $(this).closest('.acf_relationship');
			
		
		// update attr
	    $el.attr('data-post_type', val);
	    $el.attr('data-paged', 1);
	    
	    
	    // fetch
	    acf.fields.relationship.set({ $el : $el }).fetch();
		
	});

	
	$(document).on('click', '.acf_relationship .relationship_left .relationship_list a', function( e ){
		
		e.preventDefault();
		
		acf.fields.relationship.set({ $el : $(this).closest('.acf_relationship') }).add( $(this) );
		
		$(this).blur();
		
	});
	
	$(document).on('click', '.acf_relationship .relationship_right .relationship_list a', function( e ){
		
		e.preventDefault();
		
		acf.fields.relationship.set({ $el : $(this).closest('.acf_relationship') }).remove( $(this) );
		
		$(this).blur();
		
	});
	
	$(document).on('keyup', '.acf_relationship input.relationship_search', function( e ){
		
		// vars
		var val = $(this).val(),
			$el = $(this).closest('.acf_relationship');
			
		
		// update attr
	    $el.attr('data-s', val);
	    $el.attr('data-paged', 1);
	    
	    
	    // fetch
	    clearTimeout( acf.fields.relationship.timeout );
	    acf.fields.relationship.timeout = setTimeout(function(){
	    
	    	 acf.fields.relationship.set({ $el : $el }).fetch();
	    	
	    }, 500);
		
	});
	
	$(document).on('keypress', '.acf_relationship input.relationship_search', function( e ){
		
		// don't submit form
		if( e.which == 13 )
		{
			e.preventDefault();
		}
		
	});
	

})(jQuery);

/* **********************************************
     Begin tab.js
********************************************** */

(function($){

	
	/*
	*  acf/setup_fields
	*
	*  run init function on all elements for this field
	*
	*  @type	event
	*  @date	20/07/13
	*
	*  @param	{object}	e		event object
	*  @param	{object}	el		DOM object which may contain new ACF elements
	*  @return	N/A
	*/
	
	$(document).on('acf/setup_fields', function(e, el){
		
		$(el).find('.acf-tab').each(function(){
			
			// vars
			var $el		=	$(this),
				$field	=	$el.parent(),
				$wrap	=	$field.parent(),
				
				id		=	$el.attr('data-id'),
				label 	= 	$el.html();
				


			// only run once for each tab
			if( $el.hasClass('acf-tab-added') )
			{
				return;
			}
			
			$el.addClass('acf-tab-added');
			
			
			// create tab group if it doesnt exist
			if( ! $wrap.children('.acf-tab-group').exists() )
			{
				$wrap.children('.field_type-tab:first').before('<ul class="hl clearfix acf-tab-group"></ul>');
			}
			
			
			// add tab
			$wrap.children('.acf-tab-group').append('<li class="field_key-' + id + '" data-field_key="' + id + '"><a class="acf-tab-button" href="#" data-id="' + id + '">' + label + '</a></li>');
			
			
		});
		
		
		// trigger
		$(el).find('.acf-tab-group').each(function(){
			
			$(this).find('li:first a').trigger('click');
			
		});

	
	});
	
	
	/*
	*  Events
	*
	*  jQuery events for this field
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('click', '.acf-tab-button', function( e ){
		
		
		e.preventDefault();
		
		
		// vars
		var $a		=	$(this),
			$ul		=	$a.closest('ul'),
			$wrap	=	$ul.parent(),
			id		=	$a.attr('data-id');
		
		
		// classes
		$ul.find('li').removeClass('active');
		$a.parent('li').addClass('active');
		
		
		// hide / show
		$wrap.children('.field_type-tab').each(function(){
			
			var $tab = $(this);
			
			if( $tab.hasClass('field_key-' + id) )
			{
				$tab.nextUntil('.field_type-tab').removeClass('acf-tab_group-hide').addClass('acf-tab_group-show');
			}
			else
			{
				$tab.nextUntil('.field_type-tab').removeClass('acf-tab_group-show').addClass('acf-tab_group-hide');
			}
			
		});

		
		// blur to remove dotted lines around button
		$a.trigger('blur');

		
	});
		

})(jQuery);

/* **********************************************
     Begin validation.js
********************************************** */

(function($){
	
	
	/*
	*  Validation
	*
	*  JS model
	*
	*  @type	object 
	*  @date	1/06/13
	*
	*/
	
	acf.validation = {
	
		status : true,
		disabled : false,
		
		run : function(){
			
			// reference
			var _this = this;
			
			
			// reset
			_this.status = true;
			
			
			// loop through all fields
			$('.postbox:not(.acf-hidden) .field.required, .form-field.required').each(function(){
				
				// vars
				var div = $(this);
				
				
				// set validation data
				div.data('validation', true);
				
	
				// if is hidden by conditional logic, ignore
				if( div.hasClass('acf-conditional_logic-hide') )
				{
					return;
				}
				
				
				// if is hidden by conditional logic on a parent tab, ignore
				if( div.hasClass('acf-tab_group-hide') )
				{
					if( div.prevAll('.field_type-tab:first').hasClass('acf-conditional_logic-hide') )
					{
						return;
					}
				}
				
				
				// text / textarea
				if( div.find('input[type="text"], input[type="email"], input[type="number"], input[type="hidden"], textarea').val() == "" )
				{
					div.data('validation', false);
				}
				
				
				// wysiwyg
				if( div.find('.acf_wysiwyg').exists() && typeof(tinyMCE) == "object")
				{
					div.data('validation', true);
					
					var id = div.find('.wp-editor-area').attr('id'),
						editor = tinyMCE.get( id );
	
	
					if( editor && !editor.getContent() )
					{
						div.data('validation', false);
					}
				}
				
				
				// select
				if( div.find('select').exists() )
				{
					div.data('validation', true);
	
					if( div.find('select').val() == "null" || ! div.find('select').val() )
					{
						div.data('validation', false);
					}
				}
	
				
				// radio
				if( div.find('input[type="radio"]').exists() )
				{
					div.data('validation', false);
	
					if( div.find('input[type="radio"]:checked').exists() )
					{
						div.data('validation', true);
					}
				}
				
				
				// checkbox
				if( div.find('input[type="checkbox"]').exists() )
				{
					div.data('validation', false);
	
					if( div.find('input[type="checkbox"]:checked').exists() )
					{
						div.data('validation', true);
					}
				}
	
				
				// relationship
				if( div.find('.acf_relationship').exists() )
				{
					div.data('validation', false);
					
					if( div.find('.acf_relationship .relationship_right input').exists() )
					{
						div.data('validation', true);
					}
				}
				
				
				// repeater
				if( div.find('.repeater').exists() )
				{
					div.data('validation', false);
					
					if( div.find('.repeater tr.row').exists() )
					{
						div.data('validation', true);
					}			
				}
				
				
				// flexible content
				if( div.find('.acf_flexible_content').exists() )
				{
					div.data('validation', false);
					if( div.find('.acf_flexible_content .values table').exists() )
					{
						div.data('validation', true);
					}	
				}
				
				
				// gallery
				if( div.find('.acf-gallery').exists() )
				{
					div.data('validation', false);
					
					if( div.find('.acf-gallery .thumbnail').exists())
					{
						div.data('validation', true);
					}
				}
				
				
				// hook for custom validation
				$(document).trigger('acf/validate_field', div );
				
				
				// set validation
				if( ! div.data('validation') )
				{
					_this.status = false;
					div.closest('.field').addClass('error');
				}
				
	
			});
			// end loop through all fields
		}
		
	};
	
	
	/*
	*  Events
	*
	*  Remove error class on focus
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('focus click', '.field.required input, .field.required textarea, .field.required select', function( e ){
	
		$(this).closest('.field').removeClass('error');
		
	});
	
	
	/*
	*  Save Post
	*
	*  If user is saving a draft, allow them to bypass the validation
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('click', '#save-post', function(){
		
		acf.validation.disabled = true;
		
	});
	
	
	/*
	*  Submit Post
	*
	*  Run validation and return true|false accordingly
	*
	*  @type	function
	*  @date	1/03/2011
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	$(document).on('submit', '#post', function(){
		
		// If disabled, bail early on the validation check
		if( acf.validation.disabled )
		{
			return true;
		}
		
		
		// do validation
		acf.validation.run();
			
			
		if( ! acf.validation.status )
		{
			// vars
			var $form = $(this);
			
			
			// show message
			$form.siblings('#message').remove();
			$form.before('<div id="message" class="error"><p>' + acf.l10n.validation.error + '</p></div>');
			
			
			// hide ajax stuff on submit button
			$('#publish').removeClass('button-primary-disabled');
			$('#ajax-loading').attr('style','');
			$('#publishing-action .spinner').hide();
			
			return false;
		}

		
		// remove hidden postboxes
		// + this will stop them from being posted to save
		$('.acf_postbox.acf-hidden').remove();
		

		// submit the form
		return true;
		
	});
	

})(jQuery);

/* **********************************************
     Begin wysiwyg.js
********************************************** */

(function($){
	
	/*
	*  WYSIWYG
	*
	*  jQuery functionality for this field type
	*
	*  @type	object
	*  @date	20/07/13
	*
	*  @param	N/A
	*  @return	N/A
	*/
	
	var _wysiwyg = acf.fields.wysiwyg = {
		
		$el : null,
		$textarea : null,
		
		o : {},
		
		set : function( o ){
			
			// merge in new option
			$.extend( this, o );
			
			
			// find textarea
			this.$textarea = this.$el.find('textarea');
			
			
			// get options
			this.o = acf.helpers.get_atts( this.$el );
			
			
			// add ID
			this.o.id = this.$textarea.attr('id');
			
			
			// return this for chaining
			return this;
			
		},
		has_tinymce : function(){
		
			var r = false;
			
			if( typeof(tinyMCE) == "object" )
			{
				r = true;
			}
			
			return r;
			
		},
		init : function(){
			
			// is clone field?
			if( acf.helpers.is_clone_field( this.$textarea ) )
			{
				return;
			}
			
			
			// temp store tinyMCE.settings
			var tinyMCE_settings = $.extend( {}, tinyMCE.settings );
			
			
			// reset tinyMCE settings
			tinyMCE.settings.theme_advanced_buttons1 = '';
			tinyMCE.settings.theme_advanced_buttons2 = '';
			tinyMCE.settings.theme_advanced_buttons3 = '';
			tinyMCE.settings.theme_advanced_buttons4 = '';
			
			if( acf.helpers.isset( this.toolbars[ this.o.toolbar ] ) )
			{
				$.each( this.toolbars[ this.o.toolbar ], function( k, v ){
					tinyMCE.settings[ k ] = v;
				})
			}
				
				
			// add functionality back in
			tinyMCE.execCommand("mceAddControl", false, this.o.id);
			
			
			// events - load
			$(document).trigger('acf/wysiwyg/load', this.o.id);
				
				
			// add events (click, focus, blur) for inserting image into correct editor
			this.add_events();
				
			
			// restore tinyMCE.settings
			tinyMCE.settings = tinyMCE_settings;
			
			
			// set active editor to null
			wpActiveEditor = null;
					
		},
		add_events : function(){
		
			// vars
			var id = this.o.id,
				editor = tinyMCE.get( id );
			
			
			// validate
			if( !editor )
			{
				return;
			}
			
			
			// vars
			var	$container = $('#wp-' + id + '-wrap'),
				$body = $( editor.getBody() );
	
			
			// events
			$container.on('click', function(){
			
				$(document).trigger('acf/wysiwyg/click', id);
				
			});
			
			$body.on('focus', function(){
			
				$(document).trigger('acf/wysiwyg/focus', id);
				
			});
			
			$body.on('blur', function(){
			
				$(document).trigger('acf/wysiwyg/blur', id);
				
			});
			
			
		},
		destroy : function(){
			
			// vars
			var id = this.o.id,
				editor = tinyMCE.get( id );
			
			
			// Remove tinymcy functionality.
			// Due to the media popup destroying and creating the field within such a short amount of time,
			// a JS error will be thrown when launching the edit window twice in a row.
			try
			{
				tinyMCE.execCommand("mceRemoveControl", false, id);
			} 
			catch(e)
			{
				console.log( e );
			}
			
			
			// set active editor to null
			wpActiveEditor = null;
			
		}
		
	};
	
	
	/*
	*  acf/setup_fields
	*
	*  run init function on all elements for this field
	*
	*  @type	event
	*  @date	20/07/13
	*
	*  @param	{object}	e		event object
	*  @param	{object}	el		DOM object which may contain new ACF elements
	*  @return	N/A
	*/
	
	$(document).on('acf/setup_fields', function(e, el){
		
		// validate
		if( ! _wysiwyg.has_tinymce() )
		{
			return;
		}
		
		
		// Destory all WYSIWYG fields
		// This hack will fix a problem when the WP popup is created and hidden, then the ACF popup (image/file field) is opened
		$(el).find('.acf_wysiwyg').each(function(){
			
			_wysiwyg.set({ $el : $(this) }).destroy();
			
		});
		
		
		// Add WYSIWYG fields
		setTimeout(function(){
			
			$(el).find('.acf_wysiwyg').each(function(){
			
				_wysiwyg.set({ $el : $(this) }).init();
				
			});
			
		}, 0);
		
	});
	
	
	/*
	*  acf/remove_fields
	*
	*  This action is called when the $el is being removed from the DOM
	*
	*  @type	event
	*  @date	20/07/13
	*
	*  @param	{object}	e		event object
	*  @param	{object}	$el		jQuery element being removed
	*  @return	N/A
	*/
	
	$(document).on('acf/remove_fields', function(e, $el){
		
		// validate
		if( ! _wysiwyg.has_tinymce() )
		{
			return;
		}
		
		
		$el.find('.acf_wysiwyg').each(function(){
			
			_wysiwyg.set({ $el : $(this) }).destroy();
			
		});
		
	});
		
	
	/*
	*  acf/wysiwyg/click
	*
	*  this event is run when a user clicks on a WYSIWYG field
	*
	*  @type	event
	*  @date	17/01/13
	*
	*  @param	{object}	e		event object
	*  @param	{int}		id		WYSIWYG ID
	*  @return	N/A
	*/
	
	$(document).on('acf/wysiwyg/click', function(e, id){
		
		wpActiveEditor = id;
		
		container = $('#wp-' + id + '-wrap').closest('.field').removeClass('error');
		
	});
	
	
	/*
	*  acf/wysiwyg/focus
	*
	*  this event is run when a user focuses on a WYSIWYG field body
	*
	*  @type	event
	*  @date	17/01/13
	*
	*  @param	{object}	e		event object
	*  @param	{int}		id		WYSIWYG ID
	*  @return	N/A
	*/
	
	$(document).on('acf/wysiwyg/focus', function(e, id){
		
		wpActiveEditor = id;
		
		container = $('#wp-' + id + '-wrap').closest('.field').removeClass('error');
		
	});
	
	
	/*
	*  acf/wysiwyg/blur
	*
	*  this event is run when a user loses focus on a WYSIWYG field body
	*
	*  @type	event
	*  @date	17/01/13
	*
	*  @param	{object}	e		event object
	*  @param	{int}		id		WYSIWYG ID
	*  @return	N/A
	*/
	
	$(document).on('acf/wysiwyg/blur', function(e, id){
		
		wpActiveEditor = null;
		
		// update the hidden textarea
		// - This fixes a but when adding a taxonomy term as the form is not posted and the hidden tetarea is never populated!
		var editor = tinyMCE.get( id );
		
		
		// validate
		if( !editor )
		{
			return;
		}
		
		
		var el = editor.getElement();
		
			
		// save to textarea	
		editor.save();
		
		
		// trigger change on textarea
		$( el ).trigger('change');
		
	});

	
	/*
	*  acf/sortable_start
	*
	*  this event is run when a element is being drag / dropped
	*
	*  @type	event
	*  @date	10/11/12
	*
	*  @param	{object}	e		event object
	*  @param	{object}	el		DOM object which may contain new ACF elements
	*  @return	N/A
	*/
	
	$(document).on('acf/sortable_start', function(e, el) {
		
		// validate
		if( ! _wysiwyg.has_tinymce() )
		{
			return;
		}
		
		
		$(el).find('.acf_wysiwyg').each(function(){
			
			_wysiwyg.set({ $el : $(this) }).destroy();
			
		});
		
	});
	
	
	/*
	*  acf/sortable_stop
	*
	*  this event is run when a element has finnished being drag / dropped
	*
	*  @type	event
	*  @date	10/11/12
	*
	*  @param	{object}	e		event object
	*  @param	{object}	el		DOM object which may contain new ACF elements
	*  @return	N/A
	*/
	
	$(document).on('acf/sortable_stop', function(e, el) {
		
		// validate
		if( ! _wysiwyg.has_tinymce() )
		{
			return;
		}
		
		
		$(el).find('.acf_wysiwyg').each(function(){
			
			_wysiwyg.set({ $el : $(this) }).init();
			
		});
		
	});
	
	
	/*
	*  window load
	*
	*  @description: 
	*  @since: 3.5.5
	*  @created: 22/12/12
	*/
	
	$(window).load(function(){
		
		// validate
		if( ! _wysiwyg.has_tinymce() )
		{
			return;
		}
		
		
		// vars
		var wp_content = $('#wp-content-wrap').exists(),
			wp_acf_settings = $('#wp-acf_settings-wrap').exists()
			mode = 'tmce';
		
		
		// has_editor
		if( wp_acf_settings )
		{
			// html_mode
			if( $('#wp-acf_settings-wrap').hasClass('html-active') )
			{
				mode = 'html';
			}
		}
		
		
		setTimeout(function(){
			
			// trigger click on hidden wysiwyg (to get in HTML mode)
			if( wp_acf_settings && mode == 'html' )
			{
				$('#acf_settings-tmce').trigger('click');
			}
			
		}, 1);
		
		
		setTimeout(function(){
			
			// trigger html mode for people who want to stay in HTML mode
			if( wp_acf_settings && mode == 'html' )
			{
				$('#acf_settings-html').trigger('click');
			}
			
			// Add events to content editor
			if( wp_content )
			{
				_wysiwyg.set({ $el : $('#wp-content-wrap') }).add_events();
			}
			
			
		}, 11);
		
	});
	
	
	/*
	*  Full screen
	*
	*  @description: this hack will hide the 'image upload' button in the wysiwyg full screen mode if the field has disabled image uploads!
	*  @since: 3.6
	*  @created: 26/02/13
	*/
	
	$(document).on('click', '.acf_wysiwyg a.mce_fullscreen', function(){
		
		// vars
		var wysiwyg = $(this).closest('.acf_wysiwyg'),
			upload = wysiwyg.attr('data-upload');
		
		if( upload == 'no' )
		{
			$('#mce_fullscreen_container td.mceToolbar .mce_add_media').remove();
		}
		
	});
	

})(jQuery);