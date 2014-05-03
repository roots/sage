var strings = require("../index.js");
var chalk   = require("chalk");
var stripColor = chalk.stripColor;
var assert = require("chai").assert;

// Compiler with prefx
var compile = strings.getCompiler("{green:[BS]}");

describe("Returning the compile function", function () {
    it("can return a function", function () {
        assert.isFunction(compile);
    });
    it("can compile with a prefix", function () {
        var actual = stripColor(compile("kittie"));
        var expected = "[BS] kittie";
        assert.equal(actual, expected);
    });
    it("can compile multiple lines with prefix (1)", function () {
        var actual = stripColor(compile(["kittie", "shane"]));
        var expected = "[BS] kittie\n[BS] shane";
        assert.equal(actual, expected);
    });
    it("can compile multiple lines with prefix (2)", function () {
        var actual = stripColor(compile(["{green:kittie}", "shane"]));
        var expected = "[BS] kittie\n[BS] shane";
        assert.equal(actual, expected);
    });
});
