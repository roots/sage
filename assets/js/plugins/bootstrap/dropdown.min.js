/* ========================================================================
 * Bootstrap: dropdown.js v3.0.0
 * http://twbs.github.com/bootstrap/javascript.html#dropdowns
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
 * ======================================================================== */+function(e){"use strict";function i(){e(t).remove();e(n).each(function(t){var n=s(e(this));if(!n.hasClass("open"))return;n.trigger(t=e.Event("hide.bs.dropdown"));if(t.isDefaultPrevented())return;n.removeClass("open").trigger("hidden.bs.dropdown")})}function s(t){var n=t.attr("data-target");if(!n){n=t.attr("href");n=n&&/#/.test(n)&&n.replace(/.*(?=#[^\s]*$)/,"")}var r=n&&e(n);return r&&r.length?r:t.parent()}var t=".dropdown-backdrop",n="[data-toggle=dropdown]",r=function(t){var n=e(t).on("click.bs.dropdown",this.toggle)};r.prototype.toggle=function(t){var n=e(this);if(n.is(".disabled, :disabled"))return;var r=s(n),o=r.hasClass("open");i();if(!o){"ontouchstart"in document.documentElement&&!r.closest(".navbar-nav").length&&e('<div class="dropdown-backdrop"/>').insertAfter(e(this)).on("click",i);r.trigger(t=e.Event("show.bs.dropdown"));if(t.isDefaultPrevented())return;r.toggleClass("open").trigger("shown.bs.dropdown");n.focus()}return!1};r.prototype.keydown=function(t){if(!/(38|40|27)/.test(t.keyCode))return;var r=e(this);t.preventDefault();t.stopPropagation();if(r.is(".disabled, :disabled"))return;var i=s(r),o=i.hasClass("open");if(!o||o&&t.keyCode==27){t.which==27&&i.find(n).focus();return r.click()}var u=e("[role=menu] li:not(.divider):visible a",i);if(!u.length)return;var a=u.index(u.filter(":focus"));t.keyCode==38&&a>0&&a--;t.keyCode==40&&a<u.length-1&&a++;~a||(a=0);u.eq(a).focus()};var o=e.fn.dropdown;e.fn.dropdown=function(t){return this.each(function(){var n=e(this),i=n.data("dropdown");i||n.data("dropdown",i=new r(this));typeof t=="string"&&i[t].call(n)})};e.fn.dropdown.Constructor=r;e.fn.dropdown.noConflict=function(){e.fn.dropdown=o;return this};e(document).on("click.bs.dropdown.data-api",i).on("click.bs.dropdown.data-api",".dropdown form",function(e){e.stopPropagation()}).on("click.bs.dropdown.data-api",n,r.prototype.toggle).on("keydown.bs.dropdown.data-api",n+", [role=menu]",r.prototype.keydown)}(window.jQuery);