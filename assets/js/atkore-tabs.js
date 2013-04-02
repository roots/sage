// allows direct link to bootstrap tab item https://github.com/twitter/bootstrap/issues/2415

$(function () {
   var activeTab = $('[href=' + location.hash + ']');
   activeTab && activeTab.tab('show');
});