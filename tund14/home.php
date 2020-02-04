 <?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_news.php");
  $database = "if19_marten_vp";

  $newsHTML = null;

  //SESSIOON
  require("classes/Session.class.php");
  //sessioon mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehel
  SessionManager::sessionStart("vp", 0, "/~martejur/", "greeny.cs.tlu.ee");




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

  //tegeleme küpsistega
  //setcookie peab olema enne <html> elementi!
  //nimi [väärtus, aegumine, path ehk kataloog, domeen(domain), secure ehk kas HTTPS, http-only - kindlasti üle veebi]
  setcookie("vpname", $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"], time() + (86400 * 30), "/~martejur/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);

  if(isset($_COOKIE["vpname"])) {
      echo "Küpsisest selgus nimi: " .$_COOKIE["vpname"];
  } else {
      echo "Küpsiseid ei leitud!";
  }



  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];

  $newsHTML = readNews();

	require("header.php");
?>


<body>
  <?php
    echo "<h1>" .$userName ."i" ." koolitöö leht</h1>";
  ?>
  <p>See leht on loodud koolis õppetöö raames
  ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <p><a href="?logout=1">Logi välja!</a> | <a href="userprofile.php">Kasutajaprofiil</a> | <a href="messages.php">Sõnumid</a> | <a href="changepassword.php">Muuda salasõna</a> | <a href="picupload.php">Piltide üleslaadimine</a> |
  <a href="showfilminfo.php">Filmid</a> | <a href="publicgallery.php">Pildigalerii</a> | <a href="addnews.php">Lisa uudiseid</a> </p>

  <?php echo $newsHTML ?>
</body>
</html>
