(function() {

	// Variable to store tinymce url
	var wpb_mce_url;

	// Create WPBandit shortcodes TinyMCE plugin
	tinymce.create('tinymce.plugins.wpbanditShortcodes', {
		// Initialize plugin
		init: function ( ed, url ) {

			// Set tinymce url
			wpb_mce_url = url;

			// Append wpb-shortcode div
			jQuery('body').append('<div id="wpb-shortcode" style="display:none;"></div>');

			// Add wpbanditPopup command
			ed.addCommand('wpbanditPopup', function(a, params) {
				var shortcode = params.identifier;

				// Load shortcode template into wpb-shortcode div
				jQuery('#wpb-shortcode').load(wpb_mce_url + '/sc-' + shortcode + '.html',
					function() {
						// Load thickbox
						tb_show('Add Shortcode', '#TB_inline?inlineId=wpb-shortcode&width=640');

						// Get Thickbox height
						wpb_tb_height = jQuery('#TB_window').height()-45;

						// Set TB_ajaxContent height
						jQuery('#TB_ajaxContent').css('height', wpb_tb_height+'px');

						// Get selected content
						wpb_content = tinyMCE.activeEditor.selection.getContent();

						// Insert selected content if exists
						if ( wpb_content) {
							jQuery(':input[name=wpb-content]').val(wpb_content);
						}
					}
				);
			});
		},

		createControl : function(n,cm) {
			var a = this;

			switch (n) {
				case 'wpbandit_button':
					// Create menu button
					var btn = cm.createSplitButton('wpbandit_button', {
						title : 'WPBandit Shortcodes',
						image : wpb_mce_url + '/tinymce.bootstrap.png',
						icons: false
					});

					btn.onRenderMenu.add(function(c,m) {
						// Submenu item variable
						var sub;

						// Add shortcode button menu items
						a.addWithPopup(m, 'Button', 'button');
						a.addWithPopup(m, 'Columns', 'columns');
						a.addImmediate(m, 'Divider', '[hr]<br/>');
						m.addSeparator();
						sub = m.addMenu({ title : 'Typography' });
							a.addWithContent (sub, 'Dropcap', 'dropcap');
							a.addWithContent (sub, 'Highlight', 'highlight');
							a.addWithPopup (sub, 'Pull Quote', 'pullquote');
						sub = m.addMenu({ title : 'List Styles' });
							a.addImmediate (sub, 'Arrow List', '[list type="arrow"]<br/>[li]List Item[/li]<br/>[/list]');
							a.addImmediate (sub, 'Check List', '[list type="check"]<br/>[li]List Item[/li]<br/>[/list]');
							a.addImmediate (sub, 'Plus List', '[list type="plus"]<br/>[li]List Item[/li]<br/>[/list]');
							a.addImmediate (sub, 'Minus List', '[list type="minus"]<br/>[li]List Item[/li]<br/>[/list]');
							a.addImmediate (sub, 'Cross List', '[list type="cross"]<br/>[li]List Item[/li]<br/>[/list]');
						m.addSeparator();
						a.addWithPopup(m, 'Accordion', 'accordion');
						a.addWithPopup(m, 'Alert Box', 'alert');
						a.addWithPopup(m, 'Tabs', 'tabs');
						a.addWithPopup(m, 'Toggle', 'toggle');
						m.addSeparator();
						a.addWithPopup(m, 'Google Maps', 'google-maps');
					});

					// Return button instance
					return btn;
			} // end switch statement

			return null;
		},

		// Add shortcode with options via popup
		addWithPopup: function(ed, title, id) {
			ed.add({
				title: title,
				onclick: function() {
					tinyMCE.activeEditor.execCommand('wpbanditPopup', false, {
						title: title,
						identifier: id
					})
				}
			})
		},

		// Add shortcode with content
		addWithContent: function(ed, title, sc) {
			 ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand('mceInsertContent', false,
						'['+sc+']'+tinyMCE.activeEditor.selection.getContent()+'[/'+sc+']'
					)
				}
			})
		},

		// Immediately add shortcode
		addImmediate: function(ed, title, sc) {
			ed.add({
				title: title,
				onclick: function () {
					tinyMCE.activeEditor.execCommand('mceInsertContent', false, sc)
				}
			})
		},

		// TinyMCE plugin info
		getInfo: function () {
			return {
				longname: 'WPBandit Shortcodes',
				author: 'WPBandit',
				authorurl: 'http://wpbandit.com',
				infourl: 'http://wpbandit.com',
				version: '1.0'
			}
		}

	});

	// Register wpbanditShortcodes plugin
	tinymce.PluginManager.add('wpbanditShortcodes', tinymce.plugins.wpbanditShortcodes);

})();
