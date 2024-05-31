#!/bin/sh 

mkdir -p ./out # ensure the out file exists
echo "🧰 Running plugin build for the $1 platform!";

## WORDPRESS
if [ $1 == "wordpress" ]; then
	zip -r ./out/wordpress.zip ./src/wordpress
else
	echo "Not a supported platform.";
fi

## WIX
## SQAURESPACE
## DRUPAL

echo "✨ Build complete! ✨";
