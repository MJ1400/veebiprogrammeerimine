<?php
 //globaalsed muutujad on väljaspool funktsiooni
  require("../../../configVP.php");
  $database = "if19_mirjam_pe_1";
  require("functions_film.php");
  require("functions_main.php");  
  require("functions_user.php");
  
    //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");
  
  //pannakse funktsioon käima (sama nimi on aga pole sama muutuja)
  $filmInfoHTML = readAllFilms();
  $someFilmInfoHTML = readSomeFilms();
  //kui pole sisseloginud
  if(!isset($_SESSION["userID"])) {
	  //siis jõuga sisselogimise lehele
	  header("Location: page.php");
	  exit();
  }
  
  //väljalogimie
  if(isset($_GET["Logout"])){
	  session_destroy();
	  header("Location: page.php");
	  exit();
  }
  
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
  $userid = $_SESSION["userID"];
  
  //lisame lehe päise
  require("header.php");
 
?>


<body>
  <?php
    echo "<h1>" .$userName ." kodutöö 26.10.2019</h1>";
  ?>
  <p>Antud leht on loodud koolis õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <h2>Eesti filmid</h2>
  <h3>Praegu on andmebaasis järgmised filmid:</h3>
  <?php
    echo $filmInfoHTML;
  ?>
  <h3>Järgnevad filmid on vanemad kui 50 aastat:</h3>
  <?php
    echo $someFilmInfoHTML;
  ?>
  <hr>
  <p><a href="?Logout=1">Logi välja!</a> - Tagasi <a href="home.php">avalehele</a> - Lisa uusi filme <a href="addfilm.php">siit</a>.</p>
  <?php
  //lisame lehe jaluse
  require("footer.php");
  ?>