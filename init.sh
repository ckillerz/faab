#!/bin/bash

set -e -x

# Génère un hash MD5 aléatoire :

mkhash ()
{
	(
		dd if=/dev/urandom bs=1024 count=1 2>/dev/null
		date
		dmesg
	) | md5sum | sha1sum | cut -f 1 -d " "

}

# Génère le nom alétoire du dossier utilisateur :

USERS_DIR=$(mkhash)

# Génère le nom alétoire du fichier de messages :

POSTS_FILE=$(mkhash)

echo ""
echo "USERS_DIR=$USERS_DIR"
echo "POSTS_FILE="$POSTS_FILE""
echo ""

# Génère le fichier d configuration à partir des noms générés :

sed \
	-e "s,@USERS_DIR@,$USERS_DIR,g" \
	-e "s,@POSTS_FILE@,$POSTS_FILE,g" < config.php.in > config.php

# Initialise l'arborescence :

install -d -m 775 "data/$USERS_DIR"
echo -n >         "data/$USERS_DIR/index.html"
echo -n >         "data/$USERS_DIR/index.php"
echo -n >         "data/$USERS_DIR/index.htm"

echo -n > "data/index.html"
echo -n > "data/index.php"
echo -n > "data/index.htm"

find .    -type f | xargs chmod 644
find .    -type d | xargs chmod 755
find data -type d | xargs chmod 777
find data -type f | xargs chmod 666

exit 0


