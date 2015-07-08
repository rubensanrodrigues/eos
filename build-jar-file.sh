#!/bin/sh
mainClass="MemoryAnalizer"
homePath=`pwd`
cd $homePath/MemoryAnalizer/
javac -d bin/ -sourcepath src/  src/MemoryAnalizer.java
cd $homePath/MemoryAnalizer/bin/
touch Manifest.txt
echo "Main-Class: $mainClass" > Manifest.txt
jar -cfm $homePath/bin/MemoryAnalizer.jar Manifest.txt .
cd $homePath
