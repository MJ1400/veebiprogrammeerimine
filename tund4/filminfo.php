<?php
  //väljaspool funktsiooni on globaalsed muutujad
  require("../../../config_vp2019.php");
  require("functions_film.php");
  $userName = "Marten Jürgensi";
  $database = "if19_marten_1";

  $filmInfoHTML = readAllFilms();
  $filmInfo50 = readFilmsOlder50();

	//lisame lehe päise
	require("header.php");
?>


<body>
  <?php
    echo "<h1>" .$userName ." koolitöö leht</h1>";
  ?>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <h2>Eesti filmid</h2>
  <p>Praegu on andmebaasis järgmised filmid:</p>
  <?php
  // echo "Server: " .$serverHost .", kasutaja: " .$serverUsername;
  echo $filmInfoHTML;
  ?>

  <hr>
  <h2>Eesti filmid vanemad kui 50 aastat</h2>
  <p>Praegu on andmebaasis järgmised filmid:</p>
  <?php
  echo $filmInfo50;

   ?>

</body>
</html>
