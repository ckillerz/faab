<?php
	require dirname(__FILE__) . '/header.php' ; 
?>   

<div class="content">

	<h2>Création d'un compte utilisateur</h2>

	<form id="form" action="newF.php" method="get">
	<p><b>Nom d'utilisateur :</b></p>
	<p>
		Veuillez saisir un nom d'utilisateur dans le champ ci-dessous. Ce nom ne
		doit être constitué que par les caractères alphabétiques
		(a à z) et numériques. Sa longueur doit être comprise entre 3 et 15 
		caractères.
	</p>
	<p class="input">
		<input id="user" name="user" class="text" type="text" size="17" maxlength="15" />
	</p>  
	<p><b>Mot de passe :</b></p>
	<p>
		Veuillez saisir un mot de passe dans les champs ci-dessous. Le mot de
		passe ne doit être constitué  que par les caractères alphabétiques et 
		numériques. Sa longueur doit être comprise entre 8 et 32 caractères.
	</p>
	<p class="input">
		<input id="pass1" name="pass1" class="text" type="password" size="17"  maxlength="32" />
	</p>
	<p class="input">
		<input id="pass2" name="pass2" class="text" type="password" size="17"  maxlength="32" />
	</p>
	<p class="input">
		<input id="send" name="send" class="button" value="Valider" type="submit" />
	</p>  
	</form> 
</div>

<script type="text/javascript" src="newF.js"></script>

<?php
	require dirname(__FILE__) . '/footer.php' ;
?>