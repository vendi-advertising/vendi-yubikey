#!/bin/bash

cd "$(dirname "$0")"
cd ..

#Make bin dir if needed
if [ ! -d bin/ ]; then
    mkdir bin
fi

#Download a markdown convertor
if [ ! -f bin/wp2md ]; then
    wget https://github.com/wpreadme2markdown/wp-readme-to-markdown/releases/download/2.0.2/wp2md.phar -O bin/wp2md
    chmod a+x bin/wp2md
fi

#Convert readme to markdown
./bin/wp2md convert < readme.txt > README.md

#Look for the wordpress test dir stuff
if [ ! -d ~/wordpress-i18n-tools/ ]; then

    #Jump to folder
    pushd ~
    mkdir wordpress-i18n-tools
    cd wordpress-i18n-tools

    #Checkout tools and trunk
    svn co http://develop.svn.wordpress.org/trunk/tools/
    svn co http://develop.svn.wordpress.org/trunk/src/

    #Jump back
    popd
fi

#Make languages dir if needed
if [ ! -d languages/ ]; then
    mkdir languages
fi

#Translate
php ~/wordpress-i18n-tools/tools/i18n/makepot.php wp-plugin . languages/vendi-yubikey.pot

#Jump back
#popd
