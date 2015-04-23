<?php

	require getcwd() . '/config.php'      ;
	require getcwd() . '/include/file.php' ;

	if(( ! isset( $_POST['login'] ) )
	 ||( ! isset( $_POST['password1'] ) )
	 ||( ! isset( $_POST['password2'] ) ) 
	 ||( ! isset( $_POST['action'] ) )
	 ||( strcasecmp( $_POST['action'], 'Envoyer' ) != 0 ))
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : requête incomplète' );
	}

	$sLogin     = $_POST['login' ] ;
	$sPassword1 = $_POST['password1'] ;
	$sPassword2 = $_POST['password2'] ;

	if( ! ereg( '^[a-zA-Z0-9]{3,15}$', $sLogin ) )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( "Erreur : format de nom d'utilisateur incorrect" );
	}

	$sFile = $sUsersDir . '/' . strtolower($sLogin) . '.dat' ;
	if( is_file($sFile) )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( "Erreur : utilisateur existant" );
	}

	if( ! ereg( '^[a-zA-Z0-9]{8,32}$', $sPassword1 ) || ! ereg( '^[a-zA-Z0-9]{8,32}$', $sPassword2 ) )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( "Erreur: format de mot de passe incorrect" );
	}

	if( strcmp( $sPassword1, $sPassword2 ) != 0 )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( "Erreur: les mots de passe ne correspondent pas" );
	}

	$aUser = array();
	$aUser['login'] = $sLogin ;
	$aUser['hash']  = md5( md5( $sPassword1 ) );
	$aUser['time']  = time();
	$aUser['ip']    = ip2long( $_SERVER['REMOTE_ADDR'] );
	
	ignore_user_abort( TRUE );

	if( ! FileWrite( $sFile, serialize($aUser) ) )
	{
		header( 'HTTP/1.1 500 Internel Server Error' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( "Erreur: création du nouveau compte impossible" );
	}

	session_name( $sCookieName );
	session_set_cookie_params( $iCookieLifetime, $sCookiePath );
	session_start();
	$_SESSION['login'] = $sLogin ;

	header( 'HTTP/1.1 200 OK' );
	header( 'Location: index.php' );
	exit( "Votre compte $sLogin a été créé avec succès" );

?>