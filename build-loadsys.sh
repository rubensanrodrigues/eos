#!/bin/sh
home_path=`pwd`
cd $home_path/loadsys.c/
make
cp loadsys $home_path/bin/
cd $home_path
