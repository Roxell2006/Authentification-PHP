<?php
	class Database
	{
		private static $connection = null;
		
		// connection via SqLite
		public static function connectSqlite($dbName)
		{
			self::$connection = new PDO('sqlite:' . $dbName);
		}
		
		// connection via MySql
		public static function connectMysql($dbHost, $dbName, $dbUser, $dbPassword)
		{
			try{
				self::$connection = new PDO("mysql:host=" . $dbHost . ";dbname=" . $dbName, $dbUser, $dbPassword);
			}
			catch(PDOException $e){
				die($e->getMessage());
			}
            return self::$connection;
		}
	
		public static function disconnect()
		{
			self::$connection = null;
		}
		
		// Ajout d'un nouveau utilisateur et renvois son id 
		public static function insertUser($user)
		{
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {
				$query = self::$connection->prepare('INSERT INTO users (UserName , Password, Date ) VALUES (:name, :password, :date)');
				$query->execute([
					'name' => $user->UserName,
					'password' => $user->Password,
					'date' => $user->Date,
				]);
			}
			catch (PDOException $e) {
				echo 'Connexion échouée : ' . $e->getMessage();
			}
			return self::$connection->lastInsertId();
		}
		
		// recherche si le nom de l'utilisateur existe déjà
		public static function findUser($name)
		{	
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {		
				$query = self::$connection->prepare('SELECT * FROM users WHERE UserName = :name'); 
				$query->execute(['name' => $name]);
				$user = $query->fetch(PDO::FETCH_OBJ);
			}
			catch (PDOException $e) {
				echo 'Connexion échouée : ' . $e->getMessage();
			} 
			return $user;
		}
		
		// Sélectionne un utilisateur avec son id
		public static function getUser($id)
		{
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {		
				$query = self::$connection->prepare('SELECT * FROM users WHERE Id = :id'); 
				$query->execute(['id' => $id]);
				$user = $query->fetch(PDO::FETCH_OBJ);
			}
			catch (PDOException $e) {
				echo 'Connexion échouée : ' . $e->getMessage();
			} 
			return $user;
		}
		
		//Modifie les informations de l'utilisateur sélectionné par son id
		public static function updateUser($user)
		{
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {		
				$query = self::$connection->prepare('UPDATE users SET Pseudo = :pseudo, Email = :email, Photo = :photo WHERE Id = :id'); 
				$query->execute([
					'id' => $user->Id,
					'pseudo' => $user->Pseudo,
					'email' => $user->Email,
					'photo' => $user->Photo
				]);
			}
			catch (PDOException $e) {
				echo 'Connexion échouée : ' . $e->getMessage();
			} 
		}
		
		//Inscrit la date de la dernière connection au profil
		public static function insertDate($id)
		{
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			try {		
				$query = self::$connection->prepare('UPDATE users SET Date = :date WHERE Id = :id'); 
				$query->execute([
					'id' => $id,
					'date' => date('d-m-Y, H:i')	
				]);
			}
			catch (PDOException $e) {
				echo 'Connexion échouée : ' . $e->getMessage();
			}
		}
	}
	
	class Users 
	{
		public $Id;
		public $UserName;
		public $Password;
		public $Date;
		public $Pseudo;
		public $Email;
		public $Photo;
	}
?>