<?php

function addNews($newsTitle, $news, $expiredate){
  $notice = null;
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, expire) VALUES (?, ?, ?, ?)");
  echo $conn->error;
  $stmt->bind_param("isss", $_SESSION["userID"], $newsTitle, $news, $expiredate);
  if($stmt->execute()){
    $notice = " Uudis salvestati andmebaasi!";
  } else {
    $notice = " Uudise salvestamine ebaõnnestus tehnilistel põhjustel! " .$stmt->error;
  }
  $stmt->close();
  $conn->close();
  return $notice;
}

function readNews(){
$notice = null;
$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
$stmt = $conn->prepare("SELECT title, content FROM vpnews WHERE expire>=now() and deleted IS NULL");
echo $conn->error;
$stmt->bind_result($titleFromDb, $contentFromDb);
$stmt->execute();
  while($stmt->fetch()){
  $notice .= "<h3>" .$titleFromDb  ."</h3> \n <p>" .$contentFromDb ."</p> \n";
}
$stmt->close();
$conn->close();
return $notice;
}


?>
