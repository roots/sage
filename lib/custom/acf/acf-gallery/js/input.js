/*
*  Gallery
*
*  @description: 
*  @since: 3.5.8
*  @created: 17/01/13
*/

(function($){
	
	var _gallery = acf.fields.gallery,
		_media = acf.media;
	
	
	/*
	*  Add
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_gallery.add = function( image ){
	
	
		// vars
		var gallery = _media.div,
			tpml = gallery.find('.tmpl-thumbnail').html();
		
		
		
		// update vars on tmpl
		$.each(image, function( k, v ){
			var regex = new RegExp('{' + k + '}', 'g');
			tpml = tpml.replace(regex, v);
		});
		
	    
	    // add div
	    gallery.find('.thumbnails > .inner').append( tpml );
		
		
		// update gallery count
		_gallery.update_count( gallery );
		
			 	
	 	// validation
		gallery.closest('.field').removeClass('error');
		
	};
	
	
	/*
	*  Edit
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_gallery.edit_image = function(){
	
	
		// vars
		var div = _media.div,
			id = div.attr('data-id');
		
		
		// show edit attachment
		tb_show( acf.fields.image.text.title_edit , acf.admin_url + 'media.php?attachment_id=' + id + '&action=edit&acf_action=edit_attachment&acf_field=gallery&TB_iframe=1');
		
		
	};
	
	
	/*
	*  Update Image
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_gallery.update_image = function(){
	
	
		// vars
		var div = acf.media.div,
			id = div.attr('data-id'),
			ajax_data = {
				action : 'acf/fields/gallery/get_image',
				id : id,
				nonce : acf.nonce
			};
		
		
		// ajax find new list data
		$.ajax({
			url: ajaxurl,
			type: 'post',
			data : ajax_data,
			cache: false,
			dataType: "json",
			success: function( json ) {
		    	
	
				// validate
				if( !json )
				{
					return false;
				}
				
				
				// update list-item html
				$.each(json, function( k, v ){
					if( div.find('.td-' + k).exists() )
					{
						div.find('.td-' + k).text( v );
					}
				});
	 	
			}
		});
		
		
	};
	
	
	/*
	*  Update Count
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	_gallery.update_count = function( div )
	{
		// vars
		var count = div.find('.thumbnails .thumbnail').length,
			max_count = ( count > 2 ) ? 2 : count,
			span = div.find('.toolbar .count');
		
		
		span.html( span.attr('data-' + max_count).replace('{count}', count) );
		
	}
	
	
	/*
	*  View: Grid
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.acf-gallery .toolbar .view-grid').live('click', function(){
		
		// vars
		var gallery = $(this).closest('.acf-gallery');
		
		
		// active class
		$(this).parent().addClass('active').siblings('.view-list-li').removeClass('active');
		
		
		// gallery class
		gallery.removeClass('view-list');
		
		
		return false;
			
	});
	
	
	/*
	*  View: List
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.acf-gallery .toolbar .view-list').live('click', function(){
		
		// vars
		var gallery = $(this).closest('.acf-gallery');
		
		
		// active class
		$(this).parent().addClass('active').siblings('.view-grid-li').removeClass('active');
		
		
		// gallery class
		gallery.addClass('view-list');
		
		
		return false;
			
	});
	
	
	/*
	*  Add Button
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	// add image
	$('.acf-gallery .toolbar .add-image').live('click', function(){
		
		// vars
		var div = _media.div = $(this).closest('.acf-gallery'),
			preview_size = div.attr('data-preview_size');
		

		// show the thickbox
		// show the thickbox
		if( _media.type() == 'backbone' )
		{
			// clear the frame
			_media.clear_frame();
			
			
			// acf.media.frame.content.get().options.filters=true;
			
		    // Create the media frame. Leave options blank for defaults
			_media.frame = wp.media({
				title : _gallery.text.title_add,
				multiple : true,
				library : {
					//type : 'image'
				}
			});
			
			
			// add filter by overriding the option when the title is being created. This is an evet fired before the rendering / creating of the library content so it works but is a bit of a hack. In the future, this should be changed to an init / options event
			_media.frame.on('title:create', function(){
				var state = _media.frame.state();
				state.set('filterable', 'uploaded');
			});
			
			
			// When an image is selected, run a callback.
			_media.frame.on( 'select', function() {
				
				// get selected images
				selection = _media.frame.state().get('selection');
				
				if( selection )
				{
					selection.each(function(attachment){
						
						// is image already in gallery?
						if( div.find('.thumbnails .thumbnail[data-id="' + attachment.id + '"]').exists() )
						{
							return;
						}
						
						
				    	var image = {
						    id : attachment.id,
						    src : attachment.attributes.url,
						    title : attachment.attributes.title,
						    caption : attachment.attributes.caption,
						    alt : attachment.attributes.alt,
						    description : attachment.attributes.description
					    };
				    	
					    
					    // file?
					    if( attachment.attributes.type != 'image' )
					    {
						    image.src = attachment.attributes.icon;
					    }
					    
					    
				    	// is preview size available?
				    	if( attachment.attributes.sizes && attachment.attributes.sizes[ preview_size ] )
				    	{
					    	image.src = attachment.attributes.sizes[ preview_size ].url;
				    	}
				    	
				    	
				    	// add image to field
				        _gallery.add( image );
				        
				    });
				    // selection.each(function(attachment){
				}
				// if( selection )
			});
			// _media.frame.on( 'select', function() {
				
			_media.frame.on('content:create:browse', function( e ){
				
				/*
acf.media.frame.content.get().collection.on( 'all', function( e ){
				    console.log( 'collection: ' + e );
			    });
*/
			    
			});
			
			// log all events
			_media.frame.on('all', function( e ){
				
				//console.log( 'frame: ' + e );
			});
			
			_media.frame.on('content:activate:browse', function(){
				
				_gallery.hide_selected_items();
				 
				acf.media.frame.content.get().collection.on( 'reset add', function(){
				    
				    _gallery.hide_selected_items();
				    
			    });
				
				// event fired after a search
				
				/*
acf.media.frame.content.get().collection.on( 'all', function( e ){
				    console.log( 'collection: ' + e );
			    });
*/

			 
			 
			}); 
				
			// Finally, open the modal
			_media.frame.open();
			
			
			/* // event fired when viewing "Media Library"
			
			
			
			
			
			// event fired when viewing "Upload Files"
			acf.media.frame.on('content:activate:upload', function(){
				$(document).trigger('acf/media/upload');
			});
	
			// event fired when opening the media popup
			acf.media.frame.on('open', function( e ){
				
				acf.media.frame.content.get().attachments.render();
				acf.media.frame.reset();
				$(document).trigger('acf/media/open');
				
				
		    });
		    
		    acf.media.frame.on('reset', function( e ){
				$(document).trigger('acf/media/reset');
				
				console.log('main reset');
		    });
		    
		    
		    // event fired when closing the media popup
			acf.media.frame.on('close', function( e ){
				
				// clear the div
				$(document).trigger('acf/media/close');
		    });
		    
		    
			// When an image is selected, run a callback.
		    acf.media.frame.on('select', function()
		    {
		    	$(document).trigger('acf/media/select', [acf.media.frame.state().get('selection')]);
		    }); */
		}
		else
		{
			tb_show( _gallery.text.title_add , acf.admin_url + 'media-upload.php?post_id=' + acf.post_id + '&post_ID=' + acf.post_id + '&type=image&acf_type=gallery&acf_preview_size=' + preview_size + 'TB_iframe=1');
		}
		
	
		return false;
					
	});
	
	
	/*
	*  Edit Button
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.acf-gallery .thumbnail .acf-button-edit').live('click', function(){
		
		// vars
		_media.div = $(this).closest('.thumbnail');
		
		_gallery.edit_image();
		
		return false;
			
	});
	
	
	/*
	*  Remove Button
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$('.acf-gallery .thumbnail .acf-button-delete').live('click', function(){
		
		// vars
		var thumbnail = $(this).closest('.thumbnail'),
			gallery = thumbnail.closest('.acf-gallery');
		
		
		thumbnail.animate({
			opacity : 0
		}, 250, function(){
			
			thumbnail.remove();
			
			_gallery.update_count( gallery );
			
		});
		
		return false;
			
	});
	
	
	
	_gallery.hide_selected_items = function(){
		
		// set timeout for 0, then it will always run last after the add event
		setTimeout(function(){
		
		
		// vars
		var gallery = _media.div,
			div = _media.frame.content.get().$el;
			collection = _media.frame.content.get().collection || null,
			i = -1;
			
		
		if( collection )
		{
			collection.each(function( item ){
			
				i++;
				
				var attachment = div.find('.attachments > .attachment:eq(' + i + ')');
				
				
				// if image is already inside the gallery, disable it!
				if( gallery.find('.thumbnails .thumbnail[data-id="' + item.id + '"]').exists() )
				{
					item.off('selection:single');
					attachment.addClass('acf-selected');
				}
				
			});
		}
		
		
		}, 0);
	
	}
		
	
	/*
	*  acf/setup_fields
	*
	*  @description: 
	*  @since: 3.5.8
	*  @created: 17/01/13
	*/
	
	$(document).live('acf/setup_fields', function(e, postbox){
		
		$(postbox).find('.acf-gallery').each(function(i){
			
			// is clone field?
			if( acf.helpers.is_clone_field($(this).children('input[type="hidden"]')) )
			{
				return;
			}
			
			
			// vars
			var div = $(this),
				thumbnails = div.find('.thumbnails');
				
			
			// update count
			_gallery.update_count( div );

			
			// sortable
			thumbnails.find('> .inner').sortable({
				items : '> .thumbnail',
				/* handle: '> td.order', */
				forceHelperSize: true,
				forcePlaceholderSize: true,
				scroll: true,
				start: function (event, ui) {
				
					// alter width / height to allow for 2px border
					ui.placeholder.width( ui.placeholder.width() - 4 );
					ui.placeholder.height( ui.placeholder.height() - 4 );
	   			}
			});

			
		});
	
	});

})(jQuery);
