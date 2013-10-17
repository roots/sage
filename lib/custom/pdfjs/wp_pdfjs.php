<?php
/*
 * Plugin Name: pdf.js
 * Plugin URI: 
 * Description: Publish PDF presentations and documents in your posts. 
 * Version: 0.2
 * Author: Henning Kropp
 * Author URI: http://henning.kropponline.de
 * License: GPLv3 (http://www.gnu.org/licenses/gpl-3.0.txt)
*/

define( 'WP_PDFJS_DIR', locate_template('/lib/custom/pdfjs/' . __FILE__ ) );
define( 'WP_PDFJS_URL', locate_template('/lib/custom/pdfjs/' . __FILE__ ) );


// add css + js
function add_wp_pdfjs_css_and_script() {
    wp_enqueue_style('wp_pdfjs_css', WP_PDFJS_URL.'wp_pdfjs.css', false );
    wp_enqueue_script('wp_pdfjs_js', WP_PDFJS_URL.'wp_pdfjs.js', false );
}
add_action('admin_enqueue_scripts', 'add_wp_pdfjs_css_and_script' );

//function add_wp_pdfjs_view_css() {
//    wp_enqueue_style('wp_pdfjs_view_css', WP_PDFJS_URL.'wp_pdfjs_view.css', false );
//    wp_enqueue_script('wp_pdfjs_view_js', WP_PDFJS_URL.'wp_pdfjs_view.js', false );
//}
//add_action( 'wp_enqueue_scripts', 'my_scripts_method' );

// add button to edit menu
function wp_pdfjs_media_button() {
    $title = esc_attr('Add a presentation');
    echo '<a href="#" title="' . $title . '"><div id="wp_pdfjs-menu-button" alt="' . $title . '"></div></a>';
}
add_action( 'media_buttons', 'wp_pdfjs_media_button', 1000 );

// add pdf filter to media uploader
function modify_post_mime_types($post_mime_types) {
    $post_mime_types['application/pdf'] = array(__('PDFs'), __('Manage PDFs'), _n_noop('PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>'));
    return $post_mime_types;
} 
add_filter('post_mime_types', 'modify_post_mime_types');

// add upload mime pdf
function custom_upload_mimes ( $existing_mimes ) {
    $existing_mimes['pdf'] = 'application/pdf'; 
    return $existing_mimes;
}
add_filter('upload_mimes', 'custom_upload_mimes');


