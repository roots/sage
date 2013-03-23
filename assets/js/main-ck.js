/* Author:

*/$(document).ready(function(){var e={autoPlay:!0,autoPlayDelay:3e3,cycle:!0,nextButton:!0,prevButton:!0,preloader:!0,navigationSkipThreshold:1e3,fadeFrameWhenSkipped:!1},t=$("#sequence").sequence(e).data("sequence");t.afterLoaded=function(){$(".prev, .next").fadeIn(500)}});