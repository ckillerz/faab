<?php

	require getcwd() . '/config.php' ;
	require getcwd() . '/include/file.php' ;
	require getcwd() . '/include/slipounet.php' ;

	if( function_exists( 'date_default_timezone_set') )
	{
		date_default_timezone_set( 'Europe/Paris' );
	}

	$iId         = 1 ;
	$iIp         = ip2long( $_SERVER['REMOTE_ADDR'] );
	$sTime       = strftime( '%Y%m%d%H%M%S', time() );
	$sUserAgent  = '' ;
	$sLogin      = '' ;
	$sMessage    = '' ;

	/*
	 * Lecture et vérification du message
	 */

	if( ! isset( $_POST['message' ] ) )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : Veuillez vérifier la configuration de votre client.' );
	}

	if( strlen( trim($_POST['message']) ) == 0 )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur : Message vide, tu as mal au bide.' );
	}

	$sMessage = $_POST['message'];
	if( get_magic_quotes_gpc() )
	{
		$sMessage = stripslashes( $sMessage );
	}

	/*
	 * Lecture et vérification du User-Agent :
	 */

	if( isset( $_SERVER['HTTP_USER_AGENT'] ) &&( strlen( $_SERVER['HTTP_USER_AGENT'] ) > 0 ))
	{
		$sUserAgent = $_SERVER['HTTP_USER_AGENT'] ;
		if( get_magic_quotes_gpc() )
		{
			$sUserAgent = stripslashes( $sUserAgent );
		}
		$sUserAgent = str_clean( $sUserAgent, 80 );
		if( $sUserAgent == NULL )
		{
			header( 'HTTP/1.1 400 Bad Request' );
			header( 'Content-Type: text/plain ; charset=UTF-8' );
			exit( 'Erreur : Votre User-Agent n\'est pas proprement codé en UTF-8.' );
		}
	}

	/*
	 * Lecture et vérification du cookie de session :
	 */

	if( isset( $_COOKIE[$sCookieName] ) )
	{
		session_name( $sCookieName );
		session_set_cookie_params( $iCookieLifetime, $sCookiePath );
		session_start();
		if( isset( $_SESSION['login'] ) )
		{
			$sLogin = $_SESSION['login'];
		}
	}
	else
	{
		if( ! $bAllowAnonymous )
		{
			header( 'HTTP/1.1 403 Forbidden' );
			header( 'Content-Type: text/plain ; charset=UTF-8' );
			exit( 'Erreur : Les messages anonymes sont interdits par la loi.' );
		}	
	}

	ignore_user_abort( TRUE );

	/*
	 * Lecture des posts :
	 */

	$aPosts = array();
	if( is_file( $sPostsFile ) )
	{
		$sContent = FileRead( $sPostsFile );
		if( $sContent )
		{
			$aPosts = unserialize( $sContent );
			if( !is_array($aPosts) )
			{
				$aPosts = array();
			}
		}
	}	

	/* On calcul l'id du nouveau post à partir de celui du dernier : */

	if( count($aPosts) > 0 )
	{
		krsort( $aPosts, SORT_NUMERIC );
		reset( $aPosts );
		list( $iId, $aPost) = each( $aPosts );
		$sLastIp = $aPost['ip'] ;
		$iId = $aPost['id'] + 1 ;
	
		if( $sLastIp == $iIp )
		{
			$sLastTime = $aPost['time'];
			$iLastTime = mktime(
				substr( $sLastTime, 8, 2 ), substr( $sLastTime, 10, 2 ), substr( $sLastTime, 12, 2 ),
				substr( $sLastTime, 4, 2 ), substr( $sLastTime, 6, 2),   substr( $sLastTime, 0, 4)
			);
			/*if( ( time() - $iLastTime ) < 10 )
			{
				header( 'HTTP/1.1 403 Forbidden' );
				header( 'Content-Type: text/plain ; charset=UTF-8' );
				exit( 'Erreur : Vous ne pouvez pas poster 2 messages en moins de 10 secondes.' );
			}*/
		}	
	}

	$sMessage = slipounet( $sMessage, $iMsgLen );
	if( $sMessage == NULL )
	{
		header( 'HTTP/1.1 400 Bad Request' );
		header( 'Content-Type: text/plain ; charset=UTF-8' );
		exit( 'Erreur: Votre message n\'est pas proprement codé en UTF-8.' );
	}

	$aPost                 = array();
	$aPost['id'] = $iNewId = $iId ;
	$aPost['time']         = $sTime ;
	$aPost['login']        = $sLogin ;
	$aPost['user_agent']   = $sUserAgent ;
	$aPost['ip']           = $iIp ;
	$aPost['message']      = $sMessage ;

	$aPosts[$iId] = $aPost ;
	krsort( $aPosts, SORT_NUMERIC );
	while( count($aPosts) > $iBoardSize )
	{
		array_pop($aPosts);
	}

	$sHtml = '' ;
	$sXml = '<?xml version="1.0" encoding="UTF-8"?>'
	     . "<board site=\"" . $_SERVER['SERVER_NAME'] . "\">" ;

	foreach( $aPosts as $iId => $aPost )
	{
		$sTime    = $aPost['time'];
		$sYear    = substr( $sTime, 0, 4 );
		$sMonth   = substr( $sTime, 4, 2 );
		$sDay     = substr( $sTime, 6, 2 );
		$sHour    = substr( $sTime, 8, 2 );
		$sMin     = substr( $sTime, 10, 2 );
		$sSec     = substr( $sTime, 12, 2);
		$sLogin   = $aPost['login'];
		$sUserAgent = htmlspecialchars($aPost['user_agent']);
		$sMessage = $aPost['message'];

		$sHtmlMessage = preg_replace(
			'/([0-9]{2}):([0-9]{2}):?([0-9]{2})?/',
			'<span class="timeref" '
			. 'onmouseout="h(\'\1\2\3\')" '
			. 'onmouseover="s(\'\1\2\3\')">\0</span>',
			$sMessage
		);   

		$sHtml = $sHtml
		. "\t<div id=\"i$sHour$sMin$sSec\" class=\"post\">\n"
		. "\t\t<span class=\"time\" title=\"$sDay/$sMonth/$sYear\" "
		. "onclick=\"a('$sHour:$sMin:$sSec')\" "
		. "onmouseover=\"s('$sHour$sMin$sSec')\" "
		. "onmouseout=\"h('$sHour$sMin$sSec')\" "
		. ">$sHour:$sMin:$sSec</span>\n" ;

		if( strlen($sLogin) > 0 ) {
			$sHtml = $sHtml . "\t\t<span class=\"login\" title=\"$sUserAgent\">&lt;$sLogin&gt;</span>\n" ;
		} else {
			$sUA = mb_substr( $sUserAgent, 0, 10, 'UTF-8' );
			$sHtml = $sHtml . "\t\t<span class=\"user-agent\" title=\"$sUserAgent\"><i>($sUA)</i></span>\n" ;
		}
		$sHtml = $sHtml
		. "\t\t<span class=\"message\">$sHtmlMessage</span>\n"
		. "\t</div>\n" ;

		$sXml = $sXml . "<post time=\"$sTime\" id=\"$iId\">"
		. "<info>$sUserAgent</info>"
		. "<message>$sMessage</message>"
		. "<login>$sLogin</login>"
		. "</post>" ;
	} 
	$sXml = $sXml . '</board>' ;

	if( ! FileWriteRaw ( $sHtmlFile,  $sHtml ) )
	{
		header( 'HTTP/1.1 500 Internal Server Error' );
		header( 'Content-Type: text/plain; charset=UTF-8' );
		exit( 'Erreur : Enregistrement du backend HTML impossible.' );
	}

	if( ! FileWriteRaw( $sXmlFile,   $sXml ) )
	{
		header( 'HTTP/1.1 500 Internal Server Error' );
		header( 'Content-Type: text/plain; charset=UTF-8' );
		exit( 'Erreur : Enregistrement du backend XML impossible.' );
	}

	if( ! FileWrite( $sPostsFile, serialize($aPosts) ) )
	{
		header( 'HTTP/1.1 500 Internal Server Error' );
		header( 'Content-Type: text/plain; charset=UTF-8' );
		exit( 'Erreur : Enregistrement des messages impossible.' );
	}

	header( 'HTTP/1.1 302 Found' );
	header( 'X-Post-Id: ' . $iNewId );
	header( 'Location: index.php' );
?>
