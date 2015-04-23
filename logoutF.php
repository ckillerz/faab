<?php
	require getcwd() . '/header.php' ;
?>

<div class="content">

<h2>Déconnection</h2>

<form id="form" action="logoutF.php" method="post">
	<p class="input">
		Cliquez sur le bouton ci-dessous pour terminer votre session. Pour poster
		avec votre compte, vous devrez vous identifier, sinon vos messages seront
		anonymes.
	</p>
	<p class="input">
		<input id="send" name="send" class="button" type="submit" value="Valider" />
	</p>
</form>
	
<script type="text/javascript" src="logoutF.js" ></script>

</div>

<?php
	require getcwd() . '/footer.php' ;
?>
