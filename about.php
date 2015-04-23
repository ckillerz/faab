<?php
	require getcwd() . '/header.php' ;
	
	$sBackendURL = 'http://'
		. $_SERVER['SERVER_NAME']
		. ':'
		. $_SERVER['SERVER_PORT']
		. dirname($_SERVER['PHP_SELF'])
		. '/' . $sXmlFile ;
	$sPostURL = 'http://'
		. $_SERVER['SERVER_NAME']
		. ':'
		. $_SERVER['SERVER_PORT']
		. dirname($_SERVER['PHP_SELF'])
		. '/add.php' ; 
	
?>

<div class="content">
	<h2>Paramètrage de wmc²</h2>
	<p class="content">
		Pour utiliser cette tribune dans
		<a
			href="http://hules.free.fr/wmcoincoin"
			title="WMCoinCoin"
		>wmc²</a>,
		ajoutez les lignes suivantes dans le fichier
		<tt>.wmcoincoin/options</tt> de votre
		dossier personnel&nbsp;:
	</p>
	<pre class="content">
		board_site:                <?php echo $sBoardName . "\n" ; ?>
		.backend_flavour:          2
		.palmipede.userlogin:      VOTRE_LOGIN
		.backend.url:              <?php echo $sBackendURL . "\n" ; ?>
		.post.url:                 <?php echo $sPostURL    . "\n" ; ?>
		.tribune.delay:            300
		.palmipede.msg_max_length: <?php echo $iMsgLen ?>
	</pre>
	<p class="content">
		Gardez la valeur indiquée du paramètre
		<tt>tribune.delay</tt>&nbsp;! Il s'agit du temps de la mise à
		jour de la tribune et il a été réglé de manière à éviter de
		générer trop de trafic pour cette tribune afin de respecter les
		ressources mise à diposition par l'hébergeur. Merci&nbsp;!
	</p>
</div>

<?php
   require getcwd() . '/footer.php' ;
?>
