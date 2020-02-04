<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_weight.php");
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
    $messagesHTML = null;


    if(isset($_POST["submitWeight"])){
      if(isset($_POST["weight"]) and !empty($_POST["weight"])) {
        $notice = saveWeight($_POST["weight"]);
      }

    }

    // $messagesHTML = readAllMessages();


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

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  	  <label>Tänane kaal</label><br>
  	  <input type="number" name="weight" value="75" min="2" step="0.1">
  	  <br>
  	  <input name="submitWeight" type="submit" value="Salvesta kaal"><span><?php echo $notice; ?></span>
  	</form>
    <hr>


  </body>
  </html>
