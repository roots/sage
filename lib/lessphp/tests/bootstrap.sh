#!/bin/sh

echo "This script clones Twitter Bootstrap, compiles it with lessc and lessphp,"
echo "cleans up results with sort.php, and outputs diff. To run it, you need to"
echo "have git and lessc installed."
echo ""

if [ -z "$input" ]; then
  input="bootstrap/less/bootstrap.less"
fi
dest=$(basename "$input")
dest="${dest%.*}"

if [ -z "$@" ]; then
  diff_tool="diff -b -u -t -B"
else
  diff_tool=$@
fi

mkdir -p tmp

if [ ! -d 'bootstrap/' ]; then
  echo ">> Cloning bootstrap to bootstrap/"
  git clone https://github.com/twitter/bootstrap
fi

echo ">> lessc compilation ($input)"
lessc "$input" "tmp/$dest.lessc.css"

echo ">> lessphp compilation ($input)"
../plessc "$input" "tmp/$dest.lessphp.css"
echo ">> Cleanup and convert"

php sort.php "tmp/$dest.lessc.css" > "tmp/$dest.lessc.clean.css"
php sort.php "tmp/$dest.lessphp.css" > "tmp/$dest.lessphp.clean.css"

echo ">> Doing diff"
$diff_tool "tmp/$dest.lessc.clean.css" "tmp/$dest.lessphp.clean.css"
