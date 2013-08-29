
MODx Plugin

MODx <http://www.modxcms.com/> is an open source PHP application framework.
I first came across them in my referrer logs when tillda asked if anyone
could implement an HTML Purifier plugin.  This forum thread
<http://modxcms.com/forums/index.php/topic,6604.0.html> eventually resulted
in the fruition of this plugin that davidm says, "is on top of my favorite
list."  HTML Purifier goes great with WYSIWYG editors!



1. Credits

PaulGregory wrote the overall structure of the code.  I added the
slashes hack.



2. Install

First, you need to place HTML Purifier library somewhere.  The code here
assumes that you've placed in MODx's assets/plugins/htmlpurifier (no version
number).

Log into the manager, and navigate:

Resources > Manage Resources > Plugins tab > New Plugin

Type in a name (probably HTML Purifier), and copy paste this code into the
textarea:

--------------------------------------------------------------------------------
$e = &$modx->Event;
if ($e->name == 'OnBeforeDocFormSave') {
    global $content;

    include_once '../assets/plugins/htmlpurifier/library/HTMLPurifier.auto.php';
    $purifier = new HTMLPurifier();

    static $magic_quotes = null;
    if ($magic_quotes === null) {
        // this is an ugly hack because this hook hasn't
        // had the backslashes removed yet when magic_quotes_gpc is on,
        // but HTMLPurifier must not have the quotes slashed.
        $magic_quotes = get_magic_quotes_gpc();
    }

    if ($magic_quotes) $content = stripslashes($content);
    $content = $purifier->purify($content);
    if ($magic_quotes) $content = addslashes($content);
}
--------------------------------------------------------------------------------

Then navigate to the System Events tab and check "OnBeforeDocFormSave".
Save the plugin.  HTML Purifier now is integrated!



3. Making sure it works

You can test HTML Purifier by deliberately putting in crappy HTML and seeing
whether or not it gets fixed.  A better way is to put in something like this:

<p lang="fr">Il est bon</p>

...and seeing whether or not the content comes out as:

<p lang="fr" xml:lang="fr">Il est bon</p>

(lang to xml:lang synchronization is one of the many features HTML Purifier
has).



4. Caveat Emptor

This code does not intercept save requests from the QuickEdit plugin, this may
be added in a later version.  It also modifies things on save, so there's a
slight chance that HTML Purifier may make a boo-boo and accidently mess things
up (the original version is not saved).

Finally, make sure that MODx is using UTF-8.  If you are using, say, a French
localisation, you may be using Latin-1, if that's the case, configure
HTML Purifier properly like this:

$config = HTMLPurifier_Config::createDefault();
$config->set('Core', 'Encoding', 'ISO-8859-1'); // or whatever encoding
$purifier = new HTMLPurifier($config);



5. Known Bugs

'rn' characters sometimes mysteriously appear after purification. We are
currently investigating this issue. See: <http://htmlpurifier.org/phorum/read.php?3,1866>



6. See Also

A modified version of Jot 1.1.3 is available, which integrates with HTML
Purifier. You can check it out here: <http://modxcms.com/forums/index.php/topic,25621.msg161970.html>


X. Changelog

2008-06-16
- Updated code to work with 3.1.0 and later
- Add Known Bugs and See Also section

    vim: et sw=4 sts=4
