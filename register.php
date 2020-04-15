<?php
	// charge les fichiers nécessaires: base de donnée, fonctions d'authentification et le header (menu)
	require 'database.php'; 
	require 'auth.php';
	require 'header.php';
	
	// si déjà connecté alors on se dirige vers la page de profil
	if(is_connect()){
		header('location: profil.php');
		exit();	
	}
	
	// initialise les variables qui contiendra les données du formulaire.
	$identity = $password = $confirm = "";
	$identityError = $passwordError = $confirmError = "";
	$success = true;

	// réccupère les données du formulaire
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$identity = verifyInput($_POST["identity"]);
		$password = verifyInput($_POST["password"]);
		$confirm  = verifyInput($_POST["confirm"]);
		
		// vérifie les informations du formulaire
		if(empty($identity)){
			$identityError = "Veuillez entrer votre identifiant svp";
			$success = false;
		}
		if(empty($password)){
			$passwordError = "Veuillez entrer votre mot de passe svp";
			$success = false;
		}
		else if(strlen($password)< 6){
			$passwordError = "le mot de passe doit contenir au moins 6 caractères";
			$success = false;
		}
		if(empty($confirm)){
			$confirmError = "Veuillez confirmer votre mot de passe svp";
			$success = false;
		}
		else if($confirm != $password){
			$confirmError = "le mot de passe est différent !";
			$success = false;
		}
		// si tout est bon, on peut insérer le nouveau compte à la base de données
		if($success){
			$passwordhash = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]); // crypte le mot de passe
			$user = new Users();
			$user->UserName = $identity;
			$user->Password = $passwordhash;
			
			// save to bdd Sqlite
			Database::connectSqlite(dirname(__FILE__).'\\users.db');
			
			//Vérifie si le nom d'utilisateur est déjà pris
			$test = Database::findUser($identity);
			if(!$test){
				// nom d'utilisateur disponible
				$user->Date = date('d-m-Y, H:i');	// Ajoute la date de connection
				$id = Database::insertUser($user);  // Inscrit le nouvel utilisateur et réccupère son id
				Database::disconnect();				
				connect($id, $identity);			// On se connecte en mémorisant l'id et le nom d'utilisateur
				header('location: index.php');		// redirection vers la page d'accueil
				exit();	
			}
			else{
				// nom d'utilisateur déjà pris !
				Database::disconnect();
				$identityError = "Nom d'utilisateur déjà pris";
				$success = false;
			}
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
	<h1>Register</h1><br/>
	<form id="register-form" method="post" action="" role="form">
		<label for="identity">Identifiant: </label>
		<input type="text" id="identity" name="identity" class="form-control" placeholder="Votre identifiant" value="<?php echo $identity; ?>" />
		<p class="comment"><?php echo $identityError; ?></p>
		
		<label for="password">Mot de passe: </label>
		<input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe" value="<?php echo $password; ?>" />
		<p class="comment"><?php echo $passwordError; ?></p>
		
		<label for="confirm">Confirmez votre Mot de passe: </label>
		<input type="password" id="confirm" name="confirm" class="form-control" placeholder="Confirmez votre mot de passe" value="<?php echo $confirm; ?>" />
		<p class="comment"><?php echo $confirmError; ?></p><br/>
		
		<input type="submit" class="btn btn-primary" value="Créer mon compte" />		
	</form><br/><br/>
</div>
		
<?php require 'footer.php'; ?>