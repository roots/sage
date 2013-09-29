/* Author:

*/
$(document).ready(function(){
    function changeText(id, text){
        $("#sequence ul li:nth-child("+id+")").children().children(".sequence-class").text(text);
    }

    var options = {
        autoPlay: true,
        autoPlayDelay: 8000,
        pauseOnHover: false,
        cycle: true,
        nextButton: true,
        prevButton: true,
        preloader: '.sequence-preloader',
        navigationSkip: true,
        navigationSkipThreshold: 250,
        fadeFrameWhenSkipped: true,
        preloadTheseFrames: [1,2,3],
        transitionThreshold: 250,
        reverseAnimationsWhenNavigatingBackwards: true,
        hidePreloaderUsingCSS: true,
        hideFramesUntilPreloaded: false,
        hidePreloaderDelay: 150
    };

    var optionsProducts = {
        autoPlay: true,
        autoPlayDelay: 4000,
        pauseOnHover: true,
        cycle: true,
        nextButton: false,
        prevButton: false,
        transitionThreshold: 250
    };

    var sequence = $("#sequence").sequence(options).data("sequence"); //initiate Sequence
    var sequenceProducts = $("#sequence-featured-product").sequence(optionsProducts).data("sequence"); //initiate Sequence


    sequence.beforeCurrentFrameAnimatesIn = function(){
        if(sequence.direction === 1){
            changeText(sequence.nextFrameID, "");
        }else{
            changeText(sequence.nextFrameID, ".animate-out");
        }
    },

    sequence.beforeCurrentFrameAnimatesOut = function(){
        if(sequence.direction === 1){
            changeText(sequence.currentFrameID, ".animate-out");
            changeText(sequence.nextFrameID, "");
        }else{
            changeText(sequence.currentFrameID, "");
            changeText(sequence.nextFrameID, ".animate-out");
        }
    },

    sequenceProducts.beforeNextFrameAnimatesIn = function(){
        changeText(sequence.nextFrameID, ".animate-in");
    };

    sequenceProducts.beforeCurrentFrameAnimatesIn = function(){
        if(sequence.direction === 1){
            changeText(sequence.nextFrameID, "");
        }else{
            changeText(sequence.nextFrameID, ".animate-out");
        }
    },

    sequenceProducts.beforeCurrentFrameAnimatesOut = function(){
        if(sequence.direction === 1){
            changeText(sequence.currentFrameID, ".animate-out");
            changeText(sequence.nextFrameID, "");
        }else{
            changeText(sequence.currentFrameID, "");
            changeText(sequence.nextFrameID, ".animate-out");
        }
    },

    sequenceProducts.beforeNextFrameAnimatesIn = function(){
        changeText(sequence.nextFrameID, ".animate-in");
    };
});