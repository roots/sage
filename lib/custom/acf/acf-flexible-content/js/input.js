/*
*  Flexible Content
*
*  @description: 
*  @since: 3.5.8
*  @created: 17/01/13
*/

(function($){
	
	/*
	*  Vars
	*
	*  @description: 
	*  @since: 3.6
	*  @created: 30/01/13
	*/
	
	acf.fields.flexible_content = {
		add_sortable : function(){},
		update_order : function(){},
		add_layout : function(){},
		remove_layout : function(){}
	}
	
	
	var _flex = acf.fields.flexible_content;
	
	
	/*
	*  Add Sortable
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_flex.add_sortable = function( div )
	{
		
		// remove (if clone) and add sortable
		div.children('.values').unbind('sortable').sortable({
			items : '> .layout',
			handle : '> .menu-item-handle',
			forceHelperSize : true,
			forcePlaceholderSize : true,
			scroll : true,
			start : function (event, ui) {
			
				$(document).trigger('acf/sortable_start', ui.item);
				$(document).trigger('acf/sortable_start_flex', ui.item);
        		
   			},
   			stop : function (event, ui) {
			
				$(document).trigger('acf/sortable_stop', ui.item);
				$(document).trigger('acf/sortable_stop_flex', ui.item);
				
				// update order numbers				
				_flex.update_order( div );
   			}
		});
		
	};
	
	
	/*
	*  Update Order
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_flex.update_order = function( div )
	{
		div.find('> .values .layout').each(function(i){
			$(this).find('> .menu-item-handle .fc-layout-order').html(i+1);
		});
	
	};
	
	
	/*
	*  Add Layout
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_flex.add_layout = function( layout, div )
	{
		// vars
		var new_id = acf.helpers.uniqid(),
			new_field_html = div.find('> .clones > .layout[data-layout="' + layout + '"]').html().replace(/(=["]*[\w-\[\]]*?)(acfcloneindex)/g, '$1' + new_id),
			new_field = $('<div class="layout" data-layout="' + layout + '"></div>').append( new_field_html );
			
			
		// hide no values message
		div.children('.no_value_message').hide();
		
		
		// add row
		div.children('.values').append(new_field); 
		
		
		// acf/setup_fields
		$(document).trigger('acf/setup_fields',new_field);
		
		
		// update order numbers
		_flex.update_order( div );
		
		
		// validation
		div.closest('.field').removeClass('error');
	}
	
	
	/*
	*  Remove Layout
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_flex.remove_layout = function( layout )
	{
		// vars
		var div = layout.closest('.acf_flexible_content');
		var temp = $('<div style="height:' + layout.height() + 'px"></div>');
		
		
		// animate out tr
		layout.addClass('acf-remove-item');
		setTimeout(function(){
			
			layout.before(temp).remove();
			
			temp.animate({'height' : 0 }, 250, function(){
				temp.remove();
			});
		
			if( !div.children('.values').children('.layout').exists() )
			{
				div.children('.no_value_message').show();
			}
			
		}, 400);
		
	}
	
	
	/*
	*  acf/setup_fields
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.acf_flexible_content').each(function(){
			
			var div =  $(this);

			// sortable
			_flex.add_sortable( div );
						
		});
		
	});


	/*
	*  Show / Hide popup
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.acf_flexible_content .flexible-footer .add-row-end').live('click', function()
	{
		$(this).trigger('focus');
		
	}).live('focus', function()
	{
		$(this).siblings('.acf-popup').addClass('active');
		
	}).live('blur', function()
	{
		var button = $(this);
		setTimeout(function(){
			button.siblings('.acf-popup').removeClass('active');
		}, 250);
		
	});
	
	
	/*
	*  Delete Layout Button
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.acf_flexible_content .fc-delete-layout').live('click', function(){
	
		var layout = $(this).closest('.layout');
		
		_flex.remove_layout( layout );
		
		return false;
	});
	
	
	/*
	*  Add Layout Button
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.acf_flexible_content .acf-popup ul li a').live('click', function(){

		var layout = $(this).attr('data-layout'),
			div = $(this).closest('.acf_flexible_content');
		
		_flex.add_layout( layout, div );
		
		return false;
		
	});
	
	
	/*
	*  Hide Show Flexible Content
	*
	*  @description: 
	*  @since 3.5.2
	*  @created: 11/11/12
	*/
	
	$('.acf_flexible_content .layout .menu-item-handle').live('click', function(){
		
		// vars
		var layout = $(this).closest('.layout');
		
		
		if( layout.attr('data-toggle') == 'closed' )
		{
			layout.attr('data-toggle', 'open');
			layout.children('.acf-input-table').show();
		}
		else
		{
			layout.attr('data-toggle', 'closed');
			layout.children('.acf-input-table').hide();
		}
			
	});
	

})(jQuery);
