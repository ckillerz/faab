<?php
	require getcwd() . '/header.php' ;
?>

<div class="content">

<h2>Identification</h2>

<form id="form" action="loginF.php" method="post">
	<p class="input">
		 Utilisateur :<br /><input id="user" name="user" class="text" type="text"     size="15" /><br />
		Mot de passe :<br /><input id="pass" name="pass" class="text" type="password" size="15" /><br />
		<br />
		<input id="send" name="send" class="button" type="submit" value="Valider" />
	</p>
</form>
	
<script type="text/javascript" src="loginF.js"></script>

</div>

<?php
	require getcwd() . '/footer.php' ;
?>
