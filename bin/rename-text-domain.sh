#!/usr/bin/env bash

if [ $# -lt 1 ]; then
    echo "usage: $0 <new-slug>"
    exit 1
fi

NEW_SLUG="'$1'"
ORIGINAL_SLUG="'sage'"

echo "Searching for files containing ${ORIGINAL_SLUG}..."
git grep -lw ${ORIGINAL_SLUG} -- './*.php' ':!/app/lib/' | xargs sed -i "s/$ORIGINAL_SLUG/$NEW_SLUG/g"
