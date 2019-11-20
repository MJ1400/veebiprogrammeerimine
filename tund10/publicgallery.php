<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_pic.php");
  //võtan kasutusele oma klassi
  //require("classes/Test.classes.php");
  $database = "if19_marten_vp";
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
      if($page > 1) {
        echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ' ."\n";
      } else {
        echo "<span>Eelmine leht</span> | \n";
      }

      if($page * $limit < $totalPics) {
        echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>' ."\n";
      } else {
        echo "<span>Järgmine leht</span> | \n";
      }
     ?>
    </p>
    <?php
      if($privacyValue == 1){
      echo $publicThumbsHTML;
    } elseif($privacyValue == 2) {
      echo $userThumbsHTML;
    } elseif($privacyValue == 3) {
      echo $privateThumbsHTML;
    }
     ?>
	  <hr>
  </body>
  </html>
