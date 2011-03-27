<?php  

function roots_get_home_path() {
	$home = get_option( 'home' );
	$siteurl = get_option( 'siteurl' );
	if ( $home != '' && $home != $siteurl ) {
	        $wp_path_rel_to_home = str_replace($home, '', $siteurl); /* $siteurl - $home */
	        $pos = strpos($_SERVER["SCRIPT_FILENAME"], $wp_path_rel_to_home);
	        $home_path = substr($_SERVER["SCRIPT_FILENAME"], 0, $pos);
		$home_path = trailingslashit( $home_path );
	} else {
		$home_path = ABSPATH;
	}
	return $home_path;
}

$home_path = roots_get_home_path();

if (!is_writable($home_path . '.htaccess')) {
	add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Please make sure your <a href="%s">htaccess</a> file is writeable ', 'roots'), admin_url('options-permalink.php')) . "</p></div>';"));
};

// thanks to Scott Walkinshaw (scottwalkinshaw.com)

function roots_add_htaccess($rules) {

	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n# Better website experience for IE users";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n";
	$rules .= "\n# Force the latest IE version, in various cases when it may fall back to IE7 mode";
	$rules .= "\n#  github.com/rails/rails/commit/123eb25#commitcomment-118920";
	$rules .= "\n# Use ChromeFrame if it's installed for a better experience for the poor IE folk";
	$rules .= "\n";
	$rules .= "\n<IfModule mod_setenvif.c>";
  	$rules .= "\n<IfModule mod_headers.c>";
    $rules .= "\nBrowserMatch MSIE ie";
    $rules .= "\nHeader set X-UA-Compatible \"IE=Edge,chrome=1\" env=ie";
  	$rules .= "\n</IfModule>";
	$rules .= "\n</IfModule>";
	$rules .= "\n";
	$rules .= "\n<IfModule mod_headers.c>";
	$rules .= "\n# Because X-UA-Compatible isn't sent to non-IE (to save header bytes),";
	$rules .= "\n#   We need to inform proxies that content changes based on UA";
  	$rules .= "\nHeader append Vary User-Agent";
	$rules .= "\n# Cache control is set only if mod_headers is enabled, so that's unncessary to declare";
	$rules .= "\n</IfModule>";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n# Cross-domain AJAX requests";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n";
	$rules .= "\n# Serve cross-domain ajax requests, disabled.   ";
	$rules .= "\n# enable-cors.org";
	$rules .= "\n# code.google.com/p/html5security/wiki/CrossOriginRequestSecurity";
	$rules .= "\n";
	$rules .= "\n#  <IfModule mod_headers.c>";
	$rules .= "\n#    Header set Access-Control-Allow-Origin "*"";
	$rules .= "\n#  </IfModule>";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n# Webfont access";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n";
	$rules .= "\n# allow access from all domains for webfonts";
	$rules .= "\n# alternatively you could only whitelist";
	$rules .= "\n#   your subdomains like \"sub.domain.com\"";
	$rules .= "\n";
	$rules .= "\n<FilesMatch \"\.(ttf|otf|eot|woff|font.css)$\">";
  	$rules .= "\n<IfModule mod_headers.c>";
    $rules .= "\nHeader set Access-Control-Allow-Origin "*"";
  	$rules .= "\n</IfModule>";
	$rules .= "\n</FilesMatch>";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n# Proper MIME type for all files";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n";
	$rules .= "\n# audio";
	$rules .= "\nAddType audio/ogg                      oga ogg";
	$rules .= "\n";
	$rules .= "\n# video";
	$rules .= "\nAddType video/ogg                      ogv";
	$rules .= "\nAddType video/mp4                      mp4";
	$rules .= "\nAddType video/webm                     webm";
	$rules .= "\n";
	$rules .= "\n# Proper svg serving. Required for svg webfonts on iPad";
	$rules .= "\n#   twitter.com/FontSquirrel/status/14855840545";
	$rules .= "\nAddType     image/svg+xml              svg svgz ";
	$rules .= "\nAddEncoding gzip                       svgz";
	$rules .= "\n                                       ";
	$rules .= "\n# webfonts                             ";
	$rules .= "\nAddType application/vnd.ms-fontobject  eot";
	$rules .= "\nAddType font/truetype                  ttf";
	$rules .= "\nAddType font/opentype                  otf";
	$rules .= "\nAddType application/x-font-woff        woff";
	$rules .= "\n";
	$rules .= "\n# assorted types                                      ";
	$rules .= "\nAddType image/x-icon                   ico";
	$rules .= "\nAddType image/webp                     webp";
	$rules .= "\nAddType text/cache-manifest            appcache manifest";
	$rules .= "\nAddType text/x-component               htc";
	$rules .= "\nAddType application/x-chrome-extension crx";
	$rules .= "\nAddType application/x-xpinstall        xpi";
	$rules .= "\nAddType application/octet-stream       safariextz";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n# gzip compression";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n";
	$rules .= "\n<IfModule mod_deflate.c>";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n# force deflate for mangled headers developer.yahoo.com/blogs/ydn/posts/2010/12/pushing-beyond-gzipping/";
	$rules .= "\n<IfModule mod_setenvif.c>";
  	$rules .= "\n<IfModule mod_headers.c>";
    $rules .= "\nSetEnvIfNoCase ^(Accept-EncodXng|X-cept-Encoding|X{15}|~{15}|-{15})$ ^((gzip|deflate)\s,?\s(gzip|deflate)?|X{4,13}|~{4,13}|-{4,13})$ HAVE_Accept-Encoding";
    $rules .= "\nRequestHeader append Accept-Encoding \"gzip,deflate\" env=HAVE_Accept-Encoding";
  	$rules .= "\n</IfModule>";
	$rules .= "\n</IfModule>";
	$rules .= "\n# html, txt, css, js, json, xml, htc:";
	$rules .= "\n<IfModule filter_module>";
  	$rules .= "\nFilterDeclare   COMPRESS";
  	$rules .= "\nFilterProvider  COMPRESS  DEFLATE resp=Content-Type /text/(html|css|javascript|plain|x(ml|-component))/";
  	$rules .= "\nFilterProvider  COMPRESS  DEFLATE resp=Content-Type /application/(javascript|json|xml|x-javascript)/";
  	$rules .= "\nFilterChain     COMPRESS";
  	$rules .= "\nFilterProtocol  COMPRESS  change=yes;byteranges=no";
	$rules .= "\n</IfModule>";
	$rules .= "\n";
	$rules .= "\n<IfModule !mod_filter.c>";
  	$rules .= "\n# Legacy versions of Apache";
  	$rules .= "\nAddOutputFilterByType DEFLATE text/html text/plain text/css application/json";
  	$rules .= "\nAddOutputFilterByType DEFLATE text/javascript application/javascript application/x-javascript ";
  	$rules .= "\nAddOutputFilterByType DEFLATE text/xml application/xml text/x-component";
	$rules .= "\n</IfModule>";
	$rules .= "\n";
	$rules .= "\n# webfonts and svg:";
  	$rules .= "\n<FilesMatch \"\.(ttf|otf|eot|svg)$\" >";
    $rules .= "\nSetOutputFilter DEFLATE";
  	$rules .= "\n</FilesMatch>";
	$rules .= "\n</IfModule>";
	$rules .= "\n";
	$rules .= "\n";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n# UTF-8 encoding";
	$rules .= "\n# ----------------------------------------------------------------------";
	$rules .= "\n";
	$rules .= "\n# use utf-8 encoding for anything served text/plain or text/html";
	$rules .= "\nAddDefaultCharset utf-8";
	$rules .= "\n";
	$rules .= "\n# force utf-8 for a number of file formats";
	$rules .= "\nAddCharset utf-8 .html .css .js .xml .json .rss";
	$rules .= "\n";
	
	return $rules;
}

add_action('mod_rewrite_rules', 'roots_add_htaccess');
?>