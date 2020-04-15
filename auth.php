<?php
	
	// vérifie si on est connecté en vérifiant si la super global $_SESSION['connect'] existe
	function is_connect(){
		if(session_status() === PHP_SESSION_NONE){
			session_start();
		}
		return !empty($_SESSION['connect']);
	}
	// on se connecte en ajoutant une variable super global $_SESSION['connect'] avec en valeur l'id de l'utilisateur
	function connect($id, $name){
		if(session_status() === PHP_SESSION_NONE){
			session_start();
		}
		$_SESSION['connect'] = $id;
		$_SESSION['username'] = $name;
	}
	// on se déconnecte en supprimant la super global $_SESSION['connect']
	function disconnect(){
		unset($_SESSION['connect']);
	}
	
?>