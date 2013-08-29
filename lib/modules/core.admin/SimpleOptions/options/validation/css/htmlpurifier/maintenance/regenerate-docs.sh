#!/bin/bash -e
./compile-doxygen.sh
cd ../docs
scp doxygen.tgz htmlpurifier.org:/home/ezyang/htmlpurifier.org
ssh htmlpurifier.org "cd /home/ezyang/htmlpurifier.org && ./reload-docs.sh"
