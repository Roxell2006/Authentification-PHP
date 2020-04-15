<?php 
	require 'database.php'; 
	require 'header.php'; 
	require 'auth.php';
	
	// si déjà connecté alors on se déconnecte "logout" et on redirectionne vers l'accueil
	if(is_connect()){
		disconnect();
		header('location: index.php');
		exit();	
	}
	
	// initialise les variables qui contiendra les données du formulaire.
	$identity = $password = "";
	$identityError = $passwordError = "";
	$success = true;
	
	// réccupère les données du formulaire
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$identity = verifyInput($_POST["identity"]);
		$password = verifyInput($_POST["password"]);
		
		// vérifie les informations du formulaire
		if(empty($identity)){
			$identityError = "Veuillez entrer votre identifiant svp";
			$success = false;
		}
		if(empty($password)){
			$passwordError = "Veuillez entrer votre mot de passe svp";
			$success = false;
		}
		// si tout est bon, on regarde dans la bdd si on trouve l'utilisateur
		if($success){
			Database::connectSqlite(dirname(__FILE__).'\\users.db');
			$user = Database::findUser($identity);
			// si trouvé on teste son mot de passe
			if($user){
				if(password_verify($password, $user->Password))
				{
					// mot de passe correct:
					connect($user->Id, $user->UserName); 	// on est connecté
					Database::insertDate($user->Id);		// on inscrit la date de la connection
					Database::disconnect();
					header('location: index.php');
					exit();	
				}
				else
				{
					// mot de passe incorrect:
					echo '<p class="alert alert-danger">Mot de passe invalide !</p>';
				}
			}
			else{
				// nom d'utilisateur incorrect
				echo '<p class="alert alert-danger">Nom d\'utilisateur ou mot de passe incorrect !</p>';
			}
			Database::disconnect();
		}	
	}
	
	function verifyInput($var)
	{
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlspecialchars($var);
		return $var;
	}		
?>

<div class="container">
	<h1>Login</h1><br/>
	<form id="login-form" method="post" action="" role="form">
		<label for="identity">Identifiant: </label>
		<input type="text" id="identity" name="identity" class="form-control" placeholder="Votre identifiant" value="<?php echo $identity ?>" />
		<p class="comment"><?php echo $identityError ?></p>
		
		<label for="password">Mot de passe: </label>
		<input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe" />
		<p class="comment"><?php echo $passwordError ?></p><br/>
		
		<input type="submit" class="btn btn-primary" value="Me Connecter" />
		
	</form><br/><br/>
</div>
		
<?php require 'footer.php'; ?>