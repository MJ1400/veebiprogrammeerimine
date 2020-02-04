<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_message.php");
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


    if(isset($_POST["submitMessage"])){
      if(isset($_POST["message"]) and !empty($_POST["message"])) {
        $notice = storeMessage(test_input($_POST["message"]));
      }

    }

    // $messagesHTML = readAllMessages();
    $messagesHTML = readMyMessages();


    require("header.php");
  ?>


  <body>
    <?php
      echo "<h1>" .$userName ."i" ." koolitöö leht</h1>";
    ?>
    <p>See leht on loodud koolis õppetöö raames
    ja ei sisalda tõsiseltvõetavat sisu!</p>
    <hr>
  <p><a href="?logout=1">Logi välja!</a> | <a href="userprofile.php">Kasutajaprofiil</a> | <a href="messages.php">Sõnumid</a> | <a href="changepassword.php">Muuda salasõna</a> </p>
    <p>Tagasi <a href="home.php">avalehele</a></p>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  	  <label>Minu sõnum</label><br>
  	  <textarea rows="5" cols="50" name="message" placeholder="Lisa siia oma sõnum ..."></textarea>
  	  <br>
  	  <input name="submitMessage" type="submit" value="Salvesta sõnum"><span><?php echo $notice; ?></span>
  	</form>
    <hr>
    <h2>Senised sõnumid</h2>
    <?php
      echo $messagesHTML;
     ?>

  </body>
  </html>
