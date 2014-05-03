var compile = require("./index").compile;
var compileWithPrexfix = require("./index").getCompiler("[PREFIX]");

var string;
var multiLine;

// Single line, single colour
var singleLine = "{green:This is a green string}";
console.log(compile(singleLine));

// Single Line, multi colour
var multiColor = "{green:This is a green string} with a {red:red string} inside";
console.log(compile(multiColor));

// With prefix & Multiline
var prefixed = [];
prefixed.push("This is line 1");
prefixed.push("This is line 2");
console.log(compileWithPrexfix(prefixed));

// With prefix, multiline & colors
prefixed = [];
prefixed.push("{green:This is line 1 in GREEN}");
prefixed.push("This is line 2 with no colour");
prefixed.push("{red:This is line 3 in RED}");
console.log(compileWithPrexfix(prefixed));