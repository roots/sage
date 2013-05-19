#!/bin/sh

# creates tar.gz for current version

VERSION=`./plessc -v | sed -n 's/^v\(.*\)$/\1/p'`
OUT_DIR="tmp/lessphp"
TMP=`dirname $OUT_DIR`

mkdir -p $OUT_DIR
tar -c `git ls-files` | tar -C $OUT_DIR -x

rm $OUT_DIR/.gitignore
rm $OUT_DIR/package.sh
rm $OUT_DIR/lessify
rm $OUT_DIR/lessify.inc.php

OUT_NAME="lessphp-$VERSION.tar.gz"
tar -czf $OUT_NAME -C $TMP lessphp/
echo "Wrote $OUT_NAME"

rm -r $TMP


echo
echo "Don't forget to"
echo "* Update the version in lessc.inc.php (two places)"
echo "* Update the version in the README.md"
echo "* Update the version in docs.md (two places)"
echo "* Update @current_version in site.moon"
echo "* Add entry to feed.moon for changelog"
echo "* Update the -New- area on homepage with date and features"
echo


