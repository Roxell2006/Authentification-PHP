<?php 
	require 'auth.php';
	
	// Ceci est une page accessible uniquement que si on est connecté
	// donc si on est pas connecté on fait une redirection vers la page Login.php
	if(!is_connect()){
		header('location: login.php');
		exit();	
	}
?>
	
<?php require 'header.php'; ?>
		
<div class="container">
	<h1> Page du site </h1>
	<p> Vous parcourez maintenant une page sécurisée </p><br/><br/>
</div> 

<script>
	$(function(){
		$("#register").html('<?php echo $_SESSION['username'] ?>');
		$("#login").html('logout');
	});
</script>

<?php require 'footer.php'; ?>
