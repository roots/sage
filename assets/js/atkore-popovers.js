$(document).ready(function() {
  $('.well-reps').tooltip();

  $('.brandpopover').popover({
        html: true,
        trigger: 'hover'
  });
  $('.gallery .thumbnail').click(function (e) {
    e.preventDefault()
    });
  $('.gallery .thumbnail').popover({
        html: true,
        trigger: 'hover'
  })
});