// AJAX Functions
var jq = jQuery;

// Global variable to prevent multiple AJAX requests
var bp_ajax_request = null;

jq(document).ready( function() {
	/**** Page Load Actions *******************************************************/

	/* Hide Forums Post Form */
	if ( '-1' == window.location.search.indexOf('new') && jq('div.forums').length )
		jq('#new-topic-post').hide();
	else
		jq('#new-topic-post').show();

	/* Activity filter and scope set */
	bp_init_activity();

	/* Object filter and scope set. */
	var objects = [ 'members', 'groups', 'blogs', 'forums' ];
	bp_init_objects( objects );

	/* @mention Compose Scrolling */
	var $whats_new = jq('#whats-new');
	if ( jq.query.get('r') && $whats_new.length ) {
		jq('#whats-new-options').animate({
			height:'40px'
		});
		jq("#whats-new-form textarea").animate({
			height:'50px'
		});
		jq.scrollTo( $whats_new, 500, {
			offset:-125,
			easing:'easeOutQuad'
		} );
		var whats_new_content = $whats_new.val();
		$whats_new.val('').focus().val(whats_new_content);
	}

	/**** Activity Posting ********************************************************/

	/* Textarea focus */
	$whats_new.focus( function(){
		jq("#whats-new-options").animate({
			height:'40px'
		});
		jq("#whats-new-form textarea").animate({
			height:'50px'
		});
		jq("#aw-whats-new-submit").prop("disabled", false);
		
		var $whats_new_form = jq("form#whats-new-form");
		if ( $whats_new_form.hasClass("submitted") ) {
			$whats_new_form.removeClass("submitted");	
		}
	});

	/* On blur, shrink if it's empty */
	$whats_new.blur( function(){
		if (!this.value.match(/\S+/)) {
			this.value = "";
			jq("#whats-new-options").animate({
				height:'40px'
			});
			jq("form#whats-new-form textarea").animate({
				height:'20px'
			});
			jq("#aw-whats-new-submit").prop("disabled", true);
		}
	});

	/* New posts */
	jq("#aw-whats-new-submit").on( 'click', function() {
		var button = jq(this);
		var form = button.closest("form#whats-new-form");

		form.children().each( function() {
			if ( jq.nodeName(this, "textarea") || jq.nodeName(this, "input") )
				jq(this).prop( 'disabled', true );
		});

		/* Remove any errors */
		jq('div.error').remove();
		button.addClass('loading');
		button.prop('disabled', true);
		form.addClass("submitted");

		/* Default POST values */
		var object = '';
		var item_id = jq("#whats-new-post-in").val();
		var content = jq("#whats-new").val();

		/* Set object for non-profile posts */
		if ( item_id > 0 ) {
			object = jq("#whats-new-post-object").val();
		}

		jq.post( ajaxurl, {
			action: 'post_update',
			'cookie': bp_get_cookies(),
			'_wpnonce_post_update': jq("#_wpnonce_post_update").val(),
			'content': content,
			'object': object,
			'item_id': item_id,
			'_bp_as_nonce': jq('#_bp_as_nonce').val() || ''
		},
		function(response) {

			form.children().each( function() {
				if ( jq.nodeName(this, "textarea") || jq.nodeName(this, "input") ) {
					jq(this).prop( 'disabled', false );
				}
			});

			/* Check for errors and append if found. */
			if ( response[0] + response[1] == '-1' ) {
				form.prepend( response.substr( 2, response.length ) );
				jq( '#' + form.attr('id') + ' div.error').hide().fadeIn( 200 );
			} else {
				if ( 0 == jq("ul.activity-list").length ) {
					jq("div.error").slideUp(100).remove();
					jq("#message").slideUp(100).remove();
					jq("div.activity").append( '<ul id="activity-stream" class="activity-list item-list">' );
				}

				jq("#activity-stream").prepend(response);
				jq("#activity-stream li:first").addClass('new-update just-posted');

				if ( 0 != jq("#latest-update").length ) {
					var l = jq("#activity-stream li.new-update .activity-content .activity-inner p").html();
					var v = jq("#activity-stream li.new-update .activity-content .activity-header p a.view").attr('href');

					var ltext = jq("#activity-stream li.new-update .activity-content .activity-inner p").text();

					var u = '';
					if ( ltext != '' )
						u = l + ' ';

					u += '<a href="' + v + '" rel="nofollow">' + BP_DTheme.view + '</a>';

					jq("#latest-update").slideUp(300,function(){
						jq("#latest-update").html( u );
						jq("#latest-update").slideDown(300);
					});
				}

				jq("li.new-update").hide().slideDown( 300 );
				jq("li.new-update").removeClass( 'new-update' );
				jq("#whats-new").val('');
			}

			jq("#whats-new-options").animate({
				height:'0px'
			});
			jq("#whats-new-form textarea").animate({
				height:'20px'
			});
			jq("#aw-whats-new-submit").prop("disabled", true).removeClass('loading');
		});

		return false;
	});

	/* List tabs event delegation */
	jq('div.activity-type-tabs').on( 'click', function(event) {
		var target = jq(event.target).parent();

		if ( event.target.nodeName == 'STRONG' || event.target.nodeName == 'SPAN' )
			target = target.parent();
		else if ( event.target.nodeName != 'A' )
			return false;

		/* Reset the page */
		jq.cookie( 'bp-activity-oldestpage', 1, {
			path: '/'
		} );

		/* Activity Stream Tabs */
		var scope = target.attr('id').substr( 9, target.attr('id').length );
		var filter = jq("#activity-filter-select select").val();

		if ( scope == 'mentions' )
			jq( '#' + target.attr('id') + ' a strong' ).remove();

		bp_activity_request(scope, filter);

		return false;
	});

	/* Activity filter select */
	jq('#activity-filter-select select').change( function() {
		var selected_tab = jq( 'div.activity-type-tabs li.selected' );

		if ( !selected_tab.length )
			var scope = null;
		else
			var scope = selected_tab.attr('id').substr( 9, selected_tab.attr('id').length );

		var filter = jq(this).val();

		bp_activity_request(scope, filter);

		return false;
	});

	/* Stream event delegation */
	jq('div.activity').on( 'click', function(event) {
		var target = jq(event.target);

		/* Favoriting activity stream items */
		if ( target.hasClass('fav') || target.hasClass('unfav') ) {
			var type = target.hasClass('fav') ? 'fav' : 'unfav';
			var parent = target.closest('.activity-item');
			var parent_id = parent.attr('id').substr( 9, parent.attr('id').length );

			target.addClass('loading');

			jq.post( ajaxurl, {
				action: 'activity_mark_' + type,
				'cookie': bp_get_cookies(),
				'id': parent_id
			},
			function(response) {
				target.removeClass('loading');

				target.fadeOut( 200, function() {
					jq(this).html(response);
					jq(this).attr('title', 'fav' == type ? BP_DTheme.remove_fav : BP_DTheme.mark_as_fav);
					jq(this).fadeIn(200);
				});

				if ( 'fav' == type ) {
					if ( !jq('.item-list-tabs #activity-favs-personal-li').length ) {
						if ( !jq('.item-list-tabs #activity-favorites').length )
							jq('.item-list-tabs ul #activity-mentions').before( '<li id="activity-favorites"><a href="#">' + BP_DTheme.my_favs + ' <span>0</span></a></li>');

						jq('.item-list-tabs ul #activity-favorites span').html( Number( jq('.item-list-tabs ul #activity-favorites span').html() ) + 1 );
					}

					target.removeClass('fav');
					target.addClass('unfav');

				} else {
					target.removeClass('unfav');
					target.addClass('fav');

					jq('.item-list-tabs ul #activity-favorites span').html( Number( jq('.item-list-tabs ul #activity-favorites span').html() ) - 1 );

					if ( !Number( jq('.item-list-tabs ul #activity-favorites span').html() ) ) {
						if ( jq('.item-list-tabs ul #activity-favorites').hasClass('selected') )
							bp_activity_request( null, null );

						jq('.item-list-tabs ul #activity-favorites').remove();
					}
				}

				if ( 'activity-favorites' == jq( '.item-list-tabs li.selected').attr('id') )
					target.parent().parent().parent().slideUp(100);
			});

			return false;
		}

		/* Delete activity stream items */
		if ( target.hasClass('delete-activity') ) {
			var li        = target.parents('div.activity ul li');
			var id        = li.attr('id').substr( 9, li.attr('id').length );
			var link_href = target.attr('href');
			var nonce     = link_href.split('_wpnonce=');

			nonce = nonce[1];

			target.addClass('loading');

			jq.post( ajaxurl, {
				action: 'delete_activity',
				'cookie': bp_get_cookies(),
				'id': id,
				'_wpnonce': nonce
			},
			function(response) {

				if ( response[0] + response[1] == '-1' ) {
					li.prepend( response.substr( 2, response.length ) );
					li.children('#message').hide().fadeIn(300);
				} else {
					li.slideUp(300);
				}
			});

			return false;
		}

		// Spam activity stream items
		if ( target.hasClass( 'spam-activity' ) ) {
			var li = target.parents( 'div.activity ul li' );
			target.addClass( 'loading' );

			jq.post( ajaxurl, {
				action: 'bp_spam_activity',
				'cookie': encodeURIComponent( document.cookie ),
				'id': li.attr( 'id' ).substr( 9, li.attr( 'id' ).length ),
				'_wpnonce': target.attr( 'href' ).split( '_wpnonce=' )[1]
			},

			function(response) {
				if ( response[0] + response[1] === '-1' ) {
					li.prepend( response.substr( 2, response.length ) );
					li.children( '#message' ).hide().fadeIn(300);
				} else {
					li.slideUp( 300 );
				}
			});

			return false;
		}

		/* Load more updates at the end of the page */
		if ( target.parent().hasClass('load-more') ) {
			jq("#buddypress li.load-more").addClass('loading');

			if ( null == jq.cookie('bp-activity-oldestpage') )
				jq.cookie('bp-activity-oldestpage', 1, {
					path: '/'
				} );

			var oldest_page = ( jq.cookie('bp-activity-oldestpage') * 1 ) + 1;

			var just_posted = [];
			
			jq('.activity-list li.just-posted').each( function(){
				just_posted.push( jq(this).attr('id').replace( 'activity-','' ) );
			});

			jq.post( ajaxurl, {
				action: 'activity_get_older_updates',
				'cookie': bp_get_cookies(),
				'page': oldest_page,
				'exclude_just_posted': just_posted.join(',')
			},
			function(response)
			{
				jq("#buddypress li.load-more").removeClass('loading');
				jq.cookie( 'bp-activity-oldestpage', oldest_page, {
					path: '/'
				} );
				jq("#buddypress ul.activity-list").append(response.contents);

				target.parent().hide();
			}, 'json' );

			return false;
		}
	});

	// Activity "Read More" links
	jq('div.activity').on('click', '.activity-read-more a', function(event) {
		var target = jq(event.target);
		var link_id = target.parent().attr('id').split('-');
		var a_id = link_id[3];
		var type = link_id[0]; /* activity or acomment */

		var inner_class = type == 'acomment' ? 'acomment-content' : 'activity-inner';
		var a_inner = jq('#' + type + '-' + a_id + ' .' + inner_class + ':first' );
		jq(target).addClass('loading');

		jq.post( ajaxurl, {
			action: 'get_single_activity_content',
			'activity_id': a_id
		},
		function(response) {
			jq(a_inner).slideUp(300).html(response).slideDown(300);
		});

		return false;
	});

	/**** Activity Comments *******************************************************/

	/* Hide all activity comment forms */
	jq('form.ac-form').hide();

	/* Hide excess comments */
	if ( jq('.activity-comments').length )
		bp_legacy_theme_hide_comments();

	/* Activity list event delegation */
	jq('div.activity').on( 'click', function(event) {
		var target = jq(event.target);

		/* Comment / comment reply links */
		if ( target.hasClass('acomment-reply') || target.parent().hasClass('acomment-reply') ) {
			if ( target.parent().hasClass('acomment-reply') )
				target = target.parent();

			var id = target.attr('id');
			ids = id.split('-');

			var a_id = ids[2]
			var c_id = target.attr('href').substr( 10, target.attr('href').length );
			var form = jq( '#ac-form-' + a_id );

			form.css( 'display', 'none' );
			form.removeClass('root');
			jq('.ac-form').hide();

			/* Hide any error messages */
			form.children('div').each( function() {
				if ( jq(this).hasClass( 'error' ) )
					jq(this).hide();
			});

			if ( ids[1] != 'comment' ) {
				jq('#acomment-' + c_id).append( form );
			} else {
				jq('#activity-' + a_id + ' .activity-comments').append( form );
			}

			if ( form.parent().hasClass( 'activity-comments' ) )
				form.addClass('root');

			form.slideDown( 200 );
			jq.scrollTo( form, 500, {
				offset:-100,
				easing:'easeOutQuad'
			} );
			jq('#ac-form-' + ids[2] + ' textarea').focus();

			return false;
		}

		/* Activity comment posting */
		if ( target.attr('name') == 'ac_form_submit' ) {
			var form = target.parents( 'form' );
			var form_parent = form.parent();
			var form_id = form.attr('id').split('-');

			if ( !form_parent.hasClass('activity-comments') ) {
				var tmp_id = form_parent.attr('id').split('-');
				var comment_id = tmp_id[1];
			} else {
				var comment_id = form_id[2];
			}

			var content = jq( '#' + form.attr('id') + ' textarea' );

			/* Hide any error messages */
			jq( '#' + form.attr('id') + ' div.error').hide();
			target.addClass('loading').prop('disabled', true);
			content.addClass('loading').prop('disabled', true);

			var ajaxdata = {
				action: 'new_activity_comment',
				'cookie': bp_get_cookies(),
				'_wpnonce_new_activity_comment': jq("#_wpnonce_new_activity_comment").val(),
				'comment_id': comment_id,
				'form_id': form_id[2],
				'content': content.val()
			};

			// Akismet
			var ak_nonce = jq('#_bp_as_nonce_' + comment_id).val();
			if ( ak_nonce ) {
				ajaxdata['_bp_as_nonce_' + comment_id] = ak_nonce;
			}

			jq.post( ajaxurl, ajaxdata, function(response) {
				target.removeClass('loading');
				content.removeClass('loading');

				/* Check for errors and append if found. */
				if ( response[0] + response[1] == '-1' ) {
					form.append( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
				} else {
					var activity_comments = form.parent();
					form.fadeOut( 200, function() {
						if ( 0 == activity_comments.children('ul').length ) {
							if ( activity_comments.hasClass('activity-comments') ) {
								activity_comments.prepend('<ul></ul>');
							} else {
								activity_comments.append('<ul></ul>');
							}
						}

						/* Preceeding whitespace breaks output with jQuery 1.9.0 */
						var the_comment = jq.trim( response );

						activity_comments.children('ul').append( jq( the_comment ).hide().fadeIn( 200 ) );
						form.children('textarea').val('');
						activity_comments.parent().addClass('has-comments');
					} );
					jq( '#' + form.attr('id') + ' textarea').val('');

					/* Increase the "Reply (X)" button count */
					jq('#activity-' + form_id[2] + ' a.acomment-reply span').html( Number( jq('#activity-' + form_id[2] + ' a.acomment-reply span').html() ) + 1 );

					// Increment the 'Show all x comments' string, if present
					var show_all_a = activity_comments.find('.show-all').find('a');
					if ( show_all_a ) {
						var new_count = jq('li#activity-' + form_id[2] + ' a.acomment-reply span').html();
						show_all_a.html( BP_DTheme.show_x_comments.replace( '%d', new_count ) );
					}
				}

				jq(target).prop("disabled", false);
				jq(content).prop("disabled", false);
			});

			return false;
		}

		/* Deleting an activity comment */
		if ( target.hasClass('acomment-delete') ) {
			var link_href = target.attr('href');
			var comment_li = target.parent().parent();
			var form = comment_li.parents('div.activity-comments').children('form');

			var nonce = link_href.split('_wpnonce=');
			nonce = nonce[1];

			var comment_id = link_href.split('cid=');
			comment_id = comment_id[1].split('&');
			comment_id = comment_id[0];

			target.addClass('loading');

			/* Remove any error messages */
			jq('.activity-comments ul .error').remove();

			/* Reset the form position */
			comment_li.parents('.activity-comments').append(form);

			jq.post( ajaxurl, {
				action: 'delete_activity_comment',
				'cookie': bp_get_cookies(),
				'_wpnonce': nonce,
				'id': comment_id
			},
			function(response) {
				/* Check for errors and append if found. */
				if ( response[0] + response[1] == '-1' ) {
					comment_li.prepend( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
				} else {
					var children = jq( '#' + comment_li.attr('id') + ' ul' ).children('li');
					var child_count = 0;
					jq(children).each( function() {
						if ( !jq(this).is(':hidden') )
							child_count++;
					});
					comment_li.fadeOut(200, function() {
						comment_li.remove();
					});

					/* Decrease the "Reply (X)" button count */
					var count_span = jq('#' + comment_li.parents('#activity-stream > li').attr('id') + ' a.acomment-reply span');
					var new_count = count_span.html() - ( 1 + child_count );
					count_span.html(new_count);
					
					// Change the 'Show all x comments' text
					var show_all_a = comment_li.siblings('.show-all').find('a');
					if ( show_all_a ) {
						show_all_a.html( BP_DTheme.show_x_comments.replace( '%d', new_count ) );
					}

					/* If that was the last comment for the item, remove the has-comments class to clean up the styling */
					if ( 0 == new_count ) {
						jq(comment_li.parents('#activity-stream > li')).removeClass('has-comments');
					}
				}
			});

			return false;
		}

		// Spam an activity stream comment
		if ( target.hasClass( 'spam-activity-comment' ) ) {
			var link_href  = target.attr( 'href' );
			var comment_li = target.parent().parent();

			target.addClass('loading');

			// Remove any error messages
			jq( '.activity-comments ul div.error' ).remove();

			// Reset the form position
			comment_li.parents( '.activity-comments' ).append( comment_li.parents( '.activity-comments' ).children( 'form' ) );

			jq.post( ajaxurl, {
				action: 'bp_spam_activity_comment',
				'cookie': encodeURIComponent( document.cookie ),
				'_wpnonce': link_href.split( '_wpnonce=' )[1],
				'id': link_href.split( 'cid=' )[1].split( '&' )[0]
			},

			function ( response ) {
				// Check for errors and append if found.
				if ( response[0] + response[1] == '-1' ) {
					comment_li.prepend( jq( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );

				} else {
					var children = jq( '#' + comment_li.attr( 'id' ) + ' ul' ).children( 'li' );
					var child_count = 0;
					jq(children).each( function() {
						if ( !jq( this ).is( ':hidden' ) ) {
							child_count++;
						}
					});
					comment_li.fadeOut( 200 );

					// Decrease the "Reply (X)" button count
					var parent_li = comment_li.parents( '#activity-stream > li' );
					jq( '#' + parent_li.attr( 'id' ) + ' a.acomment-reply span' ).html( jq( '#' + parent_li.attr( 'id' ) + ' a.acomment-reply span' ).html() - ( 1 + child_count ) );
				}
			});

			return false;
		}

		/* Showing hidden comments - pause for half a second */
		if ( target.parent().hasClass('show-all') ) {
			target.parent().addClass('loading');

			setTimeout( function() {
				target.parent().parent().children('li').fadeIn(200, function() {
					target.parent().remove();
				});
			}, 600 );

			return false;
		}

		// Canceling an activity comment	
		if ( target.hasClass( 'ac-reply-cancel' ) ) {
			jq(target).closest('.ac-form').slideUp( 200 );
			return false;
		};
	});

	/* Escape Key Press for cancelling comment forms */
	jq(document).keydown( function(e) {
		e = e || window.event;
		if (e.target)
			element = e.target;
		else if (e.srcElement)
			element = e.srcElement;

		if( element.nodeType == 3)
			element = element.parentNode;

		if( e.ctrlKey == true || e.altKey == true || e.metaKey == true )
			return;

		var keyCode = (e.keyCode) ? e.keyCode : e.which;

		if ( keyCode == 27 ) {
			if (element.tagName == 'TEXTAREA') {
				if ( jq(element).hasClass('ac-input') )
					jq(element).parent().parent().parent().slideUp( 200 );
			}
		}
	});

	/**** Directory Search ****************************************************/

	/* The search form on all directory pages */
	jq('.dir-search').on( 'click', function(event) {
		if ( jq(this).hasClass('no-ajax') )
			return;

		var target = jq(event.target);

		if ( target.attr('type') == 'submit' ) {
			var css_id = jq('.item-list-tabs li.selected').attr('id').split( '-' );
			var object = css_id[0];

			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope') , 'div.' + object, target.parent().children('label').children('input').val(), 1, jq.cookie('bp-' + object + '-extras') );

			return false;
		}
	});

	/**** Tabs and Filters ****************************************************/

	/* When a navigation tab is clicked - e.g. | All Groups | My Groups | */
	jq('div.item-list-tabs').on( 'click', function(event) {
		if ( jq(this).hasClass('no-ajax') )
			return;

		var targetElem = ( event.target.nodeName == 'SPAN' ) ? event.target.parentNode : event.target;
		var target     = jq( targetElem ).parent();
		if ( 'LI' == target[0].nodeName && !target.hasClass( 'last' ) ) {
			var css_id = target.attr('id').split( '-' );
			var object = css_id[0];

			if ( 'activity' == object )
				return false;

			var scope = css_id[1];
			var filter = jq("#" + object + "-order-select select").val();
			var search_terms = jq("#" + object + "_search").val();

			bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );

			return false;
		}
	});

	/* When the filter select box is changed re-query */
	jq('li.filter select').change( function() {
		if ( jq('.item-list-tabs li.selected').length )
			var el = jq('.item-list-tabs li.selected');
		else
			var el = jq(this);

		var css_id = el.attr('id').split('-');
		var object = css_id[0];
		var scope = css_id[1];
		var filter = jq(this).val();
		var search_terms = false;

		if ( jq('.dir-search input').length )
			search_terms = jq('.dir-search input').val();

		if ( 'friends' == object )
			object = 'members';

		bp_filter_request( object, filter, scope, 'div.' + object, search_terms, 1, jq.cookie('bp-' + object + '-extras') );

		return false;
	});

	/* All pagination links run through this function */
	jq('#buddypress').on( 'click', function(event) {
		var target = jq(event.target);

		if ( target.hasClass('button') )
			return true;

		if ( target.parent().parent().hasClass('pagination') && !target.parent().parent().hasClass('no-ajax') ) {
			if ( target.hasClass('dots') || target.hasClass('current') )
				return false;

			if ( jq('.item-list-tabs li.selected').length )
				var el = jq('.item-list-tabs li.selected');
			else
				var el = jq('li.filter select');

			var page_number = 1;
			var css_id = el.attr('id').split( '-' );
			var object = css_id[0];
			var search_terms = false;
			var pagination_id = jq(target).closest('.pagination-links').attr('id');

			if ( jq('div.dir-search input').length )
				search_terms = jq('.dir-search input').val();

			if ( jq(target).hasClass('next') )
				var page_number = Number( jq('.pagination span.current').html() ) + 1;
			else if ( jq(target).hasClass('prev') )
				var page_number = Number( jq('.pagination span.current').html() ) - 1;
			else
				var page_number = Number( jq(target).html() );
			
			if ( pagination_id.indexOf( 'pag-bottom' ) !== -1 ) {
				var caller = 'pag-bottom';
			} else {
				var caller = null;
			}

			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope'), 'div.' + object, search_terms, page_number, jq.cookie('bp-' + object + '-extras'), caller );

			return false;
		}

	});

	/**** New Forum Directory Post **************************************/

	/* Hit the "New Topic" button on the forums directory page */
	jq('a.show-hide-new').on( 'click', function() {
		if ( !jq('#new-topic-post').length )
			return false;

		if ( jq('#new-topic-post').is(":visible") )
			jq('#new-topic-post').slideUp(200);
		else
			jq('#new-topic-post').slideDown(200, function() {
				jq('#topic_title').focus();
			} );

		return false;
	});

	/* Cancel the posting of a new forum topic */
	jq('#submit_topic_cancel').on( 'click', function() {
		if ( !jq('#new-topic-post').length )
			return false;

		jq('#new-topic-post').slideUp(200);
		return false;
	});

	/* Clicking a forum tag */
	jq('#forum-directory-tags a').on( 'click', function() {
		bp_filter_request( 'forums', 'tags', jq.cookie('bp-forums-scope'), 'div.forums', jq(this).html().replace( /&nbsp;/g, '-' ), 1, jq.cookie('bp-forums-extras') );
		return false;
	});

	/** Invite Friends Interface ****************************************/

	/* Select a user from the list of friends and add them to the invite list */
	jq("#invite-list input").on( 'click', function() {
		jq('.ajax-loader').toggle();

		var friend_id = jq(this).val();

		if ( jq(this).prop('checked') == true )
			var friend_action = 'invite';
		else
			var friend_action = 'uninvite';

		jq('.item-list-tabs li.selected').addClass('loading');

		jq.post( ajaxurl, {
			action: 'groups_invite_user',
			'friend_action': friend_action,
			'cookie': bp_get_cookies(),
			'_wpnonce': jq("#_wpnonce_invite_uninvite_user").val(),
			'friend_id': friend_id,
			'group_id': jq("#group_id").val()
		},
		function(response)
		{
			if ( jq("#message") )
				jq("#message").hide();

			jq('.ajax-loader').toggle();

			if ( friend_action == 'invite' ) {
				jq('#friend-list').append(response);
			} else if ( friend_action == 'uninvite' ) {
				jq('#friend-list li#uid-' + friend_id).remove();
			}

			jq('.item-list-tabs li.selected').removeClass('loading');
		});
	});

	/* Remove a user from the list of users to invite to a group */
	jq("#friend-list").on('click', 'li a.remove', function() {
		jq('.ajax-loader').toggle();

		var friend_id = jq(this).attr('id');
		friend_id = friend_id.split('-');
		friend_id = friend_id[1];

		jq.post( ajaxurl, {
			action: 'groups_invite_user',
			'friend_action': 'uninvite',
			'cookie': bp_get_cookies(),
			'_wpnonce': jq("#_wpnonce_invite_uninvite_user").val(),
			'friend_id': friend_id,
			'group_id': jq("#group_id").val()
		},
		function(response)
		{
			jq('.ajax-loader').toggle();
			jq('#friend-list #uid-' + friend_id).remove();
			jq('#invite-list #f-' + friend_id).prop('checked', false);
		});

		return false;
	});

	/** Profile Visibility Settings *********************************/
	jq('.field-visibility-settings').hide();
	jq('.visibility-toggle-link').on( 'click', function() {
		var toggle_div = jq(this).parent();

		jq(toggle_div).fadeOut( 600, function(){
			jq(toggle_div).siblings('.field-visibility-settings').slideDown(400);
		});

		return false;
	} );

	jq('.field-visibility-settings-close').on( 'click', function() {
		var settings_div = jq(this).parent();
		var vis_setting_text = settings_div.find('input:checked').parent().text();

		settings_div.slideUp( 400, function() {
			settings_div.siblings('.field-visibility-settings-toggle').fadeIn(800);
			settings_div.siblings('.field-visibility-settings-toggle').children('.current-visibility-level').html(vis_setting_text);
		} );

		return false;
	} );

	jq("#profile-edit-form input:not(:submit), #profile-edit-form textarea, #profile-edit-form select, #signup_form input:not(:submit), #signup_form textarea, #signup_form select").change( function() {
		var shouldconfirm = true;

		jq('#profile-edit-form input:submit, #signup_form input:submit').on( 'click', function() {
			shouldconfirm = false;
		});
		
		window.onbeforeunload = function(e) {
			if ( shouldconfirm ) {
				return BP_DTheme.unsaved_changes;
			}
		};
	});

	/** Friendship Requests **************************************/

	/* Accept and Reject friendship request buttons */
	jq("#friend-list a.accept, #friend-list a.reject").on( 'click', function() {
		var button = jq(this);
		var li = jq(this).parents('#friend-list li');
		var action_div = jq(this).parents('li div.action');

		var id = li.attr('id').substr( 11, li.attr('id').length );
		var link_href = button.attr('href');

		var nonce = link_href.split('_wpnonce=');
		nonce = nonce[1];

		if ( jq(this).hasClass('accepted') || jq(this).hasClass('rejected') )
			return false;

		if ( jq(this).hasClass('accept') ) {
			var action = 'accept_friendship';
			action_div.children('a.reject').css( 'visibility', 'hidden' );
		} else {
			var action = 'reject_friendship';
			action_div.children('a.accept').css( 'visibility', 'hidden' );
		}

		button.addClass('loading');

		jq.post( ajaxurl, {
			action: action,
			'cookie': bp_get_cookies(),
			'id': id,
			'_wpnonce': nonce
		},
		function(response) {
			button.removeClass('loading');

			if ( response[0] + response[1] == '-1' ) {
				li.prepend( response.substr( 2, response.length ) );
				li.children('#message').hide().fadeIn(200);
			} else {
				button.fadeOut( 100, function() {
					if ( jq(this).hasClass('accept') ) {
						action_div.children('a.reject').hide();
						jq(this).html( BP_DTheme.accepted ).contents().unwrap();
					} else {
						action_div.children('a.accept').hide();
						jq(this).html( BP_DTheme.rejected ).contents().unwrap();
					}
				});
			}
		});

		return false;
	});

	/* Add / Remove friendship buttons */
	jq('#members-dir-list').on('click', '.friendship-button a', function() {
		jq(this).parent().addClass('loading');
		var fid = jq(this).attr('id');
		fid = fid.split('-');
		fid = fid[1];

		var nonce = jq(this).attr('href');
		nonce = nonce.split('?_wpnonce=');
		nonce = nonce[1].split('&');
		nonce = nonce[0];

		var thelink = jq(this);

		jq.post( ajaxurl, {
			action: 'addremove_friend',
			'cookie': bp_get_cookies(),
			'fid': fid,
			'_wpnonce': nonce
		},
		function(response)
		{
			var action = thelink.attr('rel');
			var parentdiv = thelink.parent();

			if ( action == 'add' ) {
				jq(parentdiv).fadeOut(200,
					function() {
						parentdiv.removeClass('add_friend');
						parentdiv.removeClass('loading');
						parentdiv.addClass('pending_friend');
						parentdiv.fadeIn(200).html(response);
					}
					);

			} else if ( action == 'remove' ) {
				jq(parentdiv).fadeOut(200,
					function() {
						parentdiv.removeClass('remove_friend');
						parentdiv.removeClass('loading');
						parentdiv.addClass('add');
						parentdiv.fadeIn(200).html(response);
					}
					);
			}
		});
		return false;
	} );

	/** Group Join / Leave Buttons **************************************/

	// Confirmation when clicking Leave Group in group headers
	jq('#buddypress').on('click', '.group-button .leave-group', function() {
		if ( false == confirm( BP_DTheme.leave_group_confirm ) ) {
			return false;
		}
	});

	jq('#groups-dir-list').on('click', '.group-button a', function() {
		var gid = jq(this).parent().attr('id');
		gid = gid.split('-');
		gid = gid[1];

		var nonce = jq(this).attr('href');
		nonce = nonce.split('?_wpnonce=');
		nonce = nonce[1].split('&');
		nonce = nonce[0];

		var thelink = jq(this);

		// Leave Group confirmation within directories - must intercept
		// AJAX request
		if ( thelink.hasClass( 'leave-group' ) && false == confirm( BP_DTheme.leave_group_confirm ) ) {
			return false;
		}

		jq.post( ajaxurl, {
			action: 'joinleave_group',
			'cookie': bp_get_cookies(),
			'gid': gid,
			'_wpnonce': nonce
		},
		function(response)
		{
			var parentdiv = thelink.parent();

			// user groups page
			if ( ! jq('body.directory').length ) {
				location.href = location.href;

			// groups directory
			} else {
				jq(parentdiv).fadeOut(200,
					function() {
						parentdiv.fadeIn(200).html(response);

						var mygroups = jq('#groups-personal span');
						var add      = 1;

						if( thelink.hasClass( 'leave-group' ) ) {
							// hidden groups slide up
							if ( parentdiv.hasClass( 'hidden' ) ) {
								parentdiv.closest('li').slideUp( 200 );
							}

							add = 0;
						} else if ( thelink.hasClass( 'request-membership' ) ) {
							add = false;
						}

						// change the "My Groups" value
						if ( mygroups.length && add !== false ) {
							if ( add ) {
								mygroups.text( ( mygroups.text() >> 0 ) + 1 );
							} else {
								mygroups.text( ( mygroups.text() >> 0 ) - 1 );
							}
						}

					}
				);
			}
		});
		return false;
	} );

	/** Button disabling ************************************************/

	jq('#buddypress').on( 'click', '.pending', function() {
		return false;
	});

	/** Private Messaging ******************************************/

	/** Message search*/
	jq('.message-search').on( 'click', function(event) {
		if ( jq(this).hasClass('no-ajax') )
			return;

		var target = jq(event.target);

		if ( target.attr('type') == 'submit' ) {
			//var css_id = jq('.item-list-tabs li.selected').attr('id').split( '-' );
			var object = 'messages';

			bp_filter_request( object, jq.cookie('bp-' + object + '-filter'), jq.cookie('bp-' + object + '-scope') , 'div.' + object, target.parent().children('label').children('input').val(), 1, jq.cookie('bp-' + object + '-extras') );

			return false;
		}
	});

	/* AJAX send reply functionality */
	jq("#send_reply_button").click(
		function() {
			var order = jq('#messages_order').val() || 'ASC',
			offset = jq('#message-recipients').offset();

			var button = jq("#send_reply_button");
			jq(button).addClass('loading');

			jq.post( ajaxurl, {
				action: 'messages_send_reply',
				'cookie': bp_get_cookies(),
				'_wpnonce': jq("#send_message_nonce").val(),

				'content': jq("#message_content").val(),
				'send_to': jq("#send_to").val(),
				'subject': jq("#subject").val(),
				'thread_id': jq("#thread_id").val()
			},
			function(response)
			{
				if ( response[0] + response[1] == "-1" ) {
					jq('#send-reply').prepend( response.substr( 2, response.length ) );
				} else {
					jq('#send-reply #message').remove();
					jq("#message_content").val('');

					if ( 'ASC' == order ) {
						jq('#send-reply').before( response );
					} else {
						jq('#message-recipients').after( response );
						jq(window).scrollTop(offset.top);
					}

					jq(".new-message").hide().slideDown( 200, function() {
						jq('.new-message').removeClass('new-message');
					});
				}
				jq(button).removeClass('loading');
			});

			return false;
		}
	);

	/* Marking private messages as read and unread */
	jq("#mark_as_read, #mark_as_unread").click(function() {
		var checkboxes_tosend = '';
		var checkboxes = jq("#message-threads tr td input[type='checkbox']");

		if ( 'mark_as_unread' == jq(this).attr('id') ) {
			var currentClass = 'read'
			var newClass = 'unread'
			var unreadCount = 1;
			var inboxCount = 0;
			var unreadCountDisplay = 'inline';
			var action = 'messages_markunread';
		} else {
			var currentClass = 'unread'
			var newClass = 'read'
			var unreadCount = 0;
			var inboxCount = 1;
			var unreadCountDisplay = 'none';
			var action = 'messages_markread';
		}

		checkboxes.each( function(i) {
			if(jq(this).is(':checked')) {
				if ( jq('#m-' + jq(this).attr('value')).hasClass(currentClass) ) {
					checkboxes_tosend += jq(this).attr('value');
					jq('#m-' + jq(this).attr('value')).removeClass(currentClass);
					jq('#m-' + jq(this).attr('value')).addClass(newClass);
					var thread_count = jq('#m-' + jq(this).attr('value') + ' td span.unread-count').html();

					jq('#m-' + jq(this).attr('value') + ' td span.unread-count').html(unreadCount);
					jq('#m-' + jq(this).attr('value') + ' td span.unread-count').css('display', unreadCountDisplay);

					var inboxcount = jq('tr.unread').length;

					jq('#user-messages span').html( inboxcount );

					if ( i != checkboxes.length - 1 ) {
						checkboxes_tosend += ','
					}
				}
			}
		});
		jq.post( ajaxurl, {
			action: action,
			'thread_ids': checkboxes_tosend
		});
		return false;
	});

	/* Selecting unread and read messages in inbox */
	jq( 'body.messages #item-body div.messages' ).on( 'change', '#message-type-select', function() {
		var selection = this.value;
		var checkboxes = jq( "td input[type='checkbox']" );

		checkboxes.each( function(i) {
			checkboxes[i].checked = "";
		});

		var checked_value = "checked";
		switch ( selection ) {
			case 'unread' :
				checkboxes = jq("tr.unread td input[type='checkbox']");
				break;
			case 'read' :
				checkboxes = jq("tr.read td input[type='checkbox']");
				break;
			case '' :
				checked_value = "";
				break;
		}

		checkboxes.each( function(i) {
			checkboxes[i].checked = checked_value;
		});
	});
	
	/* Bulk delete messages */
	jq( 'body.messages #item-body div.messages' ).on( 'click', '.messages-options-nav a', function() {
		if ( -1 == jq.inArray( this.id ), Array( 'delete_sentbox_messages', 'delete_inbox_messages' ) ) {
			return;
		}
		
		checkboxes_tosend = '';
		checkboxes = jq("#message-threads tr td input[type='checkbox']");

		jq('#message').remove();
		jq(this).addClass('loading');

		jq(checkboxes).each( function(i) {
			if( jq(this).is(':checked') )
				checkboxes_tosend += jq(this).attr('value') + ',';
		});

		if ( '' == checkboxes_tosend ) {
			jq(this).removeClass('loading');
			return false;
		}
		
		jq.post( ajaxurl, {
			action: 'messages_delete',
			'thread_ids': checkboxes_tosend
		}, function(response) {
			if ( response[0] + response[1] == "-1" ) {
				jq('#message-threads').prepend( response.substr( 2, response.length ) );
			} else {
				jq('#message-threads').before( '<div id="message" class="updated"><p>' + response + '</p></div>' );

				jq(checkboxes).each( function(i) {
					if( jq(this).is(':checked') ) {
						// We need to uncheck because message is only hidden
						// Otherwise, AJAX will be fired again with same data 
						jq(this).attr( 'checked', false );
						jq(this).parent().parent().fadeOut(150);
					}
				});
			}

			jq('#message').hide().slideDown(150);
			jq("#delete_inbox_messages, #delete_sentbox_messages").removeClass('loading');
		});

		return false;
	});

	/* Close site wide notices in the sidebar */
	jq("#close-notice").on( 'click', function() {
		jq(this).addClass('loading');
		jq('#sidebar div.error').remove();

		jq.post( ajaxurl, {
			action: 'messages_close_notice',
			'notice_id': jq('.notice').attr('rel').substr( 2, jq('.notice').attr('rel').length )
		},
		function(response) {
			jq("#close-notice").removeClass('loading');

			if ( response[0] + response[1] == '-1' ) {
				jq('.notice').prepend( response.substr( 2, response.length ) );
				jq( '#sidebar div.error').hide().fadeIn( 200 );
			} else {
				jq('.notice').slideUp( 100 );
			}
		});
		return false;
	});

	/* Toolbar & wp_list_pages Javascript IE6 hover class */
	jq("#wp-admin-bar ul.main-nav li, #nav li").mouseover( function() {
		jq(this).addClass('sfhover');
	});

	jq("#wp-admin-bar ul.main-nav li, #nav li").mouseout( function() {
		jq(this).removeClass('sfhover');
	});

	/* Clear BP cookies on logout */
	jq('a.logout').on( 'click', function() {
		jq.cookie('bp-activity-scope', null, {
			path: '/'
		});
		jq.cookie('bp-activity-filter', null, {
			path: '/'
		});
		jq.cookie('bp-activity-oldestpage', null, {
			path: '/'
		});

		var objects = [ 'members', 'groups', 'blogs', 'forums' ];
		jq(objects).each( function(i) {
			jq.cookie('bp-' + objects[i] + '-scope', null, {
				path: '/'
			} );
			jq.cookie('bp-' + objects[i] + '-filter', null, {
				path: '/'
			} );
			jq.cookie('bp-' + objects[i] + '-extras', null, {
				path: '/'
			} );
		});
	});
	
	/* if js is enabled then replace the no-js class by a js one */
	if( jq('body').hasClass('no-js') )
		jq('body').attr('class', jq('body').attr('class').replace( /no-js/,'js' ) );
		
});

/* Setup activity scope and filter based on the current cookie settings. */
function bp_init_activity() {
	/* Reset the page */
	jq.cookie( 'bp-activity-oldestpage', 1, {
		path: '/'
	} );

	if ( null != jq.cookie('bp-activity-filter') && jq('#activity-filter-select').length )
		jq('#activity-filter-select select option[value="' + jq.cookie('bp-activity-filter') + '"]').prop( 'selected', true );

	/* Activity Tab Set */
	if ( null != jq.cookie('bp-activity-scope') && jq('.activity-type-tabs').length ) {
		jq('.activity-type-tabs li').each( function() {
			jq(this).removeClass('selected');
		});
		jq('#activity-' + jq.cookie('bp-activity-scope') + ', .item-list-tabs li.current').addClass('selected');
	}
}

/* Setup object scope and filter based on the current cookie settings for the object. */
function bp_init_objects(objects) {
	jq(objects).each( function(i) {
		if ( null != jq.cookie('bp-' + objects[i] + '-filter') && jq('#' + objects[i] + '-order-select select').length )
			jq('#' + objects[i] + '-order-select select option[value="' + jq.cookie('bp-' + objects[i] + '-filter') + '"]').prop( 'selected', true );

		if ( null != jq.cookie('bp-' + objects[i] + '-scope') && jq('div.' + objects[i]).length ) {
			jq('.item-list-tabs li').each( function() {
				jq(this).removeClass('selected');
			});
			jq('#' + objects[i] + '-' + jq.cookie('bp-' + objects[i] + '-scope') + ', #object-nav li.current').addClass('selected');
		}
	});
}

/* Filter the current content list (groups/members/blogs/topics) */
function bp_filter_request( object, filter, scope, target, search_terms, page, extras, caller ) {
	if ( 'activity' == object )
		return false;

	if ( jq.query.get('s') && !search_terms )
		search_terms = jq.query.get('s');

	if ( null == scope )
		scope = 'all';

	/* Save the settings we want to remain persistent to a cookie */
	jq.cookie( 'bp-' + object + '-scope', scope, {
		path: '/'
	} );
	jq.cookie( 'bp-' + object + '-filter', filter, {
		path: '/'
	} );
	jq.cookie( 'bp-' + object + '-extras', extras, {
		path: '/'
	} );

	/* Set the correct selected nav and filter */
	jq('.item-list-tabs li').each( function() {
		jq(this).removeClass('selected');
	});
	jq('#' + object + '-' + scope + ', #object-nav li.current').addClass('selected');
	jq('.item-list-tabs li.selected').addClass('loading');
	jq('.item-list-tabs select option[value="' + filter + '"]').prop( 'selected', true );

	if ( 'friends' == object )
		object = 'members';

	if ( bp_ajax_request )
		bp_ajax_request.abort();

	bp_ajax_request = jq.post( ajaxurl, {
		action: object + '_filter',
		'cookie': bp_get_cookies(),
		'object': object,
		'filter': filter,
		'search_terms': search_terms,
		'scope': scope,
		'page': page,
		'extras': extras
	},
	function(response)
	{
		/* animate to top if called from bottom pagination */
		if ( caller == 'pag-bottom' && jq('#subnav').length ) {
			var top = jq('#subnav').parent();
			jq('html,body').animate({scrollTop: top.offset().top}, 'slow', function() {
				jq(target).fadeOut( 100, function() {
					jq(this).html(response);
					jq(this).fadeIn(100);
			 	});
			});	

		} else {
			jq(target).fadeOut( 100, function() {
				jq(this).html(response);
				jq(this).fadeIn(100);
		 	});
		}

		jq('.item-list-tabs li.selected').removeClass('loading');
	});
}

/* Activity Loop Requesting */
function bp_activity_request(scope, filter) {
	/* Save the type and filter to a session cookie */
	jq.cookie( 'bp-activity-scope', scope, {
		path: '/'
	} );
	jq.cookie( 'bp-activity-filter', filter, {
		path: '/'
	} );
	jq.cookie( 'bp-activity-oldestpage', 1, {
		path: '/'
	} );

	/* Remove selected and loading classes from tabs */
	jq('.item-list-tabs li').each( function() {
		jq(this).removeClass('selected loading');
	});
	/* Set the correct selected nav and filter */
	jq('#activity-' + scope + ', .item-list-tabs li.current').addClass('selected');
	jq('#object-nav.item-list-tabs li.selected, div.activity-type-tabs li.selected').addClass('loading');
	jq('#activity-filter-select select option[value="' + filter + '"]').prop( 'selected', true );

	/* Reload the activity stream based on the selection */
	jq('.widget_bp_activity_widget h2 span.ajax-loader').show();

	if ( bp_ajax_request )
		bp_ajax_request.abort();

	bp_ajax_request = jq.post( ajaxurl, {
		action: 'activity_widget_filter',
		'cookie': bp_get_cookies(),
		'_wpnonce_activity_filter': jq("#_wpnonce_activity_filter").val(),
		'scope': scope,
		'filter': filter
	},
	function(response)
	{
		jq('.widget_bp_activity_widget h2 span.ajax-loader').hide();

		jq('div.activity').fadeOut( 100, function() {
			jq(this).html(response.contents);
			jq(this).fadeIn(100);

			/* Selectively hide comments */
			bp_legacy_theme_hide_comments();
		});

		/* Update the feed link */
		if ( null != response.feed_url )
			jq('.directory #subnav li.feed a, .home-page #subnav li.feed a').attr('href', response.feed_url);

		jq('.item-list-tabs li.selected').removeClass('loading');

	}, 'json' );
}

/* Hide long lists of activity comments, only show the latest five root comments. */
function bp_legacy_theme_hide_comments() {
	var comments_divs = jq('div.activity-comments');

	if ( !comments_divs.length )
		return false;

	comments_divs.each( function() {
		if ( jq(this).children('ul').children('li').length < 5 ) return;

		var comments_div = jq(this);
		var parent_li = comments_div.parents('#activity-stream > li');
		var comment_lis = jq(this).children('ul').children('li');
		var comment_count = ' ';

		if ( jq('#' + parent_li.attr('id') + ' a.acomment-reply span').length )
			var comment_count = jq('#' + parent_li.attr('id') + ' a.acomment-reply span').html();

		comment_lis.each( function(i) {
			/* Show the latest 5 root comments */
			if ( i < comment_lis.length - 5 ) {
				jq(this).addClass('hidden');
				jq(this).toggle();

				if ( !i ) 
					jq(this).before( '<li class="show-all"><a href="#' + parent_li.attr('id') + '/show-all/" title="' + BP_DTheme.show_all_comments + '">' + BP_DTheme.show_x_comments.replace( '%d', comment_count ) + '</a></li>' );
			}
		});

	});
}

/* Helper Functions */

function checkAll() {
	var checkboxes = document.getElementsByTagName("input");
	for(var i=0; i<checkboxes.length; i++) {
		if(checkboxes[i].type == "checkbox") {
			if($("check_all").checked == "") {
				checkboxes[i].checked = "";
			}
			else {
				checkboxes[i].checked = "checked";
			}
		}
	}
}

function clear(container) {
	if( !document.getElementById(container) ) return;

	var container = document.getElementById(container);

	if ( radioButtons = container.getElementsByTagName('INPUT') ) {
		for(var i=0; i<radioButtons.length; i++) {
			radioButtons[i].checked = '';
		}
	}

	if ( options = container.getElementsByTagName('OPTION') ) {
		for(var i=0; i<options.length; i++) {
			options[i].selected = false;
		}
	}

	return;
}

/* Returns a querystring of BP cookies (cookies beginning with 'bp-') */
function bp_get_cookies() {
	// get all cookies and split into an array
	var allCookies   = document.cookie.split(";");

	var bpCookies    = {};
	var cookiePrefix = 'bp-';

	// loop through cookies
	for (var i = 0; i < allCookies.length; i++) {
		var cookie    = allCookies[i];
		var delimiter = cookie.indexOf("=");
		var name      = jq.trim( unescape( cookie.slice(0, delimiter) ) );
		var value     = unescape( cookie.slice(delimiter + 1) );

		// if BP cookie, store it
		if ( name.indexOf(cookiePrefix) == 0 ) {
			bpCookies[name] = value;
		}
	}

	// returns BP cookies as querystring
	return encodeURIComponent( jq.param(bpCookies) );
}

/* ScrollTo plugin - just inline and minified */
;(function($){var h=$.scrollTo=function(a,b,c){$(window).scrollTo(a,b,c)};h.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:true};h.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(e,f,g){if(typeof f=='object'){g=f;f=0}if(typeof g=='function')g={onAfter:g};if(e=='max')e=9e9;g=$.extend({},h.defaults,g);f=f||g.duration;g.queue=g.queue&&g.axis.length>1;if(g.queue)f/=2;g.offset=both(g.offset);g.over=both(g.over);return this._scrollable().each(function(){if(e==null)return;var d=this,$elem=$(d),targ=e,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}$.each(g.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=h.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(g.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=g.offset[pos]||0;if(g.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*g.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(g.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&g.queue){if(old!=attr[key])animate(g.onAfterFirst);delete attr[key]}});animate(g.onAfter);function animate(a){$elem.animate(attr,f,g.easing,a&&function(){a.call(this,e,g)})}}).end()};h.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return typeof a=='object'?a:{top:a,left:a}}})(jQuery);

