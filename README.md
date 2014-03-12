![Cole Advertising & Design](http://www.cole-ad.co.uk/graphics/cole_home_web.jpg "Cole Advertising & Design")

roots-gulp
=========
***

What?
--
roots-gulp is a port of [Roots](https://github.com/roots/roots) by [Ben Word](http://roots.io/author/benword/) to [Gulp](http://gulpjs.com), the streaming build system. 

Why?
--
To address performance issues with [Grunt](http://gruntjs.com) in the original version of Roots. Gulp has proven itself to be almost always quicker off the mark after a few weeks of regular use.

Who?
--
Built at [Cole Advertising & Design](http://cole-ad.co.uk) for internal use

***
Uhhh.. how do I work this?
--
roots-gulp has a few pre-requisites. Before you can make use of the awesome build system, you need to install [Node.js](http://nodejs.org/) on your development machine.

Once you have Node installed, we need a global installation of Gulp. This can be installed with `sudo npm install -g gulp`.

Now you need to install the modules we're using for the build process with `npm install`. This reads your dependencies from `package.json` and installs them all.

From here, we can now run `gulp watch` and let Gulp handle all of our boring compilation and minification for us. Yay.