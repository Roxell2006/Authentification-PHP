<?php
	require 'database.php'; 
	require 'auth.php';
	require 'header.php';
	
	// récuppère l'id de l'utilisateur
	session_start();
	$id = $_SESSION['connect'];
	
	// récuppère les données de l'utilisateur dans la bdd grâce à son id
	Database::connectSqlite(dirname(__FILE__).'\\users.db');
	$user = Database::getUser($id);
	Database::disconnect();
	
	// initialise les variables qui contiendra les données du formulaire.
	$pseudo = $email = "";
	$emailError = $imageError = "";
	$success = true;
	
	// si on a activé le submit (jquery du bouton btn_form
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// nettoye les données
		$pseudo = verifyInput($_POST["pseudo"]);
		$email  = verifyInput($_POST["email"]);
		
		$user->Pseudo = $pseudo;
		
		// vérifie la validité de l'email grâce au filtre VALIDATE_EMAIL
		if(!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)){
			$emailError = "Veuillez entrer une adresse mail valide svp";
			$success = false;
		}
		else{
			$user->Email = $email;
		}
		
		// réccupère le nom du fichier image téléchargé
		$image = verifyInput($_FILES['fileUpload']['name']);// nom du fichier seul  $_FILES['image']['name']	
		$imagePath = 'images/'. uniqid() . basename($image);// nom complet avec le dossier de destination et une clé unique dans le nom
		$type_file = $_FILES['fileUpload']['type'];			// nom de l'extension du fichier  $_FILES['fichier']['type']
		
	
		// vérifie si on a téléchargé une image de profil et si elle est valide
		if(!empty($image)){
			// si oui, Vérifie si c'est une image
			if(!strstr($type_file, 'jpg') && !strstr($type_file, 'jpeg') && !strstr($type_file, 'png') && !strstr($type_file, 'gif') ){
				$imageError = "Le fichier n'est pas une image";
				$success = false;
			}
			// vérifie si le fichier existe déjà: (peu probable car on utilise une clé unique en plus du nom de la photo)
			if(file_exists($imagePath)){
				$imageError = "Le fichier existe deja";
				$success = false;
			}
			// vérifie la taille:
			if($_FILES["fileUpload"]["size"] > 500000) {
				$imageError = "Le fichier ne doit pas depasser les 500 KB";
				$success = false;
			}
			//si aucune erreur, on peut uploader l'image dans le dossier
			if($success){
				if(!move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $imagePath)) 
				{
					$imageError = "Il y a eu une erreur lors de l'upload";
					$isUploadSuccess = false;
				} 
				$user->Photo = $imagePath;
			}
		}
		// si pas d'erreur, on peut enregistrer les modifications dans la bdd
		Database::connectSqlite(dirname(__FILE__).'\\users.db');
		Database::updateUser($user);
		Database::disconnect();
		header('location: index.php');
		exit();	
	}
	
	function verifyInput($var)
	{
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlspecialchars($var);
		return $var;
	}
?>
<h1 style="text-align: center;">Profil</h1><br/>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<form id="profil-form" method="post" action="" role="form" enctype="multipart/form-data">
				<label for="username">UserName: </label>
				<input type="text" id="username" name="username" class="form-control"  value="<?php echo $user->UserName ?>" disabled />
				
				<label for="pseudo">Pseudo: </label>
				<input type="text" id="pseudo" name="pseudo" class="form-control" placeholder="Votre pseudo" value="<?php echo $user->Pseudo ?>" />
				
				<label for="email">Email: </label>
				<input type="text" id="email" name="email" class="form-control" placeholder="Votre Email" value="<?php echo $user->Email ?>"/>
				<p class="comment"><?php echo $emailError; ?></p>
				
				<input type="file" accept="image/*" id="fileUpload" name="fileUpload" style="display: none;" />
				
				<input type="submit" class="btn btn-primary btnprofil" />
			</form>
		</div>
		<div class="col-md-6">
			<?php
				if ($user->Photo){
					?><img class="img-thumbnail photo" src="<?php echo $user->Photo; ?>" alt="photo de profil" />
					<p class="comment"><?php echo $imageError; ?></p><?php
				}
				else{
					?><img class="img-thumbnail photo" src="images/profil_vide.jpg" alt="photo de profil" />			
					<p style="text-align: center;" class="comment"><?php echo $imageError; ?></p><?php
				}
			?>
		</div>
	</div>
	<div class="btnsubmit">
		<button id="btn_form" style="width: 100%;" class="btn btn-primary">Enregistrer</button>
	</div>
</div>

<script>
	$(function(){
		$("#register").html('<?php echo $_SESSION['username'] ?>');
		$("#login").html('logout');
	});
</script>

<?php require 'footer.php'; ?>