/* jQuery Easing Plugin, v1.3 - http://gsgd.co.uk/sandbox/jquery/easing/ */
jQuery.easing.jswing=jQuery.easing.swing;jQuery.extend(jQuery.easing,{def:"easeOutQuad",swing:function(e,f,a,h,g){return jQuery.easing[jQuery.easing.def](e,f,a,h,g)},easeInQuad:function(e,f,a,h,g){return h*(f/=g)*f+a},easeOutQuad:function(e,f,a,h,g){return -h*(f/=g)*(f-2)+a},easeInOutQuad:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f+a}return -h/2*((--f)*(f-2)-1)+a},easeInCubic:function(e,f,a,h,g){return h*(f/=g)*f*f+a},easeOutCubic:function(e,f,a,h,g){return h*((f=f/g-1)*f*f+1)+a},easeInOutCubic:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f+a}return h/2*((f-=2)*f*f+2)+a},easeInQuart:function(e,f,a,h,g){return h*(f/=g)*f*f*f+a},easeOutQuart:function(e,f,a,h,g){return -h*((f=f/g-1)*f*f*f-1)+a},easeInOutQuart:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f+a}return -h/2*((f-=2)*f*f*f-2)+a},easeInQuint:function(e,f,a,h,g){return h*(f/=g)*f*f*f*f+a},easeOutQuint:function(e,f,a,h,g){return h*((f=f/g-1)*f*f*f*f+1)+a},easeInOutQuint:function(e,f,a,h,g){if((f/=g/2)<1){return h/2*f*f*f*f*f+a}return h/2*((f-=2)*f*f*f*f+2)+a},easeInSine:function(e,f,a,h,g){return -h*Math.cos(f/g*(Math.PI/2))+h+a},easeOutSine:function(e,f,a,h,g){return h*Math.sin(f/g*(Math.PI/2))+a},easeInOutSine:function(e,f,a,h,g){return -h/2*(Math.cos(Math.PI*f/g)-1)+a},easeInExpo:function(e,f,a,h,g){return(f==0)?a:h*Math.pow(2,10*(f/g-1))+a},easeOutExpo:function(e,f,a,h,g){return(f==g)?a+h:h*(-Math.pow(2,-10*f/g)+1)+a},easeInOutExpo:function(e,f,a,h,g){if(f==0){return a}if(f==g){return a+h}if((f/=g/2)<1){return h/2*Math.pow(2,10*(f-1))+a}return h/2*(-Math.pow(2,-10*--f)+2)+a},easeInCirc:function(e,f,a,h,g){return -h*(Math.sqrt(1-(f/=g)*f)-1)+a},easeOutCirc:function(e,f,a,h,g){return h*Math.sqrt(1-(f=f/g-1)*f)+a},easeInOutCirc:function(e,f,a,h,g){if((f/=g/2)<1){return -h/2*(Math.sqrt(1-f*f)-1)+a}return h/2*(Math.sqrt(1-(f-=2)*f)+1)+a},easeInElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return -(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e},easeOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k)==1){return e+l}if(!j){j=k*0.3}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}return g*Math.pow(2,-10*h)*Math.sin((h*k-i)*(2*Math.PI)/j)+l+e},easeInOutElastic:function(f,h,e,l,k){var i=1.70158;var j=0;var g=l;if(h==0){return e}if((h/=k/2)==2){return e+l}if(!j){j=k*(0.3*1.5)}if(g<Math.abs(l)){g=l;var i=j/4}else{var i=j/(2*Math.PI)*Math.asin(l/g)}if(h<1){return -0.5*(g*Math.pow(2,10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j))+e}return g*Math.pow(2,-10*(h-=1))*Math.sin((h*k-i)*(2*Math.PI)/j)*0.5+l+e},easeInBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*(f/=h)*f*((g+1)*f-g)+a},easeOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}return i*((f=f/h-1)*f*((g+1)*f+g)+1)+a},easeInOutBack:function(e,f,a,i,h,g){if(g==undefined){g=1.70158}if((f/=h/2)<1){return i/2*(f*f*(((g*=(1.525))+1)*f-g))+a}return i/2*((f-=2)*f*(((g*=(1.525))+1)*f+g)+2)+a},easeInBounce:function(e,f,a,h,g){return h-jQuery.easing.easeOutBounce(e,g-f,0,h,g)+a},easeOutBounce:function(e,f,a,h,g){if((f/=g)<(1/2.75)){return h*(7.5625*f*f)+a}else{if(f<(2/2.75)){return h*(7.5625*(f-=(1.5/2.75))*f+0.75)+a}else{if(f<(2.5/2.75)){return h*(7.5625*(f-=(2.25/2.75))*f+0.9375)+a}else{return h*(7.5625*(f-=(2.625/2.75))*f+0.984375)+a}}}},easeInOutBounce:function(e,f,a,h,g){if(f<g/2){return jQuery.easing.easeInBounce(e,f*2,0,h,g)*0.5+a}return jQuery.easing.easeOutBounce(e,f*2-g,0,h,g)*0.5+h*0.5+a}});

