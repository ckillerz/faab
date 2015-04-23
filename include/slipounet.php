<?php /* vim: set ts=4 sw=4 noet encoding=utf-8 : */

	/* string str_ctrl_clean( string $s )
	 * 
	 * Replace each ASCII control character of $s with space and trim() the result.
	 */
	
	function str_ctrl_clean( $s )
	{
		$iLen = strlen( $s );
		$iByte = 0 ;

		for( $i=0 ; $i<$iLen ; $i++ )
		{
			$iByte = ord( $s[$i] );
			if( ( $iByte < 32 )||( $iByte == 127 ) )
			{
				$s[$i] = ' ' ;
			}
		}
		return trim( $s ) ;
	}

	/* string str_clean( string $s, int $iLen )
	 * 
	 * 1) Ensure $s is a properly UTF-8 encoded string
	 * 2) Replace each ASCII control character with space and trim() the result
	 * 3) Return at most the $iLen first UTF-8 characters of $s
	 */

	function str_clean( $s, $iLen )
	{
		if( strcasecmp( mb_detect_encoding( $s, 'UTF-8', TRUE ), 'UTF-8' ) == 0 )
		{
			return mb_substr( str_ctrl_clean($s), 0, $iLen, 'UTF-8' );
		}
		return NULL ;
	}

	/* string slipounet( string $sMessage )
	 *
	 * This function parses <b>, <u>, <i>, <s> and <tt> HTML tags and 
	 * converts URLs to <a> tags in $sMessage. It returns a string where 
	 * useless or wrong tags are htmlentities()'ed. It is rock-solid
	 * because I'm an expert in titanium and adamantium.
	 *
	 * NOTE : it also convert &lt; &gt; and &amp; to <, > and &. 
	 */

	function slipounet( $sMessage, $iLen )
	{

		$sMessage = str_clean( $sMessage, $iLen );
		if( $sMessage == NULL )
		{
			return NULL ;
		}
		
		$sMessage = preg_replace(
			array(
				'/<[ ]*(\/?)[ ]*(b|i|s|tt|u)[ ]*>/i',
				'/(ftp|http|ftps|https|file):\/\/([^ ]+)/i'
			),
			array(
				chr(10) . '<\1\2>'  . chr(10),
				chr(10) . '\1://\2' . chr(10) 
			),
			$sMessage
		);
		$sMessage = str_replace(
			array(
				'&lt;',
				'&gt;',
				'&amp;'
			),
			array(
				chr(27) .  'lt' . chr(28),
				chr(27) .  'gt' . chr(28),
				chr(27) . 'amp' . chr(28)
			),
			$sMessage
		);

		$aWords = explode( chr(10), $sMessage );
		$iMax   = count( $aWords );
		$aTags  = array();

		$sMessage = "" ;
		for( $i=0 ; $i<$iMax ; $i++ )
		{
			$sWord = $aWords[$i];
			$iLen = strlen($sWord);
			if( $iLen == 0 )
			{
				continue ;
			}

			if( $sWord[0] == '<' )
			{
				if( preg_match( '/<(b|i|s|tt|u)>/i', $sWord ) )
				{
					$sOpenTag = strtolower( substr( $sWord, 1, strlen($sWord)-2 ) );
					array_push( $aTags, $sOpenTag );
					$sMessage = $sMessage . strtolower($sWord) ;
					continue ;
				}

				if( preg_match( '/<\/(b|i|s|tt|u)>/i', $sWord ) )
				{
					$sOpenTag = array_pop( $aTags );
					$sCloseTag = strtolower( substr( $sWord, 2, strlen($sWord)-3 ) );
					if( $sOpenTag )
					{
						if( $sOpenTag==$sCloseTag )
						{
							$sMessage .= strtolower($sWord) ;
						}
						else
						{
							$sMessage = $sMessage . '&lt;/' . $sCloseTag . '&gt;' ;
							array_push( $aTags, $sOpenTag );
						}
					}
					else
					{
						$sMessage = $sMessage . '&lt;/' . $sCloseTag . '&gt;' ;
					}
					continue ;
				}
			}

			if(( strncmp( $sWord, 'http://', 7 )  == 0 )
			 ||( strncmp( $sWord, 'https://', 8 ) == 0 )
			 ||( strncmp( $sWord, 'ftp://', 6 )   == 0 )
			 ||( strncmp( $sWord, 'file:///', 8 ) == 0 ))
			{
				$sMessage = $sMessage . '<a href="' . htmlspecialchars($sWord) . '">[url]</a>' ;
				continue ;
			}
		
			$sMessage = $sMessage . htmlspecialchars( $sWord );
		}

		while( $sTag = array_pop( $aTags ) )
		{
			$sMessage = $sMessage . '</' . $sTag . '>' ;
		}

		$sMessage = str_replace(
			array( chr(27), chr(28) ),
			array( '&',     ';' ),
			$sMessage
		);

		return $sMessage ;
	}

?>
