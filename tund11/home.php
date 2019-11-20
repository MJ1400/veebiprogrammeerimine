<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  $database = "if19_marten_vp";
  //kui pole sisseloginud
  if(!isset($_SESSION["userFirstname"])) {
    //siis jõuga sisselogimise lehele
    header("Location: page.php");
    exit();
  }
  //v2ljalogimine
  if(isset($_GET["logout"])){
    session_destroy();
    header("Location: page.php");
    exit();

  }
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];

	require("header.php");
?>


<body>
  <?php
    echo "<h1>" .$userName ."i" ." koolitöö leht</h1>";
  ?>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <p><a href="?logout=1">Logi välja!</a> | <a href="userprofile.php">Kasutajaprofiil</a> | <a href="messages.php">Sõnumid</a> | <a href="changepassword.php">Muuda salasõna</a> | <a href="picupload.php">Piltide üleslaadimine</a> | <a href="showfilminfo.php">Filmid</a> | <a href="publicgallery.php">Pildigalerii</a> </p>
</body>
</html>
