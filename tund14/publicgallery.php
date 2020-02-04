<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_pic.php");
  //võtan kasutusele oma klassi
  //require("classes/Test.classes.php");
  $database = "if19_marten_vp";

  //SESSIOON
  require("classes/Session.class.php");
  //sessioon mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehel
  SessionManager::sessionStart("vp", 0, "/~martejur/", "greeny.cs.tlu.ee");
  
  //kui pole sisseloginud
  if(!isset($_SESSION["userID"])){
  	  //siis jõuga sisselogimise lehele
  	  header("Location: page.php");
  	  exit();
    }

    //väljalogimine
    if(isset($_GET["logout"])){
  	  session_destroy();
  	  header("Location: page.php");
  	  exit();
    }

    $userName = $_SESSION["userFirstname"] ." " .$_SESSION["userLastname"];

    $notice = null;

    // piirid galerii lehel näidatava piltide arvu jaoks
    if(isset($_GET["privacy"])) {
      $privacyValue = $_GET["privacy"];
    } else {
      $privacyValue = 1;
    }


    $page = 1;
    $limit = 5;
    #$totalPics = countPublicImages(2);
    $totalPics = countPublicImages($privacyValue);
    if(!isset($_GET["page"]) or $_GET["page"] < 1) {
      $page = 1;
    } elseif (round($_GET["page"] - 1) * $limit > $totalPics) {
      $page = round($totalPics / $limit) - 1;
    } else {
      $page = $_GET["page"];
    }

    //radio button



    $publicThumbsHTML = readAllPublicPicsPage(1, $page, $limit);
    //$publicThumbsHTML = readAllPublicPics(2);
    $userThumbsHTML = readAllPublicPicsPage(2, $page, $limit);
    $privateThumbsHTML = readAllPublicPicsPage(3, $page, $limit);


  //<link rel="stylesheet" type="text/css" href="style/modal.css">
  $toScript = "\t" .'<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
  $toScript .= "\t" .'<script type="text/javascript" src="javascript/modal.js"></script>';
  require("header.php");
?>

  <body>
    <?php
      echo "<h1>" .$userName ."i" ." koolitöö leht</h1>";
    ?>
    <p>See leht on loodud koolis õppetöö raames
    ja ei sisalda tõsiseltvõetavat sisu!</p>
    <hr>
    <p><a href="?logout=1">Logi välja!</a> </p>
    <p>Tagasi <a href="home.php">avalehele</a></p>
    <hr>
    <h2> Pildigalerii </h2>
    <!-- Teeme moodalakna, W3Schools eeskuju-->
    <div id="myModal" class="modal">
	<!--Sulgemisnupp-->
	    <span id="close" class="close">&times;</span>
      <span id="edit" class="edit">&amp;</span>
	     <!--pildikoht-->
	   <img id="modalImg" class="modal-content">
	   <div id="caption"></div>

     <div id="rating" class="modalcaption">
   		<label><input id="rate1" name="rating" type="radio" value="1">1</label>
   		<label><input id="rate2" name="rating" type="radio" value="2">2</label>
   		<label><input id="rate3" name="rating" type="radio" value="3">3</label>
   		<label><input id="rate4" name="rating" type="radio" value="4">4</label>
   		<label><input id="rate5" name="rating" type="radio" value="5">5</label>
   		<input type="button" value="Salvesta hinnang" id="storeRating">
   		<br>
   		<span id="avgRating"></span>
   	</div>

  <!-- Pildi altteksti ja privaatsuse muutmise div -->
  <div id="changeAlttext" class="modalcaption">
    <label><input id="alttext" name="alttext" type="text">Muuda pildikirjeldust</label> <br>
    <label><input type="radio" name="privacy" value="1" id="privacy1"> Avalikud pildid <label><br>
    <label> <input type="radio" name="privacy" value="2" id="privacy2"> Kasutajate pildid <label> <br>
    <label><input type="radio" name="privacy" value="3" id="privacy3"> Privaatsed pildid<label> <br>
    <input type="button" value="Salvesta kirjeldus ja privaatsus" id="savePicChanges">
  </div>

  </div>

    <p>
    <form method="get">
    <input type="radio" name="privacy" value="1" <?php if(isset($_GET["privacy"]) && $_GET["privacy"] == 1) { echo 'checked = "checked"';} else { echo'checked = "checked"';}?>> Avalikud pildid <br>
    <input type="radio" name="privacy" value="2" <?php if(isset($_GET["privacy"]) && $_GET["privacy"] == 2) { echo 'checked = "checked"';}?>> Kasutajate pildid <br>
    <input type="radio" name="privacy" value="3" <?php if(isset($_GET["privacy"]) && $_GET["privacy"] == 3) { echo 'checked = "checked"';}?>> Privaatsed pildid <br>
    <br>
    <input type="submit" name="submitPrivacy" value="Näita pilte">
    </form>
    <hr>
    </p>
    <p>
      <!-- <a href="?page=1">Leht 1 </a> <a href="?page=2">Leht 2 </a> -->
    <?php
    if($page > 1){
      echo '<a href="?page=' .($page - 1) .'&privacy=' .$privacyValue .'">Eelmine leht</a> - ' ."\n";
    }else{
      echo "<span>Eelmine leht</span> - \n";
    }
    if($page * $limit < $totalPics) {
      echo '<a href="?page=' .($page + 1) .'&privacy=' .$privacyValue .'">Järgmine leht</a>' ."\n";
    }else{
      echo "<span>Järgmine leht</span> \n";
    }
    ?>
    </p>
    <div id="gallery">
    <?php
      if($privacyValue == 1){
      echo $publicThumbsHTML;
    } elseif($privacyValue == 2) {
      echo $userThumbsHTML;
    } elseif($privacyValue == 3) {
      echo $privateThumbsHTML;
    }
     ?>
  </div>
  </body>
  </html>
