[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](README.md)

# Theme templates

### comments.php

The comments template wraps each comment in an `<article>` and the vCard microformat is used for comment author information. 

### content.php

The `content.php` template is included by archive templates in the theme root.

### content-page.php

The `content-page.php` template is included by page templates in the theme root.

### content-single.php

The `content-single.php` template is included by single post templates in the theme root.

### footer.php

`footer.php` includes the Footer sidebar area and displays the site copyright information.

### head.php

`head.php` includes everything in the `<head>`.

### header.php

`header.php` is used if the Bootstrap navbar isn't enabled in `lib/config.php`. The `wp_nav_menu()` outputs with a `nav-pills` class for some basic styling from Bootstrap.

### header-top-navbar.php

`header-top-navbar.php` is used if the Bootstrap navbar is enabled in `lib/config.php`.

### page-header.php

`page-header.php` is included at the top of files in the theme root to display the `<h1>` on pages before the page content.

### searchform.php

`searchform.php` is the template used when `get_search_form()` is called.

### sidebar.php

`sidebar.php` includes the Primary Sidebar.