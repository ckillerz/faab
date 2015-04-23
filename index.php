<?php
	require getcwd() . '/header.php'      ;
	require getcwd() . '/include/file.php' ;
?>
<div class="board">
	<form id="form" action="index.php" method="get" accept-charset="UTF-8">
		<p class="input">
			<input id="msg"  name="msg"  class="text"   type="text" size="80" maxlength="<?php echo $iMsgLen ; ?>" />
			<input id="send" name="send" class="button" type="submit" value="Envoyer" />
		</p>
	</form>
	<?php echo FileReadRaw( $sHtmlFile ); ?>
</div>

<script type="text/javascript" src="index.js"></script>

<?php
	require 'footer.php' ;
?>
