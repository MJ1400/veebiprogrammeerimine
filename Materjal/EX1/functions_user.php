<?php

	//käivitame sessiooni
	//session_start();
	//var_dump($_SESSION);


	function signUp($name, $surname, $email, $gender, $birthDate, $password) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpusers3 (firstname, lastname, birthdate, gender, email, password) VALUES (?,?,?,?,?,?)");
		echo $conn->error;
		
		//valmistame parooli salvestamiseks ette
		$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
		$pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
		
		$stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);

		if($stmt->execute()) {
			$notice = "Uue kasutaja salvestamine õnnestus!";
		}else {
			$notice = "kasutaja salvestamisel tekkis tehniline viga: " .$stmt->error;
		}

		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function signIn($email, $password) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT password FROM vpusers3 WHERE email=?");
		echo  $conn->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($passwordFromDB);
		if($stmt->execute()){
			if($stmt->fetch()) {
			//parooli õigsust kontrollib:
				if(password_verify($password, $passwordFromDB)) {
					$stmt->close();
					$stmt = $conn->prepare("SELECT id, firstname, lastname FROM vpusers3 WHERE email=?");
					echo $conn->error;
					$stmt->bind_param("s", $email);
					$stmt->bind_result($idFromDB, $firstnameFromDB, $lastnameFromDB);
					$stmt->execute();
					$stmt->fetch();
					$notice = " Sisselogimine õnnestus! Tere " .$firstnameFromDB ." " .$lastnameFromDB .".";
					//salvestame kasutaja nime sessioonimuutujatesse
					$_SESSION["userID"] = $idFromDB;
					$_SESSION["userFirstname"] = $firstnameFromDB;
					$_SESSION["userLastname"] = $lastnameFromDB;
					
					$stmt->close();
					$stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vpuserprofiles WHERE userid=?");
					echo $conn->error;
					$stmt->bind_param("i", $_SESSION["userID"]);
					$stmt->bind_result($mybgcolor, $mytxtcolor);
					$stmt->execute();
					if($stmt->fetch()) {
						$notice = " Kasutaja profiil valitud!";
						$_SESSION["mybgcolor"] = $mybgcolor;
						$_SESSION["mytxtcolor"] = $mytxtcolor;
					}else{
						$_SESSION["mybgcolor"] = "#FFFFFF";
						$_SESSION["mytxtcolor"] = "#000000";
					}
					$stmt->close();
					$conn->close();
					header("Location: home.php");
					
					exit();
					
				}else {
					$notice = " Vale salasõna.";
				}
			}else {
				$notice = " Sellist kasutajat (" .$email .") ei eksisteeri!";
			}
		}else {
			$notice = " Sisse logimisel tekkis viga: " .$stmt->error;
		}
		$stmt->close();
		$conn->close();
		return $notice;	
	}
	
	function createProfile($userid, $mydescription, $mybgcolor, $mytxtcolor){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT id FROM vpuserprofiles WHERE userid=?");
	echo $conn->error;
	$stmt->bind_param("i", $_SESSION["userID"]);
	$stmt->bind_result($userid);
	$stmt->execute();
	if($stmt->fetch()){
		//profiil juba olemas, uuendame
		$stmt->close();
		$stmt = $conn->prepare("UPDATE vpuserprofiles SET description=?, bgcolor=?, txtcolor=? WHERE userid=?");
		echo $conn->error;
		$stmt->bind_param("sssi", $mydescription, $mybgcolor, $mytxtcolor, $_SESSION["userID"]);
		if($stmt->execute()){
			$notice = "Profiil edukalt uuendatud!";
			$_SESSION["mybgcolor"] = $mybgcolor;
	        $_SESSION["mytxtcolor"] = $mytxtcolor;
		} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
		//$notice = "Profiil olemas, ei salvestanud midagi!";
	} else {
		//profiili pole, salvestame
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("isss", $_SESSION["userID"], $mydescription, $mybgcolor, $mytxtcolor);
		if($stmt->execute()){
			$notice = "Profiil edukalt salvestatud!";
		} else {
			$notice = "Profiili salvestamisel tekkis tõrge! " .$stmt->error;
		}
	}
	$stmt->close();
	$conn->close();
	return $notice;
  }
  
  	function changePassword($userid, $oldPassword, $newPassword){
	$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT password FROM vpusers3 WHERE id=?");
		echo  $conn->error;
		$stmt->bind_param("i", $userid);
		$stmt->bind_result($passwordFromDB);
		if($stmt->execute()){
			if($stmt->fetch()) {
			//parooli õigsust kontrollib:
				if(password_verify($oldPassword, $passwordFromDB)) {
					$stmt->close();
					$stmt = $conn->prepare("UPDATE vpusers3 SET password=? WHERE id=?");
					echo $conn->error;
					//valmistame parooli salvestamiseks ette
					$options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
					$pwdhash = password_hash($newPassword, PASSWORD_BCRYPT, $options);
					$stmt->bind_param("si", $pwdhash, $userid);
					if($stmt->execute()) {
						$notice = " Salasõna vahetamine õnnestus!";
					}else {
						$notice = " Salasõna salvestamisel tekkis tehniline viga: " .$stmt->error;
					}
				}else {
					$notice = " Sisestatud praegune salasõna on vale!";
				}
			}else {
				$notice = " Viga!";
			}
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
  
	function showMyDesc(){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT description FROM vpuserprofiles WHERE userid=?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["userID"]);
		$stmt->bind_result($descriptionFromDb);
		$stmt->execute();
		if($stmt->fetch()){
		  $notice = $descriptionFromDb;
		}
		$stmt->close();
		$conn->close();
		return $notice;
  }
  
  	function addWeight($weight) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO weight (userid, weight) VALUES (?,?)");
		echo $conn->error;
		
		$stmt->bind_param("id", $_SESSION["userID"], $weight);

		if($stmt->execute()) {
			$notice = "Uue kaalu lisamine õnnestus!";
		}else {
			$notice = "kaalu salvestamisel tekkis tehniline viga: " .$stmt->error;
		}

		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	  	function currentWeight() {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT AVG(weight) as weightAvg FROM weight WHERE userid=?");
		echo $conn->error;
		
		$stmt->bind_param("i", $_SESSION["userID"]);
		$stmt->bind_result($weightAvg);

		$stmt->execute();
		if($stmt->fetch()){
		  $notice = round($weightAvg, 1);
		}

		$stmt->close();
		$conn->close();
		return $notice;
	}
?>