<?php
	require_once("action/commonAction.php");
	require_once("action/dba/connection.php");

	class MySQLrequests {

		private static $connection;

		//remove weird characters created by HTML
		public static function clearString($badStr){		
			$goodStr = htmlspecialchars ($badStr);
			return $goodStr;
		}

		
		public static function template($desc,$id,$ext,$title,$main) {
			//clean strings
			$desc=MySQLrequests::clearString($desc);
			$title=MySQLrequests::clearString($title);
			//open connection
			$connection = Connection::getConnection();
			//prepare your request and put "?" instead ov the variables
			$statement = $connection->prepare("INSERT into j_realisation(ID_PROFIL,INFORMATION,IMG_EXT,NOM,MAIN) Values (?,?,?,?,?)");

			// assign the "?" to variables
			$statement->bindParam(1, $id);
			$statement->bindParam(2, $desc);
			$statement->bindParam(3, $ext);
			$statement->bindParam(4, $title);
			$statement->bindParam(5, $main);
			//execute your request
			$statement->execute();

			//PDO::FETCH_ASSOC: returns an array indexed by column name as returned in your result set 
			$statement->setFetchMode(PDO::FETCH_ASSOC);

			//get the results and put them in a variable
			$info = $statement->fetch();
			//close connection
			Connection::closeConnection();
			//return your variable
			return $info;
		}

		public static function authenticate($username, $password) {
			$connection = connection::getConnection();
			$pass = sha1($password);

			$statement = $connection->prepare("SELECT *from user where user_pass = ? and user_email = ?");

			$statement->bindParam(1, $pass);
			$statement->bindParam(2, $username);

			$statement->setFetchMode(PDO::FETCH_ASSOC);

			$statement->execute();

			$info = $statement->fetch();
			connection::closeConnection();
			return $info;
		}
	}