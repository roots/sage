[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](README.md)

# Rewrites

Rewrites are handled by `lib/htaccess.php`. Rewrites currently do not happen for child themes or network installs.

Rewrite:

1. `/wp-content/themes/themename/assets/css/` to `/assets/css/`
2. `/wp-content/themes/themename/assets/js/` to `/assets/js/`
3. `/wp-content/themes/themename/assets/img/` to `/assets/img/`
4. `/wp-content/plugins/` -> `/plugins/`

If HTML5 Boilerplate's `.htaccess` support is enabled in `lib/config.php`, then the `generate_rewrite_rules()` filter is used to automatically add the contents of `lib/h5bp-htaccess` to your `.htaccess` file.

## Alternative configuration

First remove the `if` statement that wraps everything, since if you're not on Apache or Litespeed then Roots will not apply the functionality.

### Nginx

    if (!-e $request_filename) {
      rewrite ^/assets/css/(.*)$ /wp-content/themes/roots/assets/css/$1 last;
      rewrite ^/assets/js/(.*)$ /wp-content/themes/roots/assets/js/$1 last;
      rewrite ^/assets/img/(.*)$ /wp-content/themes/roots/assets/img/$1 last;
      rewrite ^/plugins/(.*)$ /wp-content/plugins/$1 last;
      break;
    }
   
### Lighttpd

    url.rewrite-once = (
      "^/css/(.*)$" => "/wp-content/themes/roots/css/$1",
      "^/js/(.*)$" => "/wp-content/themes/roots/js/$1",
      "^/img/(.*)$" => "/wp-content/themes/roots/img/$1",
      "^/plugins/(.*)$" => "/wp-content/plugins/$1"
    )