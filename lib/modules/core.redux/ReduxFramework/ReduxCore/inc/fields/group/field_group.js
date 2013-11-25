/* global redux_change */
(function($){
    "use strict";
    
    $.redux.group = $.group || {};
	
    $(document).ready(function () {
        //Group functionality
        $.redux.group();
    });
    
    $.redux.group = function(){
        $("#redux-groups-accordion")
        .accordion({
            header: "> div > h3",
            collapsible: true,
            active: false,
            heightStyle: "content",
            icons: {
                "header": "ui-icon-plus", 
                "activeHeader": "ui-icon-minus"
            }
        })
        .sortable({
            axis: "y",
            handle: "h3",
            stop: function (event, ui) {
                // IE doesn't register the blur when sorting
                // so trigger focusout handlers to remove .ui-state-focus
                ui.item.children("h3").triggerHandler("focusout");
                var inputs = $('input.slide-sort');
                inputs.each(function(idx) {
                    $(this).val(idx);
                });
            }
        });
        
        $('.redux-groups-accordion-group input[type=text]').on('keyup',function(event) {
            $(this).parents('.redux-groups-accordion-group:first').find('.redux-groups-header').text(event.target.value);
            $(this).parents('.redux-groups-accordion-group:first').find('.slide-title').val(event.target.value);
        });
        
        $('.redux-groups-remove').live('click', function () {
            redux_change($(this));
            $(this).parent().find('input[type="text"]').val('');
            $(this).parent().find('input[type="hidden"]').val('');
            $(this).parent().parent().slideUp('medium', function () {
                $(this).remove();
            });
        });

        $('.redux-groups-add').click(function () {

            var newSlide = $(this).prev().find('.redux-groups-accordion-group:last').clone(true);
            var slideCount = $(newSlide).find('input[type="text"]').attr("name").match(/\d+(?!.*\d+)/);
            var slideCount1 = slideCount*1 + 1;

            $(newSlide).find('h3').text('').append('<span class="redux-groups-header">New Group</span><span class="ui-accordion-header-icon ui-icon ui-icon-plus"></span>');
            $(this).prev().append(newSlide);


            $(newSlide).find('input[type="text"], input[type="hidden"], textarea , select').each(function(){
                var attr_name = $(this).attr('name');
                var attr_id = $(this).attr('id');
                
                // For some browsers, `attr` is undefined; for others,
                // `attr` is false.  Check for both.
                if (typeof attr_id !== 'undefined' && attr_id !== false) 
                    $(this).attr("id", $(this).attr("id").replace(/\d+(?!.*\d+)/, slideCount1) );
                if (typeof attr_name !== 'undefined' && attr_name !== false) 
                    $(this).attr("name", $(this).attr("name").replace(/\d+(?!.*\d+)/, slideCount1) );

                if($(this).prop("tagName") == 'SELECT'){
                    //we clean select2 first
                    $(newSlide).find('.select2-container').remove();
                    $(newSlide).find('select').removeClass('select2-offscreen');
                }

                $(this).val('');
                if ($(this).hasClass('slide-sort')){
                    $(this).val(slideCount1);
                }
            });

            
        });
    }
})(jQuery);