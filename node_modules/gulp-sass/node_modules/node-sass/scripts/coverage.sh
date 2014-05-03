#!/bin/bash

./node_modules/.bin/jscoverage sass.js sass-coverage.js
./node_modules/.bin/jscoverage lib lib-coverage

NODESASS_COVERAGE=1 ./node_modules/.bin/mocha test -R mocha-lcov-reporter | ./node_modules/coveralls/bin/coveralls.js