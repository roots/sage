#!/bin/bash

# dependencies
JSHINT_DIR="node_modules/jshint/bin/jshint"
MOCHA_DIR="node_modules/mocha/bin/mocha"
UGLIFY_DIR="node_modules/uglify-js/bin/uglifyjs"

# check module directory & install if not found
function check_module {
    if ! type $2 &> /dev/null
    then        
        echo "$2 is not found"
        if ! type "npm" &> /dev/null
        then
            echo "npm is not installed"
            echo "Exiting"
            exit 1
        else
            echo "Installing $1..."
            npm install $1
        fi
    fi
}

check_module "jshint" $JSHINT_DIR
check_module "mocha" $MOCHA_DIR
check_module "uglify-js" $UGLIFY_DIR

echo "Verifiying code..."
$JSHINT_DIR src/ua-parser.js

echo "Running test..."
$MOCHA_DIR -R nyan test/test.js

echo "Minifying script..."
$UGLIFY_DIR src/ua-parser.js > src/ua-parser.min.js
echo "OK"
