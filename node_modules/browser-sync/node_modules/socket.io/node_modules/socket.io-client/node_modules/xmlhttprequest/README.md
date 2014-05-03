# node-XMLHttpRequest #

node-XMLHttpRequest is arapper for the built-in http client to emulate the browser XMLHttpRequest object.

This can be used with JS designed for browsers to improve reuse of code and allow the use of existing libraries.

## Usage ##
Here's how to include the module in your project and use as the browser-based XHR object.

	var XMLHttpRequest = require("XMLHttpRequest").XMLHttpRequest;
	var xhr = new XMLHttpRequest();

Refer to W3C specs for XHR methods.

## TODO ##

* Add basic authentication
* Additional unit tests
* Possibly move from http to tcp for more flexibility
