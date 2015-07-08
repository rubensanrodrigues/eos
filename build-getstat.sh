#!/bin/sh
home_path=`pwd`
cd $home_path/getstats.c/
make
cp getstats $home_path/bin/
cd $home_path
