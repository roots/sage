/* ========================================================
 * bootstrap-progressbar v0.5.0
 * ========================================================
 * Copyright 2012 minddust.com
 *
 * bootstrap-progressbar is published under Apache License,
 * Version 2.0 (see LICENSE file).
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 * ======================================================== */

(function($) {

    "use strict";

    /* PROGRESSBAR CLASS DEFINITION
     * ============================ */

    var Progressbar = function (element, options) {
        this.element = $(element);
        this.options = $.extend({}, $.fn.progressbar.defaults, options);
    };

    Progressbar.prototype = {

        constructor: Progressbar,

        transition: function() {
            var $this = this.element,
                $parent = $this.parent(),
                $back = this.back,
                $front = this.front,
                options = this.options,
                percentage = $this.attr('data-percentage'),
                amount_part = $this.attr('data-amount-part'),
                amount_total = $this.attr('data-amount-total'),
                is_vertical,
                update,
                done,
                fail;

            is_vertical = $parent.hasClass('vertical');

            update = (options.update && typeof(options.update) === 'function') ? options.update : $.fn.progressbar.defaults.update;
            done = (options.done && typeof(options.done) === 'function') ? options.done : $.fn.progressbar.defaults.done;
            fail = (options.fail && typeof(options.fail) === 'function') ? options.fail : $.fn.progressbar.defaults.fail;

            if (options.use_percentage && !percentage) {
                fail("bootstrap-progressbar: you can't use percentage without data-percentage being set");
                return;
            }
            else if (!options.use_percentage) {
                if (!amount_part && !amount_total) {
                    fail("bootstrap-progressbar: you can't use values without data-amount-part and data-amount-total being set");
                    return;
                }
                else {
                    percentage = Math.round(100 * amount_part / amount_total);
                }
            }

            if (options.display_text === $.fn.progressbar.display_text.center && !$front && !$back) {
                this.back = $back = $('<span>').addClass('progressbar-back-text');
                this.front = $front = $('<span>').addClass('progressbar-front-text');

                $parent.prepend($back);
                $this.prepend($front);

                var parent_size;

                if (is_vertical) {
                    parent_size = $parent.css('height');
                    $back.css('height', parent_size);
                    $back.css('line-height', parent_size);
                    $front.css('height', parent_size);
                    $front.css('line-height', parent_size);

                    $(window).resize(function() {
                        parent_size = $parent.css('height');
                        $back.css('height', parent_size);
                        $back.css('line-height', parent_size);
                        $front.css('height', parent_size);
                        $front.css('line-height', parent_size);
                    }); // normal resizing would brick the structure because width is in px
                }
                else {
                    parent_size = $parent.css('width');
                    $front.css('width', parent_size);

                    $(window).resize(function() {
                        parent_size = $parent.css('width');
                        $front.css('width', parent_size);
                    }); // normal resizing would brick the structure because width is in px
                }
            }

            setTimeout(function() {
                var current_percentage,
                    current_value,
                    this_size,
                    parent_size,
                    text;

                if (is_vertical) {
                    $this.css('height', percentage+'%');
                }
                else {
                    $this.css('width', percentage+'%');
                }

                var progress = setInterval(function() {
                    if (is_vertical) {
                        this_size = $this.height();
                        parent_size = $parent.height();
                    }
                    else {
                        this_size = $this.width();
                        parent_size = $parent.width();
                    }

                    current_percentage = Math.round(100 * this_size / parent_size);
                    current_value = Math.round(this_size / parent_size * amount_total);

                    if (current_percentage >= percentage) {
                        current_percentage = percentage;
                        current_value = amount_part;
                        done();
                        clearInterval(progress);
                    }

                    if (options.display_text !== $.fn.progressbar.display_text.none) {
                        text = options.use_percentage ? (current_percentage +'%') : (current_value + ' / ' + amount_total);

                        if (options.display_text === $.fn.progressbar.display_text.filled){
                            $this.text(text);
                        }
                        else if (options.display_text === $.fn.progressbar.display_text.center) {
                            $back.text(text);
                            $front.text(text);
                        }
                    }

                    update(current_percentage);
                }, options.refresh_speed);
            }, options.transition_delay);
        }
    };

    /* PROGRESSBAR PLUGIN DEFINITION
     * ============================= */

    $.fn.progressbar = function (option) {
        return this.each(function () {
            var $this = $(this),
                data = $this.data('progressbar'),
                options = typeof option === 'object' && option;
            if (!data) {
                $this.data('progressbar', (data = new Progressbar(this, options)));
            }
            if (typeof option === 'string') {
                data[option]();
            }
            data.transition();
        });
    };

    $.fn.progressbar.display_text = {
        none: 0,
        filled: 1,
        center: 2
    };

    $.fn.progressbar.defaults = {
        transition_delay: 300,
        refresh_speed: 50,
        display_text: $.fn.progressbar.display_text.none,
        use_percentage: true,
        update: $.noop,
        done: $.noop,
        fail: $.noop
    };

    $.fn.progressbar.Constructor = Progressbar;

})(window.jQuery);