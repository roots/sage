[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](TOC.md)

# Rewrites

Rewrites are handled by `lib/rewrites.php`. Rewrites currently do not happen for child themes or network installs.

Rewrite:

1. `/wp-content/themes/themename/assets/css/` to `/assets/css/`
2. `/wp-content/themes/themename/assets/js/` to `/assets/js/`
3. `/wp-content/themes/themename/assets/img/` to `/assets/img/`
4. `/wp-content/plugins/` -> `/plugins/`

If HTML5 Boilerplate's `.htaccess` support is enabled in `lib/config.php`, then the `generate_rewrite_rules()` filter is used to automatically add the contents of `lib/h5bp-htaccess` to your `.htaccess` file.

## Alternative server configurations

### Nginx

    location ~ ^/assets/(img|js|css)/(.*)$ {
      try_files $uri $uri/ /wp-content/themes/roots/assets/$1/$2;
    }
    location ~ ^/plugins/(.*)$ {
      try_files $uri $uri/ /wp-content/plugins/$1;
    }

### Lighttpd

This defines if your WP is in a subfolder or not.
var.wpdir = "/"

This handles the custom assets/plugins directory calls.
url.rewrite-once = ("^" + wpdir + "(assets)\/.*/?" => "/wp-content/themes/roots/$0",
                    "^" + wpdir + "(plugins)\/.*/?" => "/wp-content/$0");

And this is the "standard" wordpress setup handling wordpress rewrites properly. Plese note the rewrite-once += bit of it cause its what makes the above addendum work.
url.rewrite-once += ("^" + wpdir + "(wp-.+).*/?" => "$0",
                     "^" + wpdir + "(sitemap.xml)" => "$0",
                     "^" + wpdir + "(xmlrpc.php)" => "$0",
                     "^" + wpdir + "keyword/([A-Za-z_0-9-])/?$" => wpdir + "index.php?keyword=$1",
                     "^" + wpdir + "(.+)/?$" => wpdir + "index.php/$1") 
