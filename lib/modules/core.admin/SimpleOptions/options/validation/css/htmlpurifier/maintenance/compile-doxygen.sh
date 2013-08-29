#!/bin/bash
cd ..
mkdir docs/doxygen
rm -Rf docs/doxygen/*
doxygen 1>docs/doxygen/info.log 2>docs/doxygen/errors.log
if [ "$?" != 0 ]; then
    cat docs/doxygen/errors.log
    exit
fi
cd docs
tar czf doxygen.tgz doxygen
