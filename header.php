<?php
	ob_start();
	require getcwd() . '/config.php' ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Tribune Web - <?php echo htmlspecialchars($sBoardTitle) ; ?></title>
	<link rel="stylesheet" type="text/css" href="board.css" />
</head>

<body>

<script type="text/javascript" src="board.js"></script>

<div class="header">

	<h1><?php echo htmlspecialchars($sBoardTitle); ?></h1>

<div class="links">
[
<?php
	$sLogin = NULL ;
	if( isset( $_COOKIE[$sCookieName] ) )
	{
		session_name($sCookieName);
		session_set_cookie_params( $iCookieLifetime, $sCookiePath );
		session_start();
		if( isset( $_SESSION['login'] ) )
		{	
			$sLogin = $_SESSION['login'];
		}
	}
	if( $sLogin )
	{
		echo "\tVous êtes <tt>$sLogin</tt>\n" ;
		echo "\t| <a href=\"logoutF.php\">Déconnection</a> \n" ;
	}
	else
	{
		echo "\t<a href=\"loginF.php\">Identification</a>\n" ;
		echo "\t| <a href=\"newF.php\">Créer un compte</a>\n" ;
	}
?>
]
<br />
[
	<a href="index.php" title="Afficher la tribune">Tribune</a>
	|
	<a href="about.php" title="Information sur cette tribune">À propos ...</a> 
]
</div>

</div>
