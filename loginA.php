<?php

	if(( ! isset( $_POST['login'] ))
	 ||( ! isset( $_POST['password'] ))
	 ||( ! isset( $_POST['action'] ))
	 ||( strcasecmp( $_POST['action'], 'login' ) != 0 ))
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : requête incorrecte.' );
	}

	require getcwd() . '/config.php'       ;
	require getcwd() . '/include/file.php' ;

	$sLogin = $_POST['login'];
	$sFile = $sUsersDir . '/' . $sLogin . '.dat' ;
	if( !ereg( '^[a-z0-9]{3,15}$', $sLogin ) || !is_file($sFile) )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : Nom d\'utilisateur incorrect.' );
	}

	$sContent = FileRead($sFile);
	$aUser = @unserialize( $sContent );
	if(( $sContent === FALSE )||( $aUser === FALSE ))
	{
		header( 'HTTP/1.1 500 Internal Server Error' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : La lecture du compte a échoué.' );
	}

	$sFormPassword = $_POST['password'];
	if(( ! ereg( '^[a-z0-9]{5,15}$', $sFormPassword ) )
	 ||( strcmp( $aUser['hash'], md5( md5( $sFormPassword ) ) )!=0 ) )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : Mot de passe incorrect.' );
	}

	session_name( $sCookieName );
	session_set_cookie_params( $iCookieLifetime, $sCookiePath );
	session_start();
	$_SESSION['login'] = $sLogin ;

	header( 'Location: index.php' );
?>
