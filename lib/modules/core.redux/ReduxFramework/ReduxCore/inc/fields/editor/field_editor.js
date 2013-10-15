(function($){
    "use strict";
    
    $.redux = $.redux || {};
    
    $(document).ready(function () {
        $.redux.editor();
    });
    
    $.redux.editor = function(){
        jQuery("textarea#redux-editor").each(function(){
           
            var el_id   = this.id,
            current     = jQuery(this), 
            parent      = current.parents('.wp-editor-wrap:eq(0)'),
            textarea    = parent.find('textarea#redux-editor'),
            switch_btn  = parent.find('.wp-switch-editor').removeAttr("onclick"),
            settings    = {
                id: this.id , 
                buttons: "strong,em,link,block,del,ins,img,ul,ol,li,code,spell,close"
            };
            
            // add quicktags for text editor
            quicktags(settings);
            QTags._buttonsInit(); //workaround since dom ready was triggered already and there would be no initialization
            
            // modify behavior for html editor
            switch_btn.bind('click', function(){
                var button = jQuery(this);
                
                if(button.is('.switch-tmce')){
                    parent.removeClass('html-active').addClass('tmce-active');
                    window.tinyMCE.execCommand("mceAddControl", true, el_id);
                    window.tinyMCE.get(el_id).setContent(window.switchEditors.wpautop(textarea.val()), {
                        format:'raw'
                    });
                }
                else
                {
                    parent.removeClass('tmce-active').addClass('html-active');
                    window.tinyMCE.execCommand("mceRemoveControl", true, el_id);
                }
            }).trigger('click');
            
            //make sure that when the save button is pressed the textarea gets updated and sent to the editor
            $("#dialog").find('.ui-button').bind('click', function(){
                switch_btn.filter('.switch-html').trigger('click');
            });

        });
    }
})(jQuery);   