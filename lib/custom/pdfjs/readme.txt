=== wp_pdf.js ===
Contributors: hkropp
Tags: pdf, presentation
Requires at least: 3.5
Tested up to: 3.5
Stable tag: 0.2
License: GPLv3 
License URI: http://www.gnu.org/licenses/gpl-3.0.txt

Present PDFs with style.

== Description ==

Publish PDF presentations and documents in your posts.

== Installation ==

1. Install Wordpress pdf.js

= Usage =

Use the include function.

Example usage:
[wp_pdfjs id=189 scale=0.2]

= Options =
* _id_: You can provide an id or url. If you provide an id, than it has to be the Wordpress ID of the document.
* _url_: You can provide an id or url. If you provide an url you have to make sure it is publicly accessible and a direct link to the document.
* _scale_: The scale of the document. Default is set to '1.2'.
* _download_: If a download link should appear or not.

== Changelog ==

= 0.2 =
* Download link of the document.
* Usage of GLYPHICONS icons for navigation http://glyphicons.com/glyphicons-licenses/ 
* Include documents by URL.

= 0.1.1 =
* Bugfix: Compatibility with s2Member plugin http://wordpress.org/support/topic/bug-report-11

= 0.1 =
* Initial release
