$(document).ready(function() {

  $('.nav-tabs a:first').tab('show');
  
  $('.selectpicker').selectpicker('');

  $('.collapse').collapse();

  $('.atkore-popover').popover({
    trigger:  'hover',
    html:     true,
  });

  $('#carousel-home').carousel({
    interval: 8000
  });

  $('#carousel-products').carousel({
    interval: 8000
  });
  
  $('#carousel-brands').carousel({
    interval: false
  });

  $('li.menu-newsletter').find('a').each(function () {
   $(this).attr('href', '#newsletter');
   $(this).attr('data-target', '#newsletter');
   $(this).attr('data-toggle', 'modal');
  });

  $('a.pdfbutton').each(function () {
   $(this).attr('class', 'list-group-item');
  });
  
  $('.tab-pane .right-side').find('img').each(function () {
   $(this).attr('class', 'img-thumbnail');
   $(this).attr('align', 'right');
  });
  
  $('#overview.tab-pane').find('img').each(function () {
   $(this).attr('class', 'img-thumbnail');
   $(this).attr('align', 'right');
  });


  $('.tagged_as a').each(function () {
   $(this).attr('class', 'label label-primary');
  });
  
  
  $("table").tablecloth({
  theme: "default",
  bordered: true,
  condensed: true,
  striped: true,
  sortable: false,
  clean: true,
  cleanElements: "th td",
  customClass: "atkore-table table-hover"
});

   var activeTab = $('[href=' + location.hash + ']');
   activeTab && activeTab.tab('show');

});