/* jQuery Cookie plugin */
jQuery.cookie=function(name,value,options){if(typeof value!='undefined'){options=options||{};if(value===null){value='';options.expires=-1;}var expires='';if(options.expires&&(typeof options.expires=='number'||options.expires.toUTCString)){var date;if(typeof options.expires=='number'){date=new Date();date.setTime(date.getTime()+(options.expires*24*60*60*1000));}else{date=options.expires;}expires='; expires='+date.toUTCString();}var path=options.path?'; path='+(options.path):'';var domain=options.domain?'; domain='+(options.domain):'';var secure=options.secure?'; secure':'';document.cookie=[name,'=',encodeURIComponent(value),expires,path,domain,secure].join('');}else{var cookieValue=null;if(document.cookie&&document.cookie!=''){var cookies=document.cookie.split(';');for(var i=0;i<cookies.length;i++){var cookie=jQuery.trim(cookies[i]);if(cookie.substring(0,name.length+1)==(name+'=')){cookieValue=decodeURIComponent(cookie.substring(name.length+1));break;}}}return cookieValue;}};

/* jQuery querystring plugin */
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('M 6(A){4 $11=A.11||\'&\';4 $V=A.V===r?r:j;4 $1p=A.1p===r?\'\':\'[]\';4 $13=A.13===r?r:j;4 $D=$13?A.D===j?"#":"?":"";4 $15=A.15===r?r:j;v.1o=M 6(){4 f=6(o,t){8 o!=1v&&o!==x&&(!!t?o.1t==t:j)};4 14=6(1m){4 m,1l=/\\[([^[]*)\\]/g,T=/^([^[]+)(\\[.*\\])?$/.1r(1m),k=T[1],e=[];19(m=1l.1r(T[2]))e.u(m[1]);8[k,e]};4 w=6(3,e,7){4 o,y=e.1b();b(I 3!=\'X\')3=x;b(y===""){b(!3)3=[];b(f(3,L)){3.u(e.h==0?7:w(x,e.z(0),7))}n b(f(3,1a)){4 i=0;19(3[i++]!=x);3[--i]=e.h==0?7:w(3[i],e.z(0),7)}n{3=[];3.u(e.h==0?7:w(x,e.z(0),7))}}n b(y&&y.T(/^\\s*[0-9]+\\s*$/)){4 H=1c(y,10);b(!3)3=[];3[H]=e.h==0?7:w(3[H],e.z(0),7)}n b(y){4 H=y.B(/^\\s*|\\s*$/g,"");b(!3)3={};b(f(3,L)){4 18={};1w(4 i=0;i<3.h;++i){18[i]=3[i]}3=18}3[H]=e.h==0?7:w(3[H],e.z(0),7)}n{8 7}8 3};4 C=6(a){4 p=d;p.l={};b(a.C){v.J(a.Z(),6(5,c){p.O(5,c)})}n{v.J(1u,6(){4 q=""+d;q=q.B(/^[?#]/,\'\');q=q.B(/[;&]$/,\'\');b($V)q=q.B(/[+]/g,\' \');v.J(q.Y(/[&;]/),6(){4 5=1e(d.Y(\'=\')[0]||"");4 c=1e(d.Y(\'=\')[1]||"");b(!5)8;b($15){b(/^[+-]?[0-9]+\\.[0-9]*$/.1d(c))c=1A(c);n b(/^[+-]?[0-9]+$/.1d(c))c=1c(c,10)}c=(!c&&c!==0)?j:c;b(c!==r&&c!==j&&I c!=\'1g\')c=c;p.O(5,c)})})}8 p};C.1H={C:j,1G:6(5,1f){4 7=d.Z(5);8 f(7,1f)},1h:6(5){b(!f(5))8 d.l;4 K=14(5),k=K[0],e=K[1];4 3=d.l[k];19(3!=x&&e.h!=0){3=3[e.1b()]}8 I 3==\'1g\'?3:3||""},Z:6(5){4 3=d.1h(5);b(f(3,1a))8 v.1E(j,{},3);n b(f(3,L))8 3.z(0);8 3},O:6(5,c){4 7=!f(c)?x:c;4 K=14(5),k=K[0],e=K[1];4 3=d.l[k];d.l[k]=w(3,e.z(0),7);8 d},w:6(5,c){8 d.N().O(5,c)},1s:6(5){8 d.O(5,x).17()},1z:6(5){8 d.N().1s(5)},1j:6(){4 p=d;v.J(p.l,6(5,7){1y p.l[5]});8 p},1F:6(Q){4 D=Q.B(/^.*?[#](.+?)(?:\\?.+)?$/,"$1");4 S=Q.B(/^.*?[?](.+?)(?:#.+)?$/,"$1");8 M C(Q.h==S.h?\'\':S,Q.h==D.h?\'\':D)},1x:6(){8 d.N().1j()},N:6(){8 M C(d)},17:6(){6 F(G){4 R=I G=="X"?f(G,L)?[]:{}:G;b(I G==\'X\'){6 1k(o,5,7){b(f(o,L))o.u(7);n o[5]=7}v.J(G,6(5,7){b(!f(7))8 j;1k(R,5,F(7))})}8 R}d.l=F(d.l);8 d},1B:6(){8 d.N().17()},1D:6(){4 i=0,U=[],W=[],p=d;4 16=6(E){E=E+"";b($V)E=E.B(/ /g,"+");8 1C(E)};4 1n=6(1i,5,7){b(!f(7)||7===r)8;4 o=[16(5)];b(7!==j){o.u("=");o.u(16(7))}1i.u(o.P(""))};4 F=6(R,k){4 12=6(5){8!k||k==""?[5].P(""):[k,"[",5,"]"].P("")};v.J(R,6(5,7){b(I 7==\'X\')F(7,12(5));n 1n(W,12(5),7)})};F(d.l);b(W.h>0)U.u($D);U.u(W.P($11));8 U.P("")}};8 M C(1q.S,1q.D)}}(v.1o||{});',62,106,'|||target|var|key|function|value|return|||if|val|this|tokens|is||length||true|base|keys||else||self||false|||push|jQuery|set|null|token|slice|settings|replace|queryObject|hash|str|build|orig|index|typeof|each|parsed|Array|new|copy|SET|join|url|obj|search|match|queryString|spaces|chunks|object|split|get||separator|newKey|prefix|parse|numbers|encode|COMPACT|temp|while|Object|shift|parseInt|test|decodeURIComponent|type|number|GET|arr|EMPTY|add|rx|path|addFields|query|suffix|location|exec|REMOVE|constructor|arguments|undefined|for|empty|delete|remove|parseFloat|compact|encodeURIComponent|toString|extend|load|has|prototype'.split('|'),0,{}))
