<?php 
	require 'header.php'; 
	require 'auth.php';
	
	// si on est connecté on change une partie du menu 
	// en indiquand le nom de l'utilisateur à la place de Registre et Logout à la place de Login
	if(is_connect()){
		?>
		<script>
			$(function(){
				$("#register").html('<?php echo $_SESSION['username'] ?>');
				$("#login").html('logout');
			});
		</script>
		<?php
	}
?>
		
<div class="container">
	<h1> Page d'accueil </h1><br/><br/>
</div>
		
<?php require 'footer.php'; ?>
