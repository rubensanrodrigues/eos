#!/bin/sh
home_path=`pwd`
SH=$(command -v sh);
$SH $home_path/build-jar-file.sh
$SH $home_path/build-getstat.sh
$SH $home_path/build-loadsys.sh
$SH $home_path/build-loadsysmod.sh
