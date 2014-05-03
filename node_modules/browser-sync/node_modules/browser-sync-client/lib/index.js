"use strict";

var socket    = require("./socket");
var shims     = require("./client-shims");
var notify    = require("./notify");
var codeSync  = require("./code-sync");
var ghostMode = require("./ghostmode");
var emitter   = require("./emitter");
var utils     = require("./browser.utils");

/**
 * @constructor
 */
var BrowserSync = function () {
    this.socket  = socket;
    this.emitter = emitter;
    this.utils   = utils.utils;
};

/**
 * Helper to check if syncing is allowed
 * @param data
 * @returns {boolean}
 */
BrowserSync.prototype.canSync = function (data) {
    return data.url === window.location.pathname;
};

var bs;

/**
 * @param opts
 */
exports.init = function (opts) {

    bs      = new BrowserSync();
    bs.opts = opts;

    if (opts.notify) {
        notify.init(bs);
        notify.flash("Connected to BrowserSync :)");
    }

    if (opts.ghostMode) {
        ghostMode.init(bs);
    }

    if (opts.codeSync) {
        codeSync.init(bs);
    }
};

socket.on("connection", exports.init);

/**debug:start**/
if (window.__karma__) {
    window.__bs_scroll__     = require("./ghostmode.scroll");
    window.__bs_clicks__     = require("./ghostmode.clicks");
    window.__bs_location__   = require("./ghostmode.location");
    window.__bs_inputs__     = require("./ghostmode.forms.input");
    window.__bs_toggles__    = require("./ghostmode.forms.toggles");
    window.__bs_submit__     = require("./ghostmode.forms.submit");
    window.__bs_forms__      = require("./ghostmode.forms");
    window.__bs_utils__      = require("./browser.utils");
    window.__bs_emitter__    = emitter;
    window.__bs_notify__     = notify;
    window.__bs_code_sync__  = codeSync;
    window.__bs_ghost_mode__ = ghostMode;
    window.__bs_socket__     = socket;
    window.__bs_index__      = exports;
}
/**debug:end**/