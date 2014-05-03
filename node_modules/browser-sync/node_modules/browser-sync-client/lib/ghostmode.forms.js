"use strict";

exports.plugins = {
    "inputs":  require("./ghostmode.forms.input"),
    "toggles": require("./ghostmode.forms.toggles"),
    "submit":  require("./ghostmode.forms.submit")
};

/**
 * Load plugins for enabled options
 * @param bs
 */
exports.init = function (bs, eventManager) {

    var checkOpt = true;
    var opts = bs.opts.ghostMode.forms;

    if (opts === true) {
        checkOpt = false;
    }

    function init(name) {
        exports.plugins[name].init(bs, eventManager);
    }

    for (var name in exports.plugins) {
        if (!checkOpt) {
            init(name);
        } else {
            if (opts[name]) {
                init(name);
            }
        }
    }
};