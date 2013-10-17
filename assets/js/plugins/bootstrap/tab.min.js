/* ========================================================================
 * Bootstrap: tab.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#tabs
 * ========================================================================
 * Copyright 2012 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ======================================================================== */+function(e){"use strict";var t=function(t){this.element=e(t)};t.prototype.show=function(){var t=this.element,n=t.closest("ul:not(.dropdown-menu)"),r=t.attr("data-target");if(!r){r=t.attr("href");r=r&&r.replace(/.*(?=#[^\s]*$)/,"")}if(t.parent("li").hasClass("active"))return;var i=n.find(".active:last a")[0],s=e.Event("show.bs.tab",{relatedTarget:i});t.trigger(s);if(s.isDefaultPrevented())return;var o=e(r);this.activate(t.parent("li"),n);this.activate(o,o.parent(),function(){t.trigger({type:"shown.bs.tab",relatedTarget:i})})};t.prototype.activate=function(t,n,r){function o(){i.removeClass("active").find("> .dropdown-menu > .active").removeClass("active");t.addClass("active");if(s){t[0].offsetWidth;t.addClass("in")}else t.removeClass("fade");t.parent(".dropdown-menu")&&t.closest("li.dropdown").addClass("active");r&&r()}var i=n.find("> .active"),s=r&&e.support.transition&&i.hasClass("fade");s?i.one(e.support.transition.end,o).emulateTransitionEnd(150):o();i.removeClass("in")};var n=e.fn.tab;e.fn.tab=function(n){return this.each(function(){var r=e(this),i=r.data("bs.tab");i||r.data("bs.tab",i=new t(this));typeof n=="string"&&i[n]()})};e.fn.tab.Constructor=t;e.fn.tab.noConflict=function(){e.fn.tab=n;return this};e(document).on("click.bs.tab.data-api",'[data-toggle="tab"], [data-toggle="pill"]',function(t){t.preventDefault();e(this).tab("show")})}(window.jQuery);