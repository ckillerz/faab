<?php /* vim: set ts=4 sw=4 noet encoding=utf-8 : */

	/*********************
	 * PRIVATE FUNCTIONS *
	 *********************/

	function _FileWLock( $sFile )
	{
		$rLock = fopen( $sFile . '.lock' ,'w' );
		if( $rLock ) 
		{
			if( flock( $rLock, LOCK_EX ) )
			{
				return $rLock ;
			}
		}
		return FALSE ;
	}

	function _FileRLock( $sFile )
	{
		$rLock = fopen( $sFile . '.lock' ,'w' );
		if( $rLock ) 
		{
			if( flock( $rLock, LOCK_SH ) )
			{
				return $rLock ;
			}
		}
		return FALSE ;
	}

	function _FileUnlock( $rLock )
	{
		if( $rLock )
		{
			flock( $rLock, LOCK_UN );
			fclose( $rLock );
		}	
	}

	function _FileWrite( $sFile, $sData )
	{
		$rLock = _FileWLock( $sFile );
		if( !$rLock ) 
		{
			return FALSE ;
		}

		$rFile = fopen( $sFile, 'wb' );
		if( !$rFile )
		{
			_FileUnlock($rLock);
			return FALSE ;
		}

		$iSize = strlen($sData);
		$bRetVal = (( fwrite( $rFile, $sData, $iSize ) == $iSize )&& fflush($rFile) );

		
		fclose( $rFile );
		_FileUnlock( $rLock );
		return $bRetVal ;
	}

	function _FileRead( $sFile )
	{
		if( is_file( $sFile ) == FALSE )
		{
			return FALSE ;
		}
		
		$iSize = filesize( $sFile );
		if( !$iSize )
		{
			return FALSE ;
		}

		$rLock = _FileRLock( $sFile );
		if( !$rLock ) 
		{
			return FALSE ;
		}

		$rFile = fopen( $sFile, 'rb' );
		if( !$rFile ) {
			_FileUnlock($rLock);
			return FALSE ;
		}
		
		$sData = fread( $rFile, $iSize );
		fclose( $rFile );
		_FileUnlock( $rLock );

		if(( is_string( $sData) == FALSE )||( strlen($sData) != $iSize ))
		{
			return FALSE ;
		}

		return $sData ;
	}

	/********************
	 * PUBLIC FUNCTIONS *
	 ********************/

	function FileReadRaw( $sFile )
	{
		return _FileRead( $sFile ) ;
	}

	function FileWriteRaw( $sFile, $sData )
	{
		return _FileWrite( $sFile, $sData) ;
	}

	function FileWrite( $sFile, $sData )
	{
		if( is_string( $sData ) == TRUE )
		{
			$aContent        = array();
			$aContent['str'] = $sData ;
			$aContent['len'] = strlen( $sData );
			$aContent['md5'] = md5( $sData );
			return _FileWrite( $sFile, serialize($aContent) ) ;
		}
		return FALSE ;
	}

	function FileRead( $sFile )
	{
		$sContent = _FileRead($sFile);
		if( is_string($sContent) == FALSE )
		{
			return FALSE ;
		}

		$aContent = unserialize($sContent);
		if(
		   ( is_array($aContent) == FALSE ) ||
		   ( array_key_exists( 'str', $aContent ) == FALSE ) ||
		   ( array_key_exists( 'len', $aContent ) == FALSE ) ||
		   ( array_key_exists( 'md5', $aContent ) == FALSE ) )
		{
			return FALSE ;
		}

		$sData = $aContent['str'];
		$iLen  = $aContent['len'];
		$sMD5  = $aContent['md5'];

		if(
		   ( is_string( $sData ) == FALSE ) ||
		   ( strlen( $sData ) != $iLen ) ||
		   ( md5( $sData ) != $sMD5 ) )
		{
			return FALSE ; 
		}

		return $sData ;
	}

?>
