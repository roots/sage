(function (a) {
    a(function () {
        a(".toggle-nav").on("click", function (b) {
            var c = a(b.currentTarget);
            c.hasClass("open") ? (c.removeClass("open"), a("#megaDrop").slideUp(400)) : (c.addClass("open"), a("#megaDrop").slideDown(400))
        })
    })
})(jQuery);