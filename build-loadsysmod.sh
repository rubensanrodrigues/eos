#!/bin/sh
home_path=`pwd`
cd $home_path/loadsysmod.c/
make
echo "---------------------------------------------------"
echo "Modulo loadsysmod criado com sucesso!"
echo "Para finalizar a instalacao, como root, execute:"
echo "cd loadsysmod.c/"
echo "make install"
echo "---------------------------------------------------"
cd $home_path
