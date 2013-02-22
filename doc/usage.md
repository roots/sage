[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](TOC.md)

# Usage

The HTML, CSS and JavaScript in Roots comes from a combination of HTML5 Boilerplate and Twitter Bootstrap.

* [HTML5 Boilerplate documentation](https://github.com/h5bp/html5-boilerplate/blob/master/README.md)
* [Twitter Bootstrap documentation](http://twitter.github.com/bootstrap/getting-started.html)

## Basic structure

A basic Roots theme initially looks like this:

```
.
├── assets
│   ├── css
│   │   ├── less
│   │   │   │── bootstrap.less
│   │   │   └── responsive.less
│   │   │── app.css
│   │   │── bootstrap.css
│   │   │── bootstrap-responsive.css
│   │   │── editor-style.css
│   ├── img
│   └── js
│       ├── main.js
│       ├── plugins.js (includes bootstrap.js)
│       └── vendor
│           ├── jquery-1.9.1.min.js
│           └── modernizr-2.6.2.min.js
├── doc
├── lang
│   └── roots.pot
├── lib
│   ├── activation.php
│   ├── cleanup.php
│   ├── config.php
│   ├── custom.php
│   ├── h5bp-htaccess
│   ├── htaccess.php
│   ├── init.php
│   ├── nav.php
│   ├── rewrites.php
│   ├── scripts.php
│   ├── sidebar.php
│   ├── utils.php
│   └── widgets.php
├── templates
│   ├── comments.php
│   ├── content.php
│   ├── content-page.php
│   ├── content-single.php
│   ├── entry-meta.php
│   ├── footer.php
│   ├── head.php
│   ├── header.php
│   ├── header-top-navbar.php
│   ├── page-header.php
│   ├── searchform.php
│   └── sidebar.php
├── 404.php
├── base.php
├── functions.php
├── index.php
├── page.php
├── page-custom.php
├── screenshot.png
├── single.php
└── style.css
```

What follows is a general overview of each major part and how to use them.

### assets/css/

This directory should contain all your project's CSS files. If you're using LESS, you should have `less/bootstrap.less` compile to `css/bootstrap.css`, and `less/responsive.less` compile to `css/bootstrap-responsive.css`. Any additional LESS files that aren't from Bootstrap should compile to `css/app.css`.

### assets/img/

This directory should contain all your project's image files.

### assets/js/

This directory should contain all your project's JS files. Libraries, plugins,
and custom code can all be included here. It includes some initial JS to help
get you started.

The files and directory structure are adopted from [HTML5 Boilerplate's JavaScript](https://github.com/h5bp/html5-boilerplate/blob/master/doc/js.md).

### doc/

This directory contains all the Roots documentation. You can use it
as the location and basis for your own project's documentation.

### lang/

This directory contains all of the theme translations. [About translating the theme](http://www.icanlocalize.com/site/tutorials/how-to-translate-with-gettext-po-and-pot-files/).

### lib/

This directory contains all of the theme functionality. [About the theme library](lib.md).

### templates/

This directory contains all of the theme templates. [About the templates](templates.md).


### 404.php

A helpful custom 404 to get you started.

### base.php

This is the default HTML skeleton that forms the basis of all pages on
your site. [About the theme wrapper](wrapper.md).

### functions.php

This file loads all of the [theme library](lib.md) files, sets up the default navigation menus, and adds support for post thumbnails.

### index.php

This file is used to serve all of the archive templates.

### page.php

This file is used to serve the page template.

### page-custom.php

An example of a custom page template. By default, this page is full width and doesn't contain a sidebar as defined in `lib/config.php`'s `roots_sidebar()` function.

### single.php

This file is used to serve the single post template.

### style.css

This file is used to tell WordPress that we're a theme. None of the actual CSS is contained in this file.
