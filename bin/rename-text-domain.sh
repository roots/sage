#!/usr/bin/env bash

if [ $# -lt 2 ]; then
    echo "usage: $0 <new-name> <new-slug>"
    exit 1
fi

NEW_NAME=$1
NEW_SLUG=$2

echo "Searching for files containing ${NEW_SLUG}..."
git grep -lw ${NEW_SLUG} -- './*.php' './*.js' './resources/assets/config.json' ':!/app/lib/'