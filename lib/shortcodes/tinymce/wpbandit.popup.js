jQuery(document).ready(function($) {

	/* Close popup on cancel button click
	/*-----------------------------------------------------------------------*/
	$(document).on('click', '.wpb-cancel-btn', function(e) {
		// Prevent default click action
		e.preventDefault();
		// Remove thickbox
		tb_remove();
	});

	/* Add table row on click
	/*-----------------------------------------------------------------------*/
	$(document).on('click', '.wpb-addrow-btn', function(e) {
		// Prevent default click action
		e.preventDefault();

		// Variable to decide if we need to store row
		wpb_store_row = false;

		// Get shortcode
		shortcode = $('input[name=wpb-shortcode]').val();

		// Determine if we need to store row
		if ( typeof wpb_row == 'undefined' ) {
			wpb_store_row = true;
		} else if ( wpb_sc != shortcode ) {
			wpb_store_row = true;
		}

		// Get row ?
		if ( wpb_store_row ) {
			// Store shortcode
			wpb_sc = shortcode;
			// Store row
			wpb_row = $('.wpb-section').html();
			wpb_row = '<div class="wpb-section">'+wpb_row+'</div>';
		}

		// Limit rows if necessary
		if ( $('input[name=wpb-limit]').length ) {
			// Get limit
			wpb_limit = $('input[name=wpb-limit]').val();
			wpb_count = $('.wpb-section').length;

			// Are we over limit ?
			if ( wpb_limit == wpb_count ) {
				alert('Cannot add more than ' + wpb_limit +' items.');
				return;
			}
		}

		// Add row
		$(wpb_row).insertBefore('.wpb-addrow');
	});


	/* Build standard shortcode on popup submit
	/*-----------------------------------------------------------------------*/
	$(document).on('submit', '#wpb-popup-form', function(e) {
		// Prevent default form action
		e.preventDefault();

		// Get shortcode
		wpb_sc = $('input[name=wpb-shortcode]').val();

		// Get fields
		wpb_fields = $('#wpb-popup-form .wpb-field');

		// Create shortcode
		shortcode = wpb_create_shortcode(wpb_sc,wpb_fields);

		// Insert shortcode into editor
		wpb_insert_shortcode(shortcode);
	});


	/* Build multi shortcode on popup submit
	/*-----------------------------------------------------------------------*/
	$(document).on('submit', '#wpb-popup-form-multi', function(e) {
		// Prevent default form action
		e.preventDefault();

		// Get shortcode container, shortcode
		wpb_sc_container = $('input[name=wpb-shortcode-container]').val();
		wpb_sc = $('input[name=wpb-shortcode]').val();

		// Begin shortcode container
		if ( 'undefined' != typeof wpb_sc_container) {
			var shortcode = '['+wpb_sc_container+']'+'<br/>';
		} else {
			shortcode = '';
		}
			
			// Loop through rows
			$('.wpb-section').each(function() {
				// Get fields
				wpb_fields = $(this).find('.wpb-field');

				// Append shortcode
				shortcode += wpb_create_shortcode(wpb_sc,wpb_fields);
				shortcode += '<br/>';
			});

		// End shortcode container
		if ( 'undefined' != typeof wpb_sc_container) {
			shortcode += '[/'+wpb_sc_container+']';
		}

		// Insert shortcode into editor
		wpb_insert_shortcode(shortcode);
	});

	/* Create shortcode
	/*-----------------------------------------------------------------------*/
	function wpb_create_shortcode(wpb_sc,fields) {
		// Shortcode attributes
		var wpb_atts = '';

		// Loop through fields
		fields.each(function() {
			
			// Get field name and value
			var name = $(this).attr('name');
			var value = $(this).val();

			// Remove wpb- prefix from field name
			name = name.replace('wpb-','');

			// Append attribute
			if ( ('content' != name) && ('-false' != value) ) {
				wpb_atts += ' ' + name + '="' + value + '"';
			}

			// Set content
			if ( 'content' == name) {
				wpb_content = value ? value : '';
			}
		});

		// Build shortcode
		shortcode = '['+wpb_sc+wpb_atts+']'+wpb_content+'[/'+wpb_sc+']';

		// Return shortcode
		return shortcode;
	}


	/* Insert shortcode into editor
	/*-----------------------------------------------------------------------*/
	function wpb_insert_shortcode(shortcode) {
		// Insert shortcode into editor
		tinyMCE.activeEditor.execCommand('mceInsertContent', false, shortcode)

		// Remove thickbox
		tb_remove();
	}




	/*-----------------------------------------------------------------------*/
	/* COLUMNS : sc-columns.html
	/*-----------------------------------------------------------------------*/

	/* Show columns section on change
	/*-----------------------------------------------------------------------*/
	$(document).on('change', 'select[name=wpb-column-type]', function(e) {
		// Prevent default form action
		e.preventDefault();
		// Hide all sections
		$('.wpb-section-hide').hide();
		// Get section
		section = '#' + $(this).val();
		// Show selected section
		$(section).show();
	});

	/* Add selected class on columns click
	/*-----------------------------------------------------------------------*/
	$(document).on('click', 'a.wpb-grid-btn', function(e) {
		// Prevent default form action
		e.preventDefault();
		// Remove selected class
		$('a.wpb-grid-btn').removeClass('selected');
		// Add selected class
		$(this).addClass('selected');
	});

	/* Build column shortcode on popup submit
	/*-----------------------------------------------------------------------*/
	$(document).on('submit', '#wpb-popup-form-columns', function(e) {
		// Prevent default form action
		e.preventDefault();

		// Shortcode variable
		shortcode = '';

		// Get selected section
		selected = $('.wpb-popup').find('a.wpb-grid-btn.selected');

		// Loop through columns
		selected.children('span').each(function() {
			// Get classes
			classes = $(this).attr('class').replace('grid ','');

			// Build shortcode
			if (classes.indexOf('last') >= 0) {
				// Strip last from classses
				classes = classes.replace(' last', '');
				// Create shortcode
				shortcode += '[column size="' + classes + '" last="true"]' + 
					' content goes here [/column]<br />';
			} else {
				shortcode += '[column size="' + classes + '"]' + 
					' content goes here [/column]<br />';
			}
		});

		// Insert shortcode into editor
		wpb_insert_shortcode(shortcode);
	});

});