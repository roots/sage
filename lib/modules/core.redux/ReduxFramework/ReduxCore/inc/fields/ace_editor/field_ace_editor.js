/*global jQuery, document*/

jQuery(document).ready(function () {

  jQuery('.ace-editor').each(function(index, element){
      

      var area = element;
      var editor = jQuery(element).attr('data-editor');
      
      var aceeditor = ace.edit(editor);
      aceeditor.setTheme("ace/theme/"  + jQuery(element).attr('data-theme'));
      aceeditor.getSession().setMode("ace/mode/" + jQuery(element).attr('data-mode'));
      
      aceeditor.on('change', function(e){
          jQuery('#'+area.id).val(aceeditor.getSession().getValue());
          redux_change(jQuery(element));
      });
      
  });
  
});