var acf = {
	post_id : 0,
	nonce : '',
	admin_url : '',
	ajaxurl : '',
	text : {},
	l10n : {},
	helpers : {
		uniqid : function(){},
		sortable : function(){},
		create_field : function(){}
	},
	conditional_logic : {
		fields : [],
		setup : function(){}
	},
	location : {
		$el : null,
		init : function(){},
		add_rule : function(){},
		remove_rule : function(){},
		add_group : function(){},
		remove_group : function(){}
	}
};

(function($){

	
	/*
	*  Exists
	*  
	*  @since			3.1.6
	*  @description		returns true or false on a element's existance
	*/
	
	$.fn.exists = function()
	{
		return $(this).length>0;
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
	*  acf.helpers.uniqid
	*
	*  @description: JS equivelant of PHP uniqid
	*  @since: 3.6
	*  @created: 7/03/13
	*/
	
	acf.helpers.uniqid = function(prefix, more_entropy)
    {
    	  // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		  // +    revised by: Kankrelune (http://www.webfaktory.info/)
		  // %        note 1: Uses an internal counter (in php_js global) to avoid collision
		  // *     example 1: uniqid();
		  // *     returns 1: 'a30285b160c14'
		  // *     example 2: uniqid('foo');
		  // *     returns 2: 'fooa30285b1cd361'
		  // *     example 3: uniqid('bar', true);
		  // *     returns 3: 'bara20285b23dfd1.31879087'
		  if (typeof prefix == 'undefined') {
		    prefix = "";
		  }
		
		  var retId;
		  var formatSeed = function (seed, reqWidth) {
		    seed = parseInt(seed, 10).toString(16); // to hex str
		    if (reqWidth < seed.length) { // so long we split
		      return seed.slice(seed.length - reqWidth);
		    }
		    if (reqWidth > seed.length) { // so short we pad
		      return Array(1 + (reqWidth - seed.length)).join('0') + seed;
		    }
		    return seed;
		  };
		
		  // BEGIN REDUNDANT
		  if (!this.php_js) {
		    this.php_js = {};
		  }
		  // END REDUNDANT
		  if (!this.php_js.uniqidSeed) { // init seed with big random int
		    this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
		  }
		  this.php_js.uniqidSeed++;
		
		  retId = prefix; // start with prefix, add current milliseconds hex string
		  retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
		  retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
		  if (more_entropy) {
		    // for more entropy we add a float lower to 10
		    retId += (Math.random() * 10).toFixed(8).toString();
		  }
		
		  return retId;

    };
        
    
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
		
		// validate post title
		var title = $('#titlewrap #title');
		
		if( !title.val() )
		{
			alert( acf.l10n.title );
			
			title.focus();
		
			return false;
		}

		
	});
	
	
	/*
	*  Place Confirm message on Publish trash button
	*  
	*  @since			3.1.6
	*  @description		
	*/
	
	$(document).on('click', '#submit-delete', function(){
			
		var response = confirm( acf.l10n.move_to_trash );
		if( !response )
		{
			return false;
		}
		
	});
	
	
	/*
	*  acf/update_field_options
	*  
	*  @since			3.1.6
	*  @description		Load in the opions html
	*/
	
	$(document).on('change', '#acf_fields tr.field_type select', function(){
		
		// vars
		var select = $(this),
			tbody = select.closest('tbody'),
			field = tbody.closest('.field'),
			field_type = field.attr('data-type'),
			field_key = field.attr('data-id'),
			val = select.val();
			
		
		
		// update data atts
		field.removeClass('field_type-' + field_type).addClass('field_type-' + val);
		field.attr('data-type', val);
		
		
		// tab - override field_name
		if( val == 'tab' || val == 'message' )
		{
			tbody.find('tr.field_name input[type="text"]').val('').trigger('keyup');
		}
		
		
		// show field options if they already exist
		if( tbody.children( 'tr.field_option_' + val ).exists() )
		{
			// hide + disable options
			tbody.children('tr.field_option').hide().find('[name]').attr('disabled', 'true');
			
			// show and enable options
			tbody.children( 'tr.field_option_' + val ).show().find('[name]').removeAttr('disabled');
		}
		else
		{
			// add loading gif
			var tr = $('<tr"><td class="label"></td><td><div class="acf-loading"></div></td></tr>');
			
			// hide current options
			tbody.children('tr.field_option').hide().find('[name]').attr('disabled', 'true');
			
			
			// append tr
			if( tbody.children('tr.conditional-logic').exists() )
			{
				tbody.children('tr.conditional-logic').before(tr);
			}
			else
			{
				tbody.children('tr.field_save').before(tr);
			}
			
			
			var ajax_data = {
				'action' : 'acf/field_group/render_options',
				'post_id' : acf.post_id,
				'field_key' : select.attr('name'),
				'field_type' : val,
				'nonce' : acf.nonce
			};
			
			$.ajax({
				url: ajaxurl,
				data: ajax_data,
				type: 'post',
				dataType: 'html',
				success: function(html){
					
					if( ! html )
					{
						tr.remove();
						return;
					}
					
					tr.replaceWith(html);
					
				}
			});
		}
		
	});
	
	
	/*
	*  Update Names
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	$.fn.update_names = function()
	{
		var field = $(this),
			old_id = field.attr('data-id'),
			new_id = 'field_' + acf.helpers.uniqid();
		
		
		// give field a new id
		field.attr('data-id', new_id);
		
		
		// update class
		field.attr('class', field.attr('class').replace(old_id, new_id) );
		
		
		// update field key column
		field.find('.field_meta td.field_key').text( new_id );
		
		
		// update attributes
		field.find('[id*="' + old_id + '"]').each(function()
		{	
			$(this).attr('id', $(this).attr('id').replace(old_id, new_id) );
		});
		
		field.find('[name*="' + old_id + '"]').each(function()
		{	
			$(this).attr('name', $(this).attr('name').replace(old_id, new_id) );
		});
		
	};
	
	
	/*
	*  update_order_numbers
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	function update_order_numbers(){
		
		$('#acf_fields .fields').each(function(){
			$(this).children('.field').each(function(i){
				$(this).find('td.field_order .circle').first().html(i+1);
			});
		});

	}
	
	
	/*
	*  Edit Field
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 13/10/12
	*/
	
	$(document).on('click', '#acf_fields a.acf_edit_field', function(){
		
		var field = $(this).closest('.field');
		
		if( field.hasClass('form_open') )
		{
			field.removeClass('form_open');
			$(document).trigger('acf/field_form-close', field);
		}
		else
		{
			field.addClass('form_open');
			$(document).trigger('acf/field_form-open', field);
		}
		
		field.children('.field_form_mask').animate({'height':'toggle'}, 250);
		
	});
	
	
	/*
	*  Delete Field
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 13/10/12
	*/
	
	$(document).on('click', '#acf_fields a.acf_delete_field', function(){
		
		// vars
		var a = $(this),
			field = a.closest('.field'),
			fields = field.closest('.fields'),
			temp = $('<div style="height:' + field.height() + 'px"></div>');
			
			
		// fade away
		field.animate({'left' : '50px', 'opacity' : 0}, 250, function(){
			
			field.before(temp);
			field.remove();
			

			// no more fields, show the message
			if( fields.children('.field').length <= 1 )
			{
				temp.remove();
				fields.children('.no_fields_message').show();
			}
			else
			{
				temp.animate({'height' : 0 }, 250, function(){
					temp.remove();
				});
			}
			
			update_order_numbers();
			
		});
		
		
	});
	
	
	/*
	*  Duplicate Field
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 13/10/12
	*/
	
	$(document).on('click', '#acf_fields a.acf_duplicate_field', function(){
			
		// vars
		var a = $(this),
			field = a.closest('.field'),
			new_field = null;
			
			
		// save select values
		field.find('select').each(function(){
			$(this).attr( 'data-val', $(this).val() );
		});
		
		
		// clone field
		new_field = field.clone();
		
		
		// update names
		new_field.update_names();
		new_field.find('.field:not(.field_key-field_clone)').each(function(){
			$(this).update_names();
		});

		
		// add new field
		field.after( new_field );
		
		
		// open up form
		if( field.hasClass('form_open') )
		{
			field.find('.acf_edit_field').first().trigger('click');
		}
		else
		{
			new_field.find('.acf_edit_field').first().trigger('click');
		}
		
		
		// update new_field label / name
		var label = new_field.find('tr.field_label:first input[type="text"]'),
			name = new_field.find('tr.field_name:first input[type="text"]');
					
		
		// set select values
		new_field.find('select').each(function(){
			$(this).val( $(this).attr('data-val') ).trigger('change');
		});
		
		
		name.val('');
		label.val( label.val() + ' (' + acf.l10n.copy + ')' );
		label.trigger('blur').trigger('keyup');
		
		
		// update order numbers
		update_order_numbers();
		
	});
	
	
	/*
	*  Add Field
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 13/10/12
	*/
	
	$(document).on('click', '#acf_fields #add_field', function(){
		
		var fields = $(this).closest('.table_footer').siblings('.fields');
		
		
		// clone last tr
		var new_field = fields.children('.field_key-field_clone').clone();
		
		
		// update names
		new_field.update_names();
		
		
		// show
		new_field.show();
		
		
		// append to table
		fields.children('.field_key-field_clone').before(new_field);
		
		
		// remove no fields message
		if(fields.children('.no_fields_message').exists())
		{
			fields.children('.no_fields_message').hide();
		}
		
		
		// clear name
		new_field.find('tr.field_type select').trigger('change');	
		new_field.find('.field_form input[type="text"]').val('');
		
		
		// focus after form has dropped down
		// - this prevents a strange rendering bug in Firefox
		setTimeout(function(){
        	new_field.find('.field_form input[type="text"]').first().focus();
        }, 500);
        

		// open up form
		new_field.find('a.acf_edit_field').first().trigger('click');

		
		// update order numbers
		update_order_numbers();
		
		return false;
		
		
	});
	
	
	/*
	*  Auto Complete Field Name
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	$(document).on('blur', '#acf_fields tr.field_label input.label', function(){
	
		// vars
		var $label = $(this),
			$field = $label.closest('.field'),
			$name = $field.find('tr.field_name:first input[type="text"]'),
			type = $field.attr('data-type');
			
			
		// leave blank for tab or message field
		if( type == 'tab' || type == 'message' )
		{
			$name.val('').trigger('keyup');
			return;
		}
			
		
		if( $name.val() == '' )
		{
			// thanks to https://gist.github.com/richardsweeney/5317392 for this code!
			var val = $label.val(),
				replace = {
					'ä': 'a',
					'æ': 'a',
					'å': 'a',
					'ö': 'o',
					'ø': 'o',
					'é': 'e',
					'ë': 'e',
					'ü': 'u',
					'ó': 'o',
					'ő': 'o',
					'ú': 'u',
					'é': 'e',
					'á': 'a',
					'ű': 'u',
					'í': 'i',
					' ' : '_',
					'\'' : ''
				};
			
			$.each( replace, function(k, v){
				var regex = new RegExp( k, 'g' );
				val = val.replace( regex, v );
			});
			
			
			val = val.toLowerCase();
			$name.val( val );
			$name.trigger('keyup');
		}
		
	});
	
	
	/*
	*  Update field meta
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	$(document).on('keyup', '#acf_fields .field_form tr.field_label input.label', function(){
	
		var val = $(this).val();
		var name = $(this).closest('.field').find('td.field_label strong a').first().html(val);
		
	});
	
	$(document).on('keyup', '#acf_fields .field_form tr.field_name input.name', function(){
	
		var val = $(this).val();
		var name = $(this).closest('.field').find('td.field_name').first().html(val);
		
	});
	
	$(document).on('change', '#acf_fields .field_form tr.field_type select', function(){
	
		var val = $(this).val();
		var label = $(this).find('option[value="' + val + '"]').html();
		
		$(this).closest('.field').find('td.field_type').first().html(label);
		
	});
	
	
	// sortable
	$(document).on('mouseover', '#acf_fields td.field_order', function(){
		
		// vars
		var fields = $(this).closest('.fields');
		
		
		if( fields.hasClass('sortable') )
		{
			return false;
		}
		
		
		fields.addClass('sortable').sortable({
			update: function(event, ui){
				update_order_numbers();
			},
			handle: 'td.field_order'
		});
	});
	
	
	/*
	*  Setup Location Rules
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	$(document).ready(function(){
		
		acf.location.init();
		
	});
	
	
	/*
	*  location
	*
	*  {description}
	*
	*  @since: 4.0.3
	*  @created: 13/04/13
	*/
	
	acf.location = {
		$el : null,
		init : function(){
			
			// vars
			var _this = this;
			
			
			// $el
			_this.$el = $('#acf_location');
			
			
			// add rule
			_this.$el.on('click', '.location-add-rule', function(){
				
				_this.add_rule( $(this).closest('tr') );
				
				return false;
				
			});
			
			
			// remove rule
			_this.$el.on('click', '.location-remove-rule', function(){
							
				_this.remove_rule( $(this).closest('tr') );
				
				return false;
				
			});
			
			
			// add rule
			_this.$el.on('click', '.location-add-group', function(){
							
				_this.add_group();
				
				return false;
				
			});
			
			
			// change rule
			_this.$el.on('change', '.param select', function(){
							
				// vars
				var $tr = $(this).closest('tr'),
					rule_id = $tr.attr('data-id'),
					$group = $tr.closest('.location-group'),
					group_id = $group.attr('data-id'),
					ajax_data = {
						'action' : "acf/field_group/render_location",
						'nonce' : acf.nonce,
						'rule_id' : rule_id,
						'group_id' : group_id,
						'value' : '',
						'param' : $(this).val()
					};
				
				
				// add loading gif
				var div = $('<div class="acf-loading"></div>');
				$tr.find('td.value').html( div );
				
				
				// load location html
				$.ajax({
					url: acf.ajaxurl,
					data: ajax_data,
					type: 'post',
					dataType: 'html',
					success: function(html){
		
						div.replaceWith(html);
		
					}
				});
				
				
			});
			
		},
		add_rule : function( $tr ){
			
			// vars
			var $tr2 = $tr.clone(),
				old_id = $tr2.attr('data-id'),
				new_id = acf.helpers.uniqid();
			
			
			// update names
			$tr2.find('[name]').each(function(){
				
				$(this).attr('name', $(this).attr('name').replace( old_id, new_id ));
				$(this).attr('id', $(this).attr('id').replace( old_id, new_id ));
				
			});
				
				
			// update data-i
			$tr2.attr( 'data-id', new_id );
			
			
			// add tr
			$tr.after( $tr2 );
					
			
			return false;
			
		},
		remove_rule : function( $tr ){
			
			// vars
			var siblings = $tr.siblings('tr').length;

			
			if( siblings == 0 )
			{
				// remove group
				this.remove_group( $tr.closest('.location-group') );
			}
			else
			{
				// remove tr
				$tr.remove();
			}
			
		},
		add_group : function(){
			
			// vars
			var $group = this.$el.find('.location-group:last'),
				$group2 = $group.clone(),
				old_id = $group2.attr('data-id'),
				new_id = acf.helpers.uniqid();
			
			
			// update names
			$group2.find('[name]').each(function(){
				
				$(this).attr('name', $(this).attr('name').replace( old_id, new_id ));
				$(this).attr('id', $(this).attr('id').replace( old_id, new_id ));
				
			});
			
			
			// update data-i
			$group2.attr( 'data-id', new_id );
			
			
			// update h4
			$group2.find('h4').text( acf.l10n.or );
			
			
			// remove all tr's except the first one
			$group2.find('tr:not(:first)').remove();
			
			
			// add tr
			$group.after( $group2 );
			
			
			
		},
		remove_group : function( $group ){
			
			$group.remove();
			
		}
	};
	
	

	/*----------------------------------------------------------------------
	*
	*	Document Ready
	*
	*---------------------------------------------------------------------*/
	
	$(document).ready(function(){
		
		// custom Publish metabox
		$('#submitdiv #publish').attr('class', 'acf-button');
		$('#submitdiv a.submitdelete').attr('class', 'delete-field-group').attr('id', 'submit-delete');
		
	});
	
	
	
	/*
	*  Screen Options
	*
	*  @description: 
	*  @created: 4/09/12
	*/
	
	$(document).on('change', '#adv-settings input[name="show-field_key"]', function(){
		
		if( $(this).val() == "1" )
		{
			$('#acf_fields table.acf').addClass('show-field_key');
		}
		else
		{
			$('#acf_fields table.acf').removeClass('show-field_key');
		}
		
	});
	
	
	/*
	*  Conditional Logic
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 11/10/12
	*/
	
	acf.helpers.create_field = function( options ){
		
		// dafaults
		var defaults = {
			'type' : 'text',
			'classname' : '',
			'name' : '',
			'value' : ''
		};
		options = $.extend(true, defaults, options);
		
		
		// vars
		var html = "";
		
		if( options.type == "text" )
		{
			html += '<input class="text ' + options.classname + '" type="text" id="' + options.name + '" name="' + options.name + '" value="' + options.value + '" />';
		}
		else if( options.type == "select" )
		{
			html += '<select class="select ' + options.classname + '" id="' + options.name + '" name="' + options.name + '">';
			if( options.choices )
			{
				for( var i = 0; i < options.choices.length; i++ )
				{
					var attr = '';
					if( options.choices[i].value == options.value )
					{
						attr = 'selected="selected"';
					}
					html += '<option ' + attr + ' value="' + options.choices[i].value + '">' + options.choices[i].label + '</option>';
				}
			}
			html += '</select>';
		}
		
		html = $(html);
		
		return html;
			
	};
	
	$(document).on('acf/field_form-open', function(e, field){
		
		// populate fields
		acf.conditional_logic.setup();
		
		
		$(field).find('.conditional-logic-field').each(function(){
			
			var val = $(this).val(),
				name = $(this).attr('name'),
				choices = [];
			
			
			// populate choices
			if( acf.conditional_logic.fields )
			{
				for( var i = 0; i < acf.conditional_logic.fields.length; i++ )
				{
					choices.push({
						value : acf.conditional_logic.fields[i].id,
						label : acf.conditional_logic.fields[i].label
					});
				}
			}
			
			
			// empty?
			if( choices.length == 0 )
			{
				choices.push({
					'value' : 'null',
					'label' : acf.l10n.conditional_no_fields
				});
			}
	
			
			// create select
			var select = acf.helpers.create_field({
				'type' : 'select',
				'classname' : 'conditional-logic-field',
				'name' : name,
				'value' : val,
				'choices' : choices
			});
			

			$(this).replaceWith( select );
			
			select.trigger('change');
				
		});
		
	});
	
	
	
	/*
	*  Toggle Conditional Logic
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 14/10/12
	*/
	
	$(document).on('change', 'tr.conditional-logic input[type="radio"]', function(){
		
		if( $(this).val() == "1" )
		{
			$(this).closest('tr.conditional-logic').find('.contional-logic-rules-wrapper').show();
		}
		else
		{
			$(this).closest('tr.conditional-logic').find('.contional-logic-rules-wrapper').hide();
		}
		
	});
	
	
	/*
	*  Conditional logic: Change field
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 14/10/12
	*/
	
	$(document).on('change', 'select.conditional-logic-field', function(){
		
		// vars
		var id = $(this).val(),
			field = $('#acf_fields .field_key-' + id),
			type = field.attr('data-type'),
			conditional_function = $(this).closest('tr').find('.conditional-logic-value'),
			choices = [];
			
		
		// populate choices
		if( type == "true_false" )
		{
			choices = [
				{ value : 1, label : acf.l10n.checked }
			];
						
		}
		else if( type == "select" || type == "checkbox" || type == "radio" )
		{
			var field_choices = field.find('.field_option-choices').val().split("\n");
						
			if( field_choices )
			{
				for( var i = 0; i < field_choices.length; i++ )
				{
					var choice = field_choices[i].split(':');
					
					var label = choice[0];
					if( choice[1] )
					{
						label = choice[1];
					}
					
					choices.push({
						'value' : $.trim( choice[0] ),
						'label' : $.trim( label )
					});
					
				}
			}
			
		}
		
		
		// create select
		var select = acf.helpers.create_field({
			'type' : 'select',
			'classname' : 'conditional-logic-value',
			'name' : conditional_function.attr('name'),
			'value' : conditional_function.val(),
			'choices' : choices
		});
		
		conditional_function.replaceWith( select );
		
		
	});

	
	
	/*
	*  setup_conditional_fields
	*
	*  @description: populates the acf object with all available fields
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	acf.conditional_logic.setup = function()
	{
		// reset
		acf.conditional_logic.fields = [];
		
		
		// loop through fields
		$('#acf_fields > .inside > .fields > .field:not(.field_key-field_clone)').each(function(){
			
			var field = $(this),
				id = field.attr('data-id'),
				//key = field.children('.input-field_key').val(),
				type = field.attr('data-type'),
				label = field.find('tr.field_label input').val();
			
			
			if( type == 'select' || type == 'checkbox' || type == 'true_false' || type == 'radio' )
			{
				acf.conditional_logic.fields.push({
					id : id,
					type : type,
					label : label
				});
			}
			
			
		});
		
	};
	
	
	/*
	*  Add conditional rule
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	$(document).on('click', 'tr.conditional-logic .acf-button-add', function(){
		
		// vars
		var old_tr = $(this).closest('tr'),
			new_tr = old_tr.clone(),
			old_i = parseFloat( new_tr.attr('data-i') ),
			new_i = old_i + 1;
		
		
		// update names
		new_tr.find('[name]').each(function(){
			
			$(this).attr('name', $(this).attr('name').replace('[' + old_i + ']', '[' + new_i + ']') );
			$(this).attr('id', $(this).attr('id').replace('[' + old_i + ']', '[' + new_i + ']') );
			
		});
			
			
		// update data-i
		new_tr.attr('data-i', new_i);
		
		
		// add tr
		old_tr.after( new_tr );
		
		
		// remove disabled
		old_tr.closest('table').removeClass('remove-disabled');
				
		
		return false;
		
	});
	
	
	/*
	*  Remove conditional rule
	*
	*  @description: 
	*  @since 3.5.1
	*  @created: 15/10/12
	*/
	
	$(document).on('click', 'tr.conditional-logic .acf-button-remove', function(){
		
		var table = $(this).closest('table');
		
		// validate
		if( table.hasClass('remove-disabled') )
		{
			return false;
		}
		
		
		// remove tr
		$(this).closest('tr').remove();
		
		
		// add clas to table
		if( table.find('tr').length <= 1 )
		{
			table.addClass('remove-disabled');
		}
		
		
		return false;
		
	});
	
	
	/*
	*  Field: Radio
	*
	*  Simple toggle for the radio 'other_choice' option
	*
	*  @type	function
	*  @date	1/07/13
	*/
	
	$(document).on('change', '.radio-option-other_choice input', function(){
		
		// vars
		var $el = $(this),
			$td = $el.closest('td');
		
		
		if( $el.is(':checked') )
		{
			$td.find('.radio-option-save_other_choice').show();
		}
		else
		{
			$td.find('.radio-option-save_other_choice').hide();
			$td.find('.radio-option-save_other_choice input').removeAttr('checked');
		}
		
	});

	

})(jQuery);