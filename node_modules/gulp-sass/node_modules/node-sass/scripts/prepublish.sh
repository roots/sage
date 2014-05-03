#!/bin/bash

DIRECTORY="../node-sass-binaries"

if [ "$CI" == "true" ]; then
	echo "Skipping prepublish on CI builds";
elif [ -d "$DIRECTORY" ]; then
	echo "Copying binaries to bin"
	cp -R $DIRECTORY/*-v8-* bin/
else
	echo "Skipping bin copy. Please clone https://github.com/andrew/node-sass-binaries for prebuilt binaries";
fi