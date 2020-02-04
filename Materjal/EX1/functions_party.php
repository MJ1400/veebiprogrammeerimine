<?php

	function signUpParty($name, $surname, $code) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO PARTY (Eesnimi, Perekonnanimi, Kood) VALUES (?,?,?)");
		echo $conn->error;
		
		$stmt->bind_param("sss", $name, $surname, $code);

		if($stmt->execute()) {
			$notice = " Peole registreerumine õnnestus!";
			header("Location: party_reg.php");
		}else {
			$notice = " Peole registreerumisel tekkis tehniline viga: " .$stmt->error;
		}

		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function readAllPartyReg() {
	  $notice = null;
	  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  mysqli_set_charset( $conn, 'utf8');
	  $stmt = $conn->prepare("SELECT Eesnimi, Perekonnanimi, Kood, Tasu FROM PARTY WHERE Kustutatud IS NULL");
	  
	  $stmt->bind_result($name, $surname, $code, $paid);

	  $stmt->execute();
	  
	  $partyInfoHTML = '<table style="width:50%" border="1"><tr bgcolor="#C0C0C0">';
	  $partyInfoHTML .= '<th>Eesnimi</th><th>Perekonnanimi</th><th>Üliõpilaskood</th><th>Makstud</th>';
	  $partyInfoHTML .= '</tr>';
	  while($stmt->fetch()){
		  $partyInfoHTML .= '<tr><td>' .$name .'</td><td>';
		  $partyInfoHTML .= $surname .'</td><td>';
		  $partyInfoHTML .= $code .'</td><td>';
		  if ($paid == NULL){
			$partyInfoHTML .= "Ei ole makstud</td></tr>";
		  }else {
			$partyInfoHTML .= "<i>Makstud</i></td></tr>";
		  } 		  
	  }
	  $partyInfoHTML .= '</table><br>';
	  $stmt->close();
	  $conn->close();
	  return $partyInfoHTML;
	} 
	
 	function readSomePartyInfo() {
	  $notice = null;
	  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  mysqli_set_charset( $conn, 'utf8');
	  $stmt = $conn->prepare("SELECT Eesnimi, Perekonnanimi FROM PARTY WHERE Kustutatud IS NULL");
	  
	  $stmt->bind_result($name, $surname);

	  $stmt->execute();
	  
	  $partyInfoHTML = '<table style="width:20%" border="1"><tr bgcolor="#C0C0C0">';
	  $partyInfoHTML .= '<th>Eesnimi</th><th>Perekonnanimi</th>';
	  $partyInfoHTML .= '</tr>';
	  while($stmt->fetch()){
		  $partyInfoHTML .= '<tr><td>' .$name .'</td><td>';
		  $partyInfoHTML .= $surname .'</td></tr>';
	  }
	  $partyInfoHTML .= '</table>';
	  $stmt->close();
	  $stmt = $conn->prepare("SELECT COUNT(Kood) FROM PARTY WHERE Kustutatud IS NULL");
	  $stmt->bind_result($registered);
	  $stmt->execute();
		while($stmt->fetch()){
		  $partyInfoHTML .= '<p>Kokku peole registreerinud inimesi: ' .$registered .'</p>';
	  }
	  $stmt->close();
	  $stmt = $conn->prepare("SELECT Eesnimi, Perekonnanimi FROM PARTY WHERE Kustutatud IS NULL AND Tasu IS NOT NULL");
	  $stmt->bind_result($name, $surname);
	  $stmt->execute();
	  $partyInfoHTML .= '<h1>Neist kindlad tulijad (maksnud) on:</h1>';
	  $partyInfoHTML .= '<table style="width:20%" border="1"><tr bgcolor="#C0C0C0">';
	  $partyInfoHTML .= '<th>Eesnimi</th><th>Perekonnanimi</th>';
	  $partyInfoHTML .= '</tr>';
	  while($stmt->fetch()){
		  $partyInfoHTML .= '<tr><td>' .$name .'</td><td>';
		  $partyInfoHTML .= $surname .'</td></tr>';
	  }
	  $partyInfoHTML .= '</table>';
	  $stmt->close();
	  $stmt = $conn->prepare("SELECT COUNT(Kood) FROM PARTY WHERE Kustutatud IS NULL AND Tasu IS NOT NULL");
	  $stmt->bind_result($registeredPaid);
	  $stmt->execute();
		while($stmt->fetch()){
		  $partyInfoHTML .= '<p>Kindlaid tulijaid on kokku: ' .$registeredPaid .'</p>';
	  }
	  $stmt->close();
	  $conn->close();
	  return $partyInfoHTML;
	}  	
	
	function markAsPaid($code) {
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("UPDATE PARTY SET Tasu=1 WHERE Kood=?");
		echo $conn->error;
		$stmt->bind_param("s", $code);
		if($stmt->execute()) {
			$notice = " Makse edukalt kinnitatud!";
			header("Location: party_admin.php");
		}else {
			$notice = " Seda üliõpilaskoodi ei ole või on makse juba kinnitatud!";
		}
		$stmt->close();
		$conn->close();
		return $notice;
		
	}
	
	function deleteParty($code) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT ID FROM PARTY WHERE Kood=?");
		$stmt->bind_param("i", $code);
		$stmt->bind_result($ID);
		if($stmt->execute()) {
			$stmt->close();
			$stmt = $conn->prepare("UPDATE PARTY SET Kustutatud=1 WHERE ID=?");
			echo $conn->error;
			$stmt->bind_param("i", $ID);
			if($stmt->execute()) {
				$notice = " Registreerumine edukalt tühistatud!";
				header("Location: party_del.php");
			}else{
				$notice = " Registreerimise tühistamisel tekkis tehniline viga: " .$stmt->error;
			}
		}else{
			$notice = " Sellise üliõpilaskoodiga registreerunut ei ole või on registreerimine juba tühistatud!";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
?>