// shortcode [wp_pdfjs io=123 scale=1.5]
function wp_pdfjs_func( $atts ) {
    extract(shortcode_atts(array(
                'id' => '-1',
                'url' => '',
                'scale' => '1.5',
                'download' => true
                    ), $atts));

    if ($id <= 0 && url == '') {
        return "[wp_pdfjs: MISSING ATTACHMENT ID OR URL!]";
    }
    
    $pdfjs_image_url = WP_PDFJS_URL.'images/';
    
    $pdfjs_script_url = WP_PDFJS_URL.'pdf.js';

    $presentation_url = "";
    if( $id < 0){
      $id = base_convert($url, 10, 36);
      $presentation_url = $url;
    } else {
      $presentation_url = wp_get_attachment_url($id);
    }
    

    $render_function = "
                    PDFJS.disableWorker = true;
                    var pdfDoc_{$id} = null,
                        pageNum_{$id} = 1,
                        scale_{$id} = ${scale},
                        canvas_{$id} = document.getElementById('wp_pdfjs_canvas_{$id}'),
                        ctx_{$id} = canvas_{$id}.getContext('2d');
                        
                    function wp_pdfj_renderPage_{$id}(num) {
                        // Using promise to fetch the page
                        pdfDoc_{$id}.getPage(num).then(function(page) {
                            var viewport = page.getViewport(scale_{$id});
                            canvas_{$id}.height = viewport.height;
                            canvas_{$id}.width = viewport.width;
                            // Render PDF page into canvas context
                            var renderContext = {
                                canvasContext: ctx_{$id},
                                viewport: viewport
                            };
                            page.render(renderContext);
                        });
                        // Update page counters
                        document.getElementById('wp_pdfjs_page_num_{$id}').textContent = pageNum_{$id};
                        document.getElementById('wp_pdfjs_page_count_{$id}').textContent = pdfDoc_{$id}.numPages;
                        // hide pagination
                        if(pdfDoc_{$id}.numPages <= 1) {
                          jQuery('div#wp_pdfjs_pagination_{$id}').hide();
                        }
                        jQuery('#wp_pdfjs_canvas_container_{$id}')
                          .css( 'width', jQuery('#wp_pdfjs_canvas_{$id}').width() );
                    }
                    
                    function wp_pdfjs_goPrevious_{$id}() {
                        if (pageNum_{$id} <= 1)
                            return;
                        pageNum_{$id}--;
                        wp_pdfj_renderPage_{$id}(pageNum_{$id});
                    }
                    jQuery('#wp_pdfjs_prev_{$id}').click(wp_pdfjs_goPrevious_{$id});
                    
                    function wp_pdfjs_goNext_{$id}() {
                        if (pageNum_{$id} >= pdfDoc_{$id}.numPages)
                            return;
                        pageNum_{$id}++;
                        wp_pdfj_renderPage_{$id}(pageNum_{$id});
                    }
                    jQuery('#wp_pdfjs_next_{$id}').click(wp_pdfjs_goNext_{$id});
                        
                    jQuery('#wp_pdfjs_canvas_{$id}').click(wp_pdfjs_goNext_{$id});

                    PDFJS.getDocument('{$presentation_url}').then(function getPdfHelloWorld(_pdfDoc) {
                        pdfDoc_{$id} = _pdfDoc;
                        wp_pdfj_renderPage_{$id}(pageNum_{$id});
                    });";

    $return_str = "
            <div id='wp_pdfjs_canvas_container_{$id}'>
            		<div id='wp_pdfjs_pagination_{$id}' class='wp_pdfjs_navi'>
                  <center>
                    <!-- DOWNLOAD_LINK -->
                    <a href='javascript:void(0)' id='wp_pdfjs_prev_{$id}' class='btn btn-default'><img src='{$pdfjs_image_url}glyphicons_210_left_arrow.png'/></a>
                    &nbsp; 
                    <small><span id='wp_pdfjs_page_num_{$id}'></span> / <span id='wp_pdfjs_page_count_{$id}'></span></small>
                    &nbsp;
                    <a href='javascript:void(0)' id='wp_pdfjs_next_{$id}'  class='btn btn-default'><img src='{$pdfjs_image_url}glyphicons_211_right_arrow.png'/></a>
                  </center>
								</div>
                <canvas id='wp_pdfjs_canvas_{$id}' style='border:1px solid black'>
                Loading ....
                </canvas>
                <div id='wp_pdfjs_pagination_{$id}' class='wp_pdfjs_navi'>
                  <center>
                    <!-- DOWNLOAD_LINK -->
                    <a href='javascript:void(0)' id='wp_pdfjs_prev_{$id}'><img src='{$pdfjs_image_url}glyphicons_210_left_arrow.png'/></a>
                    &nbsp; 
                    <small><span id='wp_pdfjs_page_num_{$id}'></span> / <span id='wp_pdfjs_page_count_{$id}'></span></small>
                    &nbsp;
                    <a href='javascript:void(0)' id='wp_pdfjs_next_{$id}'><img src='{$pdfjs_image_url}glyphicons_211_right_arrow.png'/></a>
                  </center>
            </div><br/>
            </div>
    <script type='text/javascript'>
        if(typeof PDFJS === 'undefined'){
            jQuery.getScript('{$pdfjs_script_url}',function(){{$render_function}});
        } else {{$render_function}}
</script>";
    
    if($download){
      $return_str = str_replace("<!-- DOWNLOAD_LINK -->", "<a title='Download' alt='Download' href='{$presentation_url}' style='float: left;'><img src='{$pdfjs_image_url}glyphicons_134_inbox_in.png' style='width: 14px; height: 14px;'/></a>", $return_str);
    }

    return $return_str;
}
add_shortcode( 'wp_pdfjs', 'wp_pdfjs_func' );

?>
