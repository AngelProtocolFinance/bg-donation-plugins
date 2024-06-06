#!/bin/sh

mkdir -p ./out; # ensure the output folder exists

echo "🧰 Building plugin for the $1 platform!";

## WORDPRESS
if [ $1 == "wordpress" ]; then
	echo "> removing old plugin zip file";
	rm --interactive=never -f ./out/wordpress.zip;
	echo "> zipping up latest plugin code";
	cd ./src;
	zip -r ../out/wordpress.zip wordpress;
else
	echo "Not a supported platform!";
fi

echo "✨ Build complete! ✨";
