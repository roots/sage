[Roots Theme homepage](http://www.rootstheme.com/) | [Documentation
table of contents](TOC.md)

# Rewrites

Rewrites are handled by `lib/rewrites.php`. Rewrites currently do not happen for child themes or network installs.

Rewrite:

1. `/wp-content/themes/themename/assets/css/` to `/assets/css/`
2. `/wp-content/themes/themename/assets/js/` to `/assets/js/`
3. `/wp-content/themes/themename/assets/img/` to `/assets/img/`
4. `/wp-content/plugins/` -> `/plugins/`

## Alternative server configurations

### Nginx

Include these in your Nginx config, before the PHP fastcgi block (`location ~ \.php$`).

    location ~ ^/assets/(img|js|css)/(.*)$ {
      try_files $uri $uri/ /wp-content/themes/roots/assets/$1/$2;
    }
    location ~ ^/plugins/(.*)$ {
      try_files $uri $uri/ /wp-content/plugins/$1;
    }

### Lighttpd

    url.rewrite-once = (
      "^/css/(.*)$" => "/wp-content/themes/roots/css/$1",
      "^/js/(.*)$" => "/wp-content/themes/roots/js/$1",
      "^/img/(.*)$" => "/wp-content/themes/roots/img/$1",
      "^/plugins/(.*)$" => "/wp-content/plugins/$1"
    )
