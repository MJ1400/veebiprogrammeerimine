<?php

	function saveNews($userid, $newsTitle, $newsContent, $expiredate) {
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		mysqli_set_charset( $conn, 'utf8');
		$stmt = $conn->prepare("INSERT INTO news (userid, title, content, expire) VALUES (?,?,?,?)");
		echo $conn->error;
		$addedDate = time();
		$stmt->bind_param("isss", $userid, $newsTitle, $newsContent, $expiredate);

		if($stmt->execute()) {
			$notice = "Uudis salvestatud!";
		}else {
			$notice = "Uudise salvestamisel tekkis tehniline viga: " .$stmt->error;
		}

		$stmt->close();
		$conn->close();
		return $notice;
	}

function readAllNews($limit) {
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT vpusers3.firstname, vpusers3.lastname, news.title, news.content, news.added FROM vpusers3 JOIN news ON vpusers3.id = news.userid WHERE deleted IS NULL ORDER BY news.added DESC LIMIT ?");
	echo $conn->error;
	$stmt->bind_param("i", $limit);
	$stmt->bind_result($firstname, $lastname, $title, $content, $added);
	//htmlspecialchars_decode()
	$user = 
	$stmt->execute();
	while($stmt->fetch()){
		$notice .= "<p><b><u>" .htmlspecialchars_decode($title) ."</u></b> (Lisatud: " .$added ." " .$firstname ." " .$lastname .")</p> \n";
		$notice .= "<p>" .htmlspecialchars_decode($content) ."</p>";
	}	
	if(empty($notice)) {
		$notice = "<p>Otsitud sõnumeid pole!</p> \n";
	}
	$stmt->close();
	$conn->close();
	return $notice;
}


?>