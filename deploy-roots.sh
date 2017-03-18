#!/bin/bash
#
# This is a simple deploy script to create a .zip file of your theme
# The first argument passed becomes the .zip filename.
echo "Building site for packaging..."
grunt build
echo "Begin zipping..."
FILE="roots-deploy-theme.zip"
if [ ! -z "$1" ]
    then
    FILE=$1
else
    echo "No argument given, using default name: $FILE"
fi
 
if [ -r "$FILE" ]
    then
    echo "Cleaning up last deploy file..."
    rm "$FILE"
fi
 
echo "Zipping theme contents into file: $FILE"
zip -r "$FILE" . -x node_modules\* *.zip deploy-roots.sh .git\*
echo "Done!"

