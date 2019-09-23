<?php

function readAllFilms() {
  //funktsioonis on lokaalsed muutujad, neid ei näe väljaspool funktsiooni
  //loeme andmebaasist
  //Loeme andmebaasiühenduse (näiteks $conn)
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  //valmistame ette päringu
  $stmt = $conn->prepare("SELECT pealkiri, aasta FROM film");
    // seome saadava tulemuse muutujaga
  $stmt->bind_result($filmTitle, $filmYear);
  // käivitame SQL päringu
  $stmt->execute();
  $filmInfoHTML = "";
  while($stmt->fetch()) {
    $filmInfoHTML .="<h3>" .$filmTitle ."</h3>";
    $filmInfoHTML .="<p>" .$filmYear ."</p>";
    //echo $filmTitle;
  }
  //echo $filmInfoHTML;
  //sulgeme ühenduse
  $stmt->close();
  $conn->close();
  //väljastan väärtuse
  return $filmInfoHTML;
}

function saveFilmInfo($filmTitle, $filmYear, $filmDuration, $filmGenre, $filmCompany, $filmDirector)   {
  $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  $stmt = $conn->prepare("INSERT INTO film(pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES(?,?,?,?,?,?)");
  echo $conn->error;
  //s -string, i -integer, d -decimal(murdarv)
  $stmt->bind_param("siisss", $filmTitle, $filmYear, $filmDuration, $filmGenre, $filmCompany, $filmDirector);
  $stmt->execute();

  $stmt->close();
  $conn->close();

}
?>
