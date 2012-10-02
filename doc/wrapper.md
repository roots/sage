[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](TOC.md)

# Theme wrapper

The theme wrapper functionality is found in `lib/utils.php`. This code comes directly from [scribu's theme wrapper](http://scribu.net/wordpress/theme-wrappers.html) post.

`base.php` is used to serve all of the templates for your site. In the theme root, the following files are only used to include files in the `templates/` directory, which contains all of the [theme templates](templates.md):

1. `index.php` (archive page templates) includes `templates/content.php`
2. `page.php` includes `templates/content-page.php`
3. `single.php` includes `templates/content-single.php`

The [Template Hierarchy](http://codex.wordpress.org/Template_Hierarchy) is traversed as normal before the wrapper is loaded.