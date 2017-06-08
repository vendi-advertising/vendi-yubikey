#!/bin/bash

#Get into the bin directory regardless of where we're being called from
cd "$(dirname "$0")"

#Download a markdown convertor
if [ ! -f vendor/bin/wp2md ]; then
    composer require wpreadme2markdown/wpreadme2markdown --dev
fi

#Convert readme to markdown
./vendor/bin/wp2md convert < readme.txt > README.md

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
