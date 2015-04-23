<?php
	require getcwd() . '/config.php' ;

	if(( ! isset( $_POST['action'] ) )||( strcasecmp( $_POST['action'], 'logout' ) != 0 ))
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : Requête incorrecte.' );
	}

	if( isset( $_COOKIE[$sCookieName] ) )
	{
		session_name( $sCookieName );
		session_set_cookie_params( $iCookieLifetime, $sCookiePath );
		session_start();
		$_SESSION = array();
		setcookie( $sCookieName, '', 0, $sCookiePath );
	}
	session_destroy();
	header( 'Location: index.php' );
?>
