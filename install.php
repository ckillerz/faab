<?php

# Génère un hash MD5 aléatoire

function mkhash($qtd)
{
    $authorized_cars = 'ABCDEFGHIJKLMOPQRSTUVXWYZ0123456789';
    $authorized_cars_length = strlen($authorized_cars);
    $authorized_cars_length--;

    $mkhash = NULL;
    for($x=1;$x<=$qtd;$x++)
    {
        $mkhash .= substr($authorized_cars,rand(0,$authorized_cars_length),1);
    }

    return $mkhash;
}

# Génère le nom alétoire du fichier de messages :

$USERS_DIR = mkhash(32);
$POSTS_FILE = mkhash(32);

echo "";
echo "USERS_DIR=$USERS_DIR\n\r";
echo "POSTS_FILE=$POSTS_FILE\n\r";
echo "";

# Génère le fichier d configuration à partir des noms générés :

$data = file_get_contents('config.php.in');
$data = str_replace("@USERS_DIR@", $USERS_DIR, $data);
$data = str_replace("@POSTS_FILE", $POSTS_FILE, $data);
file_put_contents('config.php', $data);

# Initialise l'arborescence :

mkdir("data/$USERS_DIR", 775, true);
file_put_contents("data/$USERS_DIR/index.html", "");
file_put_contents("data/$USERS_DIR/index.htm", "");
file_put_contents("data/$USERS_DIR/index.php", "");

file_put_contents("data/index.html", "");
file_put_contents("data/index.htm", "");
file_put_contents("data/index.php", "");

# Initialise l'arborescence :

function set_perms($dir, $perm_dir, $perm_file)
{
    $weeds = array('.', '..', '.git');
    $directories = array_diff(scandir($dir), $weeds);

    foreach($directories as $fd)
    {
        if(is_dir($fd))
            chmod($fd, $perm_dir);
        elseif(is_file($fd))
              chmod($fd, $perm_file);
    }
}
set_perms('.', 755, 644);
set_perms('data', 777, 666);
?>