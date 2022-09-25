#!/bin/bash

# Simple build script for building app to production
# environment. Just like i said, this is very simple
# and needs to be updated soon.

AUTHOR=${USER}
DATE=$(date +%Y-%m-%d_%H:%M:%S)

echo "Build date: ${DATE}"
echo "Author: ${AUTHOR}"
echo "Entering ./frontend and checking for updates"

cd ./frontend && npm update

echo "Updating API URL"

sed -i 's/http:\/\/127.0.0.1:8000\/api/http:\/\/api.dpcsppibersatuptp.online\/api/g' ./src/main.js

echo "Running npm run build"

npm run build

echo "Creating .htaccess"

tee -a dist/.htaccess <<EOF
<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteBase /
  RewriteRule ^index\.html$ - [L]
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule . /index.html [L]
</IfModule>
EOF

echo "Copying ckeditor to /node_modules"

cp -fr ckeditor/build/* node_modules/@ckeditor/ckeditor5-build-decoupled-document/build/

cd .. && echo "Finished! Have a nice day :D"