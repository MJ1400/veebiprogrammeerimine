<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  $database = "if19_marten_vp";
  //SESSIOON
  require("classes/Session.class.php");
  //sessioon mis katkeb, kui brauser suletakse ja on kättesaadav ainult meie domeenis, meie lehel
  SessionManager::sessionStart("vp", 0, "/~martejur/", "greeny.cs.tlu.ee");
  
  $passwordError = null;
  $newpasswordError = null;
  $confirmpasswordError = null;
  $otherError = null;
  $samePasswordError = null;
  $notice = null;
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

    if(isset($_POST["submitPasswordChange"])) {
      if(isset($_POST["password"]) and !empty($_POST["password"]) and strlen($_POST["password"]) > 8 and isset($_POST["passwordNew"]) and !empty($_POST["passwordNew"])
      and strlen($_POST["passwordNew"]) > 8 and isset($_POST["confirmPasswordNew"]) and !empty($_POST["confirmPasswordNew"])
      and strlen($_POST["confirmPasswordNew"]) > 8 and $_POST["passwordNew"] == $_POST["confirmPasswordNew"] and $_POST["passwordNew"] !== $_POST["password"] ){
        $passwordNew = $_POST["passwordNew"];
        $password = $_POST["password"];
      } elseif(empty($_POST["password"])) {
        $passwordError = "Palun sisestage oma praegune salasõna!";
      } elseif(strlen($_POST["password"]) < 8) {
        $passwordError = "Sinu praegune salasõna peab olema rohkem kui 8 tähemärki!";
      } elseif(empty($_POST["passwordNew"])) {
        $newpasswordError = "Palun sisestage uus salasõna!";
      } elseif(strlen($_POST["passwordNew"]) < 8) {
        $newpasswordError = "Uus salasõna peab olema pikem kui 8 tähemärki!";
      } elseif(empty($_POST["confirmPasswordNew"])) {
        $confirmpasswordError = "Palun sisestage uue salasõna kordus!";
      } elseif(strlen($_POST["confirmPasswordNew"]) < 8) {
        $confirmpasswordError = "Sinu uue salasõna kordus peab olema rohkem kui 8 tähemärki!";
      } elseif($_POST["passwordNew"] !== $_POST["confirmPasswordNew"]) {
        $confirmpasswordError = "Uue salasõna kordus on erinev kui uus salasõna!";
      } elseif($_POST["passwordNew"] == $_POST["password"]) {
        $samePasswordError = "Uus salasõna ei tohi olla sama mis praegune!";
      } else {
        $otherError = "Mõni muu viga!";
      }
      if(empty($passwordError) and empty($newpasswordError) and empty($confirmpasswordError) and empty($otherError) and empty($samePasswordError)) {
        $notice = changePassword($_POST["passwordNew"], $_POST["password"]);

      } //kui kõik korras
    } //kui nuppu vajutad

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
    <p><a href="?logout=1">Logi välja!</a> | <a href="userprofile.php">Kasutajaprofiil</a> | <a href="messages.php">Sõnumid</a> | <a href="changepassword.php">Muuda salasõna</a> </p>
    <p>Tagasi <a href="home.php">avalehele</a></p>

    <hr>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <label>Praegune Salasõna:</label><br>
      <input name="password" type="password"><span><?php echo $passwordError; ?></span><br>
      <label>Uus Salasõna (min 8 tähemärki):</label><br>
  	  <input name="passwordNew" type="password"><span><?php echo $newpasswordError; ?></span><br>
  	  <label>Korrake uut salasõna:</label><br>
  	  <input name="confirmPasswordNew" type="password"><span><?php echo $confirmpasswordError; echo $samePasswordError;?></span><br>
  	  <input name="submitPasswordChange" type="submit" value="Muuda salasõna"><span><?php echo $notice; ?></span>
  	</form>
  	<hr>

  </body>
  </html>
