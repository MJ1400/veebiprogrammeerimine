<?php
  require("../../../configVP.php");
  require("functions_main.php");  
  require("functions_user.php");
  require("functions_news.php");
  $database = "if19_mirjam_pe_1";
  
  $notice = null;
  
  //SESSION
  require("classes/Session.class.php");
  //sessioon, mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehele
  SessionManager::sessionStart("vp", 0, "/~pettmir/", "greeny.cs.tlu.ee");
  
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
  
  //cookies (peab olema enne HTML elemente
  //nimi[väärtus, aegumine, path ehk kataloog, domeen, HTTPS, http only - kindlasti üle veebi]
  setcookie("vpname", $_SESSION["userFirstname"] .$_SESSION["userLastname"], time() + (86400 * 31), "/~pettmir/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
  
  if(isset($_COOKIE["vpname"])){
	echo "Küpsisest selgus nimi: " .$_COOKIE["vpname"];
  }else{
	echo "Küpsiseid ei leitud!";  
  }
  
  $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];
  $userid = $_SESSION["userID"];
  
  $news = readAllNews(5);
  
  //lisame lehe päise
  require("header.php");
 
?>


<body>
  <?php
    echo "<h1>" .$userName ." PHP #13 02.12.2019</h1>";
  ?>
  <p>Antud leht on loodud koolis õppetöö raames ja ei sisalda tõsiseltvõetavat sisu!</p>
  <hr>
  <p><a href="?Logout=1">Logi välja!</a></p>
  <ul>
    <li><a href="userprofile.php">Kasutajaprofiil</a></li>
	<li><a href="messages.php">Sõnumid</a></li>
	<li><a href="filminfo.php">Filmid</a></li>
	<li><a href="picupload.php">Piltide üleslaadimine</a></li>
	<li><a href="publicgallery.php">Avalike piltide galerii</a></li>
  </ul>
  
  <?php
  echo $news;
  //lisame lehe jaluse
  require("footer.php");
  ?>