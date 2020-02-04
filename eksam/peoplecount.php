<?php

  require("../../../config_vp2019.php");
  require("functions_main.php");
  require("functions_user.php");
  require("functions_peoplecount.php");
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
    $genderError = null;
    $gender = null;
    $occupation = null;
    $occupationError = null;
    $entrantsError = null;
    $leaversError = null;
    $entrants = null;
    $leavers = null;
    $peopleCountHTML = null;
    $totalcount = null;
    $maxPeopleCount = null;
    $femaleStudentCountHTML = null;
    $maleStudentCountHTML = null;
    $femaleTeacherCountHTML = null;
    $maleTeacherCountHTML = null;
    $femaleCountHTML = null;
    $maleCountHTML = null;

    $peopleCountHTML = readAllPeopleCount();
    $femaleStudentCountHTML = readPeopleCount(2, 2);
    $maleStudentCountHTML = readPeopleCount(1, 2);
    $femaleTeacherCountHTML = readPeopleCount(2, 1);
    $maleTeacherCountHTML = readPeopleCount(1, 1);








    if(isset($_POST["submitPeopleCount"])){
      if(isset($_POST["gender"]) and !empty($_POST["gender"])){
    	  $gender = test_input($_POST["gender"]);
      } else {
    	  $genderError = "Palun sisestage sugu!";
      }

      if(isset($_POST["occupation"]) and !empty($_POST["occupation"])){
    	  $occupation = test_input($_POST["occupation"]);
      } else {
    	  $occupationError = "Palun sisestage okupatsioon!";
      }


      $entrants = test_input($_POST["entrants"]);

      $leavers = test_input($_POST["leavers"]);

      $totalcount = $entrants - $leavers;

      // kui kõik on korras, salvestame
    if(empty($genderError) and empty($occupationError) and empty($entrantsError)) {
      $notice = storePeopleCount($entrants, $leavers, $gender, $occupation, $totalcount);
      echo "<meta http-equiv='refresh' content='0'>";
      } //kui kõik korras
    }

    require("header.php");
  ?>


  <body>
    <?php
      echo "<h1>" ."Marten Jürgensi" ." koolitöö leht</h1>";
    ?>
    <p>Inimeste hulk hoones</p>
    <hr>
  <p><a href="?logout=1">Logi välja!</a> </p>
    <p>Tagasi <a href="home.php">avalehele</a></p>

    <hr>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  	  <label>Sisenejate arv</label><br>
  	  <input type="number" name="entrants" value="0" min="0" step="1"><br>
      <br>
      <label>Väljujate arv</label><br>
      <input type="number" name="leavers" value="0" min="0" step="1"><br>
      <br>
          <input type="radio" name="gender" value="2"><label>Naine</label>
      	  <input type="radio" name="gender" value="1"><label>Mees</label><br>
      	  <span><?php echo $genderError; ?></span><br>
          <input type="radio" name="occupation" value="2"><label>Üliõpilane</label>
          <input type="radio" name="occupation" value="1"><label>Õppejõud</label><br>
          <span><?php echo $occupationError; ?></span> <br>
          <input name="submitPeopleCount" type="submit" value="Salvesta inimeste arv"><span><?php echo $notice; ?></span>

      </form>
      <br>
      <hr>
      <?php
      echo "<p> Praegu on hoones: " .$peopleCountHTML ." inimest</p>";
      echo "<p> Maksimaalselt on hoones viibinud: " ." inimest</p>";
      echo "<p> Praegu viibib hoones: " .$femaleStudentCountHTML ." naissoost üliõpilast</p>";
      echo "<p> Praegu viibib hoones: " .$maleStudentCountHTML ." meessoost üliõpilast</p>";
      echo "<p> Praegu viibib hoones: " .$femaleTeacherCountHTML ." naissoost õpetajat</p>";
      echo "<p> Praegu viibib hoones: " .$maleTeacherCountHTML ." meessoost õpetajat</p>";


      ?>







  </body>
  </html>
