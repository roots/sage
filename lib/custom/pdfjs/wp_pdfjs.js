jQuery(document).ready(function(){
    (function ($) {
        if(typeof wp !== 'undefined' && typeof wp.media !== 'undefined'){
            frame = wp.media({
                title: 'Insert Presentation',
                library: { type: 'application' },
                button: {
                    text: 'Insert Presentation',
                    close: true
                }
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first();
                var win = window.dialogArguments || opener || parent || top;
                var shortcode='[wp_pdfjs id='+ attachment.id +' ]';
                win.send_to_editor(shortcode);
            });
            $('#wp_pdfjs-menu-button').click(function(e) {
                frame.open();
            });
        }       
    })(jQuery);  
})
