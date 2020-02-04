<?php
  // käivitame sessiooni
  // session_start();
  // var_dump($_SESSION);



  function saveWeight($weight){
  $notice = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare("INSERT INTO vpweights (userid, weight) VALUES (?,?)");
	echo $conn->error;
	$stmt->bind_param("id", $_SESSION["userID"], $weight);
	if($stmt->execute()) {
    $notice = "Kaalu salvestamine õnnestus!";
  } else {
    $notice = "Kaalu salvestamisel tekkis viga!";
  }

	$stmt->close();
	$conn->close();
	return $notice;
  }

    function showAvgWeight(){
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


 ?>
