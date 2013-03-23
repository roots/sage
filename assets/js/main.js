/* Author:

*/


$(document).ready(function(){
    var options = {
        autoPlay: true,
        autoPlayDelay: 3000,
        cycle: true,
        nextButton: true,
        prevButton: true,
        preloader: true,
        navigationSkipThreshold: 1000,
        fadeFrameWhenSkipped: false
    };
    var sequence = $("#sequence").sequence(options).data("sequence");

    sequence.afterLoaded = function(){
        $(".prev, .next").fadeIn(500);
    }
